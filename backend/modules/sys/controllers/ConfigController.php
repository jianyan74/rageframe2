<?php
namespace backend\modules\sys\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\sys\Config;
use common\models\sys\ConfigCate;
use common\helpers\ResultDataHelper;
use common\components\CurdTrait;

/**
 * 配置控制器
 *
 * Class ConfigController
 * @package backend\modules\sys\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ConfigController extends SController
{
    use CurdTrait;

    /**
     * @var
     */
    public $modelClass = 'common\models\sys\Config';

    /**
     * 首页
     *
     * @param string $cate_id
     * @return string
     */
    public function actionIndex()
    {
        $keyword = Yii::$app->request->get('keyword', '');
        $cate_id = Yii::$app->request->get('cate_id', null);

        // 查询所有子分类
        $cateIds = [];
        if (!empty($cate_id))
        {
            $cates = ConfigCate::getMultiDate(['status' => StatusEnum::ENABLED], ['*']);
            $cateIds = ArrayHelper::getChildIds($cates, $cate_id);
            array_push($cateIds, $cate_id);
        }

        $data = Config::find()
            ->filterWhere(['in', 'cate_id', $cateIds])
            ->andFilterWhere(['or',
                ['like', 'title', $keyword],
                ['like', 'name', $keyword]
            ]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('cate_id asc, sort asc')
            ->with('cate')
            ->limit($pages->limit)
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages,
            'cate_id' => $cate_id,
            'keyword' => $keyword,
            'cateDropDownList' => ConfigCate::getDropDownList()
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return array|mixed|string|\yii\web\Response
     */
    public function actionAjaxEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()))
        {
            if ($request->isAjax)
            {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return \yii\widgets\ActiveForm::validate($model);
            }

            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->analyErr($model->getFirstErrors()), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'configTypeList' => Yii::$app->params['configTypeList'],
            'cateDropDownList' => ConfigCate::getDropDownList()
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
            'cates' => ConfigCate::getItemsMergeList()
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
        if ($request->isAjax)
        {
            // 记录行为日志
            Yii::$app->services->sys->log('updateConfig', '修改配置信息');

            $config = $request->post('config', []);
            foreach ($config as $key => $value)
            {
                if ($model = Config::find()->where(['name' => $key])->one())
                {
                    $model->value = is_array($value) ? serialize($value) : $value;
                    if (!$model->save())
                    {
                        return ResultDataHelper::json(422, $this->analyErr($model->getFirstErrors()));
                    }
                }
                else
                {
                    return ResultDataHelper::json(422, "配置不存在,请刷新页面");
                }
            }

            Yii::$app->debris->clearConfigCache();
            return ResultDataHelper::json(200, "修改成功");
        }

        throw new NotFoundHttpException('请求出错!');
    }
}