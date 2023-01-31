<?php

namespace Admin;

use Admin\Admin;
use database\Database;

// class Category extends Admin
class Post
{

    public function index()
    {
        $db = new Database();
        $posts = $db->select('SELECT posts.*,categories.name AS category_name , users.email AS email FROM posts LEFT JOIN categories ON posts.cat_id = categories.id LEFT JOIN users ON posts.user_id = users.id ORDER BY `id` DESC');
        require_once(BASE_PATH . '/template/admin/posts/index.php');

    }

    public function create()
    {
        $db = new Database();
        $categories = $db->select('SELECT * FROM categories ORDER BY `id` DESC');
        require_once(BASE_PATH . '/template/admin/posts/create.php');
    }

    public function store($request)
    {
        $real_time_stamp = substr($request['published_at'], 0, 10);
        $request['published_at'] = date("Y-m-d H:i:s", (int) $real_time_stamp);
        $db = new Database();
        $admin = new Admin();
        if ($request['cat_id'] != null) {
            $request['image'] = $admin->saveImage($request['image'], 'images');
            if ($request['image']) {
                $request = array_merge($request, ['user_id' => 1]);
                $db->insert('posts', array_keys($request), $request);
                $admin->redirect('admin/post');
            } else {
                $admin->redirect('admin/post');
            }
        } else {
            $admin->redirect('admin/post');
        }
    }

    public function edit($id)
    {
        $db = new Database();
        $post = $db->select('SELECT * FROM posts WHERE id = ?;', [$id])->fetch();
        $categories = $db->select('SELECT * FROM categories ORDER BY `id` DESC');
        require_once(BASE_PATH . '/template/admin/posts/edit.php');
    }

    public function update($request, $id)
    {
        $real_time_stamp = substr($request['published_at'], 0, 10);
        $request['published_at'] = date("Y-m-d H:i:s", (int) $real_time_stamp);
        $db = new Database();
        $admin = new Admin();
        if ($request['cat_id'] != null) {
            if ($request['image']['tmp_name'] !== null) {
                $post = $db->select('SELECT * FROM posts WHERE id = ?;', [$id])->fetch();
                $admin->remove_image($post['image']);
                $request['image'] = $admin->saveImage($request['image'], 'images');
            } else {
                unset($request['image']);
            }
            $request = array_merge($request, ['user_id' => 1]);
            $db->update('posts', $id, array_keys($request), $request);
            $admin->redirect('admin/post');
        } else {
            $admin->redirect('admin/post');
        }
    }

    public function delete($id)
    {
        $db = new Database();
        $post = $db->select('SELECT * FROM posts WHERE id = ?;', [$id])->fetch();
        $admin = new Admin();
        $admin->remove_image($post['image']);
        $db->delete('posts', $id);
        $admin->redirect('admin/post');
    }

    public function selected($id)
    {
        $db = new Database();
        $post = $db->select('SELECT * FROM posts WHERE id = ?;', [$id])->fetch();
        if ($post['selelcted'] == 1) {
            $db->update('posts', $id, ['selelcted'], [2]);
        } else {
            $db->update('posts', $id, ['selelcted'], [1]);
        }
        $admin = new Admin();
        $admin->redirect('admin/post');
    }

    public function breaking_news($id)
    {
        $db = new Database();
        $post = $db->select('SELECT * FROM posts WHERE id = ?;', [$id])->fetch();
        if ($post['breaking_news'] == 1) {
            $db->update('posts', $id, ['breaking_news'], [2]);
        } else {
            $db->update('posts', $id, ['breaking_news'], [1]);
        }
        $admin = new Admin();
        $admin->redirect('admin/post');
    }

}

?>