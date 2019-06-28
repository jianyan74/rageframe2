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
        $behaviors[] = [
            'class' => BlameableBehavior::class,
            'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => ['merchant_id'],
            ],
            'value' => Yii::$app->services->merchant->getId(),
        ];

        return $behaviors;
    }
}