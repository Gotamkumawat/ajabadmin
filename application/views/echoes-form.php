<?php include 'inc/header.php'; include 'inc/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Edit Echo</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form method="post" action="<?= $action_url ?>">
                <div class="card card-default">
                    <div class="card-body row">
                        <div class="col-md-4">
                            <label>Category</label>
                            <input type="text" name="category" class="form-control" 
                                value="<?= isset($echo) ? $echo->category : '' ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" 
                                value="<?= isset($echo) ? $echo->title : '' ?>" required>
                        </div>

                        <div class="col-md-4 d-flex align-items-center mt-4">
                            <div class="form-check">
                                <input type="checkbox" name="is_publish" class="form-check-input" 
                                       <?= isset($echo) && $echo->is_publish ? 'checked' : '' ?>>
                                <label class="form-check-label">Published</label>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="<?= base_url('echoes') ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<?php include 'inc/footer.php'; ?>
