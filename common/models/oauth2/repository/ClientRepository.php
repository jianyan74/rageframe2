<?php
namespace common\models\oauth2\repository;

use Yii;
use yii\web\UnprocessableEntityHttpException;
use common\models\oauth2\entity\ClientEntity;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * Class ClientRepository
 * @package common\models\oauth2
 * @author jianyan74 <751393839@qq.com>
 */
class ClientRepository implements ClientRepositoryInterface
{
    /**
     * 验证客户端
     *
     * @param string $clientIdentifier 客户端唯一标识符
     * @param null $grantType 代表授权类型，根据类型不同，验证方式也不同
     * @param null $clientSecret 代表客户端密钥，是客户端事先在授权服务器中注册时得到的
     * @param bool $mustValidateSecret 代表是否需要验证客户端密钥
     * @return ClientEntity|ClientEntityInterface
     * @throws UnprocessableEntityHttpException
     */
    public function getClientEntity($clientIdentifier, $grantType = null, $clientSecret = null, $mustValidateSecret = true)
    {
        if (!($clentModel = Yii::$app->services->oauth2Client->findByClientId($clientIdentifier))) {
            throw new UnprocessableEntityHttpException('找不到 Client Id');
        }

        if ($mustValidateSecret === true && $clentModel['client_secret'] !== $clientSecret) {
            throw new UnprocessableEntityHttpException('Client Secret 错误');
        }

        // 返回客户端信息
        $client = new ClientEntity();
        $client->setIdentifier($clientIdentifier);
        $client->setName($clentModel['title']);
        // 校验回调域名
        // $client->setRedirectUri($clentModel['redirect_uri']);
        $client->setGrantType($grantType);

        return $client;
    }
}