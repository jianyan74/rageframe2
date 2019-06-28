<?php
namespace common\models\sys;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\enums\AuthEnum;
use common\models\common\User;
use common\models\common\AuthAssignment;

/**
 * This is the model class for table "{{%sys_manager}}".
 *
 * @property int $id
 * @property string $merchant_id 商户id
 * @property string $username 帐号
 * @property string $password_hash 密码
 * @property string $auth_key 授权令牌
 * @property string $password_reset_token 密码重置令牌
 * @property int $type 1:普通管理员;10超级管理员
 * @property string $realname 真实姓名
 * @property string $head_portrait 头像
 * @property int $gender 性别[0:未知;1:男;2:女]
 * @property string $qq qq
 * @property string $email 邮箱
 * @property string $birthday 生日
 * @property int $province_id 省
 * @property int $city_id 城市
 * @property int $area_id 地区
 * @property string $address 默认地址
 * @property string $mobile 手机号码
 * @property string $home_phone 家庭号码
 * @property int $visit_count 访问次数
 * @property int $last_time 最后一次登陆时间
 * @property string $last_ip 最后一次登陆ip
 * @property int $role 权限
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Manager extends User
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_manager}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'type', 'gender', 'province_id', 'city_id', 'area_id', 'visit_count', 'last_time', 'role', 'status', 'created_at', 'updated_at'], 'integer'],
            [['birthday'], 'safe'],
            [['username', 'qq', 'mobile', 'home_phone'], 'string', 'max' => 20],
            [['password_hash', 'password_reset_token', 'head_portrait'], 'string', 'max' => 150],
            [['auth_key'], 'string', 'max' => 32],
            [['realname'], 'string', 'max' => 10],
            [['email'], 'string', 'max' => 60],
            [['address'], 'string', 'max' => 100],
            [['last_ip'], 'string', 'max' => 16],
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
            'username' => '账号',
            'password_hash' => '密码',
            'auth_key' => '授权key',
            'password_reset_token' => '密码重置',
            'type' => '类型',
            'realname' => '真实姓名',
            'head_portrait' => '头像',
            'gender' => '性别',
            'qq' => 'QQ',
            'email' => '邮箱',
            'birthday' => '生日',
            'province_id' => '省',
            'city_id' => '市',
            'area_id' => '区',
            'address' => '地址',
            'mobile' => '手机号码',
            'home_phone' => '电话号码',
            'visit_count' => '访问次数',
            'last_time' => '最后一次登录时间',
            'last_ip' => '最后一次登录IP',
            'role' => '权限',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->auth_key = Yii::$app->security->generateRandomString();
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        AuthAssignment::deleteAll(['user_id' => $this->id, 'type' => AuthEnum::TYPE_BACKEND]);
        return parent::beforeDelete();
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['merchant_id'],
                ],
                'value' => Yii::$app->services->merchant->getId(),
            ]
        ];
    }
}
