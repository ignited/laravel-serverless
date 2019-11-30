<?php

use Ignited\LaravelServerless\Runtime\StorageDirectories;
use Ignited\LaravelServerless\Runtime\Secrets;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;

/*
|--------------------------------------------------------------------------
| Inject SSM Secrets Into Environment
|--------------------------------------------------------------------------
|
| Next, we will inject any of the application's secrets stored in AWS
| SSM into the environment variables. These variables may be a bit
| larger than the variables allowed by Lambda which has a limit.
|
*/

if(!empty($_ENV['APP_SECRETS_SSM_PATH'])) {
    fwrite(STDERR, 'Preparing to add secrets to runtime'.PHP_EOL);

    $secrets = Secrets::addToEnvironment(
        $_ENV['APP_SECRETS_SSM_PATH']
    );
}

/*
|--------------------------------------------------------------------------
| Cache Configuration
|--------------------------------------------------------------------------
|
| To give the application a speed boost, we are going to cache all of the
| configuration files into a single file. The file will be loaded once
| by the runtime then it will read the configuration values from it.
|
*/

$appRoot = getenv('LAMBDA_TASK_ROOT');

with(require $appRoot.'/bootstrap/app.php', function ($app) {
    StorageDirectories::create();

    $app->useStoragePath(StorageDirectories::PATH);

    fwrite(STDERR, 'Caching Laravel configuration'.PHP_EOL);

    $app->make(ConsoleKernelContract::class)->call('config:cache');
});