<?php

namespace addons\Merchants\backend\controllers;

use addons\Merchants\backend\forms\PassFrom;
use common\enums\AppEnum;
use Yii;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\traits\Curd;
use common\models\merchant\Merchant;
use common\enums\MerchantStateEnum;

/**
 * Class MerchantController
 * @package addons\Merchants\backend\controllers
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
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['in', 'state', [MerchantStateEnum::DISABLED, MerchantStateEnum::ENABLED]])
            ->with(['account', 'cate']);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'cates' => Yii::$app->services->merchantCate->getMapList(),
        ]);
    }

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionApply()
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
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['in', 'state', [MerchantStateEnum::AUDIT]])
            ->with(['account', 'cate']);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'cates' => Yii::$app->services->merchantCate->getMapList(),
        ]);
    }

    /**
     * 通过
     *
     * @return mixed|string
     * @throws \yii\base\ExitException
     * @throws \yii\web\UnauthorizedHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionPass()
    {
        $model = new PassFrom();
        $model->merchant_id = Yii::$app->request->get('id', null);
        $model->merchant = $this->findModel($model->merchant_id);

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            $model->pass();

            return $this->message('通过成功', $this->redirect(['apply']));
        }

        Yii::$app->services->merchant->setId($model->merchant_id);

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'roles' => Yii::$app->services->rbacAuthRole->getDropDown(AppEnum::MERCHANT),
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', null);
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'cates' => Yii::$app->services->merchantCate->getMapList(),
        ]);
    }
}