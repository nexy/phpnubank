<?php
namespace NuTests;

use Nubank\Nubank;
use GuzzleHttp\Psr7\Response;
use Nubank\Exceptions\NuException;
use Nubank\Exceptions\NuMissingCreditCardException;

class BoletoTest extends BaseTestCase
{
    public function testGettingAvailableKeys()
    {
        $proxyData = $this->getFixture('proxy');
        $accessToken = $this->getFixture('loggedin');
        $liftData = $this->getFixture('lift');
        $client = $this->configureMock([
            new Response(200, [], json_encode($proxyData)),
            new Response(200, [], json_encode($proxyData)),
            new Response(200, [], json_encode($accessToken)),
            new Response(200, [], json_encode($liftData)),
        ]);

        $nu = new Nubank($client);
        $nu->authenticateWithQrCode('12345678912', 'hunter12', 'some-uuid');

        $this->mock->reset();

        $pixKeysData = $this->getFixture('boleto.customer');
        $this->mock->append(new Response(200, [], json_encode($pixKeysData)));

        $pixKeysData = $this->getFixture('boleto.barcode');
        $this->mock->append(new Response(200, [], json_encode($pixKeysData)));

        $this->assertEquals($nu->createBoleto(200.50), '123131321231231.2313212312.2131231.21332123');
    }
}