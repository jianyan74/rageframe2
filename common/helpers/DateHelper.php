<?php
namespace common\helpers;

/**
 * 日期数据格式返回
 *
 * Class ResultDataHelper
 * @package common\helpers
 */
class DateHelper
{
    /**
     * 获取今日开始时间戳和结束时间戳
     *
     * 语法：mktime(hour,minute,second,month,day,year) => (小时,分钟,秒,月份,天,年)
     */
   public static function today()
   {
       return [
           'start' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
           'end' => mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1,
       ];
   }

    /**
     * 昨日
     *
     * @return array
     */
    public static function yesterDay()
    {
        return [
            'start' => mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')),
            'end' => mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1,
        ];
    }

    /**
     * 这周
     *
     * @return array
     */
    public static function thisWeek()
    {
        return [
            'start' => mktime(0, 0, 0, date('m'), date('d') - date('w') + 1, date('Y')),
            'end' => mktime(23, 59, 59, date('m'), date('d') - date('w') + 7, date('Y')),
        ];
    }

    /**
     * 上周
     *
     * @return array
     */
    public static function lastWeek()
    {
        return [
            'start' => mktime(0, 0, 0,date('m'),date('d') - date('w') + 1 - 7, date('Y')),
            'end' => mktime(23, 59, 59,date('m'),date('d') - date('w') + 7 - 7, date('Y')),
        ];
    }

    /**
     * 本月
     *
     * @return array
     */
    public static function thisMonth()
    {
        return [
            'start' => mktime(0, 0, 0, date('m'), 1, date('Y')),
            'end' => mktime(23, 59, 59, date('m'), date('t'), date('Y')),
        ];
    }

    /**
     * 上个月
     *
     * @return array
     */
    public static function lastMonth()
    {
        $start = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));
        $end = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));

        if (date('m', $start) != date('m', $end))
        {
            $end -= 60 * 60 * 24;
        }

        return [
            'start' => $start,
            'end' => $end,
        ];
    }

    /**
     * 几个月前
     *
     * @param integer $month 月份
     * @return array
     */
    public static function monthsAgo($month)
    {
        return [
            'start' => mktime(0, 0, 0, date('m') - $month, 1, date('Y')),
            'end' => mktime(23, 59, 59, date('m') - $month, date('t'), date('Y')),
        ];
    }

    /**
     * 格式化时间戳
     *
     * @param $time
     * @return string
     */
    public static function formatTimestamp($time)
    {
        $min = $time / 60;
        $hours = $time / 60;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));
        return $days . " 天 " . $hours . " 小时 " . $min . " 分钟 ";
    }

    /**
     * 时间戳
     *
     * @param  integer $accuracy 精度 默认微妙
     * @return int
     */
    public static function getMicrotime($accuracy = 1000000)
    {
        $microtime = explode(' ', microtime());
        return $microtime = (int)round(($microtime[1] + $microtime[0]) * $accuracy, 0);
    }
}