<?php
namespace addons\Wechat\common\models;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%addon_wechat_msg_history}}".
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
class MsgHistory extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_msg_history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'rule_id', 'keyword_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['openid', 'module'], 'string', 'max' => 50],
            [['message'], 'string', 'max' => 1000],
            [['addons_name'], 'string', 'max' => 100],
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
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 关联粉丝
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFans()
    {
        return $this->hasOne(Fans::class, ['openid' => 'openid']);
    }

    /**
     * 关联规则
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRule()
    {
        return $this->hasOne(Rule::class, ['id' => 'rule_id']);
    }
}
