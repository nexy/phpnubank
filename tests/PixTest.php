<?php
namespace NuTests;

use Nubank\Nubank;
use GuzzleHttp\Psr7\Response;
use Nubank\Exceptions\NuException;

class PixTest extends BaseTestCase
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

        $pixKeysData = $this->getFixture('pix.keys');
        $this->mock->append(new Response(200, [], json_encode($pixKeysData)));

        $keys = $nu->getAvailablePixKeys();
        //var_dump($keys); die();

        $this->assertEquals($keys->account_id, 'xxxxxxxxxxxxxxxxxxxxxxxx');
        $this->assertEquals($keys->keys[0]['value'], '12345678912');
    }

    public function testCreatingAvailablePixPaymentQrCode()
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

        $pixKeysData = $this->getFixture('pix.keys');
        $this->mock->append(new Response(200, [], json_encode($pixKeysData)));

        $keys = $nu->getAvailablePixKeys();

        $this->mock->reset();

        $pixQrData = $this->getFixture('pix.qr');
        $this->mock->append(new Response(200, [], json_encode($pixQrData)));

        //var_dump($keysData); die();
        $request = $nu->createAvailablePixPaymentQrCode('1231231232', 1232213.23, $keys->keys[0]);

        $this->assertNotNull($request->qr_code);

        $payment_code = [
            '12464565442165BR.GOV.BCB.PIX42136542416542146542165.005802BR5920John ',
            'Doe6009SAO PAULOSf5ASF56sf654aA65sa4f6S56fs'
        ];
        $this->assertEquals($request->payment_code, implode("", $payment_code));
        $this->assertEquals($request->payment_url, 'https://nubank.com.br/pagar/tttttt/yyyyyyy');
    }
}