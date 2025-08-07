<?php

return [
    'msgway' => [
        'key' => env('MSGWAY_API_KEY'),
        'sendUrl' => 'https://api.msgway.com/otp/send',
        'userOTP' => [
            'verifyUrl' => 'https://api.msgway.com/otp/verify',
            'sendRetry' => 4,
            'sendRetryTime' => 30,
            'cacheExpiry' => 300,
        ],
        'templates' => [
            'IVR' => 2,
            'webOTP' => 2,
            'register' => [
                'fa' => 3,
                'en' => 3,
            ],
            'setPassword' => [
                'fa' => 3,
                'en' => 3,
            ],
            'changeMobileValidateCurrentMobile' => [
                'fa' => 3,
                'en' => 3,
            ],
            'changeMobileValidateNewMobile' => [
                'fa' => 3,
                'en' => 3,
            ],
        ],
    ],
];
