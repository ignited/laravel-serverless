<?php

namespace Ignited\LaravelServerless\Tests;

use Ignited\LaravelServerless\Runtime\Handlers\CliHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class CliHandlerTest extends TestCase
{
    public function  setUp(): void {
        $_ENV['LAMBDA_TASK_ROOT'] = '/tmp';

        parent::setUp();
    }

    public function test_cli_handler_returns_correct_factory() {
        $event = [
            'cli' => 'test'
        ];

        $response = (new CliHandler())
            ->handle($event)
            ->toArray();

        $this->assertEquals($response['statusCode'], 127);
    }
}
