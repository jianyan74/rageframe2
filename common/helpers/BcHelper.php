<?php

namespace common\helpers;

/**
 * bc 高精度库
 *
 * 四舍六入(银行家舍入)
 * round(1.2849, 2, PHP_ROUND_HALF_EVEN);
 *
 * Class BcHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class BcHelper
{
    /**
     * 将二个高精确度数字相除
     *
     * @param $dividend
     * @param $divisor
     * @param int $scale
     * @return string|null
     */
    public static function div($dividend, $divisor, $scale = 2)
    {
        return bcdiv($dividend, $divisor, $scale);
    }

    /**
     * 将二个高精确度数字相乘
     *
     * @param $dividend
     * @param $divisor
     * @param int $scale
     * @return string|null
     */
    public static function mul($dividend, $divisor, $scale = 2)
    {
        return bcmul($dividend, $divisor, $scale);
    }

    /**
     * 两个高精度数求余/取模
     *
     * @param $dividend
     * @param $divisor
     * @param int $scale
     * @return string|null
     */
    public static function mod($dividend, $divisor, $scale = 2)
    {
        return bcmod($dividend, $divisor, $scale);
    }

    /**
     * 将二个高精确度数字相加
     *
     * @param $left_operand
     * @param $right_operand
     * @param int $scale
     * @return string
     */
    public static function add($left_operand, $right_operand, $scale = 2)
    {
        return bcadd($left_operand, $right_operand, $scale);
    }

    /**
     * 将二个高精确度数字相加
     *
     * @param $left_operand
     * @param $right_operand
     * @param int $scale
     * @return string
     */
    public static function sub($left_operand, $right_operand, $scale = 2)
    {
        return bcsub($left_operand, $right_operand, $scale);
    }

    /**
     * 比较二个高精确度数字
     *
     * @param $left_operand
     * @param $right_operand
     * @param int $scale
     * @return string
     */
    public static function comp($left_operand, $right_operand, $scale = 2)
    {
        return bccomp($left_operand, $right_operand, $scale);
    }

    /**
     * 求一高精确度数字次方值
     *
     * @param $base
     * @param $exponent
     * @param int $scale
     * @return string
     */
    public static function pow($base, $exponent, $scale = 2)
    {
        return bcpow($base, $exponent, $scale);
    }

    /**
     * 求一高精确度数字次方值
     *
     * @param $operand
     * @param null $scale
     * @return string
     */
    public static function sqrt($operand, $scale = null)
    {
        return bcsqrt($operand, $scale);
    }

    /**
     * 设置所有bc数学函数的默认小数点保留位数
     *
     * @param $scale
     * @return bool
     */
    public static function scale($scale)
    {
        return bcscale($scale);
    }

    /**
     * 四舍五入
     *
     * @param $num
     * @param $scale
     * @return float
     */
    private static function round($num, $scale)
    {
        return round($num, $scale);
    }
}