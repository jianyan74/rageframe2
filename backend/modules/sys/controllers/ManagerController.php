<?php

namespace backend\modules\sys\controllers;

use Yii;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\components\Curd;
use common\models\sys\Manager;
use common\enums\AppEnum;
use common\helpers\ArrayHelper;
use common\helpers\ResultDataHelper;
use backend\controllers\BaseController;
use backend\modules\sys\forms\PasswdForm;
use backend\modules\sys\forms\ManagerForm;

/**
 * Class ManagerController
 * @package backend\modules\sys\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ManagerController extends BaseController
{
    use Curd;

    /**
     * @var Manager
     */
    public $modelClass = Manager::class;

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        // 获取当前用户权限的下面的所有用户id，除超级管理员
        $authIds = Yii::$app->services->authRole->getChildIds();
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
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->andFilterWhere(['in', 'id', $authIds])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->with('assignment');

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionAjaxEdit()
    {
        $request = Yii::$app->request;
        $model = new ManagerForm();
        $model->id = $request->get('id');
        $model->loadData();
        $model->id != Yii::$app->params['adminAccount'] && $model->scenario = 'generalAdmin';

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        // 角色信息
        $role = Yii::$app->services->authRole->getRole();
        $childRoles = Yii::$app->services->authRole->getChildList(AppEnum::BACKEND, $role);
        $roles = ArrayHelper::itemsMerge($childRoles, $role['id'] ?? 0);
        $roles = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($roles, 'id', 'title', isset($role['level']) ? $role['level'] + 1 : 1), 'id', 'title');

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'roles' => $roles,
        ]);
    }

    /**
     * 个人中心
     *
     * @return mixed|string
     */
    public function actionPersonal()
    {
        $id = Yii::$app->user->id;
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->message('修改个人信息成功', $this->redirect(['personal']));
        }

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 修改密码
     *
     * @return array|string
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpPassword()
    {
        $model = new PasswdForm();
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->validate()) {
                return ResultDataHelper::json(404, $this->getError($model));
            }

            /* @var $manager \common\models\sys\Manager */
            $manager = Yii::$app->user->identity;
            $manager->password_hash = Yii::$app->security->generatePasswordHash($model->passwd_new);;

            if ($manager->save()) {
                Yii::$app->user->logout();
                return ResultDataHelper::json(200, '修改成功');
            }

            return ResultDataHelper::json(404, $this->analyErr($manager->getFirstErrors()));
        }

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }
}