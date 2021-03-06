<?php

namespace Ignited\LaravelServerless\Runtime;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;

class HttpKernel
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Create a new HTTP kernel instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle the incoming HTTP request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request)
    {
        $this->app->useStoragePath(StorageDirectories::PATH);

        if (static::shouldSendMaintenanceModeResponse($request)) {
            $file = $_ENV['LAMBDA_TASK_ROOT'].'/public/503.html';

            if(!file_exists($file)) {
                $file = __DIR__."/../../stubs/503.html";
            }

            $response = new Response(
                file_get_contents($file), 503
            );

            $this->app->terminate();
        } else {
            $kernel = $this->resolveKernel($request);

            $response = (new Pipeline)
                ->send($request)
                ->then(function ($request) use ($kernel) {
                    return $kernel->handle($request);
                });

            $kernel->terminate($request, $response);
        }

        return $response;
    }

    /**
     * Determine if a maintenance mode response should be sent.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function shouldSendMaintenanceModeResponse(Request $request)
    {
        return isset($_ENV['APP_MAINTENANCE_MODE']) &&
            $_ENV['APP_MAINTENANCE_MODE'] === 'true' &&
            'https://'.$request->getHttpHost() !== $_ENV['APP_VANITY_URL'];
    }

    /**
     * Resolve the HTTP kernel for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Http\Kernel
     */
    protected function resolveKernel(Request $request)
    {
        return tap($this->app->make(HttpKernelContract::class), function ($kernel) use ($request) {
            $this->app->instance('request', $request);

            Facade::clearResolvedInstance('request');

            $kernel->bootstrap();
        });
    }
}