<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-10-30
 * Time: 9:33 AM
 */

use League\Container\Container;

/** @var $setting array */

/** @var service\IO $io */
$io = $container->get('io');
$request = $io->getRequest();

# load commands
require 'commands.php';
/** @var array $command */

# log
/** @var \Monolog\Logger $log */
$log = $container->get('logger');
$log->addInfo(json_encode((array)$request));

$dispatch = new stdClass();
switch ($request) {
    case isset($request->message):
        $dispatch = message($request, $dispatch, $container, $command, $io);
        break;
    case isset($request->callback_query):
        $dispatch = callback($request, $dispatch, $container, $command);
        break;
    case isset($request->inline_query):
        $dispatch = inline($request, $dispatch, $container, $command);
        break;
}

/**
 * @param stdClass $request
 * @param stdClass $dispatch
 * @param Container $container
 * @param array $command
 * @param $io
 * @return stdClass
 * @internal param array $setting
 */
function message(stdClass $request, stdClass $dispatch, Container $container, array $command, $io): stdClass
{
    $text = $request->message->text;
    $message = $command['message'];

    $method = $message[$text]
        ? $message[$text]
        : $message[getMessageText($text, $io)];

    $dispatch->controller = 'MessageController';
    $dispatch->method = $method ?? 'messageOther';

    return $dispatch;
}

/**
 * @param $text
 * @param $io
 * @return mixed
 */
function getMessageText($text, $io)
{
    // checks if message has parameter after space
    $explodedBySpace = explode(' ', $text);
    if (count($explodedBySpace) > 1) {
        $text = $explodedBySpace[0];
        array_shift($explodedBySpace);
        $io->setParams($explodedBySpace);

        return $text;
    }

    // checks if message has parameter after `=`
    $explodedByEqualOperator = explode('=', $text);
    if (count($explodedByEqualOperator) > 1) {
        $text = $explodedByEqualOperator[0];
        array_shift($explodedByEqualOperator);
        $io->setParams(explode(' ',$explodedByEqualOperator
            ? $explodedByEqualOperator[0] : ''));

        return $text;
    }

    return $text;
}

/**
 * @param stdClass $request
 * @param stdClass $dispatch
 * @param Container $container
 * @param array $command
 * @return stdClass
 */
function callback(stdClass $request, stdClass $dispatch, Container $container, array $command): stdClass
{


    # set method name
    if ($request->callback_query->game_short_name) {
        $dispatch->method = $command['callback']['game'];

    } elseif ($request->callback_query->data) {

        $callbackQueryData = $request->callback_query->data;
        $method = array_shift(explode('-', $callbackQueryData));
        $callbackData = $command['callback']['data'];
        $dispatch->method = $callbackData[$method];
    }


    return $dispatch;


}

/**
 * @param stdClass $request
 * @param stdClass $dispatch
 * @param Container $container
 * @param array $command
 * @return stdClass
 * @internal param array $setting
 */
function inline(stdClass $request, stdClass $dispatch, Container $container, array $command): stdClass
{
    $query = $request->inline_query->query;
    $inline = $command['inline'];

    /** @var \Monolog\Logger $log */
//    $log = $container->get('logger');
//    $log->addNotice('message', ['query' => $query]);

    $dispatch->controller = 'InlineController';
    $dispatch->method = $inline[$query];

    return $dispatch;
}