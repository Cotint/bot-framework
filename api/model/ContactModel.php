<?php
/**
 * Created by PhpStorm.
 * User: mohsenjalalian
 * Date: 6/9/17
 * Time: 4:04 PM
 */

namespace model;


use League\Container\Container;

class ContactModel extends MainModel
{

//    public $pdo;
//
//    public function __construct(Container $container)
//    {
//        $this->pdo=$this->container->get('pdo');
//    }

    public function all()
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("SELECT * FROM `options`");
        $stmt->execute();

        $result =$stmt->fetchAll();


        $options=[];

        foreach($result as $k=>$option){
            $option['value']=$option['value'];
            $options[$option['key']]=$option['value'];
        }

        return $options;
    }


    public function hasShop($cityName)
    {
        $zone_id=$this->getZone($cityName);
        if(!$zone_id){
            return false;
        }

        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `shops` WHERE `active`=1 AND `zone_id`= ".$zone_id);

        $stmt->execute();

        return $stmt->fetchColumn()>0;

    }

    private function getZone($cityName)
    {
        $pdo = $this->container->get('pdo');


        $stmt = $pdo->prepare("SELECT id FROM `zone` WHERE `name` LIKE '".$cityName."'");

        $stmt->execute();

        $result= $stmt->fetchAll();

        return $result[0]['id'];

    }
}