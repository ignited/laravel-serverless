<?php

namespace Ignited\LaravelServerless\Runtime;

use Ignited\LaravelServerless\Runtime\Handlers\CliHandler;
//use Ignited\LaravelServerless\Runtime\Handlers\QueueHandler;
//use Ignited\LaravelServerless\Runtime\Handlers\UnknownEventHandler;

class CliHandlerFactory
{
    /**
     * Create a new handler for the given CLI event.
     *
     * @param  array  $event
     * @return \Ignited\LaravelServerless\Contracts\LambdaEventHandler;
     */
    public static function make(array $event)
    {
        if (isset($event['cli'])) {
            return new CliHandler;
//        } elseif (isset($event['Records'][0]['messageId'])) {
//            return new QueueHandler;
//        } else {
//            return new UnknownEventHandler;
        }
    }
}