<?php
namespace addons\RfExample\backend\controllers;

use yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use common\enums\StatusEnum;
use common\helpers\ResultDataHelper;
use common\helpers\ExcelHelper;
use addons\RfExample\common\models\Curd;

/**
 * Class CurdController
 * @package addons\RfExample\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class CurdController extends BaseController
{
    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex()
    {
        $title = Yii::$app->request->get('title');
        $start_time = Yii::$app->request->get('start_time', date('Y-m-d', strtotime("-60 day")));
        $end_time = Yii::$app->request->get('end_time', date('Y-m-d', strtotime("+1 day")));
        
        $data = Curd::find()
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->andFilterWhere(['like', 'title', $title])
            ->andFilterWhere(['between','created_at', strtotime($start_time), strtotime($end_time)]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('index',[
            'models' => $models,
            'pages' => $pages,
            'title' => $title,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return string|yii\console\Response|yii\web\Response
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', null);
        $model = $this->findModel($id);
        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['index']);
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
        if ($this->findModel($id)->delete()) {
            return $this->message("删除成功",$this->redirect(['index']));
        }

        return $this->message("删除失败",$this->redirect(['index']),'error');
    }

    /**
     * 更新排序/状态字段
     *
     * @param $id
     * @return array
     */
    public function actionAjaxUpdate($id)
    {
        if (!($model = Curd::findOne($id))) {
            return ResultDataHelper::json(404, '找不到数据');
        }

        $model->attributes = ArrayHelper::filter(Yii::$app->request->get(), ['sort', 'status']);
        if (!$model->save()) {
            return ResultDataHelper::json(422, $this->getError($model));
        }

        return ResultDataHelper::json(200, '修改成功');
    }

    /**
     * 导出Excel
     *
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function actionExport()
    {
        // [名称, 字段名, 类型, 类型规则]
        $header = [
            ['ID', 'id'],
            ['标题', 'title', 'text'],
            ['用户账号', 'manager.username', 'text'],
            ['状态', 'status', 'selectd', [0 => '已禁用', 1 => '已启用', -1 => '已删除']],
            ['性别', 'sex', 'function', function($model){
                return $model['sex'] == 1 ? '男' : '女';
            }],
            ['创建时间', 'created_at', 'date', 'Y-m-d'],
        ];

        $list = Curd::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->with(['manager'])
            ->asArray()
            ->all();

        return ExcelHelper::exportData($list, $header, 'Curd数据导出_' . time());
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return Curd|null
     */
    protected function findModel($id)
    {
        if (empty($id) || empty(($model = Curd::findOne($id)))) {
            $model = new Curd;
            return $model->loadDefaultValues();
        }

        return $model;
    }
}