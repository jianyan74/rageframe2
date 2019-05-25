<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\authclient\signature;

/**
 * @deprecated
 *
 * HmacSha1 represents 'HMAC-SHA1' signature method.
 *
 * Since 2.1.3 this class is deprecated, use [[HmacSha]] with `sha1` algorithm instead.
 *
 * @see HmacSha
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class HmacSha1 extends HmacSha
{
    /**
     * {@inheritdoc}
     */
    public $algorithm = 'sha1';
}
