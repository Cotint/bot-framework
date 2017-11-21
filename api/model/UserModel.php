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

    public function setHistory($userId, $field, $count)
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("INSERT INTO `user` (`id`, `gender`, `height`, `weight`, `age`, `state`, `activity`, `last_state`, `chat_id`, `create_at`, `update_at`)" .
            " VALUES (NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, 'chat_id', CURRENT_TIMESTAMP, '" . time() . "');");
        $stmt->execute();
    }

    public function setBmi($userId, $chatId)
    {
        $pdo = $this->container->get('pdo');

        $user = $this->getUser($chatId);
        if (!isset($user) || $user == null) {
            $stmt = $pdo->prepare("INSERT INTO `user` (`id`, `gender`, `height`, `weight`, `age`, `state`, `activity`, `last_state`, `chat_id`, `create_at`, `update_at`, `user_id`,`bmi`)" .
                " VALUES (NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '" . $chatId . "', CURRENT_TIMESTAMP, '" . time() . "', '" . $userId . "', 1);");
            return $stmt->execute();
        } else {
            $stmt = $pdo->prepare("UPDATE `user` SET `last_state` = '7',`bmi` ='1', `update_at` = '" . time() . "' WHERE `user`.`chat_id` = " . $chatId);
            return $stmt->execute();
        }
    }

    public function setGender($userId, $gender, $chatId)
    {
        $pdo = $this->container->get('pdo');

        $user = $this->getUser($chatId);
        if (!isset($user) || $user == null) {
            $stmt = $pdo->prepare("INSERT INTO `user` (`id`, `gender`, `height`, `weight`, `age`, `state`, `activity`, `last_state`, `chat_id`, `create_at`, `update_at`, `user_id`)" .
                " VALUES (NULL, '" . $gender . "', NULL, NULL, NULL, NULL, NULL, 1, '" . $chatId . "', CURRENT_TIMESTAMP, '" . time() . "', '" . $userId . "');");
            return $stmt->execute();
        } else {
            $stmt = $pdo->prepare("UPDATE `user` SET `gender` = '" . $gender . "', `last_state` = '1', `bmi`='0', `update_at` = '" . time() . "' WHERE `user`.`chat_id` = " . $chatId);
            return $stmt->execute();
        }
    }

    public function setHeight($height, $chatId)
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("UPDATE `user` SET `height` = '" . $height . "', `last_state` = '2', `update_at` = '" . time() . "' WHERE `bmi`=0 AND `user`.`chat_id` = " . $chatId);
        return $stmt->execute();
    }

    public function setWeight($weight, $chatId)
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("UPDATE `user` SET `weight` = '" . $weight . "', `last_state` = '3', `update_at` = '" . time() . "' WHERE `bmi`=0 AND `user`.`chat_id` = " . $chatId);
        return $stmt->execute();
    }

    public function setHeightBmi($height, $chatId)
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("UPDATE `user` SET `height` = '" . $height . "', `last_state` = '8',`bmi`='1', `update_at` = '" . time() . "' WHERE `user`.`chat_id` = " . $chatId);
        return $stmt->execute();
        
    }

    public function setWeightBmi($weight, $chatId)
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("UPDATE `user` SET `weight` = '" . $weight . "', `last_state` = '9', `update_at` = '" . time() . "' WHERE `bmi`=1 AND `user`.`chat_id` = " . $chatId);
        return $stmt->execute();
    }

    public function setAge($age, $chatId)
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("UPDATE `user` SET `age` = '" . $age . "', `last_state` = '4', `update_at` = '" . time() . "' WHERE `bmi`=0 AND `user`.`chat_id` = " . $chatId);
        return $stmt->execute();
    }

    public function setState($state, $chatId)
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("UPDATE `user` SET `state` = '" . $state . "', `last_state` = '5', `update_at` = '" . time() . "' WHERE `bmi`=0 AND `user`.`chat_id` = " . $chatId);
        return $stmt->execute();
    }

    public function setActivity($activity, $chatId)
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("UPDATE `user` SET `activity` = '" . $activity . "', `last_state` = '6', `update_at` = '" . time() . "' WHERE `bmi`=0 AND `user`.`chat_id` = " . $chatId);
        return $stmt->execute();
    }

    public function getUser($chatId)
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("SELECT * FROM `user` WHERE `chat_id` = " . $chatId);
        $stmt->execute();

        $res = $stmt->fetchAll();
        return $res;
    }

    public function getUserBmi($chatId)
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("SELECT * FROM `user` WHERE `bmi` = 1 AND `chat_id` = " . $chatId);
        $stmt->execute();

        $res = $stmt->fetchAll();
        return $res;
    }

    public function getState($chatId)
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("SELECT last_state,bmi FROM `user` WHERE `chat_id` = " . $chatId);
        $stmt->execute();

        $res = $stmt->fetchAll();
        return $res;
    }
}