<?php

namespace common\models\sys;

use Yii;

/**
 * This is the model class for table "{{%sys_addons_binding}}".
 *
 * @property int $id 主键
 * @property string $addons_name 插件名称
 * @property string $entry 入口类别[menu,cover]
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
        return '{{%sys_addons_binding}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['addons_name', 'route', 'entry', 'title'], 'required'],
            [['addons_name', 'route'], 'string', 'max' => 30],
            [['entry'], 'string', 'max' => 10],
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
            'entry' => '入口',
            'title' => '标题',
            'route' => '路由',
            'icon' => 'Icon',
        ];
    }

    /**
     * 创建
     *
     * @param $data
     * @param $entry
     * @param $addons_name
     * @throws \Exception
     */
    public static function careteEntry($data, $entry, $addons_name)
    {
        self::deleteAll(['entry' => $entry, 'addons_name' => $addons_name]);
        foreach ($data as $vo)
        {
            $model = new AddonsBinding();
            $model->attributes = $vo;
            $model->entry = $entry;
            $model->addons_name = $addons_name;
            if (!$model->save())
            {
                $error = Yii::$app->debris->analyErr($model->getFirstErrors());
                throw new \Exception($error);
            }
        }
    }
}
