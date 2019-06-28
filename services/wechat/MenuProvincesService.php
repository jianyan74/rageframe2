<?php
namespace services\wechat;

use common\components\Service;
use common\models\wechat\MenuProvinces;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;

/**
 * Class MenuProvincesService
 * @package services\wechat
 * @author jianyan74 <751393839@qq.com>
 */
class MenuProvincesService extends Service
{
    /**
     *
     * @param $pid
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListByPid($pid)
    {
        return MenuProvinces::find()
            ->where(['pid' => $pid, 'status' => StatusEnum::ENABLED])
            ->orderBy('id asc')
            ->all();
    }

    /**
     * 根据父级ID返回信息
     *
     * @param int $parentid
     * @return array
     */
    public function getMapList($pid = 0)
    {
        return ArrayHelper::map($this->getListByPid($pid), 'title', 'title');
    }

    /**
     * 根据父级标题返回信息
     *
     * @param int $parentid
     * @return array
     */
    public function getListByTitle($title)
    {
        if($model = MenuProvinces::findOne(['title' => $title, 'level' => 2, 'status' => StatusEnum::ENABLED])) {
            return $this->getMapList($model->id);
        }

        return [];
    }
}