<?php
namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%common_sms_log}}".
 *
 * @property int $id
 * @property int $member_id 用户id
 * @property string $mobile 手机号码
 * @property string $content 内容
 * @property int $error_code 报错code
 * @property string $error_msg 报错信息
 * @property string $error_data 报错日志
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class SmsLog extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_sms_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'error_code', 'status', 'created_at', 'updated_at'], 'integer'],
            [['error_data'], 'string'],
            [['mobile'], 'string', 'max' => 20],
            [['content'], 'string', 'max' => 1000],
            [['error_msg'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'mobile' => '手机号码',
            'content' => '内容',
            'error_code' => '报错 Code',
            'error_msg' => '报错说明',
            'error_data' => '具体报错信息',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
