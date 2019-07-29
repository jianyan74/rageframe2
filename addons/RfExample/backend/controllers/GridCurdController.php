<?php
namespace addons\RfExample\backend\controllers;

use common\helpers\ResultDataHelper;
use Yii;
use addons\RfExample\common\models\Curd as CurdModel;
use common\models\base\SearchModel;
use common\components\Curd;
use common\enums\StatusEnum;
use common\controllers\AddonsController;

/**
 * Class GridCurdController
 * @package addons\RfExample\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class GridCurdController extends AddonsController
{
    use Curd;

    /**
     * @var string
     */
    public $modelClass = CurdModel::class;

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => CurdModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'sort' => SORT_ASC,
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed
     */
    public function actionEdit()
    {
        $this->layout = '@backend/views/layouts/default';

        $id = Yii::$app->request->get('id', null);
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return ResultDataHelper::json(200, '保存成功');
            }

            return ResultDataHelper::json(422, $this->getError($model));
        }

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }
}