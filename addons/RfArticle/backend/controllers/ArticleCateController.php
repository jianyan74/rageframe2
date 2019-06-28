<?php
namespace addons\RfArticle\backend\controllers;

use Yii;
use common\components\Curd;
use addons\RfArticle\common\models\ArticleCate;
use yii\data\ActiveDataProvider;

/**
 * 文章分类
 *
 * Class ArticleCateController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ArticleCateController extends BaseController
{
    use Curd;

    /**
     * @var ArticleCate
     */
    public $modelClass = ArticleCate::class;

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = ArticleCate::find()
            ->orderBy('sort asc, created_at asc')
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @return mixed|string|\yii\console\Response|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        $model->pid = $request->get('pid', null) ?? $model->pid; // 父id

        // ajax 验证
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'cateDropDownList' => ArticleCate::getEditDropDownList($id),
        ]);
    }
}