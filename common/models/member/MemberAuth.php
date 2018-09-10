<?php
namespace common\models\member;

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
    const TYPE_MINI_PROGRAM = 'miniProgram';
    const TYPE_WECHAT = 'wechat';
    const TYPE_QQ = 'qq';
    const TYPE_SINA = 'sina';

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
            [['type'], 'string', 'max' => 20],
            [['openid', 'country', 'province', 'city'], 'string', 'max' => 100],
            [['nickname', 'head_portrait'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'unionid' => 'Unionid',
            'type' => '类型',
            'openid' => 'Openid',
            'sex' => '性别',
            'nickname' => '昵称',
            'head_portrait' => '头像',
            'birthday' => '生日',
            'country' => '国家',
            'province' => '省',
            'city' => '市',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
 * @param $type
 * @param $openid
 * @return MemberAuth|null
 */
    public static function findOpend($type, $openid)
    {
        return self::findOne(['type' => $type, 'openid' => $openid]);
    }

    /**
     * @param $type
     * @param $openid
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findOpendMapMember($type, $openid)
    {
        return self::find()->where(['type' => $type, 'openid' => $openid])->with('member')->one();
    }

    /**
     * @param $data
     * @return array|MemberAuth
     */
    public function add($data)
    {
        $model = new self();
        $model->attributes = $data;
        if ($model->save())
        {
            return $model;
        }

        return $model->getFirstErrors();
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
