<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-10-29
 * Time: 10:40 AM
 */


# run
$controllerName = $dispatch->controller;
$methodName = $dispatch->method;
$namespace = '\controller\\'.$controllerName;
$controller = new $namespace($container);


$controller->$methodName();


//# execute method
//try {
//    $controller->$methodName();
//} catch (Exception $e) {
//
//    /** @var \main\MessageMain $messageMain */
//    $messageMain = $container->get('messageMain');
//    $messageMain->errorCreateResult();
//}

# send response
/** @var \service\IO $io */
$io = $container->get('io');
$io->sendResponse();