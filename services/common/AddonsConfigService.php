<?php

namespace services\common;

use Yii;
use common\enums\AppEnum;
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
     * @param $merchant_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByName($name, $app_id, $merchant_id = '')
    {
        return AddonsConfig::find()
            ->where(['addons_name' => $name, 'app_id' => $app_id])
            ->andFilterWhere(['merchant_id' => $merchant_id])
            ->one();
    }
}