<?php

namespace common\models\rbac;

use common\enums\StatusEnum;

/**
 * This is the model class for table "{{%rbac_auth_item_child}}".
 *
 * @property int $role_id 角色id
 * @property int $item_id 权限id
 * @property string $name 别名
 * @property string $app_id 类别
 * @property int $is_addon 是否插件
 * @property string $addons_name 插件名称
 */
class AuthItemChild extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%rbac_auth_item_child}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'item_id', 'is_addon'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['app_id'], 'string', 'max' => 20],
            [['addons_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'role_id' => '角色id',
            'item_id' => '权限id',
            'name' => '别名',
            'app_id' => '类别',
            'is_addon' => '是否插件',
            'addons_name' => '插件名称',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(AuthItem::class, ['id' => 'item_id'])
            ->orderBy('sort asc, id asc')
            ->where(['status' => StatusEnum::ENABLED]);
    }
}
