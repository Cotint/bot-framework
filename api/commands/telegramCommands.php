<?php
/**
 * Created by PhpStorm.
 * User: user1
 * Date: 6/18/18
 * Time: 10:32 AM
 */

namespace commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class telegramCommands
{
    /**
     * token variables
     *
     * @var array
     */
    private $tokens = [];

    /**
     * @var array
     */
    private $answer;

    /**
     * telegramCommands constructor.
     */
    public function __construct()
    {
        $argv = $_SERVER['argv'];

        array_shift($argv);

        $this->tokens = $argv;

        $this->checkCommand();
    }

    /**
     * @return int
     * @internal param $commandType
     */
    private function switchCommands()
    {
        $commandType = $this->tokens[0];
        array_shift($this->tokens);

        switch ($commandType) {
            case 'setWebHook':
                if (!isset($this->tokens[0])) {
                    $this->help('url is required','error');
                }
                if (!isset($this->tokens[1])) {
                    return $this->help('token is required','error');
                }

                $this->setWebHook();
                break;
            case 'getWebHook':
                if (!$this->tokens) {
                    return $this->help('token is required','error');
                }

                $this->getWebHook();
                break;
            case 'deleteWebHook':
                if (!$this->tokens) {
                    return $this->help('token is required','error');
                }

                $this->deleteWebHook();
                break;
            default:
                $this->help();
                break;
        }
    }

    /**
     * @param null $message
     * @param null $type
     * @return int
     */
    private function help($message = null, $type = null)
    {
        $this->setAnswer(
            ($message ? "\n ".$type." : " . $message . "\n " : "\n ") .
            " telegram commands help: \n\n" .
            "   setWebHook <" . 'url' . "> <" . 'token' . ">   sets given webHook on the given bot token \n" .
            "   getWebHook <" . 'token' . ">         gets webHook of the given bot token \n" .
            "   deleteWebHook <" . 'token' . ">      deletes webHook of the given bot token \n\n");
        return 0;
    }

    /**
     * checks if there is any commands
     */
    private function checkCommand()
    {
        $this->tokens[0] ? $this->switchCommands() : $this->help();
    }

    /**
     * set web hook of given bot token
     *
     * @return void
     * @internal param $url
     * @internal param $token
     * @internal param $client
     * @internal param $response
     * @internal param $content
     */
    private function setWebHook()
    {
        $url = $this->tokens[0];
        $token = $this->tokens[1];
        $client = new Client();

        try {
            $response = $client->get(
                'https://api.telegram.org/bot' . $token . '/setWebHook?url='.$url
            );
        } catch (ClientException $ex) {
            $this->setAnswer('wrong token or url');
            return;
        }

        $content = ($response->getBody()->getContents());
        $content = json_decode($content);
        $this->setAnswer($content->description
            ? $content->description
            : 'there is no web hook set');
        return;
    }

    /**
     * get web hook of given bot token
     *
     * @return void
     * @internal param $token
     * @internal param $client
     * @internal param $response
     * @internal param $content
     */
    private function getWebHook()
    {
        $token = $this->tokens[0];
        $client = new Client();

        try {
            $response = $client->get(
                'https://api.telegram.org/bot' . $token . '/getWebHookInfo'
            );
        } catch (ClientException $ex) {
            $this->setAnswer('wrong token');
            return;
        }

        $content = ($response->getBody()->getContents());
        $content = json_decode($content);
        $this->setAnswer($content->result->url
            ? 'webHook is : ' . $content->result->url
            : 'there is no web hook set');
        return;
    }

    /**
     * delete web hook of given bot token
     *
     * @return void
     * @internal param $token
     * @internal param $client
     * @internal param $response
     * @internal param $content
     */
    private function deleteWebHook()
    {
        $token = $this->tokens[0];
        $client = new Client();

        try {
            $response = $client->get(
                'https://api.telegram.org/bot' . $token . '/deleteWebHook'
            );
        } catch (ClientException $ex) {
            $this->setAnswer('wrong token');
            return;
        }

        $content = ($response->getBody()->getContents());
        $content = json_decode($content);
        $this->setAnswer($content->description
            ? $content->description
            : 'there is no web hook set');
        return;
    }

    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param mixed $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * @param mixed $tokens
     */
    public function setTokens($tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * @return mixed
     */
    public function getTokens()
    {
        return $this->tokens;
    }
}