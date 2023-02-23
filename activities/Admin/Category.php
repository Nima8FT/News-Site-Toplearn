<?php

namespace Admin;

use Admin\Admin;
use database\Database;
use Auth\Auth;

// class Category extends Admin
class Category
{

    // function __construct()
    // {
    //     $auth = new Auth();
    //     $auth->check_admin();
    // }

    public function index()
    {
        $db = new Database();
        $categories = $db->select('SELECT * FROM categories ORDER BY `id` DESC');
        require_once(BASE_PATH . '/template/admin/categories/index.php');

    }

    public function create()
    {
        require_once(BASE_PATH . '/template/admin/categories/create.php');
    }

    public function store($request)
    {
        $db = new Database();
        $db->insert('categories', array_keys($request), $request);
        $admin = new Admin();
        $admin->redirect('admin/category');
    }

    public function edit($id)
    {
        $db = new Database();
        $categories = $db->select('SELECT * FROM categories WHERE id = ?;', [$id])->fetch();
        require_once(BASE_PATH . '/template/admin/categories/edit.php');
    }

    public function update($request, $id)
    {
        $db = new Database();
        $db->update('categories', $id, array_keys($request), $request);
        $admin = new Admin();
        $admin->redirect('admin/category');
    }

    public function delete($id)
    {
        $db = new Database();
        $db->delete('categories', $id);
        $admin = new Admin();
        $admin->redirect('admin/category');
    }

}

?>