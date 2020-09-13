<?php

namespace common\models\backend;

/**
 * This is the model class for table "{{%backend_member_auth}}".
 *
 * @property string $id 主键
 * @property string $member_id 用户id
 * @property string $unionid 唯一ID
 * @property string $oauth_client 授权组别
 * @property string $oauth_client_user_id 授权id
 * @property int $gender 性别[0:未知;1:男;2:女]
 * @property string $nickname 昵称
 * @property string $head_portrait 头像
 * @property string $birthday 生日
 * @property string $country 国家
 * @property string $province 省
 * @property string $city 市
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Auth extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%backend_member_auth}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['oauth_client', 'oauth_client_user_id'], 'required'],
            [['member_id', 'gender', 'status', 'created_at', 'updated_at'], 'integer'],
            [['birthday'], 'safe'],
            [['unionid'], 'string', 'max' => 64],
            [['oauth_client'], 'string', 'max' => 20],
            [['oauth_client_user_id', 'nickname', 'country', 'province', 'city'], 'string', 'max' => 100],
            [['head_portrait'], 'string', 'max' => 150],
            ['member_id', 'isBinding']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户id',
            'unionid' => '第三方用户唯一id',
            'oauth_client' => '类型',
            'oauth_client_user_id' => '第三方用户id',
            'gender' => '性别',
            'nickname' => '昵称',
            'head_portrait' => '头像',
            'birthday' => '生日',
            'country' => '国家',
            'province' => '省',
            'city' => '市',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
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
}
