<?php

namespace common\models\oauth2\entity;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

/**
 * Class AccessTokenEntity
 * @package common\models\oauth2\entity
 * @author jianyan74 <751393839@qq.com>
 */
class AccessTokenEntity implements AccessTokenEntityInterface
{
    use AccessTokenTrait, TokenEntityTrait, EntityTrait;
}