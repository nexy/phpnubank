<?php
namespace NuTests;

use Nubank\Nubank;
use GuzzleHttp\Psr7\Response;
use Nubank\Exceptions\NuException;
use Nubank\Exceptions\NuMissingCreditCardException;

class BillsTest extends BaseTestCase
{
    public function testBillsHistory()
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

        $billsData = $this->getFixture('bills');
        $this->mock->append(new Response(200, [], json_encode($billsData)));

        $bills = $nu->getBills();

        $this->assertEquals(
            $bills->bills[2]['_links']['self']['href'],
            'https://mocked-proxy-url/api/bills/abcde-fghi-jklmn-opqrst-uvxz'
        );
        $this->assertEquals(
            $bills->bills[2]['href'],
            'nuapp://bill/abcde-fghi-jklmn-opqrst-uvxz'
        );
        $this->assertEquals($bills->bills[2]['id'], 'abcde-fghi-jklmn-opqrst-uvxz');
        $this->assertEquals($bills->bills[2]['state'], 'overdue');

        $this->assertEquals($bills->bills[2]['summary']["adjustments"], "-63.99106066");
        $this->assertEquals($bills->bills[2]['summary']["close_date"], "2018-03-03");
        $this->assertEquals($bills->bills[2]['summary']["due_date"], "2018-03-10");
        $this->assertEquals($bills->bills[2]['summary']["effective_due_date"], "2018-03-12");
        $this->assertEquals($bills->bills[2]['summary']["expenses"], "364.14");
        $this->assertEquals($bills->bills[2]['summary']["fees"], "0");
        $this->assertEquals($bills->bills[2]['summary']["interest"], 0);
        $this->assertEquals($bills->bills[2]['summary']["interest_charge"], "0");
        $this->assertEquals($bills->bills[2]['summary']["interest_rate"], "0.1375");
        $this->assertEquals($bills->bills[2]['summary']["interest_reversal"], "0");
        $this->assertEquals($bills->bills[2]['summary']["international_tax"], "0");
        $this->assertEquals($bills->bills[2]['summary']["minimum_payment"], 8003);
        $this->assertEquals($bills->bills[2]['summary']["open_date"], "2018-02-03");
        $this->assertEquals($bills->bills[2]['summary']["paid"], 28515);
        $this->assertEquals($bills->bills[2]['summary']["past_balance"], -1500);
        $this->assertEquals($bills->bills[2]['summary']["payments"], "-960.47");
        $this->assertEquals($bills->bills[2]['summary']["precise_minimum_payment"], "480.02544320601300");
        $this->assertEquals($bills->bills[2]['summary']["precise_total_balance"], "285.152041645013");
        $this->assertEquals($bills->bills[2]['summary']["previous_bill_balance"], "945.473102305013");
        $this->assertEquals($bills->bills[2]['summary']["remaining_minimum_payment"], 0);
        $this->assertEquals($bills->bills[2]['summary']["tax"], "0");
        $this->assertEquals($bills->bills[2]['summary']["total_accrued"], "0");
        $this->assertEquals($bills->bills[2]['summary']["total_balance"], 28515);
        $this->assertEquals($bills->bills[2]['summary']["total_credits"], "-64.18");
        $this->assertEquals($bills->bills[2]['summary']["total_cumulative"], 30015);
        $this->assertEquals($bills->bills[2]['summary']["total_financed"], "0");
        $this->assertEquals($bills->bills[2]['summary']["total_international"], "0");
        $this->assertEquals($bills->bills[2]['summary']["total_national"], "364.32893934");
        $this->assertEquals($bills->bills[2]['summary']["total_payments"], "-960.47");
    }

    public function testBillDetails()
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

        $billData = $this->getFixture('onebill');
        $this->mock->append(new Response(200, [], json_encode($billData)));

        $bill = $nu->getBillDetails('https://some-url/bill/123-1234-1234-123');
        
        $this->assertEquals(
            $bill->_links['barcode']['href'],
            'https://mocked-proxy-url/api/bills/abcde-fghi-jklmn-opqrst-uvxz/boleto/barcode'
        );
        $this->assertEquals(
            $bill->_links['boleto_email']['href'],
            'https://mocked-proxy-url/api/bills/abcde-fghi-jklmn-opqrst-uvxz/boleto/email'
        );
        $this->assertEquals(
            $bill->_links['invoice_email']['href'],
            'https://mocked-proxy-url/api/bills/abcde-fghi-jklmn-opqrst-uvxz/invoice/email'
        );
        $this->assertEquals(
            $bill->_links['self']['href'],
            'https://mocked-proxy-url/api/bills/abcde-fghi-jklmn-opqrst'
        );
        $this->assertEquals($bill->account_id, 'abcde-fghi-jklmn-opqrst-uvxz');
        $this->assertEquals($bill->auto_debit_failed, False);
        $this->assertEquals($bill->barcode, '');
        $this->assertEquals($bill->id, 'abcde-fghi-jklmn-opqrst-uvxz');
        $this->assertEquals($bill->line_items[0]['amount'], 2390);
        $this->assertEquals($bill->line_items[0]['category'], 'Eletronicos');
        $this->assertEquals($bill->line_items[0]['charges'], 1);
        $this->assertEquals($bill->line_items[0]['href'], 'nuapp://transaction/abcde-fghi-jklmn-opqrst-uvxz');
        $this->assertEquals($bill->line_items[0]['id'], 'abcde-fghi-jklmn-opqrst-uvxz');
        $this->assertEquals($bill->line_items[0]['index'], 0);
        $this->assertEquals($bill->line_items[0]['post_date'], '2015-09-09');
        $this->assertEquals($bill->line_items[0]['title'], 'Mercadopago Mlivre');
        $this->assertEquals($bill->linha_digitavel, '');
        $this->assertEquals($bill->payment_method, 'boleto');
        $this->assertEquals($bill->state, 'overdue');
        $this->assertEquals($bill->status, 'paid');
        $this->assertEquals($bill->summary['adjustments'], '0');
        $this->assertEquals($bill->summary['close_date'], '2015-09-25');
        $this->assertEquals($bill->summary['due_date'], '2015-10-10');
        $this->assertEquals($bill->summary['effective_due_date'], '2015-10-13');
        $this->assertEquals($bill->summary['expenses'], '78.8000');
        $this->assertEquals($bill->summary['fees'], '0');
        $this->assertEquals($bill->summary['interest'], 0);
        $this->assertEquals($bill->summary['interest_charge'], '0');
        $this->assertEquals($bill->summary['interest_rate'], '0.0775');
        $this->assertEquals($bill->summary['interest_reversal'], '0');
        $this->assertEquals($bill->summary['international_tax'], '0');
        $this->assertEquals($bill->summary['late_fee'], '0.02');
        $this->assertEquals($bill->summary['late_interest_rate'], '0.0875');
        $this->assertEquals($bill->summary['minimum_payment'], 7005);
        $this->assertEquals($bill->summary['open_date'], '2015-07-23');
        $this->assertEquals($bill->summary['paid'], 7880);
        $this->assertEquals($bill->summary['past_balance'], 0);
        $this->assertEquals($bill->summary['payments'], '0');
        $this->assertEquals($bill->summary['precise_minimum_payment'], '70.054500');
        $this->assertEquals($bill->summary['precise_total_balance'], '78.8000');
        $this->assertEquals($bill->summary['previous_bill_balance'], '0');
        $this->assertEquals($bill->summary['tax'], '0');
        $this->assertEquals($bill->summary['total_accrued'], '0');
        $this->assertEquals($bill->summary['total_balance'], 7880);
        $this->assertEquals($bill->summary['total_credits'], '0');
        $this->assertEquals($bill->summary['total_cumulative'], 7880);
        $this->assertEquals($bill->summary['total_financed'], '0');
        $this->assertEquals($bill->summary['total_international'], '0');
        $this->assertEquals($bill->summary['total_national'], '78.8000');
        $this->assertEquals($bill->summary['total_payments'], '0');
    }

    public function testBillMissingCreditCard()
    {
        $this->expectException(NuMissingCreditCardException::class);

        $proxyData = $this->getFixture('proxy');
        $accessToken = $this->getFixture('loggedin');
        $liftData = $this->getFixture('lift');
        
        //unset($liftData['_links']['bills_summary']);
        //var_dump($liftData['_links']); die();

        $client = $this->configureMock([
            new Response(200, [], json_encode($proxyData)),
            new Response(200, [], json_encode($proxyData)),
            new Response(200, [], json_encode($accessToken)),
            new Response(200, [], json_encode($liftData)),
        ]);

        $nu = new Nubank($client);
        $nu->authenticateWithQrCode('12345678912', 'hunter12', 'some-uuid');

        //var_dump($nu); die();

        $this->mock->reset();

        $this->mock->append(new Response(200, [], 'Missing card'));

        $nu->getBills();
    }
}
