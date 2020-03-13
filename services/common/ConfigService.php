<?php

namespace services\common;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Json;
use common\enums\StatusEnum;
use common\models\common\Config;
use common\components\Service;
use common\models\common\ConfigValue;
use common\enums\AppEnum;

/**
 * Class ConfigService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class ConfigService extends Service
{
    /**
     * 批量更新
     *
     * @param $app_id
     * @param $data
     */
    public function updateAll($app_id, $data)
    {
        $merchant_id = Yii::$app->services->merchant->getId();

        $config = Config::find()
            ->where(['in', 'name', array_keys($data)])
            ->andWhere(['app_id' => $app_id])
            ->with([
                'value' => function (ActiveQuery $query) use ($merchant_id, $app_id) {
                    return $query->andWhere(['app_id' => $app_id])->andFilterWhere(['merchant_id' => $merchant_id]);
                }
            ])
            ->all();

        /** @var Config $item */
        foreach ($config as $item) {
            $val = $data[$item['name']] ?? '';
            /** @var ConfigValue $model */
            $model = $item->value ?? new ConfigValue();
            $model->config_id = $item->id;
            $model->app_id = $item->app_id;
            $model->data = is_array($val) ? Json::encode($val) : $val;
            $model->save();
        }

        if ($app_id == AppEnum::BACKEND) {
            Yii::$app->debris->backendConfigAll(true);
        } else {
            Yii::$app->debris->merchantConfigAll(true, $merchant_id);
        }
    }

    /**
     * @param int $merchant_id 指定获取的配置信息
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAllWithValue($app_id, $merchant_id)
    {
        return Config::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['app_id' => $app_id])
            ->with([
                'value' => function (ActiveQuery $query) use ($merchant_id, $app_id) {
                    return $query->andWhere(['app_id' => $app_id])->andFilterWhere(['merchant_id' => $merchant_id]);
                }
            ])
            ->asArray()
            ->all();
    }
}