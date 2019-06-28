<?php
namespace services\common;

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
    public function getDropDownList()
    {
        $models = ArrayHelper::itemsMerge($this->getList());
        return ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');
    }

    /**
     * 获取下拉
     *
     * @param string $id
     * @return array
     */
    public function getEditDropDownList($id = '')
    {
        $list = ConfigCate::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
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
     * @return array
     */
    public function getItemsMergeListWithConfig()
    {
        return ArrayHelper::itemsMerge($this->getListWithConfig());
    }

    /**
     * @param $cate_id
     * @return array
     */
    public function getChildIds($cate_id)
    {
        $cates = $this->getList();
        $cateIds = ArrayHelper::getChildIds($cates, $cate_id);
        array_push($cateIds, $cate_id);

        return $cateIds;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getList()
    {
        return ConfigCate::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
    }

    /**
     * 关联配置的列表
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListWithConfig()
    {
        return ConfigCate::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->orderBy('sort asc')
            ->with(['config'])
            ->asArray()
            ->all();
    }
}