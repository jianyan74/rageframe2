<?php
namespace backend\modules\wechat\controllers;

use yii;
use yii\data\Pagination;
use yii\web\Response;
use common\helpers\ArrayHelper;
use common\helpers\ResultDataHelper;
use common\models\wechat\Fans;
use common\models\wechat\FansTags;
use common\models\wechat\FansTagMap;

/**
 * Class FansController
 * @package backend\modules\wechat\controllers
 */
class FansController extends WController
{
    /**
     * 粉丝首页
     *
     * @return string
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $follow = $request->get('follow', 1);
        $tag_id = $request->get('tag_id', null);
        $keyword = $request->get('keyword', null);

        $where = [];
        if ($keyword)
        {
            $where = ['or', ['like', 'f.openid', $keyword], ['like', 'f.nickname', $keyword]];
        }

        // 关联角色查询
        $data = Fans::find()
            ->where($where)
            ->alias('f')
            ->andWhere(['f.follow' => $follow])
            ->joinWith("tags AS t", true, 'LEFT JOIN')
            ->filterWhere(['t.tag_id' => $tag_id]);

        $pages  = new Pagination(['totalCount' =>$data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->with('tags','member')
            ->orderBy('followtime desc, unfollowtime desc')
            ->limit($pages->limit)
            ->all();

        // 全部标签
        $tags = FansTags::getList();

        return $this->render('index',[
            'models' => $models,
            'pages' => $pages,
            'follow' => $follow,
            'keyword' => $keyword,
            'tag_id' => $tag_id,
            'all_fans' => Fans::getCountFollowFans(),
            'fansTags' => $tags,
            'allTag' => ArrayHelper::map($tags, 'id', 'name'),
        ]);
    }

    /**
     * 粉丝详情
     *
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $model = Fans::findOne($id);

        return $this->renderAjax('view',[
            'model' => $model
        ]);
    }

    /**
     * 粉丝详情
     *
     * @param $id
     * @return array|string
     */
    public function actionSendMessage($id)
    {
        $model = Fans::findOne($id);

        if (Yii::$app->request->isAjax)
        {
            $message = Yii::$app->request->get('content');
            $result = $this->app->customer_service->message($message)->to($model['openid'])->send();
            if ($error = Yii::$app->debris->getWechatError($result, false))
            {
                return ResultDataHelper::json(422, $error);
            }

            return ResultDataHelper::json(200, '发送成功');
        }

        return $this->render('send-message',[
            'model' => $model
        ]);
    }

    /**
     * 贴标签
     * @param $fan_id
     * @return string|Response
     */
    public function actionMoveTag($fan_id)
    {
        $fans = Fans::find()
            ->where(['id' => $fan_id])
            ->with('tags')
            ->asArray()
            ->one();

        // 用户当前标签
        $fansTags = [];
        foreach ($fans['tags'] as $value)
        {
            $fansTags[] = $value['tag_id'];
        }

        if(Yii::$app->request->isPost)
        {
            $tags = Yii::$app->request->post('tag_id',[]);

            FansTagMap::deleteAll(['fans_id' => $fan_id]);
            // 添加标签
            foreach ($tags as $tag_id)
            {
                if (!in_array($tag_id, $fansTags))
                {
                    $this->app->user_tag->tagUsers([$fans['openid']], $tag_id);
                }

                $model = new FansTagMap();
                $model->fans_id = $fan_id;
                $model->tag_id = $tag_id;
                $model->save();
            }

            // 移除标签
            foreach ($fansTags as $tag_id)
            {
                if (!in_array($tag_id, $tags))
                {
                    $this->app->user_tag->untagUsers([$fans['openid']], $tag_id);
                }
            }

            // 更新标签
            FansTags::updateList();

            return $this->redirect(['index']);
        }

        return $this->renderAjax('move-tag', [
            'tags' => FansTags::getList(),
            'fansTags' => $fansTags,
        ]);
    }

    /**
     * 获取全部粉丝
     *
     * @return array
     * @throws yii\db\Exception
     */
    public function actionGetAllFans()
    {
        $request = Yii::$app->request;
        $next_openid = $request->get('next_openid', '');

        // 获取全部列表
        $fans_list = $this->app->user->list();
        $fans_count = $fans_list['total'];

        // 设置关注全部为为关注
        empty($next_openid) && Fans::updateAll(['follow' => Fans::FOLLOW_OFF ]);

        $total_page = ceil($fans_count / 500);
        for ($i = 0; $i < $total_page; $i++)
        {
            $fans = array_slice($fans_list['data']['openid'], $i * 500, 500);
            // 系统内的粉丝
            $system_fans = Fans::find()
                ->where(['in', 'openid', $fans])
                ->select('openid')
                ->asArray()
                ->all();

            $new_system_fans = [];
            foreach ($system_fans as $li)
            {
                $new_system_fans[$li['openid']] = $li;
            }

            $add_fans = [];
            foreach($fans as $openid)
            {
                if (empty($new_system_fans) || empty($new_system_fans[$openid]))
                {
                    $add_fans[] = [0, $openid, Fans::FOLLOW_ON, 0, '', time(), time()];
                }
            }

            if (!empty($add_fans))
            {
                // 批量插入数据
                $field = ['member_id', 'openid', 'follow', 'followtime', 'tag', 'created_at', 'updated_at'];
                Yii::$app->db->createCommand()->batchInsert(Fans::tableName(), $field, $add_fans)->execute();
            }

            // 更新当前粉丝为关注
            Fans::updateAll(['follow' => 1 ], ['in', 'openid', $fans]);
        }

        return ResultDataHelper::json(200, '同步粉丝openid完成', [
            'total' => $fans_list['total'],
            'count' => !empty($fans_list['data']['openid']) ? $fans_count : 0,
            'next_openid' => $fans_list['next_openid'],
        ]);
    }

    /**
     * 开始同步粉丝数据
     *
     * @return array
     * @throws yii\db\Exception
     */
    public function actionSync()
    {
        $request = Yii::$app->request;
        $type = $request->post('type', null) == 'all' ? 'all' : 'check';
        $page = $request->post('page', 0);

        // 全部同步
        if ($type == 'all')
        {
            $limit = 10;
            $offset = $limit * $page;

            // 关联角色查询
            $data = Fans::find()->where(['follow' => Fans::FOLLOW_ON]);
            $models = $data->offset($offset)
                ->orderBy('id desc')
                ->limit($limit)
                ->asArray()
                ->all();

            if(!empty($models))
            {
                // 同步粉丝信息
                foreach ($models as $fans)
                {
                    Fans::sync($fans['openid']);
                }

                return ResultDataHelper::json(200, '同步完成', [
                    'page' => $page + 1
                ]);
            }
        }

        // 选中同步
        if ($type == 'check')
        {
            $openids = $request->post('openids');
            if (empty($openids) || !is_array($openids))
            {
                return ResultDataHelper::json(404, '请选择粉丝');
            }

            // 系统内的粉丝
            $sync_fans = Fans::find()
                ->where(['in', 'openid', $openids])
                ->asArray()
                ->all();

            if (!empty($sync_fans))
            {
                // 同步粉丝信息
                foreach ($sync_fans as $fans)
                {
                    Fans::sync($fans['openid']);
                }
            }
        }

        return ResultDataHelper::json(200, '同步完成');
    }
}