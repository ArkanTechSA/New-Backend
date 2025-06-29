<?php

return [
    'noreply' => [
        'driver' => 'smtp',
        'host' => 'smtp.hostinger.com',
        'port' => 465,
        'username' => 'no-replay@ymtaz.sa',
        'password' => '7uhb6Ygv@@',
        'encryption' => 'ssl',
        'from' => [
            'address' => 'no-replay@ymtaz.sa',
            'name' => 'Ymtaz',
        ],
    ],

    'newsletter' => [
        'driver' => 'smtp',
        'host' => 'smtp.hostinger.com',
        'port' => 465,
        'username' => 'newsletter@ymtaz.sa',
        'password' => '7uhb6Ygv@@',
        'encryption' => 'ssl',
        'from' => [
            'address' => 'newsletter@ymtaz.sa',
            'name' => 'Ymtaz',
        ],
    ],

];
