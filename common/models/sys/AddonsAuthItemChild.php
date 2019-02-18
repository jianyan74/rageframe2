<?php
namespace common\models\sys;

use Yii;

/**
 * This is the model class for table "{{%sys_addons_auth_item_child}}".
 *
 * @property string $parent
 * @property string $child
 * @property string $addons_name 插件名称
 *
 * @property AuthItem $parent0
 */
class AddonsAuthItemChild extends \yii\db\ActiveRecord
{
    const TYPE_COVER = 1; // 核心设置
    const TYPE_MENU = 2; // 菜单路由

    const AUTH_COVER = 'rfAddonsCover'; // 入口管理路由
    const AUTH_RULE = 'rfAddonsRule'; // 规则管理路由
    const AUTH_SETTING = 'setting/display'; // 参数设置管理路由

    /**
     * @var array
     */
    public static $authExplain = [
        self::AUTH_COVER => '应用入口',
        self::AUTH_RULE => '规则管理',
        self::AUTH_SETTING => '参数设置',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_addons_auth_item_child}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent'], 'required'],
            [['parent', 'child'], 'string', 'max' => 64],
            [['addons_name'], 'string', 'max' => 30],
            [['type'], 'integer'],
            [['parent'], 'unique'],
            [['parent'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::class, 'targetAttribute' => ['parent' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'parent' => 'Parent',
            'child' => 'Child',
            'addons_name' => 'Addons Name',
            'type' => 'type',
        ];
    }

    /**
     * 重新写入授权
     *
     * @param string $parent 角色名称
     * @param array $auth 授权的路由数组
     * @return bool|int
     * @throws \yii\db\Exception
     */
    public static function accredit($parent, $auth)
    {
        // 删除原先所有权限
        self::deleteAll(['parent' => $parent]);

        $data = [];
        foreach ($auth as $value)
        {
            $data[] = [$parent, $value['child'], $value['addons_name'], $value['type']];
        }

        if (!empty($data))
        {
            // 批量插入数据
            $field = ['parent', 'child', 'addons_name', 'type'];
            return Yii::$app->db->createCommand()->batchInsert(self::tableName(), $field, $data)->execute();
        }

        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddon()
    {
        return $this->hasOne(Addons::class, ['name' => 'addons_name'])->asArray()->select(['title', 'name', 'group']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(AuthItem::class, ['name' => 'parent']);
    }
}
