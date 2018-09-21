<?php
namespace addons\RfArticle\frontend\controllers;

use Yii;
use addons\RfArticle\common\models\ArticleCate;
use addons\RfArticle\common\models\Article;
use common\enums\StatusEnum;
use common\controllers\AddonsBaseController;

/**
 * Class IndexController
 * @package addons\RfArticle\frontend\controllers
 */
class IndexController extends AddonsBaseController
{
    /**
    * 首页
    *
    * @return string
    */
    public function actionIndex()
    {
        $where = [];
        // 推荐位 位运算查询
        if ($position = Yii::$app->request->get('position', ''))
        {
            $where = Article::position($position);
        }

        $articles = Article::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere($where)
            ->asArray()
            ->all();

        // 获取递归分类
        $cates = ArticleCate::getTree();

        return $this->render('index',[
            'articles' => $articles,
            'cates' => $cates,
        ]);
    }

    /**
     * 文章详情
     *
     * @return string
     */
    public function actionDetails($id)
    {
        $article = Article::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->one();

        return $this->render('details',[
            'article' => $article,
            'prev' => Article::getPrev($id), // 文章上一篇ID
            'next' => Article::getNext($id), // 文章下一篇ID
        ]);
    }
}