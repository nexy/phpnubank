<?php
namespace NuTests;

use Nubank\Nubank;
use GuzzleHttp\Psr7\Response;

class CardServicesTest extends BaseTestCase
{
    public function testGetCardFeed()
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

        $feedData = $this->getFixture('feed');
        $this->mock->append(new Response(200, [], json_encode($feedData)));

        $feed = $nu->getCardFeed();

        $this->assertEquals($feed->as_of, '2017-09-09T06:50:22.323Z');
        $this->assertEquals($feed->customer_id, 'abcde-fghi-jklmn-opqrst-uvxz');
        $this->assertEquals(
            $feed->_links['updates']['href'],
            'https://prod-s0-webapp-proxy.nubank.com.br/api/proxy/updates_123'
        );
        $this->assertEquals(
            $feed->_links['next']['href'],
            'https://prod-s0-webapp-proxy.nubank.com.br/api/proxy/next_123'
        );

        $this->assertEquals($feed->events[0]['description'], 'Netflix.Com');
        $this->assertEquals($feed->events[0]['category'], 'transaction');
        $this->assertEquals($feed->events[0]['amount'], '3290');
        $this->assertEquals($feed->events[0]['time'], '2021-04-21T10:01:48Z');
        $this->assertEquals($feed->events[0]['title'], 'serviços');
        $this->assertEquals($feed->events[0]['id'], '43e713a0-07b7-43bb-9700-8d7ad2d5eee6');
        $this->assertEquals(
            $feed->events[0]['details']['subcategory'],
            'card_not_present'
        );
        $this->assertEquals(
            $feed->events[0]['href'],
            'nuapp://transaction/43e713a0-07b7-43bb-9700-8d7ad2d5eee6'
        );
        $this->assertEquals(
            $feed->events[0]['_links']['self']['href'],
            'https://prod-s0-facade.nubank.com.br/api/transactions/43e713a0-07b7-43bb-9700-8d7ad2d5eee6'
        );
    }

    public function testGetCardFeedStatements()
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

        $stmtData = $this->getFixture('feed');
        $this->mock->append(new Response(200, [], json_encode($stmtData)));

        $stmt = $nu->getCardStatements();

        $this->assertEquals($stmt[0]['description'], 'Netflix.Com');
        $this->assertEquals($stmt[0]['category'], 'transaction');
        $this->assertEquals($stmt[0]['amount'], '3290');
        $this->assertEquals($stmt[0]['time'], '2021-04-21T10:01:48Z');
        $this->assertEquals($stmt[0]['title'], 'serviços');
        $this->assertEquals($stmt[0]['id'], '43e713a0-07b7-43bb-9700-8d7ad2d5eee6');
        $this->assertEquals($stmt[0]['details']['subcategory'], 'card_not_present');
        $this->assertEquals($stmt[0]['href'], 'nuapp://transaction/43e713a0-07b7-43bb-9700-8d7ad2d5eee6');

        $this->assertEquals(
            $stmt[0]['_links']['self']['href'],
            'https://prod-s0-facade.nubank.com.br/api/transactions/43e713a0-07b7-43bb-9700-8d7ad2d5eee6'
        );
    }
}