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

namespace EasyWeChatComposer\Exceptions;

use Exception;

class DelegationException extends Exception
{
    /**
     * @var string
     */
    protected $exception;

    /**
     * @param string $exception
     */
    public function setException($exception)
    {
        $this->exception = $exception;

        return $this;
    }

    /**
     * @return string
     */
    public function getException()
    {
        return $this->exception;
    }
}
