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
class AddonsAuthItem extends yii\db\ActiveRecord
{
    const TYPE_SYS = 1; // 系统自带权限
    const TYPE_ADDON = 2; // 插件权限

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
            [['type'], 'integer'],
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
            'description' => '说明',
        ];
    }

    /**
     * 创建
     *
     * @param $data
     * @param $addons_name
     * @throws \Exception
     */
    public static function add($addonsConfig, $addons_name)
    {
        self::deleteAll(['addons_name' => $addons_name]);
        AddonsAuthItemChild::deleteAll(['addons_name' => $addons_name]);

       $rfAuth = [
           [
               'route' => AddonsAuthItemChild::AUTH_COVER,
               'description' => '应用入口',
           ],
           [
               'route' => AddonsAuthItemChild::AUTH_RULE,
               'description' => '规则管理',
           ],
           [
               'route' => AddonsAuthItemChild::AUTH_SETTING,
               'description' => '参数设置',
           ],
       ];

       foreach ($rfAuth as $value)
       {
           $model = new self();
           $model->attributes = $value;
           $model->addons_name = $addons_name;
           $model->type = self::TYPE_SYS;
           if (!$model->save())
           {
               $error = Yii::$app->debris->analyErr($model->getFirstErrors());
               throw new \Exception($error);
           }
       }

        $data = $addonsConfig->authItem;
        foreach ($data as $key => $vo)
        {
            $model = new self();
            $model->addons_name = $addons_name;
            $model->route = $key;
            $model->description = $vo;
            $model->type = self::TYPE_ADDON;
            if (!$model->save())
            {
                $error = Yii::$app->debris->analyErr($model->getFirstErrors());
                throw new \Exception($error);
            }
        }
    }
}
