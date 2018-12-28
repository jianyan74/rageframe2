<?php

namespace common\models\sys;

use common\enums\StatusEnum;
use Yii;

/**
 * This is the model class for table "{{%sys_config}}".
 *
 * @property int $id 主键
 * @property string $title 配置标题
 * @property string $name 配置标识
 * @property string $type 配置类型
 * @property int $cate_id 配置分类
 * @property string $extra 配置值
 * @property string $remark 配置说明
 * @property string $value 配置值
 * @property int $is_hide_remark 是否隐藏说明
 * @property int $sort 排序
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Config extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'name', 'type', 'cate_id'], 'required'],
            [['cate_id', 'is_hide_remark', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['value'], 'string'],
            [['title'], 'string', 'max' => 50],
            [['name', 'type'], 'string', 'max' => 30],
            [['extra'], 'string', 'max' => 255],
            [['remark'], 'string', 'max' => 1000],
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
            'value' => '内容',
            'is_hide_remark' => '是否隐藏说明',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return array
     */
    public static function getList()
    {
        $config = Config::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->all();

        $info = [];
        foreach ($config as $row)
        {
            $info[$row['name']] = $row['value'];
        }

        return $info;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(ConfigCate::className(), ['id' => 'cate_id']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        // 清除缓存
        Yii::$app->debris->configAll(true);
        return parent::beforeSave($insert);
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        // 清除缓存
        Yii::$app->debris->configAll(true);
        return parent::beforeDelete();
    }
}
