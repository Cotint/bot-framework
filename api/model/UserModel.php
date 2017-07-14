<?php
/**
 * Created by PhpStorm.
 * User: mohsenjalalian
 * Date: 6/9/17
 * Time: 4:04 PM
 */

namespace model;


class UserModel extends MainModel
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

    /**
     * @param string $userId
     * @param string $firstName
     * @param $lastName
     */
    public function register(string $userId, string $firstName, $lastName)
    {
        $lastName = $lastName ?? '';
        $userExist = $this->findUserById($userId);
        if (is_null($userExist))
        {
            $username = $this->generateUniqueUsername($firstName);
            $this->mongo('user')->insertOne([
                '_id' => $userId,
                'profile'=> [
                    'first_name' => $firstName,
                    'last_Name' => $lastName,
                    'username' => $username,
                ],
                'laststate' => UserModel::STATUS_START
            ]);
        }
    }

    /**
     * @param string $userId
     * @return array|null|object
     */
    public function findUserById(string $userId)
    {
        return $this->mongo('user')->findOne(['_id' => $userId]);
    }

    /**
     * @param string $firstName
     * @return string
     */
    private function generateUniqueUsername(string $firstName): string
    {
        $flag = true;
        $username = $firstName;
        while ($flag)
        {
            $userDocument = $this->findByUsername($username);
            if (is_null($userDocument))
                $flag = false;
            else
                $username = $firstName . mt_rand(1000, 9999);
        }
        return $username;
    }

    /**
     * @param string $userName
     * @return array|null|object
     */
    public function findByUsername(string $userName)
    {
        return $this->mongo('user')->findOne(['profile.username' => $userName]);
    }

    public function setState(string $userId, $state)
    {
        $updateResult = $this->mongo('user')->updateOne(
            ['_id' => $userId],
            ['$set' => ['laststate' => $state]]
        );
    }

    public function getState(string $userId)
    {
        $user = $this->mongo('user')->findOne(['_id' => $userId]);

        return $user->laststate;
    }
}