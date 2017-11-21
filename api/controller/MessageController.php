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

    public function help()
    {
        $this->messageMain()->help();
    }
    public function barang()
    {
        $this->messageMain()->barang();
    }

    public function calorie()
    {
        $this->messageMain()->calorie();
    }
    public function bmi()
    {
        $this->messageMain()->bmi();
    }
    public function backHome()
    {
        $this->messageMain()->backHome();
    }

    public function state()
    {
        $this->messageMain()->state();
    }

    public function activity()
    {
        $this->messageMain()->activity();
    }
    
    public function gender()
    {
        $this->messageMain()->gender();
    }
    
    public function addAnotherProduct()
    {
        $this->messageMain()->addAnotherProduct();
    }
    public function previousStep()
    {
        $this->messageMain()->previousStep();
    }
}