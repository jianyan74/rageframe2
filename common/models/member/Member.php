<?php

namespace common\models\member;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\enums\StatusEnum;
use common\models\base\User;
use common\helpers\RegularHelper;
use common\traits\Tree;

/**
 * This is the model class for table "{{%member}}".
 *
 * @property int $id 主键
 * @property string $merchant_id 商户id
 * @property string $username 帐号
 * @property string $password_hash 密码
 * @property string $auth_key 授权令牌
 * @property string $password_reset_token 密码重置令牌
 * @property int $type 类别[1:普通会员;10管理员]
 * @property string $nickname 昵称
 * @property string $realname 真实姓名
 * @property string $head_portrait 头像
 * @property int $current_level 当前级别
 * @property int $gender 性别[0:未知;1:男;2:女]
 * @property string $qq qq
 * @property string $email 邮箱
 * @property string $birthday 生日
 * @property string $visit_count 访问次数
 * @property string $home_phone 家庭号码
 * @property string $mobile 手机号码
 * @property int $role 权限
 * @property int $last_time 最后一次登录时间
 * @property string $last_ip 最后一次登录ip
 * @property int $province_id 省
 * @property int $city_id 城市
 * @property int $area_id 地区
 * @property string $pid 上级id
 * @property string $tree 树
 * @property string $level 级别
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Member extends User
{
    use Tree;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member}}';
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
            [['id', 'current_level', 'level', 'merchant_id', 'type', 'gender','visit_count', 'role', 'last_time', 'province_id', 'city_id', 'area_id', 'pid', 'status', 'created_at', 'updated_at'], 'integer'],
            [['birthday'], 'safe'],
            [['username', 'qq', 'home_phone', 'mobile'], 'string', 'max' => 20],
            [['password_hash', 'password_reset_token', 'head_portrait'], 'string', 'max' => 150],
            [['auth_key'], 'string', 'max' => 32],
            [['nickname', 'realname'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 60],
            [['tree'], 'string', 'max' => 2000],
            [['last_ip'], 'string', 'max' => 16],
            ['mobile', 'match', 'pattern' => RegularHelper::mobile(),'message' => '不是一个有效的手机号码'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => 'Merchant ID',
            'username' => '账号',
            'password_hash' => '密码',
            'auth_key' => '授权登录key',
            'password_reset_token' => '密码重置token',
            'type' => '类型',
            'nickname' => '昵称',
            'realname' => '真实姓名',
            'head_portrait' => '头像',
            'current_level' => '当前级别',
            'gender' => '性别',
            'qq' => 'QQ',
            'email' => '邮箱',
            'birthday' => '生日',
            'visit_count' => '登录总次数',
            'home_phone' => '家庭号码',
            'mobile' => '手机号码',
            'role' => '权限',
            'last_time' => '最后一次登录时间',
            'last_ip' => '最后一次登录ip',
            'province_id' => 'Province ID',
            'city_id' => 'City ID',
            'area_id' => 'Area ID',
            'pid' => '上级id',
            'level' => '级别',
            'tree' => '树',
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
        $scenarios = parent::scenarios();
        $scenarios['backendCreate'] = ['username', 'password_hash'];

        return $scenarios;
    }

    /**
     * 关联账号
     */
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['member_id' => 'id']);
    }

    /**
     * 关联级别
     */
    public function getLevel()
    {
        return $this->hasOne(Level::class, ['level' => 'current_level'])->where(['merchant_id' => Yii::$app->services->merchant->getId()]);
    }

    /**
     * 关联第三方绑定
     */
    public function getAuth()
    {
        return $this->hasMany(Auth::class, ['member_id' => 'id'])->where(['status' => StatusEnum::ENABLED]);
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->last_ip = Yii::$app->request->getUserIP();
            $this->last_time = time();
            $this->auth_key = Yii::$app->security->generateRandomString();
        }

        // 处理上下级关系
        $this->autoUpdateTree();

        return parent::beforeSave($insert);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $account = new Account();
            $account->member_id = $this->id;
            $account->merchant_id = $this->merchant_id;
            $account->save();
        }

        if ($this->status == StatusEnum::DELETE) {
            Account::updateAll(['status' => StatusEnum::DELETE], ['member_id' => $this->id]);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $merchant_id = Yii::$app->services->merchant->getId();

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
                'value' => !empty($merchant_id) ? $merchant_id : 0,
            ]
        ];
    }
}
