<?php

/*
 * This file is part of the overtrue/flysystem-qiniu.
 * (c) overtrue <i@overtrue.me>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Overtrue\Flysystem\Qiniu\Plugins;

use League\Flysystem\Plugin\AbstractPlugin;

class UploadToken extends AbstractPlugin
{
    public function getMethod()
    {
        return 'getUploadToken';
    }

    public function handle($key = null, $expires = 3600, $policy = null, $strictPolice = null)
    {
        return $this->filesystem->getAdapter()->getUploadToken($key, $expires, $policy, $strictPolice);
    }
}
