<?php
namespace wechat\controllers;

use Yii;
use common\helpers\PayHelper;
use common\helpers\StringHelper;
use common\models\common\PayLog;
use common\helpers\UrlHelper;

/**
 * Site controller
 */
class SiteController extends WController
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // 个人信息
        // p(Yii::$app->wechat->user);
        // p(Yii::$app->params['wechatMember']);

        return $this->render('index', [

        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        return $this->render('login', [

        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * 生成微信JSAPI支付的Demo方法 默认禁止外部访问 测试请修改方法类型
     *
     * @return string
     * @throws Yii\base\ErrorException
     * @throws \yii\base\InvalidConfigException
     */
    private function actionDemo()
    {
        $totalFee = 100;// 支付金额单位：分
        $orderSn = time() . StringHelper::randomNum();// 订单号

        $orderData = [
            'trade_type' => 'JSAPI', // JSAPI，NATIVE，APP...
            'body' => '支付简单说明',
            'detail' => '支付详情',
            'notify_url' => UrlHelper::toFront(['notify/wechat']), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'out_trade_no' => PayHelper::getOutTradeNo($totalFee, $orderSn, 1, PayLog::PAY_TYPE_WECHAT, 'JSAPI'), // 支付
            'total_fee' => $totalFee,
            'openid' => 'okFAZ0-5AbCyvn1m_ujTxmUwzlYo', // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
        ];

        $payment = Yii::$app->wechat->payment;
        $result = $payment->order->unify($orderData);
        if ($result['return_code'] == 'SUCCESS')
        {
            $config = $payment->jssdk->sdkConfig($result['prepay_id']);
        }
        else
        {
            p($result);die();
        }

        return $this->render('wxpay', [
            'jssdk' => $payment->jssdk, // $app通过上面的获取实例来获取
            'config' => $config
        ]);
    }
}
