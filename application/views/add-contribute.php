<?php 
include 'inc/header.php';
include 'inc/sidebar.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1><?= isset($contribute) ? 'Edit Contribution' : 'Add Contribution' ?></h1>
        </div>
    </section>
<section class="content">
    <div class="container-fluid">
        <form method="post" action="<?= $action_url ?>">
            <div class="card card-default">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Category</label>
                            <input type="text" name="category" id="main_title" class="form-control" 
                                   value="<?= isset($contribute) ? $contribute->category : '' ?>" 
                                   placeholder="Enter Main Category">
                        </div>

                        <div class="col-md-3">
                            <label>Title</label>
                            <input type="text" name="title" id="main_title" class="form-control" 
                                   value="<?= isset($contribute) ? $contribute->title : '' ?>" 
                                   placeholder="Enter Main Title">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Content</label>
                            <textarea class="form-control" name="content" rows="5" placeholder="Enter full content here..."><?= isset($contribute) ? $contribute->content : '' ?></textarea>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="publishCheck" name="is_publish" 
                                       <?= isset($contribute) && $contribute->is_publish ? 'checked' : '' ?>>
                                <label class="form-check-label" for="publishCheck">Publish</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success">
                        <?= isset($contribute) ? 'Update' : 'Save' ?>
                    </button>
                    <a href="<?= base_url('contributions-list') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</section>

</div>

<?php include 'inc/footer.php'; ?>
