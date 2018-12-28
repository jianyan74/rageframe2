<?php
namespace common\models\wechat;

/**
 * This is the model class for table "{{%wechat_rule}}".
 *
 * @property string $id
 * @property string $name 规则名称
 * @property string $module 模块
 * @property int $sort 排序
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Rule extends \common\models\common\BaseModel
{
    /**
     * 模块类别
     */
    const RULE_MODULE_TEXT = 'text';
    const RULE_MODULE_NEWS = 'news';
    const RULE_MODULE_MUSIC = 'music';
    const RULE_MODULE_IMAGES = 'images';
    const RULE_MODULE_VOICE = 'voice';
    const RULE_MODULE_VIDEO = 'video';
    const RULE_MODULE_ADDON = 'addon';
    const RULE_MODULE_USER_API = 'user-api';
    const RULE_MODULE_WX_CARD = 'wxcard';
    const RULE_MODULE_DEFAULT = 'default';

    /**
     * @var array
     * 说明
     */
    public static $moduleExplain = [
        self::RULE_MODULE_TEXT => '文字回复',
        self::RULE_MODULE_IMAGES => '图片回复',
        self::RULE_MODULE_NEWS => '图文回复',
        // self::RULE_MODULE_MUSIC => '音乐回复',
        self::RULE_MODULE_VOICE => '语音回复',
        self::RULE_MODULE_VIDEO => '视频回复',
        // self::RULE_MODULE_ADDON => '模块回复',
        self::RULE_MODULE_USER_API => '自定义接口回复',
        // self::RULE_MODULE_WX_CARD => '微信卡卷回复',
        // self::RULE_MODULE_DEFAULT => '默认回复',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_rule}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'unique','message' => '规则名称已经被占用'],
            [['sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'module'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '规则名称',
            'module' => '对应模块',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 获取规则模块模型
     *
     * @param $rule_id
     * @param $module
     * @return \yii\db\ActiveRecord
     */
    public static function getModuleModel($rule_id, $module)
    {
        $modelList = [
            self::RULE_MODULE_TEXT => 'common\models\wechat\ReplyText',
            self::RULE_MODULE_VIDEO => 'common\models\wechat\ReplyVideo',
            self::RULE_MODULE_IMAGES => 'common\models\wechat\ReplyImages',
            self::RULE_MODULE_NEWS => 'common\models\wechat\ReplyNews',
            self::RULE_MODULE_VOICE => 'common\models\wechat\ReplyVoice',
            self::RULE_MODULE_USER_API => 'common\models\wechat\ReplyUserApi',
            self::RULE_MODULE_ADDON => 'common\models\wechat\ReplyAddon',
        ];

        if (!($model = $modelList[$module]::find()->where(['rule_id' => $rule_id])->one()))
        {
            /* @var $model \yii\db\ActiveRecord */
            $model = new $modelList[$module]();
            $model->loadDefaultValues();
        }

        return $model;
    }

    /**
     * 查询规则标题
     *
     * @param $rule_id
     * @return string
     */
    public static function findRuleTitle($rule_id)
    {
        $rule = Rule::findOne($rule_id);
        return $rule ? $rule->name : '规则被删除';
    }

    /**
     * 删除其他数据
     */
    public function afterDelete()
    {
        $id = $this->id;
        // 关键字删除
        RuleKeyword::deleteAll(['rule_id' => $id]);
        // 规则统计
        RuleStat::deleteAll(['rule_id' => $id]);
        // 关键字规则统计
        RuleKeywordStat::deleteAll(['rule_id' => $id]);

        // 删除关联数据
        switch ($this->module)
        {
            case  self::RULE_MODULE_TEXT :
                ReplyText::deleteAll(['rule_id' => $id]);
                break;

            case  self::RULE_MODULE_NEWS :
                ReplyNews::deleteAll(['rule_id' => $id]);
                break;

            case  self::RULE_MODULE_MUSIC :
                // ReplyBasic::deleteAll(['rule_id'=>$id]);
                break;

            case  self::RULE_MODULE_IMAGES :
                ReplyImages::deleteAll(['rule_id' => $id]);
                break;

            case  self::RULE_MODULE_VOICE :
                ReplyVoice::deleteAll(['rule_id' => $id]);
                break;

            case  self::RULE_MODULE_VIDEO :
                ReplyVideo::deleteAll(['rule_id' => $id]);
                break;

            case  self::RULE_MODULE_USER_API :
                ReplyUserApi::deleteAll(['rule_id' => $id]);
                break;

            case  self::RULE_MODULE_WX_CARD :
                // ::deleteAll(['rule_id'=>$id]);
                break;

            default :
                ReplyAddon::deleteAll(['rule_id' => $id]);
                break;
        }

        parent::afterDelete();
    }

    /**
     * 关联关键字
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRuleKeyword()
    {
        return $this->hasMany(RuleKeyword::className(), ['rule_id' => 'id'])->orderBy('type asc');
    }

    /**
     * 关联模块
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAddon()
    {
        return $this->hasMany(ReplyAddon::className(), ['rule_id' => 'id']);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        // 更新状态和排序
        RuleKeyword::updateAll(['sort' => $this->sort, 'status' => $this->status], ['rule_id' => $this->id]);
        parent::afterSave($insert, $changedAttributes);
    }
}
