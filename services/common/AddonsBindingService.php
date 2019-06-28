<?php
namespace services\common;

use Yii;
use common\components\Service;
use common\models\common\AddonsBinding;

/**
 * Class AddonsBindingService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class AddonsBindingService extends Service
{
    /**
     * 创建
     *
     * @param $data
     * @param $entry
     * @param $addons_name
     * @throws \Exception
     */
    public static function careteEntry($data, $entry, $addons_name)
    {
        AddonsBinding::deleteAll(['entry' => $entry, 'addons_name' => $addons_name]);
        foreach ($data as $vo) {
            $model = new AddonsBinding();
            $model->attributes = $vo;
            $model->entry = $entry;
            $model->addons_name = $addons_name;
            if (!$model->save()) {
                $error = Yii::$app->debris->analyErr($model->getFirstErrors());
                throw new \Exception($error);
            }
        }
    }
}