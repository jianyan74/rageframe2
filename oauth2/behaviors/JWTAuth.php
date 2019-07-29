<?php

namespace oauth2\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\IdentityInterface;
use oauth2\components\ServerRequest;
use League\OAuth2\Server\CryptKey;
use common\helpers\StringHelper;
use common\models\oauth2\repository\AccessTokenRepository;

/**
 * Class JWTAuth
 * @package oauth2\behaviors
 * @author jianyan74 <751393839@qq.com>
 */
class JWTAuth extends Behavior
{
    /**
     * @var array
     */
    public $optional = [];

    /**
     * @return array
     */
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    /**
     * @param $event
     * @return bool
     * @throws UnauthorizedHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function beforeAction($event)
    {
        if (in_array(Yii::$app->controller->action->id, $this->optional)) {
            return true;
        }

        $accessTokenRepository = new AccessTokenRepository(); // instance of AccessTokenRepositoryInterface
        $publicKeyPath = 'file://' . Yii::$app->debris->config('oauth2_rsa_public');
        $server = new \League\OAuth2\Server\ResourceServer(
            $accessTokenRepository,
            new CryptKey($publicKeyPath, null, !StringHelper::isWindowsOS())
        );

        try {
            $request = ServerRequest::fromGlobals();
            $server->validateAuthenticatedRequest($request);
        } catch (\League\OAuth2\Server\Exception\OAuthServerException $exception) {
            throw new UnauthorizedHttpException($exception->getMessage());
        } catch (\Exception $exception) {
            throw new UnprocessableEntityHttpException($exception->getMessage());
        }

        $user = $request->getAttributes();
        /** @var IdentityInterface $user */
        if ($user = Yii::$app->services->oauth2AccessToken->findByAccessToken($user['oauth_access_token_id'], $user['oauth_client_id'])) {
            Yii::$app->user->login($user);
        } else {
            throw new UnauthorizedHttpException('用户不存在');
        }

        return true;
    }
}