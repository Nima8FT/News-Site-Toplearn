<?php

namespace Admin;

class Admin
{

    public $current_domain;
    public $base_path;

    function __construct()
    {
        $this->current_domain = CURRENT_DOMAIN;
        $this->base_path = BASE_PATH;
    }

    protected function redirect($url)
    {
        header('Location: ' . trim($this->current_domain, '/ ') . '/' . trim($url, '/ '));
        exit;
    }

    protected function redirect_back()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    protected function save_image($image, $image_path, $image_name = null)
    {
        $extension = explode('/', $image['type'][1]);
        if ($image_name) {
            $image_name = $image_name . '.' . $extension;
        } else {
            $image_name = date("Y-m-d-H-i-s") . '.' . $extension;
        }

        $image_temp = $image['tmp_name'];
        $image_path = 'public/' . $image_path . '/';

        if (is_uploaded_file($image_temp)) {
            if (move_uploaded_file($image_temp, $image_path . $image_name)) {
                return $image_path . $image_name;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    protected function remove_image($path)
    {
        $path = trim($this->base_path, '/ ') . '/' . trim($path, '/ ');
        if (file_exists($path)) {
            unlink($path);
        }
    }

}

?>