<?php

namespace NuTests;

use PHPUnit\Framework\TestCase;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
// use GuzzleHttp\Exception\RequestException;

abstract class BaseTestCase extends TestCase
{
  protected $mock;

  protected function configureMock($queue)
  {
    $this->mock = new MockHandler($queue);
    $handlerStack = HandlerStack::create($this->mock);

    return new Client(['handler' => $handlerStack, 'debug' => true]);
  }

  protected function getFixture($path)
  {
    $path = str_replace(".", "/", $path);
    $data = require __DIR__ . "/fixtures/{$path}.php";
    
    return $data;
  }
}