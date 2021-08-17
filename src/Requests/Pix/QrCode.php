<?php

namespace Nubank\Requests\Pix;

use Nubank\Requests\GraphqlRequest;
use Nubank\Services\MagicAttributes;

use chillerlan\QRCode\QRCode as QrCodeGenerator;

class QrCode extends GraphqlRequest
{
    use MagicAttributes;

    public function getQrData($url, $accountId, $amount, $pixKey)
    {
        $payload = [
            'createPaymentRequestInput' => [
                'amount' => $amount,
                'pixAlias' => $pixKey['value'],
                "savingsAccountId" => $accountId
            ]
        ];

        $response = parent::query($url, 'create_pix_money_request', $payload);

        $data = $response['data']['createPaymentRequest']['paymentRequest'];

        $qr = new QrCodeGenerator();

        $this->attribs = [
            'payment_url' => $data['url'],
            'payment_code' => $data['brcode'],
            'qr_code' => $qr->render($data['brcode']),
            'qr' => $qr,
        ];
    }
}