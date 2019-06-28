<?php
namespace backend\modules\wechat\controllers;

use Yii;
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
     * 参数设置
     *
     * @return string|yii\web\Response
     */
    public function actionHistoryStat()
    {
        $model = new HistoryForm();
        $model->attributes = Yii::$app->services->wechatSetting->getByFieldName('history');
        if (Yii::$app->request->isPost && $model->validate()) {
            if (Yii::$app->services->wechatSetting->setByFieldName('history', Yii::$app->request->post('HistoryForm'))) {
                return $this->message('修改成功', $this->redirect(['history-stat']));
            }
        }

        return $this->render('history-stat',[
            'model' => $model,
        ]);
    }

    /**
     * 特殊消息回复
     *
     * @return string|yii\web\Response
     */
    public function actionSpecialMessage()
    {
        if (Yii::$app->request->isPost && Yii::$app->services->wechatSetting->setByFieldName('special', Yii::$app->request->post('setting'))) {
            return $this->message('修改成功', $this->redirect(['special-message']));
        }

        return $this->render('special-message',[
            'list' => Yii::$app->services->wechatSetting->specialConfig(),
        ]);
    }
}