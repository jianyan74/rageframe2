<?php

namespace common\models\common;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%common_addons_config}}".
 *
 * @property string $id 主键
 * @property string $app_id 应用id
 * @property string $addons_name 插件名或标识
 * @property string $merchant_id 商户id
 * @property string $data 配置内
 */
class AddonsConfig extends \yii\db\ActiveRecord
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_addons_config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id'], 'integer'],
            [['data'], 'safe'],
            [['addons_name', 'app_id'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => '应用id',
            'addons_name' => '插件名称',
            'merchant_id' => '商户',
            'data' => 'Data',
        ];
    }
}
