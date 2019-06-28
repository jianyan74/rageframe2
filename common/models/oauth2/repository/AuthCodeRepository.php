<?php
namespace common\models\oauth2\repository;

use Yii;
use common\helpers\ArrayHelper;
use common\models\oauth2\entity\AuthCodeEntity;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

/**
 * Class AuthCodeRepository
 * @package common\models\oauth2
 * @author jianyan74 <751393839@qq.com>
 */
class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    /**
     * 创建新授权码时调用方法
     *
     * @return AuthCodeEntityInterface
     */
    public function getNewAuthCode()
    {
        // 需要返回 AuthCodeEntityInterface 对象
        return new AuthCodeEntity();
    }

    /**
     * 新的auth代码持久存储到永久存储区
     *
     * @param AuthCodeEntityInterface $authCodeEntity
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        // 获得刷新令牌过期时间
        $date = $authCodeEntity->getExpiryDateTime(); // 获得令牌过期时间
        $date = ArrayHelper::toArray($date);

        // 创建token
        Yii::$app->services->oauth2AuthorizationCode->create(
            $authCodeEntity->getClient()->getIdentifier(), // 获得客户端标识符
            $authCodeEntity->getIdentifier(), // 获得刷新令牌唯一标识符
            $date['date'],
            $authCodeEntity->getUserIdentifier(), // 获得用户标识符
            $authCodeEntity->getScopes()// 获得权限范围
        );
    }

    /**
     * Revoke an auth code.
     *
     * @param string $codeId
     */
    public function revokeAuthCode($codeId)
    {
        // 当使用授权码获取访问令牌时调用此方法
        // 可以在此时将授权码从持久化数据库中删除
        // 参数为授权码唯一标识符
        Yii::$app->services->oauth2AuthorizationCode->deleteByAuthorizationCode($codeId);
    }

    /**
     * Check if the auth code has been revoked.
     *
     * @param string $codeId
     *
     * @return bool Return true if this code has been revoked
     */
    public function isAuthCodeRevoked($codeId)
    {
        // 当使用授权码获取访问令牌时调用此方法
        // 用于验证授权码是否已被删除
        // return true 已删除，false 未删除
        return empty(Yii::$app->services->oauth2AuthorizationCode->findByAuthorizationCode($codeId));
    }
}