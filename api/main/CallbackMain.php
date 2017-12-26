<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-11-24
 * Time: 10:53 AM
 */

namespace main;

use Doctrine\DBAL\Driver\PDOConnection;
use Doctrine\DBAL\Driver\PDOStatement;
use model\UserModel;


class CallbackMain extends MainMain
{

    /**
     * @return UserModel
     */
    private function userModel(): UserModel
    {
        return $this->container->get('userModel');
    }



    public function subscribe()
    {


        $chatId = $this->request->message->chat->id;


        $content='';

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $chatId,
            'text' =>  urlencode($content),
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'inline_keyboard' => [
                        [
                            ['text' => "somme text"]
                        ]
                ],
                [
                    'keyboard' => $this->keyboard->button(),
                    'resize_keyboard' => true
                ]

            ]
        ];




        $this->io->setResponse($result);

    }

}