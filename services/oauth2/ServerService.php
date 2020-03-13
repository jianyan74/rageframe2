<?php

namespace services\oauth2;

use Yii;
use common\components\Service;
use common\helpers\StringHelper;
use common\models\oauth2\repository\ClientRepository;
use common\models\oauth2\repository\ScopeRepository;
use common\models\oauth2\repository\AccessTokenRepository;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;

/**
 * Class ServerService
 * @package services\oauth2
 * @author jianyan74 <751393839@qq.com>
 */
class ServerService extends Service
{
    /**
     * @var AuthorizationServer
     */
    private $_server;

    /**
     * @return AuthorizationServer
     */
    public function get(): AuthorizationServer
    {
        return $this->_server;
    }

    /**
     * @param $grant
     * @throws \Exception
     */
    public function set($grant)
    {
        $clientRepository = new ClientRepository(); // Interface: ClientRepositoryInterface
        $scopeRepository = new ScopeRepository(); // Interface: ScopeRepositoryInterface
        $accessTokenRepository = new AccessTokenRepository(); // Interface: AccessTokenRepositoryInterface

        // 初始化 server
        $this->_server = new AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $this->getPrivateKey(),
            $this->getEncryptionKey()
        );

        // 将授权码授权类型添加进 server
        $this->_server->enableGrantType(
            $grant,
            new \DateInterval('PT1H') // 设置访问令牌过期时间1小时
        );
    }

    /**
     * 私钥文件
     *
     * @return CryptKey|string
     */
    public function getPrivateKey()
    {
        $privateKey = 'file://' . Yii::getAlias(Yii::$app->debris->backendConfig('oauth2_rsa_private'));

        // 如果私钥文件有密码
        if (!empty(Yii::$app->debris->backendConfig('oauth2_rsa_private_encryption'))) {
            $privateKey = new CryptKey(
                $privateKey,
                Yii::$app->debris->backendConfig('oauth2_rsa_private_password'),
                !StringHelper::isWindowsOS()
            );
        } else {
            $privateKey = new CryptKey($privateKey, null, !StringHelper::isWindowsOS());
        }

        return $privateKey;
    }

    /**
     * 加密密钥字符串
     *
     * @return string
     */
    public function getEncryptionKey(): string
    {
        $encryptionKey = Yii::$app->debris->backendConfig('oauth2_encryption_key'); // 加密密钥字符串
        // generate using base64_encode(random_bytes(32))
        // $encryptionKey = Key::loadFromAsciiSafeString($encryptionKey); //如果通过 generate-defuse-key 脚本生成的字符串，可使用此方法传入
        return $encryptionKey;
    }
}