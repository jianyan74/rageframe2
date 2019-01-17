<?php

namespace common\models\api;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\models\member\MemberInfo;
use common\models\common\RateLimit;
use common\helpers\ArrayHelper;

/**
 * 如果不想速率控制请直接继承 common\models\common\BaseModel
 *
 * This is the model class for table "{{%api_access_token}}".
 *
 * @property string $id
 * @property string $refresh_token 刷新令牌
 * @property string $access_token 授权令牌
 * @property string $member_id 关联的用户id
 * @property string $group 组别
 * @property int $status 用户状态
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class AccessToken extends RateLimit
{
    /**
     * 组别 主要用于多端登录
     */
    const GROUP_MINI_PROGRAM = 'miniProgram'; // 小程序
    const GROUP_APP = 'app'; // app
    const GROUP_WECHAT = 'wechat'; // 微信

    /**
     * 给其他表单验证的数据
     *
     * @var array
     */
    public static $ruleGroupRnage = ['miniProgram', 'app', 'wechat'];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%api_access_token}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['refresh_token', 'access_token'], 'string', 'max' => 60],
            [['group'], 'string', 'max' => 30],
            [['access_token'], 'unique'],
            [['refresh_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'refresh_token' => '重置令牌',
            'access_token' => '登录令牌',
            'member_id' => '会员ID',
            'group' => '组别',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * access_token 找到identity
     *
     * @param mixed $token
     * @param null $type
     * @return static
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * refresh_token 找到identity
     *
     * @param $token
     * @param null $group
     * @return AccessToken|null
     */
    public static function findIdentityByRefreshToken($token, $group = null)
    {
        return static::findOne(['group' => $group, 'refresh_token' => $token]);
    }

    /**
     * 获取token
     *
     * @param object $member
     * @param bool $noFlushToken
     * @return array
     * @throws \yii\base\Exception
     */
    public static function getAccessToken(MemberInfo $member, $group)
    {
        $model = static::findModel($member->id, $group);
        $model->member_id = $member->id;
        $model->group = $group;
        $model->refresh_token = Yii::$app->security->generateRandomString() . '_' . time();
        $model->access_token = Yii::$app->security->generateRandomString() . '_' . time();

        // 记录访问次数
        $member->visit_count += 1;
        $member->last_time = time();
        $member->last_ip = Yii::$app->request->getUserIP();

        if (!$model->save())
        {
            return self::getAccessToken($member, $group);
        }

        $result = [];
        $result['refresh_token'] =  $model->refresh_token;
        $result['access_token'] = $model->access_token;
        $result['expiration_time'] = Yii::$app->params['user.accessTokenExpire'];

        $member->save();
        $member = ArrayHelper::toArray($member);
        unset($member['password_hash'], $member['auth_key'], $member['password_reset_token'], $member['access_token'], $member['refresh_token']);
        $result['member'] = $member;

        return $result;
    }

    /**
     * 返回模型
     *
     * @param $member_id
     * @param $group
     * @return array|AccessToken|null|ActiveRecord
     */
    protected static function findModel($member_id, $group)
    {
        if (empty(($model = self::find()->where(['member_id' => $member_id, 'group' => $group])->one())))
        {
            $model = new self();
            return $model->loadDefaultValues();
        }

        return $model;
    }

    /**
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
