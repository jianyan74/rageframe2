<?php

namespace backend\modules\wechat\controllers;

use Yii;
use yii\helpers\Json;
use backend\controllers\BaseController;
use backend\modules\wechat\forms\HistoryForm;

/**
 * 微信参数设置
 *
 * Class SettingController
 * @package backend\modules\wechat\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SettingController extends BaseController
{
    /**
     * @return mixed|string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionHistoryStat()
    {
        $model = new HistoryForm();
        $model->attributes = Yii::$app->services->wechatSetting->getByFieldName('history');
        if (Yii::$app->request->isPost && $model->validate()) {
            try {
                Yii::$app->services->wechatSetting->setByFieldName('history', Yii::$app->request->post('HistoryForm'));
                return $this->message('修改成功', $this->redirect(['history-stat']));
            } catch (\Exception $e) {
                return $this->message($e->getMessage(), $this->redirect(['history-stat']), 'error');
            }
        }

        return $this->render('history-stat', [
            'model' => $model,
        ]);
    }

    /**
     * @return mixed|string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionSpecialMessage()
    {
        if (Yii::$app->request->isPost) {
            try {
                Yii::$app->services->wechatSetting->setByFieldName('special', Yii::$app->request->post('setting'));
                return $this->message('修改成功', $this->redirect(['special-message']));
            } catch (\Exception $e) {
                return $this->message($e->getMessage(), $this->redirect(['special-message']), 'error');
            }
        }

        return $this->render('special-message', [
            'list' => Yii::$app->services->wechatSetting->specialConfig(),
        ]);
    }
}