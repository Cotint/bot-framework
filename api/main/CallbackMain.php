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
}