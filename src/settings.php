<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-10-29
 * Time: 8:58 AM
 */

$setting = [
    # monolog
    'logger' => [
        'name' => 'bot',
        'path' => '../log/debug.log',
    ],


    'pdo' => [
        'servername' => 'localhost',
        'dbname' => 'devtnl_tnl',
        'username' => 'devtnl_tnl',
        'password' => 'bo5hf9NO'
    ],

    # dispatch
    'dispatcher' => [
        'message' => [
            '/start' => 'start',
            '❓ درباره ما' => 'about',
            '📞 تماس با ما' => 'contact',
            '🏪 فروشگاه ها' => 'shops',
            '🛍 دسته بندی ها' => 'categories',
            '📝 اخبار' => 'news',
            '🔙 بازگشت به منو اصلی' => 'home',
        ],
        'callback' => [
            'data' => [
                'getShops' => 'getShops',
            ]
        ],
        'inline' => [
        ]
    ],
];
