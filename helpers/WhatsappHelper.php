<?php
namespace helpers;

use http\Request;
use start\Config;

class WhatsappHelper {
    /** soft10 nomresi ile mesaj gonderilmesi */
    public static function send($to, $message){
        return Request::post(
            Config::$apis['send'],
            array(
                'numbers' => [$to],
                'message' => $message
            )
        );
    }

    /** create session on node */
    public static function createSession()
    {

    }
}