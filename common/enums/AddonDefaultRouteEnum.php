<?php

namespace common\enums;

use common\models\common\Addons;

/**
 * Class AddonsDefaultRouteEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AddonDefaultRouteEnum extends BaseEnum
{
    const COVER = 'addons/cover';
    const RULE = 'addons/rule-index';
    const RULE_EDIT = 'addons/rule-edit';
    const RULE_DELETE = 'addons/rule-delete';
    const RULE_AJAX_UPDATE = 'addons/ajax-update';
    const SETTING = 'setting/display';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::COVER => '应用入口',
            self::RULE => '规则列表',
            self::RULE_EDIT => '规则编辑',
            self::RULE_DELETE => '规则删除',
            self::RULE_AJAX_UPDATE => '规则更新状态',
            self::SETTING => '参数设置',
        ];
    }

    /**
     * @param $app_id
     * @param $name
     * @return array
     */
    public static function route($app_id, $name)
    {
        return [
            [
                'name' => self::COVER,
                'title' => self::getValue(self::COVER),
                'app_id' => $app_id,
                'is_addon' => WhetherEnum::ENABLED,
                'addons_name' => $name,
                'sort' => 1,
            ],
            [
                'name' => self::RULE,
                'title' => self::getValue(self::RULE),
                'app_id' => $app_id,
                'is_addon' => WhetherEnum::ENABLED,
                'addons_name' => $name,
                'sort' => 1,
            ],
            [
                'name' => self::RULE_EDIT,
                'title' => self::getValue(self::RULE_EDIT),
                'app_id' => $app_id,
                'is_addon' => WhetherEnum::ENABLED,
                'addons_name' => $name,
                'sort' => 1,
            ],
            [
                'name' => self::RULE_AJAX_UPDATE,
                'title' => self::getValue(self::RULE_AJAX_UPDATE),
                'app_id' => $app_id,
                'is_addon' => WhetherEnum::ENABLED,
                'addons_name' => $name,
                'sort' => 1,
            ],
            [
                'name' => self::RULE_DELETE,
                'title' => self::getValue(self::RULE_DELETE),
                'app_id' => $app_id,
                'is_addon' => WhetherEnum::ENABLED,
                'addons_name' => $name,
                'sort' => 1,
            ],
            [
                'name' => self::SETTING,
                'title' => self::getValue(self::SETTING),
                'app_id' => $app_id,
                'is_addon' => WhetherEnum::ENABLED,
                'addons_name' => $name,
                'sort' => 1,
            ],
        ];
    }
}