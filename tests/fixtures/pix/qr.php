<?php

$payment_code = [
    '12464565442165BR.GOV.BCB.PIX42136542416542146542165.005802BR5920John ',
    'Doe6009SAO PAULOSf5ASF56sf654aA65sa4f6S56fs'
];
$brcode = implode("", $payment_code);

return [
    'data' => [
        'createPaymentRequest' => [
            'paymentRequest' => [
                'url' => 'https://nubank.com.br/pagar/tttttt/yyyyyyy',
                'brcode' => $brcode,
            ]
        ]
    ]
];