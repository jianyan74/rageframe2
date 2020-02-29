<?php
/**
 * Created by PhpStorm.
 * User: 毛阿毛
 * Date: 2019/11/28
 * Time: 11:57
 */

namespace services\member;

use Yii;
use common\components\Service;
use common\enums\CacheEnum;
use common\enums\StatusEnum;
use common\models\member\Account;
use common\models\member\Level;
use common\models\member\Member;

/**
 * 用户等级类
 *
 * ````` 使用方法：``````
 * 根据用户信息获取可升等级 ： Yii::$app->services->memberLevel->getLevelByMember($member);
 * 余额+积分获取：Yii::$app->services->memberLevel->getLevel(1, 200, 300);
 *
 * Class LevelService
 * @author Maomao
 * @package services\member
 */
class LevelService extends Service
{
    /**
     * @var int $timeout 过期时间
     */
    private $timeout = 20;

    /**
     * @param Member $member
     * @return bool|Level|mixed|\yii\db\ActiveRecord
     */
    public function getLevelByMember(Member $member)
    {
        /** @var Account $account */
        $account = $member->account;

        return $this->getLevel(
            $member->current_level,
            $account->consume_money,
            $account->accumulate_integral
        );
    }

    /**
     * 获取用户可升等级信息
     *
     * @param int $current_level 当前级别
     * @param float $money 消费金额
     * @param int $integral 累计积分
     * @return bool|Level|mixed|\yii\db\ActiveRecord
     */
    public function getLevel(int $current_level, float $money, int $integral)
    {
        if (!($levels = $this->getLevelForCache())) {
            return false;
        }

        foreach ($levels as $level) {
            if (!$this->getMiddle($level, $money, $integral)) {
                continue;
            }

            if ($current_level < $level->level) {
                return $level;
            }
        }

        return false;
    }

    /**
     * 根据商户id获取等级列表
     * @param int $merchant_id
     * @return array|Level[]|mixed|\yii\db\ActiveRecord[]
     */
    public function getLevelForCache()
    {
        $key = CacheEnum::getPrefix('levelList');

        if (!($list = Yii::$app->cache->get($key))) {
            $list = $this->findAll();

            Yii::$app->cache->set($key, $list, $this->timeout);
        }

        return $list;
    }

    /**
     * @param $merchant_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll()
    {
        $merchant_id = Yii::$app->services->merchant->getId();

        return Level::find()
            ->where(['merchant_id' => $merchant_id, 'status' => StatusEnum::ENABLED])
            ->orderBy(['level' => SORT_DESC, 'id' => SORT_DESC])
            ->all();
    }

    /**
     * @param Level $level
     * @param float $money 累计消费
     * @param int $integral 累计积分
     * @return array|bool|mixed
     */
    private function getMiddle(Level $level, $money, $integral)
    {
        if (!$level) {
            return false;
        }

        $money = abs($money);
        $integral = abs($integral);

        ##### 校验余额条件 #####
        $middle_money = $level->check_money ? (
        $money >= $level->money ? 1 : 0
        ) : 1;

        ##### 校验积分条件 #####
        $middle_integral = $level->check_integral ? (
        $integral >= $level->integral ? 1 : 0
        ) : 1;

        ##### 校验判断条件 #####
        if ($level->check_integral & $level->check_money) {
            $middle = $level->middle ? '&' : '|';
        } else {
            $middle = '&';
        }

        return eval("return {$middle_money} {$middle} {$middle_integral};");
    }
}