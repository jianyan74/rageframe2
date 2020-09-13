<?php

namespace backend\modules\base\controllers;

use common\enums\MemberAuthEnum;
use common\helpers\HashidsHelper;
use Yii;
use yii\web\Response;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\traits\Curd;
use common\models\backend\Member;
use common\enums\AppEnum;
use common\helpers\ResultHelper;
use common\helpers\Url;
use backend\controllers\BaseController;
use backend\modules\base\forms\PasswdForm;
use backend\modules\base\forms\MemberForm;

/**
 * Class MemberController
 * @package backend\modules\base\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MemberController extends BaseController
{
    use Curd;

    /**
     * @var Member
     */
    public $modelClass = Member::class;

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        // 获取当前用户权限的下面的所有用户id，除超级管理员
        $ids = Yii::$app->services->rbacAuthAssignment->getChildIds(AppEnum::BACKEND);

        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['username', 'mobile', 'realname'], // 模糊查询
            'defaultOrder' => [
                'type' => SORT_DESC,
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andFilterWhere(['in', 'id', $ids])
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
        $model = new MemberForm();
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

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'roles' => Yii::$app->services->rbacAuthRole->getDropDown(AppEnum::BACKEND, true),
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
                return ResultHelper::json(404, $this->getError($model));
            }

            /* @var $member \common\models\backend\Member */
            $member = Yii::$app->user->identity;
            $member->password_hash = Yii::$app->security->generatePasswordHash($model->passwd_new);;

            if ($member->save()) {
                Yii::$app->user->logout();

                return ResultHelper::json(200, '修改成功');
            }

            return ResultHelper::json(404, $this->analyErr($member->getFirstErrors()));
        }

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 绑定
     *
     * @param $uuid
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionBinding($id, $type)
    {
        $uuid = HashidsHelper::encode($id);
        switch ($type) {
            case MemberAuthEnum::WECHAT;
                $getUrl = Url::toHtml5(['binding-wechat/index', 'uuid' => $uuid]);

                $qr = Yii::$app->get('qr');
                Yii::$app->response->format = Response::FORMAT_RAW;
                Yii::$app->response->headers->add('Content-Type', $qr->getContentType());

                return $qr->setText($getUrl)
                    ->setSize(200)
                    ->setMargin(7)
                    ->writeString();
                break;
        }
    }

    /**
     * 解绑
     *
     * @param $uuid
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUnBind($type, $member_id)
    {
        Yii::$app->services->backendMemberAuth->unBind($type, $member_id);

        return $this->message("解绑成功", $this->redirect(['index']));
    }
}