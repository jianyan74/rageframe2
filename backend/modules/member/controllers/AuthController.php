<?php
namespace backend\modules\member\controllers;

use Yii;
use common\enums\StatusEnum;
use common\components\CurdTrait;
use common\models\member\MemberAuth;
use common\models\common\SearchModel;

/**
 * Class AuthController
 * @package backend\modules\member\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AuthController extends MController
{
    use CurdTrait;

    /**
     * @var string
     */
    public $modelClass = 'common\models\member\MemberAuth';

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => MemberAuth::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['realname', 'mobile'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['>=', 'status', StatusEnum::DISABLED])->andWhere(['>', 'member_id', 0]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}