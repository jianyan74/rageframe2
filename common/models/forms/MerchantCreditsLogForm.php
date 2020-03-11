<?php

namespace common\models\forms;

use yii\base\Model;
use common\models\merchant\Merchant;

/**
 * Class MerchantCreditsLogForm
 * @package common\models\forms
 * @author jianyan74 <751393839@qq.com>
 */
class MerchantCreditsLogForm extends Model
{
    /**
     * @var Merchant
     */
    public $merchant;
    public $num = 0;
    public $credit_group;
    public $remark = '';
    public $map_id = 0;

    /**
     * 支付类型
     *
     * @var int
     */
    public $pay_type = 0;

    /**
     * 字段类型(请不要占用)
     *
     * @var string
     */
    public $credit_type;
}