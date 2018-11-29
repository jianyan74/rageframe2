<?php
namespace addons\RfArticle\frontend\controllers;

use Yii;
use addons\RfArticle\common\models\ArticleCate;
use addons\RfArticle\common\models\Article;
use common\enums\StatusEnum;
use common\controllers\AddonsBaseController;
use yii\data\Pagination;

/**
 * Class IndexController
 * @package addons\RfArticle\frontend\controllers
 */
class IndexController extends AddonsBaseController
{
    /**
     * @var string
     */
    public $layout = "@addons/RfArticle/frontend/views/layouts/main";

    /**
    * 首页
    *
    * @return string
    */
    public function actionIndex()
    {
        $articles = Article::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(Article::position(1))// 推荐位 位运算查询
            ->with(['tags'])
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
     * 首页
     *
     * @return string
     */
    public function actionList()
    {
        $keyword = Yii::$app->request->get('keyword', '');
        $cate_id = Yii::$app->request->get('cate_id', '');

        $data = Article::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['like', 'title', $keyword])
            ->andFilterWhere(['cate_id' => $cate_id]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $articles = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->with(['tags'])
            ->limit($pages->limit)
            ->all();

        return $this->render('list',[
            'articles' => $articles,
            'pages' => $pages,
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
            ->where(['status' => StatusEnum::ENABLED, 'id' => $id])
            ->with(['tags'])
            ->asArray()
            ->one();

        // 更新浏览量
        Article::updateAllCounters(['view' => 1], ['id' => $id]);

        return $this->render('details',[
            'article' => $article,
            'prev' => Article::getPrev($id), // 文章上一篇ID
            'next' => Article::getNext($id), // 文章下一篇ID
        ]);
    }
}