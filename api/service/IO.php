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
        if (isset($this->request->callback_query))
            $chatId = $this->request->callback_query->message->chat->id;
        elseif (isset($this->request->message))
            $chatId = $this->request->message->chat->id;


        $botToken = "352648200:AAH3AgcNeQSKDRkByDLuO2Ah4UsRktXY9o8";
        $web = "https://api.telegram.org/bot" . $botToken;
        $method = $this->response['method'];
        $replyMarkup = $this->response['reply_markup'];
        $encodedMarkup = json_encode($replyMarkup);

        if ($method == 'sendPhoto') {
            $photo = $this->response['photo'];
            $caption = $this->response['caption'];
            file_get_contents($web . '/' . $method . '?chat_id=' . $chatId . '&reply_markup=' . $encodedMarkup . '&photo=' . $photo . '&caption=' . $caption);
        }
        if ($method == 'sendMessage') {
            $text = $this->response['text'];
            if (isset($this->response['parse_mode'])) {
                $parsMode = $this->response['parse_mode'];
                file_get_contents($web . '/' . $method . '?chat_id=' . $chatId . '&reply_markup=' . $encodedMarkup . '&text=' . $text . '&parse_mode=' . $parsMode);
            } else {
                if (is_array($text)) {
                    if (isset($this->response['keyboard'])) {
                        $keyboard = $this->response['keyboard'];
                    }
                    for ($i = 0; $i < count($text); ++$i) {
                        if (isset($this->response['keyboard'])) {
                            $this->response['reply_markup'] = [
                                'inline_keyboard' => [[$keyboard[$i]]],
                            ];
                            $encodedMarkup = json_encode($this->response['reply_markup']);
                        }
                        file_get_contents($web . '/' . $method . '?chat_id=' . $chatId . '&reply_markup=' . $encodedMarkup . '&text=' . $text[$i]);
                    }
                } else {
                    file_get_contents($web . '/' . $method . '?chat_id=' . $chatId . '&reply_markup=' . $encodedMarkup . '&text=' . $text);
                }
            }
        }
        if ($method == 'sendVenue') {
            $lat = $this->response['latitude'];
            $lng = $this->response['longitude'];
            $address = $this->response['address'];
            $title = $this->response['title'];
            file_get_contents($web . '/' . $method . '?chat_id=' . $chatId . '&reply_markup=' . $encodedMarkup . '&latitude=' . $lat . '&longitude=' . $lng . '&address=' . $address . '&title=' . $title);
        }

//        header("Content-Type: application/json");
//        echo json_encode($this->response);
    }
}