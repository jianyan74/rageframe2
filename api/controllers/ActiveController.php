<?php

namespace api\controllers;

use Yii;
use yii\filters\Cors;
use yii\filters\RateLimiter;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\HttpHeaderAuth;
use yii\filters\auth\QueryParamAuth;
use yii\web\BadRequestHttpException;
use common\traits\BaseAction;
use common\behaviors\ActionLogBehavior;
use common\behaviors\HttpSignAuth;

/**
 * Class ActiveController
 * @package api\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ActiveController extends \yii\rest\ActiveController
{
    use BaseAction;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = [];

    /**
     * 不用进行签名验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $signOptional = [];

    /**
     * 行为验证
     *
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // 跨域支持
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
        ];

        // 移除行为的权限验证的优先级
        unset($behaviors['authenticator']);

        // 进行签名验证
        if (Yii::$app->params['user.httpSignValidity'] == true) {
            $behaviors['signTokenValidate'] = [
                'class' => HttpSignAuth::class,
                'optional' => $this->signOptional, // 不进行认证判断方法
            ];
        }

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                /**
                 * 下面是四种验证access_token方式
                 *
                 * 1.HTTP 基本认证: access token 当作用户名发送，应用在access token可安全存在API使用端的场景，例如，API使用端是运行在一台服务器上的程序。
                 * \yii\filters\auth\HttpBasicAuth::class,
                 *
                 * 2.OAuth : 使用者从认证服务器上获取基于OAuth2协议的access token，然后通过 HTTP Bearer Tokens 发送到API 服务器。
                 * header格式：Authorization:Bearer+空格+access-token
                 * yii\filters\auth\HttpBearerAuth::class,
                 *
                 * 3.请求参数 access token 当作API URL请求参数发送，这种方式应主要用于JSONP请求，因为它不能使用HTTP头来发送access token
                 * http://rageframe.com/user/index/index?access-token=123
                 *
                 * 4.请求参数 access token 当作API header请求参数发送
                 * header格式: x-api-key: access-token
                 * yii\filters\auth\HttpHeaderAuth::class,
                 */
                // HttpBasicAuth::class,
                HttpBearerAuth::class,
                HttpHeaderAuth::class,
                [
                    'class' => QueryParamAuth::class,
                    'tokenParam' => 'access-token',
                ],
            ],
            // 不进行认证判断方法
            'optional' => $this->authOptional,
        ];

        /**
         * 请求速率控制
         *
         * limit部分，速度的设置是在common\models\common\RateLimit::getRateLimit($request, $action)
         * 当速率限制被激活，默认情况下每个响应将包含以下HTTP头发送 目前的速率限制信息：
         * X-Rate-Limit-Limit: 同一个时间段所允许的请求的最大数目;
         * X-Rate-Limit-Remaining: 在当前时间段内剩余的请求的数量;
         * X-Rate-Limit-Reset: 为了得到最大请求数所等待的秒数。
         * enableRateLimitHeaders：false: 不开启限制 true：开启限制
         */
        $behaviors['rateLimiter'] = [
            'class' => RateLimiter::class,
            'enableRateLimitHeaders' => true,
        ];

        // 行为日志
        // $behaviors['actionLog'] = [
        //    'class' => ActionLogBehavior::class,
        // ];

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD', 'OPTIONS'],
            'view' => ['GET', 'HEAD', 'OPTIONS'],
            'create' => ['POST', 'OPTIONS'],
            'update' => ['PUT', 'PATCH', 'OPTIONS'],
            'delete' => ['DELETE', 'OPTIONS'],
        ];
    }

    /**
     * 前置操作验证token有效期和记录日志和检查curd权限
     *
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // 权限方法检查，如果用了rbac，请注释掉
        $this->checkAccess($action->id, $this->modelClass, Yii::$app->request->get());

        // 每页数量
        $this->pageSize = Yii::$app->request->get('per-page', 10);
        $this->pageSize > 50 && $this->pageSize = 50;

        return true;
    }
}
