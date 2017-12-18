<?php
/**
 * Created by PhpStorm.
 * User: mohsenjalalian
 * Date: 6/9/17
 * Time: 4:04 PM
 */

namespace model;


use League\Container\Container;

class NewsModel extends MainModel
{

    public function all()
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("SELECT * FROM `news` WHERE `confirm`=1 LIMIT 5");
        $stmt->execute();

        $result = $stmt->fetchAll();


        $allNews=[];

        foreach ($result as $news){
            $news['image_link']="http://dev.tnl.ir/uploaded_files/".$this->getImageName($news['image_id']);
            $allNews[]=$news;
        }


        return $allNews;

    }

    private function getImageName($id){

        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("SELECT name FROM `filemanager` WHERE `id`=".$id);
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result[0]['name'];
    }


}