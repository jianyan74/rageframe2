<?php

namespace Overtrue\Flysystem\Qiniu\Plugins;

use League\Flysystem\Plugin\AbstractPlugin;

class PrivateDownloadUrl extends AbstractPlugin
{
    public function getMethod()
    {
        return 'privateDownloadUrl';
    }

    public function handle($path, $expires = 3600)
    {
        return $this->filesystem->getAdapter()->privateDownloadUrl($path, $expires);
    }
}
