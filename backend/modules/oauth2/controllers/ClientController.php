<?php

namespace backend\modules\oauth2\controllers;

use Yii;
use common\models\base\SearchModel;
use common\components\Curd;
use common\models\oauth2\Client;
use common\enums\StatusEnum;
use backend\controllers\BaseController;

/**
 * 客户端
 *
 * Class ClientController
 * @package backend\modules\member\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ClientController extends BaseController
{
    use Curd;

    /**
     * @var Client
     */
    public $modelClass = Client::class;

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
            'partialMatchAttributes' => ['title', 'client_id'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}