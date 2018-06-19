<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-11-24
 * Time: 10:55 AM
 */

namespace main;

use model\History;

class MessageMain extends MainMain
{



    /**
     *Start Method
     */
    public function start()
    {

        $data=[
            'chat_id'=>$this->request->message->chat->id,
            'username'=>$this->request->message->from->username,
            'first_name'=>$this->request->message->from->first_name,
            'last_name'=>$this->request->message->from->last_name,
        ];

//        History::saveUserInfo($data);

        $content = "سلام خوش آمدید ";
        $content .= "\n";
        $content .= "ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ";
        $content .= "\n";
        $content .= " متن آزمایشی";
        $content .= "\n";
        $content .= "\n";
        $content .= "\n";
        $content .= "✅  cotint.ir";
        $content .= "📞 021-22035976";
//        $content = json_encode($this->io->getParams());

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $data['chat_id'],
            'text' =>  urlencode($content),
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'inline_keyboard' => $this->keyboard->joinChannelButton(),
            ]
//            'reply_markup' => [
//                'keyboard' => $this->keyboard->welcomeButtons(),
//                'resize_keyboard' => true
//            ]
        ];

        $this->io->setResponse($result);
    }

    public function messageOther()
    {
        $result = [
            'method' => 'sendMessage',
            'chat_id' =>  $this->request->message->chat->id,
            'text' => json_encode($this->request),
            'reply_markup' => [
                'keyboard' => $this->keyboard->welcomeButtons(),
                'resize_keyboard' => true
            ]

        ];

        $this->io->setResponse($result);
    }


    public function errorCreateResult()
    {
        $result = [
            'method' => 'sendMessage',
            'chat_id' =>  $this->request->message->chat->id,
            'text' => 'خطا! دو.باره تلاش کنید',
            'reply_markup' => [
                'keyboard' => $this->keyboard->welcomeButtons(),
                'resize_keyboard' => true
            ]

        ];

        $this->io->setResponse($result);
    }
}