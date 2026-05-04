<?php
include('inc/header.php');
include('inc/sidebar.php');
?>

<head>
    <link rel="stylesheet" href="/common/lib/angular-multi-select/angular-multi-select.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
</style>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>About Image</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('list') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Add/Edit About Image</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Card -->
            <div class="card card-default">
                <div class="card-header" style="padding: 4px 8px; margin: 0;">
                    <div>
                        <a href="<?= base_url('about-image-list') ?>" 
                           class="btn btn-secondary" 
                           style="padding: 3px 8px; font-size: 13px; border-radius: 4px;">
                            <i class="fas fa-arrow-left" style="font-size: 13px; margin-right: 4px;"></i> Back
                        </a>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                    <?php endif; ?>

                    <form name="aboutImagesForm" id="aboutImagesForm" method="post" action="<?php echo isset($about_image) ? base_url('AddAboutController/about_image_update/' . $about_image->id) : base_url('AddAboutController/about_images_save'); ?>">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="row"></div>
                            </div>
                            <div class="row"></div>
                        </div>
                        <?php
                        $aboutImgThumbRaw = (isset($about_image) && !empty($about_image->thumbnail_url)) ? trim((string) $about_image->thumbnail_url) : '';
                        $aboutImgThumbSrc = '';
                        if ($aboutImgThumbRaw !== '') {
                            $aboutImgThumbSrc = preg_match('#^https?://#i', $aboutImgThumbRaw) ? $aboutImgThumbRaw : (rtrim(base_url(), '/') . '/' . ltrim($aboutImgThumbRaw, '/'));
                        }
                        ?>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Thumbnail URL</label>
                                <input type="text" name="thumbnail_url" id="thumbnail_url" ng-model="song.thumbnailUrl" class="form-control" ng-required="song.isAuthoringComplete" placeholder="Enter Thumbnail URL" value="<?php echo isset($about_image->thumbnail_url) ? htmlspecialchars($about_image->thumbnail_url) : ''; ?>">
                                <error-message name="Thumbnail URL" show-error="song.isAuthoringComplete && isEmpty(song.thumbnailUrl)"></error-message>
                                <?php if ($aboutImgThumbSrc !== ''): ?>
                                <div class="mt-2">
                                    <img src="<?php echo htmlspecialchars($aboutImgThumbSrc); ?>" alt="Thumbnail preview" style="max-width:220px;height:auto;border:1px solid #ddd;border-radius:4px;"
                                        onerror="this.style.display='none';">
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Description</label>
                                <textarea name="image_description" id="image_description" ng-model="song.imageDescription" class="form-control" ng-required="song.isAuthoringComplete" placeholder="Enter Description"><?php echo isset($about_image->image_description) ? $about_image->image_description : ''; ?></textarea>
                                <error-message name="Description" show-error="song.isAuthoringComplete && isEmpty(song.imageDescription)"></error-message>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Order No</label>
                                <select name="sort_order_no" id="sort_order_no" ng-model="song.sortOrderNo" class="form-control" style="height: 38px;">
                                    <option value="">Select</option>
                                    <option value="1" <?php echo (isset($about_image->sort_order_no) && $about_image->sort_order_no == '1') ? 'selected' : ''; ?>>1</option>
                                    <option value="2" <?php echo (isset($about_image->sort_order_no) && $about_image->sort_order_no == '2') ? 'selected' : ''; ?>>2</option>
                                    <option value="3" <?php echo (isset($about_image->sort_order_no) && $about_image->sort_order_no == '3') ? 'selected' : ''; ?>>3</option>
                                    <option value="4" <?php echo (isset($about_image->sort_order_no) && $about_image->sort_order_no == '4') ? 'selected' : ''; ?>>4</option>
                                    <option value="5" <?php echo (isset($about_image->sort_order_no) && $about_image->sort_order_no == '5') ? 'selected' : ''; ?>>5</option>
                                    <option value="6" <?php echo (isset($about_image->sort_order_no) && $about_image->sort_order_no == '6') ? 'selected' : ''; ?>>6</option>
                                    <option value="7" <?php echo (isset($about_image->sort_order_no) && $about_image->sort_order_no == '7') ? 'selected' : ''; ?>>7</option>
                                    <option value="8" <?php echo (isset($about_image->sort_order_no) && $about_image->sort_order_no == '8') ? 'selected' : ''; ?>>8</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>About Header</label>
                                <select name="about_header_id" id="about_header_id" ng-model="song.aboutHeaderId" class="form-control" style="height: 38px;">
                                    <option value="">Select</option>

                                    <!-- 🔹 Added 5 Static Options -->
                                    <option value="101">About Us</option>
                                    <option value="102">Our Mission</option>
                                    <option value="103">Our Vision</option>
                                    <option value="104">Team Members</option>
                                    <option value="105">Contact Information</option>

                                    <!-- 🔹 Dynamic Options from Database -->
                                    <?php foreach ($about_headers as $header): ?>
                                        <option value="<?php echo $header->id; ?>" 
                                            <?php echo (isset($about_image->about_header_id) && $about_image->about_header_id == $header->id) ? 'selected' : ''; ?>>
                                            <?php echo $header->new_menu; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Publish</label>
                                <select name="is_published" id="is_published" ng-model="song.isPublished" class="form-control" style="height: 38px;">
                                    <option value="">Select</option>
                                    <option value="true" <?php echo (isset($about_image->is_published) && $about_image->is_published == 'true') ? 'selected' : ''; ?>>Yes</option>
                                    <option value="false" <?php echo (isset($about_image->is_published) && $about_image->is_published == 'false') ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="save-btn-container">
                            <button type="submit" class="btn btn-primary save-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="/common/lib/angular/angular.min.js"></script>
<script type="text/javascript" src="/common/lib/angular-multi-select/angular-multi-select.js"></script>
<script type="text/javascript" src="/common/lib/textAngular/src/textAngular-sanitize.js"></script>
<script type="text/javascript" src="/common/lib/textAngular/src/textAngularSetup.js"></script>
<script type="text/javascript" src="/common/lib/textAngular/src/textAngular.js"></script>

<script>
angular.module('aboutImagesApp', [])
    .controller('AboutImagesController', function($scope) {
        $scope.song = {
            isAuthoringComplete: true,
            thumbnailUrl: '',
            imageDescription: '',
            sortOrderNo: '',
            aboutHeaderId: '',
            isPublished: ''
        };
        $scope.isEmpty = function(value) {
            return !value || value.trim() === '';
        };
    })
    .directive('errorMessage', function() {
        return {
            restrict: 'E',
            scope: {
                name: '@',
                showError: '='
            },
            template: '<span class="text-danger" ng-show="showError">Please enter a valid {{name}}</span>'
        };
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
}

function selectNone(button) {
    const checkboxes = button.closest('.popup-dropdown').querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => cb.checked = false);
}

function resetSelection(button) {
    const checkboxes = button.closest('.popup-dropdown').querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => cb.checked = false);
    const selectElem = button.closest('.multi-select-container').querySelector('select');
    selectElem.value = '';
}

$(document).ready(function() {
    $('#aboutImagesForm').on('submit', function(e) {
        e.preventDefault();

        const fields = [
            { id: 'thumbnail_url', name: 'Thumbnail URL' },
            { id: 'image_description', name: 'Description' },
            { id: 'sort_order_no', name: 'Order No' },
            { id: 'about_header_id', name: 'About Header' },
            { id: 'is_published', name: 'Publish' }
        ];

        for (let field of fields) {
            let value = document.getElementById(field.id).value.trim();
            if (value === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Input',
                    text: `Please fill the ${field.name}`,
                    confirmButtonText: 'OK'
                });
                document.getElementById(field.id).focus();
                return false;
            }
        }

        this.submit();
    });
});
</script>

<?php include('inc/footer.php'); ?>