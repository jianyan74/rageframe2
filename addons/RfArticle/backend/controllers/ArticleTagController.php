<?php
namespace addons\RfArticle\backend\controllers;

use Yii;
use common\controllers\AddonsBaseController;
use common\components\CurdTrait;
use addons\RfArticle\common\models\ArticleTag;

/**
 * Class ArticleTagController
 * @package addons\RfArticle\backend\controllers
 */
class ArticleTagController extends AddonsBaseController
{
    use CurdTrait;

    /**
     * @var string
     */
    public $modelClass = 'addons\RfArticle\common\models\ArticleTag';
}