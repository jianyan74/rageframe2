<?php

namespace addons\Merchants\merchant\controllers;

use Yii;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\Merchants\merchant\controllers
 */
class BaseController extends AddonsController
{
    /**
     * @var string
     */
    public $layout = "@backend/views/layouts/main";
}