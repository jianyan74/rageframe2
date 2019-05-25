<?php

/*
 * This file is part of the overtrue/flysystem-qiniu.
 * (c) overtrue <i@overtrue.me>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Overtrue\Flysystem\Qiniu\Plugins;

use League\Flysystem\Plugin\AbstractPlugin;

class FileUrl extends AbstractPlugin
{
    public function getMethod()
    {
        return 'getUrl';
    }

    public function handle($path)
    {
        return $this->filesystem->getAdapter()->getUrl($path);
    }
}
