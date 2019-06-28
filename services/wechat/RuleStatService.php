<?php
namespace services\wechat;

use common\components\Service;
use common\models\wechat\RuleStat;

/**
 * Class RuleStatService
 * @package services\wechat
 * @author jianyan74 <751393839@qq.com>
 */
class RuleStatService extends Service
{
    /**
     * 插入今日规则统计
     *
     * @param $rule_id
     */
    public function set($rule_id)
    {
        $ruleStat = RuleStat::find()
            ->where(['rule_id'=> $rule_id, 'created_at' => strtotime(date('Y-m-d'))])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();

        if ($ruleStat) {
            $ruleStat->hit += 1;
        } else {
            $ruleStat = new RuleStat();
            $ruleStat->rule_id = $rule_id;
        }

        $ruleStat->save();
    }
}