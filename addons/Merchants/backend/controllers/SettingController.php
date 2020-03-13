<?php

namespace addons\Merchants\backend\controllers;

use Yii;
use common\helpers\ArrayHelper;
use common\interfaces\AddonsSetting;
use addons\Merchants\common\models\SettingForm;

/**
 * 参数设置
 *
 * Class SettingController
 * @package addons\TinyDistribution\merchant\controllers
 */
class SettingController extends BaseController implements AddonsSetting
{
    /**
     * @return mixed|string
     */
    public function actionDisplay()
    {
        $request = Yii::$app->request;
        $model = new SettingForm();
        $model->attributes = $this->getConfig();
        if ($model->load($request->post()) && $model->validate()) {
            $this->setConfig(ArrayHelper::toArray($model));
            return $this->message('修改成功', $this->redirect(['display']));
        }

        return $this->render('display',[
            'model' => $model,
        ]);
    }
}