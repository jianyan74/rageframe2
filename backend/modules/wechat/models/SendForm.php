<?php
namespace backend\modules\wechat\models;

use Yii;
use common\models\wechat\FansTags;
use common\models\wechat\MassRecord;

/**
 * Class SendForm
 * @package backend\modules\wechat\models
 */
class SendForm extends MassRecord
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
     */
    public function send()
    {
        $app = Yii::$app->wechat->app;
        $method = $this->sendMethod[$this->media_type];

        if (!$this->tag_id)
        {
            $this->tag_name = '全部粉丝';
            $result = $app->broadcasting->$method($this->media_id);
        }
        else
        {
            $result = $app->broadcasting->$method($this->media_id, $this->tag_id);
            // 获取分组信息
            $tag = FansTags::getFindID($this->tag_id);
            $this->tag_name = $tag['name'];
            $this->fans_num = $tag['count'];
        }

        // 解析微信接口是否报错.报错则抛出错误信息
        Yii::$app->debris->analyWechatPortBack($result, false);

        if (Yii::$app->debris->getWechatPortBackError() == false)
        {
            $this->msg_id = $result['msg_id'];
            return $this->save();
        }

        return false;
    }
}