<?php
namespace common\models\member;

use Yii;
use common\behaviors\MerchantBehavior;
use common\helpers\RegularHelper;
use common\enums\StatusEnum;

/**
 * This is the model class for table "{{%member_address}}".
 *
 * @property int $id 主键
 * @property string $merchant_id 商户id
 * @property string $member_id 用户id
 * @property string $province_id 省id
 * @property string $city_id 市id
 * @property string $area_id 区id
 * @property string $address_name 地址
 * @property string $address_details 详细地址
 * @property int $is_default 默认地址
 * @property string $zip_code 邮编
 * @property string $realname 真实姓名
 * @property string $home_phone 家庭号码
 * @property string $mobile 手机号码
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Address extends \common\models\common\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_address}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['province_id', 'city_id', 'area_id', 'address_details', 'realname', 'mobile'], 'required'],
            ['mobile', 'match', 'pattern' => RegularHelper::mobile(), 'message' => '不是一个有效的手机号码'],
            [['merchant_id', 'member_id', 'province_id', 'city_id', 'area_id', 'is_default', 'zip_code', 'status', 'created_at', 'updated_at'], 'integer'],
            [['address_name', 'address_details'], 'string', 'max' => 200],
            [['realname'], 'string', 'max' => 100],
            [['home_phone', 'mobile'], 'string', 'max' => 20],
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
            'province_id' => '省',
            'city_id' => '市',
            'area_id' => '区',
            'address_name' => '地址',
            'address_details' => '详细地址',
            'is_default' => '默认地址',
            'zip_code' => '邮编',
            'realname' => '真实姓名',
            'home_phone' => '电话',
            'mobile' => '手机号码',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 关联用户
     *
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
        $this->address_name = Yii::$app->services->provinces->getCityListName([$this->province_id, $this->city_id, $this->area_id]);
        if ($this->is_default == StatusEnum::ENABLED) {
            self::updateAll(['is_default' => StatusEnum::DISABLED], ['member_id' => $this->member_id, 'is_default' => StatusEnum::ENABLED]);
        }

        return parent::beforeSave($insert);
    }
}
