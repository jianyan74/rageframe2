<?php
namespace console\controllers;

use Yii;
use common\enums\StatusEnum;
use common\models\wechat\MassRecord;
use common\helpers\ArrayHelper;
use yii\console\Controller;

/**
 * Class SendMessageController
 * @package console\controllers
 */
class SendMessageController extends Controller
{
    /**
     * 群发消息
     *
     * @var array
     */
    protected $sendMethod = [
        'text' => 'sendText',
        'news' => 'sendNews',
        'voice' => 'sendVoice',
        'image' => 'sendImage',
        'video' => 'sendVideo',
        'card' => 'sendCard',
    ];

    public function init()
    {
        // 全部配置参数
        $config = Yii::$app->debris->configAll();

        // 微信公众号
        Yii::$app->params['wechatConfig'] = ArrayHelper::merge([
            /**
             * Debug 模式，bool 值：true/false
             *
             * 当值为 false 时，所有的日志都不会记录
             */
            'debug'  => true,
            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id' => $config['wechat_appid'],
            'secret' => $config['wechat_appsecret'],
            'token' => $config['wechat_token'],
            'aes_key' => $config['wechat_encodingaeskey'],     // 兼容与安全模式下请一定要填写！！！
            /**
             * 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
             * 使用自定义类名时，构造函数将会接收一个 `EasyWeChat\Kernel\Http\Response` 实例
             */
            'response_type' => 'array',
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
                // 'base_uri'      => 'https://api.weixin.qq.com/',
            ],
        ], Yii::$app->params['wechatConfig']);

        parent::init();
    }

    /**
     * 群发消息
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function actionIndex()
    {
        $model = MassRecord::find()
            ->where(['send_status' => StatusEnum::DISABLED])
            ->andWhere(['<=', 'send_time', time()])
            ->one();

        if ($model)
        {
            try
            {
                $app = Yii::$app->wechat->app;
                $method = $this->sendMethod[$model->media_type];

                $sendContent = $method == 'sendText' ? $model->content : $model->media_id;
                $result = $app->broadcasting->$method($sendContent);

                // 校验报错
                Yii::$app->debris->getWechatError($result);

                $model->final_send_time = time();
                $model->send_status = StatusEnum::ENABLED;
                $model->save();

                echo date('Y-m-d H:i:s') . ' --- ' . '发送成功;' . PHP_EOL;
                exit();
            }
            catch (\Exception $e)
            {
                $model->send_status = StatusEnum::DELETE;
                $model->error_content = $e->getMessage();
                $model->save();

                echo date('Y-m-d H:i:s') . ' --- ' . '发送失败 --- ' . $e->getMessage() . PHP_EOL;
                exit();
            }
        }

        echo date('Y-m-d H:i:s') . ' --- ' . '未找到待发送的数据;' . PHP_EOL;
        exit();
    }
}