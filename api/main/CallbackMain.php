<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-11-24
 * Time: 10:53 AM
 */

namespace main;

use Doctrine\DBAL\Driver\PDOConnection;
use Doctrine\DBAL\Driver\PDOStatement;
use model\ShopModel;
use model\UserModel;
use model\ZoneModel;

class CallbackMain extends MainMain
{

    /**
     * @return UserModel
     */
    private function shopModel(): ShopModel
    {
        return $this->container->get('shopModel');
    }

    /**
     * @return UserModel
     */
    private function zoneModel(): ZoneModel
    {
        return $this->container->get('zoneModel');
    }


    public function getShops($category_id)
    {


        $shopModel = $this->shopModel();

        $zoneModel = $this->zoneModel();
        $cities = $zoneModel->all();


        $shops = $shopModel->find($category_id);

        $chatId = $this->request->message->chat->id;


        foreach ($shops as $shop){
            $content='';

            $content .='فروشگاه '.'#'.$shop['name']."\n";
            $content .='<a href="'.$shop['image_link'].'">&#160;</a>';

            $result = [
                'method' => 'sendMessage',
                'chat_id' => $chatId,
                'text' =>  urlencode($content),
                'parse_mode' => 'HTML',
                'reply_markup' => [
                    'inline_keyboard' => [
                            [
                                ['text' => "مشاهده روی نقشه", 'url' => $shop['map_link']]
                            ]
                    ],
                    [
                        'keyboard' => $this->keyboard->citiesButtons($cities),
                        'resize_keyboard' => true
                    ]

                ]
            ];

            $this->io->setResponse($result);
        }



    }
}