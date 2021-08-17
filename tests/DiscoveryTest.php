<?php
namespace NuTests;
// use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Response;

use Nubank\Requests\Discovery;
use Nubank\Exceptions\NuException;

class DiscoveryTest extends BaseTestCase
{
  public function testDiscoverPathsSuccessfully()
  {
    $proxyData = require __DIR__ . '/fixtures/proxy.php';

    $client = $this->configureMock([
      new Response(200, [], json_encode($proxyData)),
      new Response(200, [], json_encode($proxyData)),
    ]);

    $d = new Discovery($client);

    $this->assertEquals($d->getUrl('token'), $proxyData['token']);
  }

  public function testUrlNotExists()
  {
    $this->expectException(NuException::class);

    $proxyData = require __DIR__ . '/fixtures/proxy.php';

    $client = $this->configureMock([
      new Response(200, [], json_encode($proxyData)),
      new Response(200, [], json_encode($proxyData)),
    ]);

    $d = new Discovery($client);

    $d->getUrl('some-url');
  }
}