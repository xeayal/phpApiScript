<?php
namespace core;

class Response
{
    // Function to send a JSON response with proper headers
    public static function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        die;
    }
}