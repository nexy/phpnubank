<?php

namespace Nubank\Requests\Card;

use Nubank\Requests\BaseRequest;
use Nubank\Services\MagicAttributes;

class Feed extends BaseRequest
{
    use MagicAttributes;

    public function get($url)
    {
        $response = parent::get($url);

        $this->attribs['as_of'] = $response['feed']['as_of'];
        $this->attribs['customer_id'] = $response['feed']['customer_id'];
        $this->attribs['_links'] = $response['feed']['_links'];

        $this->attribs['events'] = $response['events'];
    }
}