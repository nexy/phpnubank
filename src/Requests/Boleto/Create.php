<?php
namespace Nubank\Requests\Boleto;

use Nubank\Requests\GraphqlRequest;
use Nubank\Services\MagicAttributes;

class Create extends GraphqlRequest
{
    use MagicAttributes;

    public function getBarCode($url, $amount)
    {
        $response = parent::query($url, 'account_id');

        $this->attribs['customer_id'] = $response['data']['viewer']['id'];

        $payload = [
            "input" => [
                "amount" => $amount,
                "customerId" => $this->attribs['customer_id'],
            ]
        ];

        $boleto_res = $this->query($url, 'create_boleto', $payload);

        $this->attribs['barcode'] = $boleto_res['data']['createTransferInBoleto']['boleto']['readableBarcode'];
    }
}