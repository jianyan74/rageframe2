<?php
namespace wechat\controllers;

use Yii;
use yii\web\Controller;
use common\models\common\PayLog;
use common\enums\StatusEnum;
use common\helpers\PayHelper;
use common\helpers\StringHelper;
use common\helpers\ArrayHelper;
use common\helpers\FileHelper;

/**
 * 微信支付回调控制器
 *
 * Class NotifyController
 * @package wechat\controllers
 */
class NotifyController extends Controller
{
    /**
     * @var bool
     */
    public $enableCsrfValidation = false;

    public function init()
    {
        $config = Yii::$app->debris->configAll();

        // 微信支付参数配置
        Yii::$app->params['wechatPaymentConfig'] = ArrayHelper::merge([
            'app_id' => $config['wechat_appid'],
            'mch_id' => $config['wechat_mchid'],
            'key' => $config['wechat_api_key'], // API 密钥
            'sandbox' => false, // 设置为 false 或注释则关闭沙箱模式
        ], Yii::$app->params['wechatPaymentConfig']);

        parent::init();
    }

    /**
     * 回调通知
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $response = Yii::$app->wechat->payment->handlePaidNotify(function($message, $fail)
        {
            // 记录写入日志
            $logPath = Yii::getAlias('@wechat') . "/runtime/pay_log/" . date('Y_m_d') . "/" . $message->openid . '.txt';
            FileHelper::writeLog($logPath, json_encode(ArrayHelper::toArray($message)));

            // 如果订单不存在 或者 订单已经支付过了，如果成功返回订单的编号和类型
            if (!($orderInfo = PayHelper::notify($message['out_trade_no'], $message)))
            {
                // 告诉微信，我已经处理完了，订单没找到，别再通知我了
                return true;
            }

            /////////////  建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////

            // 判断订单组别来源 比如课程、购物或者其他
            if ($orderInfo['order_group'] == 1)
            {
                // 查找订单
                if (!($order = Order::fineOne(['order_sn' => $orderInfo['order_sn']])))
                {
                    return true;
                }
            }

            // return_code 表示通信状态，不代表支付状态
            if ($message['return_code'] === 'SUCCESS')
            {
                if (array_get($message, 'result_code') === 'SUCCESS')// 用户支付成功
                {
                    $order->pay_status = StatusEnum::ENABLED;
                }
                else if (array_get($message, 'result_code') === 'FAIL')// 用户支付失败
                {
                    $order->pay_status = StatusEnum::DELETE;
                }
            }
            else
            {
                return $fail('通信失败，请稍后再通知我');
            }

            $order->save(); // 保存订单
            return true; // 返回处理完成
        });

        return $response;
    }

    /**
     * 生成微信JSAPI支付的Demo方法 默认禁止外部访问 测试请修改方法类型
     *
     * @return string
     * @throws Yii\base\ErrorException
     */
    private function actionDemo()
    {
        $totalFee = 100;// 支付金额单位：分
        $orderSn = time() . StringHelper::randomNum();// 订单号

        $orderData = [
            'trade_type' => 'JSAPI', // JSAPI，NATIVE，APP...
            'body' => '支付简单说明',
            'detail' => '支付详情',
            'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'out_trade_no' => PayHelper::getOutTradeNo($totalFee, $orderSn, 1, PayLog::PAY_TYPE_WECHAT, 'JSAPI'), // 支付
            'total_fee' => $totalFee,
            'openid' => '', // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
        ];

        $payment = Yii::$app->wechat->payment;
        $result = $payment->order->unify($orderData);
        if ($result['return_code'] == 'SUCCESS')
        {
            $config = $payment->jssdk->sdkConfig($result['prepay_id']);
        }
        else
        {
            throw new yii\base\ErrorException('微信支付异常, 请稍后再试');
        }

        return $this->render('wxpay', [
            'jssdk' => $payment->jssdk, // $app通过上面的获取实例来获取
            'config' => $config
        ]);
    }
}
