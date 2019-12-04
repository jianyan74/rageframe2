<?php

namespace addons\RfWechat\oauth2\controllers;

use Yii;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\RfWechat\oauth2\controllers
 */
class BaseController extends AddonsController
{
    /**
    * @var string
    */
    public $layout = "@addons/RfWechat/oauth2/views/layouts/main";
}