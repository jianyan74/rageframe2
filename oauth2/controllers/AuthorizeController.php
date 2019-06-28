<?php
namespace oauth2\controllers;

use Yii;
use oauth2\components\Response;
use common\helpers\ResultDataHelper;
use common\models\oauth2\repository\AuthCodeRepository;
use common\models\oauth2\repository\RefreshTokenRepository;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use GuzzleHttp\Psr7\ServerRequest;

/**
 * 授权码模式(即先登录获取code,再获取token)
 *
 * Class AuthorizeController
 * @package frontend\modules\open\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AuthorizeController extends OnAuthController
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
        // 初始化存储库
        $authCodeRepository = new AuthCodeRepository(); // Interface: AuthCodeRepositoryInterface
        $refreshTokenRepository = new RefreshTokenRepository(); // Interface: RefreshTokenRepositoryInterface

        // 授权码授权类型初始化
        $grant = new AuthCodeGrant(
            $authCodeRepository,
            $refreshTokenRepository,
            new \DateInterval(Yii::$app->params['user.codeExpire']) // 设置授权码过期时间为10分钟
        );
        $grant->setRefreshTokenTTL(new \DateInterval(Yii::$app->params['user.refreshTokenExpire'])); // 设置刷新令牌过期时间1个月
        Yii::$app->services->oauth2Server->set($grant); // 写入服务
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function actionCreate()
    {
        $server = Yii::$app->services->oauth2Server->get();
        $response = new Response();
        $request = ServerRequest::fromGlobals();

        try {
            // 这里只需要这一行就可以，具体的判断在 Repositories 中
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
