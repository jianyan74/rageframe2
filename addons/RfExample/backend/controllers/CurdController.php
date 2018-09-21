<?php
namespace addons\RfExample\backend\controllers;

use yii;
use yii\data\Pagination;
use common\controllers\AddonsBaseController;
use common\helpers\ResultDataHelper;
use addons\RfExample\common\models\Curd;

/**
 * curd
 *
 * Class CurdController
 * @package addons\RfExample\backend\controllers
 */
class CurdController extends AddonsBaseController
{
    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex()
    {
        $data = Curd::find();
        $pages = new Pagination([
            'totalCount' => $data->count(),
            'pageSize' => $this->_pageSize
        ]);

        $models = $data->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('index',[
            'models' => $models,
            'pages' => $pages,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return string|yii\console\Response|yii\web\Response
     */
    public function actionEdit()
    {
        $request  = Yii::$app->request;
        $id = $request->get('id', null);
        $model = $this->findModel($id);
        $model->covers = unserialize($model->covers);
        $model->files = json_decode($model->files, true);
        if ($model->load($request->post()))
        {
            $model->covers = serialize($model->covers);
            $model->files = json_encode($model->files);

            if ($model->save())
            {
                return $this->redirect(['index']);
            }
        }

        return $this->render('edit',[
            'model' => $model
        ]);
    }

    /**
     * 删除
     *
     * @param $id
     * @return mixed
     * @throws \Throwable
     * @throws yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete())
        {
            return $this->message("删除成功",$this->redirect(['index']));
        }

        return $this->message("删除失败",$this->redirect(['index']),'error');
    }

    /**
     * 更新排序/状态字段
     *
     * @return array
     */
    public function actionAjaxUpdate()
    {
        $data = Yii::$app->request->get();
        $insertData = [];
        foreach (['id', 'sort', 'status'] as $item)
        {
            isset($data[$item]) && $insertData[$item] = $data[$item];
        }

        unset($data);

        if (!($model = Curd::findOne($insertData['id'])))
        {
            return ResultDataHelper::result(404, '找不到数据');
        }

        $model->attributes = $insertData;
        if (!$model->save())
        {
            return ResultDataHelper::result(422, $this->analyErr($model->getFirstErrors()));
        }

        return ResultDataHelper::result(200, '修改成功');
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return Curd|null
     */
    protected function findModel($id)
    {
        if (empty($id) || empty(($model = Curd::findOne($id))))
        {
            $model = new Curd;
            return $model->loadDefaultValues();
        }

        return $model;
    }
}