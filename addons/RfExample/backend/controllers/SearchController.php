<?php
namespace addons\RfExample\backend\controllers;

use Yii;
use yii\data\Pagination;
use addons\RfExample\common\models\CurdSearch;
use common\controllers\AddonsBaseController;

/**
 * Class SearchController
 * @package addons\RfExample\backend\controllers
 */
class SearchController extends AddonsBaseController
{
    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CurdSearch();
        $data = $searchModel->search(Yii::$app->request->queryParams);
        $pages = new Pagination([
            'totalCount' => $data->count(),
            'pageSize' => $this->_pageSize
        ]);

        $models = $data->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('index',[
            'models' => $models,
            'searchModel' => $searchModel,
            'pages' => $pages,
        ]);
    }
}