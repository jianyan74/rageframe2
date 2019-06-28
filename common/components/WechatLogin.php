<?php
namespace common\components;

use Yii;
use yii\helpers\Json;
use common\models\member\Auth;

/**
 * WechatLogin
 *
 * Trait WechatLogin
 * @package common\components
 */
trait WechatLogin
{
    /**
     * 是否获取微信用户信息
     *
     * @var bool
     */
    protected $openGetWechatUser = true;

    /**
     * 用户id
     *
     * @var string
     */
    protected $openid;

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     */
    protected function login()
    {
        /** 检测到微信进入自动获取用户信息 **/
        if ($this->openGetWechatUser && Yii::$app->wechat->isWechat && !Yii::$app->wechat->isAuthorized()) {
            return Yii::$app->wechat->authorizeRequired()->send();
        }

        /** 当前进入微信用户信息 **/
        Yii::$app->params['wechatMember'] = Json::decode(Yii::$app->session->get('wechatUser'));

        /** 非微信网页打开时候开启模拟数据 **/
        if (empty(Yii::$app->params['wechatMember']) && Yii::$app->params['simulateUser']['switch'] == true) {
            Yii::$app->params['wechatMember'] = Yii::$app->params['simulateUser']['userInfo'];
        }

        $this->openid = Yii::$app->params['wechatMember']['id'];

        // 如果是静默登录则不写入数据库
        if (in_array('snsapi_base', Yii::$app->params['wechatConfig']['oauth']['scopes'])) {
            return false;
        }

        // 插入微信关联表
        if (!($memberAuthInfo = Yii::$app->services->memberAuth->findOauthClient(Auth::CLIENT_WECHAT, $this->openid))) {
            $original = Yii::$app->params['wechatMember']['original'];
            Yii::$app->services->memberAuth->create([
                'oauth_client' => Auth::CLIENT_WECHAT,
                'oauth_client_user_id' => $original['openid'],
                'gender' => $original['sex'],
                'nickname' => $original['nickname'],
                'head_portrait' => $original['headimgurl'],
                'country' => $original['country'],
                'province' => $original['province'],
                'city' => $original['city'],
                'language' => $original['language'],
            ]);

            unset($original, $memberAuthInfo, $memberAuth);
        }
    }
}