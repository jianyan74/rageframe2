<?php

namespace backend\modules\common\controllers;

use Yii;
use common\traits\Curd;
use common\models\base\SearchModel;
use common\models\common\MenuCate;
use common\enums\StatusEnum;
use common\enums\AppEnum;
use backend\controllers\BaseController;

/**
 * Class MenuCateController
 * @package backend\modules\base\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MenuCateController extends BaseController
{
    use Curd;

    /**
     * @var \yii\db\ActiveRecord
     */
    public $modelClass = MenuCate::class;

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'sort' => SORT_ASC,
                'id' => SORT_ASC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['app_id' => AppEnum::BACKEND]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'cates' => Yii::$app->services->menuCate->findDefault(AppEnum::BACKEND),
        ]);
    }
}