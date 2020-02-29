<?php

namespace addons\Wechat\services;

use Yii;
use yii\web\UnprocessableEntityHttpException;
use addons\Wechat\common\models\Menu;
use common\components\Service;

/**
 * Class MenuService
 * @package addons\Wechat\services
 * @author jianyan74 <751393839@qq.com>
 */
class MenuService extends Service
{
    /**
     * @param Menu $model
     * @param $data
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function createSave(Menu $model, $data)
    {
        $buttons = [];
        foreach ($data as &$button) {
            $arr = [];
            // 判断是否有子菜单
            if (isset($button['sub_button'])) {
                $arr['name'] = $button['name'];

                foreach ($button['sub_button'] as &$sub) {
                    $sub_button = $this->mergeButton($sub);
                    $sub_button['name'] = $sub['name'];
                    $sub_button['type'] = $sub['type'];
                    $arr['sub_button'][] = $sub_button;
                }
            } else {
                $arr = $this->mergeButton($button);
                $arr['name'] = $button['name'];
                $arr['type'] = $button['type'];
            }

            $buttons[] = $arr;
        }

        $model->menu_data = $buttons;
        // 判断写入是否成功
        if (!$model->validate()) {
            throw new UnprocessableEntityHttpException(Yii::$app->debris->analyErr($model->getFirstErrors()));
        }

        // 个性化菜单
        if ($model->type == Menu::TYPE_INDIVIDUATION) {
            $matchRule = [
                "tag_id" => $model->tag_id,
                "sex" => $model->sex,
                "country" => "中国",
                "province" => trim($model->province),
                "client_platform_type" => $model->client_platform_type,
                "language" => $model->language,
                "city" => trim($model->city),
            ];

            // 创建自定义菜单
            $menuResult = Yii::$app->wechat->app->menu->create($buttons, $matchRule);
            Yii::$app->debris->getWechatError($menuResult);
            $model->menu_id = $menuResult['menuid'];
        } else {
            // 验证微信报错
            Yii::$app->debris->getWechatError(Yii::$app->wechat->app->menu->create($buttons));
        }

        if (!$model->save()) {
            throw new UnprocessableEntityHttpException(Yii::$app->debris->analyErr($model->getFirstErrors()));
        }
    }

    /**
     * 合并前端过来的数据
     *
     * @param array $button
     * @return array
     */
    public function mergeButton(array $button)
    {
        $arr = [];
        if ($button['type'] == 'click' || $button['type'] == 'view') {
            $arr[Menu::$menuTypes[$button['type']]['meta']] = $button['content'];
        } elseif ($button['type'] == 'miniprogram') {
            $arr['appid'] = $button['appid'];
            $arr['pagepath'] = $button['pagepath'];
            $arr['url'] = $button['url'];
        } else {
            $arr[Menu::$menuTypes[$button['type']]['meta']] = Menu::$menuTypes[$button['type']]['value'];
        }

        return $arr;
    }

    /**
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function sync()
    {
        // 获取菜单列表
        $list = Yii::$app->wechat->app->menu->list();
        // 解析微信接口是否报错
        Yii::$app->debris->getWechatError($list);

        // 开始获取自定义菜单同步
        if (!empty($list['menu'])) {
            $model = new Menu;
            $model->title = "默认菜单";
            $model = $model->loadDefaultValues();
            $model->menu_data = $list['menu']['button'];
            $model->menu_id = isset($list['menu']['menuid']) ? $list['menu']['menuid'] : '';
            $model->save();
        }

        // 个性化菜单
        if (!empty($list['conditionalmenu'])) {
            foreach ($list['conditionalmenu'] as $menu) {
                if (!($model = Menu::findOne(['menu_id' => $menu['menuid']]))) {
                    $model = new Menu;
                    $model = $model->loadDefaultValues();
                }

                $model->title = "个性化菜单";
                $model->attributes = $menu['matchrule'];
                $model->type = Menu::TYPE_INDIVIDUATION;
                $model->tag_id = isset($menu['group_id']) ? $menu['group_id'] : '';
                $model->menu_data = $menu['button'];
                $model->menu_id = $menu['menuid'];
                $model->save();
            }
        }
    }
}