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

        $content = "سلام خوش آمدید ";
        $content .= "\n";
        $content .= "ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ";
        $content .= "\n";
        $content .= " متن آزمایشی";
        $content .= "\n";
        $content .= "\n";
        $content .= "\n";
        $content .= "✅  dev.tnl.ir";
        $content .= "📞 021-22035976";
        $content .= "\n";
        $content .= "📢  @barangfood | لینک کانال";

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

        $content='';

        foreach ($cities as $city){
            $content .='#'.$city['name'].' با '.$city['shops_count'].' فروشگاه '."\n";

        }

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
            'text' => 'دسته بندی محصولات',
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

        $newsModel = $this->newsModel();

        $allNews = $newsModel->all();

        $chatId = $this->request->message->chat->id;



        foreach ($allNews as $news){
            $content='';

            $content .=$news['description']."\n\n";
            $content .=$news['description']."\n\n";
            $content .='<a href="'.$news['image_link'].'">&#160;</a>';


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

    public function contact()
    {

        $contactModel = $this->contactModel();

        $info = $contactModel->all();

        $chat_id = $this->request->message->chat->id;

        $content = "تماس با ما";
        $content .= "\n";
        $content .= "\n";
        $content .= "تلفن: ".$info['CONTACT_PHONE']."\n";
        $content .= "ایمیل: ".$info['CONTACT_MAIL']."\n";
        $content .= "تلفن پشتیبانی: ".$info['PHONE_SUPPORT']."\n";
        $content .= "ساعات کاری: "."\n";
        $content .= $info['CONTACT_WORKING_HOURS']."\n";
        $content .= "آدرس: "."\n";
        $content .= $info['CONTACT_ADDRESS']."\n";

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

        $content = "شرکت توسعه ونوآوری لوتوس از سال1391 کار خود را در زمینه فروشگاه های زنجیره ای با نام تجاری تونل آغاز کرد. مجموعه تونل فعالیت خود را با شعار \"تونل میانبری با صرفه\" و با هدف توزیع گسترده کالا های اساسی وفروش مستقیم و بدون واسطه در شمال کشور عزیزمان آغاز و با سرعت در اقصی نقاط کشور گسترش داده و در حال حاضر33 فروشگاه فعال در سراسر کشور در حال خدمات رسانی به مشتریان این مجموعه می باشند، ولی این پایان کار نیست و مجموعه همواره در حال رشد، توسعه، و کار آفرینی است و توقف معنایی ندارد. ";

        $content .= "\n";
        $content .= "✅  dev.tnl.ir"."\n";
        $content .= "📞 021-22035976"."\n";
        $content .= "📢  @barangfood | لینک کانال";

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


        $content  = "یکی از گزینه ها رو انتخاب کنید ";
        $content .= "\n";
        $content .= "\n";
        $content .= "\n";
        $content .= "✅  dev.tnl.ir";
        $content .= "📞 021-22035976";
        $content .= "\n";
        $content .= "📢  @barangfood | لینک کانال";

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
                    case '3':
                        $setAge = $this->userModel();
                        if ($setAge->setAge($text, $chatId)) {
                            $result = [
                                'method' => 'sendMessage',
                                'chat_id' => $chatId,
                                'text' => 'وضعیت خود را وارد انتخاب کنید',
                                'reply_markup' => [
                                    'keyboard' => $this->keyboard->stateBottom(),
                                    'resize_keyboard' => true
                                ]
                            ];
                        } else {
                            $result = [
                                'method' => 'sendMessage',
                                'chat_id' => $chatId,
                                'text' => 'دوباره تلاش کنید.',
                                'reply_markup' => [
                                    'keyboard' => $this->keyboard->backButton(),
                                    'resize_keyboard' => true
                                ]
                            ];
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

    public function help()
    {
        $chatId = $this->request->message->chat->id;
        $text = "سلام دوست عزیز";
        $text .= "\n";
        $text .= "این ربات به منظور محاسبه BMI و BMR/MRM شما ایجاد شده است.";
        $text .= "\n";
        $text .= "به زبان ساده";
        $text .= "\n";
        $text .= "ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ ـ";
        $text .= "\n";
        $text .= "✅  شاخص حجم بدن (Body Mass Index = BMI) فرمولی است که توسط آماردان بلژیکی ، آدولف کتلت توسعه یافت. BMI در واقع مرجعی برای نشان دادن میزان حجم بدن می باشد و دقیق ترین معیار جهانی چاقی است.با استفاده از این مقیاس می توانید متوجه شوید که آیا دچار کمبود وزن هستید ، اضافه وزن دارید و یا اینکه طبیعی هستید!";
        $text .= "\n";
        $text .= "✅ کلمه BMR مخفف عبارت انگلیسی Basal Metabolic Rate یعنی میزان متابولیسم پایه می باشد. BMR (Basal Metabolic Rate) معیاری برای ارزیابی مقدار کالری مورد نیاز بدن و عددی میباشد که نشان دهنده ی مقدار کالری است که باید روزانه توسط فرد استفاده شود.";
        $text .= "\n";
        $text .= "استفاده از این ربات بسیار سادست. برای یافتن BMI خود روی گزینه 🔎 محاسبه شاخص BMI کلیک کرده و وزن (کیلوگرم) و قد (سانتی متر) خود را وارد میکنید. برای یافتن مقدار BMR/MRM خود نیز به روی گزینه 🔎 کالری مورد نیاز شما کلیک کردم و فرم مشاهده شده را گام به گام پر میکنید و ربات محاسبات لازم برای شمارا انجام خواهد داد.";
        $text .= "\n";
        $text .= "مشاور و راهنمای شما:";
        $text .= "\n";
        $text .= "🔽🔽🔽🔽🔽";
        $text .= "\n";
        $text .= "\n";
        $text .= "✅  baranagfood.com";
        $text .= "\n";
        $text .= "- - - - - - - - - - - - -";
        $text .= "\n";
        $text .= "📩  info@barangfood.com";
        $text .= "\n";
        $text .= "📞 021-22035976";
        $text .= "\n";
        $text .= "📢  @barangfood | بارنگ فود";

        $text = urlencode($text);

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'keyboard' => $this->keyboard->welcomeBottom(),
                'resize_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);
    }

    public function barang()
    {
        $chatId = $this->request->message->chat->id;
        $text = "♨️ مجموعه بارنگ فود با هدف ارتقاء سلامتی افراد جامعه با به کار گیری اصول و قوانین حاکم بر تغذیه سالم تاسیس شده است. شرکت بارنگ در مسیر خود از مشاوره متخصصین تغذیه برتر کشور و همچنین چند تن از افراد صاحبنظر در علم آشپزی استفاده می نماید. در این شرکت روش هایی جهت پیاده سازی علم تغذیه سالم ابداع و آموزش داده می شود.
نگاه ما به زندگی در شعار ما خلاصه می شود: ";
        $text .= "\n";
        $text .= "\n";
        $text .= "Enjoy your healthy Food";
        $text .= "\n";
        $text .= "از غذای سالمت لذت ببر ...";
        $text .= "\n";
        $text .= "با ما در ارتباط باشید";
        $text .= "\n";
        $text .= "✅  baranagfood.com";
        $text .= "\n";
        $text .= "- - - - - - - - - - - - -";
        $text .= "\n";
        $text .= "📩  info@barangfood.com";
        $text .= "\n";
        $text .= "📞 021-22035976";
        $text .= "\n";
        $text .= "📢  @barangfood | بارنگ فود";

        $text = urlencode($text);
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'keyboard' => $this->keyboard->welcomeBottom(),
                'resize_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);
    }

    public function calorie()
    {
        $chatId = $this->request->message->chat->id;
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $chatId,
            'text' => 'جنسیت خود را انتخاب کنید',
            'reply_markup' => [
                'keyboard' => $this->keyboard->genderBottom(),
                'resize_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);
    }

    public function bmi()
    {
        $chatId = $this->request->message->chat->id;
        $userId = $this->request->message->from->username;

        $setBmi = $this->userModel()->setBmi($userId, $chatId);

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $chatId,
            'text' => 'قد خود را به سانتی متر وارد کنید:',
            'reply_markup' => [
                'keyboard' => $this->keyboard->backBottom(),
                'resize_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);
    }

    public function gender()
    {
        $chatId = $this->request->message->chat->id;
        $userId = $this->request->message->from->username;
        if ($this->request->message->text == '👨‍⚖️ مرد') {
            $gender = 0;
        } else {
            $gender = 1;
        }
        $setGender = $this->userModel();
        if ($setGender->setGender($userId, $gender, $chatId)) {
            $result = [
                'method' => 'sendMessage',
                'chat_id' => $chatId,
                'text' => 'قد خود را به سانتی متر وارد کنید:',
                'reply_markup' => [
                    'keyboard' => $this->keyboard->backBottom(),
                    'resize_keyboard' => true
                ]
            ];
        } else {
            $result = [
                'method' => 'sendMessage',
                'chat_id' => $chatId,
                'text' => 'دوباره تلاش کنید.',
                'reply_markup' => [
                    'keyboard' => $this->keyboard->genderBottom(),
                    'resize_keyboard' => true
                ]
            ];
        }

        $this->io->setResponse($result);
    }

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

    public function activity()
    {
        $chatId = $this->request->message->chat->id;
        $userId = $this->request->message->from->username;
        if ($this->request->message->text == 'بدون فعالیت') {
            $activity = 0;
        } elseif ($this->request->message->text == 'کم فعالیت') {
            $activity = 1;
        } elseif ($this->request->message->text == 'فعالیت متوسط') {
            $activity = 2;
        } elseif ($this->request->message->text == 'فعالیت زیاد') {
            $activity = 3;
        } else {
            $activity = 4;
        }
        $setActivity = $this->userModel();
        if ($setActivity->setActivity($activity, $chatId)) {
            $getUserInfo = $this->userModel();
            $getUserInfo = $getUserInfo->getUser($chatId);
            $getUserInfo = $getUserInfo[0];
            $height2 = (($getUserInfo['height'] / 100) * ($getUserInfo['height'] / 100));
            $bmi = $getUserInfo['weight'] / $height2;
            $bestWeight = $height2 * 23;
            if ($bmi < 16.5) {
                $bmiMessage = 'شما دچار کمبود وزن شدید هستید';
                $changeType= "به میزان کالری پیشنهادی روزانه تان اضافه نمایید.";
            } elseif (16.5 <= $bmi && $bmi < 18.5) {
                $bmiMessage = 'شما دچار کمبود وزن هستید';
                $changeType= "به میزان کالری پیشنهادی روزانه تان اضافه نمایید.";
            } elseif (18.5 <= $bmi && $bmi < 25) {
                $bmiMessage = 'وزن شما عادی می باشد';
                $changeType= "از میزان کالری پیشنهادی روزانه تان کسر نمایید.";
            } elseif (25 <= $bmi && $bmi < 30) {
                $bmiMessage = 'شما دچار اضافه وزن هستید';
                $changeType= "از میزان کالری پیشنهادی روزانه تان کسر نمایید.";
            } elseif (30 <= $bmi && $bmi < 35) {
                $bmiMessage = 'شما دچار چاقی کلاس یک هستید';
                $changeType= "از میزان کالری پیشنهادی روزانه تان کسر نمایید.";
            } elseif (35 <= $bmi && $bmi < 40) {
                $bmiMessage = 'شما دچار چاقی کلاس دو هستید';
                $changeType= "از میزان کالری پیشنهادی روزانه تان کسر نمایید.";
            } elseif (40 <= $bmi) {
                $bmiMessage = 'شما دچار چاقی کلاس سه هستید';
                $changeType= "از میزان کالری پیشنهادی روزانه تان کسر نمایید.";
            }

            $text = "⭕️ بی ام آی شما برابر " . round($bmi) . " میباشد.";
            $text .= "\n";
            $text .= "\n";
            $text .= '🚹 '.$bmiMessage . " ،";
            $text .= "وزن ایده آل برای شما " . number_format($bestWeight, 2) . " کیلوگرم میباشد.";



            if ($getUserInfo['gender'] == 0) {
                $bmr = 66 + (13.7 * (int)$getUserInfo['weight']) + (5 * (int)$getUserInfo['height'] / 100) - (6.8 * (int)$getUserInfo['age']);
                $breakFast = number_format($bmr * 33 / 100 , 2);
                $lunch = number_format($bmr * 43 / 100,2);
                $dinner = number_format($bmr * 24 / 100,2);
                $breakFastLose = number_format(($bmr-500) * 33 / 100 , 2);
                $lunchLose = number_format(($bmr-500) * 43 / 100,2);
                $dinnerLose = number_format(($bmr-500) * 24 / 100,2);

                $discountCount = "💢 تقسیم بندی بر اساس انرژی مورد نیاز در حالت عادی : ";
                $discountCount .= "\n";
                $discountCount .= "\n";
                $discountCount .= "🍧 صبحانه : ";
                $discountCount .= $breakFast;
                $discountCount .= "\n";
                $discountCount .= "🍮 ناهار : ";
                $discountCount .= $lunch;
                $discountCount .= "\n";
                $discountCount .= "🍵 شام : ";
                $discountCount .= $dinner;
                $discountCount .= "\n";
                $discountCount .= "\n";
                $discountCount .= "💢 تقسیم بندی بر اساس انرژی مورد نیاز در صورت درخواست برای کاهش وزن : ";
                $discountCount .= "\n";
                $discountCount .= "\n";
                $discountCount .= "🍧 صبحانه : ";
                $discountCount .= $breakFastLose;
                $discountCount .= "\n";
                $discountCount .= "🍮 ناهار : ";
                $discountCount .= $lunchLose;
                $discountCount .= "\n";
                $discountCount .= "🍵 شام : ";
                $discountCount .= $dinnerLose;
                $textFirst = "📈 مقدار MRM شما برابر " . round($bmr) . "می باشد.";
                $textFirst .= "\n";
                $textFirst .= $text;
                $textFirst .= "و انرژی پیشنهادی برای شما ".number_format($bmr,2)." کالری می باشد. در صورت درخواست شما برای کاهش وزن (بدون ورزش حدود نیم کیلو در هفته و با ورزش حدود یک کیلو در هفته)500 کالری ".$changeType."";
                $textFirst .= "\n";
                $textFirst .= $discountCount;
                $textFirst .= "\n";
                $textFirst .= "برای دریافت بشقاب های سلامت به لینک زیر مراجعه نمایید.";
                $textFirst .= "\n";
                $textFirst .= "✅  baranagfood.com";
                $textFirst = urlencode($textFirst);
                $result = [
                    'method' => 'sendMessage',
                    'chat_id' => $chatId,
                    'text' => $textFirst,
                    'parse_mode' => 'HTML',
                    'reply_markup' => [
                        'keyboard' => $this->keyboard->backBottom(),
                        'resize_keyboard' => true
                    ]
                ];
            } else {
                $bmr = 655 + (9.6 * (int)$getUserInfo['weight']) + (1.7 * (int)$getUserInfo['height'] / 100) - (4.7 * (int)$getUserInfo['age']);
                $breakFast = number_format($bmr * 33 / 100 , 2);
                $lunch = number_format($bmr * 43 / 100,2);
                $dinner = number_format($bmr * 24 / 100,2);
                $breakFastLose = number_format(($bmr-500) * 33 / 100 , 2);
                $lunchLose = number_format(($bmr-500) * 43 / 100,2);
                $dinnerLose = number_format(($bmr-500) * 24 / 100,2);

                $discountCount = "💢 تقسیم بندی بر اساس انرژی مورد نیاز در حالت عادی : ";
                $discountCount .= "\n";
                $discountCount .= "\n";
                $discountCount .= "🍧 صبحانه : ";
                $discountCount .= $breakFast;
                $discountCount .= "\n";
                $discountCount .= "🍮 ناهار : ";
                $discountCount .= $lunch;
                $discountCount .= "\n";
                $discountCount .= "🍵 شام : ";
                $discountCount .= $dinner;
                $discountCount .= "\n";
                $discountCount .= "\n";
                $discountCount .= "💢 تقسیم بندی بر اساس انرژی مورد نیاز در صورت درخواست برای کاهش وزن : ";
                $discountCount .= "\n";
                $discountCount .= "\n";
                $discountCount .= "🍧 صبحانه : ";
                $discountCount .= $breakFastLose;
                $discountCount .= "\n";
                $discountCount .= "🍮 ناهار : ";
                $discountCount .= $lunchLose;
                $discountCount .= "\n";
                $discountCount .= "🍵 شام : ";
                $discountCount .= $dinnerLose;
                $textFirst = "📈 مقدار BMR شما برابر " . round($bmr) . "می باشد.";
                $textFirst .= "\n";
                $textFirst .= $text;
                $textFirst .= "و انرژی پیشنهادی برای شما ".number_format($bmr,2)." کالری می باشد. در صورت درخواست شما برای کاهش وزن (بدون ورزش حدود نیم کیلو در هفته و با ورزش حدود یک کیلو در هفته)500 کالری از میزان کالری پیشنهادی روزانه تان کسر نمایید.";
                $textFirst .= "\n";
                $textFirst .= $discountCount;
                $textFirst .= "\n";
                $textFirst .= "برای دریافت بشقاب های سلامت به لینک زیر مراجعه نمایید.";
                $textFirst .= "\n";
                $textFirst .= "✅  baranagfood.com";
                $textFirst = urlencode($textFirst);
                $result = [
                    'method' => 'sendMessage',
                    'chat_id' => $chatId,
                    'text' => $textFirst,
                    'parse_mode' => 'HTML',
                    'reply_markup' => [
                        'keyboard' => $this->keyboard->backBottom(),
                        'resize_keyboard' => true
                    ]
                ];
            }
        } else {
            $result = [
                'method' => 'sendMessage',
                'chat_id' => $chatId,
                'text' => 'دوباره تلاش کنید.',
                'reply_markup' => [
                    'keyboard' => $this->keyboard->activityBottom(),
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

    public function backPrevious()
    {
        $userId = $this->request->message->from->id;

        $this->previousState($userId);
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