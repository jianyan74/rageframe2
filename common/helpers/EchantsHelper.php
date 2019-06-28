<?php

namespace common\helpers;

/**
 * 图表辅助类
 *
 * Class EchantsHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class EchantsHelper
{
    /**
     * 某天的数据
     *
     * @param array $data
     *
     * ...
     *  $data = [
     *       0 => [
     *           'count' => 6,
     *           'time' => '1',
     *       ],
     *       1 => [
     *           'count' => 8,
     *           'time' => '18',
     *       ],
     *  ];
     * ...
     *
     * @return array
     */
    public static function day(array $data)
    {
        $numBetween = ArrayHelper::numBetween(0, 23);
        $data = ArrayHelper::arrayKey($data, 'time');
        $seriesData = [];
        foreach ($numBetween as &$item) {
            $item < 10 && $item = '0' . $item;
            $seriesData[] = isset($data[$item]) ? $data[$item]['count'] : 0;
            $item = $item . ':00';
        }

        return [
            'xAxisData' => $numBetween,
            'seriesData' => $seriesData,
        ];
    }

    /**
     * 某周的数据
     *
     * @param array $data
     *
     * ...
     *  $data = [
     *       0 => [
     *           'count' => 6,
     *           'time' => '2019-6-10',
     *       ],
     *       1 => [
     *           'count' => 8,
     *           'time' => '2019-6-15',
     *       ],
     *  ];
     * ...
     *
     * @param array $time
     * @return array
     */
    public static function week(array $data, array $time)
    {
        $numBetween = ["周一", "周二", "周三", "周四", "周五", "周六", "周日"];
        $data = ArrayHelper::arrayKey($data, 'time');
        $seriesData = [];
        $index = 0;
        for ($i = $time['start']; $i < $time['end']; $i = $i + 60 * 60 * 24) {
            $date = date('Y-n-j', $i);
            $seriesData[] = isset($data[$date]) ? $data[$date]['count'] : 0;

            $numBetween[$index] = $numBetween[$index] . "(" . date('n-j', $i) . "日)";
            $index++;
        }

        return [
            'xAxisData' => $numBetween,
            'seriesData' => $seriesData,
        ];
    }

    /**
     * 某月的数据
     *
     * @param array $data
     *
     * ...
     *  $data = [
     *       0 => [
     *           'count' => 6,
     *           'time' => '2019-6-10',
     *       ],
     *       1 => [
     *           'count' => 8,
     *           'time' => '2019-6-15',
     *       ],
     *  ];
     * ...
     *
     * @param array $time
     * @return array
     */
    public static function month(array $data, array $time)
    {
        $numBetween = ArrayHelper::numBetween(1, date('t', $time['start']), false);
        $data = ArrayHelper::arrayKey($data, 'time');
        $seriesData = [];
        for ($i = $time['start']; $i < $time['end']; $i = $i + 60 * 60 * 24) {
            $date = date('Y-n-j', $i);
            $seriesData[] = isset($data[$date]) ? $data[$date]['count'] : 0;
        }

        foreach ($numBetween as &$item) {
            $item = date('m') . '-' . $item . '日';
        }

        return [
            'xAxisData' => $numBetween,
            'seriesData' => $seriesData,
        ];
    }

    /**
     * 某年的数据
     *
     * @param array $data
     *
     * ...
     *  $data = [
     *       0 => [
     *           'count' => 6,
     *           'time' => '2019-5',
     *       ],
     *       1 => [
     *           'count' => 8,
     *           'time' => '2019-6',
     *       ],
     *  ];
     * ...
     *
     * @param array $time
     * @return array
     */
    public static function year(array $data, array $time)
    {
        $numBetween = ArrayHelper::numBetween(1, 12, false);
        $data = ArrayHelper::arrayKey($data, 'time');
        $seriesData = [];
        foreach ($numBetween as &$item) {
            $year = date('Y', $time['start']);
            $month = $year . '-' . $item;
            $seriesData[] = isset($data[$month]) ? $data[$month]['count'] : 0;
            $item = date('y', $time['start']) . '年' . $item . '月';
        }

        return [
            'xAxisData' => $numBetween,
            'seriesData' => $seriesData,
        ];
    }
}