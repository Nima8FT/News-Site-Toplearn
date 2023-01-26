<?php


//session start
session_start();


//configuration
define('BASE_PATH', __DIR__);
define('CURRENT_DOMAIN', currentDomain() . '/News-Site-Toplearn/');
define('DISPLAY_ERROR', true);
define('DB_HOST', 'localhost');
define('DB_NAME', 'newsSiteToplearn');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');



//helpers

//protocol http or https
function protocol()
{
    return stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
}


//domain name
function currentDomain()
{
    return protocol() . $_SERVER['HTTP_HOST'];
}


//address for file as css
function asset($src)
{
    $domain = trim(CURRENT_DOMAIN, '/ ');
    $src = $domain . '/' . trim($src, '/');
    return $src;
}


//address for href a tag
function url($url)
{
    $domain = trim(CURRENT_DOMAIN, '/ ');
    $url = $domain . '/' . trim($url, '/');
    return $url;
}


//user page openend
function currentUrl()
{
    return currentDomain() . $_SERVER['REQUEST_URI'];
}


//method post or get
function methodField()
{
    return $_SERVER['REQUEST_METHOD'];
}


//show error or not
function displayError($display_error)
{
    if ($display_error == true) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);
    }
}


//save message as session for show
global $flash_message;
if (isset($_SESSION['flash_message'])) {
    $flash_message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}
function flash($name, $value = null)
{
    if ($value === null) {
        global $flash_message;
        $message = isset($flash_message[$name]) ? $flash_message[$name] : '';
        return $message;
    } else {
        $_SESSION['flash_message'][$name] = $value;
    }
}


//system routing reserved
function uri($reserved_url, $class, $method, $request_method = 'GET')
{

    //current url array
    $current_url = explode('?', currentUrl())[0];
    $current_url = str_replace(CURRENT_DOMAIN, '', $current_url);
    $current_url = trim($current_url, '/');
    $current_url_array = explode('/', $current_url);
    $current_url_array = array_filter($current_url_array);


    //reserved url array
    $reserved_url = trim($reserved_url, '/');
    $reserved_url_array = explode('/', $current_url);
    $reserved_url_array = array_filter($current_url_array);


    //comparison reserved url array and current url array
    if (sizeof($reserved_url_array) !== sizeof($current_url_array) || methodField() !== $request_method) {
        return false;
    }

    $parameters = [];
    for ($key = 0; $key < sizeof($current_url_array); $key++) {
        if ($reserved_url_array[$key][0] == "{" && $reserved_url_array[$key][strlen($reserved_url_array[$key]) - 1] == "}") {
            array_push($parameters, $current_url_array[$key]);
        } else if ($current_url_array[$key] !== $reserved_url_array[$key]) {
            return false;
        }
    }

    if (methodField() == 'POST') {
        $request = isset($_FILES) ? array_merge($_POST, $_FILES) : $_POST;
        $parameters = array_merge([$request], $parameters);
    }

    $object = new $class;
    call_user_func_array(array($object, $method), $parameters);
    exit();

}


//vardump and exit
function dd($var)
{
    echo '<pre>';
    var_dump($var);
    exit;
}




?>