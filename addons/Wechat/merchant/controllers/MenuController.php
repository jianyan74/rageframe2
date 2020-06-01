<?php

namespace addons\Wechat\merchant\controllers;

use Yii;
use yii\data\Pagination;
use addons\Wechat\common\models\Menu;
use common\helpers\ResultHelper;


/**
 * 自定义菜单
 *
 * Class MenuController
 * @package addons\Wechat\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MenuController extends BaseController
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
        $data = Menu::find()
            ->where(['type' => $type])
            ->andWhere(['merchant_id' => $this->getMerchantId()]);
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

        if (Yii::$app->request->isPost) {
            $postInfo = Yii::$app->request->post();
            $model = $this->findModel($postInfo['id']);
            $model->attributes = $postInfo;

            if (!isset($postInfo['list'])) {
                return ResultHelper::json(422, '请添加菜单');
            }

            try {
                Yii::$app->wechatService->menu->createSave($model, $postInfo['list']);
                return ResultHelper::json(200, "修改成功");
            } catch (\Exception $e) {
                return ResultHelper::json(422, $e->getMessage());
            }
        }

        return $this->render('edit', [
            'model' => $model,
            'menuTypes' => Menu::$menuTypes,
            'type' => $type,
            'fansTags' => Yii::$app->wechatService->fansTags->getList()
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
        if ($model->delete()) {
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
        if ($id) {
            $model = $this->findModel($id);
            $model->save();

            // 创建微信菜单
            $createReturn = Yii::$app->wechat->app->menu->create($model->menu_data);
            // 解析微信接口是否报错
            if ($error = Yii::$app->debris->getWechatError($createReturn, false)) {
                return $this->message($error, $this->redirect(['index']), 'error');
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * 同步菜单
     *
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function actionSync()
    {
        try {
            Yii::$app->wechatService->menu->sync();
            return ResultHelper::json(200, '同步菜单成功');
        } catch (\Exception $e) {
            return ResultHelper::json(422, $e->getMessage());
        }
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return array|Menu|null|\yii\db\ActiveRecord
     */
    protected function findModel($id)
    {
        if (empty($id) || empty(($model = Menu::findOne(['id' => $id, 'merchant_id' => $this->getMerchantId()])))) {
            $model = new Menu;
            return $model->loadDefaultValues();
        }

        return $model;
    }
}