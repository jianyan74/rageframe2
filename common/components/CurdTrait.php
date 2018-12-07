<?php
namespace common\components;

use Yii;
use yii\data\Pagination;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\base\InvalidConfigException;
use common\helpers\ResultDataHelper;
use common\enums\StatusEnum;

/**
 * CURD基类特性
 *
 * 注意：会覆盖父类的继承方法，注意使用
 * Trait CurdTrait
 * @package backend\components
 */
trait CurdTrait
{
    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->modelClass === null)
        {
            throw new InvalidConfigException('"modelClass" 属性必须设置.');
        }

        parent::init();
    }

    /**
     * 首页
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $data = $this->modelClass::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages
        ]);
    }

    /**
     * 编辑/新增
     *
     * @return mixed
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', null);
        $model = $this->findModel($id);

        if ($model->load($request->post()) && $model->save())
        {
            return $this->redirect(['index']);
        }

        return $this->render($this->action->id, [
            'model' => $model,
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
        if (!($model = $this->modelClass::findOne($id)))
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
     * 直接删除
     *
     * @param $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete())
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
        // 兼容Grid多主键
        if (!is_numeric($id) && ($idArr = json_decode($id, true)))
        {
            $id = $idArr['id'];
        }

        if (!($model = $this->modelClass::findOne($id)))
        {
            return ResultDataHelper::json(404, '找不到数据');
        }

        $getData = Yii::$app->request->get();
        foreach (['sort', 'status'] as $item)
        {
            isset($getData[$item]) && $model->$item = $getData[$item];
        }

        if (!$model->save())
        {
            return ResultDataHelper::json(422, $this->analyErr($model->getFirstErrors()));
        }

        return ResultDataHelper::json(200, '修改成功');
    }

    /**
     * 编辑/新增
     *
     * @return array|mixed|string|Response
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
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->analyErr($model->getFirstErrors()), $this->redirect(['index']), 'error');
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
        if (empty($id) || empty(($model = $this->modelClass::findOne($id))))
        {
            $model = new $this->modelClass;
            return $model->loadDefaultValues();
        }

        return $model;
    }
}