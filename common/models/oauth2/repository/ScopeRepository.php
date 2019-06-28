<?php
namespace common\models\oauth2\repository;

use common\models\oauth2\entity\ScopeEntity;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
 * Class ScopeRepository
 * @package common\models\oauth2
 * @author jianyan74 <751393839@qq.com>
 */
class ScopeRepository implements ScopeRepositoryInterface
{
    /**
     * Return information about a scope.
     *
     * @param string $identifier The scope identifier
     *
     * @return ScopeEntityInterface
     */
    public function getScopeEntityByIdentifier($identifier)
    {
        // 验证权限是否在权限范围中会调用此方法
        // 参数为单个权限标识符
        // ......
        // 验证成功则返回 ScopeEntityInterface 对象
        $scope = new ScopeEntity();
        $scope->setIdentifier($identifier);

        return $scope;
    }

    /**
     * Given a client, grant type and optional user identifier validate the set of scopes requested are valid and optionally
     * append additional scopes or remove requested scopes.
     *
     * @param ScopeEntityInterface[] $scopes
     * @param string                 $grantType
     * @param ClientEntityInterface  $clientEntity
     * @param null|string            $userIdentifier
     *
     * @return ScopeEntityInterface[]
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ) {
        // 在创建授权码与访问令牌前会调用此方法
        // 用于验证权限范围、授权类型、客户端、用户是否匹配
        // 可整合进项目自身的权限控制中
        // 必须返回 ScopeEntityInterface 对象可用的 scope 数组
        // 示例：
        // $scope = new ScopeEntity();
        // $scope->setIdentifier('example');
        // $scopes[] = $scope;

        return $scopes;
    }
}