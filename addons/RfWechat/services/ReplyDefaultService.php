<?php

namespace addons\RfWechat\services;

use addons\RfWechat\common\models\ReplyDefault;
use common\components\Service;

/**
 * Class ReplyDefaultService
 * @package addons\RfWechat\services
 * @author jianyan74 <751393839@qq.com>
 */
class ReplyDefaultService extends Service
{
    /**
     * @return array|ReplyDefault|null|\yii\db\ActiveRecord
     */
    public function findOne()
    {
        if (empty(($model = ReplyDefault::find()->andFilterWhere(['merchant_id' => $this->getMerchantId()])->one()))) {
            return new ReplyDefault();
        }

        return $model;
    }
}