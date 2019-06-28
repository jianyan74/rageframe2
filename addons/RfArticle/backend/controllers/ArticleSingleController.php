<?php
namespace addons\RfArticle\backend\controllers;

use Yii;
use common\components\Curd;
use addons\RfArticle\common\models\ArticleSingle;

/**
 * 单页管理
 *
 * Class ArticleSingleController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ArticleSingleController extends BaseController
{
    use Curd;

    /**
     * @var ArticleSingle
     */
    public $modelClass = ArticleSingle::class;
}