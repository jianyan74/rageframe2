<?php
namespace addons\RfArticle\backend\controllers;

use Yii;
use common\controllers\AddonsBaseController;
use common\components\CurdTrait;
use addons\RfArticle\common\models\ArticleTag;

/**
 * 文章标签
 *
 * Class ArticleTagController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ArticleTagController extends AddonsBaseController
{
    use CurdTrait;

    /**
     * @var string
     */
    public $modelClass = 'addons\RfArticle\common\models\ArticleTag';
}