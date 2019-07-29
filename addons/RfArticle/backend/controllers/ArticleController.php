<?php
namespace addons\RfArticle\backend\controllers;

use Yii;
use yii\data\Pagination;
use common\enums\StatusEnum;
use common\components\Curd;
use common\models\base\SearchModel;
use addons\RfArticle\common\models\ArticleCate;
use addons\RfArticle\common\models\ArticleTag;
use addons\RfArticle\common\models\ArticleTagMap;
use addons\RfArticle\common\models\Article;

/**
 * 文章管理
 *
 * Class ArticleController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ArticleController extends BaseController
{
    use Curd;

    /**
     * @var Article
     */
    public $modelClass = Article::class;

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => Article::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'sort' => SORT_ASC,
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'cates' => ArticleCate::getDropDown()
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return string|\yii\console\Response|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', null);
        $model = $this->findModel($id);

        // 设置选中标签
        $tagMap = ArticleTagMap::getTagsByActicleId($id);
        $model->tags = array_column($tagMap, 'tag_id');
        // 推荐位
        $positionExplain = Article::$positionExplain;
        $keys = [];
        foreach ($positionExplain as $key => $value) {
            if (Article::checkPosition($key, $model->position)) {
                $keys[] = $key;
            }
        }
        $model->position = $keys;

        if ($model->load($request->post()) && $model->save()) {
            // 更新文章标签
            ArticleTagMap::addTags($model->id, $model->tags);
            return $this->redirect(['index']);
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'cates' => ArticleCate::getDropDown(),
            'positionExplain' => $positionExplain,
            'tags' => ArticleTag::getCheckTags(),
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
        if ($model->save()) {
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
        if ($model->save()) {
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
        $data = Article::find()
            ->where(['<', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()]);
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