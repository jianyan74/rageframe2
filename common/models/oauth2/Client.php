<?php
namespace common\models\oauth2;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%oauth_client}}".
 *
 * @property string $id
 * @property string $title 标题
 * @property string $client_id
 * @property string $client_secret
 * @property string $redirect_uri 回调Url
 * @property string $remark 备注
 * @property string $group 组别
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Client extends \common\models\common\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%oauth2_client}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id'], 'unique'],
            [['title', 'client_id', 'client_secret'], 'required'],
            [['merchant_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['client_id', 'client_secret'], 'string', 'max' => 64, 'min' => 10],
            [['redirect_uri'], 'string', 'max' => 2000],
            [['redirect_uri'], 'url'],
            [['remark'], 'string', 'max' => 200],
            [['group'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '名称',
            'client_id' => 'Client ID',
            'client_secret' => 'Client Secret',
            'redirect_uri' => '回调Url',
            'remark' => '备注',
            'group' => '组别',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
