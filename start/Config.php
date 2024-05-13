<?php
namespace start;
use core\Database;

class Config {
    /**
     * @var string[]
     * middlewarelerin qisaldilmis pathlarinin teyini
     */
    public static $middlewares = array(
        'auth' => 'Authontication'
    );

    /**
     * @var string[]
     * apilerin urllerini saxlamaq ucun
     * */
    public static $apis = array(
        'getQr' => 'https://example.com/scan',
        'checkConnection' => 'https://example.com/checkconnection',
        'send' => 'https://example.com/send',
        'sendQuote' => 'https://example.com/sendQuote',
        'logout' => 'https://example.com/logout',
        'createSession' => 'https://example.com/create-session',
        'updateAccessList' => 'https://example.com/update-access-list',
        'getProfilPicture' => 'https://example.com/get-profile-picture/:phone',
        'loadAutoReplyTemplate' => 'https://example.com/load-auto-reply-template',
        'checkWpAvailability' => 'https://example.com/check-phone-wp-availability/:phone',
    );

}