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
        $userId = $userId = $this->request->message->from->id;

        $this->previousState($userId);
    }

    public function listBrand()
    {
        $text = $this->request->message->text;

        /** @var PDOConnection $conn */
        $conn = $this->container->get('pdo');

        /** @var PDOStatement $stmt */
        $stmt = $conn->prepare("SELECT bra_Name FROM brands");

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
            'method' => 'sendPhoto',
            'chat_id' => $this->request->message->chat->id,
            'photo' => 'http://0f08a1d8.ngrok.io/public/image/support.jpg',
            'caption' => 'اگر انتقاد یا پیشنهادی دارید یا اضافه شدن بخش جدید به طور کامل و دقیق بیان کنید',
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

        $userId = $userId = $this->request->message->from->id;

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

        $userId = $userId = $this->request->message->from->id;

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

        $userId = $userId = $this->request->message->from->id;

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

        $userId = $userId = $this->request->message->from->id;

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

        $userId = $userId = $this->request->message->from->id;

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

        $userId = $userId = $this->request->message->from->id;

        $this->userModel()->setState($userId, UserModel::STATUS_SHOW_CATEGORY);
        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_SHOW_CATEGORY, $text);

        $this->io->setResponse($result);
    }

    public function listProduct()
    {
        $text = $this->request->message->text;

        $userId = $userId = $this->request->message->from->id;

        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' => 'یک گزینه را انتخاب کنید...',
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'keyboard' => $this->keyboard->listProductBottom(),
                'resize_keyboard' => true
            ]
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

            $userId = $userId = $this->request->message->from->id;

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
            default :
                $this->start();
                return true;
        }
    }
}