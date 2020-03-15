<?php

namespace common\models\api;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\UnauthorizedHttpException;
use common\enums\StatusEnum;
use common\models\member\Member;
use common\models\common\RateLimit;
use common\models\rbac\AuthAssignment;

/**
 *  如果不想速率控制请直接继承 \common\models\base\BaseModel
 *
 * This is the model class for table "{{%api_access_token}}".
 *
 * @property string $id
 * @property int $merchant_id 商户id
 * @property string $refresh_token 刷新令牌
 * @property string $access_token 授权令牌
 * @property int $member_id 用户id
 * @property string $openid
 * @property string $group 组别
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class AccessToken extends RateLimit
{
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
            [['merchant_id', 'member_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['refresh_token', 'access_token'], 'string', 'max' => 60],
            [['openid'], 'string', 'max' => 50],
            [['group'], 'string', 'max' => 100],
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
            'merchant_id' => '商户',
            'refresh_token' => '重置令牌',
            'access_token' => '登录令牌',
            'openid' => 'openid',
            'member_id' => '会员ID',
            'group' => '组别',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return array|mixed|ActiveRecord|\yii\web\IdentityInterface|null
     * @throws UnauthorizedHttpException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // 判断验证token有效性是否开启
        if (Yii::$app->params['user.accessTokenValidity'] === true) {
            $timestamp = (int)substr($token, strrpos($token, '_') + 1);
            $expire = Yii::$app->params['user.accessTokenExpire'];

            // 验证有效期
            if ($timestamp + $expire <= time()) {
                throw new UnauthorizedHttpException('您的登录验证已经过期，请重新登录');
            }
        }

        // 优化版本到缓存读取用户信息 注意需要开启服务层的cache
        return Yii::$app->services->apiAccessToken->getTokenToCache($token, $type);
    }

    /**
     * @param $token
     * @param null $group
     * @return AccessToken|\common\models\base\User|null
     */
    public static function findIdentityByRefreshToken($token, $group = null)
    {
        return static::findOne(['group' => $group, 'refresh_token' => $token, 'status' => StatusEnum::ENABLED]);
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
     * 关联授权角色
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssignment()
    {
        return $this->hasOne(AuthAssignment::class, ['user_id' => 'member_id'])
            ->where(['app_id' => Yii::$app->id]);
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
        ];
    }
}
