<?php
return [
  "access_token" =>"access_token_123",
  "token_type" =>"bearer",
  "_links" => [
    "revoke_token" => [
      "href" => "https://some-url/revoke_token"
    ],
    "revoke_all" => [
      "href" => "https://some-url/revoke_all"
    ],
    "account_emergency" => [
      "href" => "https://some-url/account_emergency"
    ],
    "bill_emergency" => [
      "href" => "https://some-url/bill_emergency"
    ]
  ],
  "refresh_token" => "string token",
  "refresh_before" => "2021-08-11T04:23:53Z"
];
