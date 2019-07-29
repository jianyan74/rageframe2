<?php

namespace common\models\forms;

use yii\base\Model;
use common\enums\PayEnum;
use common\models\member\Member;

/**
 * Class CreditsLogForm
 * @package common\models\forms
 * @author jianyan74 <751393839@qq.com>
 */
class CreditsLogForm extends Model
{
    /**
     * @var Member
     */
    public $member;
    public $num = 0;
    public $credit_group;
    public $credit_group_detail = '';
    public $remark = '';
    public $map_id = 0;

    /**
     * 支付类型
     *
     * @var int
     */
    public $pay_type = PayEnum::PAY_TYPE_USER_MONEY;
}