<?php
namespace middlewares;
use core\Response;
use helpers\Authontication as Auth;
use http\Request;

class Authontication {
    public $status;
    public function __construct()
    {
        $decoded = Auth::checkAuth(Request::headers()->token);
        if(is_object($decoded)){
            $currentTime = time();
            if ($decoded->exp < $currentTime) {
                $this->status = false;
                Response::json(['status' => 'error', 'message' => 'Token müddəti bitdi!'], 401);
            }else{
                $this->status = true;
            }
        }else{
            $this->status = false;
            Response::json(['status' => 'error', 'message' => 'You have not access'], 404);
        }
    }
}