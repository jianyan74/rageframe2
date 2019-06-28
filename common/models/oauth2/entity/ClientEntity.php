<?php
namespace common\models\oauth2\entity;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

/**
 * Class ClientEntity
 * @package common\models\oauth2\entity
 * @author jianyan74 <751393839@qq.com>
 */
class ClientEntity implements ClientEntityInterface
{
    use EntityTrait, ClientTrait;

    /**
     * @var string
     */
    protected $grantType;

    /**
     * Get the client's name.
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getGrantType()
    {
        return $this->grantType;
    }

    /**
     * @param $grantType
     */
    public function setGrantType($grantType)
    {
        $this->grantType = $grantType;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }
}