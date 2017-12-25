<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-11-24
 * Time: 10:53 AM
 */

namespace main;

use model\ShopModel;
use model\ZoneModel;

class CallbackMain extends MainMain
{



    /**
     * @return ShopModel
     */
    private function shopModel(): ShopModel
    {
        return $this->container->get('shopModel');
    }

    /**
     * @return ZoneModel
     */
    private function zoneModel(): ZoneModel
    {
        return $this->container->get('zoneModel');
    }

    private  function traverse_farsi ($str){
        $farsi_chars = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
        $latin_chars = ['0','1','2','3','4','5','6','7','8','9'];

        $rtlNum=str_replace($latin_chars,$farsi_chars,$str);

        return end(explode('-',$rtlNum)).'-'.array_shift(explode('-',$rtlNum));
    }


    public function getShops($category_id)
    {

        $shopModel = $this->shopModel();

        $zoneModel = $this->zoneModel();

        $cities = $zoneModel->all();
        $chatId = $this->request->message->chat->id;

        $shops = $shopModel->find($category_id);





        foreach ($shops as $shop){
            $content='';

            $content .='<a href="'.$shop['image_link'].'?tnl">&#160;</a>';
            $content .='فروشگاه '.' '.$shop['name']."\n\n";
            $content .="👤 مدیر :".' '.$shop['manager']."\n";
            $content .="🛍 محصولات:".'ابزار و لوازم، پوشاک و کودک، کادویی و تزئینی، خانه و آشپزخانه، مدرسه و اداره، ورزش و سرگرمی، آرایشی و بهداشتی، زیور آلات'."\n\n";
            $content .="📄 آدرس :".' '.$shop['address']."\n";
            $content .="🕰 ساعت کاری: ".' '.$shop['hours']."\n";
            $content .="☎️ تلفن :".' '.$this->traverse_farsi($shop['phone'])."\n";

            $result = [
                'method' => 'sendMessage',
                'chat_id' => $chatId,
                'text' =>  urlencode($content),
                'parse_mode' => 'HTML',
                'reply_markup' => [
                    'inline_keyboard' => [
                        [
                            ['text' => "🗺 مشاهده روی نقشه", 'url' => $shop['map_link']]
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