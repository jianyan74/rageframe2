<?php
namespace common\models\sys;

use Yii;
use common\enums\StatusEnum;
use common\models\common\BaseModel;
use common\helpers\AddonHelper;
use common\helpers\StringHelper;
use Overtrue\Pinyin\Pinyin;

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
 * @property string $config 配置
 * @property int $setting 设置
 * @property string $author 作者
 * @property string $version 版本号
 * @property string $wechat_message 接收微信回复类别
 * @property int $is_hook 钩子[0:不支持;1:支持]
 * @property int $is_rule 是否要嵌入规则
 * @property int $is_wxapp_support 小程序[0:不支持;1:支持]
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Addons extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_addons}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'unique','message' => '模块名已经占用'],
            [['name', 'title', 'group', 'version'], 'required'],
            ['name','match','pattern'=>'/^[_a-zA-Z]+$/','message' => '标识由英文和下划线组成'],
            [['config'], 'string'],
            [['is_setting', 'is_hook', 'is_rule', 'is_mini_program', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'group', 'version'], 'string', 'max' => 20],
            [['name', 'author'], 'string', 'max' => 40],
            [['title_initial'], 'string', 'max' => 1],
            [['description'], 'string', 'max' => 1000],
            [['wechat_message'], 'safe'],
            [['brief_introduction'], 'string', 'max' => 140],
            [['cover'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '模块名称',
            'name' => '模块标识',
            'title_initial' => '首字母拼音',
            'cover' => '封面',
            'group' => '组别',
            'brief_introduction' => '简介',
            'description' => '详细说明',
            'config' => '配置信息',
            'author' => '作者',
            'version' => '版本',
            'wechat_message' => '接收微信消息',
            'is_hook' => '钩子',
            'is_rule' => '嵌入规则',
            'is_setting' => '全局设置项',
            'is_mini_program' => 'Api/小程序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 根据模块标识获取模块
     *
     * @param $name
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findByName($name)
    {
        return self::find()
            ->where(['name' => $name, 'status' => StatusEnum::ENABLED])
            ->with(['binding'])
            ->one();
    }

    /**
     * @return array
     */
    public static function getLocalList()
    {
        $addon_dir = Yii::getAlias('@addons');

        // 获取插件列表
        $dirs = array_map('basename', glob($addon_dir . '/*'));
        $where = ['in', 'name', $dirs];
        $list =	self::find()
            ->where($where)
            ->asArray()
            ->all();

        $tmpAddons = [];
        foreach($list as $addon)
        {
            $tmpAddons[$addon['name']]	= $addon;
        }

        $addons = [];
        foreach ($dirs as $value)
        {
            // 判断是否安装
            if(!isset($tmpAddons[$value]))
            {
                $class = AddonHelper::getAddonConfig($value);
                // 实例化插件失败忽略执行
                if (class_exists($class))
                {
                    $config = new $class;
                    $addons[$value]	= $config->info;
                }
            }
        }

        return $addons;
    }

    /**
     * 获取全部列表
     *
     * @param string $type [插件:plug;模块:addon]
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getList()
    {
        $models = self::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();

        return $models ?? [];
    }

    /**
     * 编辑信息
     *
     * @param object $addons
     * @throws \Exception
     */
    public static function edit($model, $addonsConfig)
    {
        $model->attributes = $addonsConfig->info;
        $model->is_setting = $addonsConfig->isSetting ? StatusEnum::ENABLED : StatusEnum::DISABLED;
        $model->is_hook = $addonsConfig->isHook ? StatusEnum::ENABLED : StatusEnum::DISABLED;
        $model->is_rule = $addonsConfig->isRule ? StatusEnum::ENABLED : StatusEnum::DISABLED;
        $model->is_mini_program = $addonsConfig->isMiniProgram ? StatusEnum::ENABLED : StatusEnum::DISABLED;
        $model->group = $addonsConfig->group;
        $model->wechat_message = isset($addonsConfig->wechatMessage) ? serialize($addonsConfig->wechatMessage) : '';

        // 首先字母转大写拼音
        if (($chinese = StringHelper::strToChineseCharacters($model->title)) && !empty($chinese[0]))
        {
            $title_initial = mb_substr($chinese[0][0], 0, 1, 'utf-8');
            $pinyin = new Pinyin();
            $model->title_initial = ucwords($pinyin->abbr($title_initial));
        }

        $model->updated_at = time();
        if (!$model->save())
        {
            $error = Yii::$app->debris->analyErr($model->getFirstErrors());
            throw new \Exception($error);
        }

        return true;
    }

    /**
     * 关联绑定的菜单
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBindingMenu()
    {
        return $this->hasMany(AddonsBinding::className(), ['addons_name' => 'name'])->where(['entry' => 'menu'])->orderBy('id asc');
    }

    /**
     * 关联绑定的入口
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBindingCover()
    {
        return $this->hasMany(AddonsBinding::className(), ['addons_name' => 'name'])->where(['entry' => 'cover'])->orderBy('id asc');
    }

    /**
     * 关联绑定的菜单和导航
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBinding()
    {
        return $this->hasMany(AddonsBinding::className(), ['addons_name' => 'name'])->orderBy('id asc');
    }

    /**
     * 关联权限
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItem()
    {
        return $this->hasMany(AddonsAuthItem::className(), ['addons_name' => 'name']);
    }

    /**
     * 关联权限的菜单
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChild()
    {
        return $this->hasOne(AddonsAuthItemChild::className(), ['addons_name' => 'name']);
    }

    /**
     * 卸载插件的时候清理安装的信息
     */
    public function afterDelete()
    {
        AddonsBinding::deleteAll(['addons_name' => $this->name]);
        AddonsAuthItemChild::deleteAll(['addons_name' => $this->name]);
        AddonsAuthItem::deleteAll(['addons_name' => $this->name]);
        // Rule::deleteAll($this->name);
        parent::afterDelete();
    }
}
