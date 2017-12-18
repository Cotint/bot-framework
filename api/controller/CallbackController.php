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

    public function getShops()
    {
        $request = $this->container->get('io')->getRequest();


       $request->callback_query->data;

        $category_id=end(explode('-',$request->callback_query->data));


        $this->callbackMain()->getShops($category_id);
    } 


}