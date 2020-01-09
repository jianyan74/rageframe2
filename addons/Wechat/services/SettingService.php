<?php

namespace addons\Wechat\services;

use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use common\components\Service;
use common\enums\StatusEnum;
use common\enums\WechatEnum;
use addons\Wechat\common\models\Setting;
use common\helpers\ArrayHelper;

/**
 * Class SettingService
 * @package addons\Wechat\services
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

        $list = WechatEnum::getMap();
        $defaultList = [];
        foreach ($list as $key => $value) {
            $defaultList[$key]['title'] = $value;
            $defaultList[$key]['type'] = Setting::SPECIAL_TYPE_KEYWORD;
            $defaultList[$key]['content'] = '';
            $defaultList[$key]['module'] = [];

            foreach ($modules as $module) {
                $wechat_message = [];

                if (!empty($module['wechat_message'])) {
                    $wechat_message = $module['wechat_message'];

                    if (!is_array($module['wechat_message'])) {
                        $wechat_message = Json::decode($module['wechat_message']);
                    }
                }

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
     * @param $filds
     * @param $data
     * @return bool
     * @throws NotFoundHttpException
     */
    public function setByFieldName($filds, $data)
    {
        if (!($setting = $this->getOne())) {
            $setting = new Setting();
        }

        $setting->$filds = Json::encode($data);
        if (!$setting->save()) {
            throw new NotFoundHttpException($this->getError($setting));
        }

        return true;
    }

    /**
     * @param $field
     * @return array|mixed
     */
    public function getByFieldName($field)
    {
        $setting = $this->getOne();

        if (!empty($setting[$field])) {
            return is_array($setting[$field]) ? $setting[$field] : Json::decode($setting[$field]);
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