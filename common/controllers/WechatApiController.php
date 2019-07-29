<?php

namespace common\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\helpers\WechatHelper;

/**
 * 接收微信消息处理控制器
 *
 * Class WechatApiController
 * @package common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class WechatApiController extends Controller
{
    /**
     * 微信请求关闭CSRF验证
     *
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * 处理微信消息
     *
     * @return array|mixed
     * @throws NotFoundHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;

        switch ($request->getMethod()) {
            // 激活公众号
            case 'GET':
                if (WechatHelper::verifyToken($request->get('signature'), $request->get('timestamp'), $request->get('nonce'))) {
                    return $request->get('echostr');
                }

                throw new NotFoundHttpException('签名验证失败.');
                break;
            // 接收数据
            case 'POST':
                $app = Yii::$app->wechat->getApp();
                $app->server->push(function ($message) {
                    try {
                        // 微信消息
                        Yii::$app->services->wechatMessage->setMessage($message);// 消息记录
                        Yii::$app->params['msgHistory'] = [
                            'openid' => $message['FromUserName'],
                            'type' => $message['MsgType'],
                            'event' => '',
                            'rule_id' => 0, // 规则id
                            'keyword_id' => 0, // 关键字id
                        ];

                        switch ($message['MsgType']) {
                            case 'event' : // '收到事件消息';
                                $reply = $this->event($message);
                                break;
                            case 'text' : //  '收到文字消息';
                                $reply = Yii::$app->services->wechatMessage->text();
                                break;
                            default : // ... 其它消息(image、voice、video、location、link、file ...)
                                $reply = Yii::$app->services->wechatMessage->other();
                                break;
                        }

                        Yii::$app->services->wechatMsgHistory->save(Yii::$app->params['msgHistory'], Yii::$app->services->wechatMessage->getMessage());
                        return $reply;
                    } catch (\Exception $e) {
                        // 记录行为日志
                        Yii::$app->services->log->setStatusCode(500);
                        Yii::$app->services->log->setStatusText('wechatApiReply');
                        Yii::$app->services->log->setErrData($e->getMessage());
                        Yii::$app->services->log->insertLog();

                        if (YII_DEBUG) {
                            return $e->getMessage();
                        }

                        return '系统出错，请联系管理员';
                    }
                });

                // 将响应输出
                $response = $app->server->serve();
                $response->send();
                break;
            default:
                throw new NotFoundHttpException('所请求的页面不存在.');
        }

        exit();
    }

    /**
     * 事件处理
     *
     * @param $message
     * @return bool|mixed
     * @throws NotFoundHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    protected function event($message)
    {
        Yii::$app->params['msgHistory']['event'] = $message['Event'];

        switch ($message['Event']) {
            // 关注事件
            case 'subscribe' :
                Yii::$app->services->wechatFans->follow($message['FromUserName']);

                // 判断是否是二维码关注
                if ($qrResult = Yii::$app->services->wechatQrcodeStat->scan($message)) {
                    $message['Content'] = $qrResult;
                    Yii::$app->services->wechatMessage->setMessage($message);
                    return Yii::$app->services->wechatMessage->text();
                }

                return Yii::$app->services->wechatMessage->follow();
                break;
            // 取消关注事件
            case 'unsubscribe' :
                Yii::$app->services->wechatFans->unFollow($message['FromUserName']);
                return false;
                break;
            // 二维码扫描事件
            case 'SCAN' :
                if ($qrResult = Yii::$app->services->wechatQrcodeStat->scan($message)) {
                    $message['Content'] = $qrResult;
                    Yii::$app->services->wechatMessage->setMessage($message);
                    return Yii::$app->services->wechatMessage->text();
                }
                break;
            // 上报地理位置事件
            case 'LOCATION' :

                //TODO 暂时不处理

                break;
            // 自定义菜单(点击)事件
            case 'CLICK' :
                $message['Content'] = $message['EventKey'];
                Yii::$app->services->wechatMessage->setMessage($message);
                return Yii::$app->services->wechatMessage->text();
                break;
        }

        return false;
    }
}