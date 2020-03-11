<?php

namespace common\behaviors;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * Trait MerchantBehavior
 * @package common\components
 */
trait MerchantBehavior
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $merchant_id = Yii::$app->services->merchant->getId();
        $behaviors[] = [
            'class' => BlameableBehavior::class,
            'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => ['merchant_id'],
            ],
            'value' => !empty($merchant_id) ? $merchant_id : 0,
        ];

        return $behaviors;
    }
}