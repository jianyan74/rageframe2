<?php

namespace services\common;

use yii\db\ActiveQuery;
use common\components\Service;
use common\models\common\ConfigCate;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;

/**
 * Class ConfigCateService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class ConfigCateService extends Service
{
    /**
     * @return array
     */
    public function getDropDown($app_id)
    {
        $models = ArrayHelper::itemsMerge($this->findAll($app_id));

        return ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');
    }

    /**
     * 获取下拉
     *
     * @param string $id
     * @return array
     */
    public function getDropDownForEdit($app_id, $id = '')
    {
        $list = ConfigCate::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['app_id' => $app_id])
            ->andFilterWhere(['<>', 'id', $id])
            ->select(['id', 'title', 'pid', 'level'])
            ->orderBy('sort asc')
            ->asArray()
            ->all();

        $models = ArrayHelper::itemsMerge($list);
        $data = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');

        return ArrayHelper::merge([0 => '顶级分类'], $data);
    }

    /**
     * 获取关联配置信息的递归数组
     *
     * @param $app_id
     * @return array
     */
    public function getItemsMergeForConfig($app_id)
    {
        return ArrayHelper::itemsMerge($this->findAllWithConfig($app_id));
    }

    /**
     * @param $cate_id
     * @return array
     */
    public function getChildIds($app_id, $cate_id)
    {
        $cates = $this->findAll($app_id);
        $cateIds = ArrayHelper::getChildIds($cates, $cate_id);
        array_push($cateIds, $cate_id);

        return $cateIds;
    }

    /**
     * 关联配置的列表
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAllWithConfig($app_id)
    {
        return ConfigCate::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['app_id' => $app_id])
            ->orderBy('sort asc')
            ->with([
                'config' => function (ActiveQuery $query) use ($app_id) {
                    return $query->andWhere(['app_id' => $app_id])
                        ->with([
                            'value' => function (ActiveQuery $query) {
                                return $query->andWhere(['merchant_id' => $this->getMerchantId()]);
                            }
                        ]);
                }
            ])
            ->asArray()
            ->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll($app_id)
    {
        return ConfigCate::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['app_id' => $app_id])
            ->asArray()
            ->all();
    }
}