<?php
namespace common\models\wechat;

use Yii;
use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%wechat_fans_tag_map}}".
 *
 * @property string $id
 * @property string $fans_id 粉丝id
 * @property string $tag_id 标签id
 */
class FansTagMap extends \yii\db\ActiveRecord
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_fans_tag_map}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'fans_id', 'tag_id'], 'integer'],
            [['fans_id', 'tag_id'], 'unique', 'targetAttribute' => ['fans_id', 'tag_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fans_id' => '粉丝id',
            'tag_id' => '标签id',
        ];
    }
}
