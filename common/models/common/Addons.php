<?php
namespace common\models\common;

use Yii;
use common\models\wechat\Rule;
use common\models\wechat\RuleKeyword;
use common\enums\AuthEnum;
use common\enums\StatusEnum;

/**
 * This is the model class for table "{{%sys_addons}}".
 *
 * @property int $id 主键
 * @property string $title 中文名
 * @property string $name 插件名或标识
 * @property string $title_initial 首字母拼音
 * @property string $cover
 * @property string $group 组别
 * @property string $type 详细类别
 * @property string $brief_introduction 简单介绍
 * @property string $description 插件描述
 * @property int $setting 设置
 * @property string $author 作者
 * @property string $version 版本号
 * @property string $bootstrap 启动
 * @property string $wechat_message 接收微信回复类别
 * @property int $is_hook 钩子[0:不支持;1:支持]
 * @property int $is_rule 是否要嵌入规则
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Addons extends BaseModel
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
        return '{{%common_addons}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'unique', 'message' => '模块名已经占用'],
            [['name', 'title', 'group', 'version', 'author'], 'required'],
            ['name','match','pattern'=>'/^[_a-zA-Z]+$/','message' => '标识由英文和下划线组成'],
            [['is_setting', 'is_hook', 'is_rule', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'group', 'version'], 'string', 'max' => 20],
            [['name', 'author'], 'string', 'max' => 40],
            [['title_initial'], 'string', 'max' => 1],
            [['description'], 'string', 'max' => 1000],
            [['wechat_message'], 'safe'],
            [['brief_introduction'], 'string', 'max' => 140],
            [['cover', 'bootstrap'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'icon' => '图标',
            'title' => '插件名称',
            'name' => '插件标识',
            'title_initial' => '首字母拼音',
            'cover' => '封面',
            'group' => '组别',
            'brief_introduction' => '简介',
            'description' => '详细说明',
            'author' => '作者',
            'version' => '版本',
            'wechat_message' => '接收微信消息',
            'is_hook' => '钩子',
            'is_rule' => '嵌入规则',
            'is_setting' => '全局设置项',
            'is_mini_program' => 'Api/小程序',
            'bootstrap' => '启动',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 关联绑定的菜单
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBindingMenu()
    {
        return $this->hasMany(AddonsBinding::class, ['addons_name' => 'name'])->where(['entry' => 'menu'])->orderBy('id asc');
    }

    /**
     * 关联首页绑定的菜单
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBindingIndexMenu()
    {
        return $this->hasOne(AddonsBinding::class, ['addons_name' => 'name'])->where(['entry' => 'menu'])->asArray()->orderBy('id asc');
    }

    /**
     * 关联授权的后台菜单
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthChildMenuByBackend()
    {
        $role = Yii::$app->services->authRole->getRole();
        return $this->hasOne(AuthItemChild::class, ['addons_name' => 'name'])
            ->where(['is_menu' => StatusEnum::ENABLED, 'type' => AuthEnum::TYPE_BACKEND, 'role_id' => $role['id'] ?? -1])
            ->orderBy('item_id asc');
    }

    /**
     * 关联绑定的入口
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBindingCover()
    {
        return $this->hasMany(AddonsBinding::class, ['addons_name' => 'name'])->where(['entry' => 'cover'])->orderBy('id asc');
    }

    /**
     * 关联绑定的菜单和导航
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBinding()
    {
        return $this->hasMany(AddonsBinding::class, ['addons_name' => 'name'])->orderBy('id asc');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfig()
    {
        return $this->hasOne(AddonsConfig::class, ['addons_name' => 'name']);
    }

    /**
     * 卸载插件的时候清理安装的信息
     */
    public function afterDelete()
    {
        // 移除绑定的菜单导航
        AddonsBinding::deleteAll(['addons_name' => $this->name]);
        AddonsConfig::deleteAll(['addons_name' => $this->name]);
        // 卸载权限
        Yii::$app->services->authItem->uninstallAddonsByName($this->name);

        // 移除关键字
        if ($replys = Rule::find()->where(['module' => Rule::RULE_MODULE_ADDON, 'data' => $this->name])->asArray()->all()) {
            $ruleIds = array_column($replys, 'rule_id');
            Rule::deleteAll(['in', 'id', $ruleIds]);
            RuleKeyword::deleteAll(['in', 'rule_id', $ruleIds]);
        }

        parent::afterDelete();
    }
}
