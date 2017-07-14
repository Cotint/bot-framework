<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-10-30
 * Time: 9:32 AM
 */

spl_autoload_register(function ($classname) {
    $classname = str_replace("\\", "/", $classname);
    require ($classname. ".php");
});