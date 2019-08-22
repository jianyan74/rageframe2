<?php

namespace backend\modules\sys\controllers;

use Yii;
use yii\helpers\Json;
use common\helpers\ArrayHelper;
use common\models\sys\NotifySubscriptionConfig;
use backend\modules\sys\forms\NotifySubscriptionActionForm;
use backend\controllers\BaseController;

/**
 * Class NotifySubscriptionConfigController
 * @package backend\modules\sys\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class NotifySubscriptionConfigController extends BaseController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $model = new NotifySubscriptionActionForm();
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $data = ArrayHelper::toArray($model);
            $configModel = $this->getConfigModel();
            $configModel->action = Json::encode($data);
            $configModel->save();

            return $this->message('修改成功', $this->redirect(['index']));
        } else {
            $data = $this->getConfigModel()->action;
            $model->attributes = is_array($data) ? $data : Json::decode($data);
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    /**
     * @return array|NotifySubscriptionConfig|\yii\db\ActiveRecord|null
     */
    protected function getConfigModel()
    {
        $config = NotifySubscriptionConfig::find()
            ->where(['manager_id' => Yii::$app->user->id])
            ->one();

        if (!$config) {
            $config = new NotifySubscriptionConfig();
            $config->manager_id = Yii::$app->user->id;
            $config->action = Json::encode(NotifySubscriptionConfig::$defaultSubscriptionConfig);
            $config->save();
        }

        return $config;
    }
}
