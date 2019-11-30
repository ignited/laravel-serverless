<?php

namespace Ignited\LaravelServerless;

use Ignited\LaravelServerless\Runtime\StorageDirectories;
use Illuminate\Support\ServiceProvider;

class LaravelServerlessServiceProvider extends ServiceProvider
{
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