<?php
namespace services\common;

use Yii;
use common\models\common\ConfigValue;
use common\enums\StatusEnum;
use common\models\common\Config;
use common\components\Service;

/**
 * Class ConfigService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class ConfigService extends Service
{
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListWithValue()
    {
        return Config::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->with(['value'])
            ->asArray()
            ->all();
    }

    /**
     * 批量更新
     *
     * @param $data
     * @throws \yii\base\InvalidConfigException
     */
    public function updateAll($data)
    {
        $names = array_keys($data);
        $config = Config::find()
            ->where(['in', 'name', $names])
            ->with(['value'])
            ->all();

        foreach ($config as $item) {
            $model = !empty($item['value']) ? $item['value'] : new ConfigValue();
            $model->config_id = $item->id;
            $model->data = is_array($data[$item['name']]) ? serialize($data[$item['name']]) : $data[$item['name']];
            $model->save();
        }

        Yii::$app->debris->configAll(true);
    }
}