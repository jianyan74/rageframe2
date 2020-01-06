<?php

namespace common\enums;

use common\helpers\ArrayHelper;

/**
 * Class LevelEnum
 * @author Maomao
 * @package common\enums
 */
class MemberLevelEnum
{
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return ArrayHelper::numBetween(2, 70);
    }

    const OR = 0;
    const AND = 1;

    /**
     * @return array
     */
    public static function getMiddle(): array
    {
        return [
            self::OR => '或',
            self::AND => '且',
        ];
    }
}