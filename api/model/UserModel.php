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

    public function createOrUpdate($request)
    {
        $pdo = $this->container->get('pdo');

        $user = $this->getUser($request->from->id);

        if (!isset($user) || $user == null) {

            $user['user_id']=$request->from->id;
            $user['first_name']=$request->from->first_name;
            $user['last_name']=$request->from->last_name;
            $user['username']=$request->from->username;
            $user['chat_id']=$request->chat->id;
            $user['created_at']=time();
            $user['updated_at']=time();

            $stmt = $pdo->prepare("INSERT INTO user_history (user_id, first_name, last_name,username,chat_id,created_at,updated_at) VALUES (:user_id, :first_name, :last_name,:username,:chat_id,:created_at,:updated_at)");
            $stmt->execute([
                'user_id' =>$user['user_id'],
                'first_name' =>$user['first_name'],
                'last_name' =>$user['last_name'],
                'username' =>$user['username'],
                'chat_id' =>$user['chat_id'],
                'created_at'=>$user['created_at'],
                'updated_at'=>$user['updated_at']
            ]);


        } else {

            $user['user_id']=$request->from->id;
            $user['first_name']=$request->from->first_name;
            $user['last_name']=$request->from->last_name;
            $user['username']=$request->from->username;
            $user['chat_id']=trim($request->chat->id);


            $last_state=0;

            $sql = "UPDATE user_history SET
            last_state = :last_state, 
            updated_at = :updated_at, 
            chat_id = :chat_id  
            WHERE user_id = :user_id";

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':last_state', $last_state);
            $stmt->bindParam(':updated_at', time());
            $stmt->bindParam(':chat_id', $user['chat_id']);
            $stmt->bindParam(':user_id', $user['user_id']);
            $stmt->execute();
        }

    }


    public function setState($chatId,$state)
    {
        $pdo = $this->container->get('pdo');


        $sql = "UPDATE user_history SET
            last_state = :last_state, 
            updated_at = :updated_at, 
            chat_id = :chat_id  
            WHERE chat_id = :chat_id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':last_state', $state);
        $stmt->bindParam(':updated_at', time());
        $stmt->bindParam(':chat_id',$chatId);

        return $stmt->execute();

    }

    public function setActivity($activity, $chatId)
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("UPDATE `user` SET `activity` = '" . $activity . "', `last_state` = '6', `update_at` = '" . time() . "' WHERE `user`.`chat_id` = " . $chatId);
        return $stmt->execute();
    }

    public function getUser($user_id)
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("SELECT * FROM `user_history` WHERE `user_id` = " . $user_id);
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

        $stmt = $pdo->prepare("SELECT last_state FROM `user_history` WHERE `chat_id` = " . $chatId);
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result[0]['last_state'];
    }
}