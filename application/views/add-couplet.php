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

                    // Generic helper: read IDs from junction table for this couplet, fallback to legacy CSV/serialized field
                    $coupletId = (isset($couplet['id']) && (int)$couplet['id'] > 0) ? (int)$couplet['id'] : 0;
                    $readJunction = function ($table, $fkCol, $legacyField = null) use ($coupletId, $couplet, $readSelectedValues) {
                        $ids = [];
                        if ($coupletId > 0 && $this->db->table_exists($table)) {
                            $jr = $this->db->select($fkCol)->from($table)->where('couplet_id', $coupletId)->get()->result_array();
                            foreach ($jr as $r) { if (!empty($r[$fkCol])) { $ids[] = (string)(int)$r[$fkCol]; } }
                            $ids = array_values(array_unique($ids));
                        }
                        if (empty($ids) && $legacyField !== null && isset($couplet[$legacyField])) {
                            $ids = $readSelectedValues($couplet[$legacyField]);
                        }
                        return $ids;
                    };

                    $selected_related_keywords      = $readJunction('couplet_word',           'word_id',            'keywords');
                    $selected_related_songs         = $readJunction('couplet_song',           'song_id',            'related_songs');
                    $selected_reflections           = $readJunction('couplet_reflection',     'reflection_id',      'related_reflections');
                    $selected_related_poems         = $readJunction('couplet_relatedcouplet', 'related_couplet_id', 'related_poems');
                    $selected_related_films         = $readJunction('couplet_film',           'film_id',            'related_films');
                    $selected_related_film_episodes = $readJunction('couplet_filmepisode',    'film_episode_id',    'related_film_episodes');
                    $selected_related_people        = $readJunction('couplet_people',         'person_id',          'related_people');

                    // All people, A–Z by full name (same ordering for Poet / Attributed Poet / Translators / Related people).
                    $all_people_rows = $this->db->query("
                        SELECT id, first_name, middle_name, last_name FROM person
                        ORDER BY LOWER(TRIM(CONCAT_WS(' ', IFNULL(first_name,''), IFNULL(middle_name,''), IFNULL(last_name,'')))) ASC, id ASC
                    ")->result();
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
                                    <input type="text" id="couplet_translation" name="couplet_translation" class="form-control col-md-4" value="<?= isset($couplet['couplet_translation']) ? htmlspecialchars($couplet['couplet_translation']) : ''; ?>" placeholder="Enter Poem Title - Translation" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>⊙ Translation <span style="color:red">*</span></label>
                                    <input type="text" id="couplet_transliteration" name="couplet_transliteration" class="form-control col-md-4" value="<?= isset($couplet['couplet_transliteration']) ? htmlspecialchars($couplet['couplet_transliteration']) : ''; ?>" placeholder="Enter Poem Title - Transliteration" required>
                                    
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
                                        <?php
                                            // Build pre-selected poet IDs: from couplet.poet_id (INT), couplet.poet (legacy serialized), and couplet_poet junction
                                            $preSelectedPoets = [];
                                            if (isset($couplet) && is_array($couplet)) {
                                                if (!empty($couplet['poet_id'])) { $preSelectedPoets[] = (string)(int)$couplet['poet_id']; }
                                                if (!empty($couplet['poet'])) {
                                                    $u = @unserialize($couplet['poet']);
                                                    if (is_array($u)) { foreach ($u as $v) { $preSelectedPoets[] = (string)(int)$v; } }
                                                }
                                                $cId = (int)($couplet['id'] ?? 0);
                                                if ($cId > 0 && $this->db->table_exists('couplet_poet')) {
                                                    $jr = $this->db->select('poet_id')->from('couplet_poet')->where('couplet_id', $cId)->get()->result_array();
                                                    foreach ($jr as $r) { if (!empty($r['poet_id'])) { $preSelectedPoets[] = (string)(int)$r['poet_id']; } }
                                                }
                                            }
                                            $preSelectedPoets = array_values(array_unique(array_filter($preSelectedPoets, function($x){ return $x !== '' && $x !== '0'; })));
                                        ?>
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="poet[]" id="poet" data-placeholder="Select Poet" required>
                                            <?php
                                                foreach ($all_people_rows as $p) {
                                                    $parts = [];
                                                    if (!empty(trim($p->first_name))) { $parts[] = trim($p->first_name); }
                                                    if (!empty(trim($p->middle_name))) { $parts[] = trim($p->middle_name); }
                                                    if (!empty(trim($p->last_name))) { $parts[] = trim($p->last_name); }
                                                    $fullName = implode(' ', $parts);
                                                    if ($fullName === '') { $fullName = 'Unnamed'; }
                                                    $pid = (string)$p->id;
                                                    $sel = in_array($pid, $preSelectedPoets, true) ? ' selected' : '';
                                                    echo '<option value="'.htmlspecialchars($pid).'"'.$sel.'>'.htmlspecialchars($fullName).'</option>';
                                                }
                                            ?>
                                        </select>
                                        <button type="button" class="btn btn-success btn-sm" id="addPoetBtn">Add New</button>
                                        <button type="button" class="btn btn-primary btn-sm ml-1" id="editPoetBtn">Edit</button>
                                        <button type="button" class="btn btn-danger btn-sm ml-1" id="deletePoetBtn">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="attributedPoetContainer">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Attributed Poet</label>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <?php
                                            $preSelectedAttrPoets = [];
                                            if (isset($couplet) && is_array($couplet) && !empty($couplet['attributed_poet'])) {
                                                $u = @unserialize($couplet['attributed_poet']);
                                                if (is_array($u)) { foreach ($u as $v) { $preSelectedAttrPoets[] = (string)(int)$v; } }
                                                elseif (is_string($couplet['attributed_poet'])) {
                                                    foreach (array_filter(array_map('trim', explode(',', $couplet['attributed_poet']))) as $v) {
                                                        if (ctype_digit($v)) { $preSelectedAttrPoets[] = (string)(int)$v; }
                                                    }
                                                }
                                            }
                                            $preSelectedAttrPoets = array_values(array_unique(array_filter($preSelectedAttrPoets, function($x){ return $x !== '' && $x !== '0'; })));
                                        ?>
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="attributed_poet[]" id="attributed_poet" data-placeholder="Select Attributed Poet">
                                            <?php
                                                foreach ($all_people_rows as $p) {
                                                    $parts = [];
                                                    if (!empty(trim($p->first_name))) { $parts[] = trim($p->first_name); }
                                                    if (!empty(trim($p->middle_name))) { $parts[] = trim($p->middle_name); }
                                                    if (!empty(trim($p->last_name))) { $parts[] = trim($p->last_name); }
                                                    $fullName = implode(' ', $parts);
                                                    if ($fullName === '') { $fullName = 'Unnamed'; }
                                                    $pid = (string)$p->id;
                                                    $sel = in_array($pid, $preSelectedAttrPoets, true) ? ' selected' : '';
                                                    echo '<option value="'.htmlspecialchars($pid).'"'.$sel.'>'.htmlspecialchars($fullName).'</option>';
                                                }
                                            ?>
                                        </select>
                                        <button type="button" class="btn btn-success btn-sm" id="addAttributedPoetBtn">Add New</button>
                                        <button type="button" class="btn btn-primary btn-sm ml-1" id="editAttributedPoetBtn">Edit</button>
                                        <button type="button" class="btn btn-danger btn-sm ml-1" id="deleteAttributedPoetBtn">Delete</button>
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
                                .translation-stack {
                                    display: flex;
                                    align-items: center;
                                    margin-bottom: 18px;
                                }
                                .translation-stack label {
                                    flex: 0 0 220px;
                                    max-width: 220px;
                                    font-weight: 600;
                                    color: #333;
                                    margin-bottom: 0;
                                    font-size: 15px;
                                    padding-right: 18px;
                                    display: block;
                                }
                                .translation-stack .translation-main-group {
                                    flex: 1 1 0%;
                                    margin-bottom: 0;
                                    display: flex;
                                    flex-direction: column;
                                    gap: 8px;
                                }
                                .translation-stack .translation-control {
                                    display: block;
                                    width: 100%;
                                    margin-bottom: 0;
                                    clear: both;
                                }
                                .translation-stack .translation-select-row {
                                    display: flex;
                                    align-items: center;
                                    gap: 10px;
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
                            </style>
                            <div class="row">
                                <div class="col-12">
                                <div class="form-group translation-stack"  style="display: flex; align-items: flex-start; margin-bottom: 18px;">
                                    
                                    <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">⊙ Translation</div>
                                    <div class="translation-main-group translation-block">
                                        <div class="translation-control translation-select-row">
                                            <?php
                                                $preSelectedTranslators = [];
                                                if (isset($couplet) && is_array($couplet) && !empty($couplet['translator'])) {
                                                    $u = @unserialize($couplet['translator']);
                                                    if (is_array($u)) { foreach ($u as $v) { $preSelectedTranslators[] = (string)(int)$v; } }
                                                    elseif (is_string($couplet['translator'])) {
                                                        foreach (array_filter(array_map('trim', explode(',', $couplet['translator']))) as $v) {
                                                            if (ctype_digit($v)) { $preSelectedTranslators[] = (string)(int)$v; }
                                                        }
                                                    }
                                                }
                                                $preSelectedTranslators = array_values(array_unique(array_filter($preSelectedTranslators, function($x){ return $x !== '' && $x !== '0'; })));
                                            ?>
                                            <select class="form-control select2 col-md-4" multiple="multiple" name="translator[]" id="translator" data-placeholder="Select Translators">
                                                <?php
                                                    foreach ($all_people_rows as $p) {
                                                        $parts = [];
                                                        if (!empty(trim($p->first_name))) { $parts[] = trim($p->first_name); }
                                                        if (!empty(trim($p->middle_name))) { $parts[] = trim($p->middle_name); }
                                                        if (!empty(trim($p->last_name))) { $parts[] = trim($p->last_name); }
                                                        $fullName = implode(' ', $parts);
                                                        if ($fullName === '') { $fullName = 'Unnamed'; }
                                                        $pid = (string)$p->id;
                                                        $sel = in_array($pid, $preSelectedTranslators, true) ? ' selected' : '';
                                                        echo '<option value="'.htmlspecialchars($pid).'"'.$sel.'>'.htmlspecialchars($fullName).'</option>';
                                                    }
                                                ?>
                                            </select>
                                            <button type="button" class="btn btn-success btn-sm" id="addTranslatorBtn">Add New</button>
                                            <button type="button" class="btn btn-primary btn-sm ml-1" id="editTranslatorBtn">Edit</button>
                                            <button type="button" class="btn btn-danger btn-sm ml-1" id="deleteTranslatorBtn">Delete</button>
                                        </div>
                                        <div class="translation-control translation-editor-wrap">
                                            <textarea id="english_translation_text" name="english_translation_text" class="form-control"><?= isset($couplet['english_translation_text']) ? htmlspecialchars($couplet['english_translation_text']) : ''; ?></textarea>
                                        </div>
                                        <div class="translation-control">
                                            <button type="button" class="btn btn-primary btn-sm" id="addCoupletTranslationBtn">Add Couplet Translation</button>
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

                        <?php
                        // ============================================================
                        // Poem Glossary — Song Glossary style: multi-select from `word`
                        // table + "Add New" + "Edit" modal. DB column `couplet.glossary`
                        // is kept as free TEXT for backward compatibility: on save the
                        // selected words' transliterations are stored comma-separated.
                        // ============================================================
                        $couplet_glossary_raw = isset($couplet['glossary']) ? trim((string) $couplet['glossary']) : '';

                        $glossary_word_rows = $this->db->table_exists('word')
                            ? $this->db->query("SELECT id, word_transliteration FROM word ORDER BY LOWER(TRIM(COALESCE(word_transliteration,''))) ASC, id ASC")->result()
                            : [];

                        // Map transliteration -> id (lowercased & trimmed) for preselect matching
                        $glossary_translit_to_id = [];
                        foreach ($glossary_word_rows as $gw) {
                            $key = mb_strtolower(trim((string) $gw->word_transliteration));
                            if ($key !== '' && !isset($glossary_translit_to_id[$key])) {
                                $glossary_translit_to_id[$key] = (string) (int) $gw->id;
                            }
                        }

                        // Split existing free-text glossary on commas / newlines, match to words
                        $selected_couplet_glossary = [];
                        $unmatched_glossary_tokens = [];
                        if ($couplet_glossary_raw !== '') {
                            $tokens = preg_split('/[,\n]+/u', $couplet_glossary_raw);
                            foreach ($tokens as $tok) {
                                $tok = trim((string) $tok);
                                if ($tok === '') continue;
                                $k = mb_strtolower($tok);
                                if (isset($glossary_translit_to_id[$k])) {
                                    $selected_couplet_glossary[$glossary_translit_to_id[$k]] = true;
                                } else {
                                    $unmatched_glossary_tokens[] = $tok;
                                }
                            }
                        }
                        $selected_couplet_glossary = array_keys($selected_couplet_glossary);
                        ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group" style="display:flex; align-items:center; flex-wrap:wrap; gap:8px;">
                                    <label style="margin-bottom:0;">Poem Glossary</label>
                                    <div class="input-btn-group" style="display:inline-flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                        <select class="form-control select2" multiple="multiple" data-skip-select2="true" name="glossary[]" id="coupletglossary" data-placeholder="Select Glossary Term">
                                            <?php foreach ($glossary_word_rows as $gw): ?>
                                                <option value="<?= (int) $gw->id ?>" <?= in_array((string) $gw->id, $selected_couplet_glossary, true) ? 'selected' : '' ?>><?= htmlspecialchars((string) $gw->word_transliteration) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-sm btn-success" id="addGlossaryWordBtn" style="white-space:nowrap;">Add New</button>
                                        <button type="button" class="btn btn-sm btn-primary ml-1" id="editGlossaryWordBtn" style="white-space:nowrap;">Edit</button>
                                        <button type="button" class="btn btn-sm btn-danger ml-1" id="deleteGlossaryWordBtn" style="white-space:nowrap;">Delete</button>
                                    </div>
                                    <?php if (!empty($unmatched_glossary_tokens)): ?>
                                        <!-- Preserve any free-text glossary entries that don't match a word row, so editing doesn't lose them. -->
                                        <input type="hidden" name="glossary_extra_text" value="<?= htmlspecialchars(implode(', ', $unmatched_glossary_tokens)) ?>">
                                        <small class="text-muted" style="flex-basis:100%;">Existing free-text entries kept as-is: <em><?= htmlspecialchars(implode(', ', $unmatched_glossary_tokens)) ?></em></small>
                                    <?php endif; ?>

                                    <!-- Add New Glossary Word Modal (reuses SongController/ajax_create_glossary_word) -->
                                    <style>
                                        /* Override the page-wide flex .form-group rule inside this modal so labels,
                                           inputs and textareas stack normally and remain editable. */
                                        #addGlossaryWordModal .form-group { display: block !important; align-items: initial !important; }
                                        #addGlossaryWordModal .form-group > label { flex: none !important; max-width: none !important; width: auto !important; display: block !important; padding-right: 0 !important; margin-bottom: 6px !important; }
                                        #addGlossaryWordModal .form-group > *:not(label) { flex: none !important; width: 100% !important; }
                                        #addGlossaryWordModal .form-control { display: block; width: 100%; }
                                        #addGlossaryWordModal { z-index: 100050; }
                                        .modal-backdrop.show { z-index: 100040; }
                                    </style>
                                    <div class="modal fade" id="addGlossaryWordModal" tabindex="-1" role="dialog" aria-labelledby="addGlossaryWordModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addGlossaryWordModalLabel">Add New Glossary Word</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group" style="display:block;">
                                                        <label>Original</label>
                                                        <input type="text" class="form-control" id="newGlossaryOriginal" placeholder="Enter Original">
                                                    </div>
                                                    <div class="form-group" style="display:block;">
                                                        <label>Translation</label>
                                                        <input type="text" class="form-control" id="newGlossaryTranslation" placeholder="Enter Translation">
                                                    </div>
                                                    <div class="form-group" style="display:block;">
                                                        <label>Transliteration</label>
                                                        <input type="text" class="form-control" id="newGlossaryTransliteration" placeholder="Enter Transliteration">
                                                    </div>
                                                    <div class="form-group" style="display:block;">
                                                        <label>Word Meaning</label>
                                                        <textarea class="form-control" id="newGlossaryMeaning" rows="3" placeholder="Enter word meaning"></textarea>
                                                    </div>
                                                    <div class="form-group" style="display:block; margin-top:8px;">
                                                        <label style="display:flex; align-items:center; gap:8px; margin-bottom:0;">
                                                            <input type="checkbox" id="newGlossaryIsGlossary" value="1" style="width:auto; margin:0;">
                                                            <span>Add to Full Glossary</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-primary" id="saveGlossaryWordBtn">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                    (function () {
                                        function showModal(modal) {
                                            try {
                                                if (window.bootstrap && bootstrap.Modal) { bootstrap.Modal.getOrCreateInstance(modal).show(); return; }
                                                if (window.jQuery && $.fn && $.fn.modal) { $(modal).modal('show'); return; }
                                            } catch (e) {}
                                            modal.classList.add('show');
                                            modal.style.display = 'block';
                                            document.body.classList.add('modal-open');
                                            if (!document.getElementById('gw-modal-backdrop')) {
                                                var bd = document.createElement('div');
                                                bd.id = 'gw-modal-backdrop';
                                                bd.className = 'modal-backdrop fade show';
                                                document.body.appendChild(bd);
                                            }
                                        }
                                        function hideModal(modal) {
                                            try {
                                                if (window.bootstrap && bootstrap.Modal) {
                                                    var inst = bootstrap.Modal.getInstance(modal);
                                                    if (inst) { inst.hide(); return; }
                                                }
                                                if (window.jQuery && $.fn && $.fn.modal) { $(modal).modal('hide'); return; }
                                            } catch (e) {}
                                            modal.classList.remove('show');
                                            modal.style.display = 'none';
                                            document.body.classList.remove('modal-open');
                                            var bd = document.getElementById('gw-modal-backdrop');
                                            if (bd) bd.remove();
                                        }
                                        function init() {
                                            var btn = document.getElementById('addGlossaryWordBtn');
                                            var modal = document.getElementById('addGlossaryWordModal');
                                            var saveBtn = document.getElementById('saveGlossaryWordBtn');
                                            var sel = document.getElementById('coupletglossary');
                                            if (!btn || !modal || !saveBtn || !sel) { setTimeout(init, 200); return; }
                                            modal.querySelectorAll('[data-dismiss="modal"], .close, .btn-secondary').forEach(function (b) {
                                                b.addEventListener('click', function (e) { e.preventDefault(); hideModal(modal); });
                                            });
                                            btn.onclick = function () {
                                                ['newGlossaryOriginal','newGlossaryTranslation','newGlossaryTransliteration','newGlossaryMeaning'].forEach(function (id) {
                                                    var f = document.getElementById(id); if (f) f.value = '';
                                                });
                                                var cb = document.getElementById('newGlossaryIsGlossary'); if (cb) cb.checked = false;
                                                showModal(modal);
                                                setTimeout(function () { var f = document.getElementById('newGlossaryTransliteration'); if (f) f.focus(); }, 200);
                                            };
                                            saveBtn.onclick = async function () {
                                                var orig     = (document.getElementById('newGlossaryOriginal').value || '').trim();
                                                var trans    = (document.getElementById('newGlossaryTranslation').value || '').trim();
                                                var translit = (document.getElementById('newGlossaryTransliteration').value || '').trim();
                                                var meaning  = (document.getElementById('newGlossaryMeaning').value || '').trim();
                                                var isGlossary = !!(document.getElementById('newGlossaryIsGlossary') && document.getElementById('newGlossaryIsGlossary').checked);
                                                if (!translit) { alert('Transliteration is required'); return; }
                                                saveBtn.disabled = true;
                                                try {
                                                    var fd = new URLSearchParams();
                                                    fd.append('word_original', orig);
                                                    fd.append('word_translation', trans);
                                                    fd.append('word_transliteration', translit);
                                                    fd.append('glossary_meaning', meaning);
                                                    fd.append('is_glossary_word', isGlossary ? '1' : '0');
                                                    // Reuse the existing Song endpoint - it just inserts into `word`.
                                                    var res = await fetch('<?= base_url('SongController/ajax_create_glossary_word') ?>', {
                                                        method: 'POST',
                                                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                        body: fd.toString()
                                                    });
                                                    var data = await res.json();
                                                    if (data && data.status === 'success' && data.id) {
                                                        var opt = document.createElement('option');
                                                        opt.value = data.id;
                                                        opt.text = data.label || translit;
                                                        opt.selected = true;
                                                        sel.add(opt);
                                                        if (window.__adminRefreshSelect) {
                                                            window.__adminRefreshSelect('#coupletglossary', String(data.id));
                                                        } else if (window.jQuery) {
                                                            $('#coupletglossary').trigger('change');
                                                        }
                                                        hideModal(modal);
                                                        if (window.Swal) Swal.fire({ icon:'success', title:'Glossary word added!', timer:1200, showConfirmButton:false });
                                                    } else {
                                                        alert('Failed: ' + (data && data.message ? data.message : 'Unknown error'));
                                                    }
                                                } catch (e) {
                                                    alert('Error: ' + e.message);
                                                } finally {
                                                    saveBtn.disabled = false;
                                                }
                                            };
                                        }
                                        if (document.readyState === 'loading') {
                                            document.addEventListener('DOMContentLoaded', init);
                                        } else {
                                            init();
                                        }

                                        // Wire the "Edit" button to the admin-wide helper (same as Song page).
                                        // Bind directly on the button (no DOMContentLoaded wrap) so it works whether
                                        // the page is still loading or already loaded. The helper itself lives in
                                        // footer.php which loads after this script, so we look it up at *click* time.
                                        function wireGlossaryEdit() {
                                            var editBtn = document.getElementById('editGlossaryWordBtn');
                                            if (!editBtn) { setTimeout(wireGlossaryEdit, 200); return; }
                                            if (editBtn.dataset.gwEditBound === '1') return;
                                            editBtn.dataset.gwEditBound = '1';
                                            editBtn.addEventListener('click', function (e) {
                                                // Stop the click from bubbling to ancestors — otherwise the same
                                                // event reaches the body/backdrop and Bootstrap closes the modal
                                                // we just opened ("opens and instantly closes" symptom).
                                                e.preventDefault();
                                                e.stopPropagation();
                                                if (typeof window.__adminEditOption !== 'function') {
                                                    alert('Edit helper not loaded yet. Please try again in a moment.');
                                                    return;
                                                }
                                                // Defer opening to the next tick so the originating click has fully
                                                // finished propagating before Bootstrap registers its outside-click
                                                // listeners on the freshly shown modal.
                                                setTimeout(function () {
                                                    window.__adminEditOption({
                                                        selectId: '#coupletglossary',
                                                        modalId: '#addGlossaryWordModal',
                                                        addSaveBtnId: '#saveGlossaryWordBtn',
                                                        updateUrl:  '<?= base_url('SongController/ajax_update_glossary_word') ?>',
                                                        prefillUrl: '<?= base_url('song/ajax_get_glossary_word') ?>',
                                                        editTitle: 'Edit Glossary Word',
                                                        fields: [
                                                            { inputId: '#newGlossaryOriginal',        postKey: 'word_original' },
                                                            { inputId: '#newGlossaryTranslation',     postKey: 'word_translation' },
                                                            { inputId: '#newGlossaryTransliteration', postKey: 'word_transliteration', primary: true },
                                                            { inputId: '#newGlossaryMeaning',         postKey: 'glossary_meaning' }
                                                        ]
                                                    });
                                                }, 0);
                                            });
                                        }
                                        wireGlossaryEdit();
                                    })();
                                    </script>
                                </div>
                            </div>
                        </div>

                        <!-- Audio File Upload -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Audio File Upload</label>
                                    <?php if (!empty($audio_file_url)): ?>
                                        <div class="mb-2">
                                            <audio controls style="max-width:100%;">
                                                <source src="<?= htmlspecialchars($audio_file_url) ?>">
                                                Your browser does not support the audio element.
                                            </audio>
                                            <div class="text-muted small">Current: <a href="<?= htmlspecialchars($audio_file_url) ?>" target="_blank"><?= htmlspecialchars(basename($audio_file_url)) ?></a></div>
                                        </div>
                                    <?php endif; ?>
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
                        // Preview: try local base first (works on dev), fall back to absolute URL if path already absolute
                        $thumbnailPreviewSrc = '';
                        if ($existingThumbnailUrl !== '') {
                            if (preg_match('#^https?://#i', $existingThumbnailUrl)) {
                                $thumbnailPreviewSrc = $existingThumbnailUrl;
                            } else {
                                $thumbnailPreviewSrc = base_url(ltrim($existingThumbnailUrl, '/'));
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
                                    $keyword_rows = $this->db->table_exists('word')
                                        ? $this->db->query("SELECT id, word_transliteration FROM word ORDER BY LOWER(TRIM(COALESCE(word_transliteration,''))) ASC, id ASC")->result()
                                        : [];
                                    
                                    ?>
                                    <label>⊙ Keywords</label>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="relatedkeywords[]" id="relatedkeywords" data-placeholder="Select keywords">
                                        <?php foreach ($keyword_rows as $keyword) :
                                            $keywordId = (string)$keyword->id;
                                            $isKeywordSelected = in_array($keywordId, $selected_related_keywords, true);
                                        ?>
                                            <option value="<?= $keyword->id ?>" <?= $isKeywordSelected ? 'selected' : '' ?>><?= htmlspecialchars($keyword->word_transliteration) ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-sm btn-success ml-2" id="addNewKeywordBtn" style="white-space:nowrap;">Add New</button>
                                        <button type="button" class="btn btn-sm btn-primary ml-1" id="editKeywordBtn" style="white-space:nowrap;">Edit</button>
                                        <button type="button" class="btn btn-sm btn-danger ml-1" id="deleteKeywordBtn" style="white-space:nowrap;">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <?php
                                    if ($this->db->table_exists('songs')) {
                                        $song_rows = $this->db->query("SELECT id, umbrellaTitle FROM songs ORDER BY LOWER(TRIM(umbrellaTitle)) ASC, id ASC")->result();
                                    } else {
                                        $song_rows = $this->db->query("
                                            SELECT s.id,
                                                COALESCE(NULLIF(TRIM(t.english_transliteration), ''), NULLIF(TRIM(t.original_title), ''), CONCAT('Song #', s.id)) AS umbrellaTitle
                                            FROM song s
                                            LEFT JOIN title t ON t.id = s.song_title_id
                                            ORDER BY LOWER(TRIM(COALESCE(t.english_transliteration, t.original_title, ''))) ASC, s.id ASC
                                        ")->result();
                                    }
                                    
                                    ?>
                                    <label>⊙ Songs</label>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="related_songs[]" id="related_songs" data-placeholder="Select songs">
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
                                    $reflection_rows = $this->db->query("SELECT id, title FROM reflection ORDER BY LOWER(TRIM(COALESCE(title, ''))) ASC, id ASC")->result();
                                    ?>
                                    <label>⊙ Reflections</label>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="reflections[]" id="reflections" data-placeholder="Select reflections">
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
                                    <?php
                                    $poem_rows = $this->db->query("
                                        SELECT id, COALESCE(NULLIF(TRIM(couplet_transliteration), ''), NULLIF(TRIM(original_title), ''), CONCAT('Poem #', id)) AS poem_label
                                        FROM couplet
                                        ORDER BY LOWER(TRIM(COALESCE(NULLIF(couplet_transliteration, ''), NULLIF(original_title, ''), ''))) ASC, id ASC
                                    ")->result();
                                    ?>
                                    <label>⊙ Poems</label>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="relatedpoems[]" id="relatedpoems" data-placeholder="Select poems">
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
                                    <?php
                                    // Same list + order as Poet / Attributed Poet (all people, A–Z).
                                    $person_rows = $all_people_rows;
                                    ?>
                                    <label>⊙ People</label>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="related_people[]" id="related_people" data-placeholder="Select people">
                                        <?php foreach ($person_rows as $person_row) :
                                            $personId = (string)$person_row->id;
                                            $isPersonSelected = in_array($personId, $selected_related_people, true);
                                            $pParts = [];
                                            if (!empty(trim($person_row->first_name))) { $pParts[] = trim($person_row->first_name); }
                                            if (!empty(trim($person_row->middle_name))) { $pParts[] = trim($person_row->middle_name); }
                                            if (!empty(trim($person_row->last_name))) { $pParts[] = trim($person_row->last_name); }
                                            $personLabel = $pParts !== [] ? implode(' ', $pParts) : 'Unnamed';
                                        ?>
                                            <option value="<?= $person_row->id ?>" <?= $isPersonSelected ? 'selected' : '' ?>><?= htmlspecialchars($personLabel) ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <?php $film_rows = $this->db->query("
                                        SELECT id, COALESCE(NULLIF(english_transliteration, ''), NULLIF(english_translation, ''), original_title) AS film_label FROM film
                                        ORDER BY LOWER(TRIM(COALESCE(NULLIF(english_transliteration, ''), NULLIF(english_translation, ''), original_title, ''))) ASC, id ASC
                                    ")->result();
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
                                    <?php $episode_rows = $this->db->query("
                                        SELECT id, COALESCE(NULLIF(english_transliteration, ''), NULLIF(english_translation, ''), original_title) AS episode_label FROM film_episode
                                        ORDER BY LOWER(TRIM(COALESCE(NULLIF(english_transliteration, ''), NULLIF(english_translation, ''), original_title, ''))) ASC, id ASC
                                    ")->result();
                                     ?>
                                    <label>⊙ Film Episodes</label>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="film_episodes[]" id="film_episodes" data-placeholder="Select episodes">
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
                                                <label>Original</label>
                                                <input type="text" id="newKeywordOriginal" class="form-control" placeholder="Enter Original Keyword">
                                            </div>
                                            <div class="form-group">
                                                <label>Translation</label>
                                                <input type="text" id="newKeywordTranslation" class="form-control" placeholder="Enter Keyword Translation">
                                            </div>
                                            <div class="form-group">
                                                <label>Transliteration</label>
                                                <input type="text" id="newKeywordTransliteration" class="form-control" placeholder="Enter Keyword Transliteration">
                                            </div>
                                            <div class="form-group">
                                                <label>Word Meaning</label>
                                                <textarea id="newKeywordMeaning" class="form-control" rows="3" placeholder="Enter word meaning"></textarea>
                                            </div>
                                        </div>
                                        <style>
                                            #addNewKeywordModal .form-group { display:block !important; align-items:initial !important; }
                                            #addNewKeywordModal .form-group > label { display:block !important; flex:none !important; max-width:none !important; width:auto !important; margin-bottom:6px !important; padding-right:0 !important; }
                                            #addNewKeywordModal .form-group > *:not(label) { width:100% !important; flex:none !important; }
                                        </style>
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
                                    function __coupletKwRead() {
                                        return {
                                            word_original:        ((document.getElementById('newKeywordOriginal')        || {}).value || '').trim(),
                                            word_translation:     ((document.getElementById('newKeywordTranslation')     || {}).value || '').trim(),
                                            word_transliteration: ((document.getElementById('newKeywordTransliteration') || {}).value || '').trim(),
                                            glossary_meaning:     ((document.getElementById('newKeywordMeaning')         || {}).value || '').trim()
                                        };
                                    }
                                    function __coupletKwClear() {
                                        ['newKeywordOriginal','newKeywordTranslation','newKeywordTransliteration','newKeywordMeaning'].forEach(function (id) {
                                            var el = document.getElementById(id); if (el) el.value = '';
                                        });
                                    }
                                    if (addNewKeywordBtn && addNewKeywordModal && addKeywordConfirm && newKeywordTransliteration && relatedkeywordsSelect) {
                                        addNewKeywordBtn.onclick = function() {
                                            __coupletKwClear();
                                            addNewKeywordModal.classList.add('show');
                                            setTimeout(function() { newKeywordTransliteration.focus(); }, 300);
                                        };
                                        document.getElementById('closeAddNewKeyword').onclick = function() { addNewKeywordModal.classList.remove('show'); };
                                        document.getElementById('cancelAddNewKeyword').onclick = function() { addNewKeywordModal.classList.remove('show'); };
                                        addKeywordConfirm.onclick = async function() {
                                            var fields = __coupletKwRead();
                                            if (!fields.word_transliteration) {
                                                alert('Transliteration is required!');
                                                return;
                                            }
                                            addKeywordConfirm.disabled = true;
                                            try {
                                                var body = new URLSearchParams();
                                                Object.keys(fields).forEach(function (k) { body.append(k, fields[k]); });
                                                var res = await fetch('<?= base_url('SongController/ajax_create_keyword') ?>', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                    body: body.toString()
                                                });
                                                var data = await res.json();
                                                if (data && (data.status === 'success' || data.success)) {
                                                    var newId = data.keyword_id || data.id;
                                                    var existingOpt = Array.from(relatedkeywordsSelect.options).find(function (o) { return String(o.value) === String(newId); });
                                                    if (existingOpt) {
                                                        existingOpt.text = data.word_transliteration || fields.word_transliteration;
                                                        existingOpt.selected = true;
                                                    } else {
                                                        var option = document.createElement('option');
                                                        option.value = newId;
                                                        option.text = data.word_transliteration || fields.word_transliteration;
                                                        option.selected = true;
                                                        relatedkeywordsSelect.add(option);
                                                    }
                                                    if (window.__adminRefreshSelect) {
                                                        window.__adminRefreshSelect('#relatedkeywords', String(newId));
                                                    } else if (window.jQuery && $('#relatedkeywords').length) {
                                                        $('#relatedkeywords').trigger('change');
                                                    }
                                                    addNewKeywordModal.classList.remove('show');
                                                    __coupletKwClear();
                                                    if (window.Swal) Swal.fire({icon:'success',title:'Success',text:data.message || 'Keyword saved!',timer:1200,showConfirmButton:false});
                                                } else {
                                                    alert((data && data.message) ? data.message : 'Failed to save keyword!');
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
                                <?php
                                    // Normalize is_published — DB may store 1 / '1' / true / 'true' / 'yes'
                                    $rawCpPublish = isset($couplet['is_published']) ? $couplet['is_published'] : '';
                                    $cpPublishVal = 'false';
                                    if ($rawCpPublish === 1 || $rawCpPublish === '1' || $rawCpPublish === true ||
                                        (is_string($rawCpPublish) && in_array(strtolower($rawCpPublish), ['true','yes','y','1'], true))) {
                                        $cpPublishVal = 'true';
                                    }
                                ?>
                                <select name="is_published" class="form-control col-md-4">
                                    <option value="false" <?= $cpPublishVal === 'false' ? 'selected' : ''; ?>>No</option>
                                    <option value="true"  <?= $cpPublishVal === 'true'  ? 'selected' : ''; ?>>Yes</option>
                                </select>
                            </div>
                            </div>
                        </div>

                        <div class="save-btn-container">
                            <?= admin_edit_preview_button(isset($couplet) ? $couplet : null) ?>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>

                    <script>
                        jQuery(function ($) {
                        $(window).on('load', function () {
                          // Helper to apply value + refresh Bootstrap Multiselect or Select2
                          function applyEditSelect(sel, vals) {
                            var $s = $(sel);
                            if (!$s.length || !vals || !vals.length) return;
                            $s.val(vals.map(String));
                            if ($s.data('bs.multiselect') && $.fn.multiselect) {
                              $s.multiselect('refresh');
                            } else {
                              $s.trigger('change');
                            }
                          }
                          // Run after Bootstrap Multiselect has had time to attach (footer init runs on $(function))
                          setTimeout(function () {
                        <?php if(isset($couplet)) { ?>
                            // Set values for edit (after footer loads Select2 / AdminLTE)
                            <?php
                            $edit_poet_ids = isset($couplet['poet_id']) ? couplet_parse_id_list($couplet['poet_id']) : [];
                            if (!empty($edit_poet_ids)) :
                            ?>
                                applyEditSelect('#poet', <?php echo json_encode($edit_poet_ids); ?>);
                            <?php endif; ?>
                            <?php
                            $edit_attributed_poet_ids = isset($couplet['attributed_poet']) ? couplet_parse_id_list($couplet['attributed_poet']) : [];
                            if (!empty($edit_attributed_poet_ids)) :
                            ?>
                                applyEditSelect('#attributed_poet', <?php echo json_encode($edit_attributed_poet_ids); ?>);
                            <?php endif; ?>
                            <?php
                            $trans = isset($couplet['translator']) ? couplet_parse_id_list($couplet['translator']) : [];
                            if (!empty($trans)) :
                            ?>
                                applyEditSelect('#translator', <?php echo json_encode($trans); ?>);
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
                            <?php
                                // Helper: apply selected values to a select and refresh Bootstrap Multiselect if attached
                                $applyMultiSelect = function ($selector, $rawValue) {
                                    if (empty($rawValue)) return '';
                                    $vals = @unserialize($rawValue);
                                    if (!is_array($vals)) {
                                        $vals = array_values(array_filter(array_map('trim', explode(',', (string)$rawValue))));
                                    }
                                    if (empty($vals)) return '';
                                    return "(function(){ var \$s = $('" . $selector . "'); if(!\$s.length) return; \$s.val(" . json_encode(array_map('strval', $vals)) . "); if(\$s.data('bs.multiselect') && $.fn.multiselect){ \$s.multiselect('refresh'); } else { \$s.trigger('change'); } })();\n";
                                };
                                if (isset($couplet['related_songs'])) echo $applyMultiSelect('select[name="related_songs[]"]', $couplet['related_songs']);
                                if (isset($couplet['related_reflections'])) echo $applyMultiSelect('select[name="related_reflections[]"]', $couplet['related_reflections']);
                                if (isset($couplet['related_poems'])) echo $applyMultiSelect('select[name="related_poems[]"]', $couplet['related_poems']);
                                if (isset($couplet['related_people'])) echo $applyMultiSelect('select[name="related_people[]"]', $couplet['related_people']);
                                if (isset($couplet['related_films'])) echo $applyMultiSelect('select[name="related_films[]"]', $couplet['related_films']);
                                if (isset($couplet['related_film_episodes'])) echo $applyMultiSelect('select[name="related_film_episodes[]"]', $couplet['related_film_episodes']);
                                if (isset($couplet['translator'])) echo $applyMultiSelect('select[name="translator[]"]', $couplet['translator']);
                                if (isset($couplet['attributed_poet'])) echo $applyMultiSelect('select[name="attributed_poet[]"]', $couplet['attributed_poet']);
                                // Keywords stored as CSV in `keywords` column → relatedkeywords[] select
                                if (!empty($couplet['keywords'])) {
                                    $kwIds = array_values(array_filter(array_map('trim', explode(',', (string)$couplet['keywords']))));
                                    if (!empty($kwIds)) {
                                        echo "(function(){ var \$s = $('select[name=\"relatedkeywords[]\"]'); if(!\$s.length) return; \$s.val(" . json_encode($kwIds) . "); if(\$s.data('bs.multiselect') && $.fn.multiselect){ \$s.multiselect('refresh'); } else { \$s.trigger('change'); } })();\n";
                                    }
                                }
                                // Poet (single value in poet_id INT) → poet[] select
                                if (!empty($couplet['poet_id'])) {
                                    $pid = (int) $couplet['poet_id'];
                                    if ($pid > 0) {
                                        echo "(function(){ var \$s = $('select[name=\"poet[]\"]'); if(!\$s.length) return; \$s.val(['" . $pid . "']); if(\$s.data('bs.multiselect') && $.fn.multiselect){ \$s.multiselect('refresh'); } else { \$s.trigger('change'); } })();\n";
                                    }
                                }
                            ?>
                        <?php } ?>
                          }, 800); // end setTimeout
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
                        window.coupletInitSelect2 = function ($el) {
                            $el = window.jQuery($el);
                            if (!$el.length || typeof $el.select2 !== 'function') return;
                            var ph = $el.attr('data-placeholder') || 'Search or select…';
                            $el.select2({
                                theme: 'bootstrap4',
                                width: '100%',
                                placeholder: ph,
                                allowClear: true,
                                minimumResultsForSearch: 0,
                                closeOnSelect: false
                            });
                            $el.on('select2:open.coupletph', function () {
                                var searchPh = 'Search…';
                                setTimeout(function () {
                                    var $f = window.jQuery('.select2-container--open .select2-search__field');
                                    if ($f.length) $f.attr('placeholder', searchPh);
                                }, 0);
                            });
                        };
                        // Select2 is finalized after inc/footer.php (AdminLTE also calls $('.select2').select2()).
                        </script>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('coupletForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent form submission

    // Only fields marked with red `*` in the form are required.
    const fields = [
        { id: 'couplet_transliteration', name: 'Poem Title - Transliteration' },
        { id: 'couplet_translation',     name: 'Poem Title - Translation' },
        { id: 'poet',                    name: 'Poet' },
        { id: 'thumbnailUrl',            name: 'Thumbnail Image Upload' }
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
    if (!name) { alert("Please enter a name."); return; }
    // CodeIgniter's $this->input->post() reads x-www-form-urlencoded data, not JSON.
    // Sending JSON would arrive as empty on the server → "Name is required" error.
    const body = new URLSearchParams();
    body.append('name', name);
    body.append('hyperlink', hyperlink);
    body.append('type_id', '2'); // Poet
    fetch('<?php echo base_url("person/ajax-create"); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data && data.success) {
            const select = document.getElementById('poet');
            const option = new Option(data.fullName || name, data.id);
            select.appendChild(option);
            $(select).val([...($(select).val() || []), String(data.id)]).trigger('change');
            if (window.__adminRefreshSelect) window.__adminRefreshSelect('#poet', String(data.id));
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
            alert('Error: ' + ((data && data.message) || 'Failed to add poet'));
        }
    })
    .catch(err => alert('Error: ' + err.message));
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
    if (!name) { alert("Please enter a name."); return; }
    const body = new URLSearchParams();
    body.append('name', name);
    body.append('hyperlink', hyperlink);
    body.append('type_id', '2'); // Poet (Attributed Poet is also a poet entry)
    fetch('<?php echo base_url("person/ajax-create"); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data && data.success) {
            const select = document.getElementById('attributed_poet');
            const option = new Option(data.fullName || name, data.id);
            select.appendChild(option);
            $(select).val([...($(select).val() || []), String(data.id)]).trigger('change');
            if (window.__adminRefreshSelect) window.__adminRefreshSelect('#attributed_poet', String(data.id));
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
            alert('Error: ' + ((data && data.message) || 'Failed to add attributed poet'));
        }
    })
    .catch(err => alert('Error: ' + err.message));
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
        if (!name) { alert("Please enter a name."); return; }
        const body = new URLSearchParams();
        body.append('name', name);
        body.append('hyperlink', hyperlink);
        // Translator has no specific person.type (1=singer, 2=poet) — left out.
        fetch('<?php echo base_url("person/ajax-create"); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString()
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.success) {
                    const select = document.getElementById('translator');
                    const option = new Option(data.fullName || name, data.id);
                    select.appendChild(option);
                    $(select).val([...($(select).val() || []), String(data.id)]).trigger('change');
                    if (window.__adminRefreshSelect) window.__adminRefreshSelect('#translator', String(data.id));
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
                    alert('Error: ' + ((data && data.message) || 'Failed to add translator'));
                }
            })
            .catch(err => alert('Error: ' + err.message));
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

        if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2 && window.coupletInitSelect2) {
            window.coupletInitSelect2(window.jQuery('#' + selectId));
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
<script>
(function () {
  function bindEdit(btnId, opts) {
    var b = document.getElementById(btnId);
    if (!b) return;
    b.addEventListener('click', function () {
      if (window.__adminEditOption) window.__adminEditOption(opts);
      else alert('Edit helper not loaded');
    });
  }
  var BASE = '<?php echo base_url(); ?>';
  bindEdit('editPoetBtn', {
    selectId: '#poet', modalId: '#addPoetModal', addSaveBtnId: '#addPoet',
    updateUrl:  BASE + 'song/ajax_update_person',
    prefillUrl: BASE + 'song/ajax_get_person',
    editTitle: 'Edit Poet',
    extraPayload: { type_id: 2 },
    fields: [
      { inputId: '#addPoetName', postKey: 'name',      primary: true },
      // Modal input is #addPoetUrl (the page renders <input id="addPoetUrl">),
      // NOT #addPoetLink. Using the wrong id silently no-ops the prefill so the
      // Hyperlink field stayed blank on Edit.
      { inputId: '#addPoetUrl',  postKey: 'hyperlink' }
    ]
  });
  bindEdit('editAttributedPoetBtn', {
    selectId: '#attributed_poet', modalId: '#addAttributedPoetModal', addSaveBtnId: '#addAttributedPoet',
    updateUrl:  BASE + 'song/ajax_update_person',
    prefillUrl: BASE + 'song/ajax_get_person',
    editTitle: 'Edit Attributed Poet',
    extraPayload: { type_id: 2 },
    fields: [
      { inputId: '#addAttributedPoetName', postKey: 'name',      primary: true },
      { inputId: '#addAttributedPoetUrl',  postKey: 'hyperlink' }
    ]
  });
  bindEdit('editTranslatorBtn', {
    selectId: '#translator', modalId: '#addTranslatorModal', addSaveBtnId: '#addTranslator',
    updateUrl:  BASE + 'song/ajax_update_translator',
    prefillUrl: BASE + 'song/ajax_get_person',
    editTitle: 'Edit Translator',
    fields: [
      { inputId: '#addTranslatorName', postKey: 'name',      primary: true },
      { inputId: '#addTranslatorUrl',  postKey: 'hyperlink' }
    ]
  });
  bindEdit('editKeywordBtn', {
    selectId: '#relatedkeywords', modalId: '#addNewKeywordModal', addSaveBtnId: '#addKeywordConfirm',
    updateUrl:  BASE + 'song/ajax_update_keyword',
    prefillUrl: BASE + 'song/ajax_get_keyword', // all 4 fields from DB
    editTitle:  'Edit Keyword',
    fields: [
      { inputId: '#newKeywordOriginal',        postKey: 'word_original' },
      { inputId: '#newKeywordTranslation',     postKey: 'word_translation' },
      { inputId: '#newKeywordTransliteration', postKey: 'word_transliteration', primary: true },
      { inputId: '#newKeywordMeaning',         postKey: 'glossary_meaning' }
    ]
  });

  // ----- Delete buttons (helper defined in footer.php which loads after; defer via $(function)). -----
  $(function () {
    if (!window.__bindAdminDelete) return;
    __bindAdminDelete('deletePoetBtn',           { selectId: '#poet',            entity: 'person', label: 'Poet' });
    __bindAdminDelete('deleteAttributedPoetBtn', { selectId: '#attributed_poet', entity: 'person', label: 'Attributed Poet' });
    __bindAdminDelete('deleteTranslatorBtn',     { selectId: '#translator',      entity: 'person', label: 'Translator' });
    __bindAdminDelete('deleteGlossaryWordBtn',   { selectId: '#coupletglossary', entity: 'word',   label: 'Glossary Word' });
    __bindAdminDelete('deleteKeywordBtn',        { selectId: '#relatedkeywords', entity: 'word',   label: 'Keyword' });
  });
})();
</script>
<?php include('inc/footer.php'); ?>
<script>
jQuery(function ($) {
    if (typeof window.coupletInitSelect2 !== 'function') return;
    $('#coupletForm select.select2').each(function () {
        var $el = $(this);
        try {
            if ($el.data('select2')) {
                $el.select2('destroy');
            }
        } catch (e) { /* ignore */ }
        window.coupletInitSelect2($el);
    });
});
</script>
