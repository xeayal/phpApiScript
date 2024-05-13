<?php
namespace controllers;

use core\FormValidation;
use core\Response;
use entitys\Merchant;
use entitys\WhatsappAccount;
use helpers\Authontication;
use helpers\Date;
use helpers\WhatsappHelper;
use http\Request;
use Ramsey\Uuid\Uuid;

class MerchantController {
    public $validation;
    public $request;
    public $merchant;

    public function __construct()
    {
        $this->request = Request::body();
        $this->validation = new FormValidation();
        $this->merchant = new Merchant();
    }

    public function login(){
        $this->validation->run($this->request->phone, 'phone', 'required');
        $this->validation->run($this->request->password, 'Password', 'required');

        if($this->validation->valid()){
            $merchantData = $this->merchant->findBy(['main_whatsapp_phone' => $this->request->phone, 'active_status' => 1])[0];

            if($merchantData){
                if(password_verify($this->request->password, $merchantData['password'])){
                    $token = Authontication::generateToken([
                        'id' => $merchantData['id'],
                        'owner' => $merchantData['owner'],
                        'mainWpPhone' => $merchantData['main_whatsapp_phone']
                    ]);
                    Response::json(['status' => 'success', 'token' => $token]);
                }else{
                    Response::json(['status' => 'error', 'message' => 'Şifrə xətalıdır']);
                }
            }else{
                Response::json(['status' => 'error', 'message' => 'İstifadəçi tapılmadı']);
            }
            
        }else{
            Response::json(['status' => 'error', 'message' => $this->validation->errors]);
        }
    }

    /**
     * Create new merchant
     * @return json
     */
    public function registration()
    {
        $this->validation->run($this->request->fullName, 'Full Name', 'required');
        $this->validation->run($this->request->phone, 'Phone', 'required|numeric|min_length[10]');
        $this->validation->run($this->request->password, 'Password', 'required|min_length[4]');
        $this->validation->run($this->request->timeZone, 'Time Zone', 'required|numeric');

        if($this->validation->valid()){
            $checkFromDb = $this->merchant->findBy(['main_whatsapp_phone' => $this->request->phone, 'active_status' => 1]);

            if(count($checkFromDb) == 0){
                $hashed_password = password_hash($this->request->password, PASSWORD_DEFAULT);
                $otp = generateOtp();
                $uuid = Uuid::uuid1()->toString();
                $phone = formatPhone($this->request->phone);

                $save = $this->merchant->save([
                    'owner' => $this->request->fullName,
                    'main_whatsapp_phone' => $phone,
                    'password' => $hashed_password,
                    'otp_code' => $otp,
                    'registration_secret_key' => $uuid,
                    'time_zone' => $this->request->timeZone,
                    'created_at' => Date::fullDate()
                ]);

                if($save){
                    //otp kodu mesaj olaraq gonderirik
                    $message = 'Your verification otp code: '.$otp;
                    WhatsappHelper::send($phone, $message);

                    Response::json([
                        'status' => 'success',
                        'otpToken' => $uuid,
                        'message' => 'Uğurla qeydiyyatdan keçdiniz, Otp təsdiq gözlənilir'
                    ], 201);
                }else{
                    Response::json([
                        'status' => 'error',
                        'message' => 'Hesab yaradılarkən xəta baş verdi'
                    ]);
                }
            }else{
                Response::json([
                    'status' => 'error', 
                    'message' => 'Bu nömrəyə aktiv hesab mövcuddur, şifrənizi yeniləyərək hesabınıza daxil olun'
                ]);
            }

        }else{
            Response::json(['status' => 'error', 'message' => $this->validation->errors]);
        }
    }

    /** Otp verify */
    public function otpVerify(){
        $this->validation->run($this->request->otp, "Otp", 'numeric|length[5]');
        $this->validation->run($this->request->otpToken, "Otp token", 'required');

        if($this->validation->valid()){
            $merchant = $this->merchant->findBy(array(
                'registration_secret_key' => clear_input($this->request->otpToken),
                'otp_code' => $this->request->otp,
                'active_status' => 0
            ))[0];

            if($merchant){
                $this->merchant->save(['id' => $merchant['id'], 'active_status' => 1]);

                /** whatsapp_accounts_of_merchant table-ne elave edilir */
                $whatsappAccountObj = new WhatsappAccount();
                $whatsappAccountObj->save(array(
                    'merchant_id' => $merchant['id'],
                    'whatsapp_phone' => $merchant['main_whatsapp_phone'],
                    'time_zone' => $merchant['time_zone'],
                    'secretkey' => uniqueToken(),
                    'expire_at' => Date::yesterday(),
                    'created_at' => Date::fullDate()
                ));

                Response::json([
                    'status' => 'success',
                    'message' => 'Uğurla qeydiyyatdan keçdiniz'
                ]);
            }else{
                Response::json(['status' => 'error', 'message' => 'Otp xətalıdır, Uyğun məlumat tapılmadı']);
            }
        }else{
            Response::json(['status' => 'error', 'message' => $this->validation->errors]);
        }
    }

    /** yeniden otp gonderilmesi */
    public function sendOtp()
    {
        $this->validation->run($this->request->otpToken, "Otp token", 'required');

        if($this->validation->valid()){
            $merchant = $this->merchant->findOneBy(array(
                'registration_secret_key' => clear_input($this->request->otpToken),
                'active_status' => 0
            ));

            if($merchant){
                $otp = generateOtp();
                $message = 'Your verification otp code: '.$otp;
                $this->merchant->save(['id' => $merchant['id'], 'otp_code' => $otp]);

                WhatsappHelper::send($merchant['main_whatsapp_phone'], $message);

                Response::json([
                    'status' => 'success',
                    'otpToken' => $this->request->otpToken,
                    'message' => 'Otp təsdiq gözlənilir'
                ]);
            }else{
                Response::json(['status' => 'error', 'message' => 'Məlumat xətalıdır']);
            }
        }else{
            Response::json(['status' => 'error', 'message' => $this->validation->errors]);
        }
    }

}