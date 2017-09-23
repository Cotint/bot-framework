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

    # mongo
    'mongodb' => [
        'host' => 'mongodb://localhost:27017',
            'dbname' => 'cotintpro',
    ],

    'pdo' => [
        'servername' => 'localhost',
        'dbname' => 'telegram_panel1',
        'username' => 'root',
        'password' => '123'
    ],

    # dispatch
    'dispatcher' => [
        'message' => [
            '/start' => 'start',
            'بازگشت به منوی اصلی' => 'back',
            'بازگشت به مرحله قبل' => 'backPrevious',
            'لیست برند ها' => 'listBrand',
            'انتقادات و پیشنهادات' => 'support',
            'معرفی به دوستان' => 'invite',
            'مشاوره رایگان' => 'consult',
            'تبلیغ شاپکت' => 'shopketAds',
            'معرفی ربات' => 'aboutBot',
            'درباره ما' => 'about',
            'ارتباط با ما' => 'contact',
            'نحوه خرید' => 'buyInfo',
            'شرایط ارسال' => 'shipmentAbout',
            'شرایط استرداد' => 'refundAbout',
            'قوانین و مقررات' => 'termsConditions',
            'اینستاگرام' => 'instagram',
            'تلگرام' => 'telegram',
            'ثبت نظر' => 'setComment',
            'ثبت امتیاز' => 'setStar',
            'ارزان ترین ها' => 'cheapest',
            'پرفروش ترین ها' => 'bestSelling',
            'تازه ترین ها' => 'newest',
            'محبوب ترین ها' => 'mostPopular',
            'مشاهده سبد خرید' => 'showCart',
            'اضافه کردن به سبد خرید' => 'addToCart',
            'حذف محصول' => 'selectProductForDelete',
            'افزودن محصول' => 'addAnotherProduct',
            'ثبت نهایی' => 'finalSubmit',
            'گام قبل' => 'previousStep',             
        ],
        'callback' => [
            'مشاهده محصول' => 'getProduct',
            'حذف محصول' => 'deleteProduct',
        ],
        'inline' => [
//            '' => 'gameList',
//            'invite' => 'invite'
        ]
    ],
];