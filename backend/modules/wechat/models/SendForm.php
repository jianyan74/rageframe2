<?php
namespace backend\modules\wechat\models;

use Yii;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\wechat\FansTags;
use common\models\wechat\MassRecord;

/**
 * Class SendForm
 * @package backend\modules\wechat\models
 */
class SendForm extends MassRecord
{
    public $send_type = 1;

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
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['send_type', 'integer'];

        return $rules;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'send_type' => '发送类型'
        ]);
    }

    /**
     * 群发消息
     */
    public function send($immediately = true)
    {
        $app = Yii::$app->wechat->app;
        $method = $this->sendMethod[$this->media_type];
        $sendContent = $method == 'sendText' ? $this->content : $this->media_id;

        // 立即发送写入时间
        $immediately == true && $this->send_time = $this->final_send_time = time();

        if (!$this->tag_id)
        {
            $this->tag_name = '全部粉丝';
            $immediately == true && $result = $app->broadcasting->$method($sendContent);
        }
        else
        {
            $immediately == true && $result = $app->broadcasting->$method($sendContent, $this->tag_id);
            // 获取分组信息
            $tag = FansTags::findById($this->tag_id);
            $this->tag_name = $tag['name'];
            $this->fans_num = $tag['count'];
        }

        if (!empty($result))
        {
            Yii::$app->debris->getWechatError($result);
            $this->msg_id = $result['msg_id'];
            $this->send_status = StatusEnum::ENABLED;
        }

        return $this->save();
    }
}