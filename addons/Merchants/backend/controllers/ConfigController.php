<?php

namespace addons\Merchants\backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\common\Config;
use common\traits\Curd;
use common\enums\ConfigTypeEnum;
use common\enums\AppEnum;

/**
 * Class ConfigController
 * @package addons\Merchants\backend\controllers
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
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['app_id' => AppEnum::MERCHANT])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->viewPrefix . 'config/index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'cateDropDownList' => Yii::$app->services->configCate->getDropDown(AppEnum::MERCHANT),
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
        $model->app_id = AppEnum::MERCHANT;

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax($this->viewPrefix . 'config/ajax-edit', [
            'model' => $model,
            'configTypeList' => ConfigTypeEnum::getMap(),
            'cateDropDownList' => Yii::$app->services->configCate->getDropDown(AppEnum::MERCHANT),
        ]);
    }
}