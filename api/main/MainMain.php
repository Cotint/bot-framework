<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-11-24
 * Time: 10:55 AM
 */

namespace main;

use League\Container\Container;
use service\IO;
use Monolog\Logger;


class MainMain
{
    public $container;
    /** @var IO $io */
    public $io;
    public $request;
    public $setting;
    /** @var $keyboard KeyboardMain */
    public $keyboard;

    public $redis;

    /**
     * MainMain constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->setting = $container->get('settings');
        $this->io = $container->get('io');
        $this->request = $this->io->getRequest();
        $this->keyboard = $container->get('keyboard');
    }

    /**
     * @return Logger
     */
    public function log(): Logger
    {
        return $this->container->get('logger');
    }
}