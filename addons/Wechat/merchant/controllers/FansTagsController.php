<?php

namespace addons\Wechat\merchant\controllers;

use Yii;
use addons\Wechat\common\models\FansTagMap;


/**
 * 粉丝标签
 *
 * Class FansTagsController
 * @package addons\Wechat\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class FansTagsController extends BaseController
{
    /**
     * @return mixed|string
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws yii\web\UnprocessableEntityHttpException
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isPost) {
            try {
                $createData = Yii::$app->request->post('createData', []);
                $updateData = Yii::$app->request->post('updateData', []);
                Yii::$app->wechatService->fansTags->syncSave($createData, $updateData);

                return $this->message("保存成功", $this->redirect(['index']));
            } catch (\Exception $e) {
                return $this->message($e->getMessage(), $this->redirect(['index']), 'error');
            }
        }

        return $this->render('index', [
            'tags' => Yii::$app->wechatService->fansTags->getList()
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws yii\web\UnprocessableEntityHttpException
     */
    public function actionDelete($id)
    {
        $res = Yii::$app->wechat->app->user_tag->delete($id);
        if ($error = Yii::$app->debris->getWechatError($res, false)) {
            FansTagMap::deleteAll(['tag_id' => $id]);
            return $this->message($error, $this->redirect(['index']), 'error');
        }

        Yii::$app->wechatService->fansTags->getList(true);
        return $this->message('删除成功', $this->redirect(['index']));
    }

    /**
     * 同步标签
     *
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws yii\web\UnprocessableEntityHttpException
     */
    public function actionSynchro()
    {
        Yii::$app->wechatService->fansTags->getList(true);
        return $this->message("粉丝同步成功", $this->redirect(['index']));
    }
}