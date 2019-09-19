<?php

namespace services\sys;

use common\helpers\ArrayHelper;
use common\enums\StatusEnum;
use common\components\Service;
use common\models\sys\MenuCate;

/**
 * Class MenuCateService
 * @package services\sys
 * @author jianyan74 <751393839@qq.com>
 */
class MenuCateService extends Service
{
    /**
     * 获取首个显示的分类
     *
     * @return false|null|string
     */
    public function getFirstId()
    {
        return MenuCate::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->orderBy('sort asc')
            ->select(['id'])
            ->scalar();
    }

    /**
     * 查询 - 获取授权成功的全部分类
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getOnAuthList()
    {
        $models = $this->getAllList();

        return $models;
    }

    /**
     * 编辑 - 获取正常分类Map列表
     *
     * @return array
     */
    public function getMapDefaultMapList()
    {
        return ArrayHelper::map($this->getDefaultList(), 'id', 'title');
    }

    /**
     * 编辑 - 获取正常的分类
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getDefaultList()
    {
        return MenuCate::find()
            ->where(['is_addon' => StatusEnum::DISABLED])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->orderBy('sort asc')
            ->asArray()
            ->all();
    }

    /**
     * 查询 - 获取全部分类
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAllList()
    {
        return MenuCate::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->orderBy('sort asc')
            ->asArray()
            ->all();
    }
}