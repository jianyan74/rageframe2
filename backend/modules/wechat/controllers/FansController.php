<?php
namespace backend\modules\wechat\controllers;

use common\models\wechat\Attachment;
use common\models\wechat\AttachmentNews;
use yii;
use yii\data\Pagination;
use yii\web\Response;
use common\helpers\ArrayHelper;
use common\helpers\ResultDataHelper;
use common\models\wechat\Fans;
use common\models\wechat\FansTags;
use common\models\wechat\FansTagMap;
use common\models\wechat\Rule;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Video;
use EasyWeChat\Kernel\Messages\Voice;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;

/**
 * Class FansController
 * @package backend\modules\wechat\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class FansController extends WController
{
    /**
     * 粉丝首页
     *
     * @return string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws yii\web\UnprocessableEntityHttpException
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

        $pages  = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
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
     * 发送消息
     *
     * @param $id
     * @return array|string
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws yii\web\UnprocessableEntityHttpException
     */
    public function actionSendMessage($openid)
    {
        $model = Fans::findOne(['openid' => $openid]);
        if (Yii::$app->request->isPost)
        {
            $data = Yii::$app->request->post();
            switch ($data['type'])
            {
                // 文字回复
                case  1 :
                    $message =  new Text($data['content']);
                    break;
                // 图片回复
                case  2 :
                    $message = new Image($data['images']);
                    break;
                // 图文回复
                case  3 :
                    $new = AttachmentNews::find()->where(['sort' => 0, 'attachment_id' => $data['news']])->one();
                    $newsList[] = new NewsItem([
                        'title' => $new['title'],
                        'description' => $new['digest'],
                        'url' => $new['media_url'],
                        'image' => $new['thumb_url'],
                    ]);

                    $message = new News($newsList);
                    break;
                // 视频回复
                case 4 :
                    $message = new Video($data['video'], [
                        'title' => $data['title'],
                        'description' => $data['description'],
                    ]);
                    break;
                // 语音回复
                case 5 :
                    $message = new Voice($data['voice']);
                    break;
                default :
                    return ResultDataHelper::json(422, '找不到发送类型');
                    break;
            }

            $result = Yii::$app->wechat->app->customer_service->message($message)->to($model['openid'])->send();
            if ($error = Yii::$app->debris->getWechatError($result, false))
            {
                return ResultDataHelper::json(422, $error);
            }

            return ResultDataHelper::json(200, '发送成功');
        }

        return $this->renderAjax('send-message',[
            'model' => $model
        ]);
    }

    /**
     * 贴标签
     *
     * @param $fan_id
     * @return string|Response
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws yii\web\UnprocessableEntityHttpException
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
                    Yii::$app->wechat->app->user_tag->tagUsers([$fans['openid']], $tag_id);
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
                    Yii::$app->wechat->app->user_tag->untagUsers([$fans['openid']], $tag_id);
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
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws yii\db\Exception
     */
    public function actionGetAllFans()
    {
        $request = Yii::$app->request;
        $next_openid = $request->get('next_openid', '');

        // 获取全部列表
        $fans_list = Yii::$app->wechat->app->user->list();
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
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
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