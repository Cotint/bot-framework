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
        'dbname' => 'tnl',
        'username' => 'root',
        'password' => 'Cotint'
    ],

    # dispatch
    'dispatcher' => [
        'message' => [
            '/start' => 'start',
            'درباره ما' => 'about',
            'تماس با ما' => 'contact',
            'فروشگاه ها' => 'shops',
            'دسته بندی ها' => 'categories',
            'اخبار' => 'news',
            '🔙 بازگشت به منو اصلی' => 'home',
            '❗️ راهنما' => 'help'
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