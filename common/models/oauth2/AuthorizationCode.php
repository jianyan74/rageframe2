<?php

namespace common\models\oauth2;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%oauth2_authorization_code}}".
 *
 * @property string $authorization_code
 * @property string $merchant_id 商户id
 * @property string $client_id
 * @property string $member_id
 * @property string $redirect_uri
 * @property string $expires
 * @property array $scope
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class AuthorizationCode extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%oauth2_authorization_code}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['authorization_code', 'client_id', 'expires'], 'required'],
            [['merchant_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['expires', 'scope'], 'safe'],
            [['authorization_code', 'member_id'], 'string', 'max' => 100],
            [['client_id'], 'string', 'max' => 64],
            [['redirect_uri'], 'string', 'max' => 2000],
            [['authorization_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'authorization_code' => 'Authorization Code',
            'merchant_id' => 'Merchant ID',
            'client_id' => 'Client ID',
            'member_id' => 'Member ID',
            'redirect_uri' => 'Redirect Uri',
            'expires' => 'Expires',
            'scope' => 'Scope',
            'status' => 'Status',
            'created_at' => 'Created At',
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
