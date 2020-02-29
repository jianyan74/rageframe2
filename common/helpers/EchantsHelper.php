<?php

namespace common\helpers;

use Yii;

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
     * 通用格式化日期匹配
     *
     * @var array
     */
    protected static $formats = [
        'minute' => [
            'default' => 'Y-n-d H:i',
            'sql' => '%Y-%c-%d %H:%i',
            'view' => 'd H:i',
        ],
        'hour' => [
            'default' => 'Y-n-d H:00',
            'sql' => '%Y-%c-%d %H:00',
            'view' => 'H:00',
        ],
        'week' => [
            'default' => 'Y-n-d',
            'sql' => '%Y-%c-%d',
            'view' => 'Y-n-d',
        ],
        'day' => [
            'default' => 'Y-n-d',
            'sql' => '%Y-%c-%d',
            'view' => 'Y-n-d',
        ],
        'month' => [
            'default' => 'Y-n',
            'sql' => '%Y-%c',
            'view' => 'Y-m',
        ],
        'year' => [
            'default' => 'Y',
            'sql' => '%Y',
            'view' => 'Y',
        ],
    ];

    /**
     * 根据类型快速获取时间区间和格式
     *
     * @param $type
     * @param array $betweenTime
     * @return array
     */
    public static function getFormatTime($type, $start_time = '', $end_time = '')
    {
        switch ($type) {
            case 'yesterday' :
                list($time, $format) = [DateHelper::yesterday(), 'hour'];
                break;
            case 'thisWeek' :
                list($time, $format) = [DateHelper::thisWeek(), 'week'];
                break;
            case 'thisMonth' :
                list($time, $format) = [DateHelper::thisMonth(), 'day'];
                break;
            case 'thisYear' :
                list($time, $format) = [DateHelper::aYear(date('Y')), 'month'];
                break;
            case 'this30Day' :
                list($time, $format) = [['start' => time() - 60 * 60 *24 *30, 'end' => time()], 'day'];
                break;
            case 'lastYear' :
                list($time, $format) = [DateHelper::aYear(date('Y') - 1), 'month'];
                break;
            case 'betweenHour' :
                list($time, $format) = [['start' => $start_time, 'end' => $end_time], 'hour'];
                break;
            case 'betweenDay' :
                list($time, $format) = [['start' => $start_time, 'end' => $end_time], 'day'];
                break;
            case 'betweenMonth' :
                list($time, $format) = [['start' => $start_time, 'end' => $end_time], 'month'];
                break;
            case 'betweenYear' :
                list($time, $format) = [['start' => $start_time, 'end' => $end_time], 'year'];
                break;
            case 'customData' :
                $end = strtotime(Yii::$app->request->get('echarts_end')) + 60 * 60 * 24 - 1;
                list($time, $format) = [['start' => strtotime(Yii::$app->request->get('echarts_start')), 'end' => $end], 'day'];
                break;
            default :
                // 默认今天
                list($time, $format) = [DateHelper::today(), 'hour'];
                break;
        }

        return [$time, $format];
    }

    /**
     * 折线 柱状 获取区间统计
     *
     * $fields = [
     *      'count' => '订单笔数',
     *      'product_count' => '产品数量',
     * ];
     *
     * @param $function
     * @param array $fields 字段数组
     * @param array $time
     * @param string $format
     * @param string $distinguishKey 时间字段名称
     * @return array
     */
    public static function lineOrBarInTime(
        $function,
        array $fields,
        array $time,
        $format = 'day',
        $distinguishKey = 'time',
        $echartType = 'line'
    ) {
        $data = call_user_func($function, $time['start'], $time['end'], self::$formats[$format]['sql']);
        // 对比key
        $data = ArrayHelper::arrayKey($data, $distinguishKey);
        // 递增时间
        $all = self::progressiveIncreaseTime($time['start'], $time['end'], $format);
        // 默认数据
        $seriesData = [];
        foreach ($fields as $field => $value) {
            $seriesData[] = [
                'field' => $field,
                'name' => $value,
                'type' => $echartType,
                // 'smooth' => 'true',
                'data' => [],
            ];
        }

        foreach ($all as &$item) {
            if (isset($data[$item])) {
                foreach ($seriesData as $key => &$value) {
                    $field = $seriesData[$key]['field'];
                    $value['data'][] = $data[$item][$field] ?? 0;
                }
            } else {
                foreach ($seriesData as $key => &$value) {
                    $value['data'][] = 0;
                }
            }

            // 格式化页面显示
            if ($format == 'week') {
                $item .= '(' . DateHelper::getWeekName(strtotime($item)) . ')';
            } else {
                $item = date(self::$formats[$format]['view'], strtotime($item));
            }
        }

        return [
            'xAxisData' => $all,
            'seriesData' => $seriesData,
            'fieldsName' => array_values($fields),
        ];
    }

    /**
     * 饼图
     *
     * @param $function
     * @param array $time
     * @param array $defaultSeries
     * @return array
     */
    public static function pie($function, array $time, $defaultSeries = [])
    {
        list($data, $fields) = call_user_func($function, $time['start'], $time['end']);

        // 重组增加默认数据
        $seriesData = [];
        $seriesData[] = ArrayHelper::merge([
            'name' => '',
            'type' => 'pie',
            'radius' => '55%',
            'center' => ['50%', '50%'],
            'data' => [],
            'itemStyle' => [
                'emphasis' => [
                    'shadowBlur' => 10,
                    'shadowOffsetX' => 0,
                    'shadowColor' => 'rgba(0, 0, 0, 0.5)',
                ]
            ]
        ], $defaultSeries);

        if (empty($data)) {
            $data = [
                [
                    'name' => '暂无数据',
                    'value' => 0
                ]
            ];
        }

        $seriesData[0]['data'] = $data;

        return [
            'seriesData' => $seriesData,
            'fieldsName' => $fields,
        ];
    }

    /**
     * 饼图
     *
     * @param $function
     * @param array $time
     * @param array $defaultSeries
     * @return array
     */
    public static function lineGraphic($function, array $time, $defaultSeries = [])
    {
        list($data, $fields) = call_user_func($function, $time['start'], $time['end']);

        // 重组增加默认数据
        $seriesData = [];
        $seriesData[] = ArrayHelper::merge([
            'name' => '',
            'type' => 'bar',
            'smooth' => true,
            'barCategoryGap' => 25,
            'data' => [],
            'lineStyle' => [
                'normal' => [
                    'width' => 3,
                    'shadowBlur' => 10,
                    'shadowOffsetY' => 10,
                    'shadowColor' => 'rgba(0, 0, 0, 0.5)',
                ]
            ]
        ], $defaultSeries);

        if (empty($data)) {
            $data = [0];
        }

        $seriesData[0]['data'] = $data;

        return [
            'seriesData' => $seriesData,
            'fieldsName' => $fields,
        ];
    }

    /**
     * 重组数据 - 主要用户多此分组
     *
     * @param array $statData
     * @param $field
     * @param string $distinguishKey
     * @param string $sumKey
     * @return array
     */
    public static function regroupTimeData(array $statData, $field, $distinguishKey = 'time', $sumKey = 'count')
    {
        $resData = [];
        foreach ($statData as $statDatum) {
            $key = $statDatum[$distinguishKey];
            if (!isset($resData[$key])) {
                $resData[$key] = [
                    $distinguishKey => $statDatum[$distinguishKey],
                    $statDatum[$field] => $statDatum[$sumKey],
                ];
            } else {
                $fieldKey = $statDatum[$field];
                if (!isset($resData[$key][$fieldKey])) {
                    $resData[$key][$fieldKey] = $statDatum[$sumKey];
                } else {
                    $resData[$key][$fieldKey] += $statDatum[$sumKey];
                }
            }
        }

        return $resData;
    }

    /**
     * 获取递增时间区间
     *
     * @param $start_time
     * @param $end_time
     * @param $type
     * @return array
     */
    protected static function progressiveIncreaseTime($start_time, $end_time, $type)
    {
        $type == 'week' && $type = 'day';

        // 获取格式化
        $dateformat = self::$formats[$type]['default'];
        $startTime = strtotime(date($dateformat, $start_time));
        $endTime = strtotime(date($dateformat, $end_time));

        // 取得递增
        $all = [];
        $all[] = date($dateformat, $start_time);
        while (($startTime = strtotime("+1 $type", $startTime)) <= $endTime) {
            $all[] = date($dateformat, $startTime);
        }

        return $all;
    }
}