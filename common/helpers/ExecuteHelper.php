<?php
namespace common\helpers;

use yii\web\NotFoundHttpException;

/**
 * Class ExecuteHelper
 * @package common\helpers
 */
class ExecuteHelper
{
    /**
     * @param $class
     * @param $method
     * @param $params
     * @throws NotFoundHttpException
     */
    public static function map($class, $method, $params)
    {
        if (!class_exists($class))
        {
            throw new NotFoundHttpException($class . '未找到');
        }

        $class = new $class;
        if (!method_exists($class, $method))
        {
            throw new NotFoundHttpException($class . '/' . $method . ' 方法未找到');
        }

        return $class->run($params);
    }
}