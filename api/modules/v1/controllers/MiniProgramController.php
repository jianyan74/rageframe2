<?php
namespace api\modules\v1\controllers;

use Yii;
use api\modules\v1\models\MiniProgramLoginForm;
use common\models\member\MemberInfo;
use common\models\api\AccessToken;
use common\helpers\ArrayHelper;
use common\helpers\ResultDataHelper;
use common\models\member\MemberAuth;
use common\helpers\FileHelper;

/**
 * 小程序
 *
 * Class MiniProgramController
 * @package api\modules\v1\controllers
 */
class MiniProgramController extends \yii\rest\ActiveController
{
    public $modelClass = '';

    /**
     * 小程序SDK
     *
     * @var
     */
    public $miniProgramApp;

    public function init()
    {
        $logPath = Yii::getAlias('@runtime') . '\\wechat-miniProgram\\' . date('Y-m') . '\\';
        FileHelper::mkdirs($logPath);

        Yii::$app->params['wechatMiniProgramConfig'] = [
            'app_id' => Yii::$app->debris->config('miniprogram_appid'),
            'secret' => Yii::$app->debris->config('miniprogram_secret'),
            // token 和 aes_key 开启消息推送后可见
            // 'token' => '',
            // 'aes_key' => ''
            'response_type' => 'array',
            'log' => [
                'level' > 'debug',
                'file' => $logPath . date('d') . '.log',
            ],
        ];

        $this->miniProgramApp = Yii::$app->wechat->miniProgram;
    }

    /**
     * 通过 Code 换取 SessionKey
     *
     * @param $code
     * @return array|mixed
     * @throws \yii\base\Exception
     */
    public function actionSessionKey($code)
    {
        if (!$code)
        {
            return ResultDataHelper::api(422, '通信错误,请在微信重新发起请求');
        }

        $oauth = $this->miniProgramApp->auth->session($code);
        // 解析是否接口报错
        Yii::$app->debris->getWechatError($oauth);

        // 缓存数据
        $auth_key = Yii::$app->security->generateRandomString() . '_' . time();
        Yii::$app->cache->set($auth_key, ArrayHelper::toArray($oauth), 7195);

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

        if (!$model->validate())
        {
            return ResultDataHelper::api(422, $this->analyErr($model->getFirstErrors()));
        }

        if (!($oauth = Yii::$app->cache->get($model->auth_key)))
        {
            return ResultDataHelper::api(422, 'auth_key已过期');
        }

        $sign = sha1(htmlspecialchars_decode($model->rawData . $oauth['session_key']));
        if ($sign !== $model->signature)
        {
            return ResultDataHelper::api(422, '签名错误');
        }

        $userinfo = $this->miniProgramApp->encryptor->decryptData($oauth['session_key'], $model->iv, $model->encryptedData);
        Yii::$app->cache->delete($model->auth_key);

        // 插入到用户授权表
        if (!($memberAuthInfo = MemberAuth::findOauthClient(MemberAuth::CLIENT_MINI_PROGRAM, $userinfo['openId'])))
        {
            $memberAuth = new MemberAuth();
            $memberAuthInfo = $memberAuth->add([
                'unionid' => isset($userinfo['unionId']) ? $userinfo['unionId'] : '',
                'oauth_client' => MemberAuth::CLIENT_MINI_PROGRAM,
                'oauth_client_user_id' => $userinfo['openId'],
                'sex' => $userinfo['gender'],
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
        if (!($member = $memberAuthInfo->member))
        {
            $member = new MemberInfo();
            $member->attributes = [
                'sex' => $userinfo['gender'],
                'nickname' => $userinfo['nickName'],
                'head_portrait' => $userinfo['avatarUrl'],
            ];
            $member->save();

            // 关联用户
            $memberAuthInfo->member_id = $member['id'];
            $memberAuthInfo->save();
        }

        return AccessToken::getAccessToken($member, AccessToken::GROUP_MINI_PROGRAM);
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