<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Activation Code Lifetime
    |--------------------------------------------------------------------------
    |
    | Here you may specify the number of hours that you wish the activation
    | code to be allowed to remain usable before it expires.
    |
    */
    'activation_code' => [
        'lifetime' => 24,
    ],

    // items
    'item'            => [
        'images' => [
            'object'  => '/images/object.jpg',
            'book'    => '/images/book.jpg',
            'money'   => '/images/money.jpg',
            'default' => '/images/object.jpg',
        ],
    ],
    'user'            => [
        'avatar' => [
            'default' => '/images/avatar.jpg',
        ],
    ],
];
