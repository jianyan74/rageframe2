<?php
namespace addons\RfArticle\backend\controllers;

use addons\RfArticle\common\models\Adv;
use addons\RfArticle\common\models\Article;
use addons\RfArticle\common\models\ArticleTag;
use common\enums\StatusEnum;

/**
 * 参数设置
 *
 * Class SettingController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SettingController extends BaseController
{
    /**
     * 文章列表钩子
     *
     * @param array $params
     */
    public function actionHook($params)
    {
        $tags = ArticleTag::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->asArray()
            ->all();

        $articles = Article::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(Article::position($params['position']))
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
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

    /**
     * 幻灯片钩子
     *
     * @param array $params
     * @return string
     */
    public function actionAdv($params)
    {
        $models = Adv::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->andWhere(['<=', 'start_time', time()])
            ->andWhere(['>=', 'end_time', time()])
            ->asArray()
            ->all();

        if (!$models) {
            return false;
        }

        return $this->render('adv', [
            'models' => $models,
        ]);
    }
}