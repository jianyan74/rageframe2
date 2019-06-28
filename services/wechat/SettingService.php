<?php
namespace services\wechat;

use Yii;
use yii\helpers\Json;
use common\components\Service;
use common\enums\StatusEnum;
use common\enums\WechatEnum;
use common\models\wechat\Setting;
use common\helpers\ArrayHelper;

/**
 * Class SettingService
 * @package services\wechat
 * @author jianyan74 <751393839@qq.com>
 */
class SettingService extends Service
{
    /**
     * 获取特殊消息回复
     *
     * @return array
     */
    public function specialConfig()
    {
        // 获取支持的模块
        $modules = Yii::$app->services->addons->getList();

        $list = WechatEnum::$typeExplanation;
        $defaultList = [];
        foreach ($list as $key => $value) {
            $defaultList[$key]['title'] = $value;
            $defaultList[$key]['type'] = Setting::SPECIAL_TYPE_KEYWORD;
            $defaultList[$key]['content'] = '';
            $defaultList[$key]['module'] = [];

            foreach ($modules as $module) {
                $wechat_message = !empty($module['wechat_message']) ? unserialize($module['wechat_message']) : [];
                $wechat_message = $wechat_message ?? [];

                foreach ($wechat_message as $item) {
                    if ($key == $item) {
                        $defaultList[$key]['module'][$module['name']] = $module['title'];
                        break;
                    }
                }
            }
        }

        if (!empty($special = $this->getByFieldName('special'))) {
            $defaultList = ArrayHelper::merge($defaultList, $special);
        }

        return $defaultList;
    }

    /**
     * 写入字段数据
     *
     * @return array|mixed
     */
    public function setByFieldName($filds, $data)
    {
        $row = [];
        $row[$filds] = Json::encode($data);
        if (!($setting = $this->getOne())) {
            $setting = new Setting();
        }

        $setting->attributes = $row;
        return $setting->save();
    }

    /**
     * @param $field
     * @return array|mixed
     */
    public function getByFieldName($field)
    {
        $setting = $this->getOne();
        if (!empty($setting[$field])) {
            return json_decode($setting[$field], true);
        }

        return [];
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getList()
    {
        return Setting::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->all();
    }

    /**
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getOne()
    {
        return Setting::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }
}