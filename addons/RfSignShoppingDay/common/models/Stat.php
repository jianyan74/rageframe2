<?php

namespace addons\RfSignShoppingDay\common\models;

use common\behaviors\MerchantBehavior;
use Yii;

/**
 * This is the model class for table "{{%addon_RfSign_shopping_street_stat}}".
 *
 * @property string $id 自动编号
 * @property string $openid 用户openid
 * @property string $source_page 来源 url
 * @property string $page 当前页面 url
 * @property string $device 设备
 * @property string $ip ip地址
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Stat extends \common\models\common\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_sign_shopping_street_stat}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['openid'], 'string', 'max' => 50],
            [['source_page', 'page'], 'string', 'max' => 200],
            [['device', 'ip'], 'string', 'max' => 20],
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
            'source_page' => 'Source Page',
            'page' => 'Page',
            'device' => 'Device',
            'ip' => 'Ip',
            'status' => 'Status',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
