<?php
return [
    'data' => [
        'viewer' => [
            'savingsAccount' => [
                'id' => '1234-12345-12345-123',
                'feed' => [
                    'statements' => [
                        0 => [
                            'id' => 'e409e495-4a16-4bad-9ddb-5c447c84fdcb',
                            '__typename' => 'TransferOutEvent',
                            'title' => 'Transferência enviada',
                            'detail' => 'Waldisney da Silva - R$ 4.496,90',
                            'postDate' => '2021-04-14',
                            'amount' => '4496.9',
                        ],
                        1 => [
                            'id' => 'acb9a16b-2a1c-40cc-a20b-0778a4503f12',
                            '__typename' => 'TransferInEvent',
                            'title' => 'Transferência recebida',
                            'detail' => 'R$ 1.483,80',
                            'postDate' => '2021-04-06',
                            'amount' => '1483.8',
                            'originAccount' => [
                                'name' => 'Waldisney da Silva'
                            ],
                        ],
                        22 => [
                            'id' => 'a9f96774-37f2-431e-9e6f-a081defacf25',
                            '__typename' => 'BarcodePaymentEvent',
                            'title' => 'Pagamento efetuado',
                            'detail' => 'CONFIDENCE CORRETORA DE CAMBIO S A',
                            'postDate' => '2020-12-08',
                            'amount' => '4245.10',
                        ],
                    ],
                ],
            ],
        ],
    ],
];