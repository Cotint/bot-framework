<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-11-24
 * Time: 10:55 AM
 */

namespace main;

use Doctrine\DBAL\Driver\PDOConnection;
use Doctrine\DBAL\Driver\PDOStatement;
use function GuzzleHttp\Psr7\str;
use model\UserModel;
use model\ShopModel;
use model\ZoneModel;
use model\NewsModel;
use model\ContactModel;
use model\CategoryModel;

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
     * @return SupportModel
     */
    private function zoneModel(): ZoneModel
    {
        return $this->container->get('zoneModel');
    }

    /**
     * @return SupportModel
     */
    private function shopModel(): ShopModel
    {
        return $this->container->get('shopModel');
    }

    /**
     * @return SupportModel
     */
    private function categoryModel(): CategoryModel
    {
        return $this->container->get('categoryModel');
    }

    private function newsModel(): NewsModel
    {
        return $this->container->get('newsModel');
    }

    private function contactModel(): ContactModel
    {
        return $this->container->get('contactModel');
    }


    public function start()
    {
        $chat_id = $this->request->message->chat->id;

        $userModel = $this->userModel();

        $userModel->createOrUpdate($this->request->message);

        $content = "👀 سلام به تونل بات خوش آمدید";
        $content .= "\n";
        $content .= "\n";
        $content .= "با این ربات اطلاعات جامعی از تونل و فروشگاه هایش به دست خواهید آورد. شروع کنید";
        $content .= "\n";




        $result = [
            'method' => 'sendMessage',
            'chat_id' => $chat_id,
            'text' =>  urlencode($content),
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'keyboard' => $this->keyboard->welcomeButtons(),
                'resize_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);
    }

    public function getShops()
    {

        $shopModel = $this->shopModel();

        $cities = $shopModel->cities();

        $chatId = $this->request->message->chat->id;

        $userModel = $this->userModel();

        $userModel->setState($chatId,'city');

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $chatId,
            'text' => 'لطفا شهر مورد نظر خود را انتخاب کنید',
            'reply_markup' => [
                'inline_keyboard' => $this->keyboard->listCities($cities),
            ]
        ];

        $this->io->setResponse($result);
    }

    public function getCategories()
    {

        $categoryModel = $this->categoryModel();

        $categories = $categoryModel->all();


        $chatId = $this->request->message->chat->id;

        $userModel = $this->userModel();
        $userModel->setState($chatId,'category');


        $result = [
            'method' => 'sendMessage',
            'chat_id' => $chatId,
            'text' => 'دسته بندی محصولات ',
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'keyboard' => $this->keyboard->categories($categories),
                'resize_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);
    }

    public function getNews()
    {

        try{

            $newsModel = $this->newsModel();

            $allNews = $newsModel->all();

            $chatId = $this->request->message->chat->id;



            foreach ($allNews as $news){
                $content='';

                $content .='<a href="'.$news['image_link'].'">&#160;</a>';
                $content .=$news['title']."\n\n";
                $content .=$news['description']."\n\n";


                $result = [
                    'method' => 'sendMessage',
                    'chat_id' => $chatId,
                    'text' =>  urlencode($content),
                    'parse_mode' => 'HTML',
                    'reply_markup' => [

                        'keyboard' => $this->keyboard->backButton(),
                        'resize_keyboard' => true


                    ]
                ];

                $this->io->setResponse($result);
            }


        }

        catch (\Exception $e){
            file_put_contents('newsError.txt',$e->getMessage());
        }

    }


    private  function traverse_farsi ($str){
        $farsi_chars = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
        $latin_chars = ['0','1','2','3','4','5','6','7','8','9'];


        $nums= explode('-',str_replace($latin_chars,$farsi_chars,$str));

        return $nums[2].'-'.$nums[1].'-'.$nums[0];
    }


    public function contact()
    {

        $contactModel = $this->contactModel();

        $info = $contactModel->all();

        $chat_id = $this->request->message->chat->id;


        $content = "📞تماس با ما";
        $content .= "\n";
        $content .= "\n";
        $content .= "☎️ تلفن :".$this->traverse_farsi($info['CONTACT_PHONE'])."\n";
        $content .= "📩 ایمیل ".$info['CONTACT_MAIL']."\n";
        $content .= "🕰 ساعت کاری: "."\n";
        $content .= $info['CONTACT_WORKING_HOURS']."\n";
        $content .= "📄 آدرس :"."\n";
        $content .= $info['CONTACT_ADDRESS']."\n\n";
        $content .='<a href="https://t.me/tnl_ir">https://t.me/tnl_ir</a>'."\n";
        $content .='<a href="http://cotint.ir">Powered by Cotint</a>';


        $result = [
            'method' => 'sendMessage',
            'chat_id' => $chat_id,
            'text' =>  urlencode($content),
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'keyboard' => $this->keyboard->welcomeButtons(),
                'resize_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);

    }

    public function about()
    {
        $chat_id = $this->request->message->chat->id;

         $content =' 📄 ';
        $content .= "شرکت توسعه ونوآوری لوتوس از سال1391 کار خود را در زمینه فروشگاه های زنجیره ای با نام تجاری تونل آغاز کرد. مجموعه تونل فعالیت خود را با شعار \"تونل میانبری با صرفه\" و با هدف توزیع گسترده کالا های اساسی وفروش مستقیم و بدون واسطه در شمال کشور عزیزمان آغاز و با سرعت در اقصی نقاط کشور گسترش داده و در حال حاضر33 فروشگاه فعال در سراسر کشور در حال خدمات رسانی به مشتریان این مجموعه می باشند، ولی این پایان کار نیست و مجموعه همواره در حال رشد، توسعه، و کار آفرینی است و توقف معنایی ندارد. ";

        $content .= "\n";
        $content .= "✅  http://tnl.ir"."\n";
        $content .= "📞 028-32884105-8"."\n";
        $content .= "📢  @tnl_ir | لینک کانال"."\n";

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $chat_id,
            'text' =>  urlencode($content),
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'keyboard' => $this->keyboard->welcomeButtons(),
                'resize_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);

    }

    public function home(){

        $chat_id = $this->request->message->chat->id;

        $userModel = $this->userModel();

        $userModel->setState('0',$chat_id);


        $content  = "لطفا گزینه دیگری را انتخاب کنید:✔️";
        $content .= "\n";
        $content .= "\n";
        $content .= "\n";
        $content .= "🚇 تونل  | میانبری به صرفه";

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $chat_id,
            'text' =>  urlencode($content),
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'keyboard' => $this->keyboard->welcomeButtons(),
                'resize_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);
    }


    public function askCity()
    {
        $chatId = $this->request->message->chat->id;

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $chatId,
            'text' => 'شهر مورد نظر خود را انتخاب کنید',
            'reply_markup' => [
                'keyboard' => $this->keyboard->backButton(),
                'resize_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);
    }

    public function messageOther()
    {
        $chatId = $this->request->message->chat->id;
        $text = $this->request->message->text;

        $emoji=array_shift(explode(' ',$text));
        //it's wrong

        $name=str_replace($emoji,'',$text);


        $state = $this->userModel()->getState($chatId);

                switch ($state) {
//                    case 'city':
//
//                        $zoneModel = $this->zoneModel();
//                        $shopModel = $this->shopModel();
//
//                        if ($zoneModel->hasShop($text)) {
//
//                            $shops = $shopModel->findByName($text);
//
//
//                            foreach ($shops as $shop){
//                                $content='';
//
//                                $content .='<a href="'.$shop['image_link'].'?tnl">&#160;</a>';
//                                $content .='فروشگاه '.'#'.$shop['name']."\n";
//                                $content .='آدرس: '.$shop['address']."\n";
//                                $content .='🗺'.'مشاهده در نقشه: '.$shopModel->mapLink($shop['latlng'])."\n\n";
//
//
//                                $result = [
//                                    'method' => 'sendMessage',
//                                    'chat_id' => $chatId,
//                                    'text' =>  urlencode($content),
//                                    'parse_mode' => 'HTML',
//                                    'reply_markup' => [
//                                        'inline_keyboard' => [
//                                            [
//                                                ['text' => "📢 ورود به کانال بارنگ فود", 'url' => 't.me/barangfood']
//                                            ]
//                                        ],
//                                        'keyboard' => $this->keyboard->enterCityOrBack(),
//                                        'resize_keyboard' => true
//                                    ]
//                                ];
//
//                                $this->io->setResponse($result);
//                            }
//
//                        } else {
//
//                            $result = [
//                                'method' => 'sendMessage',
//                                'chat_id' => $chatId,
//                                'text' => 'هیچ فروشگاهی پیدا نشد!',
//                                'reply_markup' => [
//                                    'keyboard' => $this->keyboard->backBottom(),
//                                    'resize_keyboard' => true
//                                ]
//                            ];
//                            $this->io->setResponse($result);
//
//                        }
//                        break;

                    case 'category':

                     //   file_put_contents('print.html',$print);

                        $categoryModel = $this->categoryModel();


                        if ($categoryModel->hasProduct(trim($name))) {

                            $products = $categoryModel->getProducts(trim($name));



                            foreach ($products as $product){


                                $content ='<a href="'.$product['image_link'].'?tnl">&#160;</a>';
                                $content .="🔘 ".$product['name'];

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
                                'keyboard' => $this->keyboard->backButton(),
                                'resize_keyboard' => true
                            ]
                        ];
                        break;
                }

    }

    /****/






    public function state()
    {
        $chatId = $this->request->message->chat->id;
        if ($this->request->message->text == 'عادی') {
            $state = 0;
        } elseif ($this->request->message->text == 'باردار') {
            $state = 1;
        } else {
            $state = 2;
        }
        $setState = $this->userModel();
        if ($setState->setState($state, $chatId)) {
            $text = "وضعیت فعالیتی خود را وارد انتخاب کنید";
            $text .= "\n";
            $text .= "\n";
            $text .= "🔵بدون فعالیت";
            $text .= "\n";
            $text .= "🔴خواب و دراز کشیدن";
            $text .= "\n";
            $text .= "\n";
            $text .= "🔵کم فعالیت";
            $text .= "\n";
            $text .= "🔴نشستن و فعالیت های سرپایی، نقاشی کردن، رانندگی، کار آزمایشگاهی، تایپ کردن، خیاطی، اتو کردن، پخت و پز، زدن یک ساز موسیقی";
            $text .= "\n";
            $text .= "\n";
            $text .= "🔵فعالیت متوسط";
            $text .= "\n";
            $text .= "🔴پیاده روی روی سطح صاف، نجاری، کار در رستوران، نظافت منزل، مراقبت از بچه، گلف، قایق رانی، تنیس روز میز";
            $text .= "\n";
            $text .= "\n";
            $text .= "🔵فعالیت زیاد";
            $text .= "\n";
            $text .= "🔴پیاده روی سریع، بیل زدن، حمل بار، دوچرخه سواری، اسکی، تنیس و رقصیدن";
            $text .= "\n";
            $text .= "\n";
            $text .= "🔵فعالیت بسیار زیاد";
            $text .= "\n";
            $text .= "🔴ورزش های سنگین روزانه، پیاده روی در سربالایی، بریدن درخت، حفاری، بسکتبال، کوه نوردی، فوتبال";
            $text .= "\n";

            $text = urlencode($text);
            $result = [
                'method' => 'sendMessage',
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
                'reply_markup' => [
                    'keyboard' => $this->keyboard->activityBottom(),
                    'resize_keyboard' => true
                ]
            ];
        } else {
            $result = [
                'method' => 'sendMessage',
                'chat_id' => $chatId,
                'text' => 'دوباره تلاش کنید.',
                'reply_markup' => [
                    'keyboard' => $this->keyboard->stateBottom(),
                    'resize_keyboard' => true
                ]
            ];
        }

        $this->io->setResponse($result);
    }


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


    public function listBrand()
    {
        $text = $this->request->message->text;

        /** @var PDOConnection $conn */
        $conn = $this->container->get('pdo');

        /** @var PDOStatement $stmt */
        $stmt = $conn->prepare("SELECT DISTINCT bra_Name, bra_ID FROM brands JOIN product ON product.pro_BraID = brands.bra_ID 
                                JOIN proCat ON proCat.pro_ID = product.pro_ID 
                                JOIN category ON proCat.cat_ID = category.cat_ID");


        $stmt->execute();

        $res = $stmt->fetchAll();

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' => 'یک گزینه را انتخاب کنید...',
            'parse_mode' => 'HTML',

            'reply_markup' => [
                'keyboard' => $this->keyboard->listBrandBottom($res),
                'resize_keyboard' => true
            ]
        ];

        $userId = $userId = $this->request->message->from->id;

        $this->userModel()->setState($userId, UserModel::STATUS_LIST_BRAND);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_LIST_BRAND, $text);

        $this->io->setResponse($result);
    }

    public function support()
    {
        $text = $this->request->message->text;

        $result = [
//            'method' => 'sendPhoto',
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
//            'photo' => 'https://6de89762.ngrok.io/public/image/support.jpg',
            'text' => 'اگر انتقاد یا پیشنهادی دارید یا اضافه شدن بخش جدید به طور کامل و دقیق بیان کنید',
            'reply_markup' => [
                'keyboard' => $this->keyboard->back(),
                'resize_keyboard' => true
            ]
        ];

        $userId = $userId = $this->request->message->from->id;

        $this->userModel()->setState($userId, UserModel::STATUS_SUPPORT);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_SUPPORT, $text);

        $this->io->setResponse($result);
    }

    public function invite()
    {
        $text = "برای معرفی ربات پردیس سینمایی کورش 📽 می توانید از لینک زیر استفاده کنید";
        $text .= "\n";
        $text .= "\n";
        $text .= "\n";
        $text .= "\n";
        $text .= "@KouroshCinemaBot";
        $text .= "دسترسی 24 ساعته به برنامه فیلم و نمایش های پردیس سینمایی کورش از طریق ربات تلگرام:";
        $text .= "\n";
        $text .= "\n";
        $text .= "\n";
        $text .= "\n";
        $text .= "@KouroshCinemaBot";

        $text = urlencode($text);

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' => $text,
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'resize_keyboard' => true
            ]
        ];

        $userId = $userId = $this->request->message->from->id;

        $requestText = $this->request->message->text;

        $this->userModel()->setState($userId, UserModel::STATUS_INVITE);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_INVITE, $requestText);

        $this->io->setResponse($result);
    }

    public function telegram()
    {
        $text = "لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.";
        $text .= "\n";
        $text .= "\n";
        $text .= "\n";
        $text .= "چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد";

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' => $text,
            'reply_markup' => [
                'resize_keyboard' => true
            ]
        ];

        $userId = $userId = $this->request->message->from->id;

        $requestText = $this->request->message->text;

        $this->userModel()->setState($userId, UserModel::STATUS_TELEGRAM);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_TELEGRAM, $requestText);

        $this->io->setResponse($result);
    }

   
}
