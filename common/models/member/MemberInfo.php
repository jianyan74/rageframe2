<?php

namespace common\models\member;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%member_info}}".
 *
 * @property int $id 主键
 * @property string $username 帐号
 * @property string $password_hash 密码
 * @property string $auth_key 授权令牌
 * @property string $password_reset_token 密码重置令牌
 * @property int $type 类别[1:普通会员;10管理员]
 * @property string $nickname 昵称
 * @property string $realname 真实姓名
 * @property string $head_portrait 头像
 * @property int $sex 性别[1:男;2:女]
 * @property string $qq qq
 * @property string $email 邮箱
 * @property string $birthday 生日
 * @property string $user_money 余额
 * @property string $accumulate_money 累积消费
 * @property string $frozen_money 累积金额
 * @property int $user_integral 当前积分
 * @property string $address_id 默认地址
 * @property int $visit_count 访问次数
 * @property string $home_phone 家庭号码
 * @property string $mobile_phone 手机号码
 * @property int $role 权限
 * @property int $last_time 最后一次登陆时间
 * @property string $last_ip 最后一次登陆ip
 * @property int $provinces 省
 * @property int $city 城市
 * @property int $area 地区
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class MemberInfo extends \common\models\common\User
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required', 'on' => ['backendCreate']],
            [['password_hash'], 'string', 'min' => 6, 'on' => ['backendCreate']],
            [['username'], 'unique', 'on' => ['backendCreate']],
            [['type', 'sex', 'user_integral', 'address_id', 'visit_count', 'role', 'last_time', 'provinces', 'city', 'area', 'status', 'created_at', 'updated_at'], 'integer'],
            [['birthday'], 'safe'],
            [['user_money', 'accumulate_money', 'frozen_money'], 'number'],
            [['username', 'qq', 'home_phone', 'mobile_phone'], 'string', 'max' => 20],
            [['password_hash', 'password_reset_token', 'head_portrait'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['nickname'], 'string', 'max' => 50],
            [['realname'], 'string', 'max' => 10],
            [['email'], 'string', 'max' => 60],
            [['last_ip'], 'string', 'max' => 16],
            ['mobile_phone', 'match', 'pattern' => '/^[1][3578][0-9]{9}$/','message' => '不是一个有效的手机号码'],
            ['last_ip', 'default', 'value' => '0.0.0.0'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '账号',
            'password_hash' => '密码',
            'auth_key' => '授权登录key',
            'password_reset_token' => '密码重置token',
            'type' => '类型',
            'nickname' => '昵称',
            'realname' => '真实姓名',
            'head_portrait' => '头像',
            'sex' => '性别',
            'qq' => 'QQ',
            'email' => '邮箱',
            'birthday' => '生日',
            'user_money' => '余额',
            'accumulate_money' => '累计金额',
            'frozen_money' => '冻结金额',
            'user_integral' => '积分',
            'address_id' => '默认收货地址id',
            'visit_count' => '登录总次数',
            'home_phone' => '家庭号码',
            'mobile_phone' => '手机号码',
            'role' => '权限',
            'last_time' => '最后一次登录时间',
            'last_ip' => '最后一次登录ip',
            'provinces' => '省',
            'city' => '市',
            'area' => '区',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 场景
     *
     * @return array
     */
    public function scenarios()
    {
        return [
            'backendCreate' => ['username', 'password_hash'],
            'default' => array_keys($this->attributeLabels()),
        ];
    }

    /**
     * 设置默认收货地址
     *
     * @param $address_id
     */
    public static function setDefaultAddress($member_id, $address_id)
    {
        if ($model = self::findOne($member_id))
        {
            $model->address_id = $address_id;
            $model->save();
        }
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
     * 行为插入时间戳
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
}
