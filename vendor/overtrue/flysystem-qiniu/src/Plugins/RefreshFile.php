<?php

namespace Overtrue\Flysystem\Qiniu\Plugins;

use League\Flysystem\Plugin\AbstractPlugin;

class RefreshFile extends AbstractPlugin
{
    public function getMethod()
    {
        return 'refresh';
    }

    public function handle($path = [])
    {
        return $this->filesystem->getAdapter()->refresh($path);
    }
}
