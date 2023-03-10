<?php

namespace Admin;

use Admin\Admin;
use Auth\Auth;
use database\Database;

// class Category extends Admin
class Menu
{

    // function __construct()
    // {
    //     $auth = new Auth();
    //     $auth->check_admin();
    // }

    public function index()
    {
        $db = new Database();
        $menus = $db->select('SELECT m1.*, m2.name AS parent_name FROM menus m1 LEFT JOIN menus m2 ON m1.parent_id = m2.id ORDER BY `id` DESC');
        require_once(BASE_PATH . '/template/admin/menus/index.php');
    }

    public function create()
    {
        $db = new Database();
        $menus = $db->select('SELECT * FROM menus WHERE parent_id IS NULL ORDER BY `id` DESC');
        require_once(BASE_PATH . '/template/admin/menus/create.php');
    }

    public function store($request)
    {
        $db = new Database();
        $db->insert('menus', array_keys(array_filter($request)), array_filter($request));
        $admin = new Admin();
        $admin->redirect('admin/menu');
    }

    public function edit($id)
    {
        $db = new Database();
        $menu = $db->select('SELECT * FROM menus WHERE id = ?;', [$id])->fetch();
        $menus = $db->select('SELECT * FROM menus WHERE parent_id IS NULL');
        require_once(BASE_PATH . '/template/admin/menus/edit.php');
    }

    public function update($request, $id)
    {
        $db = new Database();
        $db->update('menus', $id, array_keys($request), $request);
        $admin = new Admin();
        $admin->redirect('admin/menu');
    }

    public function delete($id)
    {
        $db = new Database();
        $db->delete('menus', $id);
        $admin = new Admin();
        $admin->redirect('admin/menu');
    }

}

?>