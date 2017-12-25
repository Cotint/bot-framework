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

        $stmt = $pdo->prepare('SELECT * FROM news WHERE confirm = :confirm ORDER BY created_at DESC LIMIT 5 ');

        $stmt->execute(array('confirm' => 1));

//        $stmt->execute();
//
//        $result = $stmt->fetchAll();


        $allNews=[];

        foreach ($stmt as $news){
            $news['image_link']="http://dev.tnl.ir/uploaded_files/".$this->getImageName($news['image_id']);
            $allNews[]=$news;
        }


        return $allNews;

    }

    private function getImageName($id){

        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("SELECT name FROM filemanager WHERE id= :id");
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetchAll();

        return $result[0]['name'];
    }


}