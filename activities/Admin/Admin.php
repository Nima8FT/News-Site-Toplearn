<?php

namespace Admin;
use Auth\Auth;

class Admin
{

    public $current_domain;
    public $base_path;

    function __construct()
    {
        $auth = new Auth();
        $auth->check_admin();
        $this->current_domain = CURRENT_DOMAIN;
        $this->base_path = BASE_PATH;
    }

    public function redirect($url)
    {
        header('Location: ' . trim($this->current_domain, '/ ') . '/' . trim($url, '/ '));
        exit;
    }

    public function redirect_back()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }


    public function saveImage($image, $imagePath, $imageName = null)
    {
        $home_url = '../';
        $format_img = explode('.', $image['name']);
        $format_img = end($format_img);
        if ($imageName) {
            $imageName = $imageName . '.' . $format_img;
        } else {
            $imageName = date("Y-m-d-H-i-s") . '.' . $format_img;
        }

        $img_temp = $image['tmp_name'];
        $img_dir = 'public/' . $imagePath . $imageName;

        if (is_uploaded_file($img_temp)) {
            if (move_uploaded_file($img_temp, $home_url . $img_dir)) {
                return $img_dir;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    public function remove_image($path)
    {
        $path = trim($this->current_domain, '/ ') . '/' . trim($path, '/ ');
        if (file_exists($path)) {
            unlink($path);
        }
    }

}

?>