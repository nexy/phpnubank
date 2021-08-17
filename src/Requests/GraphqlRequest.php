<?php

namespace Nubank\Requests;

class GraphqlRequest extends BaseRequest
{
    public function query($url, $queryMap, $variables = [])
    {
        $data = file_get_contents( realpath(__DIR__ . "/GraphqlQueries/{$queryMap}.gql") );
        
        /*
        $data = preg_replace("/^(query [0-9a-zA-Z_]+ {)/", "query {", $data);
        $data = preg_replace("/[ \t\n]+/", " ", $data);
        */

        //$variables = preg_replace("/[ \t\n]+/", " ", $variables);

        $payload = [ 'query' => $data ];
        if (is_array($variables) && count($variables) > 0) {
            $payload['variables'] = $variables;
        }

        return parent::post($url, $payload);
    }
}