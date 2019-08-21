<?php

namespace common\models\oauth2\entity;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

/**
 * Class ScopeEntity
 * @package common\models\oauth2\entity
 * @author jianyan74 <751393839@qq.com>
 */
class ScopeEntity implements ScopeEntityInterface
{
    use EntityTrait;

    // 没有 Trait 实现这个方法，需要自行实现
    // oauth2-server 项目的测试代码的实现例子
    public function jsonSerialize()
    {
        return $this->getIdentifier();
    }
}