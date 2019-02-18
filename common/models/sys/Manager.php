<?php
namespace common\models\sys;

use common\helpers\RegularHelper;
use Yii;

/**
 * This is the model class for table "{{%sys_manager}}".
 *
 * @property int $id
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
 * @property int $provinces 省
 * @property int $city 城市
 * @property int $area 地区
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
class Manager extends \common\models\common\User
{
    /**
     * @inheritdoc
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
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['password_hash','username'], 'required'],
            ['password_hash', 'string', 'min' => 6],
            ['username', 'unique','message' => '用户账户已经占用'],
            [['type', 'gender', 'provinces', 'city', 'area', 'visit_count', 'last_time', 'role', 'status', 'created_at', 'updated_at'], 'integer'],
            [['birthday'], 'safe'],
            [['username', 'qq', 'mobile', 'home_phone'], 'string', 'max' => 20],
            [['password_hash', 'password_reset_token', 'head_portrait'], 'string', 'max' => 150],
            [['auth_key'], 'string', 'max' => 32],
            [['realname'], 'string', 'max' => 10],
            [['email'], 'string', 'max' => 60],
            [['address'], 'string', 'max' => 100],
            [['last_ip'], 'string', 'max' => 16],
            ['last_ip','default', 'value' => '0.0.0.0'],
            ['mobile','match','pattern' => RegularHelper::mobile(),'message'=>'不是一个有效的手机号码'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '登录名',
            'password_hash' => '登录密码',
            'auth_key' => '授权秘钥',
            'password_reset_token' => '密码重置验证秘钥',
            'type' => '管理员类型',
            'nickname' => '昵称',
            'realname' => '真实姓名',
            'head_portrait' => '个人头像',
            'gender' => '性别',
            'qq' => 'qq',
            'email' => '邮箱',
            'birthday' => '出生日期',
            'address' => '详细地址',
            'provinces' => '省份',
            'city' => '城市',
            'area' => '区',
            'visit_count' => '登陆次数',
            'home_phone' => '家庭电话',
            'mobile' => '手机号码',
            'role' => '权限',
            'status' => '状态',
            'last_time' => '最后登录的时间',
            'last_ip' => '最后登录的IP地址',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getManagers()
    {
        return self::find()
            ->where(['<>', 'id', Yii::$app->user->id])
            ->asArray()
            ->all();
    }

    /**
     * 关联权限
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssignment()
    {
        return $this->hasOne(AuthAssignment::class, ['user_id' => 'id']);
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord)
        {
            $this->auth_key = Yii::$app->security->generateRandomString();
        }
        
        return parent::beforeSave($insert);
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        AuthAssignment::deleteAll(['user_id' => $this->id]);
        return parent::beforeDelete();
    }
}
