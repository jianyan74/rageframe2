<?php
namespace addons\Wechat\common\models;
use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%addon_wechat_rule_keyword}}".
 *
 * @property string $id
 * @property string $rule_id 规则ID
 * @property string $module 模块名
 * @property string $content 内容
 * @property int $type 类别
 * @property int $sort 优先级
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 */
class RuleKeyword extends \yii\db\ActiveRecord
{
    use MerchantBehavior;

    const TYPE_MATCH = 1;
    const TYPE_INCLUDE = 2;
    const TYPE_REGULAR = 3;
    const TYPE_TAKE = 4;

    /**
     * @var array
     */
    public static $typeExplain = [
        self::TYPE_MATCH => '直接匹配关键字',
        self::TYPE_INCLUDE => '正则表达式',
        self::TYPE_REGULAR => '包含关键字',
        self::TYPE_TAKE => '直接接管',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_rule_keyword}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'rule_id', 'type', 'sort', 'status'], 'integer'],
            [['module', 'content'], 'required'],
            [['module'], 'string', 'max' => 50],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rule_id' => '规则ID',
            'module' => '处理的模块',
            'content' => '内容',
            'type' => '类别',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}
