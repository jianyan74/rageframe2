<?php
namespace common\models\member;

use common\enums\StatusEnum;
use Yii;

/**
 * This is the model class for table "{{%member_auth}}".
 *
 * @property int $id 主键
 * @property int $member_id 用户id
 * @property string $unionid 唯一ID
 * @property string $type 授权组别
 * @property string $openid 授权id
 * @property int $sex 性别
 * @property string $nickname 昵称
 * @property string $head_portrait 头像
 * @property string $birthday 生日
 * @property string $country 国家
 * @property string $province 省
 * @property string $city 市
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class MemberAuth extends \common\models\common\BaseModel
{
    const CLIENT_MINI_PROGRAM = 'miniProgram';
    const CLIENT_WECHAT = 'wechat';
    const CLIENT_QQ = 'qq';
    const CLIENT_SINA = 'sina';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_auth}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'sex', 'status', 'created_at', 'updated_at'], 'integer'],
            [['birthday'], 'safe'],
            [['unionid'], 'string', 'max' => 64],
            [['oauth_client'], 'string', 'max' => 20],
            [['oauth_client_user_id', 'country', 'province', 'city'], 'string', 'max' => 100],
            [['nickname', 'head_portrait'], 'string', 'max' => 200],
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
            'unionid' => 'Unionid',
            'oauth_client' => '类型',
            'oauth_client_user_id' => 'Openid',
            'sex' => '性别',
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
     * 验证绑定
     *
     * @param $attribute
     */
    public function isBinding($attribute)
    {
        $model = self::find()
            ->where([
                'status' => StatusEnum::ENABLED,
                'member_id' => $this->member_id,
                'oauth_client_user_id' => $this->oauth_client_user_id,
            ])
            ->one();

        if ($model && $model->id != $this->id)
        {
            $this->addError($attribute, '用户已绑定请不要重复绑定');
        }
    }

    /**
     * @param $oauthClient
     * @param $oauthClientId
     * @return MemberAuth|null
     */
    public static function findOauthClient($oauthClient, $oauthClientUserId)
    {
        return self::findOne(['oauth_client' => $oauthClient, 'oauth_client_user_id' => $oauthClientUserId]);
    }

    /**
     * @param $oauthClient
     * @param $oauthClientId
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findOauthClientMapMember($oauthClient, $oauthClientUserId)
    {
        return self::find()
            ->where(['oauth_client' => $oauthClient, 'oauth_client_user_id' => $oauthClientUserId])
            ->with('member')
            ->one();
    }

    /**
     * @param $data
     * @return MemberAuth
     * @throws \Exception
     */
    public function add($data)
    {
        $model = new self();
        $model->attributes = $data;

        if (!$model->save())
        {
            $error = Yii::$app->debris->analyErr($model->getFirstErrors());
            throw new \Exception($error);
        }

        return $model;
    }

    /**
     * 关联用户
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(MemberInfo::className(), ['id' => 'member_id']);
    }
}
