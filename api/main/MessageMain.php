<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-11-24
 * Time: 10:55 AM
 */

namespace main;

use model\User;
use model\History;

class MessageMain extends MainMain
{
    /**
     * @return UserModel
     */
    private function userModel(): UserModel
    {
        return $this->container->get('userModel');
    }


    /**
     *Start Method
     */
    public function start()
    {

        $data=[
            'chat_id'=>$this->request->message->chat->id,
            'username'=>$this->request->message->from->username,
            'first_name'=>$this->request->message->from->first_name,
            'last_name'=>$this->request->message->from->last_name,
        ];

        History::saveUserInfo($data);

        $content = "سلام خوش آمدید ";
        $content .= "\n";
        $content .= "ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ";
        $content .= "\n";
        $content .= " متن آزمایشی";
        $content .= "\n";
        $content .= "\n";
        $content .= "\n";
        $content .= "✅  cotint.ir";
        $content .= "📞 021-22035976";


        $result = [
            'method' => 'sendMessage',
            'chat_id' => $data['chat_id'],
            'text' =>  urlencode($content),
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'inline_keyboard' => $this->keyboard->inlineButtons(),
            ]
        ];

        $this->io->setResponse($result);
    }

    public function messageOther()
    {
        $chatId = $this->request->message->chat->id;
        $text = $this->request->message->text;

        $state = $this->userModel()->getState($chatId);

        switch ($state) {
                case 'city':

                    $zoneModel = $this->zoneModel();
                    $shopModel = $this->shopModel();

                    if ($zoneModel->hasShop($text)) {

                        $shops = $shopModel->findByName($text);


                        foreach ($shops as $shop){
                            $content='';

                            $content .='فروشگاه '.'#'.$shop['name']."\n";
                            $content .='آدرس: '.$shop['address']."\n";
                            $content .='<a href="'.$shop['image_link'].'">&#160;</a>';
                            $content .='🗺'.'مشاهده در نقشه: '.$shopModel->mapLink($shop['latlng'])."\n\n";


                            $result = [
                                'method' => 'sendMessage',
                                'chat_id' => $chatId,
                                'text' =>  urlencode($content),
                                'parse_mode' => 'HTML',
                                'reply_markup' => [
                                    'inline_keyboard' => [
                                        [
                                            ['text' => "📢 ورود به کانال بارنگ فود", 'url' => 't.me/barangfood']
                                        ]
                                    ],
                                    'keyboard' => $this->keyboard->enterCityOrBack(),
                                    'resize_keyboard' => true
                                ]
                            ];

                            $this->io->setResponse($result);
                        }

                    } else {

                        $result = [
                            'method' => 'sendMessage',
                            'chat_id' => $chatId,
                            'text' => 'هیچ فروشگاهی پیدا نشد!',
                            'reply_markup' => [
                                'keyboard' => $this->keyboard->backBottom(),
                                'resize_keyboard' => true
                            ]
                        ];
                        $this->io->setResponse($result);

                    }
                    break;
                case 'category':

                    $categoryModel = $this->categoryModel();


                    if ($categoryModel->hasProduct($text)) {

                        $products = $categoryModel->getProducts($text);


                        foreach ($products as $product){
                            $content='';

                            $content .='محصول '.'#'.$product['name']."\n";
                            $content .='<a href="'.$product['image_link'].'">&#160;</a>';

                            $result = [
                                'method' => 'sendMessage',
                                'chat_id' => $chatId,
                                'text' =>  urlencode($content),
                                'parse_mode' => 'HTML',
                                'reply_markup' => [
                                    'keyboard' => $this->keyboard->categories($categoryModel->all()),
                                    'resize_keyboard' => true
                                ]
                            ];

                            $this->io->setResponse($result);
                        }

                    } else {

                        $result = [
                            'method' => 'sendMessage',
                            'chat_id' => $chatId,
                            'text' => 'هیچ محصولی پیدا نشد!',
                            'reply_markup' => [
                                'keyboard' => $this->keyboard->categories($categoryModel->all()),
                                'resize_keyboard' => true
                            ]
                        ];
                        $this->io->setResponse($result);

                    }


                    break;
                default :
                    $result = [
                        'method' => 'sendMessage',
                        'chat_id' => $chatId,
                        'text' => 'دستور وارد شده صحیح نیست.',
                        'reply_markup' => [
                            'keyboard' => $this->keyboard->back(),
                            'resize_keyboard' => true
                        ]
                    ];
                    $this->io->setResponse($result);
                    break;
        }

    }

    /****/



    public function back()
    {
        $text = $this->request->message->text;

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' => 'یک گزینه را انتخاب کنید...',
            'reply_markup' => [
                'keyboard' => $this->keyboard->mainBottom(),
                'resize_keyboard' => true
            ]
        ];

        $userId = $userId = $this->request->message->from->id;

        $this->userModel()->setState($userId, UserModel::STATUS_BACK);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_BACK, $text);

        $this->io->setResponse($result);
    }

    public function errorCreateResult()
    {
        $result = [
            'method' => 'sendMessage',
            'chat_id' =>  $this->request->message->chat->id,
            'text' => 'خطا! دو.باره تلاش کنید',
            'reply_markup' => [
                'keyboard' => $this->keyboard->welcomeButtons(),
                'resize_keyboard' => true
            ]

        ];

        $this->io->setResponse($result);
    }
}