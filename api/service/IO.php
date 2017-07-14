<?php

/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-10-29
 * Time: 12:00 PM
 */

namespace service;


class IO
{
    public $request;
    public $response;

    /**
     * IO constructor.
     */
    public function __construct()
    {
        $this->setRequest();
        $this->setResponse(null);
    }

    /**
     * @return object
     */
    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest()
    {
        $request = file_get_contents("php://input");
        $this->request = json_decode($request);
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param $response array
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    public function sendResponse()
    {
        $chatId = $this->request->message->chat->id;
        $botToken = "369113728:AAFpG5RL-WoieKWDIdgbUQMr03RbTsUNWn0";
        $web = "https://api.telegram.org/bot".$botToken;
        $method = $this->response['method'];
        $replyMarkup = $this->response['reply_markup'];
        $encodedMarkup = json_encode($replyMarkup);
        if ($method == 'sendPhoto') {
            $photo = $this->response['photo'];
            $caption = $this->response['caption'];
            file_get_contents($web.'/'.$method.'?chat_id='.$chatId.'&reply_markup='.$encodedMarkup.'&photo='.$photo.'&caption='.$caption);
        }
        if ($method == 'sendMessage') {
            $text = $this->response['text'];
            if (isset($this->response['parse_mode'])) {
                $parsMode = $this->response['parse_mode'];
                file_get_contents($web.'/'.$method.'?chat_id='.$chatId.'&reply_markup='.$encodedMarkup.'&text='.$text.'&parse_mode='.$parsMode);
            } else {
                file_get_contents($web.'/'.$method.'?chat_id='.$chatId.'&reply_markup='.$encodedMarkup.'&text='.$text);
            }
        }
        if ($method == 'sendVenue') {
            $lat = $this->response['latitude'];
            $lng = $this->response['longitude'];
            $address = $this->response['address'];
            $title = $this->response['title'];
            file_get_contents($web.'/'.$method.'?chat_id='.$chatId.'&reply_markup='.$encodedMarkup.'&latitude='.$lat.'&longitude='.$lng.'&address='.$address.'&title='.$title);
        }

//        header("Content-Type: application/json");
//        echo json_encode($this->response);
    }
}