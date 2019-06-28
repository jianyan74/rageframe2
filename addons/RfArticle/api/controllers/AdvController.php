<?php
namespace addons\RfArticle\api\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\enums\StatusEnum;
use addons\RfArticle\common\models\Adv;
use api\controllers\OnAuthController;

/**
 * Class AdvController
 * @package addons\RfArticle\api\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AdvController extends OnAuthController
{
    public $modelClass = Adv::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $optional = ['index'];

    /**
     * @return ActiveDataProvider
     */
    public function actionIndex()
    {
        // 默认为首页幻灯片
        return new ActiveDataProvider([
            'query' => Adv::find()
                ->where(['status' => StatusEnum::ENABLED])
                ->andWhere(['location_id' => Yii::$app->request->get('location_id', 1)])
                ->andWhere(['<=', 'start_time', time()])
                ->andWhere(['>=', 'end_time', time()])
                ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
                ->orderBy('sort asc')
                ->asArray()
        ]);
    }

    /**
     * 权限验证
     *
     * @param string $action 当前的方法
     * @param null $model 当前的模型类
     * @param array $params $_GET变量
     * @throws \yii\web\BadRequestHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // 方法名称
        if (in_array($action, ['delete', 'create', 'update', 'view'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}