<?php
namespace backend\modules\wechat\controllers;

use Yii;
use yii\data\Pagination;
use common\models\wechat\Menu;
use common\helpers\ResultDataHelper;
use common\models\wechat\FansTags;

/**
 * 自定义菜单
 *
 * Class MenuController
 * @package backend\modules\wechat\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MenuController extends WController
{
    /**
     * 自定义菜单首页
     *
     * @return string
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionIndex()
    {
        $type = Yii::$app->request->get('type', Menu::TYPE_CUSTOM);
        $data = Menu::find()->where(['type' => $type]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('status desc, id desc')
            ->limit($pages->limit)
            ->all();

        // 查询下菜单
        !$models && Yii::$app->debris->getWechatError(Yii::$app->wechat->app->menu->current());

        return $this->render('index', [
            'pages' => $pages,
            'models' => $models,
            'type' => $type,
            'types' => Menu::$typeExplain,
        ]);
    }

    /**
     * 创建菜单
     *
     * @return array|string
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $type = $request->get('type');
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost)
        {
            $postInfo = Yii::$app->request->post();
            $model = $this->findModel($postInfo['id']);
            $model->attributes = $postInfo;

            if (!isset($postInfo['list']))
            {
                return ResultDataHelper::json(422, '请添加菜单');
            }

            $buttons = [];
            foreach ($postInfo['list'] as &$button)
            {
                $arr = [];
                // 判断是否有子菜单
                if (isset($button['sub_button']))
                {
                    $arr['name'] = $button['name'];
                    foreach ($button['sub_button'] as &$sub)
                    {
                        $sub_button = Menu::mergeButton($sub);
                        $sub_button['name'] = $sub['name'];
                        $sub_button['type'] = $sub['type'];
                        $arr['sub_button'][] = $sub_button;
                    }
                }
                else
                {
                    $arr = Menu::mergeButton($button);
                    $arr['name'] = $button['name'];
                    $arr['type'] = $button['type'];
                }

                $buttons[] = $arr;
            }

            $model->menu_data = serialize($buttons);
            // 判断写入是否成功
            if (!$model->validate())
            {
                return ResultDataHelper::json(422, $this->analyErr($model->getFirstErrors()));
            }

            // 个性化菜单
            if ($model->type == Menu::TYPE_INDIVIDUATION)
            {
                $matchRule = [
                    "tag_id" => $model->tag_id,
                    "sex" => $model->sex,
                    "country" => "中国",
                    "province" => trim($model->province),
                    "client_platform_type" => $model->client_platform_type,
                    "language" => $model->language,
                    "city" => trim($model->city),
                ];

                // 创建自定义菜单
                $menuResult = Yii::$app->wechat->app->menu->create($buttons, $matchRule);
                if ($error = Yii::$app->debris->getWechatError($menuResult, false))
                {
                    return ResultDataHelper::json(422, $error);
                }

                $model->menu_id = $menuResult['menuid'];
                $model->save();

                return ResultDataHelper::json(200, "修改成功");
            }

            // 验证微信报错
            if ($error = Yii::$app->debris->getWechatError(Yii::$app->wechat->app->menu->create($buttons), false))
            {
                return ResultDataHelper::json(422, $error);
            }

            // 判断写入是否成功
            if (!$model->save())
            {
                return ResultDataHelper::json(422, $this->analyErr($model->getFirstErrors()));
            }

            return ResultDataHelper::json(200, "修改成功");
        }

        return $this->render('edit', [
            'model' => $model,
            'menuTypes' => Menu::$menuTypes,
            'type' => $type,
            'fansTags' => FansTags::getList()
        ]);
    }

    /**
     * 删除菜单
     *
     * @param $id
     * @param $type
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id, $type)
    {
        $model = $this->findModel($id);
        if ($model->delete())
        {
            // 个性化菜单删除
            !empty($model['menu_id']) && Yii::$app->wechat->app->menu->delete($model['menu_id']);
            return $this->message("删除成功", $this->redirect(['index', 'type' => $type]));
        }

        return $this->message("删除失败", $this->redirect(['index', 'type' => $type]), 'error');
    }

    /**
     * 替换菜单为当前的菜单
     *
     * @param $id
     * @return mixed|\yii\web\Response
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionSave($id)
    {
        if ($id)
        {
            $model = $this->findModel($id);
            $model->save();

            // 创建微信菜单
            $createReturn = Yii::$app->wechat->app->menu->create(unserialize($model->menu_data));
            // 解析微信接口是否报错
            if ($error = Yii::$app->debris->getWechatError($createReturn, false))
            {
                return $this->message($error, $this->redirect(['index']), 'error');
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * 同步菜单
     *
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionSync()
    {
        // 获取菜单列表
        $list = Yii::$app->wechat->app->menu->list();
        // 解析微信接口是否报错
        if ($error = Yii::$app->debris->getWechatError($list, false))
        {
            return ResultDataHelper::json(404, $error);
        }

        // 开始获取自定义菜单同步
        if (!empty($list['menu']))
        {
            $model = new Menu;
            $model->title = "默认菜单";
            $model = $model->loadDefaultValues();
            $model->menu_data = serialize($list['menu']['button']);
            $model->menu_id = isset($list['menu']['menuid']) ? $list['menu']['menuid'] : '';
            $model->save();
        }

        // 个性化菜单
        if (!empty($list['conditionalmenu']))
        {
            foreach ($list['conditionalmenu'] as $menu)
            {
                if (!($model = Menu::findOne(['menu_id' => $menu['menuid']])))
                {
                    $model = new Menu;
                    $model = $model->loadDefaultValues();
                }

                $model->title = "个性化菜单";
                $model->attributes = $menu['matchrule'];
                $model->type = Menu::TYPE_INDIVIDUATION;
                $model->tag_id = isset($menu['group_id']) ? $menu['group_id'] : '';
                $model->menu_data = serialize($menu['button']);
                $model->menu_id = $menu['menuid'];
                $model->save();
            }
        }

        return ResultDataHelper::json(200, '同步菜单成功');
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return $this|Menu|static
     */
    protected function findModel($id)
    {
        if (empty($id) || empty(($model = Menu::findOne($id))))
        {
            $model = new Menu;
            return $model->loadDefaultValues();
        }

        return $model;
    }
}