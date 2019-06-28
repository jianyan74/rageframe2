<?php
namespace addons\RfSignShoppingDay\backend\controllers;

use addons\RfSignShoppingDay\common\models\User;
use common\components\Curd;

/**
 * Class UserController
 * @package addons\RfSignShoppingDay\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class UserController extends BaseController
{
    use Curd;

    /**
     * @var User
     */
    public $modelClass = User::class;
}