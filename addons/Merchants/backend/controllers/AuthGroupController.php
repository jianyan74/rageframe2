<?php
namespace addons\Merchants\backend\controllers;

use common\enums\AppEnum;
use common\enums\StatusEnum;
use common\enums\WhetherEnum;
use common\helpers\ResultHelper;
use common\models\common\AuthGroup;
use common\traits\Curd;
use Yii;
use yii\data\ActiveDataProvider;

class AuthGroupController extends BaseController
{
    use Curd;

    public $modelClass = AuthGroup::class;

    public $appId = AppEnum::MERCHANT;

    public function actionIndex()
    {
        $query = $this->modelClass::find()
            ->where(['app_id' => $this->appId])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->orderBy('sort asc, created_at asc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
        return $this->render($this->viewPrefix . 'auth-group/index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', null);
        $model = $this->findModel($id);
        $model->app_id = $this->appId;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $model->attributes = $data;

            if (!$model->save()) {
                return ResultHelper::json(422, $this->getError($model));
            }

            // 创建角色关联的权限信息
            Yii::$app->services->authGroup->accredit($model->id, $data['userTreeIds'] ?? [], WhetherEnum::DISABLED, $this->appId);
            Yii::$app->services->authGroup->accredit($model->id, $data['plugTreeIds'] ?? [], WhetherEnum::ENABLED, $this->appId);

            return ResultHelper::json(200, '提交成功');
        }
        // 所有权限信息
        $allAuth = Yii::$app->services->authItem->getAuthInLogin($this->appId);
        // 获取当前角色权限
        list($defaultFormAuth, $defaultCheckIds, $addonsFormAuth, $addonsCheckIds) = Yii::$app->services->authGroup->getJsTreeData($id, $allAuth);

        return $this->render( $this->viewPrefix . 'auth-group/edit',[
            'model' => $model,
            'defaultFormAuth' => $defaultFormAuth,
            'defaultCheckIds' => $defaultCheckIds,
            'addonsFormAuth' => $addonsFormAuth,
            'addonsCheckIds' => $addonsCheckIds,
        ] );
    }
}