<?php
namespace services\wechat;

use Yii;
use common\helpers\ExecuteHelper;
use common\models\wechat\Setting;
use common\components\Service;
use common\helpers\AddonHelper;
use common\models\wechat\Rule;
use common\enums\StatusEnum;
use common\models\wechat\MassRecord;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Video;
use EasyWeChat\Kernel\Messages\Voice;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;

/**
 * Class MessageService
 * @package services\wechat
 * @author jianyan74 <751393839@qq.com>
 */
class MessageService extends Service
{
    protected $message;

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
     * @param MassRecord $massRecord
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function send(MassRecord $massRecord)
    {
        // 每次都需要重载配置
        $this->afreshLoad($massRecord->merchant_id);

        try {
            $sendContent = $massRecord->data;

            // 如果是图文
            if ($massRecord->module == Rule::RULE_MODULE_NEWS) {
                $sendContent = $massRecord->attachment->media_id;
            }

            $method = $this->sendMethod[$massRecord->module];
            if ($massRecord->tag_id > 0) {
                $result = Yii::$app->wechat->app->broadcasting->$method($sendContent, $massRecord->tag_id);
            } else {
                $result = Yii::$app->wechat->app->broadcasting->$method($sendContent);
            }

            Yii::$app->debris->getWechatError($result);

            MassRecord::updateAll([
                'final_send_time' => time(),
                'send_status' => StatusEnum::ENABLED,
                'msg_id' => isset($result['msg_id']) ?? 0,
                'msg_data_id' => isset($result['msg_data_id']) ?? 0,
            ], ['id' => $massRecord->id]);

            return true;
        } catch (\Exception $e) {
            MassRecord::updateAll([
                'error_content' => $e->getMessage(),
                'send_status' => StatusEnum::DELETE,
            ], ['id' => $massRecord->id]);

            return false;
        }
    }

    /**
     * 发送客服消息
     *
     * @param $openid
     * @param $type
     * @param $data
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function customer($openid, $type, $data)
    {
        switch ($type) {
            // 文字回复
            case Rule::RULE_MODULE_TEXT :
                $message =  new Text($data);
                break;
            // 图片回复
            case Rule::RULE_MODULE_IMAGE :
                $message = new Image($data);
                break;
            // 图文回复
            case Rule::RULE_MODULE_NEWS :
                $new = Yii::$app->services->wechatAttachmentNews->first($data);
                $newsList[] = new NewsItem([
                    'title' => $new['title'],
                    'description' => $new['digest'],
                    'url' => $new['media_url'],
                    'image' => $new['thumb_url'],
                ]);

                $message = new News($newsList);
                break;
            // 视频回复
            case Rule::RULE_MODULE_VIDEO :
                $video = Yii::$app->services->wechatAttachment->findByMediaId($data);
                $message = new Video($data, [
                    'title' => $video['file_name'],
                    'description' => $video['description'],
                ]);
                break;
            // 语音回复
            case Rule::RULE_MODULE_VOICE :
                $message = new Voice($data);
                break;
        }

        $result = Yii::$app->wechat->app->customer_service->message($message)->to($openid)->send();
        Yii::$app->debris->getWechatError($result);
    }

    /**
     * 写入消息
     *
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * 获取微信消息
     *
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 文字匹配回复
     *
     * @return bool|mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function text()
    {
        $message = Yii::$app->services->wechatMessage->getMessage();
        // 查询用户关键字匹配
        if (!($reply = Yii::$app->services->wechatRuleKeyword->match($message['Content']))) {
            $replyDefault = Yii::$app->services->wechatReplyDefault->findOne();
            if ($replyDefault->default_content) {
                $reply = Yii::$app->services->wechatRuleKeyword->match($replyDefault->default_content);
            } else {
                return false;
            }
        }

        return $reply;
    }

    /**
     * 关注匹配回复
     *
     * @return bool|mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function follow()
    {
        $replyDefault = Yii::$app->services->wechatReplyDefault->findOne();
        if ($replyDefault->follow_content) {
            return Yii::$app->services->wechatRuleKeyword->match($replyDefault->follow_content);
        }

        return false;
    }

    /**
     * 其他匹配回复
     *
     * @return bool|mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function other()
    {
        $message = $this->getMessage();
        $msgType = $message['MsgType'];
        $special = Yii::$app->services->wechatSetting->getByFieldName('special');
        if (isset($special[$msgType])) {
            // 关键字
            if ($special[$msgType]['type'] == Setting::SPECIAL_TYPE_KEYWORD) {
                if ($default = Yii::$app->services->wechatRuleKeyword->match($special[$msgType]['content'])) {
                    return $default;
                }
            }

            // 模块处理
            if (!empty($special[$msgType]['selected'])) {
                Yii::$app->params['msgHistory']['module'] = Rule::RULE_MODULE_ADDON;
                Yii::$app->params['msgHistory']['addons_name'] = $special[$msgType]['selected'];

                $class = AddonHelper::getAddonMessage($special[$msgType]['selected']);
                return ExecuteHelper::map($class, 'run', $message);
            }
        }

        return false;
    }
}