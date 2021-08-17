<?php

namespace Nubank\Requests\Account;

use Nubank\Requests\GraphqlRequest;
use Nubank\Services\MagicAttributes;

class InvestmentsYields extends GraphqlRequest
{
    use MagicAttributes;

    public function query($url, $queryMap, $variables = [])
    {
        $response = parent::query($url, $queryMap, $variables);
        //dd($response);

        $this->attribs['label'] = $response['data']['viewer']['productFeatures']['savings']['screens']['detailedBalance']['monthBalanceSection']['yieldSection']['semantics']['label'];
    }
}
