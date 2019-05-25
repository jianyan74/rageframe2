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

namespace EasyWeChatComposer\Http;

use EasyWeChat\Kernel\Contracts\Arrayable;
use EasyWeChat\Kernel\Http\Response as HttpResponse;
use JsonSerializable;

class Response implements Arrayable, JsonSerializable
{
    /**
     * @var \EasyWeChat\Kernel\Http\Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $array;

    /**
     * @param \EasyWeChat\Kernel\Http\Response $response
     */
    public function __construct(HttpResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @see \ArrayAccess::offsetExists
     *
     * @param string $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->toArray()[$offset]);
    }

    /**
     * @see \ArrayAccess::offsetGet
     *
     * @param string $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->toArray()[$offset] ?? null;
    }

    /**
     * @see \ArrayAccess::offsetSet
     *
     * @param string $offset
     * @param mixed  $value
     */
    public function offsetSet($offset, $value)
    {
        //
    }

    /**
     * @see \ArrayAccess::offsetUnset
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        //
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->array ?: $this->array = $this->response->toArray();
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
