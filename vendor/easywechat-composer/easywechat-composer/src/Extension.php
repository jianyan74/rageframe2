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

use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\ServiceContainer;
use ReflectionClass;

class Extension
{
    /**
     * @var \EasyWeChat\Kernel\ServiceContainer
     */
    protected $app;

    /**
     * @var string
     */
    protected $manifestPath;

    /**
     * @var array|null
     */
    protected $manifest;

    /**
     * @param \EasyWeChat\Kernel\ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
        $this->manifestPath = __DIR__.'/../extensions.php';
    }

    /**
     * Get observers.
     *
     * @return array
     */
    public function observers(): array
    {
        if ($this->shouldIgnore()) {
            return [];
        }

        $observers = [];

        foreach ($this->getManifest() as $name => $extra) {
            $observers = array_merge($observers, $extra['observers'] ?? []);
        }

        return array_map([$this, 'listObserver'], array_filter($observers, [$this, 'validateObserver']));
    }

    /**
     * @param mixed $observer
     *
     * @return bool
     */
    protected function isDisable($observer): bool
    {
        return in_array($observer, $this->app->config->get('disable_observers', []));
    }

    /**
     * Get the observers should be ignore.
     *
     * @return bool
     */
    protected function shouldIgnore(): bool
    {
        return !file_exists($this->manifestPath) || $this->isDisable('*');
    }

    /**
     * Validate the given observer.
     *
     * @param mixed $observer
     *
     * @return bool
     *
     * @throws \ReflectionException
     */
    protected function validateObserver($observer): bool
    {
        return !$this->isDisable($observer)
            && (new ReflectionClass($observer))->implementsInterface(EventHandlerInterface::class)
            && $this->accessible($observer);
    }

    /**
     * Determine whether the given observer is accessible.
     *
     * @param string $observer
     *
     * @return bool
     */
    protected function accessible($observer): bool
    {
        if (!method_exists($observer, 'getAccessor')) {
            return true;
        }

        return in_array(get_class($this->app), (array) $observer::getAccessor());
    }

    /**
     * @param mixed $observer
     *
     * @return array
     */
    protected function listObserver($observer): array
    {
        $condition = method_exists($observer, 'onCondition') ? $observer::onCondition() : '*';

        return [$observer, $condition];
    }

    /**
     * Get the easywechat manifest.
     *
     * @return array
     */
    protected function getManifest(): array
    {
        if (!is_null($this->manifest)) {
            return $this->manifest;
        }

        return $this->manifest = file_exists($this->manifestPath) ? require $this->manifestPath : [];
    }
}
