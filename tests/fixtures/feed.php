<?php
return [
  'feed' => [
    'as_of' => '2017-09-09T06:50:22.323Z',
    'customer_id' => 'abcde-fghi-jklmn-opqrst-uvxz',
    '_links' => [
      'updates' => [
        'href' => 'https://prod-s0-webapp-proxy.nubank.com.br/api/proxy/updates_123'
      ],
      'next' => [
        'href' => 'https://prod-s0-webapp-proxy.nubank.com.br/api/proxy/next_123'
      ]
    ],
  ],
  'events' => [
    [
      'id' => '43e713a0-07b7-43bb-9700-8d7ad2d5eee6',
      'description' => 'Netflix.Com',
      'category' => 'transaction',
      'amount' => 3290,
      'time' => '2021-04-21T10:01:48Z',
      'title' => 'serviços',
      'details' => ['subcategory' => 'card_not_present'],
      'href' => 'nuapp://transaction/43e713a0-07b7-43bb-9700-8d7ad2d5eee6',
      '_links' => [
        'self' => [
          'href' => 'https://prod-s0-facade.nubank.com.br/api/transactions/43e713a0-07b7-43bb-9700-8d7ad2d5eee6'
        ]
      ]
    ]
  ]
];