<?php
namespace backend\modules\wechat\controllers;

use Yii;
use common\models\wechat\Setting;
use backend\modules\wechat\models\HistoryForm;

/**
 * 微信参数设置
 *
 * Class SettingController
 * @package backend\modules\wechat\controllers
 */
class SettingController extends WController
{
    /**
     * 参数设置
     *
     * @return string|yii\web\Response
     */
    public function actionHistoryStat()
    {
        $model = new HistoryForm();
        $model->attributes = Setting::getData('history');
        if (Yii::$app->request->isPost && $model->validate())
        {
            if (Setting::setData('history', Yii::$app->request->post('HistoryForm')))
            {
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
        if (Yii::$app->request->isPost)
        {
            if (Setting::setData('special', Yii::$app->request->post('setting')))
            {
                return $this->message('修改成功', $this->redirect(['special-message']));
            }
        }

        return $this->render('special-message',[
            'list' => Setting::specialConfig(),
        ]);
    }
}