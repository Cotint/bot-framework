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
        'dbname' => 'barangbot',
        'username' => 'root',
        'password' => ''
    ],

    # dispatch
    'dispatcher' => [
        'message' => [
            '/start' => 'start',
            '👩‍⚖️ زن' => 'gender',
            'وضعیت' => 'state',
            'فعالیت روزانه' => 'activity',
            '♨ بارنگ فود' => 'barang',
            '⚖️ محاسبه کالری مورد نیاز' => 'calorie',
            '💹 محاسبه BMI' => 'bmi',
            '👨‍⚖️ مرد' => 'gender',
            'عادی' => 'state',
            'باردار' => 'state',
            'شیرده' => 'state',
            'بدون فعالیت' => 'activity',
            'کم فعالیت' => 'activity',
            'فعالیت متوسط' => 'activity',
            'فعالیت زیاد' => 'activity',
            'فعالیت خیلی زیاد' => 'activity',
            '🔙 بازگشت به صفحه اصلی' => 'backHome',
            '❗️ راهنما' => 'help'
        ],
        'callback' => [
        ],
        'inline' => [
        ]
    ],
];