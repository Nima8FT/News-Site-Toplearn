<?php

namespace Admin;

use Admin\Admin;
use database\Database;

// class Category extends Admin
class Banner
{

    public function index()
    {
        $db = new Database();
        $banners = $db->select('SELECT * FROM banners ORDER BY `id` DESC');
        require_once(BASE_PATH . '/template/admin/banners/index.php');

    }

    public function create()
    {
        require_once(BASE_PATH . '/template/admin/banners/create.php');
    }

    public function store($request)
    {
        $db = new Database();
        $admin = new Admin();
        $request['image'] = $admin->saveImage($request['image'], 'images');
        if ($request['image']) {
            $db->insert('banners', array_keys($request), $request);
            $admin->redirect('admin/banner');
        } else {
            $admin->redirect('admin/banner');
        }
    }

    public function edit($id)
    {
        $db = new Database();
        $banner = $db->select('SELECT * FROM banners WHERE id = ?;', [$id])->fetch();
        require_once(BASE_PATH . '/template/admin/banners/edit.php');
    }

    public function update($request, $id)
    {
        $db = new Database();
        $admin = new Admin();
        if ($request['image']['tmp_name'] !== null) {
            $banner = $db->select('SELECT * FROM banners WHERE id = ?;', [$id])->fetch();
            $admin->remove_image($banner['image']);
            $request['image'] = $admin->saveImage($request['image'], 'images');
        } else {
            unset($request['image']);
        }
        $db->update('banners', $id, array_keys($request), $request);
        $admin->redirect('admin/banner');
    }

    public function delete($id)
    {
        $db = new Database();
        $db->delete('banners', $id);
        $admin = new Admin();
        $admin->redirect('admin/banner');
    }

}

?>