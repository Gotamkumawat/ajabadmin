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

    /* Bootstrap-multiselect fix: prevent global input styles from breaking option checkboxes */
    .multiselect-native-select .btn-group,
    .multiselect-native-select .multiselect {
        width: 100% !important;
    }
    .multiselect-native-select .multiselect {
        text-align: left;
        white-space: normal;
        min-height: 44px;
        max-height: 130px;
        overflow-y: auto;
    }
    .multiselect-native-select .multiselect .buttonLabel {
        display: inline;
        font-size: 15px;
        font-weight: 500;
    }
    .multiselect-native-select .multiselect .caret {
        float: right;
        margin-top: 8px;
    }
    .multiselect-container.dropdown-menu {
        width: 100%;
        min-width: 320px;
        max-height: 380px;
        overflow-y: auto;
    }
    .multiselect-container > li > a > label {
        padding: 6px 12px 6px 30px !important;
        font-size: 15px;
        white-space: normal;
    }
    .multiselect-container input[type="checkbox"] {
        width: auto !important;
        height: auto !important;
        padding: 0 !important;
        margin: 0 8px 0 0 !important;
        border: 0 !important;
        box-shadow: none !important;
        position: static !important;
    }
    .multiselect-container .multiselect-filter input {
        width: 100% !important;
        max-width: none !important;
    }
    .multiselect-extra-actions {
        display: flex;
        gap: 8px;
        padding: 8px 10px 4px;
    }
    .multiselect-extra-actions .btn {
        padding: 4px 10px;
        font-size: 13px;
        line-height: 1.2;
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
    $select_publish = isset($song['publish']) ? $song['publish'] : '';
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
                                    <label style="flex: 0 0 220px; padding-right: 18px;">Umbrella Title <span style="color:red">*</span></label>
                                    
                                        <?php
                                        // Convert comma-separated string to array
                                        $selected_umbrellaTitle = [];
                                        if (isset($song['umbrellaTitle']) && $song['umbrellaTitle'] !== '') {
                                            $selected_umbrellaTitle = explode(',', (string)$song['umbrellaTitle']);
                                        } elseif (isset($song['umbrella_title_id']) && $song['umbrella_title_id'] !== '') {
                                            $selected_umbrellaTitle = [(string)$song['umbrella_title_id']];
                                        }
                                        // Old DB relation: song.umbrella_title_id -> title.id
                                        $umbrella_rows = $this->db->query("SELECT id, english_transliteration, original_title FROM title ORDER BY english_transliteration ASC, original_title ASC")->result();
                                        ?>
                                        <select class="form-control select2 col-md-4" multiple="multiple" id="umbrellaTitle" name="umbrellaTitle[]">
                                            <option value="">Select Umbrella Title</option>
                                            <?php foreach ($umbrella_rows as $row): ?>
                                                <?php
                                                $label = trim((string)$row->english_transliteration);
                                                if ($label === '') { $label = trim((string)$row->original_title); }
                                                $rid = (string)$row->id;
                                                ?>
                                                <option value="<?= htmlspecialchars($rid) ?>" <?= in_array($rid, array_map('strval', $selected_umbrellaTitle), true) ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <div style="margin-left: 20px;">
                                        <button type="button" class="btn btn-info" id="editBtn">Edit</button>
                                        <button type="button" class="btn btn-success" id="addBtn">Add New</button>
                                        <button type="button" class="btn btn-danger" id="deleteBtn">Delete</button>
                                    </div>
                                </div>
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
                                                                <input type="text" class="form-control" id="addUmbrellaInput" placeholder="Enter new umbrella title">
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
                                    $singer_rows = $this->db->query("SELECT id, first_name, middle_name, last_name FROM person WHERE type = 1 ORDER BY first_name ASC, middle_name ASC, last_name ASC")->result();
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
                                    <label style="flex: 0 0 220px; padding-right: 18px;">Singer Name</label>
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="singer[]" id="singer" onchange="if(window.updateSingerPoetVisibility){window.updateSingerPoetVisibility('singer');}">
                                            <option value="">Select Singer</option>
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
                                        <button type="button" class="btn btn-success btn-sm" id="addSingerBtn">Add New</button>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="poetRow">
                            <div class="col-12">
                                <div class="form-group" style="display: flex; align-items: center; gap:10px; margin-bottom: 18px;">
                                    <label style="flex: 0 0 220px; padding-right: 18px;">Poet</label>
                                        <?php
                                        $poet_rows = $this->db->query("SELECT id, first_name, middle_name, last_name FROM person WHERE type = 2 ORDER BY first_name ASC, middle_name ASC, last_name ASC")->result();
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
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="poet[]" id="poet" onchange="if(window.updateSingerPoetVisibility){window.updateSingerPoetVisibility('poet');}">
                                            <option value="">Select Poet</option>
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
                                        <button type="button" class="btn btn-success btn-sm" id="addPoetBtn">Add New</button>
                                </div>
                            </div>
                        </div>

                        <!-- Add Singer Modal -->
                        <div class="modal" id="addSingerModal">
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
                        $existingThumbnailUrl = '';
                        if (isset($song) && is_array($song) && !empty($song['thumbnailUrl'])) {
                            $existingThumbnailUrl = trim((string) $song['thumbnailUrl']);
                        }
                        // Preview only: production base (DB path in hidden field stays unchanged for save/update).
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
                            .song-translation-stack .translation-select .select2-container {
                                width: 220px !important;
                                max-width: 100%;
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
                            #extraSongTranslations .translation-help-text {
                                color: #d71919;
                                font-style: italic;
                                margin: 4px 0 8px;
                            }
                        </style>
                        <div class="form-row song-translation-stack" style="display: flex; align-items: flex-start; margin-bottom: 18px;">
                            <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Song Lyrics (Translation)</div>
                            <div style="flex: 1;">
                                <div class="translation-main-group">
                                    <div>
                                        <button type="button" class="btn btn-primary btn-sm" id="addSongTranslationBtn">Add Song Translation</button>
                                    </div>
                                    <div class="translation-select">
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
                                <select class="form-control select2 col-md-4" multiple="multiple" name="translator[]" id="translator">
                                    <option value="">Select Translator</option>
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
                                    </div>
                                    <div class="translation-editor">
                                        <textarea id="songLyricsTranslated" name="songLyricsTranslated" class="form-control"><?= htmlspecialchars($songLyricsTranslation) ?></textarea>
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

                        <div class="form-row" style="display: flex; align-items: center; margin-bottom: 18px;">
                            <div style="flex: 0 0 220px; padding-right: 18px; font-weight: 600;">Song Glossary</div>
                            <div style="flex: 1;">
                                <textarea class="form-control col-md-8" name="songglossary" id="songglossary" rows="3"><?= isset($song['songglossary']) ? htmlspecialchars($song['songglossary']) : '' ?></textarea>
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
                                    if ($this->db->table_exists('keywords')) {
                                        $keyword_rows = $this->db->query("SELECT id, word_transliteration FROM keywords")->result();
                                    } elseif ($this->db->table_exists('word')) {
                                        $keyword_rows = $this->db->query("SELECT id, word_transliteration FROM word")->result();
                                    } elseif ($this->db->table_exists('words')) {
                                        $keyword_rows = $this->db->query("SELECT id, word AS word_transliteration FROM words")->result();
                                    } else {
                                        $keyword_rows = [];
                                    }
                                    ?>
                                    <label>⊙ Keywords</label>
                                    <div class="input-btn-group d-flex align-items-center">
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="relatedkeywords[]" id="relatedkeywords">
                                        <?php foreach ($keyword_rows as $keyword) : ?>
                                            <option value="<?= $keyword->id ?>" <?= in_array((string) $keyword->id, array_map('strval', $selected_keywords), true) ? 'selected' : '' ?>><?= htmlspecialchars($keyword->word_transliteration) ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-sm btn-success ml-2" id="addNewKeywordBtn" style="white-space:nowrap;">Add New</button>
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
                                                <input type="text" class="form-control" id="addNewKeywordInput" placeholder="Enter new keyword">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-primary" id="saveNewKeywordBtn">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                // Add New Keyword Modal logic
                                document.addEventListener('DOMContentLoaded', function() {
                                    var addNewKeywordBtn = document.getElementById('addNewKeywordBtn');
                                    var addNewKeywordModal = document.getElementById('addNewKeywordModal');
                                    var saveNewKeywordBtn = document.getElementById('saveNewKeywordBtn');
                                    var addNewKeywordInput = document.getElementById('addNewKeywordInput');
                                    var relatedkeywordsSelect = document.getElementById('relatedkeywords');
                                    if (addNewKeywordBtn && addNewKeywordModal && saveNewKeywordBtn && addNewKeywordInput && relatedkeywordsSelect) {
                                        addNewKeywordBtn.onclick = function() {
                                            $(addNewKeywordModal).modal('show');
                                            addNewKeywordInput.value = '';
                                            setTimeout(function() { addNewKeywordInput.focus(); }, 300);
                                        };
                                        saveNewKeywordBtn.onclick = async function() {
                                            var newKeyword = addNewKeywordInput.value.trim();
                                            if (!newKeyword) {
                                                alert('Please enter a keyword!');
                                                return;
                                            }
                                            saveNewKeywordBtn.disabled = true;
                                            try {
                                                // AJAX call to add keyword
                                                var res = await fetch('<?= base_url('SongController/ajax_create_keyword') ?>', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                    body: 'word_transliteration=' + encodeURIComponent(newKeyword)
                                                });
                                                var data = await res.json();
                                                if (data && data.success) {
                                                    var option = document.createElement('option');
                                                    option.value = data.id;
                                                    option.text = data.word_transliteration || newKeyword;
                                                    option.selected = true;
                                                    relatedkeywordsSelect.add(option);
                                                    if (window.jQuery && $('#relatedkeywords').length) $('#relatedkeywords').trigger('change');
                                                    $(addNewKeywordModal).modal('hide');
                                                    addNewKeywordInput.value = '';
                                                    if (window.Swal) Swal.fire({icon:'success',title:'Success',text:'Keyword added!',timer:1200,showConfirmButton:false});
                                                } else {
                                                    alert('Failed to add keyword!');
                                                }
                                            } catch (e) {
                                                alert('Error: ' + e.message);
                                            }
                                            saveNewKeywordBtn.disabled = false;
                                        };
                                    }
                                });
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
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="related_songs[]" id="related_songs">
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
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="reflections[]" id="reflections">
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
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="relatedpoems[]" id="relatedpoems">
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
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="related_people[]" id="related_people">
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
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="films[]" id="films">
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
                                        <select class="form-control select2 col-md-4" multiple="multiple" name="film_episodes[]" id="film_episodes">
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
                                                        if (window.jQuery && $('#relatedkeywords').length) $('#relatedkeywords').trigger('change');
                                                        $(addNewKeywordModal).modal('hide');
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
                                    <label>Publish Status <span style="color:red">*</span></label>
                                    <select class="form-control col-md-4" name="publish" id="publish" required>
                                        <option value="false" <?= $select_publish=='false'?'selected':'' ?>>No</option>
                                        <option value="true" <?= $select_publish=='true'?'selected':'' ?>>Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        

                        <div class="save-btn-container">
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
    });
    }


        // --- Umbrella Title Add/Edit/Delete Modal Logic ---
        document.addEventListener('DOMContentLoaded', function() {
            // Open Add modal
            document.getElementById('addBtn').onclick = function() {
                $('#addUmbrellaModal').modal('show');
                document.getElementById('addUmbrellaInput').value = '';
            };
            // Open Edit modal
            document.getElementById('editBtn').onclick = function() {
                const select = document.getElementById('umbrellaTitle');
                if (!select.value) {
                    Swal.fire({icon:'warning',title:'Select a title to edit!'}); return;
                }
                document.getElementById('editUmbrellaInput').value = select.options[select.selectedIndex].text;
                $('#editUmbrellaModal').modal('show');
            };
            // Add new umbrella title (AJAX)
            document.getElementById('saveUmbrellaBtn').onclick = async function() {
                const input = document.getElementById('addUmbrellaInput');
                const value = input.value.trim();
                if (!value) { Swal.fire({icon:'warning',title:'Please enter a title!'}); return; }
                this.disabled = true;
                try {
                    const res = await fetch('<?= base_url('SongController/ajax_add_umbrella_title') ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'title=' + encodeURIComponent(value)
                    });
                    const data = await res.json();
                    if (data && data.success) {
                        const select = document.getElementById('umbrellaTitle');
                        const option = document.createElement('option');
                        option.value = value;
                        option.text = value;
                        option.selected = true;
                        select.add(option);
                        $('#addUmbrellaModal').modal('hide');
                        Swal.fire({icon:'success',title:'Added!'});
                    } else {
                        Swal.fire({icon:'error',title:'Failed to add!'});
                    }
                } catch(e) {
                    Swal.fire({icon:'error',title:'Error',text:e.message});
                }
                this.disabled = false;
            };
            // Edit umbrella title (AJAX)
            document.getElementById('updateUmbrellaBtn').onclick = async function() {
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
            // Delete umbrella title (AJAX)
            document.getElementById('deleteBtn').onclick = async function() {
                const select = document.getElementById('umbrellaTitle');
                if (!select.value) { Swal.fire({icon:'warning',title:'Select a title to delete!'}); return; }
                if (!(await Swal.fire({title:'Delete this title?',text:select.value,icon:'question',showCancelButton:true})).isConfirmed) return;
                try {
                    const res = await fetch('<?= base_url('SongController/ajax_delete_umbrella_title') ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'title=' + encodeURIComponent(select.value)
                    });
                    const data = await res.json();
                    if (data && data.success) {
                        select.remove(select.selectedIndex);
                        Swal.fire({icon:'success',title:'Deleted!'});
                    } else {
                        Swal.fire({icon:'error',title:'Failed to delete!'});
                    }
                } catch(e) {
                    Swal.fire({icon:'error',title:'Error',text:e.message});
                }
            };
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
    });
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
                        'songglossary',
                        'formattedContent'
                    ];

                    editorIDs.forEach(function(id) {
                        if (document.getElementById(id)) { // 🟢 check if textarea exists
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
            { id: 'umbrellaTitle', name: 'Umbrella Title' },
            { id: 'Songtitle_transliteration', name: 'Song Title - Transliteration' },
            { id: 'songtitletraan', name: 'Song Title - Translated' },
            { id: 'year', name: 'Year' },
            { id: 'location', name: 'Location' },
            { id: 'thumbnailUrl', name: 'Thumbnail Image Upload' },
            { id: 'thumbnailexcerpt', name: 'Thumbnail Excerpt' },
            { id: 'publish', name: 'Publish Status' }
        ];
        for (let field of fields) {
            let el = document.getElementById(field.id);
            if (!el) continue; // skip if element not found
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
                return false; // stop submission
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
    const editBtn = document.getElementById('editBtn');
    const addBtn = document.getElementById('addBtn');
    const addSingerBtn = document.getElementById('addSingerBtn');
    const addPoetBtn = document.getElementById('addPoetBtn');
    const addTranslatorBtn = document.getElementById('addTranslatorBtn');
    const closeEdit = document.getElementById('closeEdit');
    const closeAdd = document.getElementById('closeAdd');
    const closeAddSinger = document.getElementById('closeAddSinger');
    const closeAddPoet = document.getElementById('closeAddPoet');
    const closeAddTranslator = document.getElementById('closeAddTranslator');
    const cancelEdit = document.getElementById('cancelEdit');
    const cancelAdd = document.getElementById('cancelAdd');
    const cancelAddSinger = document.getElementById('cancelAddSinger');
    const cancelAddPoet = document.getElementById('cancelAddPoet');
    const cancelAddTranslator = document.getElementById('cancelAddTranslator');
    const updateTitle = document.getElementById('updateTitle');
    const addTitle = document.getElementById('addTitle');
    const addSingerButton = document.getElementById('addSinger');
    const addPoet = document.getElementById('addPoet');
    const addTranslator = document.getElementById('addTranslator');
    const deleteBtn = document.getElementById('deleteBtn');
    const umbrellaSelect = document.getElementById('umbrellaTitle');
    const singerSelect = document.getElementById('singer');
    const poetSelect = document.getElementById('poet');
    const translatorSelect = document.getElementById('translator');
        const singerRow = document.getElementById('singerRow') || (singerSelect ? singerSelect.closest('.row') : null);
        const poetRow = document.getElementById('poetRow') || (poetSelect ? poetSelect.closest('.row') : null);

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
                $(selectEl).prop('disabled', !!disabled);
                if ($(selectEl).data('select2')) {
                    // Forcibly close dropdown if disabling
                    if (disabled) {
                        $(selectEl).select2('close');
                    }
                    $(selectEl).trigger('change.select2');
                }
            }
        };

        const updateSingerPoetVisibility = (changedBy = '') => {
            // First paint / server-loaded values: don't disable fields (Select2 would look empty).
            if (changedBy === '') {
                if (poetRow) poetRow.style.removeProperty('display');
                if (singerRow) singerRow.style.removeProperty('display');
                return;
            }

            let singerValues = getSelectedValues(singerSelect);
            let poetValues = getSelectedValues(poetSelect);

            // If both are selected, clear the one that was not just changed
            if (changedBy === 'singer' && singerValues.length > 0 && poetValues.length > 0) {
                clearSelectValues(poetSelect);
                poetValues = [];
            }
            if (changedBy === 'poet' && poetValues.length > 0 && singerValues.length > 0) {
                clearSelectValues(singerSelect);
                singerValues = [];
            }

            // Always show both rows
            if (poetRow) poetRow.style.removeProperty('display');
            if (singerRow) singerRow.style.removeProperty('display');

            // Enforce strict mutual exclusivity (only after user interaction)
            if (singerValues.length > 0) {
                setSelectDisabled(poetSelect, true);
                setSelectDisabled(singerSelect, false);
            } else if (poetValues.length > 0) {
                setSelectDisabled(singerSelect, true);
                setSelectDisabled(poetSelect, false);
            } else {
                setSelectDisabled(poetSelect, false);
                setSelectDisabled(singerSelect, false);
            }
        };

        window.updateSingerPoetVisibility = updateSingerPoetVisibility;

    // Open modals
    // Remove old modal open logic for Umbrella Title
    // editBtn.onclick = () => { editModal.style.display = 'block'; };
    // addBtn.onclick = () => { addModal.style.display = 'block'; };
    addSingerBtn.onclick = () => { addSingerModal.style.display = 'block'; };
    addPoetBtn.onclick = () => { addPoetModal.style.display = 'block'; };
    if (addTranslatorBtn) {
        addTranslatorBtn.onclick = () => { addTranslatorModal.style.display = 'block'; };
    }
    // Related Content modals
    // document.getElementById('addKeywordBtn').onclick = () => { document.getElementById('addKeywordModal').style.display = 'block'; };
    document.getElementById('addSongBtn').onclick = () => { document.getElementById('addSongModal').style.display = 'block'; };
    document.getElementById('addReflectionBtn').onclick = () => { document.getElementById('addReflectionModal').style.display = 'block'; };
    document.getElementById('addPoemBtn').onclick = () => { document.getElementById('addPoemModal').style.display = 'block'; };
    document.getElementById('addPersonBtn').onclick = () => { document.getElementById('addPersonModal').style.display = 'block'; };
    document.getElementById('addFilmBtn').onclick = () => { document.getElementById('addFilmModal').style.display = 'block'; };
    document.getElementById('addEpisodeBtn').onclick = () => { document.getElementById('addEpisodeModal').style.display = 'block'; };

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

    // Close modals
    if (closeEdit) closeEdit.onclick = () => { if (editModal) editModal.style.display = 'none'; };
    if (cancelEdit) cancelEdit.onclick = () => { if (editModal) editModal.style.display = 'none'; };
    if (closeAdd) closeAdd.onclick = () => { if (addModal) addModal.style.display = 'none'; };
    if (cancelAdd) cancelAdd.onclick = () => { if (addModal) addModal.style.display = 'none'; };
    [closeAddSinger, cancelAddSinger].forEach(btn => btn.onclick = () => addSingerModal.style.display = 'none');
    [closeAddPoet, cancelAddPoet].forEach(btn => btn.onclick = () => addPoetModal.style.display = 'none');
    [closeAddTranslator, cancelAddTranslator].forEach(btn => btn.onclick = () => addTranslatorModal.style.display = 'none');

    // Outside click close
    window.onclick = (event) => {
      if (event.target === editModal) editModal.style.display = 'none';
      if (event.target === addModal) addModal.style.display = 'none';
      if (event.target === addSingerModal) addSingerModal.style.display = 'none';
      if (event.target === addPoetModal) addPoetModal.style.display = 'none';
      if (event.target === addTranslatorModal) addTranslatorModal.style.display = 'none';
    };

    // Update title
    updateTitle.onclick = () => {
      alert("✅ Umbrella title updated successfully!");
      editModal.style.display = 'none';
    };

    // Add new title
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

    // Add new singer (persist to DB via AJAX)
    const BASE_URL = '<?= base_url() ?>';
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
                    const option = document.createElement('option');
                    option.value = data.id;
                    option.text = data.fullName || newSinger;
                    option.selected = true;
                    singerSelect.add(option);
                    if (window.jQuery && $('#singer').length) {
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

    // Add new poet (persist to DB via AJAX)
    addPoet.onclick = async () => {
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
          const option = document.createElement('option');
          option.value = data.id;
          option.text = data.fullName || newPoet;
          option.selected = true;
          poetSelect.add(option);
                    if (window.jQuery && $('#poet').length) {
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

            var extraIndex = 0;
            function buildTranslatorOptionsHtml() {
                var html = '';
                Array.prototype.forEach.call(baseTranslator.options, function(opt) {
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
                    + '<div class="translation-help-text">Please deselect translators before deleting translation</div>'
                    + '<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">'
                    + '  <select class="form-control select2 col-md-4" multiple="multiple" name="extra_translator[' + extraIndex + '][]" id="' + selectId + '" data-placeholder="Select Translators">'
                    +        buildTranslatorOptionsHtml()
                    + '  </select>'
                    + '  <button type="button" class="btn btn-danger btn-sm remove-extra-song-translation">Delete</button>'
                    + '</div>'
                    + '<div class="translation-editor"><textarea id="' + textareaId + '" name="extra_song_translation_text[]" class="form-control"></textarea></div>';

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

        // Delete selected title
    // Umbrella Title delete with AJAX
    deleteUmbrellaBtn.onclick = async () => {
        const select = document.getElementById('umbrellaTitle');
        if (!select || !select.selectedOptions.length) {
            Swal.fire({icon:'warning',title:'Select a title to delete!'}); return;
        }
        const val = select.selectedOptions[0].value;
        if (!val) { Swal.fire({icon:'warning',title:'Invalid selection!'}); return; }
        if (!(await Swal.fire({title:'Delete this title?',text:val,icon:'question',showCancelButton:true})).isConfirmed) return;
        // AJAX delete
        try {
            const res = await fetch('<?= base_url('SongController/ajax_delete_umbrella_title') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'title=' + encodeURIComponent(val)
            });
            const data = await res.json();
            if (data && data.success) {
                // Remove from select
                for (let opt of Array.from(select.options)) {
                    if (opt.value === val) select.removeChild(opt);
                }
                Swal.fire({icon:'success',title:'Deleted!'});
            } else {
                Swal.fire({icon:'error',title:'Failed',text:data && data.message ? data.message : 'Delete failed'});
            }
        } catch(e) {
            Swal.fire({icon:'error',title:'Error',text:e.message});
        }
    };
  </script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.jQuery && $.fn && $.fn.select2) {
        $('.select2').select2({
            placeholder: "Select options",
            allowClear: true
        });
    }

    var __selSingers = <?= json_encode(isset($selected_singers) ? $selected_singers : []) ?>;
    var __selPoets = <?= json_encode(isset($selected_poet) ? $selected_poet : []) ?>;
    if (window.jQuery && $('#singer').length && __selSingers.length) {
        window.__suppressSingerPoetSync = true;
        $('#singer').val(__selSingers);
        window.__suppressSingerPoetSync = false;
    }
    if (window.jQuery && $('#poet').length && __selPoets.length) {
        window.__suppressSingerPoetSync = true;
        $('#poet').val(__selPoets);
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
        $(sel).val(vals.map(String)).trigger('change');
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
            + '<div class="translation-help-text">Please deselect translators before deleting translation</div>'
            + '<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">'
            + '  <select class="form-control select2 col-md-4" multiple="multiple" name="extra_translator_fb[' + extraIndex + '][]" id="' + selectId + '" data-placeholder="Select Translators">'
            +        buildTranslatorOptionsHtml()
            + '  </select>'
            + '  <button type="button" class="btn btn-danger btn-sm remove-extra-song-translation-fb">Delete</button>'
            + '</div>'
            + '<div class="translation-editor"><textarea id="' + textareaId + '" name="extra_song_translation_text[]" class="form-control"></textarea></div>';

        container.appendChild(wrapper);

        if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
            window.jQuery('#' + selectId).select2();
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
// Move all .modal elements to <body> to ensure Bootstrap modal stacking works (same as Related Content popups)
document.addEventListener('DOMContentLoaded', function() {
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        if (modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }
    });
});
</script>
<?php include('inc/footer.php'); ?>