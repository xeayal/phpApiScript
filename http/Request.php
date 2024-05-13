<?php
namespace http;

use core\Response;

class Request {
    public static function get($url, $header = ['Content-Type: application/json'])
    {
        array_push($header, 'User-Agent: '.env('USER_AGENT'));

        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => ENVIRONMENT == 'development' ? false : true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response,true);
    }

    public static function post($url, $content, $header = ['Content-Type: application/json'])
    {
        array_push($header, 'User-Agent: '.env('USER_AGENT'));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => ENVIRONMENT == 'development' ? false : true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($content),
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    public function put($url, $content, $header = ['Content-Type: application/json'])
    {
        array_push($header, 'User-Agent: '.env('USER_AGENT'));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($content),
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    public function delete($url, $header = ['Content-Type: application/json'])
    {
        array_push($header, 'User-Agent: '.env('USER_AGENT'));
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_VERBOSE => false,
            CURLOPT_HEADER => false,
            CURLOPT_SSL_VERIFYPEER => ENVIRONMENT == 'development' ? false : true,
            CURLOPT_HTTPHEADER => $header
        ));
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    /*
     * @return array
     */
    public static function body()
    {
        $requestData = json_decode(file_get_contents('php://input'));

        // JSON formatında istek alınıp alınmadığını kontrol et
        if ($requestData === null && json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400); // Bad Request
            Response::json(array("error" => "Request body is empty or Invalid JSON data"));
            exit;
        }

        return $requestData;
    }

    /*
     * @return string
     */
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /*
     * @return object
     */
    public static function headers()
    {
        return (object) getallheaders();
    }
}