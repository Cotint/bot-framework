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

    public function shops(){
        $this->messageMain()->getShops();
    }

    public function categories(){
        $this->messageMain()->getCategories();
    }

    public function news()
    {
        $this->messageMain()->getNews();
    }

    public function help()
    {
        $this->messageMain()->help();
    }

    public function askCity()
    {
        $this->messageMain()->askCity();
    }

    public function contact()
    {
        $this->messageMain()->contact();
    }

    public function about()
    {
        $this->messageMain()->about();
    }

    public function home()
    {
        $this->messageMain()->home();
    }

    public function state()
    {
        $this->messageMain()->state();
    }


    public function messageOther()
    {
        $this->messageMain()->messageOther();
    }
}