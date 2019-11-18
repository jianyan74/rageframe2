<?php

namespace backend\modules\base\controllers;

use Yii;
use common\helpers\ArrayHelper;
use common\models\backend\NotifySubscriptionConfig;
use common\enums\SubscriptionActionEnum;
use common\enums\SubscriptionAlertTypeEnum;
use backend\modules\base\forms\NotifySubscriptionActionForm;
use backend\controllers\BaseController;

/**
 * Class NotifySubscriptionConfigController
 * @package backend\modules\base\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class NotifySubscriptionConfigController extends BaseController
{
    /**
     * @return mixed|string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $model = new NotifySubscriptionActionForm();
        $model->attributes = $this->getConfigModel()->action;

        if (Yii::$app->request->isPost) {
            $newData = Yii::$app->request->post($model->formName(), []);
            $data = Yii::$app->services->backendNotifySubscriptionConfig->getData($newData);

            if (($model->attributes = $data) && $model->validate()) {
                $data = ArrayHelper::toArray($model);
                $configModel = $this->getConfigModel();
                $configModel->action = $data;
                $configModel->save();

                return $this->message('修改成功', $this->redirect(['index']));
            }

            return $this->message('修改失败', $this->redirect(['index']), 'error');
        }

        return $this->render('index', [
            'model' => $model,
            'typeExplain' => SubscriptionAlertTypeEnum::$listExplain,
            'valueExplain' => SubscriptionActionEnum::$listExplain,
        ]);
    }

    /**
     * @return array|NotifySubscriptionConfig|\yii\db\ActiveRecord|null
     */
    protected function getConfigModel()
    {
        $config = NotifySubscriptionConfig::find()
            ->where(['member_id' => Yii::$app->user->id])
            ->one();

        if (!$config) {
            $config = new NotifySubscriptionConfig();
            $config->member_id = Yii::$app->user->id;
            $config->save();
        }

        return $config;
    }
}