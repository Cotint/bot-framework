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

# send response
/** @var \service\IO $io */
$io = $container->get('io');
$io->sendResponse();