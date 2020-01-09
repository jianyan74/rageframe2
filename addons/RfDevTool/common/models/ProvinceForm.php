<?php

namespace addons\RfDevTool\common\models;

use yii\base\Model;

/**
 * Class ProvinceForm
 * @package addons\RfDevTool\common\models
 * @author jianyan74 <751393839@qq.com>
 */
class ProvinceForm extends Model
{
    public $year;
    public $maxLevel = 3;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['year', 'maxLevel'], 'required'],
            [['maxLevel'], 'integer', 'min' => 0, 'max' => 4],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'year' => '年份',
            'maxLevel' => '数据级别',
        ];
    }
}