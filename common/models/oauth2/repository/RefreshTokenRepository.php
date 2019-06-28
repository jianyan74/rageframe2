<?php
namespace common\models\oauth2\repository;

use Yii;
use common\helpers\ArrayHelper;
use common\models\oauth2\entity\RefreshTokenEntity;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

/**
 * Class RefreshTokenRepository
 * @package common\models\oauth2
 * @author jianyan74 <751393839@qq.com>
 */
class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * 创建新授权码时调用方法
     *
     * @return RefreshTokenEntity|RefreshTokenEntityInterface|null
     */
    public function getNewRefreshToken()
    {
        // 需要返回 RefreshTokenEntityInterface 对象
        return new RefreshTokenEntity();
    }

    /**
     * 创建新刷新令牌
     *
     * @param RefreshTokenEntityInterface $refreshTokenEntity
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        // 可以使用参数中的 RefreshTokenEntityInterface 对象，获得有价值的信息：

        // $refreshTokenEntity->getAccessToken()->getIdentifier(); // 获得访问令牌标识符

        // 获得刷新令牌过期时间
        $date = $refreshTokenEntity->getExpiryDateTime(); // 获得令牌过期时间
        $date = ArrayHelper::toArray($date);

        // 创建token
        Yii::$app->services->oauth2RefreshToken->create(
            $refreshTokenEntity->getAccessToken()->getClient()->getIdentifier(), // 获得客户端标识符
            $refreshTokenEntity->getAccessToken()->getClient()->getGrantType(),
            $refreshTokenEntity->getIdentifier(), // 获得刷新令牌唯一标识符
            $date['date'],
            $refreshTokenEntity->getAccessToken()->getUserIdentifier(), // 获得用户标识符
            $refreshTokenEntity->getAccessToken()->getScopes()// 获得权限范围
        );
    }

    /**
     * 当使用刷新令牌获取访问令牌时调用此方法
     * 原刷新令牌将删除，创建新的刷新令牌
     *
     * @param string $tokenId 刷新令牌唯一标识
     */
    public function revokeRefreshToken($tokenId)
    {
        // 可在此删除原刷新令牌
        Yii::$app->services->oauth2RefreshToken->deleteByRefreshToken($tokenId);
    }

    /**
     * 当使用刷新令牌获取访问令牌时调用此方法
     * 用于验证刷新令牌是否已被删除
     *
     * @param string $tokenId
     * @return bool
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        // return true 已删除，false 未删除
        return empty(Yii::$app->services->oauth2RefreshToken->findByRefreshToken($tokenId));
    }
}