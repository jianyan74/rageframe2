<?php
namespace addons\RfArticle\backend\controllers;

use addons\RfArticle\common\models\Article;
use addons\RfArticle\common\models\ArticleTag;
use common\controllers\AddonsBaseController;
use common\enums\StatusEnum;

/**
 * Class SettingController
 * @package addons\RfArticle\backend\controllers
 */
class SettingController extends AddonsBaseController
{
    /**
     * @param $params
     */
    public function actionHook($params)
    {
        $tags = ArticleTag::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();

        $articles = Article::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(Article::position($params['position']))
            ->orderBy('view desc')
            ->with(['tags'])
            ->limit(10)
            ->asArray()
            ->all();

        return $this->render('hook', [
            'tags' => $tags,
            'articles' => $articles,
        ]);
    }
}