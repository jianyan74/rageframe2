<?php
namespace addons\RfExample\common\models;

use common\behaviors\MerchantBehavior;
use yii\redis\ActiveRecord;

/**
 * Class RedisCurd
 * @package addons\RfExample\common\models
 * @author jianyan74 <751393839@qq.com>
 */
class RedisCurd extends ActiveRecord
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'cover', 'longitude', 'latitude'], 'required'],
            [['merchant_id', 'sort', 'created_at', 'updated_at', 'status'], 'integer'],
            [['sort', 'created_at', 'updated_at', 'status'], 'filter', 'filter' => 'intval'],
            [['author'], 'safe'],
        ];
    }

    /**
     * 表字段
     *
     * {@inheritdoc}
     */
    public function attributes()
    {
        return array_keys($this->attributeLabels());
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'merchant_id' => '商户id',
            'sort' => '排序',
            'status' => '状态',
            'cover' => '封面',
            'author' => '作者',
            'longitude' => '经度',
            'latitude' => '纬度',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}