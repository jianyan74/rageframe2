<?php

namespace common\helpers;

/**
 * 算法辅助类
 *
 * Class ArithmeticHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class ArithmeticHelper
{
    /**
     * 生成红包算法
     *
     * @param number $money 红包总金额
     * @param number $num 生成的红包数量
     * @param number $min 红包最小金额
     * @param number $max 红包最大金额
     * @return array
     */
    public static function getRedPackage($money, $num, $min, $max)
    {
        $data = [];

        // 判断最小红包乘数量是否大于总金额
        if ($min * $num > $money) {
            return $data;
        }

        // 判断最大红包乘数量是否小于总金额
        if ($max * $num < $money) {
            return $data;
        }

        while ($num >= 1) {
            $num--;
            $kmix = max($min, $money - $num * $max);
            $kmax = min($max, $money - $num * $min);
            $kAvg = $money / ($num + 1);
            // 获取最大值和最小值的距离之间的最小值
            $kDis = min($kAvg - $kmix, $kmax - $kAvg);
            // 获取0到1之间的随机数与距离最小值相乘得出浮动区间，这使得浮动区间不会超出范围
            $r = ((float)(rand(1, 10000) / 10000) - 0.5) * $kDis * 2;
            $k = round($kAvg + $r, 2);

            $money -= $k;
            $data[] = $k;
        }

        shuffle($data);
        return $data;
    }

    /** ------ 抽奖算法(摇一摇，拉霸机，刮刮乐) ------ **/

    /**
     * 非必中 总概率1-1000
     * @param array $awards 奖品数组
     * @param string $prob 奖品概率
     * @param string $key 返回的数组键值
     * @return bool
     */
    public static function drawRandom($awards = [], $prob = 'prob', $key = 'id')
    {
        $rand = mt_rand(1, 1000);
        $proArr = [];
        $pro = 0;
        // 按概率抽奖
        foreach ($awards as $award) {
            $pro += $award[$prob];
            $proArr[] = $pro;
        }

        foreach ($proArr as $k => $v) {
            if ($rand < $v) {
                return $awards[$k][$key];
                break;
            }
        }

        return false;
    }

    /**
     * 抽奖必中
     *
     * @param array $awards 奖品数组
     * @param string $prob 奖品概率
     * @return bool
     */
    public static function drawBitslap($awards = [], $prob = 'prob')
    {
        $proArr = [];
        if ($awards) {
            foreach ($awards as $key => $value) {
                $proArr[$key] = $value[$prob];
            }

            $result = self::getDrawRand($proArr);
            return $awards[$result]['id'];
        }

        return false;
    }

    /**
     * 经典的概率算法
     *
     * $proArr是一个预先设置的数组，
     * 假设数组为：array(100,200,300，400)，
     * 开始是从1,1000 这个概率范围内筛选第一个数是否在他的出现概率范围之内，
     * 如果不在，则将概率空间，也就是k的值减去刚刚的那个数字的概率空间，
     * 在本例当中就是减去100，也就是说第二个数是在1，900这个范围内筛选的。
     * 这样 筛选到最终，总会有一个数满足要求。
     * 就相当于去一个箱子里摸东西，
     * 第一个不是，第二个不是，第三个还不是，那最后一个一定是。
     * 这个算法简单，而且效率非常高，
     * 关键是这个算法已在我们以前的项目中有应用，尤其是大数据量的项目中效率非常棒。
     */
    public static function getDrawRand($proArr = [])
    {
        $result = '';
        // 概率数组的总概率精度
        $proSum = array_sum($proArr);
        // 概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);

            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }

        unset ($proArr);
        return $result;
    }
}