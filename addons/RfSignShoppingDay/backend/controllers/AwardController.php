<?php
namespace addons\RfSignShoppingDay\backend\controllers;

use common\components\Curd;
use addons\RfSignShoppingDay\common\models\Award;
use common\enums\StatusEnum;
use yii\data\Pagination;

/**
 * Class AwardController
 * @package addons\RfSignShoppingDay\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AwardController extends BaseController
{
    use Curd;

    /**
     * @var Award
     */
    public $modelClass = Award::class;

    /**
     * 首页
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $data = Award::find()->andFilterWhere(['merchant_id' => $this->getMerchantId()]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->all();

        // 当前概率
        $prob = Award::find()->where(['status' => StatusEnum::ENABLED])->andFilterWhere(['merchant_id' => $this->getMerchantId()])->sum('prob');

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages,
            'prob' => $prob ?? 0
        ]);
    }
}