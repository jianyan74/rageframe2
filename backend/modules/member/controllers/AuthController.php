<?php
namespace backend\modules\member\controllers;

use Yii;
use common\enums\StatusEnum;
use common\components\Curd;
use common\models\member\Auth;
use common\models\common\SearchModel;
use backend\controllers\BaseController;

/**
 * Class AuthController
 * @package backend\modules\member\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AuthController extends BaseController
{
    use Curd;

    /**
     * @var string
     */
    public $modelClass = 'common\models\member\Auth';

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => Auth::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['realname', 'mobile'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['>', 'member_id', 0]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}