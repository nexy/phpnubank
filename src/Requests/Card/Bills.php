<?php

namespace Nubank\Requests\Card;

use Nubank\Requests\BaseRequest;
use Nubank\Services\MagicAttributes;
use Nubank\Exceptions\NuMissingCreditCardException;

class Bills extends BaseRequest
{
    use MagicAttributes;

    public function get($url)
    {
        $response = parent::get($url);

        /**
         * @todo Essa construção é provisória, deve ser como está em Nubank.php:117 como comentário
         */
        if (!isset($response['bills'])) {
            throw new NuMissingCreditCardException('Missing url');
        }

        $this->attribs['bills'] = $response['bills'];
    }
}