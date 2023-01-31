<?php

namespace Admin;

use Admin\Admin;
use database\Database;

// class Category extends Admin
class Setting
{

    public function index()
    {
        $db = new Database();
        $setting = $db->select('SELECT * FROM setting ORDER BY `id` DESC')->fetch();
        require_once(BASE_PATH . '/template/admin/settings/index.php');

    }

    public function edit($id)
    {
        $db = new Database();
        $setting = $db->select('SELECT * FROM setting WHERE id = ?;', [$id])->fetch();
        require_once(BASE_PATH . '/template/admin/settings/edit.php');
    }

    public function update($request, $id)
    {
        $db = new Database();
        $admin = new Admin();
        $setting = $db->select('SELECT * FROM setting WHERE id = ?;', [$id])->fetch();
        if ($request['logo']['tmp_name'] !== '') {
            $request['logo'] = $admin->saveImage($request['image'], 'images');
        } else {
            unset($request['logo']);
        }
        if ($request['icon']['tmp_name'] !== '') {
            $request['icon'] = $admin->saveImage($request['image'], 'images');
        } else {
            unset($request['icon']);
        }
        if (!empty($setting)) {
            $db->update('setting', $id, array_keys($request), $request);
        } else {
            $db->insert('setting', array_keys($request), $request);
        }
        $admin->redirect('admin/setting');
    }

}

?>