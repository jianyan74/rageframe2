<?php
namespace common\models\common;

use common\enums\StatusEnum;

/**
 * This is the model class for table "{{%common_auth_item_child}}".
 *
 * @property string $role_id 角色id
 * @property string $item_id 权限id
 * @property string $name 别名
 * @property string $type 类别
 * @property string $type_child 子类别
 * @property string $addons_name 插件名称
 */
class AuthItemChild extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_auth_item_child}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'item_id', 'is_menu'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['type', 'type_child'], 'string', 'max' => 20],
            [['addons_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'item_id' => 'Item ID',
            'name' => 'Name',
            'type' => 'Type',
            'type_child' => 'Type Child',
            'addons_name' => 'Addons Name',
            'is_menu' => '是否菜单',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(AuthItem::class, ['id' => 'item_id'])
            ->orderBy('sort asc, id asc')
            ->where(['status' => StatusEnum::ENABLED])
            ->select(['id', 'title', 'name', 'pid', 'level', 'type', 'type_child', 'addons_name', 'is_menu']);
    }
}
