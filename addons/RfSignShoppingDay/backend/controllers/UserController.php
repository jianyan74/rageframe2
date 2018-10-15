<?php
namespace addons\RfSignShoppingDay\backend\controllers;

use common\components\CurdTrait;
use common\controllers\AddonsBaseController;

/**
 * Class UserController
 * @package addons\RfSignShoppingDay\backend\controllers
 */
class UserController extends AddonsBaseController
{
    use CurdTrait;

    /**
     * @var string
     */
    public $modelClass = '\addons\RfSignShoppingDay\common\models\User';
}