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

    public function showCart()
    {
        $this->messageMain()->showCart();
    }

    public function selectProductForDelete()
    {
        $this->messageMain()->selectProductForDelete();
    }

    public function setComment()
    {
        $this->messageMain()->setComment();
    }

    public function setStar()
    {
        $this->messageMain()->setStar();
    }

    public function cheapest()
    {
        $this->messageMain()->cheapest();
    }

    public function bestSelling()
    {
        $this->messageMain()->bestSelling();
    }

    public function newest()
    {
        $this->messageMain()->newest();
    }

    public function mostPopular()
    {
        $this->messageMain()->mostPopular();
    }

    public function addToCart()
    {
        $this->messageMain()->addToCart();
    }

    public function finalSubmit()
    {
        $this->messageMain()->finalSubmit();
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