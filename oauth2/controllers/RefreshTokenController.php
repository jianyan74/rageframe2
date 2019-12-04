<?php

namespace oauth2\controllers;

use Yii;
use GuzzleHttp\Psr7\ServerRequest;
use oauth2\components\Response;
use common\helpers\ResultHelper;
use common\models\oauth2\repository\RefreshTokenRepository;

/**
 * 刷新access_token
 *
 * Class RefreshTokenController
 * @package oauth2\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class RefreshTokenController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['create'];

    /**
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        $refreshTokenRepository = new RefreshTokenRepository();

        $grant = new \League\OAuth2\Server\Grant\RefreshTokenGrant($refreshTokenRepository);
        $grant->setRefreshTokenTTL(new \DateInterval(Yii::$app->params['user.refreshTokenExpire'])); // new refresh tokens will expire after 1 month

        Yii::$app->services->oauth2Server->set($grant); // 写入服务
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        /* @var \League\OAuth2\Server\AuthorizationServer $server */
        $server = Yii::$app->services->oauth2Server->get();
        $response = new Response();
        $request = ServerRequest::fromGlobals();

        // Try to respond to the request
        try {
            $server->respondToAccessTokenRequest($request, $response);
        } catch (\League\OAuth2\Server\Exception\OAuthServerException $exception) {
            return ResultHelper::api(422, $exception->getMessage());
        } catch (\Exception $exception) {
            return ResultHelper::api(422, $exception->getMessage());
        }
    }

    /**
     * 权限验证
     *
     * @param string $action 当前的方法
     * @param null $model 当前的模型类
     * @param array $params $_GET变量
     * @throws \yii\web\BadRequestHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // 方法名称
        if (in_array($action, ['index', 'view', 'update', 'delete'])) {
            throw new \yii\web\BadRequestHttpException('您的权限不足，如需要请联系管理员');
        }
    }
}