<?php

namespace addons\RfArticle\oauth2\controllers;

use Yii;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\RfArticle\oauth2\controllers
 */
class BaseController extends AddonsController
{
    /**
    * @var string
    */
    public $layout = "@addons/RfArticle/oauth2/views/layouts/main";
}