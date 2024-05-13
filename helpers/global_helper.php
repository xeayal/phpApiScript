<?php 
function dd($data)
{
    echo '<pre>';
    print_r($data);
    die;
}

function generateOtp()
{
    return rand(10000, 99999);
}

function uniqueToken(){
    return password_hash(md5(uniqid().rand(1000,9999).time()), PASSWORD_DEFAULT);
}

function env($k)
{
    $env_file = file_get_contents('.env');
    $lines = explode("\n", $env_file);
    $_ENV = [];
    foreach ($lines as $line) {
        $parts = explode('=', $line);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            $_ENV[$key] = $value;
        }
    }
    return $_ENV[$k];
}

function formatPhone($phone)
{
    $phone = str_replace('+', '', $phone);
    $phone = str_replace(' ', '', $phone);
    $phone = str_replace('-', '', $phone);
    $phone = str_replace('(', '', $phone);
    $phone = str_replace(')', '', $phone);
    $phone = ltrim($phone, '0');
    return $phone;
}


function clear_input($data)
{
    $data = htmlspecialchars($data);
    $data = trim($data);
    $data = strip_tags($data);
    return $data;
}