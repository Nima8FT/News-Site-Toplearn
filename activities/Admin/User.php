<?php

namespace Admin;

use Admin\Admin;
use database\Database;

// class Category extends Admin
class User
{

    public function index()
    {
        $db = new Database();
        $users = $db->select('SELECT * FROM users ORDER BY `id` DESC');
        require_once(BASE_PATH . '/template/admin/users/index.php');

    }
    public function edit($id)
    {
        $db = new Database();
        $user = $db->select('SELECT * FROM users WHERE id = ?;', [$id])->fetch();
        require_once(BASE_PATH . '/template/admin/users/edit.php');
    }

    public function update($request, $id)
    {
        $db = new Database();
        $admin = new Admin();
        $db->update('users', $id, array_keys($request), $request);
        $admin->redirect('admin/user');
    }

    public function delete($id) {
        $db = new Database();
        $db->delete('users', $id);
        $admin = new Admin();
        $admin->redirect('admin/user');
    }

    public function premission($id) {
        $db = new Database();
        $user = $db->select('SELECT * FROM users WHERE id = ?;', [$id])->fetch();
        if ($user['permission'] == 'user') {
            $db->update('users', $id, ['permission'], ['admin']);
        } else {
            $db->update('users', $id, ['permission'], ['user']);
        }
        $admin = new Admin();
        $admin->redirect('admin/user');
    }

}

?>