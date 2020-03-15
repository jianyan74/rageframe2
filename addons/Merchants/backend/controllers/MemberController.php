<?php

namespace addons\Merchants\backend\controllers;

use Yii;
use common\models\base\SearchModel;
use common\traits\Curd;
use common\models\merchant\Member;
use common\enums\StatusEnum;
use common\enums\AppEnum;
use common\helpers\ArrayHelper;
use addons\Merchants\backend\forms\MemberForm;

/**
 * Class MemberController
 * @package addons\Merchants\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MemberController extends BaseController
{
    use Curd;

    /**
     * @var Member
     */
    public $modelClass = Member::class;

    public $merchant_id;

    public function init()
    {
        parent::init();

        $this->merchant_id = Yii::$app->request->get('merchant_id');
        Yii::$app->services->merchant->setId($this->merchant_id);
    }

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['username', 'mobile', 'realname'], // 模糊查询
            'defaultOrder' => [
                'type' => SORT_DESC,
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['merchant_id' => $this->merchant_id])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->with('assignment');

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'merchant_id' => $this->merchant_id
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
        $model->merchant_id = $this->merchant_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'merchant_id' => $this->merchant_id]);
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'merchant_id' => $this->merchant_id
        ]);
    }

    /**
     * 创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     * @throws \yii\db\Exception
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function actionAjaxEdit()
    {
        $request = Yii::$app->request;
        $model = new MemberForm();
        $model->id = $request->get('id');
        $model->loadData();
        $model->scenario = 'generalAdmin';

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            return $model->save()
                ? $this->redirect(['index', 'merchant_id' => $this->merchant_id])
                : $this->message($this->getError($model), $this->redirect(['index', 'merchant_id' => $this->merchant_id]), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'roles' => Yii::$app->services->rbacAuthRole->getDropDown(AppEnum::MERCHANT),
            'merchant_id' => $this->merchant_id
        ]);
    }

    /**
     * 伪删除
     *
     * @param $id
     * @return mixed
     */
    public function actionDestroy($id)
    {
        if (!($model = $this->modelClass::findOne($id))) {
            return $this->message("找不到数据", $this->redirect(['index', 'merchant_id' => $this->merchant_id]), 'error');
        }

        $model->status = StatusEnum::DELETE;
        if ($model->save()) {
            return $this->message("删除成功", $this->redirect(['index', 'merchant_id' => $this->merchant_id]));
        }

        return $this->message("删除失败", $this->redirect(['index', 'merchant_id' => $this->merchant_id]), 'error');
    }
}