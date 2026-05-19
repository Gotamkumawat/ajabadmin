<?php 
include('inc/header.php');
include('inc/sidebar.php');

// Show success message if set in session (works for all forms)
if (isset($_SESSION['success']) && $_SESSION['success']) {
    echo '<script>window.addEventListener("DOMContentLoaded", function() { Swal.fire({ icon: "success", title: "Upload successful", text: "'.addslashes($_SESSION['success']).'", confirmButtonText: "OK" }); });</script>';
    unset($_SESSION['success']);
}

// Check if edit mode (id is provided via $news from controller)
$edit_mode = isset($news) && !empty($news);
$popup_items = [];
if ($edit_mode && !empty($news['popup_items_array'])) {
    $popup_items = $news['popup_items_array'];
}
$news = $edit_mode ? $news : array(
    'id' => '',
    'popup_items' => ''
);
?>

<head>
    <!-- <link rel="stylesheet" href="/common/lib/angular-multi-select/angular-multi-select.css">
    <link rel="stylesheet" href="/common/lib/select2/select2.min.css"> -->
</head>
<style>
    /* Clean multi-select box look */
    .select2-container--default .select2-selection--multiple {
        min-height: 38px;
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 4px 6px;
        display: flex;
        flex-wrap: wrap; /* allows proper wrapping */
        align-items: center;
        overflow: hidden; /* remove scrollbar */
    }

    /* Smaller and clean selected tags */
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

    /* Remove scrollbar completely */
    .select2-container--default .select2-selection--multiple::-webkit-scrollbar {
        display: none;
    }

    /* Adjust close icon spacing */
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        margin-right: 4px;
    }

    /* Responsive container */
    .multi-select-container {
        max-width: 100%;
    }

    .container {
        width: 300px;
        margin: 50px auto;
        font-family: Arial, sans-serif;
    }

    select, input:not([type="file"]), textarea {
        width: 100%;
        padding: 8px;
        margin: 10px 0;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    /* File input styling */
    input[type="file"] {
        display: block;
        width: 100%;
        margin: 10px 0;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        cursor: pointer;
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

    /* Additional CSS for new form sections */
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

    .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
        margin: 1px 2px !important;
        padding: 2px 4px !important;
    }
    .cke_notification {
        display: none;
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
                    <h1><?php echo $edit_mode ? 'Edit News Details' : 'Enter News Details'; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('add_new'); ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo $edit_mode ? 'Edit News Details' : 'Add News Details'; ?></li>
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
                    <!-- News Items Menu Tabs -->
                    <!-- Only one News Item section, tabs removed -->
                    <form name="newsForm" method="post" action="<?php echo base_url('NewsController/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($news['id']); ?>">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="row">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-6">
                            </div>
                        </div>
                        <!-- News Item 1 Section -->
                        <div id="section-1" class="popup-section" style="margin-left: 0; margin-right: auto; display:block;">
                            <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                                <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Category</div>
                                <div style="flex: 1;">
                                    <select name="item_1_category" class="form-control category-select col-md-4" data-target="1">
                                        <option value="">Select Category</option>
                                        <option value="single_small" <?php echo (isset($popup_items[0]['category']) && $popup_items[0]['category'] === 'single_small') ? 'selected' : ''; ?>>SINGLE IMAGE (SMALL)</option>
                                        <option value="single_large" <?php echo (isset($popup_items[0]['category']) && $popup_items[0]['category'] === 'single_large') ? 'selected' : ''; ?>>SINGLE IMAGE (LARGE)</option>
                                        <option value="multi_carousel" <?php echo (isset($popup_items[0]['category']) && $popup_items[0]['category'] === 'multi_carousel') ? 'selected' : ''; ?>>MULTI IMAGE CAROUSEL</option>
                                        <option value="video" <?php echo (isset($popup_items[0]['category']) && $popup_items[0]['category'] === 'video') ? 'selected' : ''; ?>>VIDEO</option>
                                        <option value="text" <?php echo (isset($popup_items[0]['category']) && $popup_items[0]['category'] === 'text') ? 'selected' : ''; ?>>TEXT ONLY</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row media-single" style="display: none; align-items: center; margin-bottom: 18px;">
                                <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Thumbnail Image (for pop up)</div>
                                <div style="flex: 1;">
                                    <input type="file" name="item_1_image" class="form-control col-md-4" style="width:100%;" accept="image/*">
                                    <?php if (isset($popup_items[0]['image']) && !empty($popup_items[0]['image'])):
                                        $nwThumbPath = trim((string) $popup_items[0]['image']);
                                        $nwThumbSrc = preg_match('#^https?://#i', $nwThumbPath) ? $nwThumbPath : (rtrim(base_url(), '/') . '/' . ltrim($nwThumbPath, '/'));
                                    ?>
                                        <div class="mt-2">
                                            <img src="<?php echo htmlspecialchars($nwThumbSrc); ?>" alt="Current thumbnail" style="max-width:220px;height:auto;border:1px solid #ddd;border-radius:4px;"
                                                onerror="this.style.display='none';">
                                        </div>
                                        <small>Current: <a href="<?php echo htmlspecialchars($nwThumbSrc); ?>" target="_blank">View Image</a></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-row media-single" style="display: none; align-items: center; margin-bottom: 18px;">
                                <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Image Size</div>
                                <div style="flex: 1;">
                                    <select name="item_1_image_size" class="form-control col-md-4">
                                        <option value="">Select</option>
                                        <option value="small" <?php echo (isset($popup_items[0]['image_size']) && $popup_items[0]['image_size'] === 'small') ? 'selected' : ''; ?>>Small</option>
                                        <option value="big" <?php echo (isset($popup_items[0]['image_size']) && $popup_items[0]['image_size'] === 'big') ? 'selected' : ''; ?>>Big</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row media-multiple" style="display: none; align-items: center; margin-bottom: 18px;">
                                <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Upload Multiple Images (Image)</div>
                                <div style="flex: 1;">
                                    <input type="file" name="item_1_images[]" class="form-control col-md-4" accept="image/*" multiple>
                                    <?php if (isset($popup_items[0]['images']) && !empty($popup_items[0]['images'])): ?>
                                        <small>Current images uploaded: <?php echo count($popup_items[0]['images']); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-row media-video" style="display: none; align-items: center; margin-bottom: 18px;">
                                <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Video Link (YouTube/Vimeo URL)</div>
                                <div style="flex: 1;">
                                    <input type="url" name="item_1_video" class="form-control col-md-4" placeholder="https://youtube.com/..." value="<?php echo isset($popup_items[0]['video_url']) ? htmlspecialchars($popup_items[0]['video_url']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                                <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">News Title</div>
                                <div style="flex: 1;">
                                    <input type="text" name="item_1_title" class="form-control col-md-4" value="<?php echo isset($popup_items[0]['title']) ? htmlspecialchars($popup_items[0]['title']) : ''; ?>">
                                </div>
                            </div>
                            <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                                <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">News Second Title</div>
                                <div style="flex: 1;">
                                    <input type="text" name="item_1_second_title" class="form-control  col-md-4" value="<?php echo isset($popup_items[0]['second_title']) ? htmlspecialchars($popup_items[0]['second_title']) : ''; ?>">
                                </div>
                            </div>
                            <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                                <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">News Content</div>
                                <div style="flex: 1;">
                                     <textarea name="item_1_content" id="item_1_content" class="form-control col-md-8" rows="9" style="width:100%;"><?php echo isset($popup_items[0]['content']) ? htmlspecialchars($popup_items[0]['content']) : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                                <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Published (y/n)</div>
                                <div style="flex: 1;">
                                    <select name="item_1_published" class="form-control col-md-4">
                                        <option value="">Select</option>
                                        <option value="1" <?php echo (isset($popup_items[0]['published']) && $popup_items[0]['published'] == '1') ? 'selected' : ''; ?>>Yes</option>
                                        <option value="0" <?php echo (isset($popup_items[0]['published']) && $popup_items[0]['published'] == '0') ? 'selected' : ''; ?>>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                                <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Sequence order</div>
                                <div style="flex: 1;">
                                    <input type="number" name="item_1_sequence_order" class="form-control col-md-4" value="<?php echo isset($popup_items[0]['sequence_order']) ? htmlspecialchars($popup_items[0]['sequence_order']) : ''; ?>">
                                </div>
                            </div>
                            <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                                <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Show on Home Popup?</div>
                                <div style="flex: 1;">
                                    <select name="item_1_show_on_home" class="form-control col-md-4">
                                        <option value="">Select</option>
                                        <option value="1" <?php echo (isset($popup_items[0]['show_on_home']) && $popup_items[0]['show_on_home'] == '1') ? 'selected' : ''; ?>>Yes</option>
                                        <option value="0" <?php echo (isset($popup_items[0]['show_on_home']) && $popup_items[0]['show_on_home'] == '0') ? 'selected' : ''; ?>>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Removed Add More button and related row -->

                        <div style="display: flex; justify-content: flex-end;">
                            <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Update' : 'Save'; ?></button>
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

<script>
// Category-based media toggle
function initCategoryToggles() {
    const selects = document.querySelectorAll('.category-select');

    selects.forEach(select => {
        // Find the closest section (popup-section)
        const section = select.closest('.popup-section');
        if (!section) return;
        // Find the media fields within this section
        const single = section.querySelector('.media-single');
        const multiple = section.querySelector('.media-multiple');
        const video = section.querySelector('.media-video');

        const applyState = () => {
            const val = select.value;
            if (single) single.style.display = (val === 'single') ? 'block' : 'none';
            if (multiple) multiple.style.display = (val === 'multiple') ? 'block' : 'none';
            if (video) video.style.display = (val === 'video') ? 'block' : 'none';
            if (val === 'text' || val === '') {
                if (single) single.style.display = 'none';
                if (multiple) multiple.style.display = 'none';
                if (video) video.style.display = 'none';
            }
        };

        select.addEventListener('change', applyState);
        applyState();
    });
}

function initAddMoreButton() {
    const button = document.getElementById('add-more-items');
    if (!button) return;

    const sections = Array.from(document.querySelectorAll('.popup-section'));

    // Ensure only first is visible at start
    sections.forEach((section, index) => {
        if (index === 0) {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    });

    const updateButtonState = () => {
        const hidden = sections.filter((section, idx) => idx > 0 && section.style.display === 'none');
        if (hidden.length === 0) {
            button.disabled = true;
            button.textContent = 'Max 5 items added';
        }
    };

    button.addEventListener('click', function () {
        const nextHidden = sections.find((section, idx) => idx > 0 && section.style.display === 'none');
        if (nextHidden) {
            nextHidden.style.display = 'block';
            updateButtonState();
        }
    });

    updateButtonState();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initCategoryToggles();
    // News menu tab logic
    const tabs = document.querySelectorAll('.news-menu-tab');
    const sections = [
        document.getElementById('section-1'),
        document.getElementById('section-2'),
        document.getElementById('section-3'),
        document.getElementById('section-4'),
        document.getElementById('section-5')
    ];
    tabs.forEach((tab, idx) => {
        tab.addEventListener('click', function() {
            tabs.forEach((t, i) => {
                t.classList.toggle('active', i === idx);
                t.style.background = i === idx ? '#eaf2ff' : '#fff';
                t.style.borderColor = i === idx ? '#b5d1ff' : '#e0e0e0';
                t.style.color = i === idx ? '#1a3c6c' : '#222';
            });
            sections.forEach((section, sidx) => {
                section.style.display = (sidx === idx) ? 'block' : 'none';
            });
        });
    });
    // Show only first section by default
    sections.forEach((section, idx) => {
        section.style.display = idx === 0 ? 'block' : 'none';
    });
});
</script>
<script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function initNewsContentEditor() {
    var textarea = document.getElementById('item_1_content');
    if (!textarea || typeof CKEDITOR === 'undefined') {
        return false;
    }
    if (CKEDITOR.instances && CKEDITOR.instances.item_1_content) {
        return true;
    }
    CKEDITOR.replace('item_1_content', {
        height: 280,
        extraPlugins: 'colorbutton,font,justify',
        toolbar: [
            { name: 'document', items: ['Source', '-', 'Preview'] },
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
            { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
            { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] }
        ]
    });
    return true;
}

document.addEventListener('DOMContentLoaded', function () {
    if (!initNewsContentEditor()) {
        // Retry once after full page scripts load.
        setTimeout(initNewsContentEditor, 400);
    }
});

window.addEventListener('load', function () {
    initNewsContentEditor();
});

var newsForm = document.querySelector('form[name="newsForm"]');
if (newsForm) {
    newsForm.addEventListener('submit', function () {
        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances && CKEDITOR.instances.item_1_content) {
            CKEDITOR.instances.item_1_content.updateElement();
        }
    });
}
</script>
<?php 
include('inc/footer.php');
?>