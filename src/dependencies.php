<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-10-29
 * Time: 8:58 AM
 */

use Illuminate\Database\Capsule\Manager as Capsule;

# get container
$container = new League\Container\Container;

# setting
/** @var $setting array */
$container->share('settings', $setting);

# input output handler
$container->share('io', new \service\IO());


$container->share('capsule', function (){

    $capsule = new Capsule;

    $capsule->addConnection([
        "driver" => "mysql",
        "host" =>"localhost",
        "database" => "barber",
        "username" => "root",
        "password" => "Cotint"

    ]);

    $capsule->bootEloquent();
    $capsule->setAsGlobal();


    return $capsule;
});



//
//$container['capsule']->setAsGlobal();
//// Setup the Eloquent ORM...
//$container['capsule']->bootEloquent();

//$container->share('capsule', (new Capsule())->addConnection([
//                        "driver" => "mysql",
//                        "host" =>"127.0.0.1",
//                        "database" => "barber",
//                        "username" => "root",
//                        "password" => "Cotint"
//
//])->setAsGlobal());

# log
$container->share('logger', function () use ($setting) {
    $log = new \Monolog\Logger($setting['logger']['name']);
    $log->pushHandler(new \Monolog\Handler\StreamHandler($setting['logger']['path'], \Monolog\Logger::DEBUG));
    return $log;
});

#guzzle
$container->share('client',new GuzzleHttp\Client);

# keyboard
$container->share('keyboard', new \main\KeyboardMain());

# callbackMain
$container->share('callbackMain', new \main\CallbackMain($container));

# inlineMain
$container->share('inlineMain', new \main\InlineMain($container));

# messageMain
$container->share('messageMain', new \main\MessageMain($container));

# pdo
$container->share('pdo', function () use ($setting) {
    $servername = $setting['pdo']['servername'];
    $username = $setting['pdo']['username'];
    $password = $setting['pdo']['password'];
    $dbname = $setting['pdo']['dbname'];
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }
    catch(PDOException $e)
    {
        return $e->getMessage();
    }
});


/*Add Your New Models Down Here*/

