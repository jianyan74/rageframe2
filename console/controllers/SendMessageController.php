<?php
namespace console\controllers;

use Yii;
use common\enums\StatusEnum;
use common\models\wechat\MassRecord;
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