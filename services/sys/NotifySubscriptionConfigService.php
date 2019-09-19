<?php

namespace services\sys;

use common\components\Service;
use common\enums\SubscriptionAlertTypeEnum;
use common\helpers\ArrayHelper;
use common\models\sys\NotifySubscriptionConfig;

/**
 * Class NotifySubscriptionConfigService
 * @package services\sys
 * @author jianyan74 <751393839@qq.com>
 */
class NotifySubscriptionConfigService extends Service
{
    /**
     * @param $manager_id
     * @return NotifySubscriptionConfig|null
     */
    public function findByManagerId($manager_id)
    {
        return NotifySubscriptionConfig::findOne(['manager_id' => $manager_id]);
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListWithManager()
    {
        return NotifySubscriptionConfig::find()->with('manager')->all();
    }

    /**
     * @param $newData
     */
    public function getData($newData)
    {
        $data = SubscriptionAlertTypeEnum::default();

        foreach ($newData as $key => $datum) {
            !empty($datum) && $data[$key] = ArrayHelper::merge($data[$key], $datum);
        }

        return $data;
    }
}