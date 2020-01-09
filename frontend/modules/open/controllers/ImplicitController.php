<?php
namespace frontend\modules\open\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use GuzzleHttp\Psr7\ServerRequest;
use oauth2\components\Response;
use League\OAuth2\Server\Grant\ImplicitGrant;
use common\models\oauth2\entity\UserEntity;

/**
 * 简化模式(在redirect_uri 的Hash传递token; Auth客户端运行在浏览器中,如JS,Flash)
 *
 * Class ImplicitController
 * @package frontend\modules\open\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ImplicitController extends Controller
{
    /**
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        $grant = new ImplicitGrant(new \DateInterval(Yii::$app->params['user.accessTokenExpire']));
        Yii::$app->services->oauth2Server->set($grant); // 写入服务
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        /* @var \League\OAuth2\Server\AuthorizationServer $server */
        $server = Yii::$app->services->oauth2Server->get();
        $response = new Response();
        $request = ServerRequest::fromGlobals();

        // Try to respond to the request
        try {
            // 验证HTTP请求并返回
            $authRequest = $server->validateAuthorizationRequest($request);
            // 是否授权成功
            $authRequest->setAuthorizationApproved(true);
            $authRequest->setUser(new UserEntity());
            $server->completeAuthorizationRequest($authRequest, $response);
        } catch (\League\OAuth2\Server\Exception\OAuthServerException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        } catch (\Exception $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }
    }
}