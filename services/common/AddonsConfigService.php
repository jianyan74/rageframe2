<?php

namespace services\common;

use common\components\Service;
use common\models\common\AddonsConfig;

/**
 * Class AddonsConfigService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class AddonsConfigService extends Service
{
    /**
     * @param $name
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByName($name)
    {
        return AddonsConfig::find()
            ->where(['addons_name' => $name, 'merchant_id' => $this->getMerchantId()])
            ->one();
    }
}