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

namespace EasyWeChatComposer\Encryption;

use EasyWeChatComposer\Contracts\Encrypter;
use EasyWeChatComposer\Exceptions\DecryptException;
use EasyWeChatComposer\Exceptions\EncryptException;

class DefaultEncrypter implements Encrypter
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $cipher;

    /**
     * @param string $key
     * @param string $cipher
     */
    public function __construct($key, $cipher = 'AES-256-CBC')
    {
        $this->key = $key;
        $this->cipher = $cipher;
    }

    /**
     * Encrypt the given value.
     *
     * @param string $value
     *
     * @return string
     *
     * @throws \EasyWeChatComposer\Exceptions\EncryptException
     */
    public function encrypt($value)
    {
        $iv = random_bytes(openssl_cipher_iv_length($this->cipher));

        $value = openssl_encrypt($value, $this->cipher, $this->key, 0, $iv);

        if ($value === false) {
            throw new EncryptException('Could not encrypt the data.');
        }

        $iv = base64_encode($iv);

        return base64_encode(json_encode(compact('iv', 'value')));
    }

    /**
     * Decrypt the given value.
     *
     * @param string $payload
     *
     * @return string
     *
     * @throws \EasyWeChatComposer\Exceptions\DecryptException
     */
    public function decrypt($payload)
    {
        $payload = json_decode(base64_decode($payload), true);

        $iv = base64_decode($payload['iv']);

        $decrypted = openssl_decrypt($payload['value'], $this->cipher, $this->key, 0, $iv);

        if ($decrypted === false) {
            throw new DecryptException('Could not decrypt the data.');
        }

        return $decrypted;
    }
}
