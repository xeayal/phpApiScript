<?php
namespace helpers;
use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Authontication {
    private static $secretKey;

    public function __construct()
    {
        self::$secretKey = env('JWT_KEY');
    }

    public static function generateToken($data)
    {
        $currentTime = time();
        $expireTime = $currentTime + (60 * 1500);

        $request_data = [
            "iat" => $currentTime,
            "exp" => $expireTime,
            'data' => $data
        ];

        $jwt = JWT::encode(
            $request_data,
            self::$secretKey,
            'HS512'
        );

        return $jwt;
    }

    public static function checkAuth($token){
        $data = JWT::decode($token, new Key(self::$secretKey, 'HS512'));
        return $data;
    }
}