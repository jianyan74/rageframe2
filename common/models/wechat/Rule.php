<?php
namespace common\models\wechat;

use Yii;
use common\behaviors\MerchantBehavior;

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
class Rule extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * 模块类别
     */
    const RULE_MODULE_TEXT = 'text';
    const RULE_MODULE_NEWS = 'news';
    const RULE_MODULE_MUSIC = 'music';
    const RULE_MODULE_IMAGE = 'image';
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
        self::RULE_MODULE_IMAGE => '图片回复',
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
            ['name', 'unique','message' => '规则名称已经被占用',
                'filter' => function ($query) {
                    $query->andWhere(['merchant_id' => Yii::$app->services->merchant->getId()]);
                }],
            [['merchant_id', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'module'], 'string', 'max' => 50],
            [['data'], 'string'],
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
     * 关联关键字
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRuleKeyword()
    {
        return $this->hasMany(RuleKeyword::class, ['rule_id' => 'id'])->orderBy('type asc');
    }

    /**
     * 关联资源
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttachment()
    {
        return $this->hasMany(Attachment::class, ['media_id' => 'data']);
    }


    /**
     * 关联图文资源
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(AttachmentNews::class,['attachment_id' => 'data'])->orderBy('id asc');
    }

    /**
     * 关联图文资源
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsTop()
    {
        return $this->hasOne(AttachmentNews::class,['attachment_id' => 'data'])->where(['sort' => 0]);
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

        parent::afterDelete();
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
