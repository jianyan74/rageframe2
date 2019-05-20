<?php
namespace addons\RfSignShoppingDay\backend\controllers;

use common\components\Curd;
use common\controllers\AddonsBaseController;

/**
 * Class UserController
 * @package addons\RfSignShoppingDay\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class UserController extends AddonsBaseController
{
    use Curd;

    /**
     * @var string
     */
    public $modelClass = '\addons\RfSignShoppingDay\common\models\User';
}