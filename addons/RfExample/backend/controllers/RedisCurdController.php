<?php
namespace addons\RfExample\backend\controllers;

use Yii;
use addons\RfExample\common\models\RedisCurd;
use common\components\Curd;
use common\helpers\StringHelper;
use common\enums\StatusEnum;

/**
 * Class RedisCurdController
 * @package addons\RfExample\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class RedisCurdController extends BaseController
{
    use Curd;

    /**
     * @var RedisCurd
     */
    public $modelClass = RedisCurd::class;

    /**
     * @param $id
     * @return RedisCurd|null
     * @throws \Exception
     */
    protected function findModel($id)
    {
        if (empty($id) || empty(($model = $this->modelClass::findOne($id)))) {
            $model = new $this->modelClass();
            $model->id = StringHelper::uuid('uniqid');
            $model->status = StatusEnum::ENABLED;
            return $model;
        }

        return $model;
    }
}