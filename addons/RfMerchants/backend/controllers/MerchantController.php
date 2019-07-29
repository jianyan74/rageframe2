<?php
namespace addons\RfMerchants\backend\controllers;

use Yii;
use common\enums\StatusEnum;
use common\components\Curd;
use common\models\base\SearchModel;
use addons\RfMerchants\common\models\Merchant;

/**
 * Class MerchantController
 * @package addons\RfMerchants\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MerchantController extends BaseController
{
    use Curd;

    /**
     * @var Merchant
     */
    public $modelClass = Merchant::class;

    /**
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
                'id' => SORT_DESC,
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

    /**
     * 返回模型
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     */
    protected function findModel($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($id) || empty($model = $this->modelClass::findOne($id))) {
            $model = new $this->modelClass;
            return $model->loadDefaultValues();
        }

        return $model;
    }
}