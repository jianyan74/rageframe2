<?php
namespace common\models\wechat;

use Yii;

/**
 * This is the model class for table "{{%wechat_qrcode_stat}}".
 *
 * @property string $id
 * @property string $qrcord_id 二维码id
 * @property string $openid 微信openid
 * @property int $type 1:关注;2:扫描
 * @property string $name 场景名称
 * @property string $scene_str 场景值
 * @property string $scene_id 场景ID
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class QrcodeStat extends \common\models\common\BaseModel
{
    const TYPE_ATTENTION = 1;
    const TYPE_SCAN = 2;

    /**
     * @var array
     */
    public static $typeExplain = [
        self::TYPE_ATTENTION => '关注',
        self::TYPE_SCAN => '扫描',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_qrcode_stat}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['qrcord_id', 'type', 'scene_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['openid', 'name'], 'string', 'max' => 50],
            [['scene_str'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'qrcord_id' => '二维码id',
            'openid' => '用户openid',
            'type' => '二维码类别',
            'name' => '二维码名称',
            'scene_str' => '场景值',
            'scene_id' => '场景id',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 判断二维码扫描事件
     *
     * @param array $message 微信消息
     * @return bool|mixed
     */
    public static function scan($message)
    {
        // 关注事件
        if ($message['Event'] == Account::TYPE_SUBSCRIBE && !empty($message['Ticket']))
        {
            if ($qrCode = Qrcode::getFindWhereFirst(['ticket' => trim($message['Ticket'])]))
            {
                static::add($qrCode, $message['FromUserName'], self::TYPE_ATTENTION);
                return $qrCode['keyword'];
            }
        }

        // 扫描事件
        $where = ['scene_str' => $message['EventKey']];
        if (is_numeric($message['EventKey']))
        {
            $where = ['scene_id' => $message['EventKey']];
        }

        if ($qrCode = Qrcode::getFindWhereFirst($where))
        {
            static::add($qrCode, $message['FromUserName'], self::TYPE_SCAN);
            return $qrCode['keyword'];
        }

        return false;
    }

    /**
     * 插入扫描记录
     *
     * @param $qrCode
     * @param $openid
     * @param $type
     */
    public static function add($qrCode, $openid, $type)
    {
        $model = new self();
        $model->attributes = $qrCode;
        $model->openid = $openid;
        $model->type = $type;
        $model->save();
    }

    /**
     * 关联粉丝
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFans()
    {
        return $this->hasOne(Fans::className(), ['openid' => 'openid'])->select('openid, nickname, follow');
    }
}
