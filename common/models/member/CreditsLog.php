<?php
namespace common\models\member;

use Yii;
use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%member_credits_log}}".
 *
 * @property int $id
 * @property string $merchant_id 商户id
 * @property string $member_id 用户id
 * @property string $credit_type 变动类型[integral:积分;money:余额]
 * @property string $credit_group 变动的组别
 * @property string $credit_group_detail 变动的详细组别
 * @property double $old_num 之前的数据
 * @property double $new_num 变动后的数据
 * @property double $num 变动的数据
 * @property string $remark 备注
 * @property string $ip ip地址
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class CreditsLog extends \common\models\common\BaseModel
{
    use MerchantBehavior;

    const CREDIT_TYPE_USER_MONEY = 'user_money';
    const CREDIT_TYPE_USER_INTEGRAL = 'user_integral';

    /**
     * 变动类型
     *
     * @var array
     */
    public static $creditTypeExplain = [
        self::CREDIT_TYPE_USER_MONEY => '余额日志',
        self::CREDIT_TYPE_USER_INTEGRAL => '积分日志',
    ];

    const CREDIT_GROUP_MANAGER = 'manager';

    /**
     * 变动组别
     *
     * @var array
     */
    public static $creditGroupExplain = [
        self::CREDIT_GROUP_MANAGER => '管理员',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_credits_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'member_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['old_num', 'new_num', 'num'], 'number'],
            [['credit_type', 'credit_group', 'credit_group_detail', 'ip'], 'string', 'max' => 30],
            [['remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户',
            'member_id' => '用户',
            'ip' => 'ip地址',
            'credit_type' => '变动类型',
            'credit_group' => '操作类型',
            'credit_group_detail' => '操作详细类型',
            'old_num' => '变更之前',
            'new_num' => '变更后',
            'num' => '变更数量',
            'remark' => '备注',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->ip = Yii::$app->request->userIP;
        }

        return parent::beforeSave($insert);
    }
}
