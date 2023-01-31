<?php

namespace Admin;

use Admin\Admin;
use database\Database;

// class Category extends Admin
class Comment
{

    public function index()
    {
        $db = new Database();
        $comments = $db->select('SELECT comments.*,posts.title AS post_name , users.email AS email FROM comments LEFT JOIN posts ON comments.post_id = posts.id LEFT JOIN users ON comments.user_id = users.id ORDER BY `id` DESC');
        $unseen_comment = $db->select('SELECT * FROM comments WHERE status = ?' , ['unseen']);
        foreach ($unseen_comment as $comment) {
            $db->update('comments', $comment['id'], ['status'], ['seen']);
        }
        require_once(BASE_PATH . '/template/admin/comments/index.php');

    }

    public function change($id)
    {
        $db = new Database();
        $comment = $db->select('SELECT * FROM comments WHERE id = ?;', [$id])->fetch();
        if ($comment['status'] == 'seen' || $comment['status'] == 'unseen') {
            $db->update('comments', $id, ['status'], ['approved']);
        } else {
            $db->update('comments', $id, ['status'], ['seen']);
        }
        $admin = new Admin();
        $admin->redirect('admin/comment');
    }

}

?>