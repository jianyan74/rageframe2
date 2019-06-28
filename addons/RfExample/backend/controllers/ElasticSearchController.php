<?php
namespace addons\RfExample\backend\controllers;

use Yii;
USE yii\data\Pagination;
use common\enums\StatusEnum;
use common\components\Curd;
use common\helpers\StringHelper;
use addons\RfExample\common\models\ElasticSearchCurd;

/**
 * Class ElasticSearchController
 * @package addons\RfExample\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ElasticSearchController extends BaseController
{
    use Curd;

    public $modelClass = ElasticSearchCurd::class;

    /**
     * 到时候正式请配置在main里面
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        /** ------ 全文搜索引擎 ------ **/

        // 配置了es的集群，那么需要在http_address中把每一个节点的ip都要配置上
        Yii::$app->set('elasticsearch', [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '127.0.0.1:9200'],
                // ['http_address' => '192.168.0.210:9200'],
            ],
        ]);

        // 更新字段，每次修改字段都需执行该方法
        ElasticSearchCurd::updateMapping();

        // 删库 注意使用
        // ElasticSearchCurd::deleteMapping();
        // 获取字段
        // ElasticSearchCurd::getMapping();

        parent::init();
    }

    /**
     * @return string
     * @throws \yii\elasticsearch\Exception
     */
    public function actionIndex()
    {
        $data = ElasticSearchCurd::find();
        $pages = new Pagination([
            'totalCount' => $data->count(),
            'pageSize' => $this->pageSize
        ]);

        // sort 字段按照desc的方式进行排序
        $sort = [
            'sort' => [
                'order' => 'desc'
            ]
        ];

        // 查询文档 https://blog.csdn.net/taoshujian/article/details/60397099
        // http://blog.csdn.net/dm_vincent/article/details/42024799
        $filterArr = [
            'bool' => [
                'must' => [ // 此示例编写两个match查询，即查询title中包含“1”和 '2'帐户。bool must子句表示所有条件必须满足 类似于判断条件中的&&。
                    ['match' => ['title' => '1']],
                    ['match' => ['title' => '2']]
                ],
                'should' => [ // 相比之下，此示例中两个match则是查询并title中包含“1”或“2”的所有帐户。bool should类似于判断条件中的||
                    ['match' => ['title' => '1']],
                    ['match' => ['title' => '2']]
                ],
                'must_not' => [ // 此示例的两个match查询，则是查询title中既不包含“1”也不包含“3”的所有帐户。
                    ['match' => ['title' => '1']],
                    ['match' => ['title' => '2']]
                ],
            ]
        ];

        $filterArr = [];

        $models = $data->offset($pages->offset)
            ->limit($pages->limit)
            ->orderby($sort)
            ->query($filterArr)
            ->asArray()
            ->all();

        // $data = ArrayHelper::getColumn($models, '_source');

        return $this->render('index',[
            'models' => $models,
            'pages' => $pages,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            if ($this->findModel($id)->delete()) {
                return $this->message("删除成功", $this->redirect(['index']));
            }
        } catch(\Exception $e) {

        }

        return $this->message("删除失败", $this->redirect(['index']), 'error');
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return ElasticSearchCurd|null
     * @throws \Exception
     */
    protected function findModel($id)
    {
        if (empty($id) || empty(($model = ElasticSearchCurd::findOne($id)))) {
            $model = new ElasticSearchCurd();
            $model->primaryKey = StringHelper::uuid('uniqid');
            $model->status = StatusEnum::ENABLED;
            $model->sort = 0;
            return $model;
        }

        return $model;
    }
}