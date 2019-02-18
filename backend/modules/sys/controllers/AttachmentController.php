<?php
namespace backend\modules\sys\controllers;

use Yii;
use common\enums\StatusEnum;
use common\models\common\SearchModel;
use common\components\CurdTrait;
use common\models\common\Attachment;

/**
 * Class AttachmentController
 * @package backend\modules\sys\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AttachmentController extends SController
{
    use CurdTrait;

    /**
     * @var string
     */
    public $modelClass = 'common\models\common\Attachment';

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => Attachment::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'driveExplain' => Attachment::$driveExplain
        ]);
    }
}