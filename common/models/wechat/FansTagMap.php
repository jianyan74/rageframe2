<?php
namespace common\models\wechat;

use Yii;

/**
 * This is the model class for table "{{%wechat_fans_tag_map}}".
 *
 * @property string $id
 * @property string $fans_id 粉丝id
 * @property string $tag_id 标签id
 */
class FansTagMap extends \yii\db\ActiveRecord
{
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
            [['fans_id', 'tag_id'], 'integer'],
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

    /**
     * 批量添加标签
     *
     * @param $fan_id
     * @param $data
     * @throws \yii\db\Exception
     */
    public static function add($fans_id, $data)
    {
        self::deleteAll(['fans_id' => $fans_id]);

        $field = ['fans_id', 'tag_id'];
        return Yii::$app->db->createCommand()->batchInsert(self::tableName(), $field, $data)->execute();
    }
}
