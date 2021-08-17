<?php

if (!function_exists('parseFloat')) {
    function parseFloat($value)
    {
        $matches = [];
        preg_match_all("/[0-9]+/", $value, $matches, PREG_PATTERN_ORDER);

        $cents = array_pop($matches[0]);

        return (float)sprintf("%d.%d", implode("", $matches[0]), $cents);
    }
}

if (!function_exists('pixTransaction')) {
    function pixTransaction($transaction)
    {
        if ( $transaction['__typename'] !== 'GenericFeedEvent' ) {
            return $transaction;
        }

        $pixTransactionMap = \Nubank\Utils\PixConstants::pixTransactionMap();

        if ( in_array($pixTransactionMap, $transaction['title']) ) {
            $transaction['__typename'] = $pixTransactionMap[$transaction['title']];
            $transaction['amount'] = parseFloat($transaction['detail']);
        }

        return $transaction;
    }
}

if (!function_exists('dd')) {
    function dd($arg)
    {
        var_dump($arg); die();
    }
}
