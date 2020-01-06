<?php

namespace addons\Wechat\common\enums;

use common\enums\BaseEnum;

/**
 * Class MenuLanguageEnum
 * @package addons\Wechat\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class MenuLanguageEnum extends BaseEnum
{
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            '' => '不限',
            'zh_CN' => '简体中文',
            'zh_TW' => '繁体中文TW',
            'zh_HK' => '繁体中文HK',
            'en' => '英文',
            'id' => '印尼',
            'ms' => '马来',
            'es' => '西班牙',
            'ko' => '韩国',
            'it' => '意大利',
            'ja' => '日本',
            'pl' => '波兰',
            'pt' => '葡萄牙',
            'ru' => '俄国',
            'th' => '泰文',
            'vi' => '越南',
            'ar' => '阿拉伯语',
            'hi' => '北印度',
            'he' => '希伯来',
            'tr' => '土耳其',
            'de' => '德语',
            'fr' => '法语',
        ];
    }
}