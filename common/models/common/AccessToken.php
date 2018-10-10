<?php
namespace common\models\common;

use Yii;
use common\models\member\MemberInfo;
use common\helpers\ArrayHelper;

/**
 * token
 *
 * 如果不想速率控制请直接继承 common\models\member\MemberInfo
 *
 * Class AccessToken
 * @package common\models\common
 */
class AccessToken extends RateLimit
{
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
