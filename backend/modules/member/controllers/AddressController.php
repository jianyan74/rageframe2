<?php

namespace backend\modules\member\controllers;

use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use common\helpers\ResultHelper;
use common\enums\StatusEnum;
use common\models\member\Address;
use common\models\member\Member;
use backend\controllers\BaseController;

/**
 * 收货地址
 *
 * Class AddressController
 * @package backend\modules\member\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AddressController extends BaseController
{
    protected $member_id;
    /**
     * @var Member
     */
    protected $member;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->member_id = Yii::$app->request->get('member_id');
        $this->member = Yii::$app->services->member->findById($this->member_id);

        parent::init();
    }

    /**
     * 首页
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $data = Address::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['member_id' => $this->member_id]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages,
            'member_id' => $this->member_id,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', null);
        $model = $this->findModel($id);
        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['index', 'member_id' => $this->member_id]);
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'member_id' => $this->member_id,
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
        if (!($model = Address::findOne($id))) {
            return $this->message("找不到数据", $this->redirect(['index']), 'error');
        }

        $model->status = StatusEnum::DELETE;
        if ($model->save()) {
            return $this->message("删除成功", $this->redirect(['index', 'member_id' => $model->member_id]));
        }

        return $this->message("删除失败", $this->redirect(['index', 'member_id' => $model->member_id]), 'error');
    }

    /**
     * 更新排序/状态字段
     *
     * @param $id
     * @return array
     */
    public function actionAjaxUpdate($id)
    {
        if (!($model = Address::findOne($id))) {
            return ResultHelper::json(404, '找不到数据');
        }

        $model->attributes = ArrayHelper::filter(Yii::$app->request->get(), ['sort', 'status']);
        if (!$model->save()) {
            return ResultHelper::json(422, $this->getError($model));
        }

        return ResultHelper::json(200, '修改成功');
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(['index', 'member_id' => $model->member_id])
                : $this->message($this->getError($model), $this->redirect(['index', 'member_id' => $model->member_id]), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return mixed
     */
    protected function findModel($id)
    {
        if (empty($id) || empty(($model = Address::findOne($id)))) {
            $model = new Address;
            $model = $model->loadDefaultValues();
            $model->member_id = $this->member_id;
            $model->merchant_id = $this->member->merchant_id;
            return $model;
        }

        return $model;
    }
}