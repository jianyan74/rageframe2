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

namespace EasyWeChatComposer\Delegation;

use EasyWeChatComposer\EasyWeChat;

class DelegationOptions
{
    /**
     * @var array
     */
    protected $config = [
        'enabled' => false,
    ];

    /**
     * @return $this
     */
    public function enable()
    {
        $this->config['enabled'] = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function disable()
    {
        $this->config['enabled'] = false;

        return $this;
    }

    /**
     * @param bool $ability
     *
     * @return $this
     */
    public function ability($ability)
    {
        $this->config['enabled'] = (bool) $ability;

        return $this;
    }

    /**
     * @param string $host
     *
     * @return $this
     */
    public function toHost($host)
    {
        $this->config['host'] = $host;

        return $this;
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        EasyWeChat::mergeConfig([
            'delegation' => $this->config,
        ]);
    }
}
