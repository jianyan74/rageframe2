<?php
namespace backend\modules\wechat\controllers;

use Yii;
use common\components\CurdTrait;
use common\models\wechat\MsgHistory;
use yii\data\Pagination;

/**
 * 微信历史消息
 *
 * Class MsgHistoryController
 * @package backend\modules\wechat\controllers
 */
class MsgHistoryController extends WController
{
    use CurdTrait;

    /**
     * @var string
     */
    public $modelClass = 'common\models\wechat\MsgHistory';

    /**
     * 首页
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $keywords = Yii::$app->request->get('keywords', '');

        $data = MsgHistory::find()->andFilterWhere(['like', 'message', $keywords]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages,
            'keywords' => $keywords
        ]);
    }
}