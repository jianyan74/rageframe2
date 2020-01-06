<?php

namespace addons\Merchants\backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\traits\Curd;
use common\enums\StatusEnum;
use common\enums\AppEnum;
use common\enums\WhetherEnum;
use common\models\common\AuthItem;

/**
 * Class AuthItemController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AuthItemController extends BaseController
{
    use Curd;

    /**
     * @var AuthItem
     */
    public $modelClass = AuthItem::class;

    /**
     * 默认应用
     *
     * @var string
     */
    public $appId = AppEnum::MERCHANT;

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = $this->modelClass::find()
            ->where(['app_id' => $this->appId, 'is_addon' => WhetherEnum::DISABLED])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->orderBy('sort asc, created_at asc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render($this->viewPrefix . 'auth-item/index', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', '');
        /** @var AuthItem $model */
        $model = $this->findModel($id);
        $model->pid = $request->get('pid', null) ?? $model->pid; // 父id
        $model->app_id = $this->appId;
        $model->is_addon = WhetherEnum::DISABLED;

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax($this->viewPrefix . 'auth-item/ajax-edit', [
            'model' => $model,
            'dropDownList' => Yii::$app->services->authItem->getDropDownForEdit(AppEnum::MERCHANT, $id),
        ]);
    }
}