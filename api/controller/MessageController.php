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

    public function back()
    {
        $this->messageMain()->back();
    }

    public function backPrevious()
    {
        $this->messageMain()->backPrevious();
    }

    public function listBrand()
    {
        $this->messageMain()->listBrand();
    }

    public function support()
    {
        $this->messageMain()->support();
    }

    public function invite()
    {
        $this->messageMain()->invite();
    }

    public function consult()
    {
        $this->messageMain()->consult();
    }

    public function shopketAds()
    {
        $this->messageMain()->shopketAds();
    }

    public function aboutBot()
    {
        $this->messageMain()->aboutBot();
    }

    public function about()
    {
        $this->messageMain()->about();
    }

    public function contact()
    {
        $this->messageMain()->contact();
    }

    public function buyInfo()
    {
        $this->messageMain()->buyInfo();
    }

    public function shipmentAbout()
    {
        $this->messageMain()->shipmentAbout();
    }

    public function refundAbout()
    {
        $this->messageMain()->refundAbout();
    }

    public function termsConditions()
    {
        $this->messageMain()->termsConditions();
    }

    public function instagram()
    {
        $this->messageMain()->instagram();
    }

    public function telegram()
    {
        $this->messageMain()->telegram();
    }

    public function messageOther()
    {
        $this->messageMain()->messageOther();
    }
}