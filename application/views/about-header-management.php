<?php
include('inc/header.php');
include('inc/sidebar.php');
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">About Header Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('list') ?>">Home</a></li>
                        <li class="breadcrumb-item active">About Header Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
            <?php endif; ?>
            
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">About Header Options</h3>
                </div>
                <div class="card-body">
                    <form name="aboutHeaderForm" id="aboutHeaderForm" method="post" action="<?= base_url('AboutController/save_header') ?>" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo isset($about_header) ? $about_header->id : ''; ?>">
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="new_menu">Menu Name</label>
                                <input type="text" name="new_menu" id="new_menu" class="form-control" 
                                       value="<?php echo isset($about_header) ? htmlspecialchars($about_header->new_menu) : ''; ?>" 
                                       placeholder="Enter menu name">
                            </div>
                            
                            <div class="col-md-6 form-group">
                                <label for="logo_image">Logo/Image</label>
                                <input type="file" name="logo_image" id="logo_image" class="form-control-file" accept="image/*">
                                <?php if (isset($about_header) && !empty($about_header->logo_image)): ?>
                                    <div class="mt-2">
                                        <img src="<?= base_url('uploads/about_headers/' . $about_header->logo_image) ?>" 
                                             alt="Current logo" style="max-width: 100px; height: auto; border: 1px solid #ddd;">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="display_order">Display Order</label>
                                <input type="number" name="display_order" id="display_order" class="form-control" 
                                       value="<?php echo isset($about_header) ? $about_header->display_order : ''; ?>" 
                                       placeholder="Enter display order" min="1">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>
                                    <input type="checkbox" name="is_active" id="is_active" 
                                           <?php echo (isset($about_header) && $about_header->is_active == '1') ? 'checked' : ''; ?> >
                                    Active
                                </label>
                            </div>
                        </div>
                        
                        <div class="save-btn-container">
                            <button type="submit" class="btn btn-primary">Save Header</button>
                            <a href="<?= base_url('about-header-list') ?>" class="btn btn-secondary">View All Headers</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include('inc/footer.php'); ?>
