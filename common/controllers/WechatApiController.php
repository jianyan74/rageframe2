<?php
namespace common\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use common\models\wechat\Fans;
use common\helpers\WechatHelper;
use common\models\wechat\WechatMessage;
use common\models\wechat\MsgHistory;
use common\models\wechat\QrcodeStat;

/**
 * 接收微信消息处理控制器
 *
 * Class WechatApiController
 * @package common\controllers
 */
class WechatApiController extends BaseController
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

        switch ($request->getMethod())
        {
            // 激活公众号
            case 'GET':
                if (WechatHelper::verifyToken($request->get('signature'), $request->get('timestamp'), $request->get('nonce')))
                {
                    return $request->get('echostr');
                }

                throw new NotFoundHttpException('签名验证失败.');

                break;

            // 接收数据
            case 'POST':
                $app = Yii::$app->wechat->getApp();
                $app->server->push(function ($message)
                {
                    // 微信消息
                    Yii::$app->params['wechatMessage'] = $message;
                    // 消息记录
                    Yii::$app->params['msgHistory'] = [
                        'openid' => $message['FromUserName'],
                        'type' => $message['MsgType'],
                        'event' => '',
                        'rule_id' => 0, // 规则id
                        'keyword_id' => 0, // 关键字id
                    ];

                    switch ($message['MsgType'])
                    {
                        case 'event' : // '收到事件消息';
                            $reply = $this->event($message);
                            break;
                        case 'text' : //  '收到文字消息';
                            $reply = WechatMessage::text();
                            break;
                        default : // ... 其它消息(image、voice、video、location、link、file ...)
                            $reply = WechatMessage::other();
                            break;
                    }

                    MsgHistory::setData(Yii::$app->params['msgHistory'], $message);
                    unset(Yii::$app->params['wechatMessage'], Yii::$app->params['msgHistory']);

                    return $reply;
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
     */
    public function event($message)
    {
        Yii::$app->params['msgHistory']['event'] = $message['Event'];

        switch ($message['Event'])
        {
            // 关注事件
            case 'subscribe' :
                Fans::follow($message['FromUserName']);

                // 判断是否是二维码关注
                if ($qrResult = QrcodeStat::scan($message))
                {
                    Yii::$app->params['wechatMessage']['Content'] = $qrResult;
                    return WechatMessage::text();
                }

                return WechatMessage::follow();
                break;
            // 取消关注事件
            case 'unsubscribe' :
                Fans::unFollow($message['FromUserName']);
                return false;
                break;
            // 二维码扫描事件
            case 'SCAN' :
                if ($qrResult = QrcodeStat::scan($message))
                {
                    Yii::$app->params['wechatMessage']['Content'] = $qrResult;
                    return WechatMessage::text();
                }
                break;
            // 上报地理位置事件
            case 'LOCATION' :

                break;
            // 自定义菜单(点击)事件
            case 'CLICK' :
                Yii::$app->params['wechatMessage']['Content'] = $message['EventKey'];
                return WechatMessage::text();
                break;
        }

        return false;
    }
}