<?php

namespace common\helpers;

use yii\helpers\BaseArrayHelper;
use yii\helpers\Json;

/**
 * Class ArrayHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class ArrayHelper extends BaseArrayHelper
{
    /**
     * 递归数组
     *
     * @param array $items
     * @param string $idField
     * @param int $pid
     * @param string $pidField
     * @return array
     */
    public static function itemsMerge(array $items, $pid = 0, $idField = "id", $pidField = 'pid', $child = '-')
    {
        $map = [];
        $tree = [];
        foreach ($items as &$it) {
            $it[$child] = [];
            $map[$it[$idField]] = &$it;
        }

        foreach ($items as &$it) {
            $parent = &$map[$it[$pidField]];
            if ($parent) {
                $parent[$child][] = &$it;
            } else {
                $pid == $it[$pidField] && $tree[] = &$it;
            }
        }

        unset($items, $map);

        return $tree;
    }

    /**
     * 传递一个子分类ID返回所有的父级分类
     *
     * @param array $items
     * @param $id
     * @return array
     */
    public static function getParents(array $items, $id)
    {
        $arr = [];
        foreach ($items as $v) {
            if ($v['id'] == $id) {
                $arr[] = $v;
                $arr = array_merge(self::getParents($items, $v['pid']), $arr);
            }
        }

        return $arr;
    }

    /**
     * 传递一个父级分类ID返回所有子分类
     *
     * @param $cate
     * @param int $pid
     * @return array
     */
    public static function getChilds($cate, $pid)
    {
        $arr = [];
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                $arr[] = $v;
                $arr = array_merge($arr, self::getChilds($cate, $v['id']));
            }
        }

        return $arr;
    }

    /**
     * 传递一个父级分类ID返回所有子分类ID
     *
     * @param $cate
     * @param $pid
     * @param string $idField
     * @param string $pidField
     * @return array
     */
    public static function getChildIds($cate, $pid, $idField = "id", $pidField = 'pid')
    {
        $arr = [];
        foreach ($cate as $v) {
            if ($v[$pidField] == $pid) {
                $arr[] = $v[$idField];
                $arr = array_merge($arr, self::getChildIds($cate, $v[$idField], $idField, $pidField));
            }
        }

        return $arr;
    }

    /**
     * php二维数组排序 按照指定的key 对数组进行排序
     *
     * @param array $arr 将要排序的数组
     * @param string $keys 指定排序的key
     * @param string $type 排序类型 asc | desc
     * @return array
     */
    public static function arraySort($arr, $keys, $type = 'asc')
    {
        if (count($arr) <= 1) {
            return $arr;
        }

        $keysValue = [];
        $newArray = [];

        foreach ($arr as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }

        $type == 'asc' ? asort($keysValue) : arsort($keysValue);
        reset($keysValue);
        foreach ($keysValue as $k => $v) {
            $newArray[$k] = $arr[$k];
        }

        return $newArray;
    }

    /**
     * 获取数组指定的字段为key
     *
     * @param array $arr 数组
     * @param string $field 要成为key的字段名
     * @return array
     */
    public static function arrayKey(array $arr, $field)
    {
        $newArray = [];
        foreach ($arr as $value) {
            isset($value[$field]) && $newArray[$value[$field]] = $value;
        }

        return $newArray;
    }

    /**
     * 移除数组内某个key的值为传递的值
     *
     * @param array $array
     * @param $value
     * @param string $key
     * @return array
     */
    public static function removeByValue(array $array, $value, $key = 'id')
    {
        foreach ($array as $index => $item) {
            if ($item[$key] == $value) {
                unset($array[$index]);
            }
        }

        return $array;
    }

    /**
     * 获取数字区间
     *
     * @param int $start
     * @param int $end
     * @return array
     */
    public static function numBetween($start = 0, $end = 1, $key = true, $step_number = 1)
    {
        $arr = [];
        for ($i = $start; $i <= $end; $i = $i + $step_number) {
            $key == true ? $arr[$i] = $i : $arr[] = $i;
        }

        return $arr;
    }

    /**
     * 根据级别和数组返回字符串
     *
     * @param int $level 级别
     * @param array $models
     * @param $k
     * @param int $treeStat 开始计算
     * @return bool|string
     */
    public static function itemsLevel($level, array $models, $k, $treeStat = 1)
    {
        $str = '';
        for ($i = 1; $i < $level; $i++) {
            $str .= '　　';

            if ($i == $level - $treeStat) {
                if (isset($models[$k + 1])) {
                    return $str . "├──";
                }

                return $str . "└──";
            }
        }

        return false;
    }

    /**
     * 必须经过递归才能进行重组为下拉框
     *
     * @param $models
     * @param string $idField
     * @param string $titleField
     * @param int $treeStat
     * @return array
     */
    public static function itemsMergeDropDown($models, $idField = 'id', $titleField = 'title', $treeStat = 1)
    {
        $arr = [];
        foreach ($models as $k => $model) {
            $arr[] = [
                $idField => $model[$idField],
                $titleField => self::itemsLevel($model['level'], $models, $k, $treeStat) . " " . $model[$titleField],
            ];

            if (!empty($model['-'])) {
                $arr = ArrayHelper::merge($arr,
                    self::itemsMergeDropDown($model['-'], $idField, $titleField, $treeStat));
            }
        }

        return $arr;
    }

    /**
     * 匹配ip在ip数组内支持通配符
     *
     * @param $ip
     * @param $allowedIPs
     * @return bool
     */
    public static function ipInArray($ip, $allowedIPs)
    {
        foreach ($allowedIPs as $filter) {
            if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter,
                        $pos))) {
                return true;
            }
        }

        return false;
    }

    /**
     * 对比2组id，返回存在的id和被删除的id
     *
     * @param array $oldIds
     * @param array $newIds
     * @return array
     */
    public static function comparisonIds(array $oldIds, array $newIds)
    {
        $updatedIds = $deleteIds = [];

        foreach ($oldIds as $oldId) {
            if (in_array($oldId, $newIds)) {
                $updatedIds[] = $oldId;
            } else {
                $deleteIds[] = $oldId;
            }
        }

        return [$updatedIds, $deleteIds];
    }

    /**
     * 获取递归的第一个没有子级的数据
     *
     * @param $array
     * @return mixed
     */
    public static function getFirstRowByItemsMerge(array $array)
    {
        foreach ($array as $item) {
            if (!empty($item['-'])) {
                return self::getFirstRowByItemsMerge($item['-']);
            } else {
                return $item;
            }
        }

        return false;
    }

    /**
     * 获取所有没有子级的数据
     *
     * @param $array
     * @return mixed
     */
    public static function getNotChildRowsByItemsMerge(array $array)
    {
        $arr = [];

        foreach ($array as $item) {
            if (!empty($item['-'])) {
                $arr = array_merge($arr, self::getNotChildRowsByItemsMerge($item['-']));
            } else {
                $arr[] = $item;
            }
        }

        return $arr;
    }

    /**
     * 递归转普通二维数组
     *
     * @param $array
     * @return mixed
     */
    public static function getRowsByItemsMerge(array $array, $childField = '-')
    {
        $arr = [];

        foreach ($array as $item) {
            if (!empty($item[$childField])) {
                $arr = array_merge($arr, self::getRowsByItemsMerge($item[$childField]));
            }

            unset($item[$childField]);
            $arr[] = $item;
        }

        return $arr;
    }

    /**
     * 重组 map 类型转为正常的数组
     *
     * @param array $array
     * @param string $keyForField
     * @param string $valueForField
     * @return array
     */
    public static function regroupMapToArr($array = [], $keyForField = 'route', $valueForField = 'title')
    {
        $arr = [];
        foreach ($array as $key => $item) {
            if (!is_array($array[$key])) {
                $arr[] = [
                    $keyForField => $key,
                    $valueForField => $item,
                ];
            } else {
                $arr[] = $item;
            }
        }

        return $arr;
    }

    /**
     * 数组内某字段转数组
     *
     * @param array $data
     * @param string $field
     * @return array
     */
    public static function fieldToArray(array $data, $field = 'covers')
    {
        foreach ($data as &$datum) {
            if (empty($datum[$field])) {
                $datum[$field] = [];
            }

            if (!is_array($datum[$field])) {
                $datum[$field] = Json::decode($datum[$field]);
            }
        }

        return $data;
    }

    /**
     * 数组转xml
     *
     *
     * @param $arr
     * 微信回调成功：['return_code' => 'SUCCESS', 'return_msg' => 'OK']
     * 微信回调失败：['return_code' => 'FAIL', 'return_msg' => 'OK']
     * @return bool|string
     */
    public static function toXml($arr)
    {
        if (!is_array($arr) || count($arr) <= 0) {
            return false;
        }

        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }

        $xml .= "</xml>";
        return $xml;
    }
}