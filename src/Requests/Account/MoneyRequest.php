<?php

namespace Nubank\Requests\Account;

use Nubank\Requests\GraphqlRequest;
use Nubank\Services\MagicAttributes;

class MoneyRequest extends GraphqlRequest
{
    use MagicAttributes;

    public function query($url, $queryMap, $variables = [])
    {
        $this->attribs = parent::query($url, $queryMap, $variables);
    }
}
