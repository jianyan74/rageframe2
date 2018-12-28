<?php
namespace common\models\wechat;

use common\enums\StatusEnum;

/**
 * This is the model class for table "{{%wechat_msg_history}}".
 *
 * @property string $id
 * @property string $rule_id 规则id
 * @property int $keyword_id 关键字id
 * @property string $openid
 * @property string $module 触发模块
 * @property string $message 微信消息
 * @property string $type
 * @property string $event 详细事件
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class MsgHistory extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_msg_history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rule_id', 'keyword_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['openid', 'module'], 'string', 'max' => 50],
            [['message'], 'string', 'max' => 1000],
            [['type', 'event'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rule_id' => '规则ID',
            'keyword_id' => '关键字ID',
            'openid' => 'Openid',
            'module' => '触发模块',
            'message' => '微信消息',
            'type' => '消息类型',
            'event' => '事件',
            'status' => '状态',
            'created_at' => '创建事件',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 写入历史记录
     *
     * @param array $data 历史消息记录
     * @param array $message 微信消息
     */
    public static function setData($data, $message)
    {
        $setting = Setting::getData('history');
        // 记录历史
        if (!isset($setting['history_status']) || $setting['history_status'] == StatusEnum::ENABLED)
        {
            $msgHistory = new self();
            $data['message'] = $data['type'] == 'text' ? $message['Content'] : self::filtrate($message);
            $msgHistory->attributes = $data;
            $msgHistory->save();
        }

        // 统计记录
        if (!isset($setting['utilization_status']) || $setting['utilization_status'] == StatusEnum::ENABLED)
        {
            // 插入规则统计
            !empty($data['rule_id']) && RuleStat::setStat($data['rule_id']);
            // 插入关键字统计
            if (!empty($data['keyword_id']) && !empty($data['rule_id']))
            {
                RuleKeywordStat::setStat($data['rule_id'], $data['keyword_id']);
            }
        }

        return;
    }

    /**
     * 过滤消息信息
     *
     * @param $message
     * @return string
     */
    public static function filtrate($message)
    {
        $arr = [];
        $filtrate = ['ToUserName', 'FromUserName', 'CreateTime', 'MsgType'];
        foreach ($message as $key => $value)
        {
            if (!in_array($key, $filtrate))
            {
                $arr[$key] = $value;
            }
        }

        return serialize($arr);
    }

    /**
     * 解析微信发过来的消息内容
     *
     * @param $type
     * @param $messgae
     * @return mixed|string
     */
    public static function readMessage($type, $messgae)
    {
        switch ($type)
        {
            case Account::TYPE_TEXT :
                return $messgae;
                break;

            case Account::TYPE_IMAGE :
                $messgae = unserialize($messgae);
                return $messgae['PicUrl'];
                break;

            case Account::TYPE_VIDEO :
                $messgae = unserialize($messgae);
                return "MediaId【" . $messgae['MediaId'] . "】";
                break;

            case Account::TYPE_LOCATION :
                $messgae = unserialize($messgae);
                return '主动发送位置 : 经纬度【'.$messgae['Location_X'] . ',' . $messgae['Location_Y'] . "】<br>地址 : " . $messgae['Label'];
                break;

            case Account::TYPE_CILCK :
                $messgae = unserialize($messgae);
                return '单击菜单触发 : ' . $messgae['EventKey'];
                break;

            case Account::TYPE_SUBSCRIBE :
                return '关注公众号';
                break;

            case Account::TYPE_VOICE :
                $messgae = unserialize($messgae);
                return isset($messgae['Recognition']) ? $messgae['Recognition'] : '语音消息';
                break;

            // 触发事件
            case Account::TYPE_EVENT :

                $messgae = unserialize($messgae);
                switch ($messgae['Event'])
                {
                    case Account::TYPE_UN_SUBSCRIBE :
                        return '取消关注公众号';
                        break;

                    case Account::TYPE_SUBSCRIBE :
                        return '通过二维码关注公众号 : '. str_replace('qrscene_', '', $messgae['EventKey']);
                        break;

                    case Account::TYPE_EVENT_LOCATION :
                        return '被动发送位置 : 经纬度【'.$messgae['Latitude'] . ',' . $messgae['Longitude'] . "】精度:" . $messgae['Precision'];
                        break;

                    case Account::TYPE_EVENT_VIEW :
                        return "单击菜单访问 : " . $messgae['EventKey'];
                        break;

                    case Account::TYPE_CILCK :
                        return "单击菜单触发关键字 : " . $messgae['EventKey'];
                        break;

                    case Account::TYPE_SCAN :
                        return "二维码扫描 : " . $messgae['EventKey'];
                        break;

                    case 'location_select' :
                        $sendLocationInfo = $messgae['SendLocationInfo'];
                        return "主动发送位置 : " . '经纬度【'.$sendLocationInfo['Location_X'] . ',' . $sendLocationInfo['Location_Y'] . "】地址:" . $sendLocationInfo['Label'];
                        break;

                    case 'scancode_waitmsg' :
                        $scanCodeInfo = $messgae['ScanCodeInfo'];
                        return "调用二维码扫描等待返回地址 : " . $scanCodeInfo['ScanResult'];
                        break;

                    case 'pic_sysphoto' :
                        return "调用拍照发图";
                        break;

                    case 'pic_photo_or_album' :
                        return "调用拍照相册";
                        break;

                    case 'scancode_push' :
                        $scanCodeInfo = $messgae['ScanCodeInfo'];
                        return "调用二维码直接扫描返回地址 : " . $scanCodeInfo['ScanResult'];
                        break;

                    case 'MASSSENDJOBFINISH' :
                        return "点击图文MsgID为 : " . $messgae['MsgID'];
                        break;

                    default :
                        return serialize($messgae);
                        break;
                }

                break;

            default :
                return $messgae;
                break;
        }
    }

    /**
     * 关联粉丝
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFans()
    {
        return $this->hasOne(Fans::className(), ['openid' => 'openid']);
    }
}
