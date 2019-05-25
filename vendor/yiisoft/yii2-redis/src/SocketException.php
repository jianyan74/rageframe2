<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\redis;

use yii\db\Exception;

/**
 * SocketException indicates a socket connection failure in [[Connection]].
 * @since 2.0.7
 */
class SocketException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Redis Socket Exception';
    }
}
