<?php

namespace common\models\oauth2\repository;

use common\models\member\Member;
use common\models\oauth2\entity\UserEntity;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class UserRepository
 * @package common\models\oauth2
 * @author jianyan74 <751393839@qq.com>
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @param string $username
     * @param string $password
     * @param string $grantType 使用授权类型
     * @param ClientEntityInterface $clientEntity
     * @return UserEntity|UserEntityInterface
     * @throws UnprocessableEntityHttpException
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {
        /* @var $member \common\models\base\User */
        if (!($member = Member::findByUsername($username))) {
            throw new UnprocessableEntityHttpException('找不到用户信息');
        }

        if (!$member->validatePassword($password)) {
            throw new UnprocessableEntityHttpException('密码错误');
        }

        // 可以验证是否为用户可使用的授权类型($grantType)与客户端($clientEntity)

        $user = new UserEntity();
        $user->setIdentifier($member->id);

        return $user;
    }
}