<?php declare(strict_types=1);

use Bref\Runtime\LambdaRuntime;
use Bref\Runtime\PhpFpm;

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

$phpFpm = new PhpFpm($handler);
try {
    $phpFpm->start();
} catch (\Throwable $e) {
    $lambdaRuntime->failInitialization('Error while starting PHP-FPM', $e);
}

while (true) {
    $lambdaRuntime->processNextEvent(function ($event) use ($phpFpm): array {
        $response = $phpFpm->proxy($event);

        $multiHeader = array_key_exists('multiValueHeaders', $event);
        return $response->toApiGatewayFormat($multiHeader);
    });

    try {
        $phpFpm->ensureStillRunning();
    } catch (\Throwable $e) {
        echo $e->getMessage();
        exit(1);
    }
}