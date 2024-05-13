<?php
use controllers\MerchantController;

$app->post('/login', function (){
    $merchant = new MerchantController();
    $merchant->login();
});

$app->post('/registration', function (){
    $merchant = new MerchantController();
    $merchant->registration();
});

$app->post('/otp-verify', function (){
    $merchant = new MerchantController();
    $merchant->otpVerify();
});

$app->post('/otp-send', function (){
    $merchant = new MerchantController();
    $merchant->sendOtp();
});