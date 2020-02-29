<?php

namespace common\models\common;

/**
 * This is the model class for table "{{%sys_addons_binding}}".
 *
 * @property int $id 主键
 * @property string $addons_name 插件名称
 * @property string $entry 入口类别[menu,cover]
 * @property string $app_id 应用
 * @property string $title 名称
 * @property string $route 路由
 * @property string $icon 图标
 */
class AddonsBinding extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_addons_binding}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['addons_name', 'route', 'entry', 'title'], 'required'],
            [['addons_name', 'route'], 'string', 'max' => 30],
            [['route'], 'string', 'max' => 200],
            [['entry'], 'string', 'max' => 10],
            [['app_id'], 'string', 'max' => 20],
            [['params'], 'safe'],
            [['title', 'icon'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'addons_name' => '模块名称',
            'app_id' => '应用',
            'entry' => '入口',
            'title' => '标题',
            'params' => '参数',
            'route' => '路由',
            'icon' => 'Icon',
        ];
    }
}
