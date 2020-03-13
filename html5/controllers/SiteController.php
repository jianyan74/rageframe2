<?php

namespace html5\controllers;

use Yii;
use common\helpers\StringHelper;
use common\helpers\Url;

/**
 * Class SiteController
 * @package wechat\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SiteController extends BaseController
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
     * 生成微信JSAPI支付的Demo方法
     *
     * 默认禁止外部访问
     * 测试请修改方法类型
     *
     * 注意：请开启微信的安全支付路径
     * 域名/html5/site
     *
     * @return string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function actionWechatPay()
    {
        $totalFee = 100;// 支付金额单位：分
        $out_trade_no = time() . StringHelper::randomNum();

        $orderData = [
            'trade_type' => 'JSAPI', // JSAPI，NATIVE，APP...
            'body' => '支付简单说明',
            'detail' => '支付详情',
            'notify_url' => Url::removeMerchantIdUrl('toFront', ['notify/wechat']), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'out_trade_no' => $out_trade_no, // 支付
            'total_fee' => $totalFee,
            'openid' => Yii::$app->params['wechatMember']['id'], // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
        ];

        $payment = Yii::$app->wechat->payment;
        $result = $payment->order->unify($orderData);
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
            $config = $payment->jssdk->sdkConfig($result['prepay_id']);

            /**
             * 注意：如果需要调用扫码支付 请设置 trade_type 为 NATIVE
             *
             * 结果示例：weixin://wxpay/bizpayurl?sign=XXXXX&appid=XXXXX&mch_id=XXXXX&product_id=XXXXXX&time_stamp=XXXXXX&nonce_str=XXXXX
             */

            /**
             * $content = $payment->scheme($result['prepay_id']);
             * $qr = Yii::$app->get('qr');
             * Yii::$app->response->format = Response::FORMAT_RAW;
             * Yii::$app->response->headers->add('Content-Type', $qr->getContentType());
             *
             * return $qr->setText($content)
             * ->setSize(150)
             * ->setMargin(7)
             * ->writeString();
             */
        } else {
            p($result);
            die();
        }

        return $this->render($this->action->id, [
            'jssdk' => $payment->jssdk, // $app通过上面的获取实例来获取
            'config' => $config
        ]);
    }
}
