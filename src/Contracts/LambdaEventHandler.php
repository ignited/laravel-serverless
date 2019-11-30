<?php

namespace Ignited\LaravelServerless\Contracts;

interface LambdaEventHandler
{
    /**
     * Handle an incoming Lambda event.
     *
     * @param  array  $event
     * @param  \Ignited\LaravelServerless\Contracts\LambdaEventHandler
     * @return \Ignited\LaravelServerless\Contracts\LambdaResponse;
     */
    public function handle(array $event);
}