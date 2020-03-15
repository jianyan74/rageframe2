<?php

namespace addons\Merchants\common\models;

use yii\base\Model;

/**
 * Class SettingForm
 * @package addons\Merchants\common\models
 */
class SettingForm extends Model
{
    public $withdraw_is_open = 0;
    public $withdraw_lowest_money = 1;
    public $withdraw_account = [1];

    public $protocol_cooperation;

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
            [['withdraw_is_open'], 'integer'],
            [['withdraw_account', 'withdraw_lowest_money'], 'required'],
            [['withdraw_lowest_money'], 'number', 'min' => 0.01],
            [['share_title', 'share_cover'], 'string', 'max' => 100],
            [['share_link', 'share_desc'], 'string', 'max' => 255],
            [['share_link'], 'url'],
            [['protocol_cooperation'], 'string'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'withdraw_is_open' => '提现申请',
            'withdraw_lowest_money' => '最低提现金额',
            'withdraw_account' => '提现账户',
            'protocol_cooperation' => '合作协议',
            'share_title' => '分享标题',
            'share_cover' => '分享封面',
            'share_desc' => '分享描述',
            'share_link' => '分享链接',
        ];
    }
}