<?php
namespace addons\RfArticle\wechat\controllers;

use Yii;
use yii\data\Pagination;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\helpers\ResultDataHelper;
use addons\RfArticle\common\models\ArticleSingle;

/**
 * Class IndexController
 * @package addons\RfArticle\wechat\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class IndexController extends BaseController
{
    /**
     * 首页
     *
     * @return array|string
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax)
        {
            $data = ArticleSingle::find()
                ->select(['id', 'title', 'cover', 'created_at'])
                ->where(['status' => StatusEnum::ENABLED])
                ->andFilterWhere(['merchant_id' => $this->getMerchantId()]);

            $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize, 'validatePage' => false]);
            $models = $data->offset($pages->offset)
                ->orderBy('sort asc, id desc')
                ->limit($pages->limit)
                ->asArray()
                ->all();

            foreach ($models as &$model)
            {
                $model['link'] = Url::to(['detail', 'id' => $model['id']]);
                $model['created_at'] = date('Y-m-d', $model['created_at']);
            }

            return ResultDataHelper::json(200, '获取成功', $models);
        }

        return $this->render('index');
    }

    /**
     * 详情
     *
     * @param $id
     * @return string
     */
    public function actionDetail($id)
    {
        $model = ArticleSingle::find()
            ->where(['id' => $id, 'status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();

        $model->view += 1;
        $model->save();

        return $this->render('detail', [
            'model' => $model,
        ]);
    }
}