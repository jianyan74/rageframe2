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

namespace EasyWeChatComposer\Tests;

use EasyWeChatComposer\ManifestManager;
use PHPUnit\Framework\TestCase;

class ManifestManagerTest extends TestCase
{
    private $vendorPath;
    private $manifestPath;

    protected function getManifestManager()
    {
        return new ManifestManager(
            $this->vendorPath = __DIR__.'/__fixtures__/vendor/',
            $this->manifestPath = __DIR__.'/__fixtures__/extensions.php'
        );
    }

    public function testUnlink()
    {
        $this->assertInstanceOf(ManifestManager::class, $this->getManifestManager()->unlink());
        $this->assertFalse(file_exists($this->manifestPath));
    }
}
