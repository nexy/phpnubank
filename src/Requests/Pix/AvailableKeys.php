<?php

namespace Nubank\Requests\Pix;

use Nubank\Requests\GraphqlRequest;
use Nubank\Services\MagicAttributes;

class AvailableKeys extends GraphqlRequest
{
    use MagicAttributes;

    public function query($url, $queryMap, $variables = [])
    {
        $response = parent::query($url, $queryMap);

        $savingsAccount = $response['data']['viewer']['savingsAccount'];
        //var_dump($savingsAccount['dict']['keys']); die();

        $this->attribs = [
            'keys' => $savingsAccount['dict']['keys'],
            'account_id' => $savingsAccount['id'],
        ];
    }
}