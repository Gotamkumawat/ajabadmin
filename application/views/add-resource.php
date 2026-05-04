<?php
include('inc/header.php');
include('inc/sidebar.php');
?>
<head>
    <link rel="stylesheet" href="/common/lib/angular-multi-select/angular-multi-select.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
</head>
<style>
    /* Clean multi-select box look */
    .select2-container--default .select2-selection--multiple {
        min-height: 38px;
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 4px 6px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        overflow: hidden;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border: none;
        color: #fff;
        padding: 2px 6px;
        margin: 2px;
        border-radius: 4px;
        font-size: 12px;
        max-width: calc(100% - 10px);
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .select2-container--default .select2-selection--multiple::-webkit-scrollbar {
        display: none;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        margin-right: 4px;
    }

    .multi-select-container {
        max-width: 100%;
    }

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
        height: 38px;
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

    .alert {
        margin-bottom: 1rem;
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

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo isset($resource) ? 'Edit Resource Details' : 'Add Resource Details'; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('add_new'); ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo isset($resource) ? 'Edit Resource Details' : 'Add Resource Details'; ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
                <div class="card-header" style="padding: 4px 8px; margin: 0;">
                    <div>
                        <a href="<?php echo base_url('add_new'); ?>" 
                           class="btn btn-secondary" 
                           style="padding: 3px 8px; font-size: 13px; border-radius: 4px;">
                            <i class="fas fa-arrow-left" style="font-size: 13px; margin-right: 4px;"></i> Back
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                    <?php endif; ?>
                    
                    <form name="songForm" method="post" action="<?php echo base_url('resource/save'); ?>">
                        <input type="hidden" name="id" value="<?php echo isset($resource->id) ? $resource->id : ''; ?>">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="row"></div>
                            </div>
                            <div class="row"></div>
                        </div>

                        <!-- Singers, Words, Reflections, Couplets -->
                        <div class="row form-group">
                            <div class="col-md-3" style="margin-top: 2px">
                                <label>Resource Category</label>
                                <div class="multi-select-container">
                                    <select class="select2" multiple="multiple" data-placeholder="Select Resource Category" name="resource_category[]" id="resource_category" style="width: 100%;" onclick="togglePopup(this)">
                                        <option value="">None Selected</option>
                                        <option value="1" <?php echo (isset($resource->resource_category) && in_array('1', explode(',', $resource->resource_category))) ? 'selected' : ''; ?>>Die before your death</option>
                                        <option value="2" <?php echo (isset($resource->resource_category) && in_array('2', explode(',', $resource->resource_category))) ? 'selected' : ''; ?>>Live without regrets</option>
                                        <option value="3" <?php echo (isset($resource->resource_category) && in_array('3', explode(',', $resource->resource_category))) ? 'selected' : ''; ?>>Chase your dreams</option>
                                        <option value="4" <?php echo (isset($resource->resource_category) && in_array('4', explode(',', $resource->resource_category))) ? 'selected' : ''; ?>>Silence is golden</option>
                                        <option value="5" <?php echo (isset($resource->resource_category) && in_array('5', explode(',', $resource->resource_category))) ? 'selected' : ''; ?>>Happiness is a choice</option>
                                    </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button onclick="selectAll(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#007bff;color:#fff;border:none;cursor:pointer;">Select All</button>
                                            <button onclick="selectNone(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#17a2b8;color:#fff;border:none;cursor:pointer;">Select None</button>
                                            <button onclick="resetSelection(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#dc3545;color:#fff;border:none;cursor:pointer;">Reset</button>
                                        </div>
                                        <div class="optionsList">
                                            <label><input type="checkbox" value="1"> Die before your death</label><br>
                                            <label><input type="checkbox" value="2"> Live without regrets</label><br>
                                            <label><input type="checkbox" value="3"> Chase your dreams</label><br>
                                            <label><input type="checkbox" value="4"> Silence is golden</label><br>
                                            <label><input type="checkbox" value="5"> Happiness is a choice</label><br>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label>Main Title</label>
                                <input type="text" name="main_title" id="main_title" ng-model="song.duration" class="form-control" ng-required="song.isAuthoringComplete" placeholder="Enter Main Title" value="<?php echo isset($resource->main_title) ? htmlspecialchars($resource->main_title) : ''; ?>">
                                <error-message name="Duration" show-error="song.isAuthoringComplete && isEmpty(song.duration)"></error-message>
                            </div>
                            <div class="col-md-3">
                                <label>Second Title</label>
                                <input type="text" name="second_title" id="second_title" ng-model="song.duration" class="form-control" ng-required="song.isAuthoringComplete" placeholder="Enter Second Title" value="<?php echo isset($resource->second_title) ? htmlspecialchars($resource->second_title) : ''; ?>">
                                <error-message name="Duration" show-error="song.isAuthoringComplete && isEmpty(song.duration)"></error-message>
                            </div>
                            <div class="col-md-3">
                                <label>Info</label>
                                <input type="text" name="info" id="info" ng-model="song.duration" class="form-control" ng-required="song.isAuthoringComplete" placeholder="Enter Info" value="<?php echo isset($resource->info) ? htmlspecialchars($resource->info) : ''; ?>">
                                <error-message name="Duration" show-error="song.isAuthoringComplete && isEmpty(song.duration)"></error-message>
                            </div>
                            <div class="col-md-3">
                                <label>Author/Label</label>
                                <input type="text" name="resource_author_name" id="resource_author_name" ng-model="song.duration" class="form-control" ng-required="song.isAuthoringComplete" placeholder="Enter Author/Label" value="<?php echo isset($resource->resource_author_name) ? htmlspecialchars($resource->resource_author_name) : ''; ?>">
                                <error-message name="Duration" show-error="song.isAuthoringComplete && isEmpty(song.duration)"></error-message>
                            </div>
                            <?php
                            $resThumbRaw = (isset($resource) && !empty($resource->thumbnail_url)) ? trim((string) $resource->thumbnail_url) : '';
                            $resThumbPreviewSrc = '';
                            if ($resThumbRaw !== '') {
                                $resThumbPreviewSrc = preg_match('#^https?://#i', $resThumbRaw) ? $resThumbRaw : (rtrim(base_url(), '/') . '/' . ltrim($resThumbRaw, '/'));
                            }
                            ?>
                            <div class="col-md-3">
                                <label>Thumbnail URL</label>
                                <input type="text" name="thumbnail_url" id="thumbnail_url" ng-model="song.duration" class="form-control" ng-required="song.isAuthoringComplete" placeholder="Enter Thumbnail URL" value="<?php echo isset($resource->thumbnail_url) ? htmlspecialchars($resource->thumbnail_url) : ''; ?>">
                                <error-message name="Thumbnail URL" show-error="song.isAuthoringComplete && isEmpty(song.duration)"></error-message>
                                <?php if ($resThumbPreviewSrc !== ''): ?>
                                <div class="mt-2">
                                    <img src="<?php echo htmlspecialchars($resThumbPreviewSrc); ?>" alt="Thumbnail preview" style="max-width:200px;height:auto;border:1px solid #ddd;border-radius:4px;"
                                        onerror="this.style.display='none';">
                                </div>
                                <?php endif; ?>
                            </div>
                            <!-- Publish -->
                            <div class="col-md-3">
                                <label>Publish</label>
                                <select name="is_published" id="is_published" ng-model="song.showOnLandingPage" class="form-control" style="height: 38px;">
                                    <option value="">Select</option>
                                    <option value="true" <?php echo (isset($resource->is_published) && $resource->is_published == 1) ? 'selected' : ''; ?>>Yes</option>
                                    <option value="false" <?php echo (isset($resource->is_published) && $resource->is_published == 0) ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>
                        </div>
                        <!-- Profile -->
                        <div class="row form-group">
                            <div class="col-md-2"><label>Description</label></div>
                            <div class="col-md-10">
                                <div class="card">
                                    <div class="card-body">
                                        <textarea id="songLyricsOriginal" name="description" ng-model="song.songText.original"><?php echo isset($resource->description) ? htmlspecialchars($resource->description) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="save-btn-container">
                            <button type="submit" ng-click="saveData()" ng-disabled="!songForm.$valid" class="btn btn-primary btn-lg save-btn">
                                <?php echo isset($resource) ? 'Update' : 'Save'; ?>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select Resource Category",
        allowClear: true
    });

    // Sync Select2 with popup checkboxes
    $('.optionsList input[type="checkbox"]').on('change', function() {
        let selected = [];
        $('.optionsList input[type="checkbox"]:checked').each(function() {
            selected.push($(this).val());
        });
        $('#resource_category').val(selected).trigger('change');
    });

    $('#resource_category').on('change', function() {
        let selected = $(this).val() || [];
        $('.optionsList input[type="checkbox"]').each(function() {
            $(this).prop('checked', selected.includes($(this).val()));
        });
    });
});

function togglePopup(selectElem) {
    const popup = selectElem.parentElement.querySelector('.popup-dropdown');
    document.querySelectorAll('.popup-dropdown').forEach(p => {
        if (p !== popup) p.style.display = 'none';
    });
    popup.style.display = (popup.style.display === 'block') ? 'none' : 'block';
}

function filterOptions(inputElem) {
    const filter = inputElem.value.toLowerCase();
    const options = inputElem.parentElement.querySelectorAll('.optionsList label');
    options.forEach(label => {
        label.style.display = label.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
}

function selectAll(button) {
    const checkboxes = button.closest('.popup-dropdown').querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => cb.checked = true);
    $('#resource_category').val([...checkboxes].map(cb => cb.value)).trigger('change');
}

function selectNone(button) {
    const checkboxes = button.closest('.popup-dropdown').querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => cb.checked = false);
    $('#resource_category').val([]).trigger('change');
}

function resetSelection(button) {
    const checkboxes = button.closest('.popup-dropdown').querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => cb.checked = false);
    $('#resource_category').val([]).trigger('change');
}
</script>

<script>
setTimeout(function () {
    CKEDITOR.replace('songLyricsOriginal', {
        height: 200,
        extraPlugins: 'colorbutton,font,justify',
        toolbar: [
            { name: 'document', items: ['Source', '-', 'NewPage', 'Preview'] },
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
            { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
            { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] }
        ]
    });

    // AngularJS model sync (if AngularJS is used)
    CKEDITOR.instances['songLyricsOriginal'].on('change', function () {
        var data = CKEDITOR.instances['songLyricsOriginal'].getData();
        var scope = angular.element(document.querySelector('#songLyricsOriginal')).scope();
        if (scope) {
            scope.$apply(function () {
                scope.song.songText.original = data;
            });
        }
    });
}, 500);

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

<!-- Uncomment if AngularJS is needed -->
<!-- <script type="text/javascript" src="/common/lib/angular/angular.min.js"></script>
<script type="text/javascript" src="/common/lib/angular-multi-select/angular-multi-select.js"></script>
<script type="text/javascript" src="/common/lib/textAngular/src/textAngular-sanitize.js"></script>
<script type="text/javascript" src="/common/lib/textAngular/src/textAngularSetup.js"></script>
<script type="text/javascript" src="/common/lib/textAngular/src/textAngular.js"></script> -->

<?php 
include('inc/footer.php');
?>