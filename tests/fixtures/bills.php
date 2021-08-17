<?php
return [
    'bills' => [
        [],
        [],
        [
            'href' => 'nuapp://bill/abcde-fghi-jklmn-opqrst-uvxz',
            'id' => 'abcde-fghi-jklmn-opqrst-uvxz',
            'state' => 'overdue',
            '_links' => [
                'self' => [
                    'href' => "https://mocked-proxy-url/api/bills/abcde-fghi-jklmn-opqrst-uvxz"
                ]
            ],
            'summary' => [
                "adjustments" => "-63.99106066",
                "close_date" => "2018-03-03",
                "due_date" => "2018-03-10",
                "effective_due_date" => "2018-03-12",
                "expenses" => "364.14",
                "fees" => "0",
                "interest" => 0,
                "interest_charge" => "0",
                "interest_rate" => "0.1375",
                "interest_reversal" => "0",
                "international_tax" => "0",
                "minimum_payment" => 8003,
                "open_date" => "2018-02-03",
                "paid" => 28515,
                "past_balance" => -1500,
                "payments" => "-960.47",
                "precise_minimum_payment" => "480.02544320601300",
                "precise_total_balance" => "285.152041645013",
                "previous_bill_balance" => "945.473102305013",
                "remaining_minimum_payment" => 0,
                "tax" => "0",
                "total_accrued" => "0",
                "total_balance" => 28515,
                "total_credits" => "-64.18",
                "total_cumulative" => 30015,
                "total_financed" => "0",
                "total_international" => "0",
                "total_national" => "364.32893934",
                "total_payments" => "-960.47",
            ]
        ]
    ]
];