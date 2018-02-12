<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-11-01
 * Time: 9:41 AM
 */

namespace controller;

use main\MessageMain;


class MessageController extends MainController
{
    private function messageMain(): MessageMain
    {
        return $this->container->get('messageMain');
    }

    public function start()
    {
        $this->messageMain()->start();
    }


    public function messageOther()
    {
        $this->messageMain()->messageOther();
    }


}