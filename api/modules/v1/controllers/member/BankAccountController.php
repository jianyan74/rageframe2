<?php

namespace api\modules\v1\controllers\member;

use api\controllers\UserAuthController;
use common\models\forms\BankAccountForm;

/**
 * 提现账号
 *
 * Class BankAccountController
 * @package api\modules\v1\controllers\member
 * @author jianyan74 <751393839@qq.com>
 */
class BankAccountController extends UserAuthController
{
    /**
     * @var BankAccountForm
     */
    public $modelClass = BankAccountForm::class;
}