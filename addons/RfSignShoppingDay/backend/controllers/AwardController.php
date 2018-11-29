<?php
namespace addons\RfSignShoppingDay\backend\controllers;

use common\components\CurdTrait;
use common\controllers\AddonsBaseController;
use addons\RfSignShoppingDay\common\models\Award;
use common\enums\StatusEnum;
use yii\data\Pagination;

/**
 * Class AwardController
 * @package addons\RfSignShoppingDay\backend\controllers
 */
class AwardController extends AddonsBaseController
{
    use CurdTrait;

    /**
     * @var string
     */
    public $modelClass = '\addons\RfSignShoppingDay\common\models\Award';

    /**
     * 首页
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $data = Award::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->all();

        // 当前概率
        $prob = Award::find()->where(['status' => StatusEnum::ENABLED])->sum('prob');

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages,
            'prob' => $prob ?? 0
        ]);
    }
}