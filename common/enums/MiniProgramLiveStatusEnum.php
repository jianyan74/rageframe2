<?php

namespace common\enums;

/**
 * 小程序直播状态
 *
 * Class MiniProgramLiveStatusEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class MiniProgramLiveStatusEnum extends BaseEnum
{
    const UNDERWAY = 101;
    const NOT_STARTED = 102;
    const END = 103;
    const FORBIDDEN = 104;
    const SUSPEND = 105;
    const ABNORMAL = 106;
    const PAST_DUE = 107;

    /**
     * @return array|string[]
     */
    public static function getMap(): array
    {
        return [
            self::UNDERWAY => '直播中',
            self::NOT_STARTED => '未开始',
            self::END => '已结束',
            self::FORBIDDEN => '禁播',
            self::SUSPEND => '暂停中',
            self::ABNORMAL => '异常',
            self::PAST_DUE => '已过期',
        ];
    }
}