<?php
namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%common_config}}".
 *
 * @property string $id 主键
 * @property string $title 配置标题
 * @property string $name 配置标识
 * @property string $type 配置类型
 * @property string $cate_id 配置分类
 * @property string $extra 配置值
 * @property string $remark 配置说明
 * @property int $is_hide_remark 是否隐藏说明
 * @property string $default_value 默认配置
 * @property string $sort 排序
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Config extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'name', 'type', 'cate_id'], 'required'],
            [['cate_id', 'is_hide_remark', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'name'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 30],
            [['extra', 'remark'], 'string', 'max' => 1000],
            [['default_value'], 'string', 'max' => 500],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'name' => '标识',
            'type' => '属性',
            'cate_id' => '分类',
            'extra' => '配置项',
            'remark' => '说明',
            'is_hide_remark' => '是否隐藏说明',
            'default_value' => '默认值',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(ConfigCate::class, ['id' => 'cate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValue()
    {
        return $this->hasOne(ConfigValue::class, ['config_id' => 'id'])->where(['merchant_id' => Yii::$app->services->merchant->getId()]);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        // 重新写入缓存
        Yii::$app->debris->configAll(true);
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return bool
     */
    public function afterDelete()
    {
        // 重新写入缓存
        Yii::$app->debris->configAll(true);
        ConfigValue::deleteAll(['config_id' => $this->id]);
        return parent::beforeDelete();
    }
}
