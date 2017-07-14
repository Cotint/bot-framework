<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-11-01
 * Time: 9:42 AM
 */

namespace controller;

use main\InlineMain;


class InlineController extends MainController
{
    private function inlineMain(): InlineMain
    {
        return $this->container->get('inlineMain');
    }
}