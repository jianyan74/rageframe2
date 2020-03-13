<?php

namespace addons\Merchants\api\modules\v1\controllers;

use Yii;
use api\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\merchant\Cate;

/**
 * Class CateController
 * @package addons\Merchants\api\modules\v1\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class CateController extends OnAuthController
{
    /**
     * @var Cate
     */
    public $modelClass = Cate::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['index', 'list'];

    /**
     * @return array|\yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        $list = Yii::$app->services->merchantCate->getList();

        return ArrayHelper::itemsMerge($list, 0, 'id', 'pid', 'child');
    }

    /**
     * 根据上级ID获取下级分类数据
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionList()
    {
        $pid = Yii::$app->request->get('pid');
        $index_block_status = Yii::$app->request->get('index_block_status');

        return Cate::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['pid' => $pid])
            ->andFilterWhere(['index_block_status' => $index_block_status])
            ->orderBy('sort asc, id desc')
            ->asArray()
            ->all();
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
        if (in_array($action, ['delete', 'update', 'view', 'create'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}