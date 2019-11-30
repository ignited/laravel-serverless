<?php

namespace Ignited\LaravelServerless\Runtime;

use Ignited\LaravelServerless\Contracts\LambdaResponse;

class ArrayLambdaResponse implements LambdaResponse
{
    /**
     * The response array.
     *
     * @var array
     */
    protected $response;

    /**
     * Create a new response instance.
     *
     * @param  array  $response
     * @return void
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * Convert the response to a standard Lambda event response
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}