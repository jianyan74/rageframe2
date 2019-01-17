<?php
namespace addons\RfExample\backend\controllers;

use common\enums\StatusEnum;
use common\helpers\StringHelper;
use common\components\CurdTrait;
use common\controllers\AddonsBaseController;
use addons\RfExample\common\models\MongoDbCurd;

/**
 * Class MongoDbCurdController
 * @package addons\RfExample\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MongoDbCurdController extends AddonsBaseController
{
    use CurdTrait;

    public $modelClass = 'addons\RfExample\common\models\MongoDbCurd';

    /**
     * 返回模型
     *
     * @param $id
     * @return MongoDbCurd|null
     * @throws \Exception
     */
    protected function findModel($id)
    {
        if (empty($id) || empty(($model = MongoDbCurd::findOne($id))))
        {
            $model = new MongoDbCurd();
            $model->_id = StringHelper::uuid('uniqid');
            $model->status = StatusEnum::ENABLED;
            return $model;
        }

        return $model;
    }
}