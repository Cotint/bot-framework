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
use model\SupportModel;
use model\UserHistoryModel;
use model\UserModel;

class CallbackMain extends MainMain
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

    public function getProduct()
    {

        $text = $this->request->callback_query->message->text;
        $data = $this->request->callback_query->data;
        $userId = $this->request->callback_query->message->chat->id;
        $this->redis->set("proId" . $userId, $data);

        /** @var PDOConnection $conn */
        $conn = $this->container->get('pdo');

        /** @var PDOStatement $stmt2 */
        //img am khaande shavad
        $stmt = $conn->prepare("SELECT pro_ID, pro_Name, pro_Description, pro_LastPrice  FROM product WHERE pro_ID = :proId");

        $stmt->bindParam('proId', $data);

        $stmt->execute();

        $res = $stmt->fetchAll();

        $caption = $res[0]['pro_Name'];
        $caption .= "\n\n";
        $caption .= $res[0]['pro_Description'];
        $caption .= "\n\n";
        $caption .= "قیمت:" . "\t\t" . $res[0]['pro_LastPrice'];
        $result = [
            'method' => 'sendPhoto',
            'chat_id' => $userId,
            'photo' => 'https://6de89762.ngrok.io/bot-basket/public/image/image.jpg',
            'caption' => urlencode($caption),
            'reply_markup' => [
                'keyboard' => $this->keyboard->listProductBottom()
            ]
        ];

        $this->userModel()->setState($userId, UserModel::STATUS_SHOW_PRODUCT);
//        $this->userHistoryModel()->addHistory($userId, UserModel::STATUS_SHOW_PRODUCT, $text);
        $this->io->setResponse($result);
        
    }
    
}