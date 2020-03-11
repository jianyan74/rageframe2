<?php

namespace addons\Merchants\merchant\modules\common\controllers;

use Yii;
use common\traits\Curd;
use common\models\base\SearchModel;
use common\models\common\Attachment;
use common\enums\StatusEnum;
use addons\Merchants\merchant\controllers\BaseController;

/**
 * Class AttachmentController
 * @package addons\Merchants\merchant\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AttachmentController extends BaseController
{
    use Curd;

    /**
     * @var Attachment
     */
    public $modelClass = Attachment::class;

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'driveExplain' => Attachment::$driveExplain,
            'uploadTypeExplain' => Attachment::$uploadTypeExplain,
        ]);
    }
}