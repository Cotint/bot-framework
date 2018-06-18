<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2017-02-11
 * Time: 8:57 AM
 */

# set telegram bot commands
$command = [
    'message' => [
        '/start' => 'start',
        'درباره ما' => 'about',
        'تماس با ما' => 'contact',
        '🔙 بازگشت به منو اصلی' => 'home',
        '❗️ راهنما' => 'help',

        'deepLinkParameters' => [
            'game' => 'hubShowGame',
            'ads' => 'ads',
        ],
        'addContact' => 'addContact'
    ],
    'callback' => [
        'data'=>[
            'joinChanel' => 'joinChanel'
        ]
    ],
    'inline' => [
        '' => 'gameList',
    ]
];
