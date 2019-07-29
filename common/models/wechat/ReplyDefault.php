<?php
namespace common\models\wechat;

use common\behaviors\MerchantBehavior;
use common\models\base\BaseModel;

/**
 * This is the model class for table "{{%wechat_reply_default}}".
 *
 * @property int $id
 * @property string $follow_content 关注回复关键字
 * @property string $default_content 默认回复关键字
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class ReplyDefault extends BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_reply_default}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['follow_content', 'default_content'], 'string', 'max' => 200],
            [['follow_content', 'default_content'], 'trim'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'follow_content' => '关注回复关键字',
            'default_content' => '默认回复关键字',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
