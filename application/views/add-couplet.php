<?php
include('inc/header.php');
include('inc/sidebar.php');

if (!function_exists('couplet_parse_id_list')) {
    function couplet_parse_id_list($raw) {
        if ($raw === null || $raw === '') {
            return [];
        }
        $u = @unserialize($raw);
        if ($u !== false && is_array($u)) {
            return array_values(array_map('strval', $u));
        }
        $s = trim((string) $raw);
        if ($s === '') {
            return [];
        }
        if (preg_match('/^\d+$/', $s)) {
            return [$s];
        }
        return array_values(array_filter(array_map('trim', explode(',', $s))));
    }
}
?>
<style>
    .form-group {
        display: flex;
        align-items: center;
        margin-bottom: 18px;
    }
    .form-group label {
        flex: 0 0 220px;
        max-width: 220px;
        font-weight: 600;
        color: #333;
        margin-bottom: 0;
        font-size: 15px;
        padding-right: 18px;
        display: block;
    }
    .form-group > *:not(label) {
        flex: 1 1 0%;
        margin-bottom: 0;
    }
    .form-group .btn,
    .form-group .btn-sm {
        margin-left: 4px;
        white-space: nowrap;
    }
    .form-group select.form-control,
    .form-group input.form-control,
    .form-group textarea.form-control {
        border: 1.5px solid #bdbdbd;
        border-radius: 4px;
        padding: 7px 10px;
        font-size: 15px;
        background: #fafbfc;
        transition: border 0.2s;
        width: 100%;
    }
    .form-group select.form-control:focus,
    .form-group input.form-control:focus,
    .form-group textarea.form-control:focus {
        border-color: #007bff;
        outline: none;
        background: #fff;
    }
    .btn-success.ml-2, .btn-success.ml-2:focus, .form-group .btn-success, .form-group .btn-success:focus {
        background: #28a745;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 6px 18px;
        font-size: 15px;
        font-weight: 500;
        transition: background 0.2s;
        box-shadow: 0 2px 6px rgba(40,167,69,0.08);
    }
    .btn-success.ml-2:hover, .form-group .btn-success:hover {
        background: #218838;
    }
        /* Make selected tags wrap neatly and fit within box */
            .select2-container--default .select2-selection--multiple {
                min-height: 38px;
                border: 1px solid #ccc;
                border-radius: 6px;
                padding: 4px 6px;
                display: flex;
                flex-wrap: wrap; /* allows tags to wrap */
                align-items: center;
                overflow: auto;
            }

            /* Reduce padding and size of tags */
            .select2-container--default .select2-selection--multiple .select2-selection__choice {
                background-color: #007bff;
                border: none;
                color: white;
                padding: 2px 6px;
                margin: 2px;
                border-radius: 4px;
                font-size: 12px; /* smaller tag text */
                max-width: 100%; /* prevent overflow */
                white-space: nowrap;
                text-overflow: ellipsis;
                overflow: hidden;
            }

            /* Adjust close (×) icon */
            .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
                color: #fff;
                margin-right: 4px;
            }

            /* Make overall box not expand too much vertically */
            .multi-select-container {
                max-width: 100%;
            }

            /* Remove scrollbar completely */
.select2-container--default .select2-selection--multiple::-webkit-scrollbar {
    display: none;
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

  
    .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
        margin: 1px 2px !important;
        padding: 2px 4px !important;
    }
    .cke_notification{
    	display:none;
    }
 .select2-container .select2-selection--multiple {
    /* min-height: 38px; */
    height: 5px;
    padding-top: 2px;
    display: flex !important;
    align-items: center !important;
    font-size: 14px !important; /* 👈 placeholder छोटा */
    border: 1px solid #ccc !important;
    border-radius: 4px !important;
}

.select2-selection__placeholder {
    font-size: 14px !important; /* 👈 placeholder छोटा */
    line-height: normal !important;
    color: #777 !important;
}

.save-btn-container {

    display: flex;
    justify-content: flex-end; /* Button right side me */
    margin-bottom: 40px; /* Neeche thoda space */
}

.save-btn {
    position: relative;
    top: -20px; /* 👉 button ko upar karega (value badha/samjha ke adjust kar sakte ho) */
    padding: 8px 30px;
    font-size: 16px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}



/* SweetAlert2 Custom Styling - Exactly like your screenshot */
.custom-missing-popup {
    border-radius: 6px !important;
    padding: 20px !important;
    max-width: 500px !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
}

.custom-missing-title {
    font-size: 20px !important;
    font-weight: 600 !important;
    color: #333 !important;
    margin-bottom: 10px !important;
}

.custom-missing-text {
    font-size: 15px !important;
    color: #555 !important;
    margin: 0 !important;
    line-height: 1.5 !important;
}

.swal2-icon.swal2-warning {
    border-color: #f39c12 !important;
    color: #f39c12 !important;
}

.custom-missing-ok-btn {
    background-color: #6f42c1 !important; /* Purple like your OK button */
    color: white !important;
    border: none !important;
    border-radius: 6px !important;
    padding: 8px 24px !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
    transition: all 0.2s !important;
}

.custom-missing-ok-btn:hover {
    background-color: #5a2d91 !important;
    transform: translateY(-1px) !important;
}

/* Modal Styling */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background-color: #fff;
    padding: 0;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.close-btn {
    background: none;
    border: none;
    font-size: 28px;
    cursor: pointer;
    color: #999;
    padding: 0;
}

.close-btn:hover {
    color: #333;
}

.modal-body {
    padding: 20px;
}

.modal-body .form-group {
    margin-bottom: 15px;
}

.modal-body .form-group label {
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
    color: #333;
}

.modal-body .form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

.modal-body .form-group input:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.modal-footer {
    padding: 20px;
    border-top: 1px solid #e0e0e0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.modal-footer button {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

.btn-success {
    background-color: #28a745;
    color: white;
}

.btn-success:hover {
    background-color: #218838;
}


</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <?php
                    $is_edit_couplet = isset($couplet) && is_array($couplet) && !empty($couplet['id']);
                    $page_heading = !empty($page_title) ? $page_title : ($is_edit_couplet ? 'Edit Poem Details' : 'Add Poem Details');
                    $breadcrumb_active = $page_heading;
                    ?>
                    <h1><?php echo htmlspecialchars($page_heading); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('add_new'); ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo htmlspecialchars($breadcrumb_active); ?></li>
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
        <!-- existing form content yahan tak rahega -->
            <div class="card-header" style="padding: 4px 8px; margin: 0;">
                    <div>
                        <a href="javascript:void(0);" 
                            onclick="window.history.back();" 
                            class="btn btn-secondary" 
                            style="padding: 3px 8px; font-size: 13px; border-radius: 4px;">
                                <i class="fas fa-arrow-left" style="font-size: 13px; margin-right: 4px;"></i> Back
                        </a>
                    </div>
            </div>

             <div class="card-body">
                <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
                <?php
                    $readSelectedValues = function ($rawValue) {
                        if (empty($rawValue)) {
                            return [];
                        }
                        $parsed = @unserialize($rawValue);
                        if (is_array($parsed)) {
                            return array_map('strval', $parsed);
                        }
                        return array_map('trim', explode(',', (string)$rawValue));
                    };

                    $selected_related_keywords = [];
                    if (isset($couplet['relatedkeywords'])) {
                        $selected_related_keywords = $readSelectedValues($couplet['relatedkeywords']);
                    } elseif (isset($couplet['related_keywords'])) {
                        $selected_related_keywords = $readSelectedValues($couplet['related_keywords']);
                    }

                    $selected_related_songs = isset($couplet['related_songs']) ? $readSelectedValues($couplet['related_songs']) : [];
                    $selected_reflections = isset($couplet['related_reflections']) ? $readSelectedValues($couplet['related_reflections']) : [];
                    $selected_related_poems = isset($couplet['related_poems']) ? $readSelectedValues($couplet['related_poems']) : [];
                    $selected_related_people = isset($couplet['related_people']) ? $readSelectedValues($couplet['related_people']) : [];
                    $selected_related_films = isset($couplet['related_films']) ? $readSelectedValues($couplet['related_films']) : [];
                    $selected_related_film_episodes = isset($couplet['related_film_episodes']) ? $readSelectedValues($couplet['related_film_episodes']) : [];
                ?>
                  <!-- <form name="CoupletForm" id="coupletdForm" method="post" action="<?php echo base_url('CoupletController/save'); ?>">                         -->
                      <form method="post" action="<?= isset($form_action) ? $form_action : base_url('couplet/save'); ?>" enctype="multipart/form-data" id="coupletForm">
                        <!-- Couplet Titles -->
                         <label>Poem Title</label>
                         <div style="padding-left:20px;">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>⊙ Transliteration <span style="color:red">*</span></label>
                                    <input type="text" id="couplet_transliteration" name="couplet_transliteration" class="form-control col-md-4" value="<?= isset($couplet['couplet_transliteration']) ? htmlspecialchars($couplet['couplet_transliteration']) : ''; ?>" placeholder="Enter Poem Title - Transliteration" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>⊙ Translation <span style="color:red">*</span></label>
                                    <input type="text" id="couplet_translation" name="couplet_translation" class="form-control col-md-4" value="<?= isset($couplet['couplet_translation']) ? htmlspecialchars($couplet['couplet_translation']) : ''; ?>" placeholder="Enter Poem Title - Translation" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>⊙ Original Language</label>
                                    <input type="text" name="original_title" class="form-control col-md-4" value="<?= isset($couplet['original_title']) ? $couplet['original_title'] : ''; ?>" placeholder="Enter Poem Title - Original Language">
                                </div>
                            </div>
                        </div>
                        </div>
                        <!-- Poet, Attributed Poet, Translator -->
                        <div class="row" id="poetContainer">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Poet <span style="color:red">*</span></label>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="poet[]" id="poet" data-placeholder="Select Poet" required>
                                            <!-- No default empty option for multi-select -->
                                            <?php 
                                                $poets = $this->db->query("SELECT id, first_name, middle_name, last_name FROM person")->result();
                                                foreach ($poets as $p) {
                                                    $parts = [];
                                                    if (!empty(trim($p->first_name))) { $parts[] = trim($p->first_name); }
                                                    if (!empty(trim($p->middle_name))) { $parts[] = trim($p->middle_name); }
                                                    if (!empty(trim($p->last_name))) { $parts[] = trim($p->last_name); }
                                                    $fullName = implode(' ', $parts);
                                                    if ($fullName === '') { $fullName = 'Unnamed'; }
                                                    $pid = (string)$p->id;
                                                    echo '<option value="'.htmlspecialchars($pid).'">'.htmlspecialchars($fullName).'</option>';
                                                }
                                            ?>
                                        </select>
                                        <button type="button" class="btn btn-success btn-sm" id="addPoetBtn">Add New</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="attributedPoetContainer">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Attributed Poet</label>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="attributed_poet[]" id="attributed_poet" data-placeholder="Select Attributed Poet">
                                            <option value="">Select Attributed Poet</option>
                                            <?php 
                                                foreach ($poets as $p) {
                                                    $parts = [];
                                                    if (!empty(trim($p->first_name))) { $parts[] = trim($p->first_name); }
                                                    if (!empty(trim($p->middle_name))) { $parts[] = trim($p->middle_name); }
                                                    if (!empty(trim($p->last_name))) { $parts[] = trim($p->last_name); }
                                                    $fullName = implode(' ', $parts);
                                                    if ($fullName === '') { $fullName = 'Unnamed'; }
                                                    $pid = (string)$p->id;
                                                    echo '<option value="'.htmlspecialchars($pid).'">'.htmlspecialchars($fullName).'</option>';
                                                }
                                            ?>
                                        </select>
                                        <button type="button" class="btn btn-success btn-sm" id="addAttributedPoetBtn">Add New</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <label>Poem Text</label>
                        <div style="padding-left:20px;">
                            <div class="row">
                                <div class="col-12">
                                <div class="form-group">
                                    <label>⊙ Original</label>
                                    <textarea id="original_text" name="original_text" class="form-control col-md-4"><?= isset($couplet['original_text']) ? $couplet['original_text'] : ''; ?></textarea>
                                </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                <div class="form-group">
                                    <label>⊙ Transliteration</label>
                                    <textarea id="english_transliteration_text" name="english_transliteration_text" class="form-control col-md-4"><?= isset($couplet['english_transliteration_text']) ? htmlspecialchars($couplet['english_transliteration_text']) : ''; ?></textarea>
                                </div>
                                </div>
                            </div>

                            <style>
                                .translation-stack label {
                                    display: block !important;
                                    float: none !important;
                                    width: 100% !important;
                                    margin-bottom: 8px;
                                }
                                .translation-stack .translation-control {
                                    display: block;
                                    width: 100%;
                                    margin-bottom: 10px;
                                    clear: both;
                                }
                                .translation-stack .translation-main-group {
                                    display: flex;
                                    flex-direction: column;
                                    align-items: flex-start;
                                    gap: 8px;
                                    width: 100%;
                                }
                                #extraCoupletTranslations {
                                    width: 100%;
                                    display: block;
                                }
                                #extraCoupletTranslations .translation-stack {
                                    display: block !important;
                                    width: 100% !important;
                                    float: none !important;
                                    clear: both !important;
                                    margin-top: 10px;
                                }
                                .translation-stack .translation-control .select2-container {
                                    width: 220px !important;
                                    max-width: 100%;
                                }
                                .translation-stack .translation-editor-wrap .cke {
                                    width: 100% !important;
                                    display: block !important;
                                    float: none !important;
                                }
                                .translation-select-row {
                                    display: flex;
                                    align-items: center;
                                    gap: 8px;
                                    margin-bottom: 8px;
                                }
                                .translation-block {
                                    width: 100%;
                                    margin-bottom: 16px;
                                }
                                .translation-help-text {
                                    color: #d71919;
                                    font-style: italic;
                                    margin: 4px 0 8px;
                                }
                            </style>
                            <div class="row">
                                <div class="col-12">
                                <div class="form-group translation-stack">
                                    <label>⊙ Translation</label>
                                    <div class="translation-main-group translation-block">
                                        <div class="translation-control">
                                            <button type="button" class="btn btn-primary btn-sm" id="addCoupletTranslationBtn">Add Couplet Translation</button>
                                        </div>
                                        <div class="translation-control translation-select-row">
                                            <select class="form-control select2 col-md-4" multiple="multiple" name="translator[]" id="translator" data-placeholder="Select Translators">
                                                <option value="">Select Translators</option>
                                                <?php 
                                                    foreach ($poets as $p) {
                                                        $parts = [];
                                                        if (!empty(trim($p->first_name))) { $parts[] = trim($p->first_name); }
                                                        if (!empty(trim($p->middle_name))) { $parts[] = trim($p->middle_name); }
                                                        if (!empty(trim($p->last_name))) { $parts[] = trim($p->last_name); }
                                                        $fullName = implode(' ', $parts);
                                                        if ($fullName === '') { $fullName = 'Unnamed'; }
                                                        $pid = (string)$p->id;
                                                        echo '<option value="'.htmlspecialchars($pid).'">'.htmlspecialchars($fullName).'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="translation-control translation-editor-wrap">
                                            <textarea id="english_translation_text" name="english_translation_text" class="form-control"><?= isset($couplet['english_translation_text']) ? htmlspecialchars($couplet['english_translation_text']) : ''; ?></textarea>
                                        </div>
                                        <div id="extraCoupletTranslations" class="mt-3"></div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                <label>Poem Notes</label>
                                <textarea id="note_text" name="note_text" class="form-control"><?= isset($couplet['note_text']) ? $couplet['note_text'] : ''; ?></textarea>
                            </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Poem Glossary</label>
                                    <textarea id="glossary" name="glossary" class="form-control"><?= isset($couplet['glossary']) ? $couplet['glossary'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Audio File Upload -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Audio File Upload</label>
                                    <input type="file" name="audio_file" class="form-control col-md-4" accept="audio/*">
                                </div>
                            </div>
                        </div>

                        <!-- Thumbnail (same pattern as add-song: hidden existing path + preview + optional new file) -->
                        <?php
                        $existingThumbnailUrl = '';
                        if (isset($couplet) && is_array($couplet)) {
                            if (!empty($couplet['thumbnail_image_upload'])) {
                                $existingThumbnailUrl = trim((string) $couplet['thumbnail_image_upload']);
                            } elseif (!empty($couplet['thumbnail_url'])) {
                                $existingThumbnailUrl = trim((string) $couplet['thumbnail_url']);
                            }
                        }
                        // Preview only: production public base for legacy DB paths like images/...
                        $thumbnailPublicBase = 'https://ajabshahar.aaravega.in';
                        $thumbnailPreviewSrc = '';
                        if ($existingThumbnailUrl !== '') {
                            if (preg_match('#^https?://#i', $existingThumbnailUrl)) {
                                $thumbnailPreviewSrc = $existingThumbnailUrl;
                            } elseif (isset($existingThumbnailUrl[0]) && $existingThumbnailUrl[0] === '/') {
                                $thumbnailPreviewSrc = rtrim($thumbnailPublicBase, '/') . $existingThumbnailUrl;
                            } else {
                                $thumbnailPreviewSrc = rtrim($thumbnailPublicBase, '/') . '/' . ltrim($existingThumbnailUrl, '/');
                            }
                        }
                        ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Thumbnail Image Upload <?php if ($existingThumbnailUrl === ''): ?><span style="color:red">*</span><?php endif; ?></label>
                                    <div>
                                        <?php if ($existingThumbnailUrl !== ''): ?>
                                        <input type="hidden" name="thumbnailUrl_existing" id="thumbnailUrl_existing" value="<?= htmlspecialchars($existingThumbnailUrl) ?>">
                                        <p class="mb-2 text-muted small">Current file is kept unless you choose a new image.</p>
                                        <?php endif; ?>
                                        <input type="file" name="thumbnailUrl" id="thumbnailUrl" class="form-control col-md-4" accept="image/*">
                                        <?php if ($existingThumbnailUrl !== '' && $thumbnailPreviewSrc !== ''): ?>
                                        <div class="mt-3" id="thumbnailPreviewWrap">
                                            <img src="<?= htmlspecialchars($thumbnailPreviewSrc) ?>" alt="Current thumbnail" id="thumbnailPreviewImg"
                                                style="display:block;max-height:180px;max-width:100%;border:1px solid #ddd;border-radius:6px;object-fit:contain;background:#fafafa;"
                                                onerror="this.style.display='none';var w=document.getElementById('thumbnailPreviewBroken');if(w)w.style.display='block';">
                                            <p class="small text-muted mt-1" id="thumbnailPreviewBroken" style="display:none;">Preview could not be loaded.</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($existingThumbnailUrl !== ''): ?>
                        <script>
                        (function() {
                            var input = document.getElementById('thumbnailUrl');
                            var img = document.getElementById('thumbnailPreviewImg');
                            if (!input || !img) return;
                            input.addEventListener('change', function() {
                                var f = input.files && input.files[0];
                                if (!f || !f.type.match(/^image\//)) return;
                                var r = new FileReader();
                                r.onload = function(e) {
                                    img.src = e.target.result;
                                    img.style.display = 'block';
                                    var b = document.getElementById('thumbnailPreviewBroken');
                                    if (b) b.style.display = 'none';
                                };
                                r.readAsDataURL(f);
                            });
                        })();
                        </script>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Thumbnail Excerpt</label>
                                    <input type="text" id="thumbnail_excerpt" name="thumbnail_excerpt" class="form-control col-md-4" value="<?= isset($couplet['thumbnail_excerpt']) ? $couplet['thumbnail_excerpt'] : ''; ?>" placeholder="Enter Thumbnail Excerpt">
                                </div>
                            </div>
                        </div>

                        <label>Related Content</label>
                        <div style="padding-left:20px;">
                        <div class="row">
                            <div class="col-12">
                                
                                <div class="form-group"> 
                                    <?php
                                    $keyword_rows = $this->db->query("SELECT id, word_transliteration FROM keywords")->result(); 
                                    
                                    ?>
                                    <label>⊙ Keywords</label>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="relatedkeywords[]" id="relatedkeywords">
                                        <?php foreach ($keyword_rows as $keyword) :
                                            $keywordId = (string)$keyword->id;
                                            $isKeywordSelected = in_array($keywordId, $selected_related_keywords, true);
                                        ?>
                                            <option value="<?= $keyword->id ?>" <?= $isKeywordSelected ? 'selected' : '' ?>><?= htmlspecialchars($keyword->word_transliteration) ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-sm btn-success ml-2" id="addNewKeywordBtn" style="white-space:nowrap;">Add New</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <?php
                                    $song_rows = $this->db->query("SELECT id, umbrellaTitle FROM songs")->result();
                                    
                                    ?>
                                    <label>⊙ Songs</label>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="related_songs[]" id="related_songs">
                                        <?php foreach ($song_rows as $song_row) :
                                            $songId = (string)$song_row->id;
                                            $isSongSelected = in_array($songId, $selected_related_songs, true);
                                        ?>
                                            <option value="<?= $song_row->id ?>" <?= $isSongSelected ? 'selected' : '' ?>><?= htmlspecialchars($song_row->umbrellaTitle) ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <?php
                                    $reflection_rows = $this->db->query("SELECT id, title FROM reflection ORDER BY id DESC")->result();
                                    ?>
                                    <label>⊙ Reflections</label>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="reflections[]" id="reflections">
                                        <?php foreach ($reflection_rows as $reflection_row) :
                                            $rid = (string)$reflection_row->id;
                                            $isSelected = in_array($rid, (array)$selected_reflections, true);
                                        ?>
                                            <option value="<?= $reflection_row->id ?>" <?= $isSelected ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($reflection_row->title) ?>
                                            </option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <?php  $poem_rows = $this->db->query("SELECT id, COALESCE(NULLIF(couplet_transliteration, ''), original_title) AS poem_label FROM couplet")->result();
                                     ?>
                                    <label>⊙ Poems</label>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="relatedpoems[]" id="relatedpoems">
                                        <?php foreach ($poem_rows as $poem_row) :
                                            $poemId = (string)$poem_row->id;
                                            $isPoemSelected = in_array($poemId, $selected_related_poems, true);
                                        ?>
                                            <option value="<?= $poem_row->id ?>" <?= $isPoemSelected ? 'selected' : '' ?>><?= htmlspecialchars($poem_row->poem_label) ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <?php $person_rows = $this->db->query("SELECT id, first_name, middle_name, last_name FROM person")->result();
                                     ?>
                                    <label>⊙ People</label>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="related_people[]" id="related_people">
                                        <?php foreach ($person_rows as $person_row) :
                                            $personId = (string)$person_row->id;
                                            $isPersonSelected = in_array($personId, $selected_related_people, true);
                                        ?>
                                            <option value="<?= $person_row->id ?>" <?= $isPersonSelected ? 'selected' : '' ?>><?= htmlspecialchars($person_row->first_name . ' ' . $person_row->middle_name . ' ' . $person_row->last_name) ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <?php $film_rows = $this->db->query("SELECT id, COALESCE(NULLIF(english_transliteration, ''), NULLIF(english_translation, ''), original_title) AS film_label FROM film")->result();
                                     ?>
                                    <label>⊙ Films</label>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="films[]" id="films">
                                        <?php foreach ($film_rows as $film_row) :
                                            $filmId = (string)$film_row->id;
                                            $isFilmSelected = in_array($filmId, $selected_related_films, true);
                                        ?>
                                            <option value="<?= $film_row->id ?>" <?= $isFilmSelected ? 'selected' : '' ?>><?= htmlspecialchars($film_row->film_label) ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <?php $episode_rows = $this->db->query("SELECT id, COALESCE(NULLIF(english_transliteration, ''), NULLIF(english_translation, ''), original_title) AS episode_label FROM film_episode")->result();
                                     ?>
                                    <label>⊙ Film Episodes</label>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="film_episodes[]" id="film_episodes">
                                        <?php foreach ($episode_rows as $episode_row) :
                                            $episodeId = (string)$episode_row->id;
                                            $isEpisodeSelected = in_array($episodeId, $selected_related_film_episodes, true);
                                        ?>
                                            <option value="<?= $episode_row->id ?>" <?= $isEpisodeSelected ? 'selected' : '' ?>><?= htmlspecialchars($episode_row->episode_label) ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div>
                                <!-- Add New Keyword Modal -->
                                <div class="modal" id="addNewKeywordModal">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add New Keyword</h5>
                                            <button class="close-btn" id="closeAddNewKeyword">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Keyword Transliteration</label>
                                                <input type="text" id="newKeywordTransliteration" class="form-control" placeholder="Enter Keyword Transliteration">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn-secondary" id="cancelAddNewKeyword">Cancel</button>
                                            <button class="btn-success" id="addKeywordConfirm">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Add New Song Modal -->
                                <div class="modal" id="addNewSongModal">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add New Song</h5>
                                            <button class="close-btn" id="closeAddNewSong">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Song Title</label>
                                                <input type="text" id="newSongTitle" class="form-control" placeholder="Enter Song Title">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn-secondary" id="cancelAddNewSong">Cancel</button>
                                            <button class="btn-success" id="addSongConfirm">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Add New Reflection Modal -->
                                <div class="modal" id="addNewReflectionModal">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add New Reflection</h5>
                                            <button class="close-btn" id="closeAddNewReflection">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Reflection Title</label>
                                                <input type="text" id="newReflectionTitle" class="form-control" placeholder="Enter Reflection Title">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn-secondary" id="cancelAddNewReflection">Cancel</button>
                                            <button class="btn-success" id="addReflectionConfirm">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Add New Poem Modal -->
                                <div class="modal" id="addNewPoemModal">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add New Poem</h5>
                                            <button class="close-btn" id="closeAddNewPoem">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Poem Title</label>
                                                <input type="text" id="newPoemTitle" class="form-control" placeholder="Enter Poem Title">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn-secondary" id="cancelAddNewPoem">Cancel</button>
                                            <button class="btn-success" id="addPoemConfirm">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Add New Person Modal -->
                                <div class="modal" id="addNewPersonModal">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add New Person</h5>
                                            <button class="close-btn" id="closeAddNewPerson">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Person Name</label>
                                                <input type="text" id="newPersonName" class="form-control" placeholder="Enter Person Name">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn-secondary" id="cancelAddNewPerson">Cancel</button>
                                            <button class="btn-success" id="addPersonConfirm">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Add New Film Modal -->
                                <div class="modal" id="addNewFilmModal">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add New Film</h5>
                                            <button class="close-btn" id="closeAddNewFilm">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Film Title</label>
                                                <input type="text" id="newFilmTitle" class="form-control" placeholder="Enter Film Title">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn-secondary" id="cancelAddNewFilm">Cancel</button>
                                            <button class="btn-success" id="addFilmConfirm">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Add New Episode Modal -->
                                <div class="modal" id="addNewEpisodeModal">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add New Film Episode</h5>
                                            <button class="close-btn" id="closeAddNewEpisode">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Episode Title</label>
                                                <input type="text" id="newEpisodeTitle" class="form-control" placeholder="Enter Episode Title">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn-secondary" id="cancelAddNewEpisode">Cancel</button>
                                            <button class="btn-success" id="addEpisodeConfirm">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                // Add New Entity Modals logic for Keywords, Songs, Reflections, Poems, People, Films, Film Episodes
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Keyword
                                    var addNewKeywordBtn = document.getElementById('addNewKeywordBtn');
                                    var addNewKeywordModal = document.getElementById('addNewKeywordModal');
                                    var addKeywordConfirm = document.getElementById('addKeywordConfirm');
                                    var newKeywordTransliteration = document.getElementById('newKeywordTransliteration');
                                    var relatedkeywordsSelect = document.getElementById('relatedkeywords');
                                    if (addNewKeywordBtn && addNewKeywordModal && addKeywordConfirm && newKeywordTransliteration && relatedkeywordsSelect) {
                                        addNewKeywordBtn.onclick = function() {
                                            addNewKeywordModal.classList.add('show');
                                            newKeywordTransliteration.value = '';
                                            setTimeout(function() { newKeywordTransliteration.focus(); }, 300);
                                        };
                                        document.getElementById('closeAddNewKeyword').onclick = function() { addNewKeywordModal.classList.remove('show'); };
                                        document.getElementById('cancelAddNewKeyword').onclick = function() { addNewKeywordModal.classList.remove('show'); };
                                        addKeywordConfirm.onclick = async function() {
                                            var newKeyword = newKeywordTransliteration.value.trim();
                                            if (!newKeyword) {
                                                alert('Please enter a keyword!');
                                                return;
                                            }
                                            addKeywordConfirm.disabled = true;
                                            try {
                                                var res = await fetch('<?= base_url('SongController/ajax_create_keyword') ?>', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                    body: 'word_transliteration=' + encodeURIComponent(newKeyword)
                                                });
                                                var data = await res.json();
                                                if (data && data.status === 'success') {
                                                    var option = document.createElement('option');
                                                    option.value = data.keyword_id || data.id;
                                                    option.text = data.word_transliteration || newKeyword;
                                                    option.selected = true;
                                                    relatedkeywordsSelect.add(option);
                                                    if (window.jQuery && $('#relatedkeywords').length) $('#relatedkeywords').trigger('change');
                                                    addNewKeywordModal.classList.remove('show');
                                                    newKeywordTransliteration.value = '';
                                                    if (window.Swal) Swal.fire({icon:'success',title:'Success',text:data.message || 'Keyword added!',timer:1200,showConfirmButton:false});
                                                } else {
                                                    alert((data && data.message) ? data.message : 'Failed to add keyword!');
                                                }
                                            } catch (e) {
                                                alert('Error: ' + e.message);
                                            }
                                            addKeywordConfirm.disabled = false;
                                        };
                                    }
                                    // Song
                                    var addNewSongBtn = document.getElementById('addNewSongBtn');
                                    var addNewSongModal = document.getElementById('addNewSongModal');
                                    var addSongConfirm = document.getElementById('addSongConfirm');
                                    var newSongTitle = document.getElementById('newSongTitle');
                                    var relatedSongsSelect = document.getElementById('related_songs');
                                    if (addNewSongBtn && addNewSongModal && addSongConfirm && newSongTitle && relatedSongsSelect) {
                                        addNewSongBtn.onclick = function() {
                                            addNewSongModal.classList.add('show');
                                            newSongTitle.value = '';
                                            setTimeout(function() { newSongTitle.focus(); }, 300);
                                        };
                                        document.getElementById('closeAddNewSong').onclick = function() { addNewSongModal.classList.remove('show'); };
                                        document.getElementById('cancelAddNewSong').onclick = function() { addNewSongModal.classList.remove('show'); };
                                        addSongConfirm.onclick = async function() {
                                            var newSong = newSongTitle.value.trim();
                                            if (!newSong) {
                                                alert('Please enter a song title!');
                                                return;
                                            }
                                            addSongConfirm.disabled = true;
                                            try {
                                                var res = await fetch('<?= base_url('SongController/ajax_create_song') ?>', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                    body: 'umbrellaTitle=' + encodeURIComponent(newSong)
                                                });
                                                var data = await res.json();
                                                if (data && data.status === 'success') {
                                                    var option = document.createElement('option');
                                                    option.value = data.song_id || data.id;
                                                    option.text = data.umbrellaTitle || newSong;
                                                    option.selected = true;
                                                    relatedSongsSelect.add(option);
                                                    if (window.jQuery && $('#related_songs').length) $('#related_songs').trigger('change');
                                                    addNewSongModal.classList.remove('show');
                                                    newSongTitle.value = '';
                                                    if (window.Swal) Swal.fire({icon:'success',title:'Success',text:data.message || 'Song added!',timer:1200,showConfirmButton:false});
                                                } else {
                                                    alert((data && data.message) ? data.message : 'Failed to add song!');
                                                }
                                            } catch (e) {
                                                alert('Error: ' + e.message);
                                            }
                                            addSongConfirm.disabled = false;
                                        };
                                    }
                                    // Reflection
                                    var addNewReflectionBtn = document.getElementById('addNewReflectionBtn');
                                    var addNewReflectionModal = document.getElementById('addNewReflectionModal');
                                    var addReflectionConfirm = document.getElementById('addReflectionConfirm');
                                    var newReflectionTitle = document.getElementById('newReflectionTitle');
                                    var reflectionsSelect = document.getElementById('reflections');
                                    if (addNewReflectionBtn && addNewReflectionModal && addReflectionConfirm && newReflectionTitle && reflectionsSelect) {
                                        addNewReflectionBtn.onclick = function() {
                                            addNewReflectionModal.classList.add('show');
                                            newReflectionTitle.value = '';
                                            setTimeout(function() { newReflectionTitle.focus(); }, 300);
                                        };
                                        document.getElementById('closeAddNewReflection').onclick = function() { addNewReflectionModal.classList.remove('show'); };
                                        document.getElementById('cancelAddNewReflection').onclick = function() { addNewReflectionModal.classList.remove('show'); };
                                        addReflectionConfirm.onclick = async function() {
                                            var newReflection = newReflectionTitle.value.trim();
                                            if (!newReflection) {
                                                alert('Please enter a reflection title!');
                                                return;
                                            }
                                            addReflectionConfirm.disabled = true;
                                            try {
                                                var res = await fetch('<?= base_url('SongController/ajax_create_reflection') ?>', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                    body: 'title=' + encodeURIComponent(newReflection)
                                                });
                                                var data = await res.json();
                                                if (data && data.status === 'success') {
                                                    var option = document.createElement('option');
                                                    option.value = data.reflection_id || data.id;
                                                    option.text = data.title || newReflection;
                                                    option.selected = true;
                                                    reflectionsSelect.add(option);
                                                    if (window.jQuery && $('#reflections').length) $('#reflections').trigger('change');
                                                    addNewReflectionModal.classList.remove('show');
                                                    newReflectionTitle.value = '';
                                                    if (window.Swal) Swal.fire({icon:'success',title:'Success',text:data.message || 'Reflection added!',timer:1200,showConfirmButton:false});
                                                } else {
                                                    alert((data && data.message) ? data.message : 'Failed to add reflection!');
                                                }
                                            } catch (e) {
                                                alert('Error: ' + e.message);
                                            }
                                            addReflectionConfirm.disabled = false;
                                        };
                                    }
                                    // Poem
                                    var addNewPoemBtn = document.getElementById('addNewPoemBtn');
                                    var addNewPoemModal = document.getElementById('addNewPoemModal');
                                    var addPoemConfirm = document.getElementById('addPoemConfirm');
                                    var newPoemTitle = document.getElementById('newPoemTitle');
                                    var poemsSelect = document.getElementById('relatedpoems');
                                    if (addNewPoemBtn && addNewPoemModal && addPoemConfirm && newPoemTitle && poemsSelect) {
                                        addNewPoemBtn.onclick = function() {
                                            addNewPoemModal.classList.add('show');
                                            newPoemTitle.value = '';
                                            setTimeout(function() { newPoemTitle.focus(); }, 300);
                                        };
                                        document.getElementById('closeAddNewPoem').onclick = function() { addNewPoemModal.classList.remove('show'); };
                                        document.getElementById('cancelAddNewPoem').onclick = function() { addNewPoemModal.classList.remove('show'); };
                                        addPoemConfirm.onclick = async function() {
                                            var newPoem = newPoemTitle.value.trim();
                                            if (!newPoem) {
                                                alert('Please enter a poem title!');
                                                return;
                                            }
                                            addPoemConfirm.disabled = true;
                                            try {
                                                var res = await fetch('<?= base_url('SongController/ajax_create_poem') ?>', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                    body: 'original_title=' + encodeURIComponent(newPoem)
                                                });
                                                var data = await res.json();
                                                if (data && data.status === 'success') {
                                                    var option = document.createElement('option');
                                                    option.value = data.poem_id || data.id;
                                                    option.text = data.original_title || newPoem;
                                                    option.selected = true;
                                                    poemsSelect.add(option);
                                                    if (window.jQuery && $('#relatedpoems').length) $('#relatedpoems').trigger('change');
                                                    addNewPoemModal.classList.remove('show');
                                                    newPoemTitle.value = '';
                                                    if (window.Swal) Swal.fire({icon:'success',title:'Success',text:data.message || 'Poem added!',timer:1200,showConfirmButton:false});
                                                } else {
                                                    alert((data && data.message) ? data.message : 'Failed to add poem!');
                                                }
                                            } catch (e) {
                                                alert('Error: ' + e.message);
                                            }
                                            addPoemConfirm.disabled = false;
                                        };
                                    }
                                    // Person
                                    var addNewPersonBtn = document.getElementById('addNewPersonBtn');
                                    var addNewPersonModal = document.getElementById('addNewPersonModal');
                                    var addPersonConfirm = document.getElementById('addPersonConfirm');
                                    var newPersonName = document.getElementById('newPersonName');
                                    var peopleSelect = document.getElementById('related_people');
                                    if (addNewPersonBtn && addNewPersonModal && addPersonConfirm && newPersonName && peopleSelect) {
                                        addNewPersonBtn.onclick = function() {
                                            addNewPersonModal.classList.add('show');
                                            newPersonName.value = '';
                                            setTimeout(function() { newPersonName.focus(); }, 300);
                                        };
                                        document.getElementById('closeAddNewPerson').onclick = function() { addNewPersonModal.classList.remove('show'); };
                                        document.getElementById('cancelAddNewPerson').onclick = function() { addNewPersonModal.classList.remove('show'); };
                                        addPersonConfirm.onclick = async function() {
                                            var newPerson = newPersonName.value.trim();
                                            if (!newPerson) {
                                                alert('Please enter a person name!');
                                                return;
                                            }
                                            addPersonConfirm.disabled = true;
                                            try {
                                                var res = await fetch('<?= base_url('SongController/ajax_create_person') ?>', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                    body: 'name=' + encodeURIComponent(newPerson) + '&type_id=0'
                                                });
                                                var data = await res.json();
                                                if (data && data.success) {
                                                    var option = document.createElement('option');
                                                    option.value = data.id;
                                                    option.text = data.fullName || newPerson;
                                                    option.selected = true;
                                                    peopleSelect.add(option);
                                                    if (window.jQuery && $('#related_people').length) $('#related_people').trigger('change');
                                                    addNewPersonModal.classList.remove('show');
                                                    newPersonName.value = '';
                                                    if (window.Swal) Swal.fire({icon:'success',title:'Success',text:'Person added!',timer:1200,showConfirmButton:false});
                                                } else {
                                                    alert('Failed to add person!');
                                                }
                                            } catch (e) {
                                                alert('Error: ' + e.message);
                                            }
                                            addPersonConfirm.disabled = false;
                                        };
                                    }
                                    // Film
                                    var addNewFilmBtn = document.getElementById('addNewFilmBtn');
                                    var addNewFilmModal = document.getElementById('addNewFilmModal');
                                    var addFilmConfirm = document.getElementById('addFilmConfirm');
                                    var newFilmTitle = document.getElementById('newFilmTitle');
                                    var filmsSelect = document.getElementById('films');
                                    if (addNewFilmBtn && addNewFilmModal && addFilmConfirm && newFilmTitle && filmsSelect) {
                                        addNewFilmBtn.onclick = function() {
                                            addNewFilmModal.classList.add('show');
                                            newFilmTitle.value = '';
                                            setTimeout(function() { newFilmTitle.focus(); }, 300);
                                        };
                                        document.getElementById('closeAddNewFilm').onclick = function() { addNewFilmModal.classList.remove('show'); };
                                        document.getElementById('cancelAddNewFilm').onclick = function() { addNewFilmModal.classList.remove('show'); };
                                        addFilmConfirm.onclick = async function() {
                                            var newFilm = newFilmTitle.value.trim();
                                            if (!newFilm) {
                                                alert('Please enter a film title!');
                                                return;
                                            }
                                            addFilmConfirm.disabled = true;
                                            try {
                                                var res = await fetch('<?= base_url('SongController/ajax_create_film') ?>', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                    body: 'main_title=' + encodeURIComponent(newFilm)
                                                });
                                                var data = await res.json();
                                                if (data && data.status === 'success') {
                                                    var option = document.createElement('option');
                                                    option.value = data.film_id || data.id;
                                                    option.text = data.main_title || newFilm;
                                                    option.selected = true;
                                                    filmsSelect.add(option);
                                                    if (window.jQuery && $('#films').length) $('#films').trigger('change');
                                                    addNewFilmModal.classList.remove('show');
                                                    newFilmTitle.value = '';
                                                    if (window.Swal) Swal.fire({icon:'success',title:'Success',text:data.message || 'Film added!',timer:1200,showConfirmButton:false});
                                                } else {
                                                    alert((data && data.message) ? data.message : 'Failed to add film!');
                                                }
                                            } catch (e) {
                                                alert('Error: ' + e.message);
                                            }
                                            addFilmConfirm.disabled = false;
                                        };
                                    }
                                    // Film Episode
                                    var addNewEpisodeBtn = document.getElementById('addNewEpisodeBtn');
                                    var addNewEpisodeModal = document.getElementById('addNewEpisodeModal');
                                    var addEpisodeConfirm = document.getElementById('addEpisodeConfirm');
                                    var newEpisodeTitle = document.getElementById('newEpisodeTitle');
                                    var episodesSelect = document.getElementById('film_episodes');
                                    if (addNewEpisodeBtn && addNewEpisodeModal && addEpisodeConfirm && newEpisodeTitle && episodesSelect) {
                                        addNewEpisodeBtn.onclick = function() {
                                            addNewEpisodeModal.classList.add('show');
                                            newEpisodeTitle.value = '';
                                            setTimeout(function() { newEpisodeTitle.focus(); }, 300);
                                        };
                                        document.getElementById('closeAddNewEpisode').onclick = function() { addNewEpisodeModal.classList.remove('show'); };
                                        document.getElementById('cancelAddNewEpisode').onclick = function() { addNewEpisodeModal.classList.remove('show'); };
                                        addEpisodeConfirm.onclick = async function() {
                                            var newEpisode = newEpisodeTitle.value.trim();
                                            if (!newEpisode) {
                                                alert('Please enter an episode title!');
                                                return;
                                            }
                                            addEpisodeConfirm.disabled = true;
                                            try {
                                                var res = await fetch('<?= base_url('SongController/ajax_create_episode') ?>', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                    body: 'film_episode_title=' + encodeURIComponent(newEpisode)
                                                });
                                                var data = await res.json();
                                                if (data && data.status === 'success') {
                                                    var option = document.createElement('option');
                                                    option.value = data.episode_id || data.id;
                                                    option.text = data.film_episode_title || newEpisode;
                                                    option.selected = true;
                                                    episodesSelect.add(option);
                                                    if (window.jQuery && $('#film_episodes').length) $('#film_episodes').trigger('change');
                                                    addNewEpisodeModal.classList.remove('show');
                                                    newEpisodeTitle.value = '';
                                                    if (window.Swal) Swal.fire({icon:'success',title:'Success',text:data.message || 'Episode added!',timer:1200,showConfirmButton:false});
                                                } else {
                                                    alert((data && data.message) ? data.message : 'Failed to add episode!');
                                                }
                                            } catch (e) {
                                                alert('Error: ' + e.message);
                                            }
                                            addEpisodeConfirm.disabled = false;
                                        };
                                    }
                                });
                                </script>
                                </div>
                            </div>
                        </div>
                        </div>
                        
                         <label>Meta Data </label>
                         <div style="padding-left:20px;">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>⊙ Meta Title</label>
                                    <input type="text" name="meta_title" class="form-control col-md-4" value="<?= isset($couplet['meta_title']) ? $couplet['meta_title'] : ''; ?>" placeholder="Enter Meta Title">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>⊙ Meta Keywords</label>
                                    <input type="text" name="meta_keywords" class="form-control col-md-4" value="<?= isset($couplet['meta_keywords']) ? $couplet['meta_keywords'] : ''; ?>" placeholder="Enter Meta Keywords">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>⊙ Meta Description</label>
                                    <textarea class="form-control col-md-8" name="meta_description" rows="3" placeholder="Enter Meta Description"><?= isset($couplet['meta_description']) ? $couplet['meta_description'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                        </div>
                        <!-- Publish Options -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                <label>Publish Status</label>
                                <select name="is_published" class="form-control col-md-4">
                                    <option value="false" <?= (!isset($couplet['is_published']) || $couplet['is_published'] == 'false') ? 'selected' : ''; ?>>No</option>
                                    <option value="true" <?= isset($couplet['is_published']) && $couplet['is_published'] == 'true' ? 'selected' : ''; ?>>Yes</option>
                                    
                                </select>
                            </div>
                            </div>
                        </div>

                        <div class="save-btn-container">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>

                    <script>
                        jQuery(function ($) {
                        $(window).on('load', function () {
                        <?php if(isset($couplet)) { ?>
                            // Set values for edit (after footer loads Select2 / AdminLTE)
                            <?php
                            $poets = isset($couplet['poet_id']) ? couplet_parse_id_list($couplet['poet_id']) : [];
                            if (!empty($poets)) :
                            ?>
                                $('#poet').val(<?php echo json_encode($poets); ?>).trigger('change');
                            <?php endif; ?>
                            <?php
                            $apoets = isset($couplet['attributed_poet']) ? couplet_parse_id_list($couplet['attributed_poet']) : [];
                            if (!empty($apoets)) :
                            ?>
                                $('#attributed_poet').val(<?php echo json_encode($apoets); ?>).trigger('change');
                            <?php endif; ?>
                            <?php
                            $trans = isset($couplet['translator']) ? couplet_parse_id_list($couplet['translator']) : [];
                            if (!empty($trans)) :
                            ?>
                                $('#translator').val(<?php echo json_encode($trans); ?>).trigger('change');
                            <?php endif; ?>
                            <?php if(isset($couplet['soundCloud_track_url']) && $couplet['soundCloud_track_url']) { $urls = @unserialize($couplet['soundCloud_track_url']); if (!is_array($urls)) { $urls = []; } ?>
                                // For soundcloud, add inputs
                                const container = document.getElementById('soundcloudUrlsContainer');
                                container.innerHTML = '';
                                <?php foreach($urls as $url) { if($url) { ?>
                                    const newInput = document.createElement('div');
                                    newInput.className = 'input-group mb-2'; 
                                    newInput.innerHTML = `<input type="text" name="soundCloud_track_url[]" class="form-control" value="<?php echo addslashes($url); ?>" placeholder="Enter SoundCloud URL"><div class="input-group-append"><button type="button" class="btn btn-danger" onclick="removeSoundcloudUrl(this)">Remove</button></div>`;
                                    container.appendChild(newInput);
                                <?php } } ?>
                            <?php } ?>
                            <?php if(isset($couplet['related_songs']) && $couplet['related_songs']) { $songs = unserialize($couplet['related_songs']); ?>
                                $('select[name="related_songs[]"]').val(<?php echo json_encode($songs); ?>).trigger('change');
                            <?php } ?>
                            <?php if(isset($couplet['related_reflections']) && $couplet['related_reflections']) { $refs = unserialize($couplet['related_reflections']); ?>
                                $('select[name="reflections[]"]').val(<?php echo json_encode($refs); ?>).trigger('change');
                            <?php } ?>
                            <?php if(isset($couplet['related_poems']) && $couplet['related_poems']) { $poems = unserialize($couplet['related_poems']); ?>
                                $('select[name="relatedpoems[]"]').val(<?php echo json_encode($poems); ?>).trigger('change');
                            <?php } ?>
                            <?php if(isset($couplet['related_people']) && $couplet['related_people']) { $people = unserialize($couplet['related_people']); ?>
                                $('select[name="related_people[]"]').val(<?php echo json_encode($people); ?>).trigger('change');
                            <?php } ?>
                            <?php if(isset($couplet['related_films']) && $couplet['related_films']) { $films = unserialize($couplet['related_films']); ?>
                                $('select[name="films[]"]').val(<?php echo json_encode($films); ?>).trigger('change');
                            <?php } ?>
                            <?php if(isset($couplet['related_film_episodes']) && $couplet['related_film_episodes']) { $episodes = unserialize($couplet['related_film_episodes']); ?>
                                $('select[name="film_episodes[]"]').val(<?php echo json_encode($episodes); ?>).trigger('change');
                            <?php } ?>
                        <?php } ?>
                        });
                        });
                    </script>

                    <!-- Add Poet Modal -->
                    <div class="modal" id="addPoetModal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2>Add New Poet</h2>
                                <button class="close-btn" id="closeAddPoet">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" id="addPoetName" placeholder="Enter Poet Name">
                                </div>
                                <div class="form-group">
                                    <label>External Hyperlink (optional)</label>
                                    <input type="url" id="addPoetUrl" placeholder="Enter URL">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn-secondary" id="cancelAddPoet">Cancel</button>
                                <button class="btn-success" id="addPoet">Add</button>
                            </div>
                        </div>
                    </div>

                    <!-- Add Attributed Poet Modal -->
                    <div class="modal" id="addAttributedPoetModal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2>Add New Attributed Poet</h2>
                                <button class="close-btn" id="closeAddAttributedPoet">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" id="addAttributedPoetName" placeholder="Enter Attributed Poet Name">
                                </div>
                                <div class="form-group">
                                    <label>External Hyperlink (optional)</label>
                                    <input type="url" id="addAttributedPoetUrl" placeholder="Enter URL">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn-secondary" id="cancelAddAttributedPoet">Cancel</button>
                                <button class="btn-success" id="addAttributedPoet">Add</button>
                            </div>
                        </div>
                    </div>

                    <!-- Add Translator Modal -->
                    <div class="modal" id="addTranslatorModal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2>Add New Translator</h2>
                                <button class="close-btn" id="closeAddTranslator">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" id="addTranslatorName" placeholder="Enter Translator Name">
                                </div>
                                <div class="form-group">
                                    <label>External Hyperlink (optional)</label>
                                    <input type="url" id="addTranslatorUrl" placeholder="Enter URL">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn-secondary" id="cancelAddTranslator">Cancel</button>
                                <button class="btn-success" id="addTranslator">Add</button>
                            </div>
                        </div>
                    </div>

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
                function togglePopup(selectElem) {
                    const popup = selectElem.parentElement.querySelector('.popup-dropdown');
                    // Close all other popups
                    document.querySelectorAll('.popup-dropdown').forEach(p => {
                        if (p !== popup) p.style.display = 'none';
                    });
                    // Toggle this one
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
                
                </script>
             <script>
                // Year dropdown fill karna
                const yearSelect = document.getElementById('yearSelect');
                const currentYear = new Date().getFullYear();

                for(let y = currentYear; y >= 1900; y--){
                    const option = document.createElement('option');
                    option.value = y;
                    option.text = y;
                    yearSelect.appendChild(option);
                }
            </script>
<script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>

                <script>
                        setTimeout(function () {

                            // Initialize all 4 editors (add all textarea IDs here)
                            const editorIDs = [
                                'original_text',       // 1️⃣
                                'english_transliteration_text',     // 2️⃣
                                'english_translation_text',          // 3️⃣
                                'note_text',
                                'glossary'         // 4️⃣
                            ];

                            editorIDs.forEach(function (id) {
                                CKEDITOR.replace(id, {
                                    height: 200,
                                    extraPlugins: 'colorbutton,font,justify',
                                    toolbar: [ 
                                        { name: 'document', items: [ 'Source', '-', 'NewPage', 'Preview' ] },
                                        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat' ] },
                                        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                                        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
                                        { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                                        { name: 'colors', items: [ 'TextColor', 'BGColor' ] }
                                    ]
                                });
                            });

                        }, 500);
                        </script>
                        <script>
                        $(function() {
                            $('.select2').select2();
                        });
                        </script>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('coupletForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent form submission

    const fields = [
        { id: 'couplet_transliteration', name: 'Poem Title - Transliteration' },
        { id: 'couplet_translation', name: 'Poem Title - Translation' },
        { id: 'poet', name: 'Poet' },
        { id: 'thumbnailUrl', name: 'Thumbnail Image Upload' },
        { id: 'is_published', name: 'Publish Status' }
    ];

    for (let field of fields) {
        let element = document.getElementById(field.id);
        if (!element) continue;

        let isEmpty = false;

        if (field.id === 'thumbnailUrl') {
            let existing = document.getElementById('thumbnailUrl_existing');
            if (existing && existing.value && existing.value.trim() !== '') {
                continue;
            }
            if (element.files && element.files.length > 0) {
                continue;
            }
            isEmpty = true;
        } else if (element.tagName === 'INPUT' && element.type === 'file') {
            isEmpty = !element.files || element.files.length === 0;
        } else if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
            isEmpty = element.value.trim() === '';
        } else if (element.tagName === 'SELECT') {
            if (element.multiple) {
                isEmpty = element.selectedOptions.length === 0;
            } else {
                isEmpty = !element.value;
            }
        }

        if (isEmpty) {
            // Focus the field
            element.focus();

            // Show EXACT popup like your screenshot
            Swal.fire({
                icon: 'warning',
                title: 'Missing Input',
                text: `Please fill the ${field.name}`,
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: {
                    popup: 'custom-missing-popup',
                    title: 'custom-missing-title',
                    content: 'custom-missing-text',
                    confirmButton: 'custom-missing-ok-btn'
                },
                buttonsStyling: false,
                backdrop: `rgba(0,0,0,0.6)`,
                heightAuto: false
            });

            return false; // Stop checking further
        }
    }

    // If all fields are filled, submit form
    this.submit();
});
</script>
<script>
// No client-side word limit on thumbnail excerpt.
</script>
<script>
$(function() {
    var poetChanging = false;
    var attrPoetChanging = false;
    
    $('#poet').on('change', function() {
        if (poetChanging) return;
        if ($(this).val() && $(this).val().length > 0) {
            $('#attributedPoetContainer').hide();
            attrPoetChanging = true;
            $('#attributed_poet').val(null).trigger('change');
            attrPoetChanging = false;
        } else {
            $('#attributedPoetContainer').show();
        }
    });
    
    $('#attributed_poet').on('change', function() {
        if (attrPoetChanging) return;
        if ($(this).val() && $(this).val().length > 0) {
            $('#poetContainer').hide();
            poetChanging = true;
            $('#poet').val(null).trigger('change');
            poetChanging = false;
        } else {
            $('#poetContainer').show();
        }
    });
});
</script>
<script>
// Poet Modal
document.getElementById('addPoetBtn').addEventListener('click', function() {
    document.getElementById('addPoetModal').classList.add('show');
});
document.getElementById('closeAddPoet').addEventListener('click', function() {
    document.getElementById('addPoetModal').classList.remove('show');
});
document.getElementById('cancelAddPoet').addEventListener('click', function() {
    document.getElementById('addPoetModal').classList.remove('show');
});
document.getElementById('addPoetModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.remove('show');
    }
});
document.getElementById('addPoet').addEventListener('click', function() {
    const name = document.getElementById('addPoetName').value.trim();
    const hyperlink = document.getElementById('addPoetUrl').value.trim();
    if (name) {
        fetch('<?php echo base_url("person/ajax-create"); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name: name, hyperlink: hyperlink })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('poet');
                const option = new Option(data.fullName, data.id);
                select.appendChild(option);
                $(select).val([...$(select).val() || [], data.id]).trigger('change');
                document.getElementById('addPoetModal').classList.remove('show');
                document.getElementById('addPoetName').value = '';
                document.getElementById('addPoetUrl').value = '';
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Poet added successfully!',
                    confirmButtonText: 'OK',
                    customClass: { confirmButton: 'custom-missing-ok-btn' },
                    buttonsStyling: false
                });
            } else {
                alert('Error: ' + (data.message || 'Failed to add poet'));
            }
        })
        .catch(err => alert('Error: ' + err.message));
    } else {
        alert("Please enter a name.");
    }
});

// Attributed Poet Modal
document.getElementById('addAttributedPoetBtn').addEventListener('click', function() {
    document.getElementById('addAttributedPoetModal').classList.add('show');
});
document.getElementById('closeAddAttributedPoet').addEventListener('click', function() {
    document.getElementById('addAttributedPoetModal').classList.remove('show');
});
document.getElementById('cancelAddAttributedPoet').addEventListener('click', function() {
    document.getElementById('addAttributedPoetModal').classList.remove('show');
});
document.getElementById('addAttributedPoetModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.remove('show');
    }
});
document.getElementById('addAttributedPoet').addEventListener('click', function() {
    const name = document.getElementById('addAttributedPoetName').value.trim();
    const hyperlink = document.getElementById('addAttributedPoetUrl').value.trim();
    if (name) {
        fetch('<?php echo base_url("person/ajax-create"); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name: name, hyperlink: hyperlink })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('attributed_poet');
                const option = new Option(data.fullName, data.id);
                select.appendChild(option);
                $(select).val([...$(select).val() || [], data.id]).trigger('change');
                document.getElementById('addAttributedPoetModal').classList.remove('show');
                document.getElementById('addAttributedPoetName').value = '';
                document.getElementById('addAttributedPoetUrl').value = '';
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Attributed Poet added successfully!',
                    confirmButtonText: 'OK',
                    customClass: { confirmButton: 'custom-missing-ok-btn' },
                    buttonsStyling: false
                });
            } else {
                alert('Error: ' + (data.message || 'Failed to add attributed poet'));
            }
        })
        .catch(err => alert('Error: ' + err.message));
    } else {
        alert("Please enter a name.");
    }
});

// Translator Modal
(function () {
    var addTranslatorBtn = document.getElementById('addTranslatorBtn');
    var addTranslatorModal = document.getElementById('addTranslatorModal');
    var closeAddTranslator = document.getElementById('closeAddTranslator');
    var cancelAddTranslator = document.getElementById('cancelAddTranslator');
    var addTranslator = document.getElementById('addTranslator');
    if (!addTranslatorBtn || !addTranslatorModal || !closeAddTranslator || !cancelAddTranslator || !addTranslator) {
        return;
    }
    addTranslatorBtn.addEventListener('click', function() {
        addTranslatorModal.classList.add('show');
    });
    closeAddTranslator.addEventListener('click', function() {
        addTranslatorModal.classList.remove('show');
    });
    cancelAddTranslator.addEventListener('click', function() {
        addTranslatorModal.classList.remove('show');
    });
    addTranslatorModal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('show');
        }
    });
    addTranslator.addEventListener('click', function() {
        const name = document.getElementById('addTranslatorName').value.trim();
        const hyperlink = document.getElementById('addTranslatorUrl').value.trim();
        if (name) {
            fetch('<?php echo base_url("person/ajax-create"); ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name: name, hyperlink: hyperlink })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const select = document.getElementById('translator');
                    const option = new Option(data.fullName, data.id);
                    select.appendChild(option);
                    $(select).val([...$(select).val() || [], data.id]).trigger('change');
                    addTranslatorModal.classList.remove('show');
                    document.getElementById('addTranslatorName').value = '';
                    document.getElementById('addTranslatorUrl').value = '';
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Translator added successfully!',
                        confirmButtonText: 'OK',
                        customClass: { confirmButton: 'custom-missing-ok-btn' },
                        buttonsStyling: false
                    });
                } else {
                    alert('Error: ' + (data.message || 'Failed to add translator'));
                }
            })
            .catch(err => alert('Error: ' + err.message));
        } else {
            alert("Please enter a name.");
        }
    });
})();

// Dynamic extra translation blocks
(function() {
    var addBtn = document.getElementById('addCoupletTranslationBtn');
    var container = document.getElementById('extraCoupletTranslations');
    var baseTranslator = document.getElementById('translator');
    if (!addBtn || !container || !baseTranslator) return;

    var extraIndex = 0;

    function buildTranslatorOptionsHtml() {
        var html = '';
        Array.prototype.forEach.call(baseTranslator.options, function(opt) {
            html += '<option value="' + (opt.value || '') + '">' + (opt.text || '') + '</option>';
        });
        return html;
    }

    function addTranslationBlock(initialText) {
        extraIndex += 1;
        var blockId = 'extraTranslationBlock_' + extraIndex;
        var selectId = 'extra_translation_translator_' + extraIndex;
        var textareaId = 'extra_translation_text_' + extraIndex;

        var wrapper = document.createElement('div');
        wrapper.id = blockId;
        wrapper.className = 'mb-3 p-2 translation-stack translation-block';
        wrapper.style.border = '1px solid #e5e5e5';
        wrapper.style.borderRadius = '6px';
        wrapper.innerHTML = ''
            + '<div class="translation-help-text">Please deselect translators before deleting translation</div>'
            + '<div class="translation-control translation-select-row">'
            + '  <select class="form-control select2 col-md-4" multiple="multiple" name="extra_translator[' + extraIndex + '][]" id="' + selectId + '" data-placeholder="Select Translators">'
            +        buildTranslatorOptionsHtml()
            + '  </select>'
            + '  <button type="button" class="btn btn-danger btn-sm remove-extra-translation">Delete</button>'
            + '</div>'
            + '<div class="translation-control translation-editor-wrap">'
            + '  <textarea id="' + textareaId + '" name="extra_translation_text[]" class="form-control"></textarea>'
            + '</div>';

        container.appendChild(wrapper);

        if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
            window.jQuery('#' + selectId).select2();
        }

        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace(textareaId, {
                height: 200,
                extraPlugins: 'colorbutton,font,justify',
                toolbar: [ 
                    { name: 'document', items: [ 'Source', '-', 'NewPage', 'Preview' ] },
                    { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat' ] },
                    { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                    { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
                    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                    { name: 'colors', items: [ 'TextColor', 'BGColor' ] }
                ]
            });
            if (initialText && CKEDITOR.instances[textareaId]) {
                CKEDITOR.instances[textareaId].setData(initialText);
            } else if (initialText) {
                setTimeout(function() {
                    if (CKEDITOR.instances[textareaId]) {
                        CKEDITOR.instances[textareaId].setData(initialText);
                    }
                }, 300);
            }
        } else if (initialText) {
            var ta = document.getElementById(textareaId);
            if (ta) ta.value = initialText;
        }
    }

    addBtn.addEventListener('click', function() {
        addTranslationBlock('');
    });

    container.addEventListener('click', function(e) {
        if (!e.target.classList.contains('remove-extra-translation')) return;
        var block = e.target.closest('div[id^="extraTranslationBlock_"]');
        if (!block) return;
        var textarea = block.querySelector('textarea');
        if (textarea && typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[textarea.id]) {
            CKEDITOR.instances[textarea.id].destroy(true);
        }
        block.remove();
    });

    // Preload extra translations in edit mode
    var preload = <?= json_encode(isset($couplet['extra_translation_rows']) ? $couplet['extra_translation_rows'] : []) ?>;
    if (Array.isArray(preload) && preload.length > 0) {
        preload.forEach(function(row) {
            addTranslationBlock((row && row.text) ? row.text : '');
        });
    }
})();
</script>
<?php include('inc/footer.php'); ?>
