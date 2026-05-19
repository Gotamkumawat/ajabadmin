<?php 
include('inc/header.php');
include('inc/sidebar.php');
?>
<head>
    <link rel="stylesheet" href="/common/lib/angular-multi-select/angular-multi-select.css">
</head>
<style>
    .container {
        width: 300px;
        margin: 50px auto;
        font-family: Arial, sans-serif;
    }

    select, input, textarea {
        width: 100%;
        padding: 8px;
        margin: 10px 0;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .multi-select-container {
        position: relative;
    }

    .popup-dropdown {
        display: none;
        position: absolute;
        top: 30px;
        left: 0;
        width: 100%;
        background-color: white;
        border: 1px solid #ccc;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 999;
        padding: 10px;
        max-height: 150px;
        overflow-y: auto;
    }

    .multi-select-container input[type="checkbox"] {
        margin-right: 10px;
    }

    .multi-select-container .action-buttons {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .action-buttons button {
        padding: 5px 10px;
        cursor: pointer;
        font-size: 14px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
    }

    .action-buttons .reset {
        background-color: #f44336;
    }

    .search-box {
        width: 100%;
        padding: 5px;
    }

    .selected-items {
        padding: 10px;
        border: 1px solid #ccc;
        margin-top: 10px;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        font-size: 14px;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 6px 12px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    textarea.form-control {
        resize: vertical;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 6px 20px;
        font-size: 16px;
        border-radius: 4px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .btn-primary:disabled {
        background-color: #6c757d;
        border-color: #6c757d;
        cursor: not-allowed;
    }

    .d-flex.align-items-center {
        display: flex;
        align-items: center;
    }

    .mr-2 {
        margin-right: 0.5rem;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    input[type="checkbox"] {
        width: auto;
        height: auto;
        margin-right: 10px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
        margin: 1px 2px !important;
        padding: 2px 4px !important;
    }
    .cke_notification {
        display: none;
    }

    .select2-container .select2-selection--multiple {
        height: 5px;
        padding-top: 2px;
        display: flex !important;
        align-items: center !important;
        font-size: 14px !important;
        border: 1px solid #ccc !important;
        border-radius: 4px !important;
    }

    .select2-selection__placeholder {
        font-size: 14px !important;
        line-height: normal !important;
        color: #777 !important;
    }

    .save-btn-container {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 0px;
    }

    .save-btn {
        position: relative;
        top: -20px;
        padding: 8px 30px;
        font-size: 16px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }
    
  /* popup */
    .swal2-popup {
        width: 360px !important;
        padding: 1.4rem !important;
        border-radius: 12px !important;
        font-size: 15px !important;
    }

    /* 🔹 Title & message styling */
    .swal2-title {
        font-size: 20px !important;
        font-weight: 600 !important;
        color: #222 !important;
    }

    .swal2-html-container {
        font-size: 14px !important;
        color: #444 !important;
        margin-top: 6px !important;
    }

    /* ✅ Perfect Success Icon */
    .swal2-icon.swal2-success {
        width: 80px !important;
        height: 80px !important;
        border: 4px solid #4CAF50 !important; /* strong green circle */
        border-radius: 50% !important;
        margin: 10px auto 14px auto !important;
        background: none !important;
        box-sizing: border-box !important;
        position: relative !important;
    }

    /* ✅ Proper tick mark thickness & placement */
    .swal2-success-line-tip,
    .swal2-success-line-long {
        background-color: #4CAF50 !important;
        height: 4px !important;
        border-radius: 2px !important;
    }

    /* ✅ Ensures full circle ring — no fade issue */
    .swal2-success-ring {
        border: 4px solid #4CAF50 !important;
        opacity: 1 !important;
        transform: scale(1.02) !important;
    }

    /* ✅ Confirm button */
    .swal2-confirm {
        background-color: #4c6ef5 !important;
        border-radius: 6px !important;
        font-size: 14px !important;
        padding: 6px 22px !important;
        font-weight: 500 !important;
        box-shadow: none !important;
        transition: 0.2s ease;
    }

    .swal2-confirm:hover {
        background-color: #3953c6 !important;
}
</style>


<!-- Content Wrapper -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Cartoon Details</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="add_new">Home</a></li>
                        <li class="breadcrumb-item active">Add Cartoon Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header" style="padding: 4px 8px;">
                    <a href="javascript:void(0);" class="btn btn-secondary" style="padding: 3px 8px; font-size: 13px; border-radius: 4px;" onclick="window.history.back();">
                        <i class="fas fa-arrow-left" style="font-size: 13px; margin-right: 4px;"></i> Back
                    </a>
                </div>

                <div class="card-body">
                    <form id="cartoonForm" method="post" action="<?= $form_action ?>">
                        <?php if(isset($cartoon['id'])): ?>
                            <input type="hidden" name="id" value="<?= $cartoon['id'] ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12"><div class="row"></div></div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>Title</label>
                                <input type="text" name="title" id="title" class="form-control"
                                       value="<?= isset($cartoon['title']) ? $cartoon['title'] : '' ?>"
                                       placeholder="Enter Title" required>
                            </div>

                            <?php
                            $cartThumbRaw = (isset($cartoon['thumbnail_url']) && $cartoon['thumbnail_url'] !== '') ? trim((string) $cartoon['thumbnail_url']) : '';
                            $cartThumbPreviewSrc = '';
                            if ($cartThumbRaw !== '') {
                                $cartThumbPreviewSrc = preg_match('#^https?://#i', $cartThumbRaw) ? $cartThumbRaw : (rtrim(base_url(), '/') . '/' . ltrim($cartThumbRaw, '/'));
                            }
                            ?>
                            <div class="col-md-3">
                                <label>Cartoon Thumbnail Url</label>
                                <input type="text" name="thumbnail_url" id="thumbnail_url" class="form-control"
                                       value="<?= isset($cartoon['thumbnail_url']) ? htmlspecialchars($cartoon['thumbnail_url']) : '' ?>"
                                       placeholder="Enter Cartoon Thumbnail Url" required>
                                <?php if ($cartThumbPreviewSrc !== ''): ?>
                                <div class="mt-2">
                                    <img src="<?= htmlspecialchars($cartThumbPreviewSrc) ?>" alt="Thumbnail preview" style="max-width:200px;height:auto;border:1px solid #ddd;border-radius:4px;"
                                        onerror="this.style.display='none';">
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-3">
                                <label>Publish</label>
                                <select name="is_published" id="is_published" class="form-control" style="height: 38px;" required>
                                    <option value="">Select</option>
                                    <option value="1" <?= (isset($cartoon['is_published']) && $cartoon['is_published']==1)?'selected':'' ?>>Yes</option>
                                    <option value="0" <?= (isset($cartoon['is_published']) && $cartoon['is_published']==0)?'selected':'' ?>>No</option>
                                </select>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: flex-end;">
                            <button type="submit" class="btn btn-primary"><?= $button_text ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- ✅ SweetAlert Validation + Success Popup -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('cartoonForm').addEventListener('submit', function(e) {
  e.preventDefault();

  // Only fields marked with red `*` in the form are required (currently none).
  const fields = [];

  for (let field of fields) {
    let el = document.getElementById(field.id);
    if (el && el.value.trim() === '') {
      Swal.fire({
        icon: 'warning',
        title: 'Missing Input',
        text: `Please fill the ${field.name}`,
        confirmButtonText: 'OK'
      });
      el.focus();
      return false;
    }
  }

  // Agar sab field fill hain, form submit hoga
  this.submit();
});

// ✅ Success Message show karega agar flashdata set hai
<?php if($this->session->flashdata('success')): ?>
Swal.fire({
  icon: 'success',
  title: 'Success!',
  text: '<?= $this->session->flashdata('success') ?>',
  showConfirmButton: false,  
  timer: 2500,              
  timerProgressBar: true    
});

<?php endif; ?>
</script>

<script type="text/javascript" src="/common/lib/angular/angular.min.js"></script>
<script type="text/javascript" src="/common/lib/angular-multi-select/angular-multi-select.js"></script>
<script type="text/javascript" src="/common/lib/textAngular/src/textAngular-sanitize.js"></script>
<script type="text/javascript" src="/common/lib/textAngular/src/textAngularSetup.js"></script>
<script type="text/javascript" src="/common/lib/textAngular/src/textAngular.js"></script>

<?php include('inc/footer.php'); ?>
