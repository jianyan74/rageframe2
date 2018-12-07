<?php
namespace api\controllers;

use Yii;
use yii\web\Response;
use yii\filters\Cors;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\web\BadRequestHttpException;

/**
 * Class ActiveController
 * @package common\controllers
 */
class ActiveController extends \yii\rest\ActiveController
{
    /**
     * 普通获取每页数量
     *
     * @var int
     */
    protected $pageSize = 10;

    /**
     * 启始位移
     *
     * @var int
     */
    protected $offset = 0;

    /**
     * 获取每页数量
     *
     * @var
     */
    protected $limit;

    /**
     * 行为验证
     *
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // 跨域支持
        $behaviors['class'] = Cors::className();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                /* 下面是三种验证access_token方式 */
                // 1.HTTP 基本认证: access token 当作用户名发送，应用在access token可安全存在API使用端的场景，例如，API使用端是运行在一台服务器上的程序。
                // \yii\filters\auth\HttpBasicAuth::className(),
                // 2.OAuth : 使用者从认证服务器上获取基于OAuth2协议的access token，然后通过 HTTP Bearer Tokens 发送到API 服务器。
                // yii\filters\auth\HttpBearerAuth::className(),
                // 3.请求参数: access token 当作API URL请求参数发送，这种方式应主要用于JSONP请求，因为它不能使用HTTP头来发送access token
                // http://rageframe.com/user/index/index?accessToken=123
                // 4.请求参数: access token 当作API header请求参数发送
                // yii\filters\auth\HttpHeaderAuth::className(),
                [
                    'class' => QueryParamAuth::className(),
                    'tokenParam' => 'access-token'
                ],
            ],
            // 不进行认证判断方法
            'optional' => Yii::$app->params['user.optional'],
        ];

        /**
         * false 不开启限制 true开启限制
         *
         * limit部分，速度的设置是在User::getRateLimit($request, $action)
         * 当速率限制被激活，默认情况下每个响应将包含以下HTTP头发送 目前的速率限制信息：
         * X-Rate-Limit-Limit: 同一个时间段所允许的请求的最大数目;
         * X-Rate-Limit-Remaining: 在当前时间段内剩余的请求的数量;
         * X-Rate-Limit-Reset: 为了得到最大请求数所等待的秒数。
         * 你可以禁用这些头信息通过配置 yii\filters\RateLimiter::enableRateLimitHeaders 为false, 就像在上面的代码示例所示。
         */
        $behaviors['rateLimiter']['enableRateLimitHeaders'] = true;
        // 定义返回格式是：JSON
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;

        return $behaviors;
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
        parent::beforeAction($action);

        // 判断验证token有效性是否开启
        if (Yii::$app->params['user.accessTokenValidity'] == true)
        {
            $token = Yii::$app->request->get('accessToken');
            $timestamp = (int) substr($token, strrpos($token, '_') + 1);
            $expire = Yii::$app->params['user.accessTokenExpire'];

            // 验证有效期
            if ($timestamp + $expire <= time() && !in_array($action->id, Yii::$app->params['user.optional']))
            {
                throw new BadRequestHttpException('您的登录验证已经过期，请重新登陆');
            }
        }

        // 权限方法检查，如果用了rbac，请注释掉
        $this->checkAccess($action->id, $this->modelClass, Yii::$app->request->get());

        // 分页
        $page = Yii::$app->request->get('page', 1);
        $this->limit = Yii::$app->request->get('per-page', $this->pageSize);
        $this->limit > 100 && $this->limit = 100;
        $this->offset = ($page - 1) * $this->pageSize;

        return true;
    }

    /**
     * 解析错误
     *
     * @param $fistErrors
     * @return string
     */
    public function analyErr($firstErrors)
    {
        return Yii::$app->debris->analyErr($firstErrors);
    }
}
