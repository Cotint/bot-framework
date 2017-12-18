<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-10-30
 * Time: 9:33 AM
 */

use League\Container\Container;
/** @var $setting array*/

/** @var service\IO $io */
$io = $container->get('io');
$request = $io->getRequest();



# log
/** @var \Monolog\Logger $log */
$log = $container->get('logger');
$log->addInfo(json_encode((array)$request));

$dispatch = new stdClass();
switch ($request) {
    case isset($request->message):
        $dispatch = message($request, $dispatch, $container, $setting);
        break;
    case isset($request->callback_query):
        $dispatch = callback($request, $dispatch, $container, $setting);
        break;
    case isset($request->inline_query):
        $dispatch = inline($request, $dispatch, $container, $setting);
        break;
}

/**
 * @param stdClass $request
 * @param stdClass $dispatch
 * @param Container $container
 * @param array $setting
 * @return stdClass
 */
function message(stdClass $request, stdClass $dispatch, Container $container, array $setting): stdClass
{
    $text = $request->message->text;
    $message = $setting['dispatcher']['message'];

    /** @var \Monolog\Logger $log */
//    $log = $container->get('logger');
//    $log->addNotice('message', ['text' => $text]);

    $method = $message[$text];

    $dispatch->controller = 'MessageController';
    $dispatch->method = $method ?? 'messageOther';

    return $dispatch;
}

/**
 * @param stdClass $request
 * @param stdClass $dispatch
 * @param Container $container
 * @param array $setting
 * @return stdClass
 */
function callback(stdClass $request, stdClass $dispatch, Container $container, array $setting): stdClass
{
    $data = $request->callback_query->data;
    $dispatch->controller = 'CallbackController';

    $method=array_shift(explode('-',$data));


    // $dispatch->method = 'getShops';

    $callbackData = $setting['dispatcher']['callback']['data'];


    $dispatch->method = $callbackData[$method];


    return $dispatch;
}

/**
 * @param stdClass $request
 * @param stdClass $dispatch
 * @param Container $container
 * @param array $setting
 * @return stdClass
 */
function inline(stdClass $request, stdClass $dispatch, Container $container, array $setting): stdClass
{
    $query = $request->inline_query->query;
    $inline = $setting['dispatcher']['inline'];

    /** @var \Monolog\Logger $log */
//    $log = $container->get('logger');
//    $log->addNotice('message', ['query' => $query]);

    $dispatch->controller = 'InlineController';
    $dispatch->method = $inline[$query];

    return $dispatch;
}