<?php

namespace backend\modules\common\controllers;

use Yii;
use common\traits\Curd;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\common\IpBlacklist;
use backend\controllers\BaseController;

/**
 * Class IpBlacklistController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class IpBlacklistController extends BaseController
{
    use Curd;

    /**
     * @var IpBlacklist
     */
    public $modelClass = IpBlacklist::class;

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
            'partialMatchAttributes' => ['ip'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}