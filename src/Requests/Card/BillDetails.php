<?php

namespace Nubank\Requests\Card;

use Nubank\Requests\BaseRequest;
use Nubank\Services\MagicAttributes;

class BillDetails extends BaseRequest
{
    use MagicAttributes;

    public function get($url)
    {
        $response = parent::get($url);

        $this->attribs = array_merge($this->attribs, $response);
    }
}