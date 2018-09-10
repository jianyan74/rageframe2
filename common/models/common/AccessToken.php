<?php
namespace common\models\common;

use Yii;
use yii\filters\RateLimitInterface;
use common\models\member\MemberInfo;
use common\helpers\ArrayHelper;

/**
 * Class AccessToken
 * @package common\models\common
 */
class AccessToken extends MemberInfo implements RateLimitInterface
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

    /**
     * access_token 找到identity
     *
     * @param mixed $token
     * @param null $type
     * @return static
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * 获取token
     *
     * @param object $member
     * @param bool $noFlushToken
     * @return array
     * @throws \yii\base\Exception
     */
    public static function getAccessToken($member, $noFlushToken = false)
    {
        $member->allowance = 2;
        $member->allowance_updated_at = time();
        $member->visit_count += 1;;
        $member->last_time = time();
        $member->last_ip = Yii::$app->request->getUserIP();

        // 不刷新token获取用户信息
        if (!$noFlushToken)
        {
            $member->refresh_token = Yii::$app->security->generateRandomString() . '_' . time();
            $member->access_token = Yii::$app->security->generateRandomString() . '_' . time();
        }

        $result = [];
        $result['refresh_token'] =  $member->refresh_token;
        $result['access_token'] = $member->access_token;
        $result['expiration_time'] = Yii::$app->params['user.accessTokenExpire'];

        !$member->save() && $result = self::getAccessToken($member);

        $member = ArrayHelper::toArray($member);
        unset($member['password_hash'], $member['auth_key'], $member['password_reset_token'], $member['access_token'], $member['refresh_token']);
        $result['member'] = $member;

        return $result;
    }
}
