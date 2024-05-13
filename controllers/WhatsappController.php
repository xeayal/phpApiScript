<?php
namespace controllers;
use core\FormValidation;
use core\Response;
use entitys\WhatsappAccount;
use helpers\Authontication;
use helpers\Date;
use http\Request;

class WhatsappController {
    public $validation;
    public $request;
    public $whatsapp;

    public function __construct()
    {
        $this->request = Request::body();
        $this->validation = new FormValidation();
        $this->whatsapp = new WhatsappAccount();
    }

    /** yeni whatsapp hesabinin elave edilmesi */
    public function createWhatsappAccount()
    {
        $this->validation->run($this->request->phone, 'Phone', 'min_length[10]|numeric');
        $this->validation->run($this->request->timeZone, 'Time Zone', 'required|numeric');
        $this->validation->run($this->request->subscribePackege, 'Subscribe Packege', 'required|numeric');

        if($this->validation->valid()){
            $merchantId = Authontication::checkAuth(Request::headers()->token)->data->id;

            $whatsappAccountObj = new WhatsappAccount();
            $whatsappAccountObj->save(array(
                'merchant_id' => $merchantId,
                'whatsapp_phone' => formatPhone($this->request->phone),
                'time_zone' => $this->request->timeZone,
                'secretkey' => uniqueToken(),
                'subscribe_packege' => $this->request->subscribePackege,
                'expire_at' => Date::yesterday(),
                'created_at' => Date::fullDate()
            ));

        }else{
            Response::json(['status' => 'error', 'message' => $this->validation->errors]);
        }
    }
}