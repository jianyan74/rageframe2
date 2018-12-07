<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\common\PayLog;
use common\enums\StatusEnum;
use common\helpers\PayHelper;
use common\helpers\ArrayHelper;
use common\helpers\FileHelper;

/**
 * 支付回调
 *
 * Class NotifyController
 * @package frontend\controllers
 */
class NotifyController extends Controller
{
    /**
     * 关闭csrf
     *
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * 配置
     *
     * @var
     */
    protected $config;

    public function init()
    {
        $this->config = Yii::$app->debris->configAll();

        parent::init();
    }

    /**
     * EasyWechat支付回调 - 微信
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     */
    public function actionEasyWechat()
    {
        // 微信支付参数配置
        Yii::$app->params['wechatPaymentConfig'] = ArrayHelper::merge([
            'app_id' => $this->config['wechat_appid'],
            'mch_id' => $this->config['wechat_mchid'],
            'key' => $this->config['wechat_api_key'], // API 密钥
        ], Yii::$app->params['wechatPaymentConfig']);

        $response = Yii::$app->wechat->payment->handlePaidNotify(function($message, $fail)
        {
            // 记录写入文件日志
            $logPath = Yii::getAlias('@runtime') . "/pay_log/" . date('Y_m_d') . "/" . $message->openid . '.txt';
            FileHelper::writeLog($logPath, json_encode(ArrayHelper::toArray($message)));

            // 如果订单不存在 或者 订单已经支付过了，如果成功返回订单的编号和类型
            if (!($orderInfo = PayHelper::notify($message['out_trade_no'], $message)))
            {
                // 告诉微信，我已经处理完了，订单没找到，别再通知我了
                return true;
            }

            /////////////  建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////

            // 判断订单组别来源 比如课程、购物或者其他
            if ($orderInfo['order_group'] == PayLog::ORDER_GROUP)
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
     * EasyWechat支付回调 - 小程序
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     */
    public function actionMiniProgram()
    {
        // 微信支付参数配置
        Yii::$app->params['wechatPaymentConfig'] = ArrayHelper::merge([
            'app_id' => $this->config['miniprogram_appid'],
            'mch_id' => $this->config['wechat_mchid'],
            'key' => $this->config['wechat_api_key'], // API 密钥
        ], Yii::$app->params['wechatPaymentConfig']);

        $response = Yii::$app->wechat->payment->handlePaidNotify(function($message, $fail)
        {
            // 你的回调处理 同上
        });

        return $response;
    }

    /**
     * 公用支付回调 - 支付宝
     */
    public function actionAli()
    {
        $request = Yii::$app->pay->alipay->notify();

        try
        {
            $response = $request->send();
            if($response->isPaid())
            {
                /**
                 * Payment is successful
                 */
                die('success'); //The notify response should be 'success' only
            }
            else
            {
                /**
                 * Payment is not successful
                 */
                die('fail'); //The notify response
            }
        }
        catch (\Exception $e)
        {
            /**
             * Payment is not successful
             */
            die('fail'); //The notify response
        }
    }

    /**
     * 公用支付回调 - 银联
     */
    public function actionUnion()
    {
        $response = Yii::$app->pay->union->notify();
        if ($response->isPaid())
        {
            //pay success
        }
        else
        {
            //pay fail
        }
    }

    /**
     * 公用支付回调 - 微信
     */
    public function actionWechat()
    {
        $response = Yii::$app->pay->wechat->notify();
        if ($response->isPaid())
        {
            //pay success 注意微信会发二次消息过来 需要判断是通知还是回调
            var_dump($response->getRequestData());

            // 成功通知
            return PayHelper::notifyWechatSuccess();
        }
        else
        {
            // 失败通知
            return PayHelper::notifyWechatFail();
        }
    }
}
