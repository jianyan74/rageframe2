<?php
namespace api\modules\v1\controllers\member;

use api\controllers\UserAuthController;

/**
 * Class AuthController
 * @package api\modules\v1\controllers\member
 */
class AuthController extends UserAuthController
{
    public $modelClass = 'common\models\member\MemberAuth';
}