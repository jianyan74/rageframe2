<?php

namespace common\helpers;

use Yii;
use Hashids\Hashids;

/**
 * ID加密辅助类
 *
 * Class HashidsHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class HashidsHelper
{
    /**
     * 长度
     *
     * @var int
     */
    public static $lenght = 10;

    /**
     * @var \Hashids\Hashids
     */
    protected static $hashids;

    /**
     * 加密
     *
     * @param mixed ...$numbers
     * @return string
     */
    public static function encode(...$numbers)
    {
        return self::getHashids()->encode(...$numbers);
    }

    /**
     * 解密
     *
     * @param string $hash
     * @return array
     */
    public static function decode(string $hash)
    {
        return self::getHashids()->decode($hash);
    }

    /**
     * @return Hashids
     */
    private static function getHashids()
    {
        if (!self::$hashids instanceof Hashids) {
            self::$hashids = new Hashids(Yii::$app->request->cookieValidationKey, self::$lenght); // all lowercase
        }

        return self::$hashids;
    }
}