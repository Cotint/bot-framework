<?php
/**
 * Created by PhpStorm.
 * User: mohsenjalalian
 * Date: 6/9/17
 * Time: 4:04 PM
 */

namespace model;

use GuzzleHttp\Client;

class ShopModel extends MainModel
{

    public function cities()
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

        $allCities=[];

        foreach ($cities as $city){
            $city['shops_count']=$this->countShops($city['id']);

            $allCities[]=$city;

        }

        return $allCities;

    }

    public function find($id)
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("SELECT * FROM `shops` WHERE `active`=1 AND `zone_id` =".$id);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $shops=[];

        foreach ($result as $shop){
            $shop['image_link']="http://dev.tnl.ir/uploaded_files/".$this->getImageName($shop['image_id']);
            $shop['map_link']=$this->mapLink($shop['latlng']);
            $shops[]=$shop;
        }


        return $shops;
    }

    public function findByName($cityName)
    {
        $zone_id=$this->getZone($cityName);

        $shops = $this->find($zone_id);

        return $shops;

    }

    private function getZone($cityName)
    {
        $pdo = $this->container->get('pdo');


        $stmt = $pdo->prepare("SELECT id FROM `zone` WHERE `name` LIKE '".$cityName."'");

        $stmt->execute();

        $result= $stmt->fetchAll();

        return $result[0]['id'];

    }

    private function countShops($id)
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `shops` WHERE `active`=1 AND  `zone_id`=".$id);
        $stmt->execute();

        return $stmt->fetchColumn();

    }

    public function mapLink($latlong)
    {
        $latlong=explode(' ',str_replace(['(',')',','],'',$latlong));

        $lat=array_shift($latlong);
        $long=end($latlong);


        $mapLink ="https://www.google.com/maps/search/?api=1&language=fa&query=".$lat.','.$long;

        $client = new Client();

        $response= $client->request('POST', 'http://ctnt.ir/api/v1/shortener', [
            'json' => ['url' => $mapLink]
        ]);

        $body = $response->getBody()->getContents();

        $obj=json_decode($body);

        return $obj->short;


    }

    private function getImageName($id){

        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("SELECT name FROM `filemanager` WHERE `id`=".$id);
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result[0]['name'];
    }
}


