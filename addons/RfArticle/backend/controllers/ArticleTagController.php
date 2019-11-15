<?php

namespace addons\RfArticle\backend\controllers;

use Yii;
use common\components\MerchantCurd;
use addons\RfArticle\common\models\ArticleTag;

/**
 * 文章标签
 *
 * Class ArticleTagController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ArticleTagController extends BaseController
{
    use MerchantCurd;

    /**
     * @var ArticleTag
     */
    public $modelClass = ArticleTag::class;
}