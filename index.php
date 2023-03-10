<?php

use Auth\Auth;

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


//mails
define('MAIL_HOST', 'smtp.gmail.com');
define('SMTP_AUTH', true);
define('MAIL_USERNAME', 'nima_8a@yahoo.com');
define('MAIL_PASSWORD', 'hbylvjuymhbbfnhj');
define('MAIL_PORT', 587);
define('SENDER_MAIL', 'nima_8a@yahoo.com');
define('SENDER_NAME', 'Nima');


require_once 'database/database.php';
require_once 'activities/Admin/Dashboard.php';
require_once 'activities/Admin/Category.php';
require_once 'activities/Admin/Post.php';
require_once 'activities/Admin/Banner.php';
require_once 'activities/Admin/User.php';
require_once 'activities/Admin/Admin.php';
require_once 'activities/Admin/Comment.php';
require_once 'activities/Admin/Menu.php';
require_once 'activities/Admin/Setting.php';
require_once 'activities/Auth/Auth.php';
require_once 'activities/App/Home.php';
$db = new database\Database;


//helpers


//connecting to the database api
function ReqAPI($url, $data)
{
    $opts = array(
        'http' =>
        array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($data)
        )
    );
    $context = stream_context_create($opts);
    $result = file_get_contents($url, false, $context);

    echo $result;
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
    $reserved_url_array = explode('/', $reserved_url);
    $reserved_url_array = array_filter($reserved_url_array);


    //comparison reserved url array and current url array
    if (sizeof($current_url_array) != sizeof($reserved_url_array) || methodField() != $request_method) {
        return false;
    }

    $parameters = [];
    for ($key = 0; $key < sizeof($current_url_array); $key++) {
        if ($reserved_url_array[$key][0] == "{" && $reserved_url_array[$key][strlen($reserved_url_array[$key]) - 1] == "}") {
            array_push($parameters, $current_url_array[$key]);
        } elseif ($current_url_array[$key] !== $reserved_url_array[$key]) {
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


//vardump and exit
function dd($var)
{
    echo '<pre>';
    var_dump($var);
    exit;
}


//use shamsi date
spl_autoload_register(function ($className) {
    $path = BASE_PATH . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR;
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    include($path . $className . '.php');
});

function shamsi_date($date)
{
    return \Parsidev\Jalali\jDate::forge($date)->format('datetime');
}




//dashbord
uri('admin','Admin\Dashboard','index');



//category
//show one row
// uri('admin/category/show/{id}', 'Admin\Category', 'show');
uri('admin/category', 'Admin\Category', 'index');
uri('admin/category/create', 'Admin\Category', 'create');
uri('admin/category/store', 'Admin\Category', 'store', 'POST');
uri('admin/category/edit/{id}', 'Admin\Category', 'edit');
uri('admin/category/update/{id}', 'Admin\Category', 'update', 'POST');
uri('admin/category/delete/{id}', 'Admin\Category', 'delete');



//posts
uri('admin/post', 'Admin\Post', 'index');
uri('admin/post/create', 'Admin\Post', 'create');
uri('admin/post/store', 'Admin\Post', 'store', 'POST');
uri('admin/post/edit/{id}', 'Admin\Post', 'edit');
uri('admin/post/update/{id}', 'Admin\Post', 'update', 'POST');
uri('admin/post/delete/{id}', 'Admin\Post', 'delete');
uri('admin/post/selected/{id}', 'Admin\Post', 'selected');
uri('admin/post/breaking-news/{id}', 'Admin\Post', 'breaking_news');



//banners
uri('admin/banner', 'Admin\Banner', 'index');
uri('admin/banner/create', 'Admin\Banner', 'create');
uri('admin/banner/store', 'Admin\Banner', 'store', 'POST');
uri('admin/banner/edit/{id}', 'Admin\Banner', 'edit');
uri('admin/banner/update/{id}', 'Admin\Banner', 'update', 'POST');
uri('admin/banner/delete/{id}', 'Admin\Banner', 'delete');



//users
uri('admin/user', 'Admin\User', 'index');
uri('admin/user/edit/{id}', 'Admin\User', 'edit');
uri('admin/user/update/{id}', 'Admin\User', 'update', 'POST');
uri('admin/user/delete/{id}', 'Admin\User', 'delete');
uri('admin/user/premission/{id}', 'Admin\User', 'premission');



//comment
uri('admin/comment', 'Admin\Comment', 'index');
uri('admin/comment/change/{id}', 'Admin\Comment', 'change');


//menus
uri('admin/menu', 'Admin\Menu', 'index');
uri('admin/menu/create', 'Admin\Menu', 'create');
uri('admin/menu/store', 'Admin\Menu', 'store', 'POST');
uri('admin/menu/edit/{id}', 'Admin\Menu', 'edit');
uri('admin/menu/update/{id}', 'Admin\Menu', 'update', 'POST');
uri('admin/menu/delete/{id}', 'Admin\Menu', 'delete');



//web setting
uri('admin/setting', 'Admin\Setting', 'index');
uri('admin/setting/edit/{id}', 'Admin\Setting', 'edit');
uri('admin/setting/update/{id}', 'Admin\Setting', 'update', 'POST');



//auth
uri('register', 'Auth\Auth', 'register');
uri('register/store', 'Auth\Auth', 'register_store', 'POST');
uri('activation/store/{verify_token}', 'Auth\Auth', 'activation', 'POST');
uri('login', 'Auth\Auth', 'login');
uri('check_login', 'Auth\Auth', 'check_login', 'POST');
uri('logout', 'Auth\Auth', 'logout');
uri('forgot', 'Auth\Auth', 'forgot');
uri('forgot_request', 'Auth\Auth', 'forgot_request', 'POST');
uri('reset-password-form/{forgot_token}', 'Auth\Auth', 'reset_password_view');
uri('reset-password/{forgot_token}', 'Auth\Auth', 'reset_password','POST');



//app
uri('/','App\Home' , 'index');
uri('/home','App\Home' , 'index');
uri('/show_post/{id}','App\Home' , 'show');
uri('/show_category/{id}','App\Home' , 'category');
uri('/comment_store','App\Home' , 'comment_store','POST');



echo "404 - page not found";




?>