<?php
namespace NuTests;

use Nubank\Nubank;
use GuzzleHttp\Psr7\Response;
use Nubank\Exceptions\NuException;

class AccountTest extends BaseTestCase
{
    public function testGettingAccountFeed()
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

        $feedData = $this->getFixture('account.feed');
        $this->mock->append(new Response(200, [], json_encode($feedData)));

        $stmt = $nu->getAccountFeed()['statements'];

        $this->assertEquals($stmt[0]['id'], 'e409e495-4a16-4bad-9ddb-5c447c84fdcb');
        $this->assertEquals($stmt[0]['__typename'], 'TransferOutEvent');
        $this->assertEquals($stmt[0]['title'], 'Transferência enviada');
        $this->assertEquals($stmt[0]['detail'], 'Waldisney da Silva - R$ 4.496,90');
        $this->assertEquals($stmt[0]['postDate'], '2021-04-14');
        $this->assertEquals($stmt[0]['amount'], '4496.9');

        $this->assertEquals($stmt[1]['id'], 'acb9a16b-2a1c-40cc-a20b-0778a4503f12');
        $this->assertEquals($stmt[1]['__typename'], 'TransferInEvent');
        $this->assertEquals($stmt[1]['title'], 'Transferência recebida');
        $this->assertEquals($stmt[1]['detail'], 'R$ 1.483,80');
        $this->assertEquals($stmt[1]['postDate'], '2021-04-06');
        $this->assertEquals($stmt[1]['amount'], '1483.8');
        $this->assertEquals($stmt[1]['originAccount']['name'], 'Waldisney da Silva');
    }

    public function testGettingAccountBalance()
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

        $balanceData = $this->getFixture('account.balance');
        $this->mock->append(new Response(200, [], json_encode($balanceData)));

        $this->assertEquals($nu->getAccountBalance(), '127.33');
    }

    public function testGettingAccountStatements()
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

        $feedData = $this->getFixture('account.feed');
        $this->mock->append(new Response(200, [], json_encode($feedData)));

        $stmt = $nu->getAccountStatements();

        $this->assertEquals($stmt[0]['id'], 'e409e495-4a16-4bad-9ddb-5c447c84fdcb');
        $this->assertEquals($stmt[0]['__typename'], 'TransferOutEvent');
        $this->assertEquals($stmt[0]['title'], 'Transferência enviada');
        $this->assertEquals($stmt[0]['detail'], 'Waldisney da Silva - R$ 4.496,90');
        $this->assertEquals($stmt[0]['postDate'], '2021-04-14');
        $this->assertEquals($stmt[0]['amount'], '4496.9');

        $this->assertEquals($stmt[22]['id'], 'a9f96774-37f2-431e-9e6f-a081defacf25');
        $this->assertEquals($stmt[22]['__typename'], 'BarcodePaymentEvent');
        $this->assertEquals($stmt[22]['title'], 'Pagamento efetuado');
        $this->assertEquals($stmt[22]['detail'], 'CONFIDENCE CORRETORA DE CAMBIO S A');
        $this->assertEquals($stmt[22]['postDate'], '2020-12-08');
        $this->assertEquals($stmt[22]['amount'], '4245.10');
    }

    public function testGettingAccountInvestmentsDetails()
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

        $feedData = $this->getFixture('account.investments_details');
        $this->mock->append(new Response(200, [], json_encode($feedData)));

        $stmt = $nu->getAccountInvestimentsDetails();

        $this->assertEquals($stmt[0]['id'], 'vjdhausd-asdg-bgfs-vfsg-jrthfuv');
        $this->assertEquals($stmt[0]['rate'], 1);
        $this->assertEquals($stmt[0]['vehicle'], 'RECEIPT_DEPOSIT');
        $this->assertEquals($stmt[0]['openDate'], '2020-07-13');
        $this->assertEquals($stmt[0]['maturityDate'], '2022-07-05');
        $this->assertEquals($stmt[0]['principal'], 156.52);
        $this->assertEquals($stmt[0]['redeemedBalance']['netAmount'], 0);
        $this->assertEquals($stmt[0]['redeemedBalance']['yield'], 0);
        $this->assertEquals($stmt[0]['redeemedBalance']['incomeTax'], 0);
        $this->assertEquals($stmt[0]['redeemedBalance']['iofTax'], 0);
        $this->assertEquals($stmt[0]['redeemedBalance']['id'], 'abcdefgh-ijkl-mnop-qrst-uvwxyz0123');

        $this->assertEquals($stmt[2]['id'], 'ffghjyu-ktyu-dfgn-nfgh-asdgre');
        $this->assertEquals($stmt[2]['rate'], 1);
        $this->assertEquals($stmt[2]['vehicle'], 'RECEIPT_DEPOSIT');
        $this->assertEquals($stmt[2]['openDate'], '2020-08-11');
        $this->assertEquals($stmt[2]['maturityDate'], '2022-08-03');
        $this->assertEquals($stmt[2]['principal'], 77.77);
        $this->assertEquals($stmt[2]['redeemedBalance']['netAmount'], 39.99);
        $this->assertEquals($stmt[2]['redeemedBalance']['yield'], 0.05);
        $this->assertEquals($stmt[2]['redeemedBalance']['incomeTax'], 0.01);
        $this->assertEquals($stmt[2]['redeemedBalance']['iofTax'], 0.01);
        $this->assertEquals($stmt[2]['redeemedBalance']['id'], 'sdfgehhdf-jkre-thre-nghh-kuvsnjue633');
    }

    public function testGettingAccountInvestmentsYields()
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

        $feedData = $this->getFixture('account.investments_yields');
        $this->mock->append(new Response(200, [], json_encode($feedData)));;

        $this->assertEquals(
            $nu->getAccountInvestimentsYields(\Carbon\Carbon::now()),
            0.14
        );
    }

    public function testGettingAccountMoneyRequest()
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

        $feedData = $this->getFixture('account.feed');
        $mrData = $this->getFixture('account.money_request');
        $this->mock->append(new Response(200, [], json_encode($feedData)));
        $this->mock->append(new Response(200, [], json_encode($mrData)));

        $this->assertEquals(
            $nu->createMoneyRequest(200),
            'https://some.tld/path1/path2'
        );
    }

}