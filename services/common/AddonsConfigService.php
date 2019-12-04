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
    public function findByName($name, $merchant_id = '')
    {
        if (!$merchant_id) {
            // 总后台强制商户 id 为 1 避免拿到错误的配置
            $merchant_id = Yii::$app->services->merchant->getId();
            AppEnum::BACKEND == Yii::$app->id && $merchant_id = 1;
        }

        return AddonsConfig::find()
            ->where(['addons_name' => $name, 'merchant_id' => $merchant_id])
            ->one();
    }
}