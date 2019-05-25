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

namespace EasyWeChatComposer\Laravel;

use EasyWeChatComposer\EasyWeChat;
use EasyWeChatComposer\Encryption\DefaultEncrypter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use RuntimeException;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->publishes([
            __DIR__.'/config.php' => config_path('easywechat-composer.php'),
        ]);

        EasyWeChat::setEncryptionKey(
            $defaultKey = $this->getKey()
        );

        EasyWeChat::withDelegation()
                    ->toHost($this->config('delegation.host'))
                    ->ability($this->config('delegation.enabled'));

        $this->app->when(DefaultEncrypter::class)->needs('$key')->give($defaultKey);
    }

    /**
     * Register routes.
     */
    protected function registerRoutes()
    {
        Route::prefix('easywechat-composer')->namespace('EasyWeChatComposer\Laravel\Http\Controllers')->group(function () {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
        });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->configure();
    }

    /**
     * Register config.
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config.php', 'easywechat-composer'
        );
    }

    /**
     * Get the specified configuration value.
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed
     */
    protected function config($key = null, $default = null)
    {
        $config = $this->app['config']->get('easywechat-composer');

        if (is_null($key)) {
            return $config;
        }

        return array_get($config, $key, $default);
    }

    /**
     * @return string
     */
    protected function getKey()
    {
        return $this->config('encryption.key') ?: $this->getMd5Key();
    }

    /**
     * @return string
     */
    protected function getMd5Key()
    {
        return Cache::remember('easywechat-composer.encryption_key', 30, function () {
            throw_unless(file_exists($path = base_path('composer.lock')), RuntimeException::class, 'No encryption key provided.');

            return md5_file($path);
        });
    }
}
