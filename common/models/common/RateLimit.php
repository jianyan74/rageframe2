<?php
namespace common\models\common;

use Yii;
use yii\filters\RateLimitInterface;

/**
 * 速率控制
 *
 * Class RateLimit
 * @package common\models\common
 */
class RateLimit extends User implements RateLimitInterface
{
    /**
     * @var int
     */
    public $rateWindowSize = 3600;

    /**
     * 速度控制
     *
     * @param \yii\web\Request $request
     * @param \yii\base\Action $action
     * @return mixed
     */
    public function getRateLimit($request, $action)
    {
        // 次数、秒 例如 3600秒可访问5000次
        return [5000, $this->rateWindowSize];
    }

    /**
     * 返回剩余的允许的请求和相应的UNIX时间戳数 当最后一次速率限制检查时。
     *
     * @param \yii\web\Request $request
     * @param \yii\base\Action $action
     * @return array
     */
    public function loadAllowance($request, $action)
    {
        $allowance = Yii::$app->cache->get($this->getCacheKey('api_rate_allowance'));
        $timestamp = Yii::$app->cache->get($this->getCacheKey('api_rate_timestamp'));

        if ($allowance === false) {
            return [$this->rateWindowSize, time()];
        }

        return [$allowance, $timestamp];
    }

    /**
     * @param \yii\web\Request $request
     * @param \yii\base\Action $action
     * @param int $allowance 剩余请求次数
     * @param int $timestamp 当前的UNIX时间戳
     */
    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        Yii::$app->cache->set($this->getCacheKey('api_rate_allowance'), $allowance, $this->rateWindowSize);
        Yii::$app->cache->set($this->getCacheKey('api_rate_timestamp'), $timestamp, $this->rateWindowSize);
    }

    /**
     * @param $key
     * @return array
     */
    public function getCacheKey($key)
    {
        return [__CLASS__, $this->getId(), $key];
    }
}