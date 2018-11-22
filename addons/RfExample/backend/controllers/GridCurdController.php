<?php
namespace addons\RfExample\backend\controllers;

use Yii;
use addons\RfExample\common\models\CurdSearch;
use common\components\CurdTrait;
use common\controllers\AddonsBaseController;

/**
 * Class GridCurdController
 * @package addons\RfExample\backend\controllers
 */
class GridCurdController extends AddonsBaseController
{
    use CurdTrait;

    public $modelClass = 'addons\RfExample\common\models\Curd';

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CurdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}