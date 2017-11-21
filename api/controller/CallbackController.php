<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-11-01
 * Time: 9:42 AM
 */

namespace controller;

use main\CallbackMain;


class CallbackController extends MainController
{
    private function callbackMain(): CallbackMain
    {
        return $this->container->get('callbackMain');
    }

    public function getProduct()
    {
        $this->callbackMain()->getProduct();
    } 
    public function deleteProduct()
    {
        $this->callbackMain()->deleteProduct();
    }
        
    public function messageOther()
    {
        $this->callbackMain()->messageOther();
    }
    

    

}