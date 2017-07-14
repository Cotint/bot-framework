<?php
/**
 * Created by PhpStorm.
 * User: mohsenjalalian
 * Date: 6/9/17
 * Time: 7:46 PM
 */

namespace model;


class SupportModel extends MainModel
{
    public function addSupport(string $text, string $userId)
    {
        $this->mongo('support')->insertOne([
            'text' => $text,
            'user_id' => $userId
        ]);
    }
}