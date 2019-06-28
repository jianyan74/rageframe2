<?php
namespace services\wechat;

use common\models\wechat\ReplyDefault;
use common\components\Service;

/**
 * Class ReplyDefaultService
 * @package services\wechat
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