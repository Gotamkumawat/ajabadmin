<?php
include('inc/header.php');
include('inc/sidebar.php');
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo !empty($occupation) ? 'Edit Occupation' : 'Add Occupation'; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('list'); ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo !empty($occupation) ? 'Edit Occupation' : 'Add Occupation'; ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header" style="padding: 4px 8px; margin: 0;">
                    <a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-secondary" style="padding: 3px 8px; font-size: 13px; border-radius: 4px;">
                        <i class="fas fa-arrow-left" style="font-size: 13px; margin-right: 4px;"></i> Back
                    </a>
                </div>

                <div class="card-body">
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                    <?php endif; ?>

                    <form method="post" action="<?php echo base_url('occupation/save'); ?>" id="occupationForm">
                        <?php if (!empty($occupation) && isset($occupation['id'])): ?>
                            <input type="hidden" name="id" value="<?php echo (int)$occupation['id']; ?>">
                        <?php endif; ?>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Occupation Name <span style="color:red">*</span></label>
                            <div class="col-md-4">
                                <input
                                    type="text"
                                    name="name"
                                    id="occupation_name"
                                    class="form-control"
                                    value="<?php echo !empty($occupation['name']) ? htmlspecialchars($occupation['name']) : ''; ?>"
                                    placeholder="Enter Occupation Name"
                                    required
                                >
                            </div>
                        </div>

                        <div class="save-btn-container" style="display:flex;justify-content:flex-end;">
                            <button type="submit" class="btn btn-primary">
                                <?php echo !empty($occupation) ? 'Update Occupation' : 'Save Occupation'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function() {
    $('#occupationForm').on('submit', function(e) {
        var val = $('#occupation_name').val().trim();
        if (!val) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Missing Input',
                text: 'Please enter occupation name'
            });
            $('#occupation_name').focus();
            return false;
        }
    });
});
</script>

<?php include('inc/footer.php'); ?>

