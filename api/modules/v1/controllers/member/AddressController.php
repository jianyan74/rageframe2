<?php
namespace api\modules\v1\controllers\member;

use api\controllers\UserOnAuthController;

/**
 * 收货地址
 *
 * Class AddressController
 * @package api\modules\v1\controllers\member
 */
class AddressController extends UserOnAuthController
{
    public $modelClass = 'api\modules\v1\models\AddressForm';
}
