<?php
namespace backend\modules\member\controllers;

use Yii;
use yii\data\Pagination;
use common\enums\StatusEnum;
use common\components\CurdTrait;
use common\models\member\MemberAuth;

/**
 * Class AuthController
 * @package backend\modules\member\controllers
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
     * @return mixed
     */
    public function actionIndex()
    {
        $keyword = Yii::$app->request->get('keyword', null);

        $data = MemberAuth::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['like', 'nickname', $keyword]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->with('member')
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages,
            'keyword' => $keyword,
        ]);
    }
}