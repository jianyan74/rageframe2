<?php
namespace addons\RfExample\backend\controllers;

USE yii\data\Pagination;
use addons\RfExample\common\models\ElasticSearchCurd;
use common\controllers\AddonsBaseController;

/**
 * Class ElasticSearchController
 * @package addons\RfExample\backend\controllers
 */
class ElasticSearchController extends AddonsBaseController
{
    public function actionIndex()
    {
        $data = ElasticSearchCurd::find();
        $pages = new Pagination([
            'totalCount' => $data->count(),
            'pageSize' => $this->_pageSize
        ]);

        // emails 按照desc的方式进行排序
        $sort = [
            'emails' => [
                'order' => 'desc'
            ]
        ];

        $models = $data->offset($pages->offset)
            ->limit($pages->limit)
            ->orderby($sort)
            ->all();

        $data = \yii\helpers\ArrayHelper::getColumn($models, '_source');
    }

    /**
     * 对于上面出现的must should，自己查资料，了解elasticSearch
     * 对于term 相当于等于
     * 对于terms相当于mysql中的in
     * 在上述查询中，filter是不分词，不进行同义词查询的，速度肯定要快
     * query会进行同义词查询的，速度肯定要慢一些的。
     * @return \yii\elasticsearch\ActiveQuery
     */
    public function _getSearchQuery()
    {
//        # $field_1 $field_2 都是字段
//        $filter_arr = [
//            'bool' => [
//                'must' => [
//                    ['term' => [$field_1 => 'xxxxxxx']]
//                        # $emails_arr 是数组。
//                    ['terms' => [$field_2 => $emails_arr]]  # 在查询的字段只有一个值的时候，应该使用term而不是terms，在查询字段包含多个的时候才使用terms
//            ]
//        ],
//      ];
//      # $field_1 $field_2 都是字段
//      $query_arr = [
//          'bool' => [
//              'must' => [
//                  ['match' => [$field_1 => 'xxxxx']],
//
//              ],
//              'should' => [
//                  # 关于wildcard查询可以参看文章：http://blog.csdn.net/dm_vincent/article/details/42024799
//                  ['wildcard' => [$field_2 => "W?F*HW"]]
//              ]
//          ],
//      ];
//      # Customer 就是elasticSearch 的 model
//      $query = ElasticSearchCurd::find()->filter($filter_arr)->query($query_arr);
//      return $query;
    }
}