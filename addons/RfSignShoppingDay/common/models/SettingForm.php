<?php
namespace addons\RfSignShoppingDay\common\models;

use yii\base\Model;

/**
 * Class SettingForm
 * @package addons\RfSignShoppingDay\common\models
 */
class SettingForm extends Model
{
    public $share_title;
    public $share_cover;
    public $share_desc;
    public $share_link;

    public $site_title;
    public $start_time;
    public $end_time;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['share_title', 'share_cover', 'site_title'], 'string', 'max' => 100],
            [['share_link', 'share_desc'], 'string', 'max' => 255],
            [['share_link'], 'url'],
            [['start_time', 'end_time'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'site_title' => '活动标题',
            'start_time' => '活动开始时间',
            'end_time' => '活动结束时间',
            'share_title' => '分享标题',
            'share_cover' => '分享封面',
            'share_desc' => '分享描述',
            'share_link' => '分享链接',
        ];
    }
}