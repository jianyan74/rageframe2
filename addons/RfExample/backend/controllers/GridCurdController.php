<?php
namespace addons\RfExample\backend\controllers;

use Yii;
use addons\RfExample\common\models\Curd;
use common\models\common\SearchModel;
use common\components\CurdTrait;
use common\controllers\AddonsBaseController;

/**
 * Class GridCurdController
 * @package addons\RfExample\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class GridCurdController extends AddonsBaseController
{
    use CurdTrait;

    /**
     * @var string
     */
    public $modelClass = 'addons\RfExample\common\models\Curd';

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => Curd::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'sort' => SORT_ASC,
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return string|yii\console\Response|yii\web\Response
     */
    public function actionEdit()
    {
        $request  = Yii::$app->request;
        $id = $request->get('id', null);
        $model = $this->findModel($id);
        $model->covers = unserialize($model->covers);
        $model->files = json_decode($model->files, true);
        if ($model->load($request->post()))
        {
            $model->covers = serialize($model->covers);
            $model->files = json_encode($model->files);

            if ($model->save())
            {
                return $this->redirect(['index']);
            }
        }

        return $this->render('edit',[
            'model' => $model
        ]);
    }
}