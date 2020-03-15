<?php

namespace common\models\oauth2;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\behaviors\MerchantBehavior;
use common\models\base\User;

/**
 * This is the model class for table "{{%oauth2_access_token}}".
 *
 * @property string $id
 * @property string $merchant_id 商户id
 * @property string $access_token
 * @property string $client_id
 * @property string $member_id
 * @property string $expires
 * @property array $scope
 * @property string $grant_type 组别
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class AccessToken extends User
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%oauth2_access_token}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['access_token', 'client_id', 'expires'], 'required'],
            [['expires', 'scope'], 'safe'],
            [['access_token'], 'string', 'max' => 80],
            [['client_id'], 'string', 'max' => 64],
            [['member_id'], 'string', 'max' => 100],
            [['grant_type'], 'string', 'max' => 30],
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
            'access_token' => '授权令牌',
            'client_id' => 'Client ID',
            'member_id' => 'Member ID',
            'expires' => '有效期',
            'scope' => 'Scope',
            'grant_type' => '组别',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['client_id' => 'client_id']);
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
