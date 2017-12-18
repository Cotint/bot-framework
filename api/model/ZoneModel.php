<?php
/**
 * Created by PhpStorm.
 * User: mohsenjalalian
 * Date: 6/9/17
 * Time: 4:04 PM
 */

namespace model;


use League\Container\Container;

class ZoneModel extends MainModel
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

        $stmt = $pdo->prepare("SELECT zone_id FROM `shops` WHERE `active`=1 ");
        $stmt->execute();

        $zones_id = $stmt->fetchAll();

        $ids=[];

        foreach ($zones_id as $zone){

                $ids[]=$zone['zone_id'];
        }

        $ids = join(',',$ids);

        $stmt = $pdo->prepare("SELECT * FROM `zone` WHERE id IN ($ids)");

        $stmt->execute();

        $cities = $stmt->fetchAll();

        return $cities;
//
//        $allCities=[];
//
//        foreach ($cities as $city){
//            $city['shops_count']=$this->countShops($city['id']);
//
//            $allCities[]=$city;
//
//        }
//
//        return $allCities;

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