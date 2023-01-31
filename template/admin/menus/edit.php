<?php
require_once(BASE_PATH . '/template/admin/layouts/header.php');
?>

<section class="pt-3 pb-1 mb-2 border-bottom">
    <h1 class="h5">Edit Menu</h1>
</section>

<section class="row my-3">
    <section class="col-12">
        <form method="post" action="<?= url('admin/menu/update/' . $menu['id']) ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" value="<?= $menu['name'] ?>" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="url">url</label>
                <input type="text" value="<?= $menu['url'] ?>" class="form-control" id="url" name="url" required>
            </div>

            <div class="form-group">
                <label for="parent_id">parent ID</label>
                <select name="parent_id" id="parent_id" class="form-control" autofocus>
                    <option value="" <?php if ($menu['parent_id'] == '')
                        echo 'selected' ?>>root</option>
                    <?php foreach ($menus as $select_menu) {
                        if ($menu['id'] != $select_menu['id']) { ?>
                            <option value="<?= $select_menu['id'] ?>" <?php if ($menu['parent_id'] == $select_menu['id'])
                                  echo 'selected' ?>><?= $select_menu['name'] ?></option>
                        <?php }
                    } ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-sm">Update</button>
        </form>
    </section>
</section>

<?php
require_once(BASE_PATH . '/template/admin/layouts/footer.php');
?>