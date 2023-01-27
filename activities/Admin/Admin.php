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

        if ($imageName) {
            $extension = explode('/', $image['type'])[1];
            $imageName = $imageName . '.' . $extension;
        } else {
            $extension = explode('/', $image['type'])[1];
            $imageName = date("Y-m-d-H-i-s") . '.' . $extension;
        }

        $imageTemp = $image['tmp_name'];
        $imagePath = 'public/' . $imagePath . '/';
        
        if (is_uploaded_file($imageTemp)) {
            $full_image = $imagePath . $imageName;
            if (move_uploaded_file($imageTemp, $full_image)) {
                return $imagePath . $imageName;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }


    public function remove_image($path)
    {
        $path = trim($this->base_path, '/ ') . '/' . trim($path, '/ ');
        if (file_exists($path)) {
            unlink($path);
        }
    }

}

?>