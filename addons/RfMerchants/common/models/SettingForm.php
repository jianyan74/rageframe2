<?php

namespace addons\RfMerchants\common\models;

use yii\base\Model;

/**
 * Class SettingForm
 * @package addons\RfMerchants\common\models
 */
class SettingForm extends Model
{
    public $share_title;
    public $share_cover;
    public $share_desc;
    public $share_link;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['share_title', 'share_cover'], 'string', 'max' => 100],
            [['share_link', 'share_desc'], 'string', 'max' => 255],
            [['share_link'], 'url'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'share_title' => '分享标题',
            'share_cover' => '分享封面',
            'share_desc' => '分享描述',
            'share_link' => '分享链接',
        ];
    }
}