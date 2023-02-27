<?php

namespace App;

use Admin\Admin;
use Auth\Auth;
use database\Database;

class Home
{

    public function index()
    {
        $db = new Database();
        $setting = $db->select('SELECT * FROM setting')->fetch();
        $menus = $db->select('SELECT * FROM menus WHERE parent_id IS NULL')->fetchAll();
        $top_selected_post = $db->select('SELECT posts.* , (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comments_count (SELECT username FROM users WHERE user.id = posts.user_id) AS username (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts.selected = 1 ORDER BY created_at DESC LIMIT 0,3')->fetchAll();
        require_once(BASE_PATH . '/template/app/index.php');
    }

    public function show($id)
    {
    }

    public function category($id)
    {
    }

    public function comment_store($request)
    {
    }

    public function redirect_back()
    {
        header('Location:' . $_SERVER['HTTP_REFERER']);
        exit();
    }

}

?>