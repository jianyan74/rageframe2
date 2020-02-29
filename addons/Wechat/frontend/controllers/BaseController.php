<?php

namespace addons\Wechat\frontend\controllers;

use Yii;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\Wechat\frontend\controllers
 */
class BaseController extends AddonsController
{
    /**
    * @var string
    */
    public $layout = "@addons/Wechat/frontend/views/layouts/main";
}