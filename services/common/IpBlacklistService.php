<?php

namespace services\common;

use common\components\Service;
use common\enums\StatusEnum;
use common\models\common\IpBlacklist;

/**
 * Class IpBlacklistService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class IpBlacklistService extends Service
{
    /**
     * @param $ip
     * @param $remark
     */
    public function create($ip, string $remark)
    {
        $model = new IpBlacklist();
        $model->ip = $ip;
        $model->remark = $remark;
        $model->save();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findIps()
    {
        return IpBlacklist::find()
            ->select('ip')
            ->where(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->cache(180)
            ->asArray()
            ->column();
    }
}