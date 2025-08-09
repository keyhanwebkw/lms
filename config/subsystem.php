<?php

return [
    'appName' => env('APP_NAME', 'Admin panel'),
    'appTitle' => env('APP_TITLE', 'Admin panel'),
    'smsService' => env('SMS_SERVICE', 'msgway'),
    'adminAuthModel' => \Keyhanweb\Subsystem\Models\Manager::class,
    'availableItemsPerPage' => [
        25,
        50,
        100,
        200,
    ],

    'storage' => [
        'path' => "uploads/",
        'pathTemporaryUploads' => "uploads/tmp/",
        'fileUploaderCustomDirectory' => "fileUploader",
        'tinymceCustomDirectory' => "tinymce",
        'audio' => [
            'validate' => [
                'max:10240', //10MB
                'mimes:mp3',
            ],
        ],
        'image' => [
            'convertToWebp' => false,
            'originalConversionQuality' => 90,
            'thumbnailConversionQuality' => 70,
            'thumbnail' => [
                'width' => 300,
                'height' => 300,
                'pathThumbnail' => 'thumbnails/',
            ],
            'validate' => [
                'max:10240', //10MB
                'mimes:png,jpg,jpeg',
            ],
        ],
        'excel' => [
            'validate' => [
                'max:51200', //50MB
                'mimes:xls,xlsx',
            ],
        ],
        'pdf' => [
            'validate' => [
                'max:51200', //50MB
                'mimes:pdf',
            ],
        ],
        'video' => [
            'validate' => [
                'max:1048576', // 1GB
                'mimes:mp4,avi,mpeg,mov',
            ],
        ],
    ],

    'defaultRoles' => [
        'user' => [
            'name' => 'کاربر عادی',
            'description' => 'Default role'
        ],
        'author' => [
            'name' => 'نویسنده',
            'description' => 'Default role'
        ],
        'teacher' => [
            'name' => 'معلم',
            'description' => 'Default role'
        ],

    ],
];
