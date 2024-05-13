<?php
use controllers\WhatsappController;

$app->post('/create-whatsapp-account', function (){
    $merchant = new WhatsappController();
    $merchant->createWhatsappAccount();
},['auth']);