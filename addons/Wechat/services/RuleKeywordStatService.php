<?php

namespace addons\Wechat\services;

use common\components\Service;
use addons\Wechat\common\models\RuleKeywordStat;

/**
 * Class RuleKeywordStatService
 * @package addons\Wechat\services
 * @author jianyan74 <751393839@qq.com>
 */
class RuleKeywordStatService extends Service
{
    /**
     * 插入关键字统计
     *
     * @param $rule_id
     * @param $keyword_id
     */
    public function set($rule_id, $keyword_id)
    {
        $ruleKeywordStat = RuleKeywordStat::find()
            ->where([
                'rule_id' => $rule_id,
                'keyword_id' => $keyword_id,
                'created_at' => strtotime(date('Y-m-d'))
            ])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();

        if ($ruleKeywordStat) {
            $ruleKeywordStat->hit += 1;
        } else {
            $ruleKeywordStat = new RuleKeywordStat();
            $ruleKeywordStat->rule_id = $rule_id;
            $ruleKeywordStat->keyword_id = $keyword_id;
        }

        $ruleKeywordStat->save();
    }
}