<?php
require_once(BASE_PATH . '/template/admin/layouts/header.php');
?>

<section class="pt-3 pb-1 mb-2 border-bottom">
    <h1 class="h5">Edit Banner</h1>
</section>

<section class="row my-3">
    <section class="col-12">

        <form method="POST" action="<?= url('admin/banner/update/' . $banner['id']) ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="url">Url</label>
                <input type="text" value="<?= $banner['url'] ?>" class="form-control" id="url" name="url" required
                    autofocus>
            </div>


            <div class="form-group">
                <label for="image">Image</label>
                <img style="width: 80px;" src="<?= asset($banner['image']) ?>" alt="">
                <input type="file" id="image" name="image" class="form-control-file" required autofocus>
            </div>

            <button type="submit" class="btn btn-primary btn-sm">Update</button>
        </form>
    </section>
</section>




<?php
require_once(BASE_PATH . '/template/admin/layouts/footer.php');
?>