<?php

namespace Ignited\LaravelServerless;

use Ignited\LaravelServerless\Runtime\StorageDirectories;
use Illuminate\Support\ServiceProvider;

class LaravelServerlessServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/serverless.yml' => $this->app['path.base'].DIRECTORY_SEPARATOR.'serverless.yml',
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!empty($_ENV['LAMBDA_TASK_ROOT'])) {
            $this->app->useStoragePath(StorageDirectories::PATH);
        }
    }
}
