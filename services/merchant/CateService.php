<?php

namespace services\merchant;

use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\components\Service;
use common\helpers\TreeHelper;
use common\models\merchant\Cate;

/**
 * Class Cate
 * @package addons\TinyShop\common\components\product
 * @author jianyan74 <751393839@qq.com>
 */
class CateService extends Service
{
    /**
     * 获取下拉
     *
     * @param string $id
     * @return array
     */
    public function getDropDownForEdit($id = '')
    {
        $list = Cate::find()
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
     * @return array
     */
    public function getMapList()
    {
        $models = ArrayHelper::itemsMerge($this->getList());

        return ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');
    }

    /**
     * @param string $pid
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getList()
    {
        return Cate::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->orderBy('sort asc, id desc')
            ->asArray()
            ->all();
    }

    /**
     * 获取首页推荐
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findIndexBlock()
    {
        return Cate::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['index_block_status' => StatusEnum::ENABLED])
            ->orderBy('sort asc, id desc')
            ->cache(60)
            ->asArray()
            ->all();
    }

    /**
     * 获取所有下级id
     *
     * @param $id
     * @return array
     */
    public function findChildIdsById($id)
    {
        if ($model = $this->findById($id)) {
            $tree = $model['tree'] .  TreeHelper::prefixTreeKey($id);
            $list = $this->getChilds($tree);

            return ArrayHelper::merge([$id], array_column($list, 'id'));
        }

        return [];
    }

    /**
     * 获取所有下级
     *
     * @param $tree
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getChilds($tree)
    {
        return Cate::find()
            ->where(['like', 'tree', $tree . '%', false])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->asArray()
            ->all();
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null|Cate
     */
    public function findById($id)
    {
        return Cate::find()
            ->where(['id' => $id])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->asArray()
            ->one();
    }
}