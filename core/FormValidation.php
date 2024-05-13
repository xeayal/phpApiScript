<?php
namespace core;

class FormValidation {
    public $errors = [];

    public function run($data, $title, $conditions){
        $conditionArray = explode("|", $conditions);
        foreach ($conditionArray as $condition){
            if($condition == 'numeric'){
               if(!is_numeric($data)){
                   $this->errors[] = $title . ' must be numeric';
               }
            }

            if($condition == 'required'){
                if(!$data){
                    $this->errors[] = $title . ' is required';
                }
            }

            $lengthPattern  = 'length\[(\d+)\]';
            if (preg_match($lengthPattern, $condition, $matches)) {
                $length = intval($matches[1]);
                if($length != strlen($data)){
                    $this->errors[] = $title . ' length must be '.$length;
                }
            }

            $minLengthPattern = '/min_length\[(\d+)\]/';
            if (preg_match($minLengthPattern, $condition, $matches)) {
                $length = intval($matches[1]);
                if($length > strlen($data)){
                    $this->errors[] = $title . ' must be at least ' . $length . ' characters long';
                }
            }

            $maxLengthPattern = '/max_length\[(\d+)\]/';
            if (preg_match($maxLengthPattern, $condition, $matches)) {
                $length = intval($matches[1]);
                if($length < strlen($data)){
                    $this->errors[] = 'The length of the' . $title . ' must be a maximum of ' .$length. ' characters';
                }
            }

            if($condition == 'email'){
                if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[] = $data . ' is not valid email';
                }
            }
        }
    }


    /**
     * check form validation status
     * @return  boolean
     */
    public function valid(){
        if(count($this->errors) == 0){
            return true;
        }else{
            return false;
        }
    }
}