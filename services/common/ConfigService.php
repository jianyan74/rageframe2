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
                'value' => function (ActiveQuery $query) use ($merchant_id) {
                    return $query->andWhere(['merchant_id' => $merchant_id]);
                }
            ])
            ->all();

        foreach ($config as $item) {
            $val = $data[$item['name']] ?? '';
            /** @var ConfigValue $model */
            $model = $item->value ?? new ConfigValue();
            $model->config_id = $item->id;
            $model->data = is_array($val) ? Json::encode($val) : $val;
            $model->save();
        }

        Yii::$app->debris->configAll(true, $merchant_id);
    }

    /**
     * @param int $merchant_id 指定获取的配置信息
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAllWithValue($merchant_id)
    {
        if (!$merchant_id) {
            // 总后台强制商户 id 为 1 避免拿到错误的配置
            $merchant_id = Yii::$app->services->merchant->getId();
            AppEnum::BACKEND == Yii::$app->id && $merchant_id = 1;
        }

        $app_id = $merchant_id == 1 ? AppEnum::BACKEND : AppEnum::MERCHANT;

        return Config::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['app_id' => $app_id])
            ->with([
                'value' => function (ActiveQuery $query) use ($merchant_id) {
                    return $query->andWhere(['merchant_id' => $merchant_id]);
                }
            ])
            ->asArray()
            ->all();
    }
}