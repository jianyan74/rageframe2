<?php
namespace common\components;

use Yii;
use yii\base\Component;
use common\payment\AliPay;
use common\payment\UnionPay;
use common\payment\WechatPay;
use common\helpers\ArrayHelper;

/**
 * Class Pay
 * @package common\components
 */
class Pay extends Component
{
    /**
     * 公用配置
     *
     * @var
     */
    protected $_rfConfig;

    /**
     * Pay constructor.
     */
    public function __construct()
    {
        $this->_rfConfig = Yii::$app->debris->configAll();
    }

    /**
     * 支付宝支付
     *
     * @param array $config
     * @return AliPay
     */
    public function alipay(array $config = [])
    {
        return new AliPay(ArrayHelper::merge([
            'app_id' => $this->_rfConfig['alipay_appid'],
            'notify_url' => Yii::$app->request->hostInfo . Yii::$app->urlManager->createUrl(['notify/index']),
            'return_url' => Yii::$app->request->hostInfo . Yii::$app->urlManager->createUrl(['return/index']),
            'ali_public_key' => $this->_rfConfig['alipay_cert_path'],
            // 加密方式： ** RSA2 **
            'private_key' => $this->_rfConfig['alipay_key_path'],
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
            'app_id' => $this->_rfConfig['wechat_appid'], // 公众号 APPID
            'mch_id' => $this->_rfConfig['wechat_mchid'],
            'api_key' => $this->_rfConfig['wechat_api_key'],
            'cert_client' => $this->_rfConfig['wechat_cert_path'], // optional，退款等情况时用到
            'cert_key' => $this->_rfConfig['wechat_key_path'],// optional，退款等情况时用到
        ], $config));
    }

    /**
     * 银联支付
     *
     * @param array $config
     * @return WechatPay
     */
    public function union(array $config = [])
    {
        return new UnionPay(ArrayHelper::merge([
            'mch_id' => $this->_rfConfig['UNION_MCHID'],
            'cert_id' => $this->_rfConfig['UNION_CERT_ID'],
            'notify_url' => Yii::$app->request->hostInfo . Yii::$app->urlManager->createUrl(['notify/index']),
            'return_url' => Yii::$app->request->hostInfo . Yii::$app->urlManager->createUrl(['return/index']),
            'public_key' => $this->_rfConfig['UNION_PUBLIC_KEY'],
            'private_key' => $this->_rfConfig['UNION_PRIVATE_KEY'],
        ], $config));
    }

    /**
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
            if($this->$name())
            {
                return $this->$name([]);
            }
            else
            {
                throw $e->getPrevious();
            }
        }
    }
}