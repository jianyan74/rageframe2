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

namespace EasyWeChatComposer\Commands;

use Composer\Plugin\Capability\CommandProvider;

class Provider implements CommandProvider
{
    /**
     * Retrieves an array of commands.
     *
     * @return \Composer\Command\BaseCommand[]
     */
    public function getCommands()
    {
        return [
            new ExtensionsCommand(),
        ];
    }
}
