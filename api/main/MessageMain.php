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
        $text = $this->request->message->text;
        $userId = $this->request->message->from->id;
        $firstName = $this->request->message->from->first_name;
        $lastName = $this->request->message->from->last_name;

        $this->userModel()->register($userId, $firstName, $lastName);

        $this->redis->del("cart" . $userId);
        $chatId = $this->request->message->chat->id;
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $chatId,
            'text' => 'خوش آمدید.',
            'reply_markup' => [
                'keyboard' => $this->keyboard->mainBottom(),
                'resize_keyboard' => true
            ]
        ];

        $this->userModel()->setState($userId, UserModel::STATUS_START);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_START, $text);

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
        $stmt = $conn->prepare("SELECT category.cat_Name FROM product 
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
        $stmt2 = $conn->prepare("SELECT category.cat_Name FROM product 
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
            array_push($captions, urlencode($value['pro_Name'] . "\n". "قیمت:" . $value['pro_LastPrice']));
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

    public function messageOther()
    {
        $userId = $userId = $this->request->message->from->id;
        $checkUserState = $this->stateMessage($userId);
        if ($checkUserState == false) {
            $result = [
                'method' => 'sendMessage',
                'chat_id' => $this->request->message->chat->id,
                'text' => 'دستور وارد شده صحیح نیست.',
            ];

            $userId = $this->request->message->from->id;

            $text = $this->request->message->text;

            $this->userModel()->setState($userId, UserModel::STATUS_OTHER);
            $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_OTHER, $text);

            $this->io->setResponse($result);
        }
    }

    public function stateMessage(string $userId)
    {
        $state = $this->userModel()->getState($userId);

        switch ($state) {
            case UserModel::STATUS_SUPPORT :
                $text = $this->request->message->text;
                $this->supportModel()->addSupport($text, $userId);
                return true;
            case UserModel::STATUS_LIST_BRAND :
                $this->showBrand();
                return true;
            case UserModel::STATUS_SHOW_BRAND :
                $this->showCategory();
                return true;
            case UserModel::STATUS_SHOW_CATEGORY :
                $this->showCategory();
                return true;
            case UserModel::STATUS_FINAL_CONFIRM :
                $this->getName();
                return true;
            case UserModel::STATUS_FINAL_CONFIRM_GET_NAME :
                $this->getPhone();
                return true;
            case UserModel::STATUS_FINAL_CONFIRM_GET_PHONE :
                $this->getAddress();
                return true;
            case UserModel::STATUS_FINAL_CONFIRM_GET_ADDRESS :
                $this->getZipCode();
                return true;
            case UserModel::STATUS_FINAL_CONFIRM_GET_ADDRESS :
                $this->finished();
                return true;

            default:
                return false;
        }
    }

    public function previousState(string $userId)
    {
        $currentState = $this->userModel()->getState($userId);

        switch ($currentState) {
            case UserModel::STATUS_SHOW_BRAND :
                $this->listBrand();
                return true;
            case UserModel::STATUS_SHOW_CATEGORY:
                $this->listBrand();
                return true;
            case UserModel::STATUS_LIST_PRODUCT:
                $this->showCategory();
                return true;
            case UserModel::STATUS_SHOW_PRODUCT:
                $this->listBrand();
                return true;

            default :
                $this->start();
                return true;
        }
    }

    public function showCart()
    {
        $text = $this->request->message->text;
        $userId = $this->request->message->from->id;
        $cart = json_decode($this->redis->get("cart" . $userId), true);

        $factorText = '';
        $counter = 1;
        $finalPrice = 0;
        foreach ($cart as $key => $value) {
            $factorText .=  $counter . ":" . $value['name'] . "\n" . "قیمت:" . $value['price'] . "\n" . "تعداد:" . $value['count'];
            $factorText .= "\n";
            $factorText .= "---------------------------------------------------------";
            $factorText .= "\n";
            $finalPrice += $value['price'] * $value['count'];
            $counter++;
        }
        $factorText .= "\n";
        $factorText .= "مبلغ قابل پرداخت:" . $finalPrice;


        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' =>  urlencode($factorText),
            'reply_markup' => [
                'keyboard' => $this->keyboard->showCartBottom(),
                'resize_keyboard' => true
            ]
        ];

        $this->userModel()->setState($userId, UserModel::STATUS_SHOW_CART);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_SHOW_CART, $text);

        $this->io->setResponse($result);
    }

    public function setComment()
    {

    }

    public function setStar()
    {

    }

    public function addToCart()
    {
        $text = $this->request->message->text;
        $userId = $userId = $this->request->message->from->id;
        $ProId = $this->redis->get("proId" . $userId);
        $conn = $this->container->get('pdo');

        $stmt = $conn->prepare("SELECT pro_Name, pro_LastPrice FROM product WHERE pro_ID =:proId");
        $stmt->bindParam('proId', $ProId);
        $stmt->execute();
        $res = $stmt->fetchAll();


        if ($this->redis->get("cart" . $userId) != null || $this->redis->get("cart" . $userId) != '')
            $cart = json_decode($this->redis->get("cart" . $userId), true);
        else
            $cart = [];


        if (!empty($cart)) {
            $cart += [$ProId => ['count' => 1, 'price' => $res[0]['pro_LastPrice'], 'name' => $res[0]['pro_Name']]];
        } else {
            $cart = [$ProId => ['count' => 1, 'price' => $res[0]['pro_LastPrice'], 'name' => $res[0]['pro_Name']]];
        }

        $this->redis->set("cart" . $userId, json_encode($cart));

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $userId,
            'text' => 'محصول شما به سبد خرید اضافه شد.',
            'reply_markup' => [
                'keyboard' => $this->keyboard->showCartBottom(),
                'resize_keyboard' => true
            ]
        ];

        $this->userModel()->setState($userId, UserModel::STATUS_ADDING_TO_CART);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_ADDING_TO_CART, $text);

        $this->io->setResponse($result);
    }

    public function addAnotherProduct()
    {
        $text = $this->request->message->text;
        $userId = $this->request->message->from->id;

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $userId,
            'text' => 'شما میتوانید محصولات دیگری را به سبد خود اضافه کنید!',
            'reply_markup' => [
                'keyboard' => $this->keyboard->mainBottom(),
                'resize_keyboard' => true
            ]
        ];

        $this->userModel()->setState($userId, UserModel::STATUS_START);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_START, $text);
        $this->io->setResponse($result);
    }

    public function previousStep()
    {
        $userId = $this->request->message->from->id;
        $currentState = $this->userModel()->getState($userId);
        switch ($currentState) {
            case UserModel::STATUS_FINAL_CONFIRM_GET_PHONE :
                $this->getName();
                return true;
            case UserModel::STATUS_FINAL_CONFIRM_GET_ADDRESS :
                $this->getPhone();
                return true;
            case UserModel::STATUS_FINAL_CONFIRM_GET_ZIPCODE :
                $this->getAddress();
                return true;
            default :
                return false;
        }

        $this->userModel()->setState($userId, UserModel::STATUS_START);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_START, $text);
        $this->io->setResponse($result);
    }

    public function finalSubmit()
    {
        $text = $this->request->message->text;
        $userId = $this->request->message->from->id;
        $cart = json_decode($this->redis->get("cart" . $userId), true);

        $conn = $this->container->get('pdo');
        $stmt = $conn->prepare("INSERT INTO telegram_user(telegramChatId) VALUES(:telegramChatId)");
        $stmt->bindParam('telegramChatId', $userId);
        $stmt->execute();
        $last_Id = $conn->lastInsertId();

        foreach ($cart as $key => $value) {
            $stmt = $conn->prepare("INSERT INTO orders(userTelegramId, productId, price, count, approvalStatus) VALUES(
                  :userTelegramId, :productId, :price, :count, 'ثبت شده')");
            $stmt->bindParam('userTelegramId', $last_Id);
            $stmt->bindParam('productId', $key);
            $stmt->bindParam('price', $value['price']);
            $stmt->bindParam('count', $value['count']);
            $stmt->execute();
        }

        $detText = 'لطفا نام و نام خانوادگی خودرا وارد کنید';
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' => $detText,
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'hide_keyboard' => true,
            ],
        ];

        $this->userModel()->setState($userId, UserModel::STATUS_FINAL_CONFIRM_GET_NAME);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_FINAL_CONFIRM_GET_NAME, $text);

        $this->io->setResponse($result);
    }

    public function getName()
    {
        $text = $this->request->message->text;
        $userId = $userId = $this->request->message->from->id;

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' => 'لطفا نام و نام خانوادگی خودرا وارد کنید',
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'hide_keyboard' => true,
            ],
        ];

        $this->userModel()->setState($userId, UserModel::STATUS_FINAL_CONFIRM_GET_NAME);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_FINAL_CONFIRM_GET_NAME, $text);

        $this->io->setResponse($result);

    }

    public function getPhone()
    {
        $text = $this->request->message->text;
        $userId = $userId = $this->request->message->from->id;

        $conn = $this->container->get('pdo');
        $stmt = $conn->prepare("UPDATE telegram_user SET name= :name WHERE telegramChatId=:telegramChatId");
        $stmt->bindParam('telegramChatId', $userId);
        $stmt->bindParam('name', $text);
        $stmt->execute();

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' => 'لطفا شماره تماس خودرا وارد کنید',
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'keyboard' => $this->keyboard->previousStepBottom() ,
            ],
        ];

        $this->userModel()->setState($userId, UserModel::STATUS_FINAL_CONFIRM_GET_PHONE);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_FINAL_CONFIRM_GET_PHONE, $text);

        $this->io->setResponse($result);

    }

    public function getAddress()
    {
        $text = $this->request->message->text;
        $userId = $userId = $this->request->message->from->id;

        $conn = $this->container->get('pdo');
        $stmt = $conn->prepare("UPDATE telegram_user SET phone= :phone WHERE telegramChatId=:telegramChatId");
        $stmt->bindParam('telegramChatId', $userId);
        $stmt->bindParam('phone', $text);
        $stmt->execute();

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' => 'لطفا آدرس خودرا وارد کنید',
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'keyboard' => $this->keyboard->previousStepBottom() ,
            ],
        ];

        $this->userModel()->setState($userId, UserModel::STATUS_FINAL_CONFIRM_GET_ADDRESS);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_FINAL_CONFIRM_GET_ADDRESS, $text);

        $this->io->setResponse($result);
    }

    public function getZipCode()
    {
        $text = $this->request->message->text;
        $userId = $userId = $this->request->message->from->id;

        $conn = $this->container->get('pdo');
        $stmt = $conn->prepare("UPDATE telegram_user SET zipCode= :zipCode WHERE telegramChatId=:telegramChatId");
        $stmt->bindParam('telegramChatId', $userId);
        $stmt->bindParam('zipCode', $text);
        $stmt->execute();

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' => 'لطفا کدپستی خودرا وارد کنید',
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'keyboard' => $this->keyboard->previousStepBottom() ,
            ],
        ];

        $this->userModel()->setState($userId, UserModel::STATUS_FINAL_CONFIRM_GET_ZIPCODE);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_FINAL_CONFIRM_GET_ZIPCODE, $text);

        $this->io->setResponse($result);
    }
    public function finished()
    {
        $text = $this->request->message->text;
        $userId = $userId = $this->request->message->from->id;

        $conn = $this->container->get('pdo');
        $stmt = $conn->prepare("UPDATE telegram_user SET address= :address WHERE telegramChatId=:telegramChatId");
        $stmt->bindParam('telegramChatId', $userId);
        $stmt->bindParam('address', $text);
        $stmt->execute();

        $cart = json_decode($this->redis->get("cart" . $userId), true);
        $factorText = '';
        $counter = 1;
        $finalPrice = 0;
        foreach ($cart as $key => $value) {
            $factorText .=  $counter . ":" . $value['name'] . "\t\t" . "قیمت:" . $value['price'] . "\t\t" . "تعداد:" . $value['count'];
            $factorText .= "\n";
            $finalPrice += $value['price'] * $value['count'];
            $counter++;
        }
        $factorText .= "\n";
        $factorText .= "مبلغ قابل پرداخت:" . $finalPrice;

        $detailsText = 'از خرید شما متشکریم :)';
        $detailsText .= "\n\n";
        $detailsText .= $factorText;
        $detailsText .= "\n\n";
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' => urlencode($detailsText),
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'keyboard' => $this->keyboard->previousStepBottom(),
            ],
        ];
        $this->redis->del("cart" . $userId);
        $this->userModel()->setState($userId, UserModel::STATUS_FINISHED);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_FINISHED, $text);

        $this->io->setResponse($result);
    }
}