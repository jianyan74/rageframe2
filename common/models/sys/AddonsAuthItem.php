<?php
namespace common\models\sys;

use Yii;

/**
 * This is the model class for table "{{%sys_addons_auth_item}}".
 *
 * @property string $addons_name 插件名称
 * @property string $route 插件路由
 * @property string $description 说明
 */
class AddonsAuthItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_addons_auth_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['addons_name'], 'string', 'max' => 30],
            [['route'], 'string', 'max' => 64],
            [['description'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'addons_name' => '模块名称',
            'route' => '路由',
            'description' => '说明'
        ];
    }

    /**
     * 创建
     *
     * @param $data
     * @param $addons_name
     * @throws \Exception
     */
    public static function add($data, $addons_name)
    {
        self::deleteAll(['addons_name' => $addons_name]);
        AddonsAuthItemChild::deleteAll(['addons_name' => $addons_name]);

        foreach ($data as $key => $vo)
        {
            $model = new self();
            $model->addons_name = $addons_name;
            $model->route = $key;
            $model->description = $vo;
            if (!$model->save())
            {
                $error = Yii::$app->debris->analyErr($model->getFirstErrors());
                throw new \Exception($error);
            }
        }
    }
}
