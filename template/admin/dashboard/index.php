<?php
require_once(BASE_PATH . '/template/admin/layouts/header.php');
?>

<div class="row mt-3">

    <div class="col-sm-6 col-lg-3">
        <a href="<?= url('admin/category') ?>" class="text-decoration-none">
            <div class="card text-white bg-gradiant-green-blue mb-3">
                <div class="card-header d-flex justify-content-between align-items-center"><span><i
                            class="fas fa-clipboard-list"></i> Categories</span>
                    <?= $count_category["COUNT(*)"] ?> <span class="badge badge-pill right"></span>
                </div>
                <div class="card-body">
                    <section class="font-12 my-0"><i class="fas fa-clipboard-list"></i> GO TO Categories!</section>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="<?= url('admin/user') ?>" class="text-decoration-none">
            <div class="card text-white bg-juicy-orange mb-3">
                <div class="card-header d-flex justify-content-between align-items-center"><span><i
                            class="fas fa-users"></i> Users</span>
                    <?= $count_user["COUNT(*)"] ?> <span class="badge badge-pill right"></span>
                </div>
                <div class="card-body">
                    <section class="d-flex justify-content-between align-items-center font-12">
                        <span class=""><i class="fas fa-users-cog"></i> Admin <span class="badge badge-pill mx-1">
                                <?= $count_admin["COUNT(*)"] ?>
                            </span></span>
                        <span class=""><i class="fas fa-user"></i> All Users <span class="badge badge-pill mx-1">
                                <?= $count_user["COUNT(*)"] + $count_admin["COUNT(*)"] ?>
                            </span></span>
                    </section>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="<?= url('admin/post') ?>" class="text-decoration-none">
            <div class="card text-white bg-dracula mb-3">
                <div class="card-header d-flex justify-content-between align-items-center"><span><i
                            class="fas fa-newspaper"></i> Article</span>
                    <?= $count_post["COUNT(*)"] ?> <span class="badge badge-pill right"></span>
                </div>
                <div class="card-body">
                    <section class="d-flex justify-content-between align-items-center font-12">
                        <span class=""><i class="fas fa-bolt"></i> Views <span class="badge badge-pill mx-1">
                                <?= $sum_post_views["SUM(view)"] ?>
                            </span></span>
                    </section>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="<?= url('admin/comment') ?>" class="text-decoration-none">
            <div class="card text-white bg-neon-life mb-3">
                <div class="card-header d-flex justify-content-between align-items-center"><span><i
                            class="fas fa-comments"></i> Comment</span>
                    <?= $count_comment["COUNT(*)"] ?> <span class="badge badge-pill right"></span>
                </div>
                <div class="card-body">
                    <!--                        <h5 class="card-title">Info card title</h5>-->
                    <section class="d-flex justify-content-between align-items-center font-12">
                        <span class=""><i class="far fa-eye-slash"></i> Unseen <span class="badge badge-pill mx-1">
                                <?= $count_comment_unseen["COUNT(*)"] ?>
                            </span></span>
                        <span class=""><i class="far fa-check-circle"></i> Approved <span class="badge badge-pill mx-1">
                                <?= $count_comment_approved["COUNT(*)"] ?>
                            </span></span>
                    </section>
                </div>
            </div>
        </a>
    </div>

</div>


<div class="row mt-2">
    <div class="col-4">
        <h2 class="h6 pb-0 mb-0">
            Most viewed posts
        </h2>
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>title</th>
                        <th>view</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($most_view_posts as $most_view_post) { ?>
                        <tr>
                            <td>
                                <a class="text-primary" href="">
                                    <?= $most_view_post['id'] ?>
                                </a>
                            </td>
                            <td>
                                <?= $most_view_post['title'] ?>
                            </td>
                            <td><span class="badge badge-secondary">
                                    <?= $most_view_post['view'] ?>
                                </span></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-4">
        <h2 class="h6 pb-0 mb-0">
            Most commented posts

        </h2>
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>title</th>
                        <th>comment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($most_commented_posts as $most_commented_post) { ?>
                        <tr>
                            <td>
                                <a class="text-primary" href="">
                                    <?= $most_commented_post['id'] ?>
                                </a>
                            </td>
                            <td>
                                <?= $most_commented_post['title'] ?>
                            </td>
                            <td><span class="badge badge-success">
                                    <?= $most_commented_post['comment_count'] ?>
                                </span></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-4">
        <h2 class="h6 pb-0 mb-0">
            Comments
        </h2>
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>username</th>
                        <th>comment</th>
                        <th>status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($last_comments as $last_comment) { ?>
                        <tr>
                            <td>
                                <a class="text-primary" href="">
                                    <?= $last_comment['id'] ?>
                                </a>
                            </td>
                            <td>
                                <?= $last_comment['username'] ?>
                            </td>
                            <td>
                                <?= $last_comment['comment'] ?>
                            </td>
                            <td><span class="badge badge-warning">
                                    <?= $last_comment['status'] ?>
                                </span></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
require_once(BASE_PATH . '/template/admin/layouts/footer.php');
?>