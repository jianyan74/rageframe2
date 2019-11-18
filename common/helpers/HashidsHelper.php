<?php

namespace common\helpers;

use Hashids\Hashids;
use yii\web\UnprocessableEntityHttpException;

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
     * 为安全起见需要修改为自己的秘钥
     *
     * @var string
     */
    public static $secretKey = 'AWBG9zgAEfgwVv3ghsj6n4vKS9gMtTbu';

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
     * @throws UnprocessableEntityHttpException
     */
    public static function decode(string $hash)
    {
        $data = self::getHashids()->decode($hash);
        if (empty($data) || !is_array($data)) {
            throw new UnprocessableEntityHttpException('解密失败');
        }

        return count($data) == 1 ? $data[0] : $data;
    }

    /**
     * @return Hashids
     */
    private static function getHashids()
    {
        if (!self::$hashids instanceof Hashids) {
            self::$hashids = new Hashids(self::$secretKey, self::$lenght); // all lowercase
        }

        return self::$hashids;
    }
}