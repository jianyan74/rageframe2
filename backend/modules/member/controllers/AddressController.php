<?php
namespace backend\modules\member\controllers;

use Yii;
use yii\data\Pagination;
use common\helpers\ResultDataHelper;
use common\enums\StatusEnum;
use common\models\member\Address;
use yii\helpers\ArrayHelper;

/**
 * 收货地址
 *
 * Class AddressController
 * @package backend\modules\member\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AddressController extends MController
{
    protected $member_id;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->member_id = Yii::$app->request->get('member_id');

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
        $request  = Yii::$app->request;
        $id = $request->get('id', null);
        $model = $this->findModel($id);

        if ($model->load($request->post()) && $model->save())
        {
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
        if (!($model = Address::findOne($id)))
        {
            return $this->message("找不到数据", $this->redirect(['index']), 'error');
        }

        $model->status = StatusEnum::DELETE;
        if ($model->save())
        {
            return $this->message("删除成功", $this->redirect(['index']));
        }

        return $this->message("删除失败", $this->redirect(['index']), 'error');
    }

    /**
     * 更新排序/状态字段
     *
     * @param $id
     * @return array
     */
    public function actionAjaxUpdate($id)
    {
        if (!($model = Address::findOne($id)))
        {
            return ResultDataHelper::json(404, '找不到数据');
        }

        $model->attributes = ArrayHelper::filter(Yii::$app->request->get(), ['sort', 'status']);
        if (!$model->save())
        {
            return ResultDataHelper::json(422, $this->analyErr($model->getFirstErrors()));
        }

        return ResultDataHelper::json(200, '修改成功');
    }

    /**
     * 编辑/创建
     *
     * @return array|mixed|string|yii\web\Response
     */
    public function actionAjaxEdit()
    {
        $request  = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        if ($model->load($request->post()))
        {
            if ($request->isAjax)
            {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return \yii\widgets\ActiveForm::validate($model);
            }

            return $model->save()
                ? $this->redirect(['index', 'member_id' => $model->member_id])
                : $this->message($this->analyErr($model->getFirstErrors()), $this->redirect(['index', 'member_id' => $model->member_id]), 'error');
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
        if (empty($id) || empty(($model = Address::findOne($id))))
        {
            $model = new Address;
            $model = $model->loadDefaultValues();
            $model->member_id = $this->member_id;
            return $model;
        }

        return $model;
    }
}