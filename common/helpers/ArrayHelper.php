<?php
namespace common\helpers;

use yii\helpers\BaseArrayHelper;

/**
 * Class ArrayHelper
 * @package common\helpers
 */
class ArrayHelper extends BaseArrayHelper
{
    /**
     * 递归数组
     *
     * @param array $items
     * @param string $id
     * @param int $pid
     * @param string $pidName
     * @return array
     */
    public static function itemsMerge(array $items, $id = "id", $pid = 0, $pidName = 'pid')
    {
        $arr = [];
        foreach($items as $v)
        {
            if ($v[$pidName] == $pid)
            {
                $v['-'] = self::itemsMerge($items, $id, $v[$id], $pidName);
                $arr[] = $v;
            }
        }

        return $arr;
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
        foreach ($items as $v)
        {
            if ($v['id'] == $id)
            {
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
        foreach ($cate as $v)
        {
            if ($v['pid'] == $pid)
            {
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
     * @param int $pid
     * @return array
     */
    public static function getChildsId($cate, $pid, $id = "id", $pidName = 'pid')
    {
        $arr = [];
        foreach ($cate as $v)
        {
            if ($v[$pidName] == $pid)
            {
                $arr[] = $v[$id];
                $arr = array_merge($arr, self::getChildsId($cate, $v[$id], $id, $pidName));
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
        $count = count($arr);
        if ($count <= 1)
        {
            return $arr;
        }

        $keysvalue = [];
        $new_array = [];

        foreach ($arr as $k => $v)
        {
            $keysvalue[$k] = $v[$keys];
        }

        $type == 'asc' ? asort($keysvalue) : arsort($keysvalue);
        reset($keysvalue);

        foreach ($keysvalue as $k => $v)
        {
            $new_array[$k] = $arr[$k];
        }

        return $new_array;
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
        $new_array = [];
        foreach ($arr as $value)
        {
            $new_array[$value[$field]] = $value;
        }

        return $new_array;
    }


    /**
     * 根据级别和数组返回字符串
     *
     * @param $level
     * @param array $models
     * @param $k
     * @return bool|string
     */
    public static function itemsLevel($level, array $models, $k)
    {
        $str = '';
        for ($i = 1; $i < $level; $i++)
        {
            $str .= '　　';
            if ($i == $level - 1)
            {
                if (isset($models[$k + 1]))
                {
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
     * @return array
     */
    public static function itemsMergeDropDown($models)
    {
        $arr = [];
        foreach ($models as $k => $model)
        {
            $arr[] = [
                'id' => $model['id'],
                'title' => self::itemsLevel($model['level'], $models, $k) . " " . $model['title'],
            ];

            if (!empty($model['-']))
            {
                $arr = ArrayHelper::merge($arr, self::itemsMergeDropDown($model['-']));
            }
        }

        return $arr;
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
        if (!is_array($arr) || count($arr) <= 0)
        {
            return false;
        }

        $xml = "<xml>";
        foreach ($arr as $key => $val)
        {
            if (is_numeric($val))
            {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
            else
            {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }

        $xml .= "</xml>";
        return $xml;
    }
}