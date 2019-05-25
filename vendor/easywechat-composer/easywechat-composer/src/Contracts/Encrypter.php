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

namespace EasyWeChatComposer\Contracts;

interface Encrypter
{
    /**
     * Encrypt the given value.
     *
     * @param string $value
     *
     * @return string
     */
    public function encrypt($value);

    /**
     * Decrypt the given value.
     *
     * @param string $payload
     *
     * @return string
     */
    public function decrypt($payload);
}
