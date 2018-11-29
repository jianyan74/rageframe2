<?php
namespace addons\RfArticle\backend\controllers;

use Yii;
use common\controllers\AddonsBaseController;
use common\components\CurdTrait;
use common\enums\StatusEnum;
use addons\RfArticle\common\models\ArticleCate;
use addons\RfArticle\common\models\ArticleTag;
use addons\RfArticle\common\models\ArticleTagMap;
use addons\RfArticle\common\models\Article;
use yii\data\Pagination;

/**
 * 文章管理
 *
 * Class ArticleController
 * @package addons\RfArticle\backend\controllers
 */
class ArticleController extends AddonsBaseController
{
    use CurdTrait;

    /**
     * @var string
     */
    public $modelClass = 'addons\RfArticle\common\models\Article';

    /**
     * 首页
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $data = Article::find()->where(['>=', 'status', StatusEnum::DISABLED]);
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
     * @return string|\yii\console\Response|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', null);
        $model = $this->findModel($id);

        // 文章标签
        $tags = ArticleTag::find()->with([
            'tagMap' => function($query) use ($id){
                $query->andWhere(['article_id' => $id]);
            },])->all();

        if ($model->load($request->post()) && $model->save())
        {
            // 更新文章标签
            ArticleTagMap::addTags($model->id, $request->post('tag', []));

            return $this->redirect(['index']);
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'cates' => ArticleCate::getDropDown(),
            'positionExplain' => Article::$positionExplain,
            'tags' => $tags,
        ]);
    }

    /**
     * 还原
     *
     * @param $id
     * @return mixed
     */
    public function actionShow($id)
    {
        $model = $this->findModel($id);
        $model->status = StatusEnum::ENABLED;
        if ($model->save())
        {
            return $this->message("还原成功", $this->redirect(['recycle']));
        }

        return $this->message("还原失败", $this->redirect(['recycle']), 'error');
    }

    /**
     * 删除
     *
     * @param $id
     * @return mixed
     */
    public function actionHide($id)
    {
        $model = $this->findModel($id);
        $model->status = StatusEnum::DELETE;
        if ($model->save())
        {
            return $this->message("删除成功", $this->redirect(['index']));
        }

        return $this->message("删除失败", $this->redirect(['index']), 'error');
    }

    /**
     * 回收站
     *
     * @return mixed
     */
    public function actionRecycle()
    {
        $data = Article::find()->where(['<', 'status', StatusEnum::DISABLED]);
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
}