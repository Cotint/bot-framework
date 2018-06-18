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
use http\Exception;

class telegramCommands
{
    private $tokens = [];
    private $answer;

    /**
     * telegramCommands constructor.
     */
    public function __construct()
    {
        $argv = $_SERVER['argv'];

        // strip the application name
        array_shift($argv);

        $this->tokens = $argv;

        $this->checkCommand();
    }

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

    private function help($message = null,$type = null)
    {
        $this->setAnswer(
            ($message ? "\n ".$type." : " . $message . "\n " : "\n ") .
            " telegram commands help: \n\n" .
            "   setWebHook <" . 'token' . "> <" . 'url' . ">   sets given webHook on the given bot token \n" .
            "   getWebHook <" . 'token' . ">         gets webHook of the given bot token \n" .
            "   deleteWebHook <" . 'token' . ">      deletes webHook of the given bot token \n\n");
        return 0;
    }

    private function checkCommand()
    {
        $this->tokens[0] ? $this->switchCommands() : $this->help();
    }

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

    public function aa()
    {
        echo "Are you sure you want to do this?  Type 'yes' to continue: ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        if (trim($line) != 'yes') {
            echo "ABORTING!\n";
            exit;
        }
        echo "\n";
        echo "Thank you, continuing...\n";
    }
}