<?php
namespace addons\Wechat\common\models;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%addon_wechat_fans_tags}}".
 *
 * @property int $id
 * @property string $tags 标签
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class FansTags extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_fans_tags}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tags'], 'string'],
            [['merchant_id', 'status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tags' => '标签',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
