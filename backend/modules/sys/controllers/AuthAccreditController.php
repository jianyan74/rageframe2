<?php
namespace backend\modules\sys\controllers;

use yii;
use common\models\sys\AuthItem;
use common\helpers\ArrayHelper;
use common\helpers\ResultDataHelper;

/**
 * RBAC权限控制器
 *
 * Class AuthAccreditController
 * @package backend\modules\sys\controllers
 */
class AuthAccreditController extends SController
{
    /**
     * 权限管理
     *
     * @return string
     */
    public function actionIndex()
    {
        $models = AuthItem::find()
            ->where(['type' => AuthItem::AUTH])
            ->asArray()
            ->orderBy('sort asc')
            ->all();

        return $this->render('index', [
            'models' => ArrayHelper::itemsMerge($models, 'key', 0, 'parent_key'),
        ]);
    }

    /**
     * 权限编辑
     *
     * @return array|mixed|string|yii\web\Response
     */
    public function actionAjaxEdit()
    {
        $request = Yii::$app->request;
        $name = $request->get('name');
        $model = $this->findModel($name);
        // 父级key
        $model->level = $request->get('level', 1);// 等级
        $model->parent_key = $request->get('parent_key', 0);
        $model->type = AuthItem::AUTH;

        if ($model->load($request->post()))
        {
            if ($request->isAjax)
            {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return \yii\widgets\ActiveForm::validate($model);
            }
            else
            {
                return $model->save()
                    ? $this->redirect(['index'])
                    : $this->message($this->analyErr($model->getFirstErrors()), $this->redirect(['index']), 'error');
            }
        }

        $parent_name = "暂无";
        if ($model->parent_key != 0)
        {
            $prent = AuthItem::find()->where(['key' => $model->parent_key])->one();
            $parent_name = $prent['description'];
        }

        return $this->renderAjax('ajax-edit', [
            'model' => $model,
            'parent_name' => $parent_name,
        ]);
    }

    /**
     * 删除
     *
     * @param $name
     * @return mixed
     * @throws \Throwable
     * @throws yii\db\StaleObjectException
     */
    public function actionDelete($name)
    {
        if ($this->findModel($name)->delete())
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
        if (!($model = AuthItem::findOne(['key' => $id])))
        {
            return ResultDataHelper::json(404, '找不到数据');
        }

        $getData = Yii::$app->request->get();
        foreach (['key', 'sort', 'status'] as $item)
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
     * 返回模型
     *
     * @param $name
     * @return mixed
     */
    protected function findModel($name)
    {
        if (empty($name) || empty(($model = AuthItem::findOne($name))))
        {
            $model = new AuthItem();
            return $model->loadDefaultValues();
        }

        return $model;
    }
}