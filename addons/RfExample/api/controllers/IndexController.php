<?php
namespace addons\RfExample\api\controllers;

use Yii;
use yii\rest\Serializer;
use yii\data\ActiveDataProvider;
use common\enums\StatusEnum;
use api\controllers\OnAuthController;
use addons\RfExample\common\models\Curd;
use addons\RfExample\api\models\CurdModel;

/**
 * Api Demo
 *
 * Class IndexController
 * @package addons\RfExample\api\controllers
 */
class IndexController extends OnAuthController
{
    public $modelClass = 'addons\RfExample\common\models\CurdModel';

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $optional = ['index', 'test'];

    /**
     * Demo访问地址
     *
     * http://www.example.com/api/addons/execute?route=index/index&addon=RfExample
     *
     * @return array|ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => CurdModel::find()
                ->where(['status' => StatusEnum::ENABLED])
                ->orderBy('id desc')
                ->asArray(),
            'pagination' => [
                'pageSize' => Yii::$app->params['user.pageSize'],
                'validatePage' => false,// 超出分页不返回data
            ],
        ]);

        // 主要生成header的page信息
        $models = (new Serializer())->serialize($data);

        // 获取数据(同上) 只不过不会去写入header里面 直接读取了models
        // $models = $data->getModels();

        foreach ($models as &$model)
        {
            $model['covers'] = unserialize($model['covers']);
            $model['files'] = json_decode($model['files']);
        }

        return $models;
    }

    /**
     * @return string
     */
    public function actionTest()
    {
        return 'test';
    }
}
            