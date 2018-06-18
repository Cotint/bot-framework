<?php

/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-10-29
 * Time: 12:00 PM
 */
namespace service;

use GuzzleHttp\Client;


class IO
{
    public $request;
    public $response = [];

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
        $this->response[] = $response;
    }

    public function sendResponse()
    {

//        header("Content-Type: application/json");
//        echo json_encode($this->response);

       foreach ($this->response as $res){
           $this->normalizeResponse($res);
       }
    }

    private function normalizeResponse($response)
    {
        if (isset($this->request->callback_query))
            $chatId = $this->request->callback_query->message->chat->id;
        elseif (isset($this->request->message))
            $chatId = $this->request->message->chat->id;


        $botToken = "619940447:AAFNLsHPbdAfrzHAkA5oDqaRsRpkuvIZTkI";
        $web = "https://api.telegram.org/bot" . $botToken;

        $apiURL = 'https://api.telegram.org/bot' . $botToken . '/';

        $client = new Client( array( 'base_uri' => $apiURL ) );


        $method = $response['method'];
        $replyMarkup = $response['reply_markup'];
        $encodedMarkup = json_encode($replyMarkup);

        if ($method == 'sendPhoto') {
            $photo = $response['photo'];
            $caption = $response['caption'];
            file_get_contents($web . '/' . $method . '?chat_id=' . $chatId . '&reply_markup=' . $encodedMarkup . '&photo=' . $photo . '&caption=' . $caption);
        }

        if ($method == 'sendMessage') {
            $text = $response['text'];

            if (isset($response['parse_mode'])) {
                $parsMode = $response['parse_mode'];

                $url = $web . '/' . $method;



                file_get_contents($web . '/' . $method . '?chat_id=' . $chatId . '&reply_markup=' . $encodedMarkup . '&text=' . $text . '&parse_mode=' . $parsMode);

            } else {

                if (is_array($text)) {
                    if (isset($response['keyboard'])) {
                        $keyboard = $response['keyboard'];
                    }
                    for ($i = 0; $i < count($text); ++$i) {
                        if (isset($response['keyboard'])) {
                            $response['reply_markup'] = [
                                'inline_keyboard' => [[$keyboard[$i]]],
                            ];
                            $encodedMarkup = json_encode($response['reply_markup']);
                        }
                        file_get_contents($web . '/' . $method . '?chat_id=' . $chatId . '&reply_markup=' . $encodedMarkup . '&text=' . $text[$i]);
                    }
                } else {
                    file_get_contents($web . '/' . $method . '?chat_id=' . $chatId . '&reply_markup=' . $encodedMarkup . '&text=' . $text);
                }
            }
        }

        if ($method == 'sendVenue') {
            $lat = $response['latitude'];
            $lng = $response['longitude'];
            $address = $response['address'];
            $title = $response['title'];
            file_get_contents($web . '/' . $method . '?chat_id=' . $chatId . '&reply_markup=' . $encodedMarkup . '&latitude=' . $lat . '&longitude=' . $lng . '&address=' . $address . '&title=' . $title);
        }

//        header("Content-Type: application/json");
//        echo json_encode($response);
    }
}