<?php

namespace common\models\forms;

use yii\base\Model;

/**
 * 提现表单
 *
 * Class CommissionWithdrawForm
 * @package common\models\forms
 * @author jianyan74 <751393839@qq.com>
 */
class CommissionWithdrawForm extends Model
{
    /**
     * 流水号
     *
     * @var
     */
    public $withdraw_no;

    /**
     * 银行名称
     *
     * @var string
     */
    public $enc_bank_name;

    /**
     * 卡号/账号/openid
     *
     * @var
     */
    public $enc_bank_no;

    /**
     * 姓名
     *
     * @var string
     */
    public $enc_true_name;

    /**
     * 提现金额
     *
     * @var double
     */
    public $cash;

    /**
     * 备注
     *
     * @var string
     */
    public $memo;
}