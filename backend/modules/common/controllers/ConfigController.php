<?php

namespace backend\modules\common\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\common\Config;
use common\helpers\ResultDataHelper;
use common\components\Curd;
use backend\controllers\BaseController;

/**
 * Class ConfigController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ConfigController extends BaseController
{
    use Curd;

    /**
     * @var \yii\db\ActiveRecord
     */
    public $modelClass = Config::class;

    /**
     * 首页
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => Config::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title', 'name'], // 模糊查询
            'defaultOrder' => [
                'cate_id' => SORT_ASC,
                'sort' => SORT_ASC,
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'cateDropDownList' => Yii::$app->services->configCate->getDropDownList()
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
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'configTypeList' => Yii::$app->params['configTypeList'],
            'cateDropDownList' => Yii::$app->services->configCate->getDropDownList()
        ]);
    }

    /**
     * 网站设置
     *
     * @return string
     */
    public function actionEditAll()
    {
        return $this->render($this->action->id, [
            'cates' => Yii::$app->services->configCate->getItemsMergeListWithConfig()
        ]);
    }

    /**
     * ajax批量更新数据
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdateInfo()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $config = $request->post('config', []);
            Yii::$app->services->config->updateAll($config);
            return ResultDataHelper::json(200, "修改成功");
        }

        throw new NotFoundHttpException('请求出错!');
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     */
    protected function findModel($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($id) || empty(($model = $this->modelClass::findOne($id)))) {
            $model = new $this->modelClass;
            return $model->loadDefaultValues();
        }

        return $model;
    }
}