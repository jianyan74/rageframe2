<?php
namespace jianyan\easywechat;

use Yii;
use EasyWeChat\Factory;
use yii\base\Component;

/**
 * Class Wechat
 *
 * @package jianyan\easywechat
 *
 * @property \EasyWeChat\OfficialAccount\Application $app 微信SDK实例
 * @property \EasyWeChat\Payment\Application $payment 微信支付SDK实例
 * @property \EasyWeChat\MiniProgram\Application $miniProgram 微信小程序实例
 * @property \EasyWeChat\OpenPlatform\Application $openPlatform 微信开放平台(第三方平台)实例
 * @property \EasyWeChat\Work\Application $work 企业微信实例
 * @property \EasyWeChat\OpenWork\Application $openWork 企业微信开放平台实例
 */
class Wechat extends Component
{
    /**
     * user identity class params
     * @var array
     */
    public $userOptions = [];

    /**
     * wechat user info will be stored in session under this key
     * @var string
     */
    public $sessionParam = '_wechatUser';

    /**
     * returnUrl param stored in session
     * @var string
     */
    public $returnUrlParam = '_wechatReturnUrl';

    /**
     * 微信SDK
     *
     * @var Factory
     */
    private static $_app;

    /**
     * 支付 SKD
     *
     * @var Factory
     */
    private static $_payment;

    /**
     * 小程序 SKD
     *
     * @var Factory
     */
    private static $_miniProgram;

    /**
     * 第三方开放平台 SKD
     *
     * @var Factory
     */
    private static $_openPlatform;

    /**
     * 企业微信 SKD
     *
     * @var Factory
     */
    private static $_work;

    /**
     * 企业微信开放平台 SKD
     *
     * @var Factory
     */
    private static $_openWork;

    /**
     * @var WechatUser
     */
    private static $_user;

    /**
     * @return $this|Yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function authorizeRequired()
    {
        if(Yii::$app->request->get('code'))
        {
            // callback and authorize
            return $this->authorize($this->app->oauth->user());
        }
        else
        {
            // redirect to wechat authorize page
            $this->setReturnUrl(Yii::$app->request->getUrl());
            return Yii::$app->response->redirect($this->app->oauth->redirect()->getTargetUrl());
        }
    }

    /**
     * @param \Overtrue\Socialite\User $user
     * @return yii\web\Response
     */
    public function authorize(\Overtrue\Socialite\User $user)
    {
        Yii::$app->session->set($this->sessionParam, $user->toJSON());
        return Yii::$app->response->redirect($this->getReturnUrl());
    }

    /**
     * check if current user authorized
     * @return bool
     */
    public function isAuthorized()
    {
        $hasSession = Yii::$app->session->has($this->sessionParam);
        $sessionVal = Yii::$app->session->get($this->sessionParam);
        return ($hasSession && !empty($sessionVal));
    }

    /**
     * @param string|array $url
     */
    public function setReturnUrl($url)
    {
        Yii::$app->session->set($this->returnUrlParam, $url);
    }

    /**
     * @param null $defaultUrl
     * @return mixed|null|string
     */
    public function getReturnUrl($defaultUrl = null)
    {
        $url = Yii::$app->session->get($this->returnUrlParam, $defaultUrl);
        if (is_array($url))
        {
            if (isset($url[0]))
            {
                return Yii::$app->getUrlManager()->createUrl($url);
            }
            else
            {
                $url = null;
            }
        }

        return $url === null ? Yii::$app->getHomeUrl() : $url;
    }

    /**
     * 获取 EasyWeChat 微信实例
     *
     * @return Factory|\EasyWeChat\OfficialAccount\Application
     */
    public function getApp()
    {
        if (!self::$_app instanceof Factory)
        {
            self::$_app = Factory::officialAccount(Yii::$app->params['wechatConfig']);
        }

        return self::$_app;
    }

    /**
     * 获取 EasyWeChat 微信支付实例
     *
     * @return Factory
     */
    public function getPayment()
    {
        if (!self::$_payment instanceof Factory)
        {
            self::$_payment = Factory::payment(Yii::$app->params['wechatPaymentConfig']);
        }

        return self::$_payment;
    }

    /**
     * 获取 EasyWeChat 微信小程序实例
     *
     * @return Factory
     */
    public function getMiniProgram()
    {
        if (!self::$_miniProgram instanceof Factory)
        {
            self::$_miniProgram = Factory::miniProgram(Yii::$app->params['wechatMiniProgramConfig']);
        }

        return self::$_miniProgram;
    }

    /**
     * 获取 EasyWeChat 微信第三方开放平台实例
     *
     * @return Factory
     */
    public function getOpenPlatform()
    {
        if (!self::$_openPlatform instanceof Factory)
        {
            self::$_openPlatform = Factory::openPlatform(Yii::$app->params['wechatOpenPlatformConfig']);
        }

        return self::$_openPlatform;
    }

    /**
     * 获取 EasyWeChat 企业微信实例
     *
     * @return Factory
     */
    public function getWork()
    {
        if (!self::$_work instanceof Factory)
        {
            self::$_work = Factory::work(Yii::$app->params['wechatWorkConfig']);
        }

        return self::$_work;
    }

    /**
     * 获取 EasyWeChat 企业微信开放平台实例
     *
     * @return Factory
     */
    public function getOpenWork()
    {
        if (!self::$_openWork instanceof Factory)
        {
            self::$_openWork = Factory::openWork(Yii::$app->params['wechatOpenWorkConfig']);
        }

        return self::$_openWork;
    }

    /**
     * 获取微信身份信息
     *
     * @return WechatUser
     */
    public function getUser()
    {
        if (!$this->isAuthorized())
        {
            return new WechatUser();
        }

        if (! self::$_user instanceof WechatUser)
        {
            $userInfo = Yii::$app->session->get($this->sessionParam);
            $config = $userInfo ? json_decode($userInfo, true) : [];
            self::$_user = new WechatUser($config);
        }
        return self::$_user;
    }

    /**
     * overwrite the getter in order to be compatible with this component
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        try
        {
            return parent::__get($name);
        }
        catch (\Exception $e)
        {
            if($this->getApp()->$name)
            {
                return $this->app->$name;
            }
            else
            {
                throw $e->getPrevious();
            }
        }
    }

    /**
     * check if client is wechat
     * @return bool
     */
    public function getIsWechat()
    {
        return strpos($_SERVER["HTTP_USER_AGENT"], "MicroMessenger") !== false;
    }
}
