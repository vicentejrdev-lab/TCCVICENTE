<?php

return [
    'enabled' => filter_var(env('DEMO_MODE', true), FILTER_VALIDATE_BOOL),

    'reference' => env('DEMO_REFERENCE_PERIOD', '2026-11'),

    'master' => [
        'name' => env('DEMO_MASTER_NAME', 'Master Demonstração'),
        'email' => env('DEMO_MASTER_EMAIL', 'master@coreone.invalid'),
        'document' => env('DEMO_MASTER_DOCUMENT', '00000000000'),
        'password' => env('DEMO_MASTER_PASSWORD', 'TCC-CoreOne-2026#'),
    ],

    'expected_counts' => [
        'consultants' => 20,
        'productions' => 170,
        'associates' => 300,
        'unpaid_bills' => 45,
        'payment_lot_items' => 20,
    ],
];
