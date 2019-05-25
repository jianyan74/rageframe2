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

namespace EasyWeChatComposer;

class ManifestManager
{
    const PACKAGE_TYPE = 'easywechat-extension';

    const EXTRA_OBSERVER = 'observers';

    /**
     * The vendor path.
     *
     * @var string
     */
    protected $vendorPath;

    /**
     * The manifest path.
     *
     * @var string
     */
    protected $manifestPath;

    /**
     * @param string      $vendorPath
     * @param string|null $manifestPath
     */
    public function __construct(string $vendorPath, string $manifestPath = null)
    {
        $this->vendorPath = $vendorPath;
        $this->manifestPath = $manifestPath ?: $vendorPath.'/easywechat-composer/easywechat-composer/extensions.php';
    }

    /**
     * Remove manifest file.
     *
     * @return $this
     */
    public function unlink()
    {
        if (file_exists($this->manifestPath)) {
            @unlink($this->manifestPath);
        }

        return $this;
    }

    /**
     * Build the manifest file.
     */
    public function build()
    {
        $packages = [];

        if (file_exists($installed = $this->vendorPath.'/composer/installed.json')) {
            $packages = json_decode(file_get_contents($installed), true);
        }

        $this->write($this->map($packages));
    }

    /**
     * @param array $packages
     *
     * @return array
     */
    protected function map(array $packages): array
    {
        $manifest = [];

        $packages = array_filter($packages, function ($package) {
            return $package['type'] === self::PACKAGE_TYPE;
        });

        foreach ($packages as $package) {
            $manifest[$package['name']] = [self::EXTRA_OBSERVER => $package['extra'][self::EXTRA_OBSERVER] ?? []];
        }

        return $manifest;
    }

    /**
     * Write the manifest array to a file.
     *
     * @param array $manifest
     */
    protected function write(array $manifest)
    {
        file_put_contents(
            $this->manifestPath,
            '<?php return '.var_export($manifest, true).';'
        );

        $this->invalidate($this->manifestPath);
    }

    /**
     * Invalidate the given file.
     *
     * @param string $file
     */
    protected function invalidate($file)
    {
        if (function_exists('opcache_invalidate')) {
            @opcache_invalidate($file, true);
        }
    }
}
