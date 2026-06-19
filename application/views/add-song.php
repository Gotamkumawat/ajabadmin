<?php
include('inc/header.php');
include('inc/sidebar.php');
?>

<head>
    <link rel="stylesheet" href="/common/lib/angular-multi-select/angular-multi-select.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Multiselect (button + dropdown + Select All + Search UI) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
    <style>
        /* Hide Bootstrap's default dropdown-toggle ::after caret (we render our own <b class="caret">) */
        .multiselect-native-select .dropdown-toggle::after { display: none !important; }
        /* Replicate angular-multi-select UI from ajab_old/common/lib/angular-multi-select/angular-multi-select.css */
        .multiselect-native-select {
            position: relative;
            width: 200px !important;
            max-width: 200px !important;
            display: inline-block;
        }
        .multiselect-native-select .btn-group {
            width: 200px !important;
            max-width: 200px !important;
        }
        .multiselect-native-select .btn.multiselect {
            display: block;
            position: relative;
            text-align: center;
            cursor: pointer;
            border: 1px solid #c6c6c6 !important;
            padding: 6px 24px 6px 10px;
            font-size: 14px;
            min-height: 38px;
            max-height: 220px;
            overflow-y: auto;
            overflow-x: hidden;
            border-radius: 4px;
            color: #555;
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
            background-color: #fff !important;
            background-image: linear-gradient(#fff, #f7f7f7) !important;
            box-shadow: none;
            width: 200px !important;
            max-width: 200px !important;
            box-sizing: border-box;
            line-height: 1.5;
        }
        .multiselect-native-select .btn.multiselect .multiselect-selected-text {
            display: block;
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
            line-height: 1.5;
            width: 100%;
            text-align: center;
        }
        .multiselect-native-select .btn.multiselect .caret {
            position: absolute;
            right: 8px;
            top: 16px;
            display: inline-block;
            width: 0; height: 0;
            border-top: 4px solid #333;
            border-right: 4px solid transparent;
            border-left: 4px solid transparent;
        }
        .multiselect-native-select .btn.multiselect:hover {
            background-image: linear-gradient(#fff, #e9e9e9);
        }
        .multiselect-native-select .btn.multiselect:focus {
            background-image: linear-gradient(#fff, #e9e9e9);
            outline: none;
        }
        .multiselect-container.dropdown-menu {
            background-color: #fff;
            border: 1px solid rgba(0,0,0,0.15);
            border-radius: 4px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.175);
            min-width: 278px;
            padding: 0;
            margin: 2px 0 0;
            max-height: 380px;
            overflow-y: auto;
        }
        /* Hide library's built-in Select All & filter — we render our own */
        .multiselect-container > li.multiselect-item.multiselect-all,
        .multiselect-container > li.multiselect-item.multiselect-filter {
            display: none !important;
        }
        /* Helper container holds Select All/None/Reset + filter — old library: padding 8px 8px 0px 8px, bottom border */
        .ms-helper-container {
            padding: 8px 8px 0 8px !important;
            border-bottom: 1px solid #ddd;
            list-style: none;
            background: #fff;
        }
        .ms-action-row {
            list-style: none;
            margin: 0; padding: 0;
            display: block;
        }
        .ms-action-row .ms-action-btn {
            display: inline-block;
            text-align: center;
            cursor: pointer;
            border: 1px solid #ccc;
            height: 26px;
            font-size: 13px;
            border-radius: 2px;
            color: #666;
            background-color: #f1f1f1;
            line-height: 1.6;
            margin: 0 4px 8px 0;
            padding: 0 10px;
        }
        .ms-action-row .ms-action-btn:hover {
            border: 1px solid #ccc;
            color: #999;
            background-color: #f4f4f4;
        }
        .ms-action-row .ms-action-btn:focus {
            border: 1px solid #66AFE9 !important;
            box-shadow: inset 0 0 1px rgba(0,0,0,.035), 0 0 5px rgba(82,168,236,.7);
            outline: none;
        }
        .ms-search-input {
            border-radius: 2px;
            border: 1px solid #ccc;
            height: 26px;
            font-size: 14px;
            width: 100%;
            padding-left: 7px;
            box-sizing: border-box;
            color: #888;
            margin: 0 0 8px 0;
        }
        .ms-search-input:focus {
            border: 1px solid #66AFE9 !important;
            box-shadow: inset 0 0 1px rgba(0,0,0,.035), 0 0 5px rgba(82,168,236,.7);
            outline: none;
        }
        /* Item area — checkBoxContainer in old: padding 8px */
        .multiselect-container > li:not(.ms-helper-container):not(.ms-action-row):not(.ms-search-row) {
            margin: 0 8px;
        }
        .multiselect-container li > a {
            padding: 0;
            background: transparent;
        }
        .multiselect-container li > a > label.checkbox {
            display: block;
            padding: 3px;
            color: #444;
            white-space: nowrap;
            border: 1px solid transparent;
            position: relative;
            margin: 0;
            cursor: pointer;
            font-weight: normal;
        }
        /* Hide native checkbox visually but keep functional */
        .multiselect-container li > a > label.checkbox > input[type=checkbox] {
            position: absolute;
            left: -9999px;
            margin: 0;
        }
        /* Selected item: light gradient */
        .multiselect-container li.active > a > label.checkbox,
        .multiselect-container li > a > label.checkbox.checked {
            background-image: linear-gradient(#e9e9e9, #f1f1f1);
            color: #555;
            border-top: 1px solid #e4e4e4;
            border-left: 1px solid #e4e4e4;
            border-right: 1px solid #d9d9d9;
        }
        /* Add tick mark on selected items via ::after */
        .multiselect-container li.active > a > label.checkbox::after {
            content: "\2714";
            display: inline-block;
            position: absolute;
            right: 10px;
            top: 4px;
            font-size: 10px;
            color: #555;
        }
        /* Hover: dark gradient with white text */
        .multiselect-container li:hover > a > label.checkbox {
            background-image: linear-gradient(#c1c1c1, #999) !important;
            color: #fff !important;
            border: 1px solid #ccc !important;
        }
        .multiselect-container li:hover.active > a > label.checkbox::after {
            color: #fff;
        }
        .multiselect-container .ms-helper-container:hover {
            background: #fff !important;
        }
    </style>
</head>

<style>
    /* --- Improved Related Content Fields CSS --- */
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
    /* --- End Improved Related Content Fields CSS --- */
    .select2-container--default .select2-selection--multiple {
        min-height: 38px;
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 4px 6px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        overflow: auto;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border: none;
        color: white;
        padding: 2px 6px;
        margin: 2px;
        border-radius: 4px;
        font-size: 12px;
        max-width: 100%;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        margin-right: 4px;
    }

    .multi-select-container {
        max-width: 100%;
    }

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

    /* Bootstrap-multiselect old conflicting rules removed — new old-style rules are in <head> <style> block above */

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
        min-height: 38px;
        padding: 2px 6px;
        display: flex !important;
        flex-wrap: wrap !important;
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


    /* Modal Box */
    .modal-content {
      background-color: #fff;
      margin: 0 auto;
      padding: 0;
      border-radius: 12px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.25);
      animation: fadeIn 0.25s cubic-bezier(.4,0,.2,1);
      border: 1px solid #e3e3e3;
      overflow: hidden;
    }
    .modal-header, .modal-footer {
      padding: 16px 20px;
    }
    .modal-body {
      padding: 18px 20px 10px 20px;
    }
    .modal-header {
      border-bottom: 1px solid #f0f0f0;
      background: #f8f9fa;
    }
    .modal-footer {
      border-top: 1px solid #f0f0f0;
      background: #f8f9fa;
    }
    .modal-header h2 {
      font-size: 18px;
      font-weight: 600;
      margin: 0;
    }
    .close-btn {
      background: none;
      border: none;
      font-size: 22px;
      cursor: pointer;
      color: #888;
      margin-left: 8px;
    }

    /* Header */
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-bottom: 10px;
      border-bottom: 2px solid #eee;
    }

    .modal-header h2 {
      margin: 0;
      font-size: 20px;
    }

    .close-btn {
      background: none;
      border: none;
      font-size: 22px;
      cursor: pointer;
    }

    /* Footer */
    .modal-footer {
      text-align: right;
      margin-top: 15px;
    }

    .modal-footer button {
      padding: 8px 16px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-left: 8px;
    }

    .btn-info { background: #17a2b8; color: white; }
    .btn-success { background: #28a745; color: white; }
    .btn-danger { background: #dc3545; color: white; }
    .btn-secondary { background: #6c757d; color: white; }

    /* Input Fields */
    .form-group {
      margin-bottom: 12px;
    }

    .form-group label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
    }

    .form-group input {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    /* Animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }

    /* Singer/Poet/Translator dialogs — NOT Bootstrap .modal (avoids conflict + broken close) */
    .song-person-dialog {
      display: none;
      position: fixed;
      z-index: 100050;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background: rgba(0, 0, 0, 0.45);
      align-items: center;
      justify-content: center;
    }
    .song-person-dialog .modal-content {
      margin: 8vh auto;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Enter Song Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="add_new">Home</a></li>
                        <li class="breadcrumb-item active">Add Song Details</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <?php
    $song_transliteration = isset($song['Songtitle_transliteration']) ? $song['Songtitle_transliteration'] : '';
    $song_translated = isset($song['songtitletraan']) ? $song['songtitletraan'] : '';
    $selected_reflections = (isset($song['reflections']) && trim((string) $song['reflections']) !== '')
        ? array_values(array_filter(array_map('trim', explode(',', $song['reflections'])))) : [];
    $poems_src = '';
    if (isset($song['relatedpoems']) && trim((string) $song['relatedpoems']) !== '') {
        $poems_src = $song['relatedpoems'];
    } elseif (isset($song['couplets']) && trim((string) $song['couplets']) !== '') {
        $poems_src = $song['couplets'];
    }
    $selected_couplets = ($poems_src !== '') ? array_values(array_filter(array_map('trim', explode(',', $poems_src)))) : [];
    $selected_films = (isset($song['films']) && trim((string) $song['films']) !== '')
        ? array_values(array_filter(array_map('trim', explode(',', $song['films'])))) : [];
    $selected_episodes = (isset($song['film_episodes']) && trim((string) $song['film_episodes']) !== '')
        ? array_values(array_filter(array_map('trim', explode(',', $song['film_episodes'])))) : [];
    $selected_people = (isset($song['related_people']) && trim((string) $song['related_people']) !== '')
        ? array_values(array_filter(array_map('trim', explode(',', $song['related_people'])))) : [];
    $selected_keywords = (isset($song['relatedkeywords']) && trim((string) $song['relatedkeywords']) !== '')
        ? array_values(array_filter(array_map('trim', explode(',', $song['relatedkeywords'])))) : [];
    $selected_related_songs = (isset($song['related_songs']) && trim((string) $song['related_songs']) !== '')
        ? array_values(array_filter(array_map('trim', explode(',', $song['related_songs'])))) : [];
    $show_on_landing = isset($song['showOnLandingPage']) ? $song['showOnLandingPage'] : '';
    // Normalize publish — DB may store 1 / '1' / true / 'true' / 'yes' (or 0 / '0' / false / 'false' / 'no').
    // Fallback to legacy `is_authoring_complete` column if `publish` is empty (mirrors list view).
    $rawPublish = isset($song['publish']) && $song['publish'] !== '' && $song['publish'] !== null
        ? $song['publish']
        : (isset($song['is_authoring_complete']) ? $song['is_authoring_complete'] : '');
    $select_publish = '';
    if ($rawPublish !== '' && $rawPublish !== null) {
        if ($rawPublish === 1 || $rawPublish === '1' || $rawPublish === true ||
            (is_string($rawPublish) && in_array(strtolower($rawPublish), ['true','yes','y','1'], true))) {
            $select_publish = 'true';
        } else {
            $select_publish = 'false';
        }
    }
    $songLyricsOriginal = isset($song['songLyricsOriginal']) ? $song['songLyricsOriginal'] : '';
    // Transliteration column is DB `songLyricsNotes`; Translation column is DB `songLyricsTranslated`
    $songLyricsTransliteration = isset($song['songLyricsNotes']) ? $song['songLyricsNotes'] : '';
    $songLyricsTranslation = isset($song['songLyricsTranslated']) ? $song['songLyricsTranslated'] : '';
    $songAbout = '';
    if (isset($song) && is_array($song)) {
        if (isset($song['about']) && trim((string) $song['about']) !== '') {
            $songAbout = $song['about'];
        } elseif (!empty($song['songLyricsMeaning'])) {
            $songAbout = $song['songLyricsMeaning'];
        }
    }
    $metaDescription = isset($song['metaDescription']) ? $song['metaDescription'] : '';
    $selected_ref_check = isset($song['reflection']) ? trim($song['reflection']) : '';
    $selected_singers = [];
    $selected_poet = [];
    $selected_translator = [];
    if (isset($song) && is_array($song)) {
        if (isset($song['singer']) && trim((string) $song['singer']) !== '') {
            $selected_singers = array_values(array_filter(array_map('trim', explode(',', $song['singer']))));
        }
        if (isset($song['poet']) && trim((string) $song['poet']) !== '') {
            $selected_poet = array_values(array_filter(array_map('trim', explode(',', $song['poet']))));
        }
        if (isset($song['translator']) && trim((string) $song['translator']) !== '') {
            $selected_translator = array_values(array_filter(array_map('trim', explode(',', $song['translator']))));
        }
    }
    ?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
                <div class="card-header" style="padding: 4px 8px; margin: 0;">
                    <div>
                        <a href="javascript:void(0);" 
                            class="btn btn-secondary" 
                            style="padding: 3px 8px; font-Transliteration size: 13px; border-radius: 4px;"
                            onclick="window.history.back();">
                            <i class="fas fa-arrow-left" style="font-size: 13px; margin-right: 4px;"></i> Back
                            </a>
                    </div>
                </div>

                  <!-- /.card-header -->
                <div class="card-body">
                    <?php
                    $song_form_action = base_url('song/save');
                    if (isset($song) && is_array($song) && !empty($song['id'])) {
                        $song_form_action = base_url('song/update');
                    }
                    ?>
                    <form id="songForm" name="songForm" method="post" action="<?= htmlspecialchars($song_form_action) ?>" enctype="multipart/form-data">
                        <?php if (!empty($song['id'])): ?>
                            <input type="hidden" name="id" value="<?= $song['id'] ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group" style="display: flex; align-items: center; margin-bottom: 18px;">
                                    <label style="flex: 0 0 220px; padding-right: 18px;">Umbrella Title</label>
                                    
                                        <?php
                                        // Convert comma-separated string to array
                                        $selected_umbrellaTitle = [];
                                        if (isset($song['umbrellaTitle']) && $song['umbrellaTitle'] !== '') {
                                            $selected_umbrellaTitle = explode(',', (string)$song['umbrellaTitle']);
                                        } elseif (isset($song['umbrella_title_id']) && $song['umbrella_title_id'] !== '') {
                                            $selected_umbrellaTitle = [(string)$song['umbrella_title_id']];
                                        }

                                        // ============================================================
                                        // Group titles by name so the same Umbrella Title doesn't appear
                                        // multiple times. DB has many rows with identical
                                        // english_transliteration/original_title (one per language/version);
                                        // we collapse them into a single option using the MIN(id) as the
                                        // canonical value, and keep a sibling-ID map so a song already
                                        // linked to any of the merged ids still shows up as selected.
                                        // Grouping key = lowercased trimmed transliteration (falls back to original).
                                        // ============================================================
                                        $umbrella_raw_rows = $this->db->query(
                                            "SELECT id, english_transliteration, original_title
                                             FROM title
                                             ORDER BY english_transliteration ASC, original_title ASC, id ASC"
                                        )->result();

                                        $umbrella_rows  = [];   // de-duplicated rows for the <select>
                                        $umbrella_alias = [];   // sibling-id => canonical-id  (for preselect mapping)
                                        $umbrella_seen  = [];   // group-key  => canonical-id

                                        foreach ($umbrella_raw_rows as $row) {
                                            $label = trim((string) $row->english_transliteration);
                                            if ($label === '') { $label = trim((string) $row->original_title); }
                                            if ($label === '') { continue; } // skip totally empty rows
                                            $key = mb_strtolower($label);
                                            $rid = (string) $row->id;
                                            if (!isset($umbrella_seen[$key])) {
                                                $umbrella_seen[$key] = $rid;     // canonical id = first (smallest) id for this name
                                                $umbrella_rows[] = (object) [
                                                    'id'    => $rid,
                                                    'label' => $label,
                                                ];
                                            }
                                            // Always record the alias mapping (canonical maps to itself too).
                                            $umbrella_alias[$rid] = $umbrella_seen[$key];
                                        }

                                        // Map any selected id to its canonical id so the option shows up selected
                                        // even if the song row stores a non-canonical (sibling) id.
                                        $selected_umbrellaTitle_canon = [];
                                        foreach ($selected_umbrellaTitle as $sid) {
                                            $sid = (string) $sid;
                                            if ($sid === '') { continue; }
                                            $selected_umbrellaTitle_canon[] = isset($umbrella_alias[$sid]) ? $umbrella_alias[$sid] : $sid;
                                        }
                                        $selected_umbrellaTitle_canon = array_values(array_unique($selected_umbrellaTitle_canon));
                                        ?>
                                    <div class="d-flex align-items-center" style="gap: 8px; flex: 0 0 auto;">
                                        <select class="form-control select2" multiple="multiple" data-skip-select2="true" id="umbrellaTitle" name="umbrellaTitle[]">
                                            <option value="">Select Umbrella Title</option>
                                            <?php foreach ($umbrella_rows as $row): ?>
                                                <option value="<?= htmlspecialchars($row->id) ?>" <?= in_array($row->id, $selected_umbrellaTitle_canon, true) ? 'selected' : '' ?>><?= htmlspecialchars($row->label) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-info btn-sm" id="editBtn">Edit</button>
                                        <button type="button" class="btn btn-success btn-sm" id="addBtn">Add New</button>
                                        <button type="button" class="btn btn-danger btn-sm" id="deleteBtn">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="umbrellaTitleEditPanel" class="card card-outline card-secondary mt-2" style="display:none;">
                            <div class="card-body py-2">
                                <input type="hidden" id="umbrellaEditId" value="">
                                <div class="form-row mb-2">
                                    <label class="col-md-2 col-form-label">Original</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="umbrellaEditOriginal" placeholder="Original title">
                                    </div>
                                </div>
                                <div class="form-row mb-2">
                                    <label class="col-md-2 col-form-label">Transliteration</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="umbrellaEditTranslit" placeholder="Transliteration">
                                    </div>
                                </div>
                                <div class="form-row mb-2">
                                    <label class="col-md-2 col-form-label">Translation</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="umbrellaEditTrans" placeholder="Translation">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm" id="umbrellaEditSaveBtn">Save</button>
                                <button type="button" class="btn btn-secondary btn-sm" id="umbrellaEditCancelBtn">Cancel</button>
                            </div>
                        </div>


                                                <!-- Add Umbrella Title Modal (Bootstrap) -->
                                                <div class="modal fade" id="addUmbrellaModal" tabindex="-1" role="dialog" aria-labelledby="addUmbrellaModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="addUmbrellaModalLabel">Add Umbrella Title</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label>Transliteration <span style="color:red">*</span></label>
                                                                    <input type="text" class="form-control" id="addUmbrellaTransliteration" placeholder="Enter transliteration">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Translated</label>
                                                                    <input type="text" class="form-control" id="addUmbrellaTranslation" placeholder="Enter translation">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Original</label>
                                                                    <input type="text" class="form-control" id="addUmbrellaOriginal" placeholder="Enter original title">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="button" class="btn btn-success" id="saveUmbrellaBtn">Add</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Edit Umbrella Title Modal (Bootstrap) -->
                                                <div class="modal fade" id="editUmbrellaModal" tabindex="-1" role="dialog" aria-labelledby="editUmbrellaModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editUmbrellaModalLabel">Edit Umbrella Title</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="text" class="form-control" id="editUmbrellaInput" placeholder="Edit umbrella title">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="button" class="btn btn-info" id="updateUmbrellaBtn">Update</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                        <label>Song Title</label>
                        <div style="padding-left:20px;">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group" style="display: flex; align-items: center; margin-bottom: 18px;">
                                    <label  style="flex: 0 0 220px; padding-right: 18px;">⊙ Transliteration <span style="color:red">*</span></label>
                                    <input type="text" name="Songtitle_transliteration" class="col-md-4" id="Songtitle_transliteration" value="<?= htmlspecialchars($song_transliteration) ?>" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group" style="display: flex; align-items: center; margin-bottom: 18px;">
                                    <label style="flex: 0 0 220px; padding-right: 18px;">⊙ Translated <span style="color:red">*</span></label>
                                    <input type="text" name="songtitletraan" class="col-md-4" id="songtitletraan" value="<?= htmlspecialchars($song_translated) ?>" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group" style="display: flex; align-items: center; margin-bottom: 18px;">
                                    <label style="flex: 0 0 220px; padding-right: 18px;">⊙ Original</label>
                                    <input type="text" name="songTitleOriginal" class="col-md-4" id="songTitleOriginal" value="<?= isset($song['songTitleOriginal']) ? htmlspecialchars($song['songTitleOriginal']) : '' ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="row" id="singerRow">
                            <div class="col-12">
                                <div class="form-group" style="display: flex; align-items: center; gap:10px; margin-bottom: 18px;">
                                    <?php 
                                    // Load singers from person table and build full name from parts
                                    $singer_rows = $this->db->query("
                                        SELECT id, first_name, middle_name, last_name FROM person
                                        ORDER BY LOWER(TRIM(CONCAT(IFNULL(first_name,''),' ',IFNULL(middle_name,''),' ',IFNULL(last_name,'')))) ASC
                                    ")->result();
                                    foreach ($selected_singers as $sid) {
                                        if ($sid === '' || !ctype_digit((string) $sid)) {
                                            continue;
                                        }
                                        $found = false;
                                        foreach ($singer_rows as $p) {
                                            if ((string) $p->id === (string) $sid) {
                                                $found = true;
                                                break;
                                            }
                                        }
                                        if (!$found) {
                                            $extra = $this->db->get_where('person', ['id' => (int) $sid])->row();
                                            if ($extra) {
                                                $singer_rows[] = $extra;
                                            }
                                        }
                                    }
                                    ?>
                                    <label style="flex: 0 0 220px; padding-right: 18px;">Singer <span style="color:red">*</span></label>
                                    <div class="input-btn-group d-flex align-items-center" style="gap: 8px; min-width: 0; flex: 0 0 auto;">
                                        <select class="form-control select2" multiple="multiple" data-skip-select2="true" name="singer[]" id="singer" data-placeholder="Select Singer" onchange="if(window.updateSingerPoetVisibility){window.updateSingerPoetVisibility('singer');}">
                                            <?php foreach ($singer_rows as $p): 
                                                $parts = [];
                                                if (!empty(trim($p->first_name)))  { $parts[] = trim($p->first_name); }
                                                if (!empty(trim($p->middle_name))) { $parts[] = trim($p->middle_name); }
                                                if (!empty(trim($p->last_name)))   { $parts[] = trim($p->last_name); }
                                                $fullName = implode(' ', $parts);
                                                if ($fullName === '') { $fullName = 'Unnamed'; }
                                                $pid = (string)$p->id;
                                                $isSelected = in_array($pid, array_map('strval', $selected_singers), true);
                                            ?>
                                                <option value="<?= htmlspecialchars($pid) ?>" <?= $isSelected ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($fullName) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-sm btn-success ml-2" id="addSingerBtn" style="white-space: nowrap;">Add New</button>
                                        <button type="button" class="btn btn-sm btn-primary ml-1" id="editSingerBtn" style="white-space: nowrap;">Edit</button>
                                        <button type="button" class="btn btn-sm btn-danger ml-1" id="deleteSingerBtn" style="white-space: nowrap;">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="poetRow">
                            <div class="col-12">
                                <div class="form-group" style="display: flex; align-items: center; gap:10px; margin-bottom: 18px;">
                                    <label style="flex: 0 0 220px; padding-right: 18px;">Poet <span style="color:red">*</span></label>
                                    <div class="input-btn-group d-flex align-items-center" style="gap: 8px; min-width: 0; flex: 0 0 auto;">
                                        <?php
                                        $poet_rows = $this->db->query("
                                            SELECT id, first_name, middle_name, last_name FROM person
                                            ORDER BY LOWER(TRIM(CONCAT(IFNULL(first_name,''),' ',IFNULL(middle_name,''),' ',IFNULL(last_name,'')))) ASC
                                        ")->result();
                                    foreach ($selected_poet as $pidRaw) {
                                        if ($pidRaw === '' || !ctype_digit((string) $pidRaw)) {
                                            continue;
                                        }
                                        $found = false;
                                        foreach ($poet_rows as $p) {
                                            if ((string) $p->id === (string) $pidRaw) {
                                                $found = true;
                                                break;
                                            }
                                        }
                                        if (!$found) {
                                            $extra = $this->db->get_where('person', ['id' => (int) $pidRaw])->row();
                                            if ($extra) {
                                                $poet_rows[] = $extra;
                                            }
                                        }
                                    }
                                        ?>
                                        <select class="form-control select2" multiple="multiple" data-skip-select2="true" name="poet[]" id="poet" data-placeholder="Select Poet" onchange="if(window.updateSingerPoetVisibility){window.updateSingerPoetVisibility('poet');}">
                                            <?php foreach ($poet_rows as $p): 
                                                $parts = [];
                                                if (!empty(trim($p->first_name)))  { $parts[] = trim($p->first_name); }
                                                if (!empty(trim($p->middle_name))) { $parts[] = trim($p->middle_name); }
                                                if (!empty(trim($p->last_name)))   { $parts[] = trim($p->last_name); }
                                                $fullName = implode(' ', $parts);
                                                if ($fullName === '') { $fullName = 'Unnamed'; }
                                                $pid = (string)$p->id;
                                                $isSelected = in_array($pid, array_map('strval', $selected_poet), true);
                                            ?>
                                                <option value="<?= htmlspecialchars($pid) ?>" <?= $isSelected ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($fullName) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-sm btn-success ml-2" id="addPoetBtn" style="white-space: nowrap;">Add New</button>
                                        <button type="button" class="btn btn-sm btn-primary ml-1" id="editPoetBtn" style="white-space: nowrap;">Edit</button>
                                        <button type="button" class="btn btn-sm btn-danger ml-1" id="deletePoetBtn" style="white-space: nowrap;">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add Singer Modal -->
                        <div class="song-person-dialog" id="addSingerModal" style="display:none;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2>Add New Singer</h2>
                                    <button class="close-btn" id="closeAddSinger">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" id="addSingerName" placeholder="Enter Singer Name">
                                    </div>
                                    <div class="form-group">
                                        <label>Hyperlink (Optional)</label>
                                        <input type="url" id="addSingerLink" placeholder="https://example.com">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn-secondary" id="cancelAddSinger">Cancel</button>
                                    <button type="button" class="btn-success" id="addSinger">Add</button>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="attributedPoetRow">
                            <div class="col-12">
                                <div class="form-group" style="display: flex; align-items: center; gap:10px; margin-bottom: 18px;">
                                    <label style="flex: 0 0 220px; padding-right: 18px;">Attributed Poet</label>
                                    <div class="input-btn-group d-flex align-items-center" style="gap: 8px; min-width: 0; flex: 0 0 auto;">
                                        <?php
                                        $selected_attributed_poet = [];
                                        if (isset($song) && is_array($song)) {
                                            if (isset($song['attributed_poet']) && trim((string) $song['attributed_poet']) !== '') {
                                                $selected_attributed_poet = array_values(array_filter(array_map('trim', explode(',', $song['attributed_poet']))));
                                            }
                                        }
                                        // Use same poet_rows for attributed poet selection
                                        foreach ($selected_attributed_poet as $pidRaw) {
                                            if ($pidRaw === '' || !ctype_digit((string) $pidRaw)) {
                                                continue;
                                            }
                                            $found = false;
                                            foreach ($poet_rows as $p) {
                                                if ((string) $p->id === (string) $pidRaw) {
                                                    $found = true;
                                                    break;
                                                }
                                            }
                                            if (!$found) {
                                                $extra = $this->db->get_where('person', ['id' => (int) $pidRaw])->row();
                                                if ($extra) {
                                                    $poet_rows[] = $extra;
                                                }
                                            }
                                        }
                                        ?>
                                        <select class="form-control select2" multiple="multiple" data-skip-select2="true" name="attributed_poet[]" id="attributed_poet" data-placeholder="Select Attributed Poet" onchange="if(window.updateSingerPoetVisibility){window.updateSingerPoetVisibility('attributed_poet');}">
                                            <?php foreach ($poet_rows as $p): 
                                                $parts = [];
                                                if (!empty(trim($p->first_name)))  { $parts[] = trim($p->first_name); }
                                                if (!empty(trim($p->middle_name))) { $parts[] = trim($p->middle_name); }
                                                if (!empty(trim($p->last_name)))   { $parts[] = trim($p->last_name); }
                                                $fullName = implode(' ', $parts);
                                                if ($fullName === '') { $fullName = 'Unnamed'; }
                                                $pid = (string)$p->id;
                                                $isSelected = in_array($pid, array_map('strval', $selected_attributed_poet), true);
                                            ?>
                                                <option value="<?= htmlspecialchars($pid) ?>" <?= $isSelected ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($fullName) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-sm btn-success ml-2" id="addAttributedPoetBtn" style="white-space: nowrap;">Add New</button>
                                        <button type="button" class="btn btn-sm btn-primary ml-1" id="editAttributedPoetBtn" style="white-space: nowrap;">Edit</button>
                                        <button type="button" class="btn btn-sm btn-danger ml-1" id="deleteAttributedPoetBtn" style="white-space: nowrap;">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add Poet Modal -->
                        <div class="song-person-dialog" id="addPoetModal" style="display:none;">
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
                                        <label>Hyperlink (Optional)</label>
                                        <input type="url" id="addPoetLink" placeholder="https://example.com">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn-secondary" id="cancelAddPoet">Cancel</button>
                                    <button type="button" class="btn-success" id="addPoet">Add</button>
                                </div>
                            </div>
                        </div>

                        <!-- Add Attributed Poet Modal -->
                        <div class="song-person-dialog" id="addAttributedPoetModal" style="display:none;">
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
                                        <label>Hyperlink (Optional)</label>
                                        <input type="url" id="addAttributedPoetLink" placeholder="https://example.com">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn-secondary" id="cancelAddAttributedPoet">Cancel</button>
                                    <button type="button" class="btn-success" id="addAttributedPoet">Add</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                            <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Year <span style="color:red">*</span></div>
                            <div style="flex: 1;">
                                <select class="form-control col-md-4" name="year">
                                    <option value="">Select Year</option>
                                    <?php 
                                    $y = date('Y'); 
                                    $selected = isset($song['year']) ? $song['year'] : '';
                                    for($i=$y; $i>=1900; $i--) {
                                        echo "<option value='$i' ".($i==$selected?'selected':'').">$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                            <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Location <span style="color:red">*</span></div>
                            <div style="flex: 1;">
                                <input type="text" class="form-control col-md-4" value="<?= isset($song['location']) ? $song['location'] : '' ?>" name="location" id="location" placeholder="Enter Location">
                            </div>
                        </div>
                        <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                            <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">YouTube Video ID</div>
                            <div style="flex: 1;">
                                <input type="text" name="youtubeVideoId" id="youtubeVideoId" value="<?= isset($song['youtubeVideoId']) ? htmlspecialchars($song['youtubeVideoId']) : '' ?>" class="form-control col-md-4">
                            </div>
                        </div>
                        <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                            <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">SoundCloud Track URL</div>
                            <div style="flex: 1;">
                                <input type="text" name="soundCloudTrackUrl" id="soundCloudTrackUrl" value="<?= isset($song['soundCloudTrackUrl']) ? htmlspecialchars($song['soundCloudTrackUrl']) : '' ?>" class="form-control col-md-4">
                            </div>
                        </div>
                        <?php
                        $songInterviewAudio = '';
                        if (isset($song) && is_array($song) && !empty($song['interview_audio'])) {
                            $songInterviewAudio = trim((string) $song['interview_audio']);
                        }
                        ?>
                        <div class="form-row" style="display: flex; align-items: flex-start; margin-bottom: 18px;">
                            <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Upload Audio Track</div>
                            <div style="flex: 1;">
                                <?php if ($songInterviewAudio !== ''): ?>
                                    <input type="hidden" name="interview_audio_existing" value="<?= htmlspecialchars($songInterviewAudio) ?>">
                                    <p class="mb-2 text-muted small">Current file is kept unless you choose a new audio file.</p>
                                <?php endif; ?>
                                <input type="file" name="interview_audio_upload" id="interview_audio_upload" class="form-control col-md-4" accept="audio/*">
                            </div>
                        </div>
                        <div class="form-row" id="addToRadioRow" style="display: <?= $songInterviewAudio !== '' ? 'flex' : 'none' ?>; align-items: center; margin-bottom: 18px;">
                            <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Radio</div>
                            <div style="flex: 1;">
                                <label class="mb-0" style="font-weight: normal;">
                                    <input type="checkbox" name="add_to_radio" id="add_to_radio" value="1">
                                    Add to radio (create or update a radio track for this song using this audio)
                                </label>
                            </div>
                        </div>
                        <script>
                        (function () {
                            var f = document.getElementById('interview_audio_upload');
                            var row = document.getElementById('addToRadioRow');
                            var existing = <?= json_encode($songInterviewAudio !== '') ?>;
                            if (!f || !row) return;
                            function refresh() {
                                var hasFile = f.files && f.files.length > 0;
                                row.style.display = (hasFile || existing) ? 'flex' : 'none';
                            }
                            f.addEventListener('change', refresh);
                        })();
                        </script>
                        <?php
                        $existingThumbnailUrl = '';
                        if (isset($song) && is_array($song) && !empty($song['thumbnailUrl'])) {
                            $existingThumbnailUrl = trim((string) $song['thumbnailUrl']);
                        }
                        // Preview only: production base (DB path in hidden field stays unchanged for save/update).
                        $thumbnailPublicBase = 'https://ajab.designanddevelopment.in/admin';
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
                        <div class="form-row" style="display: flex; align-items: flex-start; margin-bottom: 18px;">
                            <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Thumbnail Image Upload <span style="color:red">*</span></div>
                            <div style="flex: 1;">
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
                                    <!-- Thumbnail image upload field: name must be 'thumbnailUrl' for upload to work -->
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
                        <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                            <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Thumbnail Excerpt <span style="color:red">*</span></div>
                            <div style="flex: 1;">
                                <input type="text" name="thumbnailexcerpt" id="thumbnailexcerpt" value="<?= isset($song['thumbnailexcerpt']) ? htmlspecialchars($song['thumbnailexcerpt']) : '' ?>" class="form-control col-md-4">
                            </div>
                        </div>
                        <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                            <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Song Lyrics (Original)</div>
                            <div style="flex: 1;">
                                <textarea id="songLyricsOriginal" name="songLyricsOriginal" class="form-control col-md-8"><?= htmlspecialchars($songLyricsOriginal) ?></textarea>
                            </div>
                        </div>
                        <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                            <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Song Lyrics (Transliteration)</div>
                            <div style="flex: 1;">
                                <textarea id="songLyricsNotes" name="songLyricsNotes" class="form-control col-md-8"><?= htmlspecialchars($songLyricsTransliteration) ?></textarea>
                            </div>
                        </div>
                        <style>
                            .song-translation-stack .translation-main-group {
                                display: flex;
                                flex-direction: column;
                                align-items: flex-start;
                                gap: 8px;
                                width: 100%;
                            }
                            /* Keep the translator dropdown + its Add/Edit/Delete
                               buttons packed together on the LEFT, instead of the
                               select stretching and pushing the buttons to the
                               far right edge of the full-width row. */
                            .song-translation-stack .translation-select {
                                justify-content: flex-start !important;
                            }
                            .song-translation-stack .translation-select > #translator,
                            .song-translation-stack .translation-select .select2-container {
                                flex: 0 0 220px !important;
                                width: 220px !important;
                                max-width: 220px;
                            }
                            .song-translation-stack .translation-select > .btn {
                                flex: 0 0 auto !important;
                            }
                            .song-translation-stack .select2-container .select2-selection--multiple .select2-selection__placeholder {
                                color: #6c757d;
                            }
                            /* Stretch every direct child of the (align-items:flex-start)
                               column so the editor spans the full available width,
                               matching the Original / Transliteration fields. */
                            .song-translation-stack .translation-main-group > * {
                                width: 100%;
                                align-self: stretch;
                            }
                            .song-translation-stack .translation-editor {
                                width: 100%;
                            }
                            .song-translation-stack .translation-editor .cke {
                                width: 100% !important;
                                display: block !important;
                                float: none !important;
                            }
                            #extraSongTranslations {
                                width: 100%;
                                display: block;
                            }
                            #extraSongTranslations .translation-block {
                                width: 100%;
                                clear: both;
                                margin-top: 12px;
                            }
                        </style>
                        <div class="form-row song-translation-stack" style="display: flex; align-items: flex-start; margin-bottom: 18px;">
                            <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Song Lyrics (Translation)</div>
                            <div style="flex: 1;">
                                <div class="translation-main-group">
                                    <div class="translation-select input-btn-group d-flex align-items-center" style="flex-wrap: nowrap; gap: 8px; min-width: 0;">
                                <?php
                                // Translators stored on song as comma-separated person.id (same as couplet form — person table, not translator table)
                                $translator_rows = $this->db->query("SELECT id, first_name, middle_name, last_name FROM person ORDER BY first_name ASC, middle_name ASC, last_name ASC")->result();
                                foreach ($selected_translator as $tidRaw) {
                                    if ($tidRaw === '' || !ctype_digit((string) $tidRaw)) {
                                        continue;
                                    }
                                    $found = false;
                                    foreach ($translator_rows as $p) {
                                        if ((string) $p->id === (string) $tidRaw) {
                                            $found = true;
                                            break;
                                        }
                                    }
                                    if (!$found) {
                                        $extra = $this->db->get_where('person', ['id' => (int) $tidRaw])->row();
                                        if ($extra) {
                                            $translator_rows[] = $extra;
                                        }
                                    }
                                }
                                ?>
                                <select class="form-control select2" multiple="multiple" data-skip-select2="true" name="translator[]" id="translator" style="flex: 0 0 220px; width: 220px; min-width: 0;" data-placeholder="Select Translator" data-select2-search-placeholder="Select Translator">
                                    <?php foreach ($translator_rows as $p):
                                        $parts = [];
                                        if (!empty(trim($p->first_name))) {
                                            $parts[] = trim($p->first_name);
                                        }
                                        if (!empty(trim($p->middle_name))) {
                                            $parts[] = trim($p->middle_name);
                                        }
                                        if (!empty(trim($p->last_name))) {
                                            $parts[] = trim($p->last_name);
                                        }
                                        $fullName = implode(' ', $parts);
                                        if ($fullName === '') {
                                            $fullName = 'Unnamed';
                                        }
                                        $pid = (string) $p->id;
                                        $isSelected = in_array($pid, array_map('strval', $selected_translator), true);
                                    ?>
                                        <option value="<?= htmlspecialchars($pid) ?>" <?= $isSelected ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($fullName) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                        <button type="button" class="btn btn-sm btn-success ml-2" id="addTranslatorBtn" style="white-space: nowrap; flex-shrink: 0;">Add New</button>
                                        <button type="button" class="btn btn-sm btn-primary ml-1" id="editTranslatorBtn" style="white-space: nowrap; flex-shrink: 0;">Edit</button>
                                        <button type="button" class="btn btn-sm btn-danger ml-1" id="deleteTranslatorBtn" style="white-space: nowrap; flex-shrink: 0;">Delete</button>
                                    </div>
                                    <div class="translation-editor">
                                        <textarea id="songLyricsTranslated" name="songLyricsTranslated" class="form-control"><?= htmlspecialchars($songLyricsTranslation) ?></textarea>
                                    </div>
                                    <div style="margin-top: 10px;">
                                        <button type="button" class="btn btn-primary btn-sm" id="addSongTranslationBtn">Add Song Translation</button>
                                    </div>
                                    <div id="extraSongTranslations"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                            <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">About</div>
                            <div style="flex: 1;">
                                <textarea class="form-control col-md-8" name="about" id="songAbout" rows="3"><?= htmlspecialchars($songAbout) ?></textarea>
                            </div>
                        </div>

                        <!-- Add Translator Modal -->
                        <div class="song-person-dialog" id="addTranslatorModal" style="display:none;">
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
                                        <label>Hyperlink</label>
                                        <input type="text" id="addTranslatorLink" placeholder="Enter Hyperlink (optional)">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn-secondary" id="cancelAddTranslator">Cancel</button>
                                    <button type="button" class="btn-success" id="addTranslator">Add</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                            <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Song Notes</div>
                            <div style="flex: 1;">
                                <textarea class="form-control col-md-8" name="songnotes" id="songnotes" rows="3"><?= isset($song['songnotes']) ? htmlspecialchars($song['songnotes']) : '' ?></textarea>
                            </div>
                        </div>

                        <?php
                            // Glossary keywords: same source as Related Keywords (word table)
                            // Pre-selected: parse `songglossary` as CSV of word IDs (numeric only)
                            $selected_song_glossary = [];
                            if (isset($song['songglossary']) && trim((string)$song['songglossary']) !== '') {
                                foreach (array_filter(array_map('trim', explode(',', (string)$song['songglossary']))) as $g) {
                                    if (ctype_digit((string)$g)) { $selected_song_glossary[] = (string)(int)$g; }
                                }
                                $selected_song_glossary = array_values(array_unique($selected_song_glossary));
                            }
                            $glossary_word_rows = $this->db->table_exists('word')
                                ? $this->db->query("SELECT id, word_transliteration FROM word ORDER BY LOWER(TRIM(COALESCE(word_transliteration,''))) ASC, id ASC")->result()
                                : [];
                        ?>
                        <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                            <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Song Glossary</div>
                            <div style="flex: 0 0 auto;">
                                <div class="input-btn-group" style="display: inline-flex; align-items: center; gap: 8px;">
                                    <select class="form-control select2" multiple="multiple" data-skip-select2="true" name="songglossary[]" id="songglossary" data-placeholder="Select Glossary Term">
                                        <?php foreach ($glossary_word_rows as $gw) : ?>
                                            <option value="<?= (int)$gw->id ?>" <?= in_array((string)$gw->id, $selected_song_glossary, true) ? 'selected' : '' ?>><?= htmlspecialchars((string)$gw->word_transliteration) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="button" class="btn btn-sm btn-success" id="addGlossaryWordBtn" style="white-space:nowrap;">Add New</button>
                                    <button type="button" class="btn btn-sm btn-primary ml-1" id="editGlossaryWordBtn" style="white-space:nowrap;">Edit</button>
                                    <button type="button" class="btn btn-sm btn-danger ml-1" id="deleteGlossaryWordBtn" style="white-space:nowrap;">Delete</button>
                                </div>

                                <!-- Add New Glossary Word Modal -->
                                <div class="modal fade" id="addGlossaryWordModal" tabindex="-1" role="dialog" aria-labelledby="addGlossaryWordModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addGlossaryWordModalLabel">Add New Glossary Word</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Original</label>
                                                    <input type="text" class="form-control" id="newGlossaryOriginal" placeholder="Enter Original">
                                                </div>
                                                <div class="form-group">
                                                    <label>Translation</label>
                                                    <input type="text" class="form-control" id="newGlossaryTranslation" placeholder="Enter Translation">
                                                </div>
                                                <div class="form-group">
                                                    <label>Transliteration</label>
                                                    <input type="text" class="form-control" id="newGlossaryTransliteration" placeholder="Enter Transliteration">
                                                </div>
                                                <div class="form-group">
                                                    <label>Word Meaning</label>
                                                    <textarea class="form-control" id="newGlossaryMeaning" rows="3" placeholder="Enter word meaning"></textarea>
                                                </div>
                                                <div class="form-group" style="margin-top: 8px;">
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
                                            if (window.bootstrap && bootstrap.Modal) {
                                                bootstrap.Modal.getOrCreateInstance(modal).show(); return;
                                            }
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
                                        var sel = document.getElementById('songglossary');
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
                                            var orig = (document.getElementById('newGlossaryOriginal').value || '').trim();
                                            var trans = (document.getElementById('newGlossaryTranslation').value || '').trim();
                                            var translit = (document.getElementById('newGlossaryTransliteration').value || '').trim();
                                            var meaning = (document.getElementById('newGlossaryMeaning').value || '').trim();
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
                                                    // Real-time refresh: rebuilds Bootstrap Multiselect / Select2 + ensures new id is selected
                                                    if (window.__adminRefreshSelect) {
                                                        window.__adminRefreshSelect('#songglossary', String(data.id));
                                                    } else if (window.jQuery) {
                                                        $('#songglossary').trigger('change');
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
                                })();
                                </script>
                            </div>
                        </div>

                        <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                            <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Is this also a Reflection?</div>
                            <div style="flex: 1; display: flex; align-items: center;">
                                <input type="checkbox" name="reflection" id="reflection" value="true" <?= ($selected_ref_check == 'true' || $selected_ref_check == '1') ? 'checked' : '' ?> style="width:auto; height:auto;">
                            </div>
                        </div>

                        <label>Related Content</label>
                        <div style="padding-left:20px;">
                        <div class="row">
                            <div class="col-12">
                                
                                <div class="form-group"> 
                                    <?php
                                    // Related keywords: always `word.id` (song_word / legacy CSV on song row).
                                    $keyword_rows = $this->db->table_exists('word')
                                        ? $this->db->query("SELECT id, word_transliteration FROM word ORDER BY LOWER(TRIM(COALESCE(word_transliteration,''))) ASC, id ASC")->result()
                                        : [];
                                    ?>
                                    <label>⊙ Keywords</label>
                                    <div class="input-btn-group d-flex align-items-center" style="gap: 8px; flex: 0 0 auto;">
                                        <select class="form-control select2" multiple="multiple" data-skip-select2="true" name="relatedkeywords[]" id="relatedkeywords">
                                        <?php foreach ($keyword_rows as $keyword) : ?>
                                            <option value="<?= $keyword->id ?>" <?= in_array((string) $keyword->id, array_map('strval', $selected_keywords), true) ? 'selected' : '' ?>><?= htmlspecialchars($keyword->word_transliteration) ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-sm btn-success" id="addNewKeywordBtn" style="white-space:nowrap;">Add New</button>
                                        <button type="button" class="btn btn-sm btn-primary ml-1" id="editKeywordBtn" style="white-space:nowrap;">Edit</button>
                                        <button type="button" class="btn btn-sm btn-danger ml-1" id="deleteKeywordBtn" style="white-space:nowrap;">Delete</button>
                                    </div>
                                <!-- Add New Keyword Modal -->
                                <div class="modal fade" id="addNewKeywordModal" tabindex="-1" role="dialog" aria-labelledby="addNewKeywordModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addNewKeywordModalLabel">Add New Keyword</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Original</label>
                                                    <input type="text" class="form-control" id="newKeywordOriginal" placeholder="Enter Original Keyword">
                                                </div>
                                                <div class="form-group">
                                                    <label>Translation</label>
                                                    <input type="text" class="form-control" id="newKeywordTranslation" placeholder="Enter Keyword Translation">
                                                </div>
                                                <div class="form-group">
                                                    <label>Transliteration</label>
                                                    <input type="text" class="form-control" id="newKeywordTransliteration" placeholder="Enter Keyword Transliteration">
                                                </div>
                                                <div class="form-group">
                                                    <label>Word Meaning</label>
                                                    <textarea class="form-control" id="newKeywordMeaning" rows="3" placeholder="Enter word meaning"></textarea>
                                                </div>
                                            </div>
                                            <!-- Anti-conflict CSS so the page-level flex .form-group rule
                                                 doesn't squash labels/inputs inside this modal. -->
                                            <style>
                                                #addNewKeywordModal .form-group { display:block !important; align-items:initial !important; }
                                                #addNewKeywordModal .form-group > label { display:block !important; flex:none !important; max-width:none !important; width:auto !important; margin-bottom:6px !important; padding-right:0 !important; }
                                                #addNewKeywordModal .form-group > *:not(label) { width:100% !important; flex:none !important; }
                                                #addNewKeywordModal { z-index: 100050; }
                                            </style>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-primary" id="saveNewKeywordBtn">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                // Add New Keyword Modal logic — delegated, runs even if elements are added/moved later
                                (function () {
                                function init() {
                                    var addNewKeywordBtn = document.getElementById('addNewKeywordBtn');
                                    var addNewKeywordModal = document.getElementById('addNewKeywordModal');
                                    var saveNewKeywordBtn = document.getElementById('saveNewKeywordBtn');
                                    var addNewKeywordInput = document.getElementById('addNewKeywordInput');
                                    var relatedkeywordsSelect = document.getElementById('relatedkeywords');
                                    console.log('[kw-modal] init btn=', !!addNewKeywordBtn, 'modal=', !!addNewKeywordModal);
                                    if (!addNewKeywordBtn || !addNewKeywordModal) {
                                        // Try again later if DOM not ready
                                        setTimeout(init, 200);
                                        return;
                                    }
                                    // Document-level delegation as belt-and-braces — works even if button gets re-rendered
                                    if (!document.__kwBtnDelegated) {
                                        document.__kwBtnDelegated = true;
                                        document.addEventListener('click', function (e) {
                                            var t = e.target;
                                            if (!t) return;
                                            if (t.id === 'addNewKeywordBtn' || (t.closest && t.closest('#addNewKeywordBtn'))) {
                                                e.preventDefault();
                                                console.log('[kw-modal] delegated click — opening');
                                                if (typeof __showKwModal === 'function') {
                                                    __showKwModal();
                                                } else {
                                                    var m = document.getElementById('addNewKeywordModal');
                                                    if (m) {
                                                        m.classList.add('show');
                                                        m.style.display = 'block';
                                                        document.body.classList.add('modal-open');
                                                        if (!document.getElementById('kw-modal-backdrop')) {
                                                            var bd = document.createElement('div');
                                                            bd.id = 'kw-modal-backdrop';
                                                            bd.className = 'modal-backdrop fade show';
                                                            document.body.appendChild(bd);
                                                        }
                                                    }
                                                }
                                                var inp = document.getElementById('addNewKeywordInput');
                                                if (inp) { inp.value = ''; setTimeout(function(){ inp.focus(); }, 200); }
                                            }
                                        });
                                    }
                                    // Robust modal show/hide: try Bootstrap (jQuery or BS5 native), fallback to manual show.
                                    function __showKwModal() {
                                        try {
                                            if (window.bootstrap && bootstrap.Modal) {
                                                var inst = bootstrap.Modal.getOrCreateInstance(addNewKeywordModal);
                                                inst.show();
                                                return;
                                            }
                                            if (window.jQuery && $.fn && $.fn.modal) {
                                                $(addNewKeywordModal).modal('show');
                                                return;
                                            }
                                        } catch (e) {}
                                        // Manual fallback
                                        addNewKeywordModal.classList.add('show');
                                        addNewKeywordModal.style.display = 'block';
                                        addNewKeywordModal.removeAttribute('aria-hidden');
                                        addNewKeywordModal.setAttribute('aria-modal', 'true');
                                        document.body.classList.add('modal-open');
                                        if (!document.getElementById('kw-modal-backdrop')) {
                                            var bd = document.createElement('div');
                                            bd.id = 'kw-modal-backdrop';
                                            bd.className = 'modal-backdrop fade show';
                                            document.body.appendChild(bd);
                                        }
                                    }
                                    function __hideKwModal() {
                                        try {
                                            if (window.bootstrap && bootstrap.Modal) {
                                                var inst = bootstrap.Modal.getInstance(addNewKeywordModal);
                                                if (inst) { inst.hide(); return; }
                                            }
                                            if (window.jQuery && $.fn && $.fn.modal) {
                                                $(addNewKeywordModal).modal('hide');
                                                return;
                                            }
                                        } catch (e) {}
                                        addNewKeywordModal.classList.remove('show');
                                        addNewKeywordModal.style.display = 'none';
                                        addNewKeywordModal.setAttribute('aria-hidden', 'true');
                                        document.body.classList.remove('modal-open');
                                        var bd = document.getElementById('kw-modal-backdrop');
                                        if (bd) bd.remove();
                                    }
                                    // Hook close buttons (data-dismiss="modal" + .close-btn)
                                    addNewKeywordModal.querySelectorAll('[data-dismiss="modal"], .close, .btn-secondary').forEach(function(btn){
                                        btn.addEventListener('click', function(e){ e.preventDefault(); __hideKwModal(); });
                                    });
                                    // Helpers: read/clear the 4 modal fields (Original / Translation / Transliteration / Word Meaning)
                                    function __kwReadModal() {
                                        var orig     = (document.getElementById('newKeywordOriginal') || {}).value || '';
                                        var trans    = (document.getElementById('newKeywordTranslation') || {}).value || '';
                                        var translit = (document.getElementById('newKeywordTransliteration') || {}).value || '';
                                        var meaning  = (document.getElementById('newKeywordMeaning') || {}).value || '';
                                        return {
                                            word_original:        orig.trim(),
                                            word_translation:     trans.trim(),
                                            word_transliteration: translit.trim(),
                                            glossary_meaning:     meaning.trim()
                                        };
                                    }
                                    function __kwClearModal() {
                                        ['newKeywordOriginal','newKeywordTranslation','newKeywordTransliteration','newKeywordMeaning'].forEach(function (id) {
                                            var el = document.getElementById(id); if (el) el.value = '';
                                        });
                                    }

                                    if (addNewKeywordBtn && addNewKeywordModal && saveNewKeywordBtn && relatedkeywordsSelect) {
                                        addNewKeywordBtn.onclick = function() {
                                            __kwClearModal();
                                            __showKwModal();
                                            setTimeout(function() {
                                                var f = document.getElementById('newKeywordTransliteration');
                                                if (f) f.focus();
                                            }, 300);
                                        };
                                        saveNewKeywordBtn.onclick = async function() {
                                            var fields = __kwReadModal();
                                            if (!fields.word_transliteration) {
                                                alert('Transliteration is required!');
                                                return;
                                            }
                                            saveNewKeywordBtn.disabled = true;
                                            try {
                                                var body = new URLSearchParams();
                                                Object.keys(fields).forEach(function (k) { body.append(k, fields[k]); });
                                                var res = await fetch('<?= base_url('SongController/ajax_create_keyword') ?>', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                    body: body.toString()
                                                });
                                                var data = await res.json();
                                                if (data && data.success) {
                                                    // Add or update option in the dropdown.
                                                    var existingOpt = Array.from(relatedkeywordsSelect.options).find(function (o) { return String(o.value) === String(data.id); });
                                                    if (existingOpt) {
                                                        existingOpt.text = data.word_transliteration || fields.word_transliteration;
                                                        existingOpt.selected = true;
                                                    } else {
                                                        var option = document.createElement('option');
                                                        option.value = data.id;
                                                        option.text = data.word_transliteration || fields.word_transliteration;
                                                        option.selected = true;
                                                        relatedkeywordsSelect.add(option);
                                                    }
                                                    if (window.__adminRefreshSelect) {
                                                        window.__adminRefreshSelect('#relatedkeywords', String(data.id));
                                                    } else if (window.jQuery) {
                                                        $('#relatedkeywords').trigger('change');
                                                    }
                                                    __hideKwModal();
                                                    __kwClearModal();
                                                    if (window.Swal) Swal.fire({icon:'success',title:'Success',text:'Keyword saved!',timer:1200,showConfirmButton:false});
                                                } else {
                                                    alert('Failed: ' + (data && data.message ? data.message : 'Unknown error'));
                                                }
                                            } catch (e) {
                                                alert('Error: ' + e.message);
                                            }
                                            saveNewKeywordBtn.disabled = false;
                                        };
                                    }
                                }
                                if (document.readyState === 'loading') {
                                    document.addEventListener('DOMContentLoaded', init);
                                } else {
                                    init();
                                }
                                })();
                                </script>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <?php
                                    $song_rows = $this->db->query("
                                        SELECT
                                            s.id,
                                            COALESCE(ts.english_transliteration, ts.original_title, tu.english_transliteration, tu.original_title, CONCAT('Song #', s.id)) AS song_title_label,
                                            COALESCE(
                                                (
                                                    SELECT GROUP_CONCAT(TRIM(CONCAT_WS(' ', p.first_name, p.middle_name, p.last_name)) SEPARATOR ', ')
                                                    FROM song_singer ss
                                                    JOIN person p ON p.id = ss.singer_id
                                                    WHERE ss.song_id = s.id
                                                ),
                                                (
                                                    SELECT GROUP_CONCAT(TRIM(CONCAT_WS(' ', p2.first_name, p2.middle_name, p2.last_name)) SEPARATOR ', ')
                                                    FROM song_person sp
                                                    JOIN person p2 ON p2.id = sp.person_id
                                                    WHERE sp.song_id = s.id AND p2.type = 1
                                                )
                                            ) AS singer_names
                                        FROM song s
                                        LEFT JOIN title ts ON ts.id = s.song_title_id
                                        LEFT JOIN title tu ON tu.id = s.umbrella_title_id
                                        ORDER BY s.id DESC
                                    ")->result();
                                    
                                    ?>
                                    <label>⊙ Songs</label>
                                    <div class="input-btn-group d-flex align-items-center">
                                        <select class="form-control select2 col-md-4" multiple="multiple" data-skip-select2="true" name="related_songs[]" id="related_songs">
                                        <?php foreach ($song_rows as $song_row) : ?>
                                            <?php
                                            $songLabel = trim((string)$song_row->song_title_label);
                                            $singerLabel = trim((string)$song_row->singer_names);
                                            if ($songLabel === '') { $songLabel = 'Song #' . $song_row->id; }
                                            $fullLabel = $songLabel . ($singerLabel !== '' ? (' - (' . $singerLabel . ')') : '');
                                            ?>
                                            <option value="<?= $song_row->id ?>" <?= in_array((string) $song_row->id, array_map('strval', $selected_related_songs), true) ? 'selected' : '' ?>><?= htmlspecialchars($fullLabel) ?></option>
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
                                    <div class="input-btn-group d-flex align-items-center">
                                        <select class="form-control select2 col-md-4" multiple="multiple" data-skip-select2="true" name="reflections[]" id="reflections">
                                        <?php foreach ($reflection_rows as $reflection_row) :
                                            $rid = (string) $reflection_row->id;
                                            $isSelected = in_array($rid, array_map('strval', $selected_reflections), true);
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
                                    <div class="input-btn-group d-flex align-items-center">
                                        <select class="form-control select2 col-md-4" multiple="multiple" data-skip-select2="true" name="relatedpoems[]" id="relatedpoems">
                                        <?php foreach ($poem_rows as $poem_row) : ?>
                                            <option value="<?= $poem_row->id ?>" <?= in_array((string) $poem_row->id, array_map('strval', $selected_couplets), true) ? 'selected' : '' ?>><?= htmlspecialchars($poem_row->poem_label) ?></option>
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
                                    <div class="input-btn-group d-flex align-items-center">
                                        <select class="form-control select2 col-md-4" multiple="multiple" data-skip-select2="true" name="related_people[]" id="related_people">
                                        <?php foreach ($person_rows as $person_row) : ?>
                                            <option value="<?= $person_row->id ?>" <?= in_array((string) $person_row->id, array_map('strval', $selected_people), true) ? 'selected' : '' ?>><?= htmlspecialchars($person_row->first_name . ' ' . $person_row->middle_name . ' ' . $person_row->last_name) ?></option>
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
                                    if ($this->db->table_exists('film')) {
                                        $film_rows = $this->db->query("SELECT id, COALESCE(NULLIF(english_transliteration, ''), NULLIF(english_translation, ''), original_title) AS film_label FROM film")->result();
                                    } else {
                                        $film_rows = [];
                                    }
                                     ?>
                                    <label>⊙ Films</label>
                                    <div class="input-btn-group d-flex align-items-center">
                                        <select class="form-control select2 col-md-4" multiple="multiple" data-skip-select2="true" name="films[]" id="films">
                                        <?php foreach ($film_rows as $film_row) : ?>
                                            <option value="<?= $film_row->id ?>" <?= in_array((string) $film_row->id, array_map('strval', $selected_films), true) ? 'selected' : '' ?>><?= htmlspecialchars($film_row->film_label) ?></option>
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
                                    if ($this->db->table_exists('film_episode')) {
                                        $episode_rows = $this->db->query("SELECT id, COALESCE(NULLIF(english_transliteration, ''), NULLIF(english_translation, ''), original_title) AS episode_label FROM film_episode")->result();
                                    } else {
                                        $episode_rows = [];
                                    }
                                     ?>
                                    <label>⊙ Film Episodes</label>
                                    <div class="input-btn-group d-flex align-items-center">
                                        <select class="form-control select2 col-md-4" multiple="multiple" data-skip-select2="true" name="film_episodes[]" id="film_episodes">
                                        <?php foreach ($episode_rows as $episode_row) : ?>
                                            <option value="<?= $episode_row->id ?>" <?= in_array((string) $episode_row->id, array_map('strval', $selected_episodes), true) ? 'selected' : '' ?>><?= htmlspecialchars($episode_row->episode_label) ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="modal fade" id="addNewSongModal" tabindex="-1" role="dialog" aria-labelledby="addNewSongModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addNewSongModalLabel">Add New Song</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="text" class="form-control" id="addNewSongInput" placeholder="Enter new song title">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-primary" id="saveNewSongBtn">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Add New Reflection Modal -->
                                    <div class="modal fade" id="addNewReflectionModal" tabindex="-1" role="dialog" aria-labelledby="addNewReflectionModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addNewReflectionModalLabel">Add New Reflection</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="text" class="form-control" id="addNewReflectionInput" placeholder="Enter new reflection title">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-primary" id="saveNewReflectionBtn">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Add New Poem Modal -->
                                    <div class="modal fade" id="addNewPoemModal" tabindex="-1" role="dialog" aria-labelledby="addNewPoemModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addNewPoemModalLabel">Add New Poem</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="text" class="form-control" id="addNewPoemInput" placeholder="Enter new poem title">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-primary" id="saveNewPoemBtn">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Add New Person Modal -->
                                    <div class="modal fade" id="addNewPersonModal" tabindex="-1" role="dialog" aria-labelledby="addNewPersonModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addNewPersonModalLabel">Add New Person</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="text" class="form-control" id="addNewPersonInput" placeholder="Enter new person name">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-primary" id="saveNewPersonBtn">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Add New Film Modal -->
                                    <div class="modal fade" id="addNewFilmModal" tabindex="-1" role="dialog" aria-labelledby="addNewFilmModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addNewFilmModalLabel">Add New Film</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="text" class="form-control" id="addNewFilmInput" placeholder="Enter new film title">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-primary" id="saveNewFilmBtn">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Add New Episode Modal -->
                                    <div class="modal fade" id="addNewEpisodeModal" tabindex="-1" role="dialog" aria-labelledby="addNewEpisodeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addNewEpisodeModalLabel">Add New Film Episode</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="text" class="form-control" id="addNewEpisodeInput" placeholder="Enter new episode title">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-primary" id="saveNewEpisodeBtn">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                    // Add New Entity Modals logic for Songs, Reflections, Poems, People, Films, Film Episodes
                                    document.addEventListener('DOMContentLoaded', function() {
                                        // Song
                                        var addNewSongBtn = document.getElementById('addNewSongBtn');
                                        var addNewSongModal = document.getElementById('addNewSongModal');
                                        var saveNewSongBtn = document.getElementById('saveNewSongBtn');
                                        var addNewSongInput = document.getElementById('addNewSongInput');
                                        var relatedSongsSelect = document.getElementById('related_songs');
                                        if (addNewSongBtn && addNewSongModal && saveNewSongBtn && addNewSongInput && relatedSongsSelect) {
                                            addNewSongBtn.onclick = function() {
                                                $(addNewSongModal).modal('show');
                                                addNewSongInput.value = '';
                                                setTimeout(function() { addNewSongInput.focus(); }, 300);
                                            };
                                            saveNewSongBtn.onclick = async function() {
                                                var newSong = addNewSongInput.value.trim();
                                                if (!newSong) {
                                                    alert('Please enter a song title!');
                                                    return;
                                                }
                                                saveNewSongBtn.disabled = true;
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
                                                        if (window.jQuery && $('#relatedkeywords').length) {
                                                            if ($('#relatedkeywords').data('bs.multiselect') && $.fn.multiselect) {
                                                                $('#relatedkeywords').multiselect('rebuild');
                                                            }
                                                            $('#relatedkeywords').trigger('change');
                                                        }
                                                        if (typeof __hideKwModal === 'function') __hideKwModal(); else $(addNewKeywordModal).modal('hide');
                                                        addNewKeywordInput.value = '';
                                                        if (window.Swal) Swal.fire({icon:'success',title:'Success',text:data.message || 'Keyword added!',timer:1200,showConfirmButton:false});
                                                    } else {
                                                        alert((data && data.message) ? data.message : 'Failed to add keyword!');
                                                    }
                                                } catch (e) {
                                                    alert('Error: ' + e.message);
                                                }
                                                saveNewSongBtn.disabled = false;
                                            };
                                        }
                                        // Reflection
                                        var addNewReflectionBtn = document.getElementById('addNewReflectionBtn');
                                        var addNewReflectionModal = document.getElementById('addNewReflectionModal');
                                        var saveNewReflectionBtn = document.getElementById('saveNewReflectionBtn');
                                        var addNewReflectionInput = document.getElementById('addNewReflectionInput');
                                        var reflectionsSelect = document.getElementById('reflections');
                                        if (addNewReflectionBtn && addNewReflectionModal && saveNewReflectionBtn && addNewReflectionInput && reflectionsSelect) {
                                            addNewReflectionBtn.onclick = function() {
                                                $(addNewReflectionModal).modal('show');
                                                addNewReflectionInput.value = '';
                                                setTimeout(function() { addNewReflectionInput.focus(); }, 300);
                                            };
                                            saveNewReflectionBtn.onclick = async function() {
                                                var newReflection = addNewReflectionInput.value.trim();
                                                if (!newReflection) {
                                                    alert('Please enter a reflection title!');
                                                    return;
                                                }
                                                saveNewReflectionBtn.disabled = true;
                                                try {
                                                    var res = await fetch('<?= base_url('SongController/ajax_create_reflection') ?>', {
                                                        method: 'POST',
                                                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                        body: 'title=' + encodeURIComponent(newReflection)
                                                    });
                                                    var data = await res.json();
                                                    if (data && data.success) {
                                                        var option = document.createElement('option');
                                                        option.value = data.id;
                                                        option.text = data.title || newReflection;
                                                        option.selected = true;
                                                        reflectionsSelect.add(option);
                                                        if (window.jQuery && $('#reflections').length) $('#reflections').trigger('change');
                                                        $(addNewReflectionModal).modal('hide');
                                                        addNewReflectionInput.value = '';
                                                        if (window.Swal) Swal.fire({icon:'success',title:'Success',text:'Reflection added!',timer:1200,showConfirmButton:false});
                                                    } else {
                                                        alert('Failed to add reflection!');
                                                    }
                                                } catch (e) {
                                                    alert('Error: ' + e.message);
                                                }
                                                saveNewReflectionBtn.disabled = false;
                                            };
                                        }
                                        // Poem
                                        var addNewPoemBtn = document.getElementById('addNewPoemBtn');
                                        var addNewPoemModal = document.getElementById('addNewPoemModal');
                                        var saveNewPoemBtn = document.getElementById('saveNewPoemBtn');
                                        var addNewPoemInput = document.getElementById('addNewPoemInput');
                                        var poemsSelect = document.getElementById('relatedpoems');
                                        if (addNewPoemBtn && addNewPoemModal && saveNewPoemBtn && addNewPoemInput && poemsSelect) {
                                            addNewPoemBtn.onclick = function() {
                                                $(addNewPoemModal).modal('show');
                                                addNewPoemInput.value = '';
                                                setTimeout(function() { addNewPoemInput.focus(); }, 300);
                                            };
                                            saveNewPoemBtn.onclick = async function() {
                                                var newPoem = addNewPoemInput.value.trim();
                                                if (!newPoem) {
                                                    alert('Please enter a poem title!');
                                                    return;
                                                }
                                                saveNewPoemBtn.disabled = true;
                                                try {
                                                    var res = await fetch('<?= base_url('SongController/ajax_create_poem') ?>', {
                                                        method: 'POST',
                                                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                        body: 'original_title=' + encodeURIComponent(newPoem)
                                                    });
                                                    var data = await res.json();
                                                    if (data && data.success) {
                                                        var option = document.createElement('option');
                                                        option.value = data.id;
                                                        option.text = data.original_title || newPoem;
                                                        option.selected = true;
                                                        poemsSelect.add(option);
                                                        if (window.jQuery && $('#relatedpoems').length) $('#relatedpoems').trigger('change');
                                                        $(addNewPoemModal).modal('hide');
                                                        addNewPoemInput.value = '';
                                                        if (window.Swal) Swal.fire({icon:'success',title:'Success',text:'Poem added!',timer:1200,showConfirmButton:false});
                                                    } else {
                                                        alert('Failed to add poem!');
                                                    }
                                                } catch (e) {
                                                    alert('Error: ' + e.message);
                                                }
                                                saveNewPoemBtn.disabled = false;
                                            };
                                        }
                                        // Person
                                        var addNewPersonBtn = document.getElementById('addNewPersonBtn');
                                        var addNewPersonModal = document.getElementById('addNewPersonModal');
                                        var saveNewPersonBtn = document.getElementById('saveNewPersonBtn');
                                        var addNewPersonInput = document.getElementById('addNewPersonInput');
                                        var peopleSelect = document.getElementById('related_people');
                                        if (addNewPersonBtn && addNewPersonModal && saveNewPersonBtn && addNewPersonInput && peopleSelect) {
                                            addNewPersonBtn.onclick = function() {
                                                $(addNewPersonModal).modal('show');
                                                addNewPersonInput.value = '';
                                                setTimeout(function() { addNewPersonInput.focus(); }, 300);
                                            };
                                            saveNewPersonBtn.onclick = async function() {
                                                var newPerson = addNewPersonInput.value.trim();
                                                if (!newPerson) {
                                                    alert('Please enter a person name!');
                                                    return;
                                                }
                                                saveNewPersonBtn.disabled = true;
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
                                                        $(addNewPersonModal).modal('hide');
                                                        addNewPersonInput.value = '';
                                                        if (window.Swal) Swal.fire({icon:'success',title:'Success',text:'Person added!',timer:1200,showConfirmButton:false});
                                                    } else {
                                                        alert('Failed to add person!');
                                                    }
                                                } catch (e) {
                                                    alert('Error: ' + e.message);
                                                }
                                                saveNewPersonBtn.disabled = false;
                                            };
                                        }
                                        // Film
                                        var addNewFilmBtn = document.getElementById('addNewFilmBtn');
                                        var addNewFilmModal = document.getElementById('addNewFilmModal');
                                        var saveNewFilmBtn = document.getElementById('saveNewFilmBtn');
                                        var addNewFilmInput = document.getElementById('addNewFilmInput');
                                        var filmsSelect = document.getElementById('films');
                                        if (addNewFilmBtn && addNewFilmModal && saveNewFilmBtn && addNewFilmInput && filmsSelect) {
                                            addNewFilmBtn.onclick = function() {
                                                $(addNewFilmModal).modal('show');
                                                addNewFilmInput.value = '';
                                                setTimeout(function() { addNewFilmInput.focus(); }, 300);
                                            };
                                            saveNewFilmBtn.onclick = async function() {
                                                var newFilm = addNewFilmInput.value.trim();
                                                if (!newFilm) {
                                                    alert('Please enter a film title!');
                                                    return;
                                                }
                                                saveNewFilmBtn.disabled = true;
                                                try {
                                                    var res = await fetch('<?= base_url('SongController/ajax_create_film') ?>', {
                                                        method: 'POST',
                                                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                        body: 'main_title=' + encodeURIComponent(newFilm)
                                                    });
                                                    var data = await res.json();
                                                    if (data && data.success) {
                                                        var option = document.createElement('option');
                                                        option.value = data.id;
                                                        option.text = data.main_title || newFilm;
                                                        option.selected = true;
                                                        filmsSelect.add(option);
                                                        if (window.jQuery && $('#films').length) $('#films').trigger('change');
                                                        $(addNewFilmModal).modal('hide');
                                                        addNewFilmInput.value = '';
                                                        if (window.Swal) Swal.fire({icon:'success',title:'Success',text:'Film added!',timer:1200,showConfirmButton:false});
                                                    } else {
                                                        alert('Failed to add film!');
                                                    }
                                                } catch (e) {
                                                    alert('Error: ' + e.message);
                                                }
                                                saveNewFilmBtn.disabled = false;
                                            };
                                        }
                                        // Film Episode
                                        var addNewEpisodeBtn = document.getElementById('addNewEpisodeBtn');
                                        var addNewEpisodeModal = document.getElementById('addNewEpisodeModal');
                                        var saveNewEpisodeBtn = document.getElementById('saveNewEpisodeBtn');
                                        var addNewEpisodeInput = document.getElementById('addNewEpisodeInput');
                                        var episodesSelect = document.getElementById('film_episodes');
                                        if (addNewEpisodeBtn && addNewEpisodeModal && saveNewEpisodeBtn && addNewEpisodeInput && episodesSelect) {
                                            addNewEpisodeBtn.onclick = function() {
                                                $(addNewEpisodeModal).modal('show');
                                                addNewEpisodeInput.value = '';
                                                setTimeout(function() { addNewEpisodeInput.focus(); }, 300);
                                            };
                                            saveNewEpisodeBtn.onclick = async function() {
                                                var newEpisode = addNewEpisodeInput.value.trim();
                                                if (!newEpisode) {
                                                    alert('Please enter an episode title!');
                                                    return;
                                                }
                                                saveNewEpisodeBtn.disabled = true;
                                                try {
                                                    var res = await fetch('<?= base_url('SongController/ajax_create_episode') ?>', {
                                                        method: 'POST',
                                                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                        body: 'film_episode_title=' + encodeURIComponent(newEpisode)
                                                    });
                                                    var data = await res.json();
                                                    if (data && data.success) {
                                                        var option = document.createElement('option');
                                                        option.value = data.id;
                                                        option.text = data.film_episode_title || newEpisode;
                                                        option.selected = true;
                                                        episodesSelect.add(option);
                                                        if (window.jQuery && $('#film_episodes').length) $('#film_episodes').trigger('change');
                                                        $(addNewEpisodeModal).modal('hide');
                                                        addNewEpisodeInput.value = '';
                                                        if (window.Swal) Swal.fire({icon:'success',title:'Success',text:'Episode added!',timer:1200,showConfirmButton:false});
                                                    } else {
                                                        alert('Failed to add episode!');
                                                    }
                                                } catch (e) {
                                                    alert('Error: ' + e.message);
                                                }
                                                saveNewEpisodeBtn.disabled = false;
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
                                    <input type="text" name="metaTitle" class="col-md-4" id="metaTitle" value="<?= isset($song['metaTitle']) ? htmlspecialchars($song['metaTitle']) : '' ?>" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>⊙ Meta Keywords</label>
                                    <input type="text" name="metaKeyword" class="col-md-4" id="metaKeyword" value="<?= isset($song['metaKeyword']) ? $song['metaKeyword'] : '' ?>" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>⊙ Meta Description</label>
                                    <textarea class="form-control col-md-8" name="metaDescription" id="metaDescription" rows="5" placeholder="Enter Meta Description"><?= htmlspecialchars($metaDescription) ?></textarea>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Publish Status</label>
                                    <select class="form-control col-md-4" name="publish" id="publish">
                                        <option value="false" <?= $select_publish=='false'?'selected':'' ?>>No</option>
                                        <option value="true" <?= $select_publish=='true'?'selected':'' ?>>Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        

                        <div class="save-btn-container">
                            <?= admin_edit_preview_button(isset($song) ? $song : null) ?>
                            <button type="submit" class="btn btn-primary save-btn mt-3">Save</button>
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

<!-- Success Popup Modal -->
<!-- Success Popup Modal -->


<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
<script>
    if (window.angular) {
        try {
            angular.module('cartoonApp');
        } catch (e) {
            angular.module('cartoonApp', []);
        }
    }

    // Initialize AngularJS module and controller
    if (window.angular) {
    angular.module('songApp', []).controller('SongController', function($scope) {
        $scope.song = {
            umbrellaTitle: '',
            songText: {
                original: '',
                translated: '',
                notes: '',
                meaning: ''
            }
        }; // Initialize song object
        $scope.editModal = {
            originalTitle: '',
            transliteration: '',
            translation: ''
        };
        $scope.addModal = {
            originalTitle: '',
            transliteration: '',
            translation: ''
        };
        $scope.showEditModal = false;
        $scope.showAddModal = false;

        // Function to update Umbrella Title
        $scope.updateUmbrellaTitle = function() {
            if ($scope.editModal.originalTitle) {
                $scope.song.umbrellaTitle = $scope.editModal.originalTitle;
                var select = document.querySelector('select[ng-model="song.umbrellaTitle"]');
                var optionExists = Array.from(select.options).some(opt => opt.value === $scope.editModal.originalTitle);
                if (!optionExists) {
                    var newOption = document.createElement('option');
                    newOption.value = $scope.editModal.originalTitle;
                    newOption.text = $scope.editModal.originalTitle;
                    select.appendChild(newOption);
                }
            }
            $scope.showEditModal = false;
            $scope.editModal = { originalTitle: '', transliteration: '', translation: '' };
        };

        // Function to add new Umbrella Title
        $scope.addUmbrellaTitle = function() {
            if ($scope.addModal.originalTitle) {
                var select = document.querySelector('select[ng-model="song.umbrellaTitle"]');
                var optionExists = Array.from(select.options).some(opt => opt.value === $scope.addModal.originalTitle);
                if (!optionExists) {
                    var newOption = document.createElement('option');
                    newOption.value = $scope.addModal.originalTitle;
                    newOption.text = $scope.addModal.originalTitle;
                    select.appendChild(newOption);
                }
                $scope.song.umbrellaTitle = $scope.addModal.originalTitle;
            }
            $scope.showAddModal = false;
            $scope.addModal = { originalTitle: '', transliteration: '', translation: '' };
        };

        // Function to delete title
        $scope.deleteTitle = function() {
            var select = document.querySelector('select[ng-model="song.umbrellaTitle"]');
            var selectedOption = select.options[select.selectedIndex];
            if (selectedOption && selectedOption.value) {
                select.removeChild(selectedOption);
                $scope.song.umbrellaTitle = '';
            }
        };

        // Validation functions
        $scope.isEmpty = function(value) {
            return !value || value.trim() === '';
        };

        $scope.isMediaUrlEmpty = function() {
            return $scope.isEmpty($scope.song.youtubeVideoId) && $scope.isEmpty($scope.song.soundCloudTrackId);
        };

        // Save data function (placeholder)
        $scope.saveData = function() {
            // Implement save logic here (e.g., send data to server)
            console.log('Saving song data:', $scope.song);
        };
    });

    // Add [Entity] Button Handlers
    $(document).ready(function() {
        // Helper for modal add
        // Remove modal popup functionality for Related Content add buttons
        // $('#addKeywordBtn').off('click');
        // $('#addSongBtn').off('click');
        // $('#addReflectionBtn').off('click');
        // $('#addPoemBtn').off('click');
        // $('#addPersonBtn').off('click');
        // $('#addFilmBtn').off('click');
        // $('#addEpisodeBtn').off('click');
        // Save logic remains unchanged
        $('#saveKeywordBtn').on('click', function() {
            var newKeyword = $('#newKeywordInput').val().trim();
            if (newKeyword) {
                var newOption = new Option(newKeyword, 'new_' + newKeyword, true, true);
                $('#relatedkeywords').append(newOption).trigger('change');
                $('#addKeywordModal').modal('hide');
                $('#newKeywordInput').val('');
            }
        });
        $('#saveSongBtn').on('click', function() {
            var newSong = $('#newSongInput').val().trim();
            if (newSong) {
                var newOption = new Option(newSong, 'new_' + newSong, true, true);
                $('#related_songs').append(newOption).trigger('change');
                $('#addSongModal').modal('hide');
                $('#newSongInput').val('');
            }
        });
        $('#saveReflectionBtn').on('click', function() {
            var newReflection = $('#newReflectionInput').val().trim();
            if (newReflection) {
                var newOption = new Option(newReflection, 'new_' + newReflection, true, true);
                $('#reflections').append(newOption).trigger('change');
                $('#addReflectionModal').modal('hide');
                $('#newReflectionInput').val('');
            }
        });
        $('#savePoemBtn').on('click', function() {
            var newPoem = $('#newPoemInput').val().trim();
            if (newPoem) {
                var newOption = new Option(newPoem, 'new_' + newPoem, true, true);
                $('#relatedpoems').append(newOption).trigger('change');
                $('#addPoemModal').modal('hide');
                $('#newPoemInput').val('');
            }
        });
        $('#savePersonBtn').on('click', function() {
            var newPerson = $('#newPersonInput').val().trim();
            if (newPerson) {
                var newOption = new Option(newPerson, 'new_' + newPerson, true, true);
                $('#related_people').append(newOption).trigger('change');
                $('#addPersonModal').modal('hide');
                $('#newPersonInput').val('');
            }
        });
        $('#saveFilmBtn').on('click', function() {
            var newFilm = $('#newFilmInput').val().trim();
            if (newFilm) {
                var newOption = new Option(newFilm, 'new_' + newFilm, true, true);
                $('#films').append(newOption).trigger('change');
                $('#addFilmModal').modal('hide');
                $('#newFilmInput').val('');
            }
        });
        $('#saveEpisodeBtn').on('click', function() {
            var newEpisode = $('#newEpisodeInput').val().trim();
            if (newEpisode) {
                var newOption = new Option(newEpisode, 'new_' + newEpisode, true, true);
                $('#film_episodes').append(newOption).trigger('change');
                $('#addEpisodeModal').modal('hide');
                $('#newEpisodeInput').val('');
            }
        });
    });
    }


        // --- Umbrella Title Add/Edit/Delete Modal Logic ---
        document.addEventListener('DOMContentLoaded', function() {
            // Open Add modal
            var addBtnUmbrella = document.getElementById('addBtn');
            if (addBtnUmbrella) {
                addBtnUmbrella.onclick = function() {
                    if (window.jQuery) { $('#addUmbrellaModal').modal('show'); }
                    var t = document.getElementById('addUmbrellaTransliteration');
                    var tr = document.getElementById('addUmbrellaTranslation');
                    var o = document.getElementById('addUmbrellaOriginal');
                    if (t) t.value = '';
                    if (tr) tr.value = '';
                    if (o) o.value = '';
                    if (t) setTimeout(function(){ t.focus(); }, 100);
                };
            }
            function getFirstUmbrellaTitleId() {
                var $s = window.jQuery ? window.jQuery('#umbrellaTitle') : null;
                if ($s && $s.length) {
                    var v = $s.val();
                    if (Array.isArray(v) && v.length) return String(v[0]);
                    if (v) return String(v);
                }
                var sel = document.getElementById('umbrellaTitle');
                if (!sel) return '';
                var opt = sel.options[sel.selectedIndex];
                return opt && opt.value ? String(opt.value) : '';
            }
            var editBtnUmbrella = document.getElementById('editBtn');
            if (editBtnUmbrella) {
                editBtnUmbrella.onclick = async function() {
                    var id = getFirstUmbrellaTitleId();
                    if (!id || !/^\d+$/.test(id)) {
                        Swal.fire({icon:'warning', title:'Select one umbrella title to edit'});
                        return;
                    }
                    var panel = document.getElementById('umbrellaTitleEditPanel');
                    try {
                        var res = await fetch('<?= base_url('SongController/ajax_get_title_row') ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'id=' + encodeURIComponent(id)
                        });
                        var data = await res.json();
                        if (!data || !data.success) {
                            document.getElementById('editUmbrellaInput').value = document.getElementById('umbrellaTitle').options[document.getElementById('umbrellaTitle').selectedIndex].text;
                            if (window.jQuery) { $('#editUmbrellaModal').modal('show'); }
                            return;
                        }
                        document.getElementById('umbrellaEditId').value = String(data.id);
                        document.getElementById('umbrellaEditOriginal').value = data.original_title || '';
                        document.getElementById('umbrellaEditTranslit').value = data.english_transliteration || '';
                        document.getElementById('umbrellaEditTrans').value = data.english_translation || '';
                        panel.style.display = 'block';
                        panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    } catch (e) {
                        Swal.fire({icon:'error', title:'Error', text: e.message || String(e)});
                    }
                };
            }
            var umbCancel = document.getElementById('umbrellaEditCancelBtn');
            if (umbCancel) {
                umbCancel.onclick = function() {
                    document.getElementById('umbrellaTitleEditPanel').style.display = 'none';
                };
            }
            var umbSave = document.getElementById('umbrellaEditSaveBtn');
            if (umbSave) {
                umbSave.onclick = async function() {
                    var id = document.getElementById('umbrellaEditId').value.trim();
                    if (!id) return;
                    umbSave.disabled = true;
                    try {
                        var body = 'id=' + encodeURIComponent(id)
                            + '&original_title=' + encodeURIComponent(document.getElementById('umbrellaEditOriginal').value.trim())
                            + '&english_transliteration=' + encodeURIComponent(document.getElementById('umbrellaEditTranslit').value.trim())
                            + '&english_translation=' + encodeURIComponent(document.getElementById('umbrellaEditTrans').value.trim());
                        var res = await fetch('<?= base_url('SongController/ajax_save_title_row') ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: body
                        });
                        var data = await res.json();
                        if (data && data.success) {
                            var sel = document.getElementById('umbrellaTitle');
                            for (var i = 0; i < sel.options.length; i++) {
                                if (String(sel.options[i].value) === String(data.id)) {
                                    sel.options[i].text = data.label || data.english_transliteration || data.original_title;
                                    break;
                                }
                            }
                            if (window.jQuery && window.jQuery('#umbrellaTitle').length) {
                                window.jQuery('#umbrellaTitle').trigger('change');
                            }
                            document.getElementById('umbrellaTitleEditPanel').style.display = 'none';
                            Swal.fire({icon:'success', title:'Saved'});
                        } else {
                            Swal.fire({icon:'error', title: (data && data.message) ? data.message : 'Save failed'});
                        }
                    } catch (e) {
                        Swal.fire({icon:'error', title:'Error', text: e.message || String(e)});
                    }
                    umbSave.disabled = false;
                };
            }
            // Add new umbrella title (AJAX) — sends transliteration, translation, original
            var saveUmbrellaEl = document.getElementById('saveUmbrellaBtn');
            if (saveUmbrellaEl) saveUmbrellaEl.onclick = async function() {
                const tEl = document.getElementById('addUmbrellaTransliteration');
                const trEl = document.getElementById('addUmbrellaTranslation');
                const oEl = document.getElementById('addUmbrellaOriginal');
                if (!tEl) return;
                const translit = tEl.value.trim();
                const translation = trEl ? trEl.value.trim() : '';
                const original = oEl ? oEl.value.trim() : '';
                if (!translit) { Swal.fire({icon:'warning',title:'Transliteration is required!'}); tEl.focus(); return; }
                this.disabled = true;
                try {
                    const body = 'title=' + encodeURIComponent(translit)
                        + '&english_transliteration=' + encodeURIComponent(translit)
                        + '&english_translation=' + encodeURIComponent(translation)
                        + '&original_title=' + encodeURIComponent(original);
                    const res = await fetch('<?= base_url('SongController/ajax_add_umbrella_title') ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: body
                    });
                    const data = await res.json();
                    if (data && data.success) {
                        const select = document.getElementById('umbrellaTitle');
                        const option = document.createElement('option');
                        option.value = (data.id != null && String(data.id) !== '') ? String(data.id) : translit;
                        option.text = data.label || translit;
                        option.selected = true;
                        select.add(option);
                        if (window.jQuery && window.jQuery('#umbrellaTitle').length) {
                            if ($('#umbrellaTitle').data('bs.multiselect') && $.fn.multiselect) {
                                $('#umbrellaTitle').multiselect('rebuild');
                            }
                            window.jQuery('#umbrellaTitle').trigger('change');
                        }
                        if (window.jQuery) { $('#addUmbrellaModal').modal('hide'); }
                        Swal.fire({icon:'success',title:'Added!'});
                    } else {
                        Swal.fire({icon:'error',title:'Failed to add!', text: (data && data.message) || ''});
                    }
                } catch(e) {
                    Swal.fire({icon:'error',title:'Error',text:e.message});
                }
                this.disabled = false;
            };
            // Legacy modal save (when title table is not used)
            var updateUmbrellaEl = document.getElementById('updateUmbrellaBtn');
            if (updateUmbrellaEl) updateUmbrellaEl.onclick = async function() {
                const input = document.getElementById('editUmbrellaInput');
                const value = input.value.trim();
                const select = document.getElementById('umbrellaTitle');
                if (!select.value) { Swal.fire({icon:'warning',title:'Select a title to edit!'}); return; }
                if (!value) { Swal.fire({icon:'warning',title:'Please enter a title!'}); return; }
                this.disabled = true;
                try {
                    const oldValue = select.value;
                    const res = await fetch('<?= base_url('SongController/ajax_update_umbrella_title') ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'old_title=' + encodeURIComponent(oldValue) + '&new_title=' + encodeURIComponent(value)
                    });
                    const data = await res.json();
                    if (data && data.success) {
                        select.options[select.selectedIndex].text = value;
                        select.options[select.selectedIndex].value = value;
                        $('#editUmbrellaModal').modal('hide');
                        Swal.fire({icon:'success',title:'Updated!'});
                    } else {
                        Swal.fire({icon:'error',title:'Failed to update!'});
                    }
                } catch(e) {
                    Swal.fire({icon:'error',title:'Error',text:e.message});
                }
                this.disabled = false;
            };
            // Delete umbrella title — route through the admin-wide safe delete helper
            // so it behaves identically to every other Delete button: confirm dialog,
            // usage check on the server (refuses if a song still references this title),
            // dropdown refresh on success. We bind via $(function) so the helper
            // (defined in inc/footer.php) is available by the time we run.
            $(function () {
                if (!window.__bindAdminDelete) return;
                __bindAdminDelete('deleteBtn', { selectId: '#umbrellaTitle', entity: 'title', label: 'Umbrella Title' });
            });
        });
        // --- End Umbrella Title Modal Logic ---

        // Year dropdown population
        const yearSelect = document.getElementById('yearSelect');
        if (yearSelect) {
            const currentYear = new Date().getFullYear();
            for (let y = currentYear; y >= 1900; y--) {
                const option = document.createElement('option');
                option.value = y;
                option.text = y;
                yearSelect.appendChild(option);
            }
        }
</script>
<script>
            // CKEditor initialization
                setTimeout(function() {
                    const editorIDs = [
                        'songLyricsOriginal',
                        'songLyricsTranslated',
                        'songLyricsNotes',
                        'songAbout',
                        'songnotes',
                        'formattedContent'
                    ];

                    editorIDs.forEach(function(id) {
                        var el = document.getElementById(id);
                        if (el && el.tagName === 'TEXTAREA') { // only replace textarea elements
                            CKEDITOR.replace(id, {
                                height: 200,
                                extraPlugins: 'colorbutton,font,justify',
                                removePlugins: 'elementspath', // 🔹 hides bottom status bar
                                resize_enabled: false, // 🔹 prevents resizing
                                toolbar: [
                                    { name: 'document', items: ['Source', '-', 'Preview'] },
                                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
                                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
                                    { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
                                    { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                                    { name: 'colors', items: ['TextColor', 'BGColor'] }
                                ]
                            });

                            // Sync CKEditor data with AngularJS model
                            CKEDITOR.instances[id].on('change', function() {
                                const data = CKEDITOR.instances[id].getData();
                                const scope = angular.element(document.querySelector('[id="' + id + '"]')).scope();

                                if (scope) {
                                    scope.$apply(function() {
                                        if (id === 'songLyricsOriginal') {
                                            scope.song.songText.original = data;
                                        } else if (id === 'songLyricsTranslated') {
                                            scope.song.songText.translated = data;
                                        } else if (id === 'songLyricsNotes') {
                                            scope.song.songText.notes = data;
                                        } else if (id === 'songAbout') {
                                            scope.song.songText.meaning = data;
                                        }
                                    });
                                }
                            });
                        }
                    });
                }, 500);

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const songFormEl = document.getElementById('songForm');
if (songFormEl) {
songFormEl.addEventListener('submit', function(e) {
    // Always re-enable both Singer and Poet fields before submit
    var singerEl = document.getElementById('singer');
    var poetEl = document.getElementById('poet');
    if (window.jQuery && $.fn && $.fn.select2) {
        $('#singer').prop('disabled', false);
        $('#poet').prop('disabled', false);
        // Also update select2 UI
        if ($('#singer').data('select2')) $('#singer').select2('enable', true);
        if ($('#poet').data('select2')) $('#poet').select2('enable', true);
    } else {
        if (singerEl) singerEl.disabled = false;
        if (poetEl) poetEl.disabled = false;
    }

    e.preventDefault(); // stop form submit

    // Only require mandatory fields if publish is 'true'
    const publishEl = document.getElementById('publish');
    const isPublish = publishEl && (publishEl.value === 'true' || publishEl.value === true);

    if (isPublish) {
        const fields = [
            { id: 'Songtitle_transliteration', name: 'Song Title - Transliteration' },
            { id: 'songtitletraan', name: 'Song Title - Translated' },
            { id: 'singer', name: 'Singer', multi: true },
            { id: 'poet', name: 'Poet', multi: true },
            { id: 'year', name: 'Year' },
            { id: 'location', name: 'Location' },
            { id: 'thumbnailUrl', name: 'Thumbnail Image Upload' },
            { id: 'thumbnailexcerpt', name: 'Thumbnail Excerpt' }
        ];
        // Singer/Poet are mutually exclusive — only one needs to be filled, not both
        const singerEl = document.getElementById('singer');
        const poetEl = document.getElementById('poet');
        const singerHasValue = singerEl && Array.from(singerEl.selectedOptions || []).some(o => o.value && o.value.trim() !== '');
        const poetHasValue = poetEl && Array.from(poetEl.selectedOptions || []).some(o => o.value && o.value.trim() !== '');
        if (!singerHasValue && !poetHasValue) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Input',
                text: 'Please select at least one Singer or Poet',
                confirmButtonText: 'OK'
            });
            return false;
        }

        for (let field of fields) {
            if (field.id === 'singer' || field.id === 'poet') continue; // handled above
            let el = document.getElementById(field.id);
            if (!el) continue;
            if (field.id === 'thumbnailUrl') {
                let existing = document.getElementById('thumbnailUrl_existing');
                if (existing && existing.value && existing.value.trim() !== '') {
                    continue;
                }
                if (el.files && el.files.length > 0) {
                    continue;
                }
            }
            let value = el.value.trim();
            if (value === '') {
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
    }

    // Ensure CKEditor values are pushed back to textarea fields before submit.
    if (window.CKEDITOR && CKEDITOR.instances) {
        Object.keys(CKEDITOR.instances).forEach(function(instanceName) {
            CKEDITOR.instances[instanceName].updateElement();
        });
    }

    // Submit the form via AJAX
    const form = this;
    const formData = new FormData(form);
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        Swal.fire({
            icon: 'success',
            title: 'Upload Successful',
            text: 'Your song has been uploaded successfully!',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            window.location.reload();
        });
        // If user closes the alert before timer, also refresh
        setTimeout(() => { window.location.reload(); }, 2100);
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Upload Failed',
            text: 'There was an error uploading the song.'
        });
    });
});
}
</script> 

       <script>
    // Get Elements
    const editModal = document.getElementById('editModal');
    const addModal = document.getElementById('addModal');
    const addSingerModal = document.getElementById('addSingerModal');
    const addPoetModal = document.getElementById('addPoetModal');
    const addTranslatorModal = document.getElementById('addTranslatorModal');
    const addSingerBtn = document.getElementById('addSingerBtn');
    const addPoetBtn = document.getElementById('addPoetBtn');
    const addAttributedPoetBtn = document.getElementById('addAttributedPoetBtn');
    const addTranslatorBtn = document.getElementById('addTranslatorBtn');
    const closeEdit = document.getElementById('closeEdit');
    const closeAdd = document.getElementById('closeAdd');
    const closeAddSinger = document.getElementById('closeAddSinger');
    const closeAddPoet = document.getElementById('closeAddPoet');
    const closeAddAttributedPoet = document.getElementById('closeAddAttributedPoet');
    const closeAddTranslator = document.getElementById('closeAddTranslator');
    const cancelEdit = document.getElementById('cancelEdit');
    const cancelAdd = document.getElementById('cancelAdd');
    const cancelAddSinger = document.getElementById('cancelAddSinger');
    const cancelAddPoet = document.getElementById('cancelAddPoet');
    const cancelAddAttributedPoet = document.getElementById('cancelAddAttributedPoet');
    const cancelAddTranslator = document.getElementById('cancelAddTranslator');
    const updateTitle = document.getElementById('updateTitle');
    const addTitle = document.getElementById('addTitle');
    const addSingerButton = document.getElementById('addSinger');
    const addPoet = document.getElementById('addPoet');
    const addTranslator = document.getElementById('addTranslator');
    const deleteBtn = document.getElementById('deleteBtn');
    const umbrellaSelect = document.getElementById('umbrellaTitle');
    const BASE_URL = '<?= base_url() ?>';
    const singerSelect = document.getElementById('singer');
    const poetSelect = document.getElementById('poet');
    const translatorSelect = document.getElementById('translator');
        const singerRow = document.getElementById('singerRow') || (singerSelect ? singerSelect.closest('.row') : null);
        const poetRow = document.getElementById('poetRow') || (poetSelect ? poetSelect.closest('.row') : null);
        const attributedPoetRow = document.getElementById('attributedPoetRow') || (document.getElementById('attributed_poet') ? document.getElementById('attributed_poet').closest('.row') : null);

        const getSelectedValues = (selectEl) => {
            if (!selectEl) return [];
            return Array.from(selectEl.options || [])
                .filter(opt => opt.selected && String(opt.value).trim() !== '')
                .map(opt => String(opt.value));
        };

        const clearSelectValues = (selectEl) => {
            if (!selectEl) return;
            if (window.jQuery) {
                $(selectEl).val(null).trigger('change');
                return;
            }
            Array.from(selectEl.options || []).forEach(opt => { opt.selected = false; });
        };

        const setSelectDisabled = (selectEl, disabled) => {
            if (!selectEl) return;
            selectEl.disabled = !!disabled;
            if (window.jQuery) {
                var $s = $(selectEl);
                $s.prop('disabled', !!disabled);
                // Select2
                if ($s.data('select2')) {
                    if (disabled) $s.select2('close');
                }
                // Bootstrap Multiselect — refresh button to reflect disabled state
                if ($s.data('bs.multiselect') && $.fn.multiselect) {
                    $s.multiselect(disabled ? 'disable' : 'enable');
                }
            }
        };

        const attributedPoetSelect = document.getElementById('attributed_poet');
        const updateSingerPoetVisibility = (changedBy = '') => {
            // First paint / server-loaded values: don't disable fields (Select2 would look empty).
            if (changedBy === '') {
                if (poetRow) poetRow.style.removeProperty('display');
                if (singerRow) singerRow.style.removeProperty('display');
                if (attributedPoetRow) attributedPoetRow.style.removeProperty('display');
                return;
            }

            let singerValues = getSelectedValues(singerSelect);
            let poetValues = getSelectedValues(poetSelect);
            let attributedPoetValues = getSelectedValues(attributedPoetSelect);

            // If multiple fields are selected, clear the ones that were not just changed
            if (changedBy === 'singer' && singerValues.length > 0) {
                clearSelectValues(poetSelect);
                poetValues = [];
                clearSelectValues(attributedPoetSelect);
                attributedPoetValues = [];
            }
            if (changedBy === 'poet' && poetValues.length > 0) {
                clearSelectValues(singerSelect);
                singerValues = [];
                clearSelectValues(attributedPoetSelect);
                attributedPoetValues = [];
            }
            if (changedBy === 'attributed_poet' && attributedPoetValues.length > 0) {
                clearSelectValues(singerSelect);
                singerValues = [];
                clearSelectValues(poetSelect);
                poetValues = [];
            }

            // Singer is ALWAYS visible. Poet ↔ Attributed Poet are mutually exclusive (one hides the other).
            if (singerRow) singerRow.style.removeProperty('display');
            if (poetRow) poetRow.style.removeProperty('display');
            if (attributedPoetRow) attributedPoetRow.style.removeProperty('display');

            // Hide whichever of (Poet / Attributed Poet) conflicts with the current selection
            if (poetValues.length > 0) {
                if (attributedPoetRow) attributedPoetRow.style.display = 'none';
            } else if (attributedPoetValues.length > 0) {
                if (poetRow) poetRow.style.display = 'none';
            }

            // No disabling — all selects remain enabled (visibility alone enforces the rule)
            setSelectDisabled(poetSelect, false);
            setSelectDisabled(singerSelect, false);
            setSelectDisabled(attributedPoetSelect, false);
        };

        window.updateSingerPoetVisibility = updateSingerPoetVisibility;

    // Open modals
    // Remove old modal open logic for Umbrella Title
    // editBtn.onclick = () => { editModal.style.display = 'block'; };
    // addBtn.onclick = () => { addModal.style.display = 'block'; };
    if (addSingerBtn && addSingerModal) {
        addSingerBtn.onclick = () => { addSingerModal.style.display = 'flex'; };
    }
    if (addPoetBtn && addPoetModal) {
        addPoetBtn.onclick = () => { addPoetModal.style.display = 'flex'; };
    }
    if (addAttributedPoetBtn && document.getElementById('addAttributedPoetModal')) {
        addAttributedPoetBtn.onclick = () => { document.getElementById('addAttributedPoetModal').style.display = 'flex'; };
    }
    if (addTranslatorBtn && addTranslatorModal) {
        addTranslatorBtn.onclick = () => { addTranslatorModal.style.display = 'flex'; };
    }
    // Related Content modals (only bind when both trigger and modal exist in DOM)
    function songBindOpenModal(btnId, modalId) {
        var b = document.getElementById(btnId);
        var m = document.getElementById(modalId);
        if (b && m) {
            b.onclick = function () { m.style.display = 'block'; };
        }
    }
    document.addEventListener('DOMContentLoaded', function () {
    songBindOpenModal('addKeywordBtn', 'addKeywordModal');
    songBindOpenModal('addSongBtn', 'addSongModal');
    songBindOpenModal('addReflectionBtn', 'addReflectionModal');
    songBindOpenModal('addPoemBtn', 'addPoemModal');
    songBindOpenModal('addPersonBtn', 'addPersonModal');
    songBindOpenModal('addFilmBtn', 'addFilmModal');
    songBindOpenModal('addEpisodeBtn', 'addEpisodeModal');

    // Close modals for Related Content and Umbrella Title
    [
        ['addKeywordModal', 'saveKeywordBtn'],
        ['addSongModal', 'saveSongBtn'],
        ['addReflectionModal', 'saveReflectionBtn'],
        ['addPoemModal', 'savePoemBtn'],
        ['addPersonModal', 'savePersonBtn'],
        ['addFilmModal', 'saveFilmBtn'],
        ['addEpisodeModal', 'saveEpisodeBtn'],
        ['editModal', 'updateTitle'],
        ['addModal', 'addTitle']
    ].forEach(([modalId, saveBtnId]) => {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        // Close on background click
        window.addEventListener('click', function(event) {
            if (event.target === modal) modal.style.display = 'none';
        });
        // Close on Cancel button
        const cancelBtn = modal.querySelector('.btn-secondary');
        if (cancelBtn) cancelBtn.onclick = () => { modal.style.display = 'none'; };
        // Close on X button (if present)
        const closeBtn = modal.querySelector('.close');
        if (closeBtn) closeBtn.onclick = () => { modal.style.display = 'none'; };
    });

    // AJAX add logic for each Related Content entity
    document.getElementById('saveKeywordBtn').onclick = async () => {
        const btn = document.getElementById('saveKeywordBtn');
        const input = document.getElementById('newKeywordInput');
        const value = input.value.trim();
        if (!value) { alert('Please enter a keyword!'); return; }
        btn.disabled = true;
        try {
            const res = await fetch(BASE_URL + 'SongController/ajax_create_keyword', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'word_transliteration=' + encodeURIComponent(value)
            });
            const data = await res.json();
            if (data && data.success) {
                const option = document.createElement('option');
                option.value = data.id;
                option.text = data.word_transliteration || value;
                option.selected = true;
                document.getElementById('relatedkeywords').add(option);
                if (window.jQuery && $('#relatedkeywords').length) $('#relatedkeywords').trigger('change');
                input.value = '';
                document.getElementById('addKeywordModal').style.display = 'none';
                if (window.Swal) Swal.fire({icon:'success',title:'Success',text:'Keyword added!',timer:1200,showConfirmButton:false});
            } else {
                alert('Failed to add keyword!');
            }
        } catch (e) { alert('Error: ' + e.message); }
        btn.disabled = false;
    };
    document.getElementById('saveSongBtn').onclick = async () => {
        const btn = document.getElementById('saveSongBtn');
        const input = document.getElementById('newSongInput');
        const value = input.value.trim();
        if (!value) { alert('Please enter a song title!'); return; }
        btn.disabled = true;
        try {
            const res = await fetch(BASE_URL + 'SongController/ajax_create_song', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'umbrellaTitle=' + encodeURIComponent(value)
            });
            const data = await res.json();
            if (data && data.success) {
                const option = document.createElement('option');
                option.value = data.id;
                option.text = data.umbrellaTitle || value;
                option.selected = true;
                document.getElementById('related_songs').add(option);
                if (window.jQuery && $('#related_songs').length) $('#related_songs').trigger('change');
                input.value = '';
                document.getElementById('addSongModal').style.display = 'none';
                if (window.Swal) Swal.fire({icon:'success',title:'Success',text:'Song added!',timer:1200,showConfirmButton:false});
            } else {
                alert('Failed to add song!');
            }
        } catch (e) { alert('Error: ' + e.message); }
        btn.disabled = false;
    };
    document.getElementById('saveReflectionBtn').onclick = async () => {
        const btn = document.getElementById('saveReflectionBtn');
        const input = document.getElementById('newReflectionInput');
        const value = input.value.trim();
        if (!value) { alert('Please enter a reflection title!'); return; }
        btn.disabled = true;
        try {
            const res = await fetch(BASE_URL + 'SongController/ajax_create_reflection', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'title=' + encodeURIComponent(value)
            });
            const data = await res.json();
            if (data && data.success) {
                const option = document.createElement('option');
                option.value = data.id;
                option.text = data.title || value;
                option.selected = true;
                document.getElementById('reflections').add(option);
                if (window.jQuery && $('#reflections').length) $('#reflections').trigger('change');
                input.value = '';
                document.getElementById('addReflectionModal').style.display = 'none';
                if (window.Swal) Swal.fire({icon:'success',title:'Success',text:'Reflection added!',timer:1200,showConfirmButton:false});
            } else {
                alert('Failed to add reflection!');
            }
        } catch (e) { alert('Error: ' + e.message); }
        btn.disabled = false;
    };
    document.getElementById('savePoemBtn').onclick = async () => {
        const btn = document.getElementById('savePoemBtn');
        const input = document.getElementById('newPoemInput');
        const value = input.value.trim();
        if (!value) { alert('Please enter a poem title!'); return; }
        btn.disabled = true;
        try {
            const res = await fetch(BASE_URL + 'SongController/ajax_create_poem', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'original_title=' + encodeURIComponent(value)
            });
            const data = await res.json();
            if (data && data.success) {
                const option = document.createElement('option');
                option.value = data.id;
                option.text = data.original_title || value;
                option.selected = true;
                document.getElementById('relatedpoems').add(option);
                if (window.jQuery && $('#relatedpoems').length) $('#relatedpoems').trigger('change');
                input.value = '';
                document.getElementById('addPoemModal').style.display = 'none';
                if (window.Swal) Swal.fire({icon:'success',title:'Success',text:'Poem added!',timer:1200,showConfirmButton:false});
            } else {
                alert('Failed to add poem!');
            }
        } catch (e) { alert('Error: ' + e.message); }
        btn.disabled = false;
    };
    document.getElementById('savePersonBtn').onclick = async () => {
        const btn = document.getElementById('savePersonBtn');
        const input = document.getElementById('newPersonInput');
        const value = input.value.trim();
        if (!value) { alert('Please enter a person name!'); return; }
        btn.disabled = true;
        try {
            const res = await fetch(BASE_URL + 'SongController/ajax_create_person', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'name=' + encodeURIComponent(value) + '&type_id=0'
            });
            const data = await res.json();
            if (data && data.success) {
                const option = document.createElement('option');
                option.value = data.id;
                option.text = data.fullName || value;
                option.selected = true;
                document.getElementById('related_people').add(option);
                if (window.jQuery && $('#related_people').length) $('#related_people').trigger('change');
                input.value = '';
                document.getElementById('addPersonModal').style.display = 'none';
                if (window.Swal) Swal.fire({icon:'success',title:'Success',text:'Person added!',timer:1200,showConfirmButton:false});
            } else {
                alert('Failed to add person!');
            }
        } catch (e) { alert('Error: ' + e.message); }
        btn.disabled = false;
    };
    document.getElementById('saveFilmBtn').onclick = async () => {
        const btn = document.getElementById('saveFilmBtn');
        const input = document.getElementById('newFilmInput');
        const value = input.value.trim();
        if (!value) { alert('Please enter a film title!'); return; }
        btn.disabled = true;
        try {
            const res = await fetch(BASE_URL + 'SongController/ajax_create_film', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'main_title=' + encodeURIComponent(value)
            });
            const data = await res.json();
            if (data && data.success) {
                const option = document.createElement('option');
                option.value = data.id;
                option.text = data.main_title || value;
                option.selected = true;
                document.getElementById('films').add(option);
                if (window.jQuery && $('#films').length) $('#films').trigger('change');
                input.value = '';
                document.getElementById('addFilmModal').style.display = 'none';
                if (window.Swal) Swal.fire({icon:'success',title:'Success',text:'Film added!',timer:1200,showConfirmButton:false});
            } else {
                alert('Failed to add film!');
            }
        } catch (e) { alert('Error: ' + e.message); }
        btn.disabled = false;
    };
    document.getElementById('saveEpisodeBtn').onclick = async () => {
        const btn = document.getElementById('saveEpisodeBtn');
        const input = document.getElementById('newEpisodeInput');
        const value = input.value.trim();
        if (!value) { alert('Please enter an episode title!'); return; }
        btn.disabled = true;
        try {
            const res = await fetch(BASE_URL + 'SongController/ajax_create_episode', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'film_episode_title=' + encodeURIComponent(value)
            });
            const data = await res.json();
            if (data && data.success) {
                const option = document.createElement('option');
                option.value = data.id;
                option.text = data.film_episode_title || value;
                option.selected = true;
                document.getElementById('film_episodes').add(option);
                if (window.jQuery && $('#film_episodes').length) $('#film_episodes').trigger('change');
                input.value = '';
                document.getElementById('addEpisodeModal').style.display = 'none';
                if (window.Swal) Swal.fire({icon:'success',title:'Success',text:'Episode added!',timer:1200,showConfirmButton:false});
            } else {
                alert('Failed to add episode!');
            }
        } catch (e) { alert('Error: ' + e.message); }
        btn.disabled = false;
    };
    });

    // Close modals
    if (closeEdit) closeEdit.onclick = () => { if (editModal) editModal.style.display = 'none'; };
    if (cancelEdit) cancelEdit.onclick = () => { if (editModal) editModal.style.display = 'none'; };
    if (closeAdd) closeAdd.onclick = () => { if (addModal) addModal.style.display = 'none'; };
    if (cancelAdd) cancelAdd.onclick = () => { if (addModal) addModal.style.display = 'none'; };
    [closeAddSinger, cancelAddSinger].forEach(btn => {
        if (btn && addSingerModal) btn.onclick = () => { addSingerModal.style.display = 'none'; };
    });
    [closeAddPoet, cancelAddPoet].forEach(btn => {
        if (btn && addPoetModal) btn.onclick = () => { addPoetModal.style.display = 'none'; };
    });
    [closeAddTranslator, cancelAddTranslator].forEach(btn => {
        if (btn && addTranslatorModal) btn.onclick = () => { addTranslatorModal.style.display = 'none'; };
    });

    // Outside click close
    window.addEventListener('click', function (event) {
      if (editModal && event.target === editModal) editModal.style.display = 'none';
      if (addModal && event.target === addModal) addModal.style.display = 'none';
      if (addSingerModal && event.target === addSingerModal) addSingerModal.style.display = 'none';
      if (addPoetModal && event.target === addPoetModal) addPoetModal.style.display = 'none';
      if (addTranslatorModal && event.target === addTranslatorModal) addTranslatorModal.style.display = 'none';
    });

    // Update title (legacy editModal only)
    if (updateTitle && editModal) {
        updateTitle.onclick = () => {
            alert("✅ Umbrella title updated successfully!");
            editModal.style.display = 'none';
        };
    }

    // Add new title (legacy addModal only)
    if (addTitle && addModal && umbrellaSelect) {
        addTitle.onclick = () => {
            const newTitle = document.getElementById('addOriginalTitle').value.trim();
            if (newTitle) {
                const option = document.createElement('option');
                option.text = newTitle;
                umbrellaSelect.add(option);
                alert("✅ New title added successfully!");
                addModal.style.display = 'none';
            } else {
                alert("⚠️ Please enter a title before adding!");
            }
        };
    }

    // Add new singer (persist to DB via AJAX)
    if (addSingerButton && addSingerModal && singerSelect) {
        addSingerButton.onclick = async () => {
            if (addSingerButton._isRunning) {
                console.error('addSingerButton handler is already running!');
                return;
            }
            addSingerButton._isRunning = true;
            console.log('addSingerButton.onclick called');
            try {
                const btn = addSingerButton;
                const newSinger = document.getElementById('addSingerName').value.trim();
                const newLink = document.getElementById('addSingerLink').value.trim();
                if (!newSinger) {
                    alert("⚠️ Please enter a name before adding!");
                    return;
                }
                btn.disabled = true;
                const res = await fetch(BASE_URL + 'person/ajax-create', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'name=' + encodeURIComponent(newSinger) + '&hyperlink=' + encodeURIComponent(newLink) + '&type_id=1'
                });
                const data = await res.json();
                if (data && data.success) {
                    // Add the option to the underlying <select> if it isn't there yet
                    if (!singerSelect.querySelector('option[value="' + String(data.id).replace(/(["\\])/g, '\\$1') + '"]')) {
                        const option = document.createElement('option');
                        option.value = data.id;
                        option.text = data.fullName || newSinger;
                        singerSelect.add(option);
                    }
                    // Rebuild the visible widget (Bootstrap Multiselect / Select2) AND select the new id.
                    // Uses the shared helper which correctly detects the widget regardless of its data key.
                    if (window.__adminRefreshSelect) {
                        window.__adminRefreshSelect('#singer', String(data.id));
                    } else if (window.jQuery && $('#singer').length) {
                        $('#singer').trigger('change');
                    }
                    updateSingerPoetVisibility('singer');
                    document.getElementById('addSingerName').value = '';
                    document.getElementById('addSingerLink').value = '';
                    addSingerModal.style.display = 'none';
                    // Show success message (not alert, use Swal if available)
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'New singer added and selected!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        alert('✅ New singer added and selected!');
                    }
                } else {
                    // Only show real error, not stack overflow
                    let msg = (data && data.message ? data.message : 'Unknown error');
                    if (window.Swal) {
                        Swal.fire({ icon: 'error', title: 'Failed', text: msg });
                    } else {
                        alert('❌ Failed to add singer: ' + msg);
                    }
                }
            } catch (e) {
                // Only show real error, not stack overflow
                let msg = e && e.message ? e.message : e;
                if (window.Swal) {
                    Swal.fire({ icon: 'error', title: 'Error', text: msg });
                } else {
                    alert('❌ Error adding singer: ' + msg);
                }
            } finally {
                addSingerButton._isRunning = false;
                addSingerButton.disabled = false;
            }
        };
    }

    // Add new poet (persist to DB via AJAX)
    if (addPoet && poetSelect && addPoetModal) addPoet.onclick = async () => {
      const btn = addPoet;
      const newPoet = document.getElementById('addPoetName').value.trim();
      const newLink = document.getElementById('addPoetLink').value.trim();
      if (!newPoet) {
        alert("⚠️ Please enter a name before adding!");
        return;
      }
      try {
        btn.disabled = true;
        const res = await fetch(BASE_URL + 'person/ajax-create', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'name=' + encodeURIComponent(newPoet) + '&hyperlink=' + encodeURIComponent(newLink) + '&type_id=2'
        });
        const data = await res.json();
        if (data && data.success) {
          if (!poetSelect.querySelector('option[value="' + String(data.id).replace(/(["\\])/g, '\\$1') + '"]')) {
            const option = document.createElement('option');
            option.value = data.id;
            option.text = data.fullName || newPoet;
            poetSelect.add(option);
          }
          if (window.__adminRefreshSelect) {
            window.__adminRefreshSelect('#poet', String(data.id));
          } else if (window.jQuery && $('#poet').length) {
            $('#poet').trigger('change');
          }
                    updateSingerPoetVisibility('poet');
          alert("✅ New poet added and selected!");
          document.getElementById('addPoetName').value = '';
          document.getElementById('addPoetLink').value = '';
          addPoetModal.style.display = 'none';
        } else {
          alert("❌ Failed to add poet: " + (data && data.message ? data.message : 'Unknown error'));
        }
      } catch (e) {
        alert("❌ Error adding poet: " + e.message);
      } finally {
        btn.disabled = false;
      }
    };

        // Add new translator as person row (same as couplet; songs.translator stores person ids)
        if (addTranslator && translatorSelect) {
            addTranslator.onclick = async (event) => {
                if (event) event.preventDefault();

                const btn = addTranslator;
                const newTranslator = document.getElementById('addTranslatorName').value.trim();
                const newLink = document.getElementById('addTranslatorLink').value.trim();

                if (!newTranslator) {
                    alert("⚠️ Please enter a name before adding!");
                    return;
                }

                try {
                    btn.disabled = true;

                    const res = await fetch(BASE_URL + 'person/ajax-create', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'name=' + encodeURIComponent(newTranslator) + '&hyperlink=' + encodeURIComponent(newLink)
                    });

                    const data = await res.json();

                    if (data && data.success) {
                        const option = document.createElement('option');
                        option.value = data.id;
                        option.text = data.fullName || newTranslator;
                        option.selected = true;
                        translatorSelect.add(option);

                        if (window.jQuery && $('#translator').length) {
                            $('#translator').trigger('change');
                        }

                        document.getElementById('addTranslatorName').value = '';
                        document.getElementById('addTranslatorLink').value = '';
                        alert("✅ New translator added and selected!");
                        if (addTranslatorModal) {
                            addTranslatorModal.style.display = 'none';
                        }
                    } else {
                        alert("❌ Failed to add translator: " + (data && data.message ? data.message : 'Unknown error'));
                    }
                } catch (e) {
                    alert("❌ Error adding translator: " + e.message);
                } finally {
                    btn.disabled = false;
                }
            };
        }

        // Dynamic extra translation blocks (same UX as couplet page)
        (function() {
            var addBtn = document.getElementById('addSongTranslationBtn');
            var container = document.getElementById('extraSongTranslations');
            var baseTranslator = document.getElementById('translator');
            if (!addBtn || !container || !baseTranslator) return;
            if (addBtn.dataset.translationBound === '1') return;
            addBtn.dataset.translationBound = '1';

            var extraIndex = 0;
            function buildTranslatorOptionsHtml() {
                var html = '';
                Array.prototype.forEach.call(baseTranslator.options, function(opt) {
                    if (String(opt.value || '').trim() === '') return;
                    html += '<option value="' + (opt.value || '') + '">' + (opt.text || '') + '</option>';
                });
                return html;
            }

            function addTranslationBlock() {
                extraIndex += 1;
                var blockId = 'extraSongTranslationBlock_' + extraIndex;
                var selectId = 'extra_song_translation_translator_' + extraIndex;
                var textareaId = 'extra_song_translation_text_' + extraIndex;

                var wrapper = document.createElement('div');
                wrapper.id = blockId;
                wrapper.className = 'translation-block';
                wrapper.innerHTML = ''
                    + '<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">'
                    + '  <select class="form-control select2 col-md-4" multiple="multiple" data-skip-select2="true" name="extra_translator[' + extraIndex + '][]" id="' + selectId + '" data-placeholder="Select Translators" data-select2-search-placeholder="Select Translators">'
                    +        buildTranslatorOptionsHtml()
                    + '  </select>'
                    + '  <button type="button" class="btn btn-danger btn-sm remove-extra-song-translation">Delete</button>'
                    + '</div>'
                    + '<div class="translation-editor"><textarea id="' + textareaId + '" name="extra_song_translation_text[]" class="form-control"></textarea></div>';

                container.appendChild(wrapper);

                if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2 && window.__songInitSelect2El) {
                    window.__songInitSelect2El(window.jQuery('#' + selectId));
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
                }
            }

            addBtn.addEventListener('click', function() {
                addTranslationBlock();
            });

            container.addEventListener('click', function(e) {
                if (!e.target.classList.contains('remove-extra-song-translation')) return;
                var block = e.target.closest('div[id^="extraSongTranslationBlock_"]');
                if (!block) return;
                var textarea = block.querySelector('textarea');
                if (textarea && typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[textarea.id]) {
                    CKEDITOR.instances[textarea.id].destroy(true);
                }
                block.remove();
            });
        })();

        if (window.jQuery) {
            $('#singer').on('change select2:select select2:unselect', () => {
                if (window.__suppressSingerPoetSync) return;
                updateSingerPoetVisibility('singer');
            });
            $('#poet').on('change select2:select select2:unselect', () => {
                if (window.__suppressSingerPoetSync) return;
                updateSingerPoetVisibility('poet');
            });
        } else {
            if (singerSelect) singerSelect.addEventListener('change', () => {
                updateSingerPoetVisibility('singer');
            });
            if (poetSelect) poetSelect.addEventListener('change', () => {
                updateSingerPoetVisibility('poet');
            });
        }
        updateSingerPoetVisibility();

        // Umbrella delete is bound in the earlier DOMContentLoaded block (deleteBtn).
  </script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.jQuery && $.fn && $.fn.select2) {
        // Per-field placeholder (multi-select breaks if a dummy value="" option is used with a global placeholder).
        window.__songInitSelect2El = function ($el) {
            $el = window.jQuery($el);
            if (!$el.length) return;
            var isMultiple = $el.prop('multiple');
            var ph = $el.attr('data-placeholder') || 'Select options';

            // Multi-select fields → Bootstrap Multiselect (None selected button + Select All + Search UI)
            if (isMultiple && window.jQuery.fn && window.jQuery.fn.multiselect) {
                // Pehle agar Select2 init ho chuka hai to use destroy karte hain
                if ($el.data('select2')) {
                    try { $el.select2('destroy'); } catch(e) {}
                }
                if ($el.data('bs.multiselect') || $el.next('.btn-group').find('.multiselect').length) return;
                $el.multiselect({
                    nonSelectedText: ph,
                    nSelectedText: ' selected',
                    allSelectedText: 'All selected',
                    enableFiltering: false,
                    includeSelectAllOption: false,
                    buttonWidth: '100%',
                    maxHeight: 380,
                    numberDisplayed: 5,
                    buttonContainer: '<div class="btn-group" style="width:100%;" />',
                    templates: {
                        button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown" style="display:block;position:relative;width:200px;max-width:200px;min-height:38px;max-height:220px;overflow-y:auto;overflow-x:hidden;text-align:center;cursor:pointer;border:1px solid #c6c6c6;padding:6px 24px 6px 10px;font-size:14px;border-radius:4px;color:#555;white-space:normal;word-wrap:break-word;word-break:break-word;background-color:#fff;background-image:linear-gradient(#fff,#f7f7f7);box-shadow:none;box-sizing:border-box;line-height:1.5;"><span class="multiselect-selected-text" style="display:block;white-space:normal;word-wrap:break-word;line-height:1.5;width:100%;text-align:center;"></span><b class="caret" style="position:absolute;right:8px;top:16px;display:inline-block;width:0;height:0;border-top:4px solid #333;border-right:4px solid transparent;border-left:4px solid transparent;"></b></button>'
                    },
                    buttonText: function(options, select) {
                        if (options.length === 0) return ph;
                        var labels = [];
                        options.each(function() { labels.push($(this).text().trim()); });
                        return labels.join(', ');
                    },
                    onChange: function(option, checked) { $el.trigger('change'); },
                    onSelectAll: function() { $el.trigger('change'); },
                    onDeselectAll: function() { $el.trigger('change'); },
                    onDropdownShown: function(event) {
                        var $dropdown = $el.next('.btn-group').find('.multiselect-container.dropdown-menu');
                        if (!$dropdown.length || $dropdown.find('.ms-helper-container').length) return;

                        $el.data('ms-initial-vals', $el.val() ? $el.val().slice() : []);

                        // Helper container = Reset button + search input.
                        // "Select All" / "Select None" intentionally omitted (UX request).
                        var $helper = $('<li class="ms-helper-container"></li>');
                        var $row = $('<div class="ms-action-row"></div>');
                        var $reset = $('<button type="button" class="ms-action-btn">Reset</button>');
                        $reset.on('click', function(e) {
                            e.preventDefault(); e.stopPropagation();
                            var initial = $el.data('ms-initial-vals') || [];
                            $el.multiselect('deselectAll', false);
                            if (initial.length) $el.multiselect('select', initial, false);
                            $el.multiselect('updateButtonText');
                            $el.trigger('change');
                        });
                        $row.append($reset);
                        $helper.append($row);

                        var $search = $('<input type="text" class="ms-search-input" placeholder="Search..." />');
                        $search.on('click', function(e) { e.stopPropagation(); });
                        $search.on('keydown', function(e) { e.stopPropagation(); });
                        // Strict alphabetical prefix: only show options whose label STARTS WITH
                        // the query. "a" => only items beginning with A; "ma" => only "Ma...".
                        $search.on('input', function() {
                            var q = $(this).val().toLowerCase().trim();
                            $dropdown.find('li').not('.ms-helper-container').each(function() {
                                var txt = $(this).text().toLowerCase().trim();
                                $(this).toggle(q === '' || txt.indexOf(q) === 0);
                            });
                        });
                        $helper.append($search);

                        $dropdown.prepend($helper);
                    }
                });
                return;
            }

            // Single-select → Select2 (jaisa pehle tha)
            if ($el.data('select2')) return;
            var searchPh = $el.attr('data-select2-search-placeholder') || ph;
            var hasEmptyOption = $el.find('option[value=""]').length > 0;
            var opts = { placeholder: ph };
            if (!isMultiple || hasEmptyOption) {
                opts.allowClear = true;
            }
            $el.select2(opts);
            $el.on('select2:open.songph', function () {
                setTimeout(function () {
                    var $f = window.jQuery('.select2-container--open .select2-search__field');
                    if ($f.length) $f.attr('placeholder', searchPh);
                }, 10);
            });
        };
        window.jQuery('.select2').each(function () {
            window.__songInitSelect2El(window.jQuery(this));
        });

        // Singer/Poet ka explicit init — multiple retries kyunki kabhi-kabhi init time pe DOM ready nahi hota
        var ensureSingerPoetSelect2 = function () {
            if (!(window.jQuery && window.jQuery.fn && window.jQuery.fn.select2)) return false;
            var allDone = true;
            ['#singer', '#poet'].forEach(function (sel) {
                var $s = window.jQuery(sel);
                if (!$s.length) return;
                if ($s.data('select2')) return;
                if (!$s.attr('data-placeholder')) {
                    $s.attr('data-placeholder', sel === '#singer' ? 'Select Singer' : 'Select Poet');
                }
                try {
                    window.__songInitSelect2El($s);
                } catch (e) {
                    console.warn('Select2 init retry needed for', sel, e);
                }
                if (!$s.data('select2')) allDone = false;
            });
            return allDone;
        };
        ensureSingerPoetSelect2();
        // Retry on window load (after all assets) — in case jQuery/select2 ka order race tha
        window.addEventListener('load', function () {
            ensureSingerPoetSelect2();
        });
        // Final safety: 500ms baad ek aur try
        setTimeout(ensureSingerPoetSelect2, 500);
    }

    var __selSingers = <?= json_encode(isset($selected_singers) ? $selected_singers : []) ?>;
    var __selPoets = <?= json_encode(isset($selected_poet) ? $selected_poet : []) ?>;
    if (window.jQuery && $('#singer').length && __selSingers.length) {
        window.__suppressSingerPoetSync = true;
        $('#singer').val(__selSingers.map(String));
        if ($('#singer').data('bs.multiselect') && $.fn.multiselect) {
            $('#singer').multiselect('refresh');
        }
        window.__suppressSingerPoetSync = false;
    }
    if (window.jQuery && $('#poet').length && __selPoets.length) {
        window.__suppressSingerPoetSync = true;
        $('#poet').val(__selPoets.map(String));
        if ($('#poet').data('bs.multiselect') && $.fn.multiselect) {
            $('#poet').multiselect('refresh');
        }
        window.__suppressSingerPoetSync = false;
    }

    var __selKeywords = <?= json_encode(isset($selected_keywords) ? $selected_keywords : []) ?>;
    var __selRelatedSongs = <?= json_encode(isset($selected_related_songs) ? $selected_related_songs : []) ?>;
    var __selReflectionsMulti = <?= json_encode(isset($selected_reflections) ? $selected_reflections : []) ?>;
    var __selPoems = <?= json_encode(isset($selected_couplets) ? $selected_couplets : []) ?>;
    var __selPeopleRel = <?= json_encode(isset($selected_people) ? $selected_people : []) ?>;
    var __selFilms = <?= json_encode(isset($selected_films) ? $selected_films : []) ?>;
    var __selEpisodes = <?= json_encode(isset($selected_episodes) ? $selected_episodes : []) ?>;
    function __applySongMulti(sel, vals) {
        if (!window.jQuery || !$(sel).length || !vals || !vals.length) return;
        var $el = $(sel);
        $el.val(vals.map(String));
        if ($el.data('bs.multiselect') && $.fn.multiselect) {
            $el.multiselect('refresh');
        } else {
            $el.trigger('change');
        }
    }
    __applySongMulti('#relatedkeywords', __selKeywords);
    __applySongMulti('#related_songs', __selRelatedSongs);
    __applySongMulti('#reflections', __selReflectionsMulti);
    __applySongMulti('#relatedpoems', __selPoems);
    __applySongMulti('#related_people', __selPeopleRel);
    __applySongMulti('#films', __selFilms);
    __applySongMulti('#film_episodes', __selEpisodes);
    var __selTranslators = <?= json_encode(isset($selected_translator) ? $selected_translator : []) ?>;
    __applySongMulti('#translator', __selTranslators);

    // Related Songs uses normal select2 multi-select (previous behavior).

    if (typeof updateSingerPoetVisibility === 'function') {
        updateSingerPoetVisibility();
    }
});
</script>

<script>
// Failsafe binding for Add Song Translation button
document.addEventListener('DOMContentLoaded', function () {
    var addBtn = document.getElementById('addSongTranslationBtn');
    var container = document.getElementById('extraSongTranslations');
    var baseTranslator = document.getElementById('translator');
    if (!addBtn || !container || !baseTranslator) return;
    if (addBtn.dataset.translationBound === '1') return;
    addBtn.dataset.translationBound = '1';

    var extraIndex = 0;
    function buildTranslatorOptionsHtml() {
        var html = '';
        Array.prototype.forEach.call(baseTranslator.options, function (opt) {
            if (String(opt.value || '').trim() === '') return;
            html += '<option value="' + (opt.value || '') + '">' + (opt.text || '') + '</option>';
        });
        return html;
    }

    addBtn.addEventListener('click', function () {
        extraIndex += 1;
        var blockId = 'extraSongTranslationBlockFallback_' + extraIndex;
        var selectId = 'extra_song_translation_translator_fb_' + extraIndex;
        var textareaId = 'extra_song_translation_text_fb_' + extraIndex;

        var wrapper = document.createElement('div');
        wrapper.id = blockId;
        wrapper.className = 'translation-block';
        wrapper.innerHTML = ''
            + '<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">'
            + '  <select class="form-control select2 col-md-4" multiple="multiple" data-skip-select2="true" name="extra_translator_fb[' + extraIndex + '][]" id="' + selectId + '" data-placeholder="Select Translators" data-select2-search-placeholder="Select Translators">'
            +        buildTranslatorOptionsHtml()
            + '  </select>'
            + '  <button type="button" class="btn btn-danger btn-sm remove-extra-song-translation-fb">Delete</button>'
            + '</div>'
            + '<div class="translation-editor"><textarea id="' + textareaId + '" name="extra_song_translation_text[]" class="form-control"></textarea></div>';

        container.appendChild(wrapper);

        if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2 && window.__songInitSelect2El) {
            window.__songInitSelect2El(window.jQuery('#' + selectId));
        }
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace(textareaId, {
                height: 200,
                extraPlugins: 'colorbutton,font,justify'
            });
        }
    });

    container.addEventListener('click', function (e) {
        if (!e.target.classList.contains('remove-extra-song-translation-fb')) return;
        var block = e.target.closest('div[id^="extraSongTranslationBlockFallback_"]');
        if (!block) return;
        var textarea = block.querySelector('textarea');
        if (textarea && typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[textarea.id]) {
            CKEDITOR.instances[textarea.id].destroy(true);
        }
        block.remove();
    });
});
</script>


<!-- Modals for Add [Entity] -->
<!-- Add Keyword Modal -->
<div class="modal fade" id="addKeywordModal" tabindex="-1" role="dialog" aria-labelledby="addKeywordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKeywordModalLabel">Add Keyword</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="newKeywordInput" placeholder="Enter new keyword">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveKeywordBtn">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- Add Keyword Modal removed as per request -->
<!-- Add Reflection Modal -->
<div class="modal fade" id="addReflectionModal" tabindex="-1" role="dialog" aria-labelledby="addReflectionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReflectionModalLabel">Add Reflection</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="newReflectionInput" placeholder="Enter new reflection title">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveReflectionBtn">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- Add Poem Modal -->
<div class="modal fade" id="addPoemModal" tabindex="-1" role="dialog" aria-labelledby="addPoemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPoemModalLabel">Add Poem</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="newPoemInput" placeholder="Enter new poem title">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="savePoemBtn">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- Add Person Modal -->
<div class="modal fade" id="addPersonModal" tabindex="-1" role="dialog" aria-labelledby="addPersonModalLabel" aria-hidden="true">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addPersonModalLabel">Add Person</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <input type="text" class="form-control" id="newPersonInput" placeholder="Enter new person name">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="savePersonBtn">Save</button>
        </div>
    </div>
</div>
<!-- Add Film Modal -->
<div class="modal fade" id="addFilmModal" tabindex="-1" role="dialog" aria-labelledby="addFilmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFilmModalLabel">Add Film</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="newFilmInput" placeholder="Enter new film title">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveFilmBtn">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- Add Episode Modal -->
<div class="modal fade" id="addEpisodeModal" tabindex="-1" role="dialog" aria-labelledby="addEpisodeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEpisodeModalLabel">Add Film Episode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="newEpisodeInput" placeholder="Enter new episode title">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEpisodeBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
// Move Bootstrap .modal elements to <body> (do not move custom .song-person-dialog)
document.addEventListener('DOMContentLoaded', function() {
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        if (modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }
    });
    document.querySelectorAll('.song-person-dialog').forEach(function(el) {
        if (el.parentNode !== document.body) {
            document.body.appendChild(el);
        }
    });
});
</script>
<script>
// Edit-selected wiring (admin-wide __adminEditOption helper from footer.php)
(function () {
  function bindEdit(btnId, opts) {
    var b = document.getElementById(btnId);
    if (!b) { console.warn('[bindEdit] button not found:', btnId); return; }
    b.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      // Some page-specific Keyword modal code on this view manually sets display:block
      // without adding the .show class (custom __showKwModal flow). When that state
      // leaks in, Bootstrap's .modal('show') sees it as already-open and refuses to
      // animate. Force a clean reset on the target modal before opening Edit.
      if (opts && opts.modalId) {
        var $m = window.jQuery ? window.jQuery(opts.modalId) : null;
        if ($m && $m.length) {
          try {
            // Strip any stale inline styles / classes / backdrops left over from
            // the Add-mode custom show logic.
            $m.removeClass('show').css('display', '').attr('aria-hidden', 'true').removeAttr('aria-modal');
            window.jQuery('body').removeClass('modal-open');
            window.jQuery('.modal-backdrop, #kw-modal-backdrop, #gw-modal-backdrop').remove();
            if (window.bootstrap && bootstrap.Modal) {
              var inst = bootstrap.Modal.getInstance($m[0]);
              if (inst) { try { inst.dispose(); } catch (e2) {} }
            } else if ($m.data && $m.data('bs.modal')) {
              try { $m.data('bs.modal', null); } catch (e2) {}
            }
          } catch (e3) {}
        }
      }
      if (window.__adminEditOption) {
        // Defer to next tick so the click event finishes propagating before
        // Bootstrap's outside-click listener attaches to the freshly-shown modal.
        setTimeout(function () { window.__adminEditOption(opts); }, 0);
      } else {
        alert('Edit helper not loaded yet. Please reload the page.');
      }
    });
  }
  var BASE = '<?php echo base_url(); ?>';
  bindEdit('editSingerBtn', {
    selectId: '#singer', modalId: '#addSingerModal', addSaveBtnId: '#addSinger',
    updateUrl:  BASE + 'song/ajax_update_person',
    prefillUrl: BASE + 'song/ajax_get_person',     // fetches name + hyperlink from DB
    editTitle: 'Edit Singer',
    extraPayload: { type_id: 1 },
    fields: [
      { inputId: '#addSingerName', postKey: 'name',      primary: true },
      { inputId: '#addSingerLink', postKey: 'hyperlink' }
    ]
  });
  bindEdit('editPoetBtn', {
    selectId: '#poet', modalId: '#addPoetModal', addSaveBtnId: '#addPoet',
    updateUrl:  BASE + 'song/ajax_update_person',
    prefillUrl: BASE + 'song/ajax_get_person',
    editTitle: 'Edit Poet',
    extraPayload: { type_id: 2 },
    fields: [
      { inputId: '#addPoetName', postKey: 'name',      primary: true },
      { inputId: '#addPoetLink', postKey: 'hyperlink' }
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
      { inputId: '#addAttributedPoetLink', postKey: 'hyperlink' }
    ]
  });
  bindEdit('editTranslatorBtn', {
    selectId: '#translator', modalId: '#addTranslatorModal', addSaveBtnId: '#addTranslator',
    updateUrl:  BASE + 'song/ajax_update_translator',
    prefillUrl: BASE + 'song/ajax_get_person',     // translator is a person row in the DB
    editTitle: 'Edit Translator',
    fields: [
      { inputId: '#addTranslatorName', postKey: 'name',      primary: true },
      { inputId: '#addTranslatorLink', postKey: 'hyperlink' }
    ]
  });
  bindEdit('editGlossaryWordBtn', {
    selectId: '#songglossary', modalId: '#addGlossaryWordModal', addSaveBtnId: '#saveGlossaryWordBtn',
    updateUrl:  BASE + 'song/ajax_update_glossary_word',
    prefillUrl: BASE + 'song/ajax_get_glossary_word',  // brings back all 4 fields for the edit popup
    editTitle: 'Edit Glossary Word',
    fields: [
      { inputId: '#newGlossaryOriginal',        postKey: 'word_original' },
      { inputId: '#newGlossaryTranslation',     postKey: 'word_translation' },
      { inputId: '#newGlossaryTransliteration', postKey: 'word_transliteration', primary: true },
      { inputId: '#newGlossaryMeaning',         postKey: 'glossary_meaning' }
    ]
  });
  bindEdit('editKeywordBtn', {
    selectId: '#relatedkeywords', modalId: '#addNewKeywordModal', addSaveBtnId: '#saveNewKeywordBtn',
    updateUrl:  BASE + 'song/ajax_update_keyword',
    prefillUrl: BASE + 'song/ajax_get_keyword', // fetches all 4 fields from DB so the popup is pre-filled
    editTitle:  'Edit Keyword',
    fields: [
      { inputId: '#newKeywordOriginal',        postKey: 'word_original' },
      { inputId: '#newKeywordTranslation',     postKey: 'word_translation' },
      { inputId: '#newKeywordTransliteration', postKey: 'word_transliteration', primary: true },
      { inputId: '#newKeywordMeaning',         postKey: 'glossary_meaning' }
    ]
  });

  // ----- Delete buttons (use the admin-wide __bindAdminDelete helper) -----
  // The helper is defined in inc/footer.php which is included AFTER this script
  // tag, so we have to wait until DOMContentLoaded to call it (otherwise
  // window.__bindAdminDelete is still undefined). Backend (song/ajax_delete_entity)
  // refuses to delete if the row is still referenced from other content.
  $(function () {
    if (!window.__bindAdminDelete) { console.warn('[admin-delete] helper not loaded'); return; }
    __bindAdminDelete('deleteSingerBtn',         { selectId: '#singer',          entity: 'person', label: 'Singer' });
    __bindAdminDelete('deletePoetBtn',           { selectId: '#poet',            entity: 'person', label: 'Poet' });
    __bindAdminDelete('deleteAttributedPoetBtn', { selectId: '#attributed_poet', entity: 'person', label: 'Attributed Poet' });
    __bindAdminDelete('deleteTranslatorBtn',     { selectId: '#translator',      entity: 'person', label: 'Translator' });
    __bindAdminDelete('deleteGlossaryWordBtn',   { selectId: '#songglossary',    entity: 'word',   label: 'Glossary Word' });
    __bindAdminDelete('deleteKeywordBtn',        { selectId: '#relatedkeywords', entity: 'word',   label: 'Keyword' });
  });
})();
</script>
<?php include('inc/footer.php'); ?>
