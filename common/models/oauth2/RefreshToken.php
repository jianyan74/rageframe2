<?php

namespace common\models\oauth2;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%oauth2_refresh_token}}".
 *
 * @property string $id
 * @property string $merchant_id 商户id
 * @property string $refresh_token
 * @property string $client_id
 * @property string $member_id
 * @property string $expires
 * @property array $scope
 * @property string $grant_type 组别
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class RefreshToken extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%oauth2_refresh_token}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['refresh_token', 'client_id', 'expires'], 'required'],
            [['expires', 'scope'], 'safe'],
            [['refresh_token'], 'string', 'max' => 80],
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
            'refresh_token' => '刷新令牌',
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
}
