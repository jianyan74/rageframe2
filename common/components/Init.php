<?php

namespace common\components;

use Yii;
use yii\base\BootstrapInterface;
use common\models\sys\Manager;
use common\helpers\ArrayHelper;
use common\helpers\FileHelper;
use yii\web\UnauthorizedHttpException;

/**
 * Class InitConfig
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class Init implements BootstrapInterface
{
    protected $id;

    /**
     * @param \yii\base\Application $application
     * @throws UnauthorizedHttpException
     */
    public function bootstrap($application)
    {
        $this->id = $application->id;// 初始化变量
        // 商户信息
        if (Yii::$app->id == 'backend') {
            /** @var Manager $identity */
            $identity = Yii::$app->user->identity;
            $this->afreshLoad($identity->merchant_id ?? 1);
        } elseif (Yii::$app->id == 'console') {
            $this->afreshLoad(1);
        } else {
            $this->afreshLoad(Yii::$app->request->get('merchant_id', 1));
        }
    }

    /**
     * 重载配置
     *
     * @param $merchant_id
     * @throws UnauthorizedHttpException
     */
    public function afreshLoad($merchant_id)
    {
        try {
            Yii::$app->services->merchant->setId($merchant_id);
            $config = Yii::$app->debris->configAll();

            // 初始化配置
            $this->initParams($config);
            // 初始化组件
            $this->initComponents($config);
        } catch (\Exception $e) {

        }

        // ip黑名单拦截器
        if (isset($config['sys_ip_blacklist_open']) && $config['sys_ip_blacklist_open'] == true) {
            $this->verifyIp();
        }

        unset($config);
    }

    /**
     * @throws UnauthorizedHttpException
     */
    protected function verifyIp()
    {
        $userIP = Yii::$app->request->userIP;
        $ips = Yii::$app->services->ipBlacklist->getList();
        if (in_array($userIP, $ips)) {
            throw new UnauthorizedHttpException('你的访问被禁止');
        }
    }

    /**
     * @param $config
     * @throws \yii\base\InvalidConfigException
     */
    protected function initParams($config)
    {
        $callbackUrl = $notifyUrl = '';
        if (!empty($this->id) && $this->id != 'console') {
            $callbackUrl = Yii::$app->request->getUrl();
            $notifyUrl = Yii::$app->request->hostInfo . Yii::$app->urlManager->createUrl(['notify/index']);
        }

        // 微信公众号
        Yii::$app->params['wechatConfig'] = ArrayHelper::merge([
            /**
             * Debug 模式，bool 值：true/false
             *
             * 当值为 false 时，所有的日志都不会记录
             */
            'debug' => true,
            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id' => $config['wechat_appid'] ?? '',
            'secret' => $config['wechat_appsecret'] ?? '',
            'token' => $config['wechat_token'] ?? '',
            'aes_key' => $config['wechat_encodingaeskey'] ?? '', // 兼容与安全模式下请一定要填写！！！
            /**
             * 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
             * 使用自定义类名时，构造函数将会接收一个 `EasyWeChat\Kernel\Http\Response` 实例
             */
            'response_type' => 'array',

            /**
             * 日志配置
             *
             * level: 日志级别, 可选为：
             *         debug/info/notice/warning/error/critical/alert/emergency
             * permission：日志文件权限(可选)，默认为null（若为null值,monolog会取0644）
             * file：日志文件位置(绝对路径!!!)，要求可写权限
             */
            'log' => [
                'level' => 'debug',
                'permission' => 0777,
                'file' => $this->createLogPath('wechat'),
            ],
            /**
             * 接口请求相关配置，超时时间等，具体可用参数请参考：
             * http://docs.guzzlephp.org/en/stable/request-options.html
             *
             * - retries: 重试次数，默认 1，指定当 http 请求失败时重试的次数。
             * - retry_delay: 重试延迟间隔（单位：ms），默认 500
             * - log_template: 指定 HTTP 日志模板，请参考：https://github.com/guzzle/guzzle/blob/master/src/MessageFormatter.php
             */
            'http' => [
                'retries' => 1,
                'retry_delay' => 500,
                'timeout' => 5.0,
                // 'base_uri' => 'https://api.weixin.qq.com/',
            ],

            /**
             * OAuth 配置
             *
             * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
             * callback：OAuth授权完成后的回调页地址
             */
            'oauth' => [
                'scopes' => ['snsapi_userinfo'],
                'callback' => $callbackUrl,
            ]
        ], Yii::$app->params['wechatConfig']);

        // 微信支付
        Yii::$app->params['wechatPaymentConfig'] = ArrayHelper::merge([
            'app_id' => $config['wechat_appid'] ?? '',
            'secret' => $config['wechat_appsecret'] ?? '',
            'mch_id' => $config['wechat_mchid'] ?? '',
            'key' => $config['wechat_api_key'] ?? '', // API 密钥
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path' => $config['wechat_cert_path'] ?? '', // XXX: 绝对路径！！！！
            'key_path' => $config['wechat_key_path'] ?? '', // XXX: 绝对路径！！！！
            // 支付回调地址
            'notify_url' => $notifyUrl,
            'sandbox' => false, // 设置为 false 或注释则关闭沙箱模式
        ], Yii::$app->params['wechatPaymentConfig']);

        // 小程序
        Yii::$app->params['wechatMiniProgramConfig'] = ArrayHelper::merge([
            'app_id' => $config['miniprogram_appid'] ?? '',
            'secret' => $config['miniprogram_secret'] ?? '',
            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => $this->createLogPath('miniprogram'),
            ],
        ], Yii::$app->params['wechatMiniProgramConfig']);

        unset($config);
    }

    /**
     * @param $config
     */
    protected function initComponents($config)
    {
        unset($config);
    }

    /**
     * 创建日志文件
     *
     * @param $path
     * @return bool
     */
    private function createLogPath($catalogue)
    {
        $logPath = Yii::getAlias('@runtime') . DIRECTORY_SEPARATOR . 'wechat-' . $catalogue . DIRECTORY_SEPARATOR . date('Y-m') . DIRECTORY_SEPARATOR;

        FileHelper::mkdirs($logPath);
        $logPath .= date('d') . '.log';
        return $logPath;
    }
}