<?php
namespace oauth2\controllers;

use Yii;
use common\models\oauth2\repository\UserRepository;
use common\models\oauth2\repository\RefreshTokenRepository;
use common\helpers\ResultDataHelper;
use GuzzleHttp\Psr7\ServerRequest;
use oauth2\components\Response;

/**
 * 密码模式(将用户名,密码传过去,直接获取token)
 *
 * Class PasswordController
 * @package oauth2\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class PasswordController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $optional = ['create'];

    /**
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        $userRepository = new UserRepository(); // instance of UserRepositoryInterface
        $refreshTokenRepository = new RefreshTokenRepository(); // instance of RefreshTokenRepositoryInterface

        $grant = new \League\OAuth2\Server\Grant\PasswordGrant(
            $userRepository,
            $refreshTokenRepository
        );

        $grant->setRefreshTokenTTL(new \DateInterval(Yii::$app->params['user.refreshTokenExpire'])); // refresh tokens will expire after 1 month

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
            return ResultDataHelper::api(422, $exception->getMessage());
        } catch (\Exception $exception) {
            return ResultDataHelper::api(422, $exception->getMessage());
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