<?php
namespace common\components;

use Yii;
use yii\base\Component;
use common\components\payment\AliPay;
use common\components\payment\UnionPay;
use common\components\payment\WechatPay;
use common\helpers\ArrayHelper;
use common\helpers\Url;

/**
 * 支付组件
 *
 * Class Pay
 * @package common\components
 * @property \common\components\payment\WechatPay $wechat
 * @property \common\components\payment\AliPay $alipay
 * @property \common\components\payment\UnionPay $union
 * @author jianyan74 <751393839@qq.com>
 */
class Pay extends Component
{
    /**
     * 公用配置
     *
     * @var
     */
    protected $rfConfig;

    public function init()
    {
        $this->rfConfig = Yii::$app->debris->configAll();

        parent::init();
    }

    /**
     * 支付宝支付
     *
     * @param array $config
     * @return AliPay
     * @throws \yii\base\InvalidConfigException
     */
    public function alipay(array $config = [])
    {
        return new AliPay(ArrayHelper::merge([
            'app_id' => $this->rfConfig['alipay_appid'],
            'notify_url' => Url::toFront(['notify/ali']),
            'return_url' => '',
            'ali_public_key' => $this->rfConfig['alipay_cert_path'],
            // 加密方式： ** RSA2 **
            'private_key' => $this->rfConfig['alipay_key_path'],
        ], $config));
    }

    /**
     * 微信支付
     *
     * @param array $config
     * @return WechatPay
     */
    public function wechat(array $config = [])
    {
        return new WechatPay(ArrayHelper::merge([
            'app_id' => $this->rfConfig['wechat_appid'], // 公众号 APPID
            'mch_id' => $this->rfConfig['wechat_mchid'],
            'api_key' => $this->rfConfig['wechat_api_key'],
            'cert_client' => $this->rfConfig['wechat_cert_path'], // optional，退款等情况时用到
            'cert_key' => $this->rfConfig['wechat_key_path'],// optional，退款等情况时用到
        ], $config));
    }

    /**
     * 银联支付
     *
     * @param array $config
     * @return UnionPay
     * @throws \yii\base\InvalidConfigException
     */
    public function union(array $config = [])
    {
        return new UnionPay(ArrayHelper::merge([
            'mch_id' => $this->rfConfig['union_mchid'],
            'notify_url' => Url::toFront(['notify/union']),
            'return_url' => '',
            'cert_id' => $this->rfConfig['union_cert_id'],
            'private_key' => $this->rfConfig['union_private_key'],
        ], $config));
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (\Exception $e) {
            if ($this->$name()) {
                return $this->$name([]);
            } else {
                throw $e->getPrevious();
            }
        }
    }
}