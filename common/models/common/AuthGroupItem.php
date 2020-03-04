<?php

namespace common\models\common;

use common\enums\StatusEnum;
use Yii;

/**
 * This is the model class for table "{{%common_auth_group_item}}".
 *
 * @property int $group_id 分组ID
 * @property int $item_id 权限ID
 * @property string $name 权限名称
 * @property string $app_id 应用ID
 * @property string $addons_name 插件名称
 * @property int $is_menu 是否菜单
 * @property int $is_addon 是否插件
 */
class AuthGroupItem extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_auth_group_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_id', 'item_id', 'is_menu', 'is_addon'], 'integer'],
            [['name', 'addons_name'], 'string', 'max' => 200],
            [['app_id'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'group_id' => 'Group ID',
            'item_id' => 'Item ID',
            'name' => 'Name',
            'app_id' => 'App ID',
            'addons_name' => 'Addons Name',
            'is_menu' => 'Is Menu',
            'is_addon' => 'Is Addon',
        ];
    }

    public function getItem()
    {
        return $this->hasOne(AuthItem::class, ['id' => 'item_id'])
            ->orderBy('sort asc, id asc')
            ->where(['status' => StatusEnum::ENABLED])
            ->select(['id', 'title', 'name', 'pid', 'level', 'app_id', 'is_addon', 'addons_name', 'is_menu']);
    }
}
