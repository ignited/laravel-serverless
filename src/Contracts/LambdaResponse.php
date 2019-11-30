<?php

namespace Ignited\LaravelServerless\Contracts;

interface LambdaResponse
{
    /**
     * Convert the response to a standard Lambda event response
     *
     * @return array
     */
    public function toArray();

}