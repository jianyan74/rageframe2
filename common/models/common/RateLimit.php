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
     * 速度控制
     *
     * @param \yii\web\Request $request
     * @param \yii\base\Action $action
     * @return mixed
     */
    public function getRateLimit($request, $action)
    {
        return Yii::$app->params['user.rateLimit'];
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
        return [$this->allowance, $this->allowance_updated_at];
    }

    /**
     * @param \yii\web\Request $request
     * @param \yii\base\Action $action
     * @param int $allowance 剩余请求次数
     * @param int $timestamp 当前的UNIX时间戳
     */
    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $this->allowance = $allowance;
        $this->allowance_updated_at = $timestamp;
        $this->save();
    }
}