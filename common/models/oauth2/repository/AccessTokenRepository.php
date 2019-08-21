<?php

namespace common\models\oauth2\repository;

use Yii;
use common\helpers\ArrayHelper;
use common\models\oauth2\entity\AccessTokenEntity;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;

/**
 * Class AccessTokenRepository
 * @package common\models\oauth2
 * @author jianyan74 <751393839@qq.com>
 */
class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * 创建新访问令牌时
     *
     * @param ClientEntityInterface  $clientEntity
     * @param ScopeEntityInterface[] $scopes
     * @param mixed                  $userIdentifier
     *
     * @return AccessTokenEntityInterface
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        // 需要返回 AccessTokenEntityInterface 对象
        // 需要在返回前，向 AccessTokenEntity 传入参数中对应属性
        $accessToken = new AccessTokenEntity();
        $accessToken->setClient($clientEntity);
        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }
        $accessToken->setUserIdentifier($userIdentifier);

        return $accessToken;
    }

    /**
     * 创建新令牌
     *
     * @param AccessTokenEntityInterface $accessTokenEntity
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        // 创建新访问令牌时调用此方法
        // 可以用于持久化存储访问令牌，持久化数据库自行选择
        // 可以使用参数中的 AccessTokenEntityInterface 对象，获得有价值的信息：

        $date = $accessTokenEntity->getExpiryDateTime(); // 获得令牌过期时间
        $date = ArrayHelper::toArray($date);

        // 创建token
        Yii::$app->services->oauth2AccessToken->create(
            $accessTokenEntity->getClient()->getIdentifier(), // 获得客户端标识符
            $accessTokenEntity->getClient()->getGrantType(),
            $accessTokenEntity->getIdentifier(),
            $date['date'],
            $accessTokenEntity->getUserIdentifier(), // 获得用户标识符
            $accessTokenEntity->getScopes()// 获得权限范围
        );
    }

    /**
     * 当使用刷新令牌获取访问令牌时调用此方法
     * 原刷新令牌将删除，创建新的刷新令牌
     *
     * @param string $tokenId
     */
    public function revokeAccessToken($tokenId)
    {
        // 可将其在持久化存储中过期
        Yii::$app->services->oauth2AccessToken->deleteByAccessToken($tokenId);
    }

    /**
     * 资源服务器验证访问令牌时将调用此方法
     * 用于验证访问令牌是否已被删除
     *
     * @param string $tokenId
     * @return bool
     */
    public function isAccessTokenRevoked($tokenId)
    {
        // return true 已删除，false 未删除
        return empty(Yii::$app->services->oauth2AccessToken->findByAccessToken($tokenId));
    }
}