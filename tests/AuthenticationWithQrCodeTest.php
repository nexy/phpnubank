<?php
namespace NuTests;

use Nubank\Nubank;
use Nubank\Exceptions\NuException;
use GuzzleHttp\Psr7\Response;

class AuthenticateWithQrCodeTest extends BaseTestCase
{
    public function testAuthenticationSuccess()
    {
        $proxyData = require __DIR__ . '/fixtures/proxy.php';
        $accessToken = require __DIR__ . '/fixtures/loggedin.php';
        $linksData = require __DIR__ . '/fixtures/lift.php';
        $client = $this->configureMock([
            new Response(200, [], json_encode($proxyData)),
            new Response(200, [], json_encode($proxyData)),
            new Response(200, [], json_encode($accessToken)),
            new Response(200, [], json_encode($linksData)),
        ]);

        $nu = new Nubank($client);
        $nu->authenticateWithQrCode('12345678912', 'hunter12', 'some-uuid');

        $this->assertEquals($nu->feed_url, 'https://mocked-proxy-url/api/proxy/events_123');
        $this->assertEquals($nu->getAuthRequest()->getHeader('Authorization'), 'Bearer access_token_123');
    }

    public function te_stRevokingCertificate()
    {
        $this->expectException(NuException::class);
        $this->expectExceptionMessage('Header Authorization not found');

        $proxyData = require __DIR__ . '/fixtures/proxy.php';
        $accessToken = ['access_token' => 'access_token_123'];
        $linksData = require __DIR__ . '/fixtures/lift.php';
        $client = $this->configureMock([
            new Response(200, [], json_encode($proxyData)),
            new Response(200, [], json_encode($proxyData)),
            new Response(200, [], json_encode($accessToken)),
            new Response(200, [], json_encode($linksData)),
            new Response(200, [], json_encode($linksData)),
        ]);

        $nu = new Nubank($client);
        $nu->authenticateWithQrCode('12345678912', 'hunter12', 'some-uuid');

        $this->mock->reset();

        $this->mock->append(new Response(200, [], '{}'));

        $nu->revokeToken();
    }
}