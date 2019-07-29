<?php
namespace common\models\wechat;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%wechat_setting}}".
 *
 * @property int $id
 * @property string $merchant_id 商户id
 * @property string $history 历史消息参数设置
 * @property string $special 特殊消息回复参数
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Setting extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * 特殊消息回复类别 - 关键字
     */
    const SPECIAL_TYPE_KEYWORD = 1;
    /**
     * 特殊消息回复类别 - 模块
     */
    const SPECIAL_TYPE_MODUL = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_setting}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['special'], 'safe'],
            [['history'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户',
            'history' => '历史消息参数设置',
            'special' => '特殊消息回复参数',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
