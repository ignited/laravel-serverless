<?php

namespace Ignited\LaravelServerless\Tests;

use PHPUnit\Framework\TestCase;
use Ignited\LaravelServerless\Runtime\CliHandlerFactory;
use Ignited\LaravelServerless\Runtime\Handlers\CliHandler;

class CliHandlerFactoryTest extends TestCase
{
    public function test_cli_handler_returns_correct_factory() {
        $handler = CliHandlerFactory::make(['cli'=>'migrate']);

        $this->assertEquals(get_class($handler), CliHandler::class);
    }
}
