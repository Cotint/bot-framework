<?php
/**
 * Created by PhpStorm.
 * User: mohsenjalalian
 * Date: 6/9/17
 * Time: 5:11 PM
 */

namespace model;


class UserHistoryModel extends MainModel
{
    const STATUS_START = 0;
    const STATUS_BACK = 1;
    const STATUS_SUPPORT = 2;
    const STATUS_INVITE = 3;
    const STATUS_LIST_BRAND = 4;
    const STATUS_CONSULT = 5;
    const STATUS_SHOPKET_ADS = 6;
    const STATUS_ABOUT_BOT = 7;
    const STATUS_ABOUT = 8;
    const STATUS_CONTACT = 9;
    const STATUS_BUY_INFO = 10;
    const STATUS_SHIPMENT_ABOUT = 11;
    const STATUS_REFUND_ABOUT = 12;
    const STATUS_TERMS_CONDITIONS = 13;
    const STATUS_INSTAGRAM = 14;
    const STATUS_TELEGRAM = 15;
    const STATUS_SHOW_BRAND = 16;
    const STATUS_OTHER = 17;
    const STATUS_SHOW_CATEGORY = 18;
    const STATUS_LIST_PRODUCT = 19;

    public function addHistory(string $userId, string $state, string $text)
    {
        $this->mongo('user_history')->insertOne([
            'state' => $state,
            'user' => $userId,
            'text' => $text,
            'create_at' => time(),
        ]);
    }

    public function getLastState(string $userId, string $state)
    {
        $options = ['sort' => ['create_at' => -1]];
        $userHistory = $this->mongo('user_history')->findOne([
            'user' => $userId,
            'state' => $state,
        ], $options);

        return $userHistory;
    }
}