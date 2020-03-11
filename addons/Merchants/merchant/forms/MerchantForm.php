<?php

namespace addons\Merchants\merchant\forms;

use common\models\merchant\Merchant;

/**
 * Class MerchantForm
 * @package addons\Merchants\merchant\forms
 * @author jianyan74 <751393839@qq.com>
 */
class MerchantForm extends Merchant
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['province_id', 'area_id', 'mobile', 'cover', 'address_details'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['address_name', 'stamp'], 'string', 'max' => 200],
            [['cover'], 'string', 'max' => 150],
            [['email'], 'string', 'max' => 60],
            [['address_details', 'longitude', 'latitude', 'mobile', 'logo', 'banner', 'qrcode'], 'string', 'max' => 100],
            [['qq', 'ww', 'region'], 'string', 'max' => 50],
            [['close_info', 'keywords', 'description'], 'string', 'max' => 255],
        ];
    }
}