<?php

namespace addons\Wechat\merchant\controllers;

use Yii;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\Wechat\merchant\controllers
 */
class BaseController extends AddonsController
{
    /**
    * @var string
    */
    public $layout = "@merchant/views/layouts/main";
}