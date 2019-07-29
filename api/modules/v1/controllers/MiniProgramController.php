<?php

namespace api\modules\v1\controllers;

use Yii;
use api\controllers\OnAuthController;
use api\modules\v1\forms\MiniProgramLoginForm;
use common\models\member\Member;
use common\models\api\AccessToken;
use common\helpers\ArrayHelper;
use common\helpers\ResultDataHelper;
use common\models\member\Auth;
use common\enums\CacheKeyEnum;

/**
 * 小程序授权验证
 *
 * Class MiniProgramController
 * @package api\modules\v1\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MiniProgramController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $optional = ['decode', 'session-key'];

    /**
     * 通过 Code 换取 SessionKey
     *
     * @param $code
     * @return array|mixed
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\base\Exception
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionSessionKey($code)
    {
        if (!$code) {
            return ResultDataHelper::api(422, '通信错误,请在微信重新发起请求');
        }

        $oauth = Yii::$app->wechat->miniProgram->auth->session($code);
        // 解析是否接口报错
        Yii::$app->debris->getWechatError($oauth);

        // 缓存数据
        $auth_key = Yii::$app->security->generateRandomString() . '_' . time();
        Yii::$app->cache->set(CacheKeyEnum::API_MINI_PROGRAM_LOGIN . $auth_key, ArrayHelper::toArray($oauth), 7195);

        return [
            'auth_key' => $auth_key // 临时缓存token
        ];
    }

    /**
     * 加密数据进行解密 || 进行登录认证
     *
     * @return array|mixed
     * @throws \yii\base\Exception
     * @throws \Exception
     */
    public function actionDecode()
    {
        $model = new MiniProgramLoginForm();
        $model->attributes = Yii::$app->request->post();

        if (!$model->validate()) {
            return ResultDataHelper::api(422, $this->getError($model));
        }

        $userinfo = $model->getUser();
        Yii::$app->cache->delete($model->auth_key);

        // 插入到用户授权表
        if (!($memberAuthInfo = Yii::$app->services->memberAuth->findOauthClient(Auth::CLIENT_MINI_PROGRAM, $userinfo['openId']))) {
            Yii::$app->services->memberAuth->create([
                'unionid' => $userinfo['unionId'] ?? '',
                'oauth_client' => Auth::CLIENT_MINI_PROGRAM,
                'oauth_client_user_id' => $userinfo['openId'],
                'gender' => $userinfo['gender'],
                'nickname' => $userinfo['nickName'],
                'head_portrait' => $userinfo['avatarUrl'],
                'country' => $userinfo['country'],
                'province' => $userinfo['province'],
                'city' => $userinfo['city'],
                'language' => $userinfo['language'],
            ]);
        }

        // TODO 查询自己关联的用户信息并处理自己的登录请求，并返回用户数据
        // TODO 以下代码都可以替换

        // 判断是否有管理信息 数据也可以后续在绑定
        if (!($member = $memberAuthInfo->member)) {
            $member = new Member();
            $member->attributes = [
                'gender' => $userinfo['gender'],
                'nickname' => $userinfo['nickName'],
                'head_portrait' => $userinfo['avatarUrl'],
            ];
            $member->save();

            // 关联用户
            $memberAuthInfo->member_id = $member['id'];
            $memberAuthInfo->save();
        }

        return Yii::$app->services->apiAccessToken->getAccessToken($member, AccessToken::GROUP_MINI_PROGRAM);
    }

    /**
     * 生成小程序码
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function actionQrCode()
    {
        // $response = $app->app_code->get('path/to/page');
        // 指定颜色
        $response = Yii::$app->wechat->miniProgram->app_code->get('path/to/page', [
            'width' => 600,
            'line_color' => [
                'r' => 105,
                'g' => 166,
                'b' => 134,
            ],
        ]);

        // $response 成功时为 EasyWeChat\Kernel\Http\StreamResponse 实例，失败时为数组或者你指定的 API 返回格式

        // 保存小程序码到文件
        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            $filename = $response->save('/path/to/directory');
        }

        // 或
        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            $filename = $response->saveAs('/path/to/directory', 'appcode.png');
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
        if (in_array($action, ['index', 'view', 'update', 'create', 'delete'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}