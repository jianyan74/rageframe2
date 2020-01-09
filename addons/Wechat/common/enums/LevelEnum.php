<?php

namespace addons\Wechat\common\enums;

use common\enums\BaseEnum;

/**
 * Class LevelEnum
 * @package addons\Wechat\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class LevelEnum extends BaseEnum
{
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            '1' => '普通订阅号',
            '2' => '普通服务号',
            '3' => '认证订阅号',
            '4' => '认证服务号/认证媒体/政府订阅号',
        ];
    }
}