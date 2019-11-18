<?php

namespace addons\RfWechat\merchant\controllers;

use Yii;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\RfWechat\merchant\controllers
 */
class BaseController extends AddonsController
{
    /**
    * @var string
    */
    public $layout = "@merchant/views/layouts/main";
}