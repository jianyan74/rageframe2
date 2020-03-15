<?php

namespace common\models\common;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%common_config_value}}".
 *
 * @property string $id 主键
 * @property int $config_id 配置id
 * @property string $app_id 应用id
 * @property string $merchant_id 商户id
 * @property string $data 配置内
 */
class ConfigValue extends \yii\db\ActiveRecord
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_config_value}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['config_id', 'merchant_id'], 'integer'],
            [['data', 'app_id'], 'string'],
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
            'config_id' => '配置id',
            'merchant_id' => '商户',
            'data' => '内容',
        ];
    }
}
