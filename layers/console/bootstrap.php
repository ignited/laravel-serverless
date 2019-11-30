#!/opt/bin/php
<?php declare(strict_types=1);

use Bref\Runtime\LambdaRuntime;
use Ignited\LaravelServerless\Runtime\CliHandlerFactory;

ini_set('display_errors', '1');
error_reporting(E_ALL);

$appRoot = getenv('LAMBDA_TASK_ROOT');

require $appRoot . '/vendor/autoload.php';

require __DIR__ . '/laravelBootstrap.php';

$lambdaRuntime = LambdaRuntime::fromEnvironmentVariable();

$handler = $appRoot . '/' . getenv('_HANDLER');
if (! is_file($handler)) {
    $lambdaRuntime->failInitialization("Handler `$handler` doesn't exist");
}

while (true) {
    $lambdaRuntime->processNextEvent(function ($event) use ($handler): array {
        return CliHandlerFactory::make($event)
                                ->handle($event)
                                ->toArray();
    });
}