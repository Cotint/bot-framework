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
use model\SupportModel;
use model\UserHistoryModel;
use model\UserModel;

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
    private function supportModel(): SupportModel
    {
        return $this->container->get('supportModel');
    }

    /**
     * @return UserHistoryModel
     */
    private function userHistoryModel(): UserHistoryModel
    {
        return $this->container->get('userHistoryModel');
    }

    public function start()
    {
        $chatId = $this->request->message->chat->id;
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $chatId,
            'text' => 'خوش آمدید.',
            'reply_markup' => [
                'keyboard' => $this->keyboard->welcomeBottom(),
                'resize_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);
    }


    public function help()
    {
        $chatId = $this->request->message->chat->id;
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $chatId,
            'text' => 'راهنما',
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
        $text = "مجموعه بارنگ فود با هدف ارتقاء سلامتی افراد جامعه با به کار گیری اصول و قوانین حاکم بر تغذیه سالم تاسیس شده است. شرکت بارنگ در مسیر خود از مشاوره متخصصین تغذیه برتر کشور و همچنین چند تن از افراد صاحبنظر در علم آشپزی استفاده می نماید. در این شرکت روش هایی جهت پیاده سازی علم تغذیه سالم ابداع و آموزش داده می شود.
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
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $chatId,
            'text' => 'قد خود را به سانتی متر وارد کنید',
            'reply_markup' => [
                'remove_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);
    }

    public function gender()
    {
        $chatId = $this->request->message->chat->id;
        $userId = $this->request->message->from->username;
        if ($this->request->message->text == 'مرد') {
            $gender = 0;
        } else {
            $gender = 1;
        }
        $setGender = $this->userModel();
        if ($setGender->setGender($userId, $gender, $chatId)) {
            $result = [
                'method' => 'sendMessage',
                'chat_id' => $chatId,
                'text' => 'قد خود را به سانتی متر وارد کنید',
                'reply_markup' => [
                    'remove_keyboard' => true
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
            $result = [
                'method' => 'sendMessage',
                'chat_id' => $chatId,
                'text' => 'وضعیت جسمانی خود را مشخص کنید',
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
            if ($getUserInfo['gender'] == 0) {
                $bmr = 66 + (13.7 * (int)$getUserInfo['weight']) + (5 * (int)$getUserInfo['height'] / 100) - (6.8 * (int)$getUserInfo['age']);
                $text = 'MRM شما برابر ' . $bmr . 'می باشد';
                $result = [
                    'method' => 'sendMessage',
                    'chat_id' => $chatId,
                    'text' => $text,
                    'reply_markup' => [
                        'remove_keyboard' => true
                    ]
                ];
            } else {
                $bmr =  655 + (9.6 * (int)$getUserInfo['weight']) + (1.7 * (int)$getUserInfo['height'] / 100) - (4.7 * (int)$getUserInfo['age']);
                $text = 'BMR شما برابر ' . $bmr . 'می باشد';
                $result = [
                    'method' => 'sendMessage',
                    'chat_id' => $chatId,
                    'text' => $text,
                    'reply_markup' => [
                        'remove_keyboard' => true
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

    public function messageOther()
    {
        $chatId = $this->request->message->chat->id;
        $text = $this->request->message->text;
        if (!is_numeric($text)) {
            $result = [
                'method' => 'sendMessage',
                'chat_id' => $chatId,
                'text' => 'لطفا عدد وارد کنید.',
                'reply_markup' => [
                    'remove_keyboard' => true
                ]
            ];

            $this->io->setResponse($result);
            exit;
        }
        $state = $this->userModel()->getState($chatId);
        $state = $state[0]['last_state'];
        if (isset($state) && $state != null) {
            switch ($state) {
                case '1':
                    $getBmi = $this->userModel();
                    $getBmi = $getBmi->getState($chatId);
                    $getBmi = $getBmi[0]['bmi'];
                    if ($getBmi == 1){
                        $setHeight = $this->userModel();
                        if ($setHeight->setHeight($text, $chatId)) {
                            $result = [
                                'method' => 'sendMessage',
                                'chat_id' => $chatId,
                                'text' => 'وزن خود را به کیلوگرم وارد کنید',
                                'reply_markup' => [
                                    'resize_keyboard' => true
                                ]
                            ];
                        } else {
                            $result = [
                                'method' => 'sendMessage',
                                'chat_id' => $chatId,
                                'text' => 'دوباره تلاش کنید.',
                                'reply_markup' => [
                                    'resize_keyboard' => true
                                ]
                            ];
                        }
                    }else{
                        $setHeight = $this->userModel();
                        if ($setHeight->setHeightBmi($text, $chatId,$this->request->message->from->username)) {
                            $result = [
                                'method' => 'sendMessage',
                                'chat_id' => $chatId,
                                'text' => 'وزن خود را به کیلوگرم وارد کنید',
                                'reply_markup' => [
                                    'resize_keyboard' => true
                                ]
                            ];
                        } else {
                            $result = [
                                'method' => 'sendMessage',
                                'chat_id' => $chatId,
                                'text' => 'دوباره تلاش کنید.',
                                'reply_markup' => [
                                    'resize_keyboard' => true
                                ]
                            ];
                        }
                    }
                    break;
                case '2':
                    $setWeight = $this->userModel();
                    $getBmi = $this->userModel();
                    $getBmi = $getBmi->getState($chatId);
                    $getBmi = $getBmi[0]['bmi'];
                    if ($getBmi == 1){
                        if ($setWeight->setWeightBmi($text, $chatId)) {
                            $getUserBmi = $this->userModel()->getUserBmi($chatId);
                            $getUserBmi = $getUserBmi[0];
                            $bmi = $getUserBmi['weight'] / (($getUserBmi['height']/100)*($getUserBmi['height']/100));
                            $result = [
                                'method' => 'sendMessage',
                                'chat_id' => $chatId,
                                'text' => 'بی ام آی شما برابر '.$bmi.' میباشد',
                                'reply_markup' => [
                                    'resize_keyboard' => true
                                ]
                            ];
                        } else {
                            $result = [
                                'method' => 'sendMessage',
                                'chat_id' => $chatId,
                                'text' => 'دوباره تلاش کنید.',
                                'reply_markup' => [
                                    'resize_keyboard' => true
                                ]
                            ];
                        }
                    }else{
                        if ($setWeight->setWeight($text, $chatId)) {
                            $result = [
                                'method' => 'sendMessage',
                                'chat_id' => $chatId,
                                'text' => 'سن خود را وارد کنید',
                                'reply_markup' => [
                                    'resize_keyboard' => true
                                ]
                            ];
                        } else {
                            $result = [
                                'method' => 'sendMessage',
                                'chat_id' => $chatId,
                                'text' => 'دوباره تلاش کنید.',
                                'reply_markup' => [
                                    'resize_keyboard' => true
                                ]
                            ];
                        }
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
                                'resize_keyboard' => true
                            ]
                        ];
                    }
                    break;
                case '6':
                    $this->start();
                    break;
                default :
                    $result = [
                        'method' => 'sendMessage',
                        'chat_id' => $chatId,
                        'text' => 'دستور وارد شده صحیح نیست.',
                        'reply_markup' => [
                            'resize_keyboard' => true
                        ]
                    ];
                    break;
            }

            $this->io->setResponse($result);
        } else {
            $this->start();
        }
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

    public function consult()
    {
        $text = $this->request->message->text;

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' => '<a href="https://telegram.me/mohammadiisaeed">برای ارتباط با مشاور لطفا کلیک کنید</a>',
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'keyboard' => $this->keyboard->back(),
                'resize_keyboard' => true
            ]
        ];

        $userId = $this->request->message->from->id;

        $this->userModel()->setState($userId, UserModel::STATUS_CONSULT);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_CONSULT, $text);

        $this->io->setResponse($result);
    }

    public function shopketAds()
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
            'reply_markup' => [
                'keyboard' => $this->keyboard->back(),
                'resize_keyboard' => true
            ]
        ];

        $userId = $this->request->message->from->id;

        $requestText = $this->request->message->text;

        $this->userModel()->setState($userId, UserModel::STATUS_SHOPKET_ADS);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_SHOPKET_ADS, $requestText);

        $this->io->setResponse($result);
    }

    public function aboutBot()
    {
        $text = $this->request->message->text;

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' => 'یک گزینه را انتخاب کنید...',
            'reply_markup' => [
                'keyboard' => $this->keyboard->aboutBotBottom(),
                'resize_keyboard' => true
            ]
        ];

        $userId = $this->request->message->from->id;

        $this->userModel()->setState($userId, UserModel::STATUS_ABOUT_BOT);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_ABOUT_BOT, $text);

        $this->io->setResponse($result);
    }

    public function about()
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

        $this->userModel()->setState($userId, UserModel::STATUS_ABOUT);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_ABOUT, $requestText);

        $this->io->setResponse($result);
    }

    public function contact()
    {
        $text = "لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.";
        $text .= "\n";
        $text .= "\n";
        $text .= "\n";
        $text .= "چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد";

        $result = [
            'method' => 'sendVenue',
            'chat_id' => $this->request->message->chat->id,
            'latitude' => 35.78819,
            'longitude' => 51.45983810000007,
            'title' => 'آدرس',
            'address' => 'اختیاره جنوبی',
            'reply_markup' => [
                'resize_keyboard' => true
            ]
        ];

        $userId = $userId = $this->request->message->from->id;

        $requestText = $this->request->message->text;

        $this->userModel()->setState($userId, UserModel::STATUS_CONTACT);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_CONTACT, $requestText);

        $this->io->setResponse($result);
    }

    public function buyInfo()
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

        $this->userModel()->setState($userId, UserModel::STATUS_BUY_INFO);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_BUY_INFO, $requestText);

        $this->io->setResponse($result);
    }

    public function shipmentAbout()
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

        $userId = $this->request->message->from->id;

        $this->userModel()->setState($userId, UserModel::STATUS_SHIPMENT_ABOUT);

        $requestText = $this->request->message->text;

        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_SHIPMENT_ABOUT, $requestText);

        $this->io->setResponse($result);
    }

    public function refundAbout()
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

        $userId = $this->request->message->from->id;

        $requestText = $this->request->message->text;

        $this->userModel()->setState($userId, UserModel::STATUS_REFUND_ABOUT);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_REFUND_ABOUT, $requestText);

        $this->io->setResponse($result);
    }

    public function termsConditions()
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

        $this->userModel()->setState($userId, UserModel::STATUS_TERMS_CONDITIONS);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_TERMS_CONDITIONS, $requestText);

        $this->io->setResponse($result);
    }

    public function instagram()
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

        $this->userModel()->setState($userId, UserModel::STATUS_INSTAGRAM);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_INSTAGRAM, $requestText);

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

    public function showBrand()
    {
        $text = $this->request->message->text;

        /** @var PDOConnection $conn */
        $conn = $this->container->get('pdo');

        /** @var PDOStatement $stmt */
        $stmt = $conn->prepare("SELECT DISTINCT category.cat_Name FROM product 
JOIN proCat ON proCat.pro_ID = product.pro_ID 
JOIN category ON proCat.cat_ID = category.cat_ID
JOIN brands ON brands.bra_ID = product.pro_BraID 
WHERE brands.bra_Name = :text AND category.cat_parentID = 0"
        );

        $stmt->bindParam('text', $text);

        $stmt->execute();

        $res = $stmt->fetchAll();

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' => 'یک گزینه را انتخاب کنید...',
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'keyboard' => $this->keyboard->listCategoryBottom($res),
                'resize_keyboard' => true
            ]
        ];

        $userId = $userId = $this->request->message->from->id;

        $this->userModel()->setState($userId, UserModel::STATUS_SHOW_BRAND);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_SHOW_BRAND, $text);

        $this->io->setResponse($result);
    }

    public function showCategory()
    {
        $text = $this->request->message->text;

        $userId = $userId = $this->request->message->from->id;

        $history = $this->userHistoryModel()->getLastState($userId, UserHistoryModel::STATUS_SHOW_BRAND);

        $lastBrand = $history->text;

        /** @var PDOConnection $conn */
        $conn = $this->container->get('pdo');

        /** @var PDOStatement $stmt2 */
        $stmt = $conn->prepare("SELECT cat_ID FROM category WHERE cat_Name = :catName");

        $stmt->bindParam('catName', $text);

        $stmt->execute();

        $catRes = $stmt->fetchAll();

        $catId = $catRes[0]['cat_ID'];

        /** @var PDOStatement $stmt2 */
        $stmt2 = $conn->prepare("SELECT DISTINCT category.cat_Name FROM product 
JOIN proCat ON proCat.pro_ID = product.pro_ID 
JOIN category ON proCat.cat_ID = category.cat_ID
JOIN brands ON brands.bra_ID = product.pro_BraID 
WHERE brands.bra_Name = :lastBrand AND category.cat_parentID = :catId"
        );

        $stmt2->bindParam('lastBrand', $lastBrand);
        $stmt2->bindParam('catId', $catId);

        $stmt2->execute();

        $res = $stmt2->fetchAll();

        if (empty($res)) {
            $this->listProduct();
            return;
        }

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' => 'یک گزینه را انتخاب کنید...',
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'keyboard' => $this->keyboard->listCategoryBottom($res),
                'resize_keyboard' => true
            ]
        ];

        $userId = $this->request->message->from->id;

        $this->userModel()->setState($userId, UserModel::STATUS_SHOW_CATEGORY);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_SHOW_CATEGORY, $text);

        $this->io->setResponse($result);
    }

    public function listProduct()
    {
        $text = $this->request->message->text;

        $userId = $userId = $this->request->message->from->id;

        $conn = $this->container->get('pdo');

        $stmt = $conn->prepare("SELECT product.pro_ID, product.pro_Name, product.pro_LastPrice FROM product 
            JOIN proCat ON proCat.pro_ID = product.pro_ID 
            JOIN category ON proCat.cat_ID = category.cat_ID
            JOIN brands ON brands.bra_ID = product.pro_BraID 
            WHERE category.cat_Name = :text");

        $stmt->bindParam('text', $text);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $captions = $keyboard = [];


        foreach ($res as $key => $value) {
            array_push($captions, urlencode($value['pro_Name'] . "\n" . "قیمت:" . $value['pro_LastPrice']));
            array_push($keyboard, ['text' => 'مشاهده محصول', "callback_data" => $value['pro_ID']]);
        }


        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' => $captions,
            'keyboard' => $keyboard,
        ];
        $this->userModel()->setState($userId, UserModel::STATUS_LIST_PRODUCT);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_LIST_PRODUCT, $text);

        $this->io->setResponse($result);
    }


    public function stateMessage(string $userId)
    {
    }

    public function previousState(string $userId)
    {
    }

    public function showCart()
    {
    }

    public function setComment()
    {

    }

    public function setStar()
    {

    }

    public function addToCart()
    {
    }

    public function getCount()
    {
    }

    public function selectProductForDelete()
    {
    }

    public function cheapest()
    {
    }

    public function bestSelling()
    {
    }

    public function newest()
    {
    }

    public function mostPopular()
    {
    }

    public function addAnotherProduct()
    {
    }

    public function previousStep()
    {
    }

    public function finalSubmit()
    {
    }

    public function getName()
    {
    }

    public function getPhone()
    {
    }

    public function getAddress()
    {
    }

    public function getZipCode()
    {
    }

    public function finished()
    {
    }
}