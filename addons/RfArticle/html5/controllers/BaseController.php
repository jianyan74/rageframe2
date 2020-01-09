<?php

namespace addons\RfArticle\html5\controllers;

use Yii;
use common\controllers\AddonsController;
use common\traits\WechatLogin;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\RfArticle\html5\controllers
 */
class BaseController extends AddonsController
{
    use WechatLogin;

    /**
     * @var string
     */
    public $layout = "@addons/RfArticle/html5/views/layouts/main";
}