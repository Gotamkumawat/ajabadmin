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
                    <h1 class="m-0">About Main</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('list') ?>">Home</a></li>
                        <li class="breadcrumb-item active">About Main</li>
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
                    <h3 class="card-title">About Page Content</h3>
                </div>
                <div class="card-body">
                    <form name="aboutMainForm" id="aboutMainForm" method="post" action="<?= base_url('AboutController/save_main') ?>" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo isset($about_main) ? $about_main->id : ''; ?>">
                        
                        <!-- Meta Data Section -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h4>Meta Data</h4>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text" name="meta_title" id="meta_title" class="form-control" 
                                                   value="<?php echo isset($about_main) ? htmlspecialchars($about_main->meta_title) : ''; ?>" 
                                                   placeholder="Enter meta title">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="meta_keywords">Meta Keywords</label>
                                            <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" 
                                                   value="<?php echo isset($about_main) ? htmlspecialchars($about_main->meta_keywords) : ''; ?>" 
                                                   placeholder="Enter meta keywords (comma separated)">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="meta_description">Meta Description</label>
                                            <textarea name="meta_description" id="meta_description" class="form-control" rows="4" 
                                                      placeholder="Enter meta description"><?php echo isset($about_main) ? htmlspecialchars($about_main->meta_description) : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Main Content Section -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h4>Main Content</h4>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="main_content">Main Content</label>
                                            <textarea name="main_content" id="main_content" class="form-control" rows="8" 
                                                      placeholder="Enter main content"><?php echo isset($about_main) ? htmlspecialchars($about_main->main_content) : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="save-btn-container">
                            <button type="submit" class="btn btn-primary">Save About Main</button>
                            <a href="<?= base_url('list') ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include('inc/footer.php'); ?>
