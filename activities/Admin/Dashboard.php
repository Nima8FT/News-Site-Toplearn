<?php

namespace Admin;

use Admin\Admin;
use Auth\Auth;
use database\Database;

class Dashboard
{

    public function index()
    {
        $db = new Database();

        $count_category = $db->select('SELECT COUNT(*) FROM categories')->fetch();
        $count_user = $db->select('SELECT COUNT(*) FROM users WHERE permission  = "user"')->fetch();
        $count_admin = $db->select('SELECT COUNT(*) FROM users WHERE permission  = "admin"')->fetch();
        $count_post = $db->select('SELECT COUNT(*) FROM posts')->fetch();
        $sum_post_views = $db->select('SELECT SUM(view) FROM posts')->fetch();
        $count_comment = $db->select('SELECT COUNT(*) FROM comments')->fetch();
        $count_comment_unseen = $db->select('SELECT COUNT(*) FROM comments WHERE status = "unseen"')->fetch();
        $count_comment_approved = $db->select('SELECT COUNT(*) FROM comments WHERE status = "approved"')->fetch();

        $most_view_posts = $db->select('SELECT * FROM posts ORDER BY view DESC LIMIT 0,5')->fetchAll();
        $most_commented_posts = $db->select('SELECT posts.id , posts.title , COUNT(comments.post_id) AS comment_count FROM posts LEFT JOIN comments ON posts.id = comments.post_id GROUP BY posts.id ORDER BY comment_count DESC LIMIT 0,5')->fetchAll();
        $last_comments = $db->select('SELECT comments.id , comments.comment , comments.status , comments.post_id , users.username FROM comments,users WHERE comments.user_id = users.id ORDER BY comments.created_at DESC LIMIT 0,5')->fetchAll();

        require_once(BASE_PATH . '/template/admin/dashboard/index.php');
    }

}

?>