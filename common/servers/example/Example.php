<?php
namespace common\servers\example;

use Yii;

/**
 * Class Example
 * @package common\servers\examply
 */
class Example extends \common\servers\Service
{
    /**
     * @return string
     */
    public function index()
    {
        return 'this common\servers\examply\Example Class';
    }

    /**
     * @return mixed
     */
    public function child()
    {
        return Yii::$app->servers->example->rule->index('serviceToChildService');
    }
}