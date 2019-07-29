<?php

namespace addons\RfSignShoppingDay\common\models;

use common\behaviors\MerchantBehavior;
use Yii;

/**
 * This is the model class for table "{{%addon_RfSign_shopping_street_user}}".
 *
 * @property int $id
 * @property string $openid
 * @property string $nickname 昵称
 * @property string $realname
 * @property int $integral 积分
 * @property string $avatar 头像
 * @property string $mobile 手机号码
 * @property int $sign_num
 * @property string $ip IP地址
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class User extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_sign_shopping_street_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['openid', 'nickname', 'avatar', 'ip'], 'required'],
            [['merchant_id', 'integral', 'sign_num', 'status', 'created_at', 'updated_at'], 'integer'],
            [['openid', 'nickname', 'realname'], 'string', 'max' => 50],
            [['avatar'], 'string', 'max' => 150],
            [['mobile'], 'string', 'max' => 11],
            [['ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => 'Openid',
            'nickname' => 'Nickname',
            'realname' => 'Realname',
            'integral' => 'Integral',
            'avatar' => 'Avatar',
            'mobile' => 'Mobile',
            'sign_num' => 'Sign Num',
            'ip' => 'Ip',
            'status' => 'Status',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
