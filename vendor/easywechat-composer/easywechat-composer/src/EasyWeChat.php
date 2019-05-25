<?php

declare(strict_types=1);

/*
 * This file is part of the EasyWeChatComposer.
 *
 * (c) 张铭阳 <mingyoungcheung@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyWeChatComposer;

use EasyWeChatComposer\Delegation\DelegationOptions;

class EasyWeChat
{
    /**
     * @var array
     */
    protected static $config = [];

    /**
     * Encryption key.
     *
     * @var string
     */
    protected static $encryptionKey;

    /**
     * @param array $config
     */
    public static function mergeConfig(array $config)
    {
        static::$config = array_merge(static::$config, $config);
    }

    /**
     * @return array
     */
    public static function config()
    {
        return static::$config;
    }

    /**
     * Set encryption key.
     *
     * @param string $key
     *
     * @return static
     */
    public static function setEncryptionKey(string $key)
    {
        static::$encryptionKey = $key;

        return new static();
    }

    /**
     * Get encryption key.
     *
     * @return string
     */
    public static function getEncryptionKey(): string
    {
        return static::$encryptionKey;
    }

    /**
     * @return \EasyWeChatComposer\Delegation\DelegationOptions
     */
    public static function withDelegation()
    {
        return new DelegationOptions();
    }
}
