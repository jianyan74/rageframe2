<?php

namespace addons\Wechat\common\enums;

use common\enums\BaseEnum;

/**
 * Class MediaType
 * @package addons\Wechat\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class MediaType extends BaseEnum
{
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            'news' => '微信图文',
            'image' => '图片',
            'voice' => '语音',
            'video' => '视频',
        ];
    }
}