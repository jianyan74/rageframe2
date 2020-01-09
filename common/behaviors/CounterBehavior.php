<?php

namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;
use yii\redis\Connection;
use yii\web\TooManyRequestsHttpException;
use common\helpers\DateHelper;

/**
 * 计数器 - 限流
 *
 * Class CounterBehavior
 * @package common\behaviors
 * @author jianyan74 <751393839@qq.com>
 */
class CounterBehavior extends Behavior
{
    /**
     * 请求方法
     *
     * @var string
     */
    public $action = "*";
    /**
     * 规定时间内最大请求数量
     *
     * @var int
     */
    public $maxCount = 1000;
    /**
     * 用户id
     *
     * @var int
     */
    public $userId = 0;
    /**
     * 在60秒时间内
     *
     * @var float|int
     */
    public $period = 60 * 1000;

    /**
     * @return array
     */
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    /**
     * @param $event
     * @throws TooManyRequestsHttpException
     */
    public function beforeAction($event)
    {
        /** @var Connection $redis */
        $redis = Yii::$app->redis;
        // 限流: 用户 + 访问方法
        $key = sprintf('hist:%s:%s', $this->userId, Yii::$app->controller->route);
        $now = DateHelper::microtime(); // 毫秒时间戳

        $redis->zadd($key, $now, $now); // value 和 score 都使用毫秒时间戳
        $redis->zremrangebyscore($key, 0, $now - $this->period); // 移除时间窗口之前的行为记录，剩下的都是时间窗口内的

        if ($redis->zcard($key) > $this->maxCount) {
            throw new TooManyRequestsHttpException('服务器繁忙');
        }
    }
}