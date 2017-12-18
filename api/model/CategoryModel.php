<?php
/**
 * Created by PhpStorm.
 * User: mohsenjalalian
 * Date: 6/9/17
 * Time: 4:04 PM
 */

namespace model;


class CategoryModel extends MainModel
{

    public function all()
    {
        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("SELECT name FROM `categories` WHERE `active`=1 AND `type`='product' ORDER BY `order`");
        $stmt->execute();

        $categories = $stmt->fetchAll();

//        foreach ($products as $product){
//            $product['image_link']="http://dev.tnl.ir/uploaded_files/".$this->getImageName($product['image_id']);
//            $products[]=$product;
//        }


        return $categories;
    }


    public function getProducts($categoryName)
    {
        $category_id=$this->getCategory($categoryName);

        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("SELECT * FROM `products` WHERE `active`=1 AND `category_id`=".$category_id." ORDER BY `order`");
        $stmt->execute();

        $result = $stmt->fetchAll();

        $products=[];

        foreach ($result as $product){
            $product['image_link']="http://dev.tnl.ir/uploaded_files/".$this->getImageName($product['image_id']);
            $products[]=$product;
        }

        return $products;
    }


    public function hasProduct($categoryName)
    {
        $category_id=$this->getCategory($categoryName);


        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `products` WHERE `active`=1 AND `category_id`=".$category_id);

        $stmt->execute();

        return $stmt->fetchColumn()>0;

    }

    private function getImageName($id){

        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("SELECT name FROM `filemanager` WHERE `id`=".$id);
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result[0]['name'];
    }

    private function getCategory($categoryName)
    {

        $pdo = $this->container->get('pdo');

        $stmt = $pdo->prepare("SELECT id FROM `categories` WHERE `name`='".$categoryName."'");

        $stmt->execute();

        $result= $stmt->fetchAll();

        return $result[0]['id'];

    }

}