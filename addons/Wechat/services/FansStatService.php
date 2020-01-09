<?php

namespace addons\Wechat\services;

use Yii;
use addons\Wechat\common\models\FansStat;
use common\components\Service;
use common\enums\CacheEnum;

/**
 * Class FansStatService
 * @package addons\Wechat\services
 * @author jianyan74 <751393839@qq.com>
 */
class FansStatService extends Service
{
    /**
     * 关注计算
     */
    public function upFollowNum()
    {
        if (!($today = FansStat::find()->where(['date' => date('Y-m-d')])->andFilterWhere(['merchant_id' => $this->getMerchantId()])->one())) {
            $today = new FansStat();
            $today->date = date('Y-m-d');
            $today->created_at = strtotime($today->date);
        }

        $today->new_attention += 1;
        $today->save();
    }

    /**
     * 取消关注计算
     */
    public function upUnFollowNum()
    {
        if (!($today = FansStat::find()->where(['date' => date('Y-m-d')])->andFilterWhere(['merchant_id' => $this->getMerchantId()])->one())) {
            $today = new FansStat();
            $today->date = date('Y-m-d');
            $today->created_at = strtotime($today->date);
        }

        $today->cancel_attention += 1;
        $today->save();
    }

    /**
     * @param $created_at
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findByCreatedAt($created_at)
    {
        return FansStat::find()
            ->where(['created_at' => $created_at])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->asArray()
            ->one();
    }

    /**
     * @param $from_date
     * @param $to_date
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findBetweenByCreatedAt($from_date, $to_date)
    {
        return FansStat::find()
            ->where(['between', 'created_at', $from_date, strtotime($to_date)])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->orderBy('created_at asc')
            ->asArray()
            ->all();
    }

    /**
     * @param $app
     * @return bool
     */
    public function getFansStat()
    {
        // 缓存设置
        $cacheKey = CacheEnum::getPrefix('wechatFansStat');
        if (Yii::$app->cache->get($cacheKey)) {
            return true;
        }

        $sevenDays = [
            date('Y-m-d', strtotime('-1 days')),
            date('Y-m-d', strtotime('-2 days')),
            date('Y-m-d', strtotime('-3 days')),
            date('Y-m-d', strtotime('-4 days')),
            date('Y-m-d', strtotime('-5 days')),
            date('Y-m-d', strtotime('-6 days')),
            date('Y-m-d', strtotime('-7 days')),
        ];

        $models = FansStat::find()
            ->where(['in', 'date', $sevenDays])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->all();

        $statUpdate = false;
        $weekStat = [];
        foreach ($models as $model) {
            $weekStat[$model['date']] = $model;
        }

        // 查询数据是否有
        foreach ($sevenDays as $sevenDay) {
            if (empty($weekStat[$sevenDay]) || $weekStat[$sevenDay]['cumulate_attention'] <= 0) {
                $statUpdate = true;
                break;
            }
        }

        if (empty($statUpdate)) {
            return true;
        }

        // 获取微信统计数据
        $stats = Yii::$app->wechat->app->data_cube;
        // 增减
        $userSummary = $stats->userSummary($sevenDays[6], $sevenDays[0]);
        // 累计用户
        $userCumulate = $stats->userCumulate($sevenDays[6], $sevenDays[0]);

        $list = [];
        if (!empty($userSummary['list'])) {
            foreach ($userSummary['list'] as $row) {
                $key = $row['ref_date'];
                $list[$key]['new_attention'] = $row['new_user'];
                $list[$key]['cancel_attention'] = $row['cancel_user'];
            }
        }

        if (!empty($userCumulate['list'])) {
            foreach ($userCumulate['list'] as $row) {
                $key = $row['ref_date'];
                $list[$key]['cumulate_attention'] = $row['cumulate_user'];
            }
        }

        // 更新到数据库
        foreach ($list as $key => $value) {
            $model = new FansStat();
            if (isset($weekStat[$key])) {
                $model = $weekStat[$key];
            }

            $model->attributes = $value;
            $model->date = $key;
            $model->created_at = strtotime($key);
            $model->save();
        }

        // 今日累计关注统计计算
        $cumulate_attention = Yii::$app->wechatService->fans->getCountFollow();
        if (!($today = FansStat::find()->where(['date' => date('Y-m-d')])->one())) {
            $today = new FansStat();
            $today->date = date('Y-m-d');
            $today->created_at = strtotime($today->date);
        }

        $today->cumulate_attention = $cumulate_attention;
        $today->save();

        Yii::$app->cache->set($cacheKey, true, 7200);
        return true;
    }
}