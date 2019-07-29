<?php


namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;
use yii\redis\Connection;
use common\helpers\DateHelper;
use yii\web\TooManyRequestsHttpException;

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
        $key = sprintf('hist:%s:%s', $this->userId, $this->action);
        $now = DateHelper::microtime();   # 毫秒时间戳

        // $pipe= $redis->multi(Redis::PIPELINE); //使用管道提升性能
        $redis->zadd($key, $now, $now); //value 和 score 都使用毫秒时间戳
        $redis->zremrangebyscore($key, 0, $now - $this->period); //移除时间窗口之前的行为记录，剩下的都是时间窗口内的
        $redis->zcard($key);  //获取窗口内的行为数量
        $redis->expire($key, $this->period + 1000);  //多加一秒过期时间
        $replies = $redis->exec();

        if ($replies[2] > $this->maxCount) {
            throw new TooManyRequestsHttpException('请求过快');
        }
    }
}