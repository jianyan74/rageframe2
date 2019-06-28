<?php

namespace api\modules\v1\controllers;

use Yii;
use api\controllers\OnAuthController;
use common\enums\PayEnum;
use common\helpers\Url;
use common\models\forms\PayForm;
use common\helpers\ResultDataHelper;

/**
 * 公用支付生成
 *
 * Class PayController
 * @package api\modules\v1\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class PayController extends OnAuthController
{
    /**
     * @var PayForm
     */
    public $modelClass = PayForm::class;

    /**
     * 生成支付参数
     *
     * @return array|mixed|\yii\db\ActiveRecord
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionCreate()
    {
        /* @var $model PayForm */
        $model = new $this->modelClass();
        $model->attributes = Yii::$app->request->post();
        $model->memberId = Yii::$app->user->identity->member_id;
        isset(PayEnum::$payTypeAction[$model->payType]) && $model->notifyUrl = Url::toFront(['notify/' . PayEnum::$payTypeAction[$model->payType]]);
        if (!$model->validate()) {
            return ResultDataHelper::api(422, $this->getError($model));
        }

        return $model->getConfig();
    }
}