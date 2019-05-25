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
 * RsaSha1 represents 'RSA-SHA1' signature method.
 *
 * Since 2.1.3 this class is deprecated, use [[RsaSha]] with `OPENSSL_ALGO_SHA1` algorithm instead.
 *
 * @see RsaSha
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class RsaSha1 extends RsaSha
{
    /**
     * {@inheritdoc}
     */
    public $algorithm = OPENSSL_ALGO_SHA1;


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'RSA-SHA1';
    }
}