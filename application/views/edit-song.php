<?php
include('inc/header.php');
include('inc/sidebar.php');
?>

<head>
    <link rel="stylesheet" href="/common/lib/angular-multi-select/angular-multi-select.css">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<style>
    /* Same CSS as provided in your original code */
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
     .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.5);
    }

    /* Modal Box */
    .modal-content {
      background-color: #fff;
      margin: 10% auto;
      padding: 20px;
      border-radius: 10px;
      width: 90%;
      max-width: 500px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
      animation: fadeIn 0.3s ease-in-out;
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
    $selected_reflections = isset($song['reflections']) ? explode(',', $song['reflections']) : [];
    $selected_couplets = isset($song['couplets']) ? explode(',', $song['couplets']) : [];
    $selected_films = isset($song['films']) ? explode(',', $song['films']) : [];
    $selected_episodes = isset($song['film_episodes']) ? explode(',', $song['film_episodes']) : [];
    $selected_people = isset($song['related_people']) ? explode(',', $song['related_people']) : [];
    $show_on_landing = isset($song['showOnLandingPage']) ? $song['showOnLandingPage'] : '';
    $select_publish = isset($song['publish']) ? $song['publish'] : '';
    $songLyricsOriginal = isset($song['songLyricsOriginal']) ? $song['songLyricsOriginal'] : '';
    // Transliteration column is DB `songLyricsNotes`; Translation column is DB `songLyricsTranslated`
    $songLyricsTransliteration = isset($song['songLyricsNotes']) ? $song['songLyricsNotes'] : '';
    $songLyricsTranslation = isset($song['songLyricsTranslated']) ? $song['songLyricsTranslated'] : '';
    $songAbout = '';
    if (isset($song['about']) && trim((string) $song['about']) !== '') {
        $songAbout = $song['about'];
    } elseif (!empty($song['songLyricsMeaning'])) {
        $songAbout = $song['songLyricsMeaning'];
    }
    $metaDescription = isset($song['metaDescription']) ? $song['metaDescription'] : '';
    $selected_ref_check = isset($song['reflection']) ? trim($song['reflection']) : '';
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
                            style="padding: 3px 8px; font-size: 13px; border-radius: 4px;"
                            onclick="window.history.back();">
                            <i class="fas fa-arrow-left" style="font-size: 13px; margin-right: 4px;"></i> Back
                            </a>
                    </div>
                </div>

                  <!-- /.card-header -->
                <div class="card-body">
                    <form name="songForm" method="post" action="<?php echo base_url('SongController/save'); ?>">
                       <!-- <form id="songForm" method="post" action="<?= $form_action ?>"> -->
                             <?php if (!empty($song['id'])): ?>
                                    <input type="hidden" name="id" value="<?= $song['id'] ?>">
                                <?php endif; ?>

                        <div class="row">
                        <!-- Umbrella Title Section -->
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left:10px;">
                                    <div class="form-group">
                                        <label>Umbrella Title</label>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <select class="form-control select2" ng-model="song.umbrellaTitle" multiple="multiple" id="umbrellaTitle" name="umbrellaTitle" required>
                                                <option value="">Select Umbrella Title</option>
                                                <option>Rain Umbrella</option>
                                                <option>Sun Protection Umbrella</option>
                                                <option>Golf Umbrella</option>
                                                <option>Compact Umbrella</option>
                                                <option>Windproof Umbrella</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:30px;">
                                    <div class="form-group">
                                        <button type="button" ng-click="showEditModal = true" class="btn btn-info" tabindex="0" id="editBtn">Edit</button>
                                        <button type="button" ng-click="showAddModal = true" class="btn btn-success" tabindex="0" id="addBtn">Add New</button>
                                        <button type="button" ng-click="deleteTitle()" class="btn btn-danger" id="deleteBtn">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 🟦 Edit Modal -->
                        <div class="modal" id="editModal">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h2>Edit Umbrella Title</h2>
                                <button class="close-btn" id="closeEdit">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                <label>Original Title</label>
                                <input type="text" id="editOriginalTitle" placeholder="Enter Original Title">
                                </div>
                                <div class="form-group">
                                <label>English Transliteration</label>
                                <input type="text" id="editTransliteration" placeholder="Enter English Transliteration">
                                </div>
                                <div class="form-group">
                                <label>English Translation</label>
                                <input type="text" id="editTranslation" placeholder="Enter English Translation">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn-secondary" id="cancelEdit">Cancel</button>
                                <button class="btn-info" id="updateTitle">Update</button>
                            </div>
                            </div>
                        </div>

                        <!-- 🟩 Add Modal -->
                        <div class="modal" id="addModal">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h2>Add New Umbrella Title</h2>
                                <button class="close-btn" id="closeAdd">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                <label>Original Title</label>
                                <input type="text" id="addOriginalTitle" placeholder="Enter Original Title">
                                </div>
                                <div class="form-group">
                                <label>English Transliteration</label>
                                <input type="text" id="addTransliteration" placeholder="Enter English Transliteration">
                                </div>
                                <div class="form-group">
                                <label>English Translation</label>
                                <input type="text" id="addTranslation" placeholder="Enter English Translation">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn-secondary" id="cancelAdd">Cancel</button>
                                <button class="btn-success" id="addTitle">Add</button>
                            </div>
                            </div>
                        </div>

                        

                       

                     <!-- Replace the existing song Title section and modal -->
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left:10px;">
                                        <div class="form-group">
                                            <label>Song Title</label>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <select class="form-control select2"  multiple="multiple" id="songTitle" name="songTitle" value="<?= !empty($song) ? $song['songTitle'] : '' ?>" required>
                                                    <option value="">Select Song Title</option>
                                                    <option>Rain Song</option>
                                                    <option>Sun Protection Song</option>
                                                    <option>Golf Song</option>
                                                    <option>Compact Song</option>
                                                    <option>Windproof Song</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:30px;">
                                        <div class="form-group">
                                            <button type="button" ng-click="showEditModal = true" class="btn btn-info" tabindex="0" id="editUmbrellaBtn">Edit</button>
                                              <button type="button" ng-click="showAddModal = true" class="btn btn-success" tabindex="0" id="addUmbrellaBtn">Add New</button>
                                            <button type="button" ng-click="deleteTitle()" class="btn btn-danger">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                                                   
                        </div>

                         <div class="row">
                            <!-- /.col -->
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Song Title - Transliteration</label>
                                        <input type="text" name="Songtitle_transliteration" id="Songtitle_transliteration" value="<?= htmlspecialchars($song_transliteration) ?>" class="form-control" ng-required="song.isAuthoringComplete" required>
                                        <error-message name="Duration" show-error="song.isAuthoringComplete && isEmpty(song.duration)"></error-message>
                                    </div>
                                </div>
                            <div class="col-md-6">
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label>Song Title - Translated (required)</label>
                                    <input type="text" name="songtitletraan" id="songtitletraan" value="<?= htmlspecialchars($song_translated) ?>" class="form-control" ng-required="song.isAuthoringComplete" required>
                                    <error-message name="Duration" show-error="song.isAuthoringComplete && isEmpty(song.duration)"></error-message>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- Singers, Words, Reflections, Couplets -->
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>Singer</label>
                                <div class="multi-select-container">
                                    <?php
                                        // Edit ke time database se selected singers array me convert ho jayega
                                        $selected_singers = [];
                                        if (!empty($song['singer'])) {
                                            $selected_singers = explode(',', $song['singer']);
                                        }
                                        ?>

                                        <select class="select2" multiple="multiple" name="singer[]" id="singer" required>
                                            <option value="">None Selected</option>
                                            <option value="1" <?= in_array('1', $selected_singers) ? 'selected' : '' ?>>Arjun Kanungo</option>
                                            <option value="2" <?= in_array('2', $selected_singers) ? 'selected' : '' ?>>Prateek Kuhad</option>
                                            <option value="3" <?= in_array('3', $selected_singers) ? 'selected' : '' ?>>Anuv Jain</option>
                                            <option value="4" <?= in_array('4', $selected_singers) ? 'selected' : '' ?>>Ritviz</option>
                                            <option value="5" <?= in_array('5', $selected_singers) ? 'selected' : '' ?>>Jasleen Royal</option>
                                            <option value="6" <?= in_array('6', $selected_singers) ? 'selected' : '' ?>>Nikhil D'Souza</option>
                                            <option value="7" <?= in_array('7', $selected_singers) ? 'selected' : '' ?>>Amit Trivedi</option>
                                            <option value="8" <?= in_array('8', $selected_singers) ? 'selected' : '' ?>>Divine</option>
                                            <option value="9" <?= in_array('9', $selected_singers) ? 'selected' : '' ?>>Taba Chake</option>
                                            <option value="10" <?= in_array('10', $selected_singers) ? 'selected' : '' ?>>Yashraj Mukhate</option>
                                            <option value="11" <?= in_array('11', $selected_singers) ? 'selected' : '' ?>>Inaayat Bhatt</option>
                                            <option value="12" <?= in_array('12', $selected_singers) ? 'selected' : '' ?>>Shashwat Singh</option>
                                            <option value="13" <?= in_array('13', $selected_singers) ? 'selected' : '' ?>>Dhruv Visvanath</option>
                                            <option value="14" <?= in_array('14', $selected_singers) ? 'selected' : '' ?>>Parekh & Singh</option>
                                            <option value="15" <?= in_array('15', $selected_singers) ? 'selected' : '' ?>>Neha Bhasin</option>
                                            <option value="16" <?= in_array('16', $selected_singers) ? 'selected' : '' ?>>Kavita Seth</option>
                                            <option value="17" <?= in_array('17', $selected_singers) ? 'selected' : '' ?>>Prakriti Kakar</option>
                                            <option value="18" <?= in_array('18', $selected_singers) ? 'selected' : '' ?>>Mohit Chauhan</option>
                                            <option value="19" <?= in_array('19', $selected_singers) ? 'selected' : '' ?>>Vaibhav Saxena</option>
                                            <option value="20" <?= in_array('20', $selected_singers) ? 'selected' : '' ?>>Raghav Meattle</option>
                                        </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button onclick="selectAll(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#007bff;color:#fff;border:none;cursor:pointer;">Select All</button>
                                            <button onclick="selectNone(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#17a2b8;color:#fff;border:none;cursor:pointer;">Select None</button>
                                            <button onclick="resetSelection(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#dc3545;color:#fff;border:none;cursor:pointer;">Reset</button>
                                        </div>
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Words</label>
                                <div class="multi-select-container">
                                    <?php
                                    $selected_words = [];
                                    if (!empty($song['words'])) {
                                        $selected_words = explode(',', $song['words']);
                                    }
                                    ?>
                                    <select class="select2" multiple="multiple" name="words[]" id="words" required>
                                        <option value="">None Selected</option>
                                         <option value="1"  <?= in_array('1', $selected_words) ? 'selected' : '' ?>>Apple</option>
                                        <option value="2"  <?= in_array('2', $selected_words) ? 'selected' : '' ?>>Banana</option>
                                        <option value="3"  <?= in_array('3', $selected_words) ? 'selected' : '' ?>>Cherry</option>
                                        <option value="4"  <?= in_array('4', $selected_words) ? 'selected' : '' ?>>Date</option>
                                        <option value="5"  <?= in_array('5', $selected_words) ? 'selected' : '' ?>>Elderberry</option>
                                        <option value="6"  <?= in_array('6', $selected_words) ? 'selected' : '' ?>>Fig</option>
                                        <option value="7"  <?= in_array('7', $selected_words) ? 'selected' : '' ?>>Grape</option>
                                        <option value="8"  <?= in_array('8', $selected_words) ? 'selected' : '' ?>>Honeydew</option>
                                        <option value="9"  <?= in_array('9', $selected_words) ? 'selected' : '' ?>>Indian Fig</option>
                                        <option value="10" <?= in_array('10', $selected_words) ? 'selected' : '' ?>>Jackfruit</option>
                                        <option value="11" <?= in_array('11', $selected_words) ? 'selected' : '' ?>>Kiwi</option>
                                        <option value="12" <?= in_array('12', $selected_words) ? 'selected' : '' ?>>Lemon</option>
                                        <option value="13" <?= in_array('13', $selected_words) ? 'selected' : '' ?>>Mango</option>
                                        <option value="14" <?= in_array('14', $selected_words) ? 'selected' : '' ?>>Nectarine</option>
                                        <option value="15" <?= in_array('15', $selected_words) ? 'selected' : '' ?>>Orange</option>
                                        <option value="16" <?= in_array('16', $selected_words) ? 'selected' : '' ?>>Papaya</option>
                                        <option value="17" <?= in_array('17', $selected_words) ? 'selected' : '' ?>>Quince</option>
                                        <option value="18" <?= in_array('18', $selected_words) ? 'selected' : '' ?>>Raspberry</option>
                                        <option value="19" <?= in_array('19', $selected_words) ? 'selected' : '' ?>>Strawberry</option>
                                        <option value="20" <?= in_array('20', $selected_words) ? 'selected' : '' ?>>Tomato</option>
                                        <option value="21" <?= in_array('21', $selected_words) ? 'selected' : '' ?>>Ugli Fruit</option>
                                        <option value="22" <?= in_array('22', $selected_words) ? 'selected' : '' ?>>Vanilla</option>
                                        <option value="23" <?= in_array('23', $selected_words) ? 'selected' : '' ?>>Watermelon</option>
                                        <option value="24" <?= in_array('24', $selected_words) ? 'selected' : '' ?>>Xigua</option>
                                        <option value="25" <?= in_array('25', $selected_words) ? 'selected' : '' ?>>Yellow Passion Fruit</option>
                                        <option value="26" <?= in_array('26', $selected_words) ? 'selected' : '' ?>>Zucchini</option>
                                        <option value="27" <?= in_array('27', $selected_words) ? 'selected' : '' ?>>Almond</option>
                                        <option value="28" <?= in_array('28', $selected_words) ? 'selected' : '' ?>>Blueberry</option>
                                        <option value="29" <?= in_array('29', $selected_words) ? 'selected' : '' ?>>Cantaloupe</option>
                                        <option value="30" <?= in_array('30', $selected_words) ? 'selected' : '' ?>>Dragonfruit</option>
                                        <option value="31" <?= in_array('31', $selected_words) ? 'selected' : '' ?>>Eggfruit</option>
                                        <option value="32" <?= in_array('32', $selected_words) ? 'selected' : '' ?>>Feijoa</option>
                                        <option value="33" <?= in_array('33', $selected_words) ? 'selected' : '' ?>>Guava</option>
                                        <option value="34" <?= in_array('34', $selected_words) ? 'selected' : '' ?>>Huckleberry</option>
                                        <option value="35" <?= in_array('35', $selected_words) ? 'selected' : '' ?>>Imbe</option>
                                        <option value="36" <?= in_array('36', $selected_words) ? 'selected' : '' ?>>Jujube</option>
                                        <option value="37" <?= in_array('37', $selected_words) ? 'selected' : '' ?>>Kumquat</option>
                                        <option value="38" <?= in_array('38', $selected_words) ? 'selected' : '' ?>>Lychee</option>
                                        <option value="39" <?= in_array('39', $selected_words) ? 'selected' : '' ?>>Mulberry</option>
                                        <option value="40" <?= in_array('40', $selected_words) ? 'selected' : '' ?>>Naranjilla</option>
                                        <option value="41" <?= in_array('41', $selected_words) ? 'selected' : '' ?>>Olive</option>
                                        <option value="42" <?= in_array('42', $selected_words) ? 'selected' : '' ?>>Persimmon</option>
                                        <option value="43" <?= in_array('43', $selected_words) ? 'selected' : '' ?>>Quandong</option>
                                        <option value="44" <?= in_array('44', $selected_words) ? 'selected' : '' ?>>Rambutan</option>
                                        <option value="45" <?= in_array('45', $selected_words) ? 'selected' : '' ?>>Salak</option>
                                        <option value="46" <?= in_array('46', $selected_words) ? 'selected' : '' ?>>Tamarind</option>
                                        <option value="47" <?= in_array('47', $selected_words) ? 'selected' : '' ?>>Ugni</option>
                                        <option value="48" <?= in_array('48', $selected_words) ? 'selected' : '' ?>>Voavanga</option>
                                        <option value="49" <?= in_array('49', $selected_words) ? 'selected' : '' ?>>Wolfberry</option>
                                        <option value="50" <?= in_array('50', $selected_words) ? 'selected' : '' ?>>Ximenia</option>
                                    </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button onclick="selectAll(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#007bff;color:#fff;border:none;cursor:pointer;">Select All</button>
                                            <button onclick="selectNone(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#17a2b8;color:#fff;border:none;cursor:pointer;">Select None</button>
                                            <button onclick="resetSelection(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#dc3545;color:#fff;border:none;cursor:pointer;">Reset</button>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-3">
                                <label>Year</label>
                                <select class="form-control" name="year" required>
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

                             <!-- Location Input -->
                            <div class="col-md-3">
                                <label>Location</label>
                                <input type="text" class="form-control" value="<?= isset($song['location']) ? $song['location'] : '' ?>" name="location" id="location" placeholder="Enter Location"  required>
                            </div>

                            <div class="col-md-3">
                                <label>Reflections</label>
                                <div class="multi-select-container">
                                    <select class="select2" multiple="multiple" name="reflections[]" id="reflections" required>
                                        <option value="">None Selected</option>
                                        <option value="1" <?= in_array('1', $selected_reflections) ? 'selected' : '' ?>>Die before your death</option>
                                        <option value="2" <?= in_array('2', $selected_reflections) ? 'selected' : '' ?>>Live without regrets</option>
                                        <option value="3" <?= in_array('3', $selected_reflections) ? 'selected' : '' ?>>Chase your dreams</option>
                                        <option value="4" <?= in_array('4', $selected_reflections) ? 'selected' : '' ?>>Silence is golden</option>
                                        <option value="5" <?= in_array('5', $selected_reflections) ? 'selected' : '' ?>>Happiness is a choice</option>
                                        <option value="6" <?= in_array('6', $selected_reflections) ? 'selected' : '' ?>>Time heals everything</option>
                                        <option value="7" <?= in_array('7', $selected_reflections) ? 'selected' : '' ?>>Less is more</option>
                                        <option value="8" <?= in_array('8', $selected_reflections) ? 'selected' : '' ?>>Learn from failures</option>
                                        <option value="9" <?= in_array('9', $selected_reflections) ? 'selected' : '' ?>>Be the change</option>
                                        <option value="10" <?= in_array('10', $selected_reflections) ? 'selected' : '' ?>>Knowledge is power</option>
                                        <option value="11" <?= in_array('11', $selected_reflections) ? 'selected' : '' ?>>Patience pays off</option>
                                        <option value="12" <?= in_array('12', $selected_reflections) ? 'selected' : '' ?>>Live in the moment</option>
                                        <option value="13" <?= in_array('13', $selected_reflections) ? 'selected' : '' ?>>Actions speak louder</option>
                                        <option value="14" <?= in_array('14', $selected_reflections) ? 'selected' : '' ?>>Health is wealth</option>
                                        <option value="15" <?= in_array('15', $selected_reflections) ? 'selected' : '' ?>>Forgive and forget</option>
                                        <option value="16" <?= in_array('16', $selected_reflections) ? 'selected' : '' ?>>Mind over matter</option>
                                        <option value="17" <?= in_array('17', $selected_reflections) ? 'selected' : '' ?>>Cherish small things</option>
                                        <option value="18" <?= in_array('18', $selected_reflections) ? 'selected' : '' ?>>Gratitude changes everything</option>
                                        <option value="19" <?= in_array('19', $selected_reflections) ? 'selected' : '' ?>>Dream big, start small</option>
                                        <option value="20" <?= in_array('20', $selected_reflections) ? 'selected' : '' ?>>Focus on solutions</option>
                                        <option value="21" <?= in_array('21', $selected_reflections) ? 'selected' : '' ?>>Kindness costs nothing</option>
                                        <option value="22" <?= in_array('22', $selected_reflections) ? 'selected' : '' ?>>Strength in adversity</option>
                                        <option value="23" <?= in_array('23', $selected_reflections) ? 'selected' : '' ?>>Stay humble</option>
                                        <option value="24" <?= in_array('24', $selected_reflections) ? 'selected' : '' ?>>Embrace uncertainty</option>
                                        <option value="25" <?= in_array('25', $selected_reflections) ? 'selected' : '' ?>>Courage conquers fear</option>
                                        <option value="26" <?= in_array('26', $selected_reflections) ? 'selected' : '' ?>>Listen more, speak less</option>
                                        <option value="27" <?= in_array('27', $selected_reflections) ? 'selected' : '' ?>>Love without conditions</option>
                                        <option value="28" <?= in_array('28', $selected_reflections) ? 'selected' : '' ?>>Be yourself always</option>
                                        <option value="29" <?= in_array('29', $selected_reflections) ? 'selected' : '' ?>>Failure is a lesson</option>
                                        <option value="30" <?= in_array('30', $selected_reflections) ? 'selected' : '' ?>>Every end is a beginning</option>
                                        <option value="31" <?= in_array('31', $selected_reflections) ? 'selected' : '' ?>>Patience is a virtue</option>
                                        <option value="32" <?= in_array('32', $selected_reflections) ? 'selected' : '' ?>>Smile through pain</option>
                                        <option value="33" <?= in_array('33', $selected_reflections) ? 'selected' : '' ?>>Change is constant</option>
                                        <option value="34" <?= in_array('34', $selected_reflections) ? 'selected' : '' ?>>Time waits for none</option>
                                        <option value="35" <?= in_array('35', $selected_reflections) ? 'selected' : '' ?>>Dream, believe, achieve</option>
                                        <option value="36" <?= in_array('36', $selected_reflections) ? 'selected' : '' ?>>Stay positive</option>
                                        <option value="37" <?= in_array('37', $selected_reflections) ? 'selected' : '' ?>>Value your time</option>
                                        <option value="38" <?= in_array('38', $selected_reflections) ? 'selected' : '' ?>>Rise above negativity</option>
                                        <option value="39" <?= in_array('39', $selected_reflections) ? 'selected' : '' ?>>Learn to let go</option>
                                        <option value="40" <?= in_array('40', $selected_reflections) ? 'selected' : '' ?>>Hustle in silence</option>
                                        <option value="41" <?= in_array('41', $selected_reflections) ? 'selected' : '' ?>>Believe in miracles</option>
                                        <option value="42" <?= in_array('42', $selected_reflections) ? 'selected' : '' ?>>Every moment counts</option>
                                        <option value="43" <?= in_array('43', $selected_reflections) ? 'selected' : '' ?>>Find your purpose</option>
                                        <option value="44" <?= in_array('44', $selected_reflections) ? 'selected' : '' ?>>Be fearless</option>
                                        <option value="45" <?= in_array('45', $selected_reflections) ? 'selected' : '' ?>>Make today count</option>
                                        <option value="46" <?= in_array('46', $selected_reflections) ? 'selected' : '' ?>>Love yourself first</option>
                                        <option value="47" <?= in_array('47', $selected_reflections) ? 'selected' : '' ?>>Stay curious</option>
                                        <option value="48" <?= in_array('48', $selected_reflections) ? 'selected' : '' ?>>Small steps matter</option>
                                        <option value="49" <?= in_array('49', $selected_reflections) ? 'selected' : '' ?>>Do what you love</option>
                                        <option value="50" <?= in_array('50', $selected_reflections) ? 'selected' : '' ?>>Never stop learning</option>
                                    </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button onclick="selectAll(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#007bff;color:#fff;border:none;cursor:pointer;">Select All</button>
                                            <button onclick="selectNone(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#17a2b8;color:#fff;border:none;cursor:pointer;">Select None</button>
                                            <button onclick="resetSelection(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#dc3545;color:#fff;border:none;cursor:pointer;">Reset</button>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-3">
                                <label>Couplets</label>
                                <div class="multi-select-container">
                                    <select class="select2" multiple="multiple" name="couplets[]" id="couplets" required>
                                        <option value="">None Selected</option>
                                         <option value="1" <?= in_array('1', $selected_couplets) ? 'selected' : '' ?>>Aag lagi is vriksh ko</option>
                                        <option value="2" <?= in_array('2', $selected_couplets) ? 'selected' : '' ?>>Pankh faila parindon ke liye</option>
                                        <option value="3" <?= in_array('3', $selected_couplets) ? 'selected' : '' ?>>Sannata cha gaya gaon me</option>
                                        <option value="4" <?= in_array('4', $selected_couplets) ? 'selected' : '' ?>>Nadi beh rahi chhupke se</option>
                                        <option value="5" <?= in_array('5', $selected_couplets) ? 'selected' : '' ?>>Chand ki roshni me raaz</option>
                                        <option value="6" <?= in_array('6', $selected_couplets) ? 'selected' : '' ?>>Andheri raat ka safar</option>
                                        <option value="7" <?= in_array('7', $selected_couplets) ? 'selected' : '' ?>>Phool khilte bagiche me</option>
                                        <option value="8" <?= in_array('8', $selected_couplets) ? 'selected' : '' ?>>Hawa me khushboo ka jadoo</option>
                                        <option value="9" <?= in_array('9', $selected_couplets) ? 'selected' : '' ?>>Patte girte sarson ke khet me</option>
                                        <option value="10" <?= in_array('10', $selected_couplets) ? 'selected' : '' ?>>Suraj ki pehli kirne</option>
                                        <option value="11" <?= in_array('11', $selected_couplets) ? 'selected' : '' ?>>Barish ki boonden mitti me</option>
                                        <option value="12" <?= in_array('12', $selected_couplets) ? 'selected' : '' ?>>Raatein aur kahaniyan purani</option>
                                        <option value="13" <?= in_array('13', $selected_couplets) ? 'selected' : '' ?>>Dhoop me chhaya ki talash</option>
                                        <option value="14" <?= in_array('14', $selected_couplets) ? 'selected' : '' ?>>Samundar ki lehren geet ga rahi</option>
                                        <option value="15" <?= in_array('15', $selected_couplets) ? 'selected' : '' ?>>Pahar ki choti par khamoshi</option>
                                        <option value="16" <?= in_array('16', $selected_couplets) ? 'selected' : '' ?>>Raat ke andheron me sitare</option>
                                        <option value="17" <?= in_array('17', $selected_couplets) ? 'selected' : '' ?>>Chhoti si muskaan bade raaz chhupaye</option>
                                        <option value="18" <?= in_array('18', $selected_couplets) ? 'selected' : '' ?>>Manzil ki ore kadam</option>
                                        <option value="19" <?= in_array('19', $selected_couplets) ? 'selected' : '' ?>>Pyaar ke rang saje</option>
                                        <option value="20" <?= in_array('20', $selected_couplets) ? 'selected' : '' ?>>Dosti ki dor kabhi na toote</option>
                                        <option value="21"<?= in_array('21', $selected_couplets) ? 'selected' : '' ?>>Aasman ke neelepan me khoya</option>
                                        <option value="22" <?= in_array('22', $selected_couplets) ? 'selected' : '' ?>>Bijli chamki toofan me</option>
                                        <option value="23" <?= in_array('23', $selected_couplets) ? 'selected' : '' ?>>Saawan ke din yaadein le aaye</option>
                                        <option value="24" <?= in_array('24', $selected_couplets) ? 'selected' : '' ?>>Patang udi aasman me</option>
                                        <option value="25" <?= in_array('25', $selected_couplets) ? 'selected' : '' ?>>Naye sapne naye raaste</option>
                                        <option value="26" <?= in_array('26', $selected_couplets) ? 'selected' : '' ?>>Gulon ka rang chhup gaya</option>
                                        <option value="27" <?= in_array('27', $selected_couplets) ? 'selected' : '' ?>>Chandni me pighla sa dil</option>
                                        <option value="28" <?= in_array('28', $selected_couplets) ? 'selected' : '' ?>>Barf ke saaye tale khushi</option>
                                        <option value="29" <?= in_array('29', $selected_couplets) ? 'selected' : '' ?>>Aankhon me aansu nahi</option>
                                        <option value="30" <?= in_array('30', $selected_couplets) ? 'selected' : '' ?>>Dil ki baatein hawa sun rahi</option>
                                        
                                    </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button onclick="selectAll(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#007bff;color:#fff;border:none;cursor:pointer;">Select All</button>
                                            <button onclick="selectNone(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#17a2b8;color:#fff;border:none;cursor:pointer;">Select None</button>
                                            <button onclick="resetSelection(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#dc3545;color:#fff;border:none;cursor:pointer;">Reset</button>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Films</label>
                                <div class="multi-select-container">
                                    <select class="select2" multiple="multiple" name="films[]" id="films" required>
                                        <option value="">None Selected</option>
                                        <option value="1" <?= in_array('1', $selected_films) ? 'selected' : '' ?>>Inception</option>
                                        <option value="2" <?= in_array('2', $selected_films) ? 'selected' : '' ?>>Interstellar</option>
                                        <option value="3" <?= in_array('3', $selected_films) ? 'selected' : '' ?>>The Dark Knight</option>
                                        <option value="4" <?= in_array('4', $selected_films) ? 'selected' : '' ?>>Titanic</option>
                                        <option value="5" <?= in_array('5', $selected_films) ? 'selected' : '' ?>>Avatar</option>
                                        <option value="6" <?= in_array('6', $selected_films) ? 'selected' : '' ?>>The Shawshank Redemption</option>
                                        <option value="7" <?= in_array('7', $selected_films) ? 'selected' : '' ?>>Gladiator</option>
                                        <option value="8" <?= in_array('8', $selected_films) ? 'selected' : '' ?>>Joker</option>
                                        <option value="9" <?= in_array('9', $selected_films) ? 'selected' : '' ?>>The Godfather</option>
                                        <option value="10" <?= in_array('10', $selected_films) ? 'selected' : '' ?>>The Matrix</option>
                                        <option value="11" <?= in_array('11', $selected_films) ? 'selected' : '' ?>>Fight Club</option>
                                        <option value="12" <?= in_array('12', $selected_films) ? 'selected' : '' ?>>Forrest Gump</option>
                                        <option value="13" <?= in_array('13', $selected_films) ? 'selected' : '' ?>>Pulp Fiction</option>
                                        <option value="14" <?= in_array('14', $selected_films) ? 'selected' : '' ?>>The Lion King</option>
                                        <option value="15" <?= in_array('15', $selected_films) ? 'selected' : '' ?>>Avengers: Endgame</option>
                                        <option value="16" <?= in_array('16', $selected_films) ? 'selected' : '' ?>>Spider-Man: No Way Home</option>
                                        <option value="17" <?= in_array('17', $selected_films) ? 'selected' : '' ?>>Doctor Strange</option>
                                        <option value="18" <?= in_array('18', $selected_films) ? 'selected' : '' ?>>Iron Man</option>
                                        <option value="19" <?= in_array('19', $selected_films) ? 'selected' : '' ?>>Black Panther</option>
                                        <option value="20" <?= in_array('20', $selected_films) ? 'selected' : '' ?>>Thor: Ragnarok</option>
                                        <option value="21" <?= in_array('21', $selected_films) ? 'selected' : '' ?>>The Batman</option>
                                        <option value="22" <?= in_array('22', $selected_films) ? 'selected' : '' ?>>Man of Steel</option>
                                        <option value="23" <?= in_array('23', $selected_films) ? 'selected' : '' ?>>Wonder Woman</option>
                                        <option value="24" <?= in_array('24', $selected_films) ? 'selected' : '' ?>>Aquaman</option>
                                        <option value="25" <?= in_array('25', $selected_films) ? 'selected' : '' ?>>Justice League</option>
                                        <option value="26" <?= in_array('26', $selected_films) ? 'selected' : '' ?>>The Flash</option>
                                        <option value="27" <?= in_array('27', $selected_films) ? 'selected' : '' ?>>Deadpool</option>
                                        <option value="28" <?= in_array('28', $selected_films) ? 'selected' : '' ?>>Logan</option>
                                        <option value="29" <?= in_array('29', $selected_films) ? 'selected' : '' ?>>The Wolverine</option>
                                        <option value="30" <?= in_array('30', $selected_films) ? 'selected' : '' ?>>Captain America: Civil War</option>
                                        
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Film Episode</label>
                                <div class="multi-select-container">
                                    <select class="select2" multiple="multiple" name="film_episodes[]" id="film_episodes" required>
                                         <option value="">None Selected</option>
                                         <option value="1" <?= in_array('1', $selected_episodes) ? 'selected' : '' ?>>Star Wars: Episode I – The Phantom Menace</option>
                                        <option value="2" <?= in_array('2', $selected_episodes) ? 'selected' : '' ?>>Star Wars: Episode II – Attack of the Clones</option>
                                        <option value="3" <?= in_array('3', $selected_episodes) ? 'selected' : '' ?>>Star Wars: Episode III – Revenge of the Sith</option>
                                        <option value="4" <?= in_array('4', $selected_episodes) ? 'selected' : '' ?>>Star Wars: Episode IV – A New Hope</option>
                                        <option value="5" <?= in_array('5', $selected_episodes) ? 'selected' : '' ?>>Star Wars: Episode V – The Empire Strikes Back</option>
                                        <option value="6" <?= in_array('6', $selected_episodes) ? 'selected' : '' ?>>Star Wars: Episode VI – Return of the Jedi</option>
                                        <option value="7" <?= in_array('7', $selected_episodes) ? 'selected' : '' ?>>Star Wars: Episode VII – The Force Awakens</option>
                                        <option value="8" <?= in_array('8', $selected_episodes) ? 'selected' : '' ?>>Star Wars: Episode VIII – The Last Jedi</option>
                                        <option value="9" <?= in_array('9', $selected_episodes) ? 'selected' : '' ?>>Star Wars: Episode IX – The Rise of Skywalker</option>
                                        <option value="10" <?= in_array('10', $selected_episodes) ? 'selected' : '' ?>>The Lord of the Rings: The Fellowship of the Ring</option>
                                        <option value="11" <?= in_array('11', $selected_episodes) ? 'selected' : '' ?>>The Lord of the Rings: The Two Towers</option>
                                        <option value="12" <?= in_array('12', $selected_episodes) ? 'selected' : '' ?>>The Lord of the Rings: The Return of the King</option>
                                        <option value="13" <?= in_array('13', $selected_episodes) ? 'selected' : '' ?>>The Hobbit: An Unexpected Journey</option>
                                        <option value="14" <?= in_array('14', $selected_episodes) ? 'selected' : '' ?>>The Hobbit: The Desolation of Smaug</option>
                                        <option value="15" <?= in_array('15', $selected_episodes) ? 'selected' : '' ?>>The Hobbit: The Battle of the Five Armies</option>
                                        <option value="16" <?= in_array('16', $selected_episodes) ? 'selected' : '' ?>>Harry Potter and the Sorcerer’s Stone</option>
                                        <option value="17" <?= in_array('17', $selected_episodes) ? 'selected' : '' ?>>Harry Potter and the Chamber of Secrets</option>
                                        <option value="18" <?= in_array('18', $selected_episodes) ? 'selected' : '' ?>>Harry Potter and the Prisoner of Azkaban</option>
                                        <option value="19" <?= in_array('19', $selected_episodes) ? 'selected' : '' ?>>Harry Potter and the Goblet of Fire</option>
                                        <option value="20" <?= in_array('20', $selected_episodes) ? 'selected' : '' ?>>Harry Potter and the Order of the Phoenix</option>
                                    </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button onclick="selectAll(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#007bff;color:#fff;border:none;cursor:pointer;">Select All</button>
                                            <button onclick="selectNone(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#17a2b8;color:#fff;border:none;cursor:pointer;">Select None</button>
                                            <button onclick="resetSelection(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#dc3545;color:#fff;border:none;cursor:pointer;">Reset</button>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Related Stories</label>
                                <div class="multi-select-container">
                                    <?php
                                            $selected_stories = isset($song['related_stories']) ? explode(',', $song['related_stories']) : [];
                                    ?>
                                    <select class="select2" multiple="multiple" name="related_stories[]" id="related_stories"  required>
                                        <option value="">None Selected</option>
                                        <option value="1" <?= in_array('1', $selected_stories) ? 'selected' : '' ?>>The Lost Treasure of Atlantis</option>
                                        <option value="2" <?= in_array('2', $selected_stories) ? 'selected' : '' ?>>Whispers in the Whispering Woods</option>
                                        <option value="3" <?= in_array('3', $selected_stories) ? 'selected' : '' ?>>The Secret of the Crystal Cave</option>
                                        <option value="4" <?= in_array('4', $selected_stories) ? 'selected' : '' ?>>Journey to the Forgotten Kingdom</option>
                                        <option value="5" <?= in_array('5', $selected_stories) ? 'selected' : '' ?>>The Phantom Ship Mystery</option>
                                        <option value="6" <?= in_array('6', $selected_stories) ? 'selected' : '' ?>>Escape from the Haunted Mansion</option>
                                        <option value="7" <?= in_array('7', $selected_stories) ? 'selected' : '' ?> >The Midnight Chase</option>
                                        <option value="8" <?= in_array('8', $selected_stories) ? 'selected' : '' ?>>The Hidden Fortress</option>
                                        <option value="9" <?= in_array('9', $selected_stories) ? 'selected' : '' ?>>Legends of the Silver Sword</option>
                                        <option value="10" <?= in_array('10', $selected_stories) ? 'selected' : '' ?>>The Enchanted Forest Chronicles</option>
                                        <option value="11" <?= in_array('11', $selected_stories) ? 'selected' : '' ?>>The Vanishing Island Adventure</option>
                                        <option value="12" <?= in_array('12', $selected_stories) ? 'selected' : '' ?>>The Pirate’s Revenge</option>
                                        <option value="13" <?= in_array('13', $selected_stories) ? 'selected' : '' ?>>The Forgotten Diary</option>
                                        <option value="14" <?= in_array('14', $selected_stories) ? 'selected' : '' ?>>Secrets of the Ancient Scroll</option>
                                        <option value="15" <?= in_array('15', $selected_stories) ? 'selected' : '' ?>>The Magic Compass Quest</option>
                                        <option value="16" <?= in_array('16', $selected_stories) ? 'selected' : '' ?>>The Cursed Ring Saga</option>
                                        <option value="17" <?= in_array('17', $selected_stories) ? 'selected' : '' ?>>The Last Dragon Chronicles</option>
                                        <option value="18" <?= in_array('18', $selected_stories) ? 'selected' : '' ?>>The Hidden Treasure Map</option>
                                        <option value="19" <?= in_array('19', $selected_stories) ? 'selected' : '' ?>>The Phantom Knight Story</option>
                                        <option value="20" <?= in_array('20', $selected_stories) ? 'selected' : '' ?>>The Silver Chalice Mystery</option>
                                        <option value="21" <?= in_array('21', $selected_stories) ? 'selected' : '' ?>>The Enchanted Castle Tales</option>
                                        <option value="22" <?= in_array('22', $selected_stories) ? 'selected' : '' ?>>The Secret Society Adventure</option>
                                        <option value="23" <?= in_array('23', $selected_stories) ? 'selected' : '' ?>>The Crystal Kingdom Quest</option>
                                        <option value="24" <?= in_array('24', $selected_stories) ? 'selected' : '' ?>>The Hidden Chamber Saga</option>
                                        <option value="25" <?= in_array('25', $selected_stories) ? 'selected' : '' ?>>The Midnight Heist</option>
                                        <option value="26" <?= in_array('26', $selected_stories) ? 'selected' : '' ?>>The Forgotten Spell</option>
                                        <option value="27" <?= in_array('27', $selected_stories) ? 'selected' : '' ?>>The Dragon’s Lair</option>
                                        <option value="28" <?= in_array('28', $selected_stories) ? 'selected' : '' ?>>The Lost Explorer</option>
                                        <option value="29" <?= in_array('29', $selected_stories) ? 'selected' : '' ?>>The Moonlight Chase</option>
                                        <option value="30" <?= in_array('30', $selected_stories) ? 'selected' : '' ?>>The Secret Passage</option>
                                        <option value="31" <?= in_array('31', $selected_stories) ? 'selected' : '' ?>>The Vanishing Key</option>
                                        <option value="32" <?= in_array('31', $selected_stories) ? 'selected' : '' ?>>The Hidden Temple</option>
                                        <option value="33" <?= in_array('32', $selected_stories) ? 'selected' : '' ?>>The Final Battle</option>
                                        <option value="34" <?= in_array('33', $selected_stories) ? 'selected' : '' ?>>The Magic Potion Mystery</option>
                                        <option value="35" <?= in_array('34', $selected_stories) ? 'selected' : '' ?>>The Hidden Village Chronicles</option>
                                        <option value="36" <?= in_array('35', $selected_stories) ? 'selected' : '' ?>>The Brave Explorer Saga</option>
                                        <option value="37"<?= in_array('36', $selected_stories) ? 'selected' : '' ?>>The Secret of the Pyramid</option>
                                        <option value="38" <?= in_array('37', $selected_stories) ? 'selected' : '' ?>>The Forgotten Realm</option>
                                        <option value="39" <?= in_array('38', $selected_stories) ? 'selected' : '' ?>>The Legendary Knight Adventure</option>
                                        <option value="40" <?= in_array('39', $selected_stories) ? 'selected' : '' ?>>The Cursed Castle</option>
                                        <option value="41" <?= in_array('40', $selected_stories) ? 'selected' : '' ?>>The Phantom Realm</option>
                                        <option value="42" <?= in_array('41', $selected_stories) ? 'selected' : '' ?>>The Silver Key Mystery</option>
                                        <option value="43" <?= in_array('43', $selected_stories) ? 'selected' : '' ?>>The Enchanted Lake</option>
                                      
                                    </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button onclick="selectAll(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#007bff;color:#fff;border:none;cursor:pointer;">Select All</button>
                                            <button onclick="selectNone(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#17a2b8;color:#fff;border:none;cursor:pointer;">Select None</button>
                                            <button onclick="resetSelection(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#dc3545;color:#fff;border:none;cursor:pointer;">Reset</button>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Related People</label>
                                <div class="multi-select-container">
                                    <select class="select2" multiple="multiple" name="related_people[]"  id="related_people" required> 
                                         <option value="">None Selected</option>
                                            <option value="1" <?= in_array('1', $selected_people) ? 'selected' : '' ?>>John Smith</option>
                                            <option value="2" <?= in_array('2', $selected_people) ? 'selected' : '' ?>>Emily Johnson</option>
                                            <option value="3" <?= in_array('3', $selected_people) ? 'selected' : '' ?>>Michael Brown</option>
                                            <option value="4" <?= in_array('4', $selected_people) ? 'selected' : '' ?>>Sarah Davis</option>
                                            <option value="5" <?= in_array('5', $selected_people) ? 'selected' : '' ?>>David Wilson</option>
                                           
                                    </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button onclick="selectAll(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#007bff;color:#fff;border:none;cursor:pointer;">Select All</button>
                                            <button onclick="selectNone(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#17a2b8;color:#fff;border:none;cursor:pointer;">Select None</button>
                                            <button onclick="resetSelection(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#dc3545;color:#fff;border:none;cursor:pointer;">Reset</button>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Related Songs</label>
                                <?php
                                 $selected_related_songs = isset($song['related_songs']) ? explode(',', $song['related_songs']) : [];
                                ?>
                                <div class="multi-select-container">
                                   <select class="select2" multiple="multiple" name="related_songs[]" id="related_songs" required>
                                        <option value="">None Selected</option>
                                        <option value="1" <?= in_array('1', $selected_related_songs) ? 'selected' : '' ?> >Die before your death</option>
                                        <option value="36" <?= in_array('36', $selected_related_songs) ? 'selected' : '' ?>>Victoria Carter</option>
                                        <option value="37" <?= in_array('37', $selected_related_songs) ? 'selected' : '' ?>>Zachary Mitchell</option>
                                        <option value="38" <?= in_array('38', $selected_related_songs) ? 'selected' : '' ?>>Samantha Perez</option>
                                        <option value="39" <?= in_array('39', $selected_related_songs) ? 'selected' : '' ?>>Nathan Roberts</option>
                                        <option value="40" <?= in_array('40', $selected_related_songs) ? 'selected' : '' ?>>Avery Turner</option>
                                        <option value="41" <?= in_array('41', $selected_related_songs) ? 'selected' : '' ?>>Aaron Phillips</option>
                                        <option value="42" <?= in_array('42', $selected_related_songs) ? 'selected' : '' ?>>Brooklyn Campbell</option>
                                        <option value="43" <?= in_array('43', $selected_related_songs) ? 'selected' : '' ?> >Ethan Parker</option>
                                        <option value="44" <?= in_array('44', $selected_related_songs) ? 'selected' : '' ?>>Madison Evans</option>
                                        <option value="45" <?= in_array('45', $selected_related_songs) ? 'selected' : '' ?>>Gabriel Edwards</option>
                                        <option value="46" <?= in_array('46', $selected_related_songs) ? 'selected' : '' ?>>Addison Collins</option>
                                        <option value="47" <?= in_array('47', $selected_related_songs) ? 'selected' : '' ?>>Christian Stewart</option>
                                        <option value="48" <?= in_array('48', $selected_related_songs) ? 'selected' : '' ?>>Scarlett Sanchez</option>
                                        <option value="49" <?= in_array('49', $selected_related_songs) ? 'selected' : '' ?>>Jonathan Morris</option>
                                        <option value="50" <?= in_array('50', $selected_related_songs) ? 'selected' : '' ?>>Victoria Rogers</option>
                                    </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button onclick="selectAll(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#007bff;color:#fff;border:none;cursor:pointer;">Select All</button>
                                            <button onclick="selectNone(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#17a2b8;color:#fff;border:none;cursor:pointer;">Select None</button>
                                            <button onclick="resetSelection(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#dc3545;color:#fff;border:none;cursor:pointer;">Reset</button>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="gathering">Gatherings</label>
                                <?php
                                 $selected_gathering = isset($song['gatherings']) ? explode(',', $song['gatherings']) : [];
                                ?>
                                <select id="gathering" name="gatherings[]" class="form-control" required>
                                    <option value="">Select a gathering</option>
                                    <option value="1" <?= in_array('1', $selected_gathering) ? 'selected' : '' ?> >Morning Service</option>
                                    <option value="evening_service" <?= in_array('1', $selected_gathering) ? 'selected' : '' ?> >Evening Service</option>
                                    <option value="youth_meeting" <?= in_array('1', $selected_gathering) ? 'selected' : '' ?>>Youth Meeting</option>
                                    <option value="special_event" <?= in_array('1', $selected_gathering) ? 'selected' : '' ?>>Special Event</option>
                                </select>
                            </div>
                             <div class="col-md-3">
                            <label for="landingPage">Show On LandingPage</label>
                            <select id="showon-page"  class="form-control" name="showOnLandingPage" required>
                                <option value="true" <?= $show_on_landing=='true'?'selected':'' ?>>Yes</option>
                                <option value="false" <?= $show_on_landing=='false'?'selected':'' ?>>No</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="songCategory">Song Category</label>
                            <?php
                                 $selected_songCategory = isset($song['songcategory']) ? $song['songcategory'] : '';
                                ?>
                            <select id="songCategory" ng-model="song.songCategory"  class="form-control" ng-required="song.isAuthoringComplete" name="songcategory[]" required>
                                <option value="">Select a category</option>
                                <option value="worship" <?= ($selected_songCategory == 'worship') ? 'selected' : '' ?>>Worship</option>
                                <option value="praise" <?= ($selected_songCategory == 'praise') ? 'selected' : '' ?>>Praise</option>
                                <option value="hymn" <?= ($selected_songCategory == 'hymn') ? 'selected' : '' ?>>Hymn</option>
                                <option value="gospel" <?= ($selected_songCategory == 'gospel') ? 'selected' : '' ?>>Gospel</option>
                                <option value="contemporary" <?= ($selected_songCategory == 'contemporary') ? 'selected' : '' ?>>Contemporary</option>
                            </select>
                            <error-message name="Song category" show-error="song.isAuthoringComplete && isEmpty(song.songCategory)"></error-message>
                        </div>
                        </div>
                        <!-- Duration, Youtube, SoundCloud, Thumbnail -->
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>Duration</label>
                                <input type="text" name="duration" id="duration" value="<?= isset($song['duration']) ? htmlspecialchars($song['duration']) : '' ?>" ng-model="song.duration" class="form-control" ng-required="song.isAuthoringComplete"  required>
                                <error-message name="Duration" show-error="song.isAuthoringComplete && isEmpty(song.duration)"></error-message>
                            </div>
                            <div class="col-md-3">
                                <label>Youtube Video ID</label>
                                <input type="text" name="youtubeVideoId" id="youtubeVideoId" value="<?= isset($song['youtubeVideoId']) ? htmlspecialchars($song['youtubeVideoId']) : '' ?>" ng-model="song.youtubeVideoId" class="form-control" ng-required="song.isAuthoringComplete && isMediaUrlEmpty()" required>
                                <error-message name="Youtube URL or SoundCloud" show-error="song.isAuthoringComplete && isMediaUrlEmpty()"></error-message>
                            </div>
                            <div class="col-md-3">
                                <label>SoundCloud track Url</label>
                                <input type="text" name="soundCloudTrackUrl" id="soundCloudTrackUrl" value="<?= isset($song['soundCloudTrackUrl']) ? htmlspecialchars($song['soundCloudTrackUrl']) : '' ?>" ng-model="song.soundCloudTrackId" class="form-control" ng-required="song.isAuthoringComplete && isMediaUrlEmpty()" required>
                                <error-message name="Youtube URL or SoundCloud" show-error="song.isAuthoringComplete && isMediaUrlEmpty()"></error-message>
                            </div>
                            <div class="col-md-3">
                                <label>Thumbnail Url</label>
                                <input type="file" name="thumbnailUrl" id="thumbnailUrl" value="<?= isset($song['thumbnailUrl']) ? htmlspecialchars($song['thumbnailUrl']) : '' ?>" ng-model="song.thumbnailURL" class="form-control"  ng-required="song.isAuthoringComplete" required>
                                <error-message name="Thumbnail URL" show-error="song.isAuthoringComplete && isEmpty(song.thumbnailURL)"></error-message>
                            </div>
                            <div class="col-md-3">
                                <label>Thumbnail Excerpt (required)</label>
                                <input type="text" name="thumbnailexcerpt" id="thumbnailexcerpt" value="<?= isset($song['thumbnailexcerpt']) ? htmlspecialchars($song['thumbnailexcerpt']) : '' ?>" ng-model="song.thumbnailexcerpt" class="form-control" ng-required="song.isAuthoringComplete && isMediaUrlEmpty()" required>
                                <error-message name="Youtube URL or SoundCloud" show-error="song.isAuthoringComplete && isMediaUrlEmpty()"></error-message>
                            </div>
                            <div class="col-md-3">
                                <label>Download Url</label>
                                <input type="text" name="downloadUrl" id="downloadUrl" value="<?= isset($song['downloadUrl']) ? htmlspecialchars($song['downloadUrl']) : '' ?>" ng-model="song.downloadURL" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>Genres</label>
                                <div class="multi-select-container">
                                    <?php
                                  $selected_genres = isset($song['genres']) ? explode(',', $song['genres']) : [];
                                ?>
                                    <select class="select2" multiple="multiple" name="genres[]" id="genres" required>
                                        <option value="">None Selected</option>
                                        <option value="1" <?= in_array('1', $selected_genres) ? 'selected' : '' ?>>Die before your death</option>
                                        <option value="36" <?= in_array('36', $selected_genres) ? 'selected' : '' ?>>Victoria Carter</option>
                                        <option value="37" <?= in_array('37', $selected_genres) ? 'selected' : '' ?>>Zachary Mitchell</option>
                                        <option value="38" <?= in_array('38', $selected_genres) ? 'selected' : '' ?>>Samantha Perez</option>
                                        <option value="39" <?= in_array('39', $selected_genres) ? 'selected' : '' ?>>Nathan Roberts</option>
                                        <option value="40" <?= in_array('40', $selected_genres) ? 'selected' : '' ?>>Avery Turner</option>
                                        <option value="41" <?= in_array('41', $selected_genres) ? 'selected' : '' ?>>Aaron Phillips</option>
                                        <option value="42" <?= in_array('42', $selected_genres) ? 'selected' : '' ?>>Brooklyn Campbell</option>
                                        <option value="43" <?= in_array('43', $selected_genres) ? 'selected' : '' ?>>Ethan Parker</option>
                                        <option value="44" <?= in_array('44', $selected_genres) ? 'selected' : '' ?>>Madison Evans</option>
                                        <option value="45" <?= in_array('45', $selected_genres) ? 'selected' : '' ?>>Gabriel Edwards</option>
                                        <option value="46" <?= in_array('46', $selected_genres) ? 'selected' : '' ?>>Addison Collins</option>
                                        <option value="47" <?= in_array('47', $selected_genres) ? 'selected' : '' ?>>Christian Stewart</option>
                                        <option value="48" <?= in_array('48', $selected_genres) ? 'selected' : '' ?>>Scarlett Sanchez</option>
                                        <option value="49" <?= in_array('49', $selected_genres) ? 'selected' : '' ?>>Jonathan Morris</option>
                                        <option value="50" <?= in_array('50', $selected_genres) ? 'selected' : '' ?>>Victoria Rogers</option>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        <!-- Lyrics Sections -->
                        <div class="row form-group">
                            <div class="col-md-2"><label>Song Lyrics - Original</label></div>
                                <div class="col-md-10">
                                    <div class="card">
                                        <div class="card-body" >
                                            <textarea id="songLyricsOriginal" name="songLyricsOriginal"  ng-model="song.songText.original" required><?= htmlspecialchars($songLyricsOriginal) ?></textarea>
                                            
                                        </div>
                                    </div>
                                </div>
                        </div>
                                <div class="row form-group">
                                    <div class="col-md-2"><label>Song Lyrics - Transliteration</label></div>
                                        <div class="col-md-10">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <textarea id="songLyricsNotes" name="songLyricsNotes" ng-model="song.songText.notes" required><?= htmlspecialchars($songLyricsTransliteration) ?></textarea>
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                <div class="row form-group">
                                    <div class="col-md-2"><label>Song Lyrics - Translation</label></div>
                        <div class="col-md-10">
                                <div class="card">
                                    <div class="card-body" style="padding: 40px;">
                                       <textarea id="songLyricsTranslated" name="songLyricsTranslated" ng-model="song.songText.translated" required><?= htmlspecialchars($songLyricsTranslation) ?></textarea>
                                    </div>
                                </div>
                            </div>
                          </div>

                                <!-- About -->
                                <div class="row form-group">
                                    <div class="col-md-2"><label>About</label></div>
                        <div class="col-md-10">
                                 <div class="card">
                                    <div class="card-body">
                                        <textarea id="songAbout" name="about" ng-model="song.songText.meaning" required><?= htmlspecialchars($songAbout) ?></textarea>
                                    </div>
                                </div>
                            </div>    
                        </div>
                        <!-- Song Notes, Glossary & Reflection Row -->
                        <div class="row form-group align-items-center">
                            <div class="col-md-3">
                            <label>Song Notes</label>
                            <input type="text" name="songnotes" id="songnotes" value="<?= isset($song['songnotes']) ? $song['songnotes'] : '' ?>" ng-model="song.songnotes" class="form-control" placeholder="Enter Song Notes" required>
                        </div>

                            <div class="col-md-3">
                                <label>Song Glossary</label>
                                <input type="text" name="songglossary" id="songglossary" value="<?= isset($song['songglossary']) ? $song['songglossary'] : '' ?>" ng-model="song.songglossary" class="form-control" required>
                            </div>
                            <!-- Publish -->
                            <div class="col-md-4">
                                <label>Publish</label>
                                <select class="form-control" name="publish" id="publish" required>
                                    <option value="">Select</option>
                                    <option value="true" <?= $select_publish=='true'?'selected':'' ?>>Yes</option>
                                <option value="false" <?= $select_publish=='false'?'selected':'' ?>>No</option>
                                </select>
                            </div>
                            <!-- Reflection -->
                            <div class="col-md-2" style="margin-top: 15px;">
                                <label>Reflection?</label>
                                <div>
                                    <input type="checkbox" name="reflection" id="reflection" value="true" <?= ($selected_ref_check == 'true' || $selected_ref_check == '1') ? 'checked' : '' ?> style="width:auto; height:auto;" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Meta Title</label>
                                <input type="text" name="metaTitle" id="metaTitle"  value="<?= isset($song['metaTitle']) ? $song['metaTitle'] : '' ?>"  ng-model="song.metaTitle" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>Meta Keyword</label>
                                <input type="text" name="metaKeyword" id="metaKeyword" value="<?= isset($song['metaKeyword']) ? $song['metaKeyword'] : '' ?>" ng-model="song.metaKeywords" class="form-control" required>
                            </div>
                            <div class="col-md-6" style="margin-top: 37px;">
                                <label>Meta Description</label>
                                <textarea class="form-control" name="metaDescription" id="metaDescription"
                                    rows="3" placeholder="Enter Meta Description" required> <?= htmlspecialchars($metaDescription) ?></textarea>
                            </div>


                        </div>
                        <!-- Reflection Checkbox Row -->
                <div class="row form-group">
                     <!-- Reflection -->
                    <!-- <div class="col-md-4">
                        <label>Is this also a Reflection?</label>
                        <div>
                            <input type="checkbox" name="reflection" style="width:auto; height:auto;">
                        </div>
                    </div> -->
                </div>
                        <br/>
                       <button type="submit" class="btn btn-primary">Save</button>
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
    // Initialize AngularJS module and controller
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

    // JavaScript for accessibility and modal handling
    document.addEventListener('DOMContentLoaded', function() {
        var editButton = document.getElementById('editUmbrellaBtn');
        var addButton = document.getElementById('addUmbrellaBtn');
        var editModal = document.getElementById('editModal');
        var addModal = document.getElementById('addModal');

        // Edit Button Handlers
        if (editButton && editModal) {
            editButton.addEventListener('click', function() {
                editModal.style.display = 'block';
                angular.element(editModal).scope().$apply(function() {
                    angular.element(editModal).scope().showEditModal = true;
                });
            });

            editButton.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    editModal.style.display = 'block';
                    angular.element(editModal).scope().$apply(function() {
                        angular.element(editModal).scope().showEditModal = true;
                    });
                }
            });

            var cancelButton = editModal.querySelector('.btn-cancel');
            if (cancelButton) {
                cancelButton.addEventListener('click', function() {
                    editModal.style.display = 'none';
                    angular.element(editModal).scope().$apply(function() {
                        angular.element(editModal).scope().showEditModal = false;
                    });
                });
            }
        }

        // Add New Button Handlers
        if (addButton && addModal) {
            addButton.addEventListener('click', function() {
                addModal.style.display = 'block';
                angular.element(addModal).scope().$apply(function() {
                    angular.element(addModal).scope().showAddModal = true;
                });
            });

            addButton.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    addModal.style.display = 'block';
                    angular.element(addModal).scope().$apply(function() {
                        angular.element(addModal).scope().showAddModal = true;
                    });
                }
            });

            var cancelButton = addModal.querySelector('.btn-cancel');
            if (cancelButton) {
                cancelButton.addEventListener('click', function() {
                    addModal.style.display = 'none';
                    angular.element(addModal).scope().$apply(function() {
                        angular.element(addModal).scope().showAddModal = false;
                    });
                });
            }
        }

        // Year dropdown population
        const yearSelect = document.getElementById('yearSelect');
        const currentYear = new Date().getFullYear();
        for (let y = currentYear; y >= 1900; y--) {
            const option = document.createElement('option');
            option.value = y;
            option.text = y;
            yearSelect.appendChild(option);
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
                        'songAbout'
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
document.getElementById('songForm').addEventListener('submit', function(e) {
  e.preventDefault(); // stop form submit

  const fields = [
    { id: 'umbrellaTitle', name: 'Umbrella Title' },
    { id: 'Songtitle_transliteration', name: 'Song Title - Transliteration' },
    { id: 'songtitletraan', name: 'Song Title - Translated (required)' },
    { id: 'singer', name: 'Singer' },
    { id: 'words', name: 'Words' },
    { id: 'year', name: 'yearSelect' },
    { id: 'location', name: 'Location' },
    { id: 'reflections', name: 'Reflections' },
    { id: 'couplets', name: 'Couplets' },
    { id: 'films', name: 'Films' },
    { id: 'film_episodes', name: 'Film Episode' },
    { id: 'related_stories', name: 'Related Stories' },
    { id: 'related_people', name: 'Related People' },
    { id: 'related_songs', name: 'Related Songs' },
    { id: 'gathering', name: 'Gatherings' },
    { id: 'showon-page', name: 'Show On LandingPage' },
    { id: 'songCategory', name: 'Song Category' },
    { id: 'duration', name: 'Duration' },
    { id: 'youtubeVideoId', name: 'Youtube Video ID' },
    { id: 'soundCloudTrackUrl', name: 'SoundCloud track Url' },
    { id: 'thumbnailUrl', name: 'Thumbnail Url' },
    { id: 'thumbnailexcerpt', name: 'Thumbnail Excerpt (required)' },
     { id: 'downloadUrl', name: 'Download Url' },
      { id: 'genres', name: 'Genres' },
       { id: 'songLyricsOriginal', name: 'Song Lyrics - Original' },
        { id: 'songLyricsNotes', name: 'Song Lyrics - Transliteration' },
         { id: 'songLyricsTranslated', name: 'Song Lyrics - Translation' },
          { id: 'songAbout', name: 'About' },
           { id: 'songnotes', name: 'Song Notes' },
            { id: 'songglossary', name: 'Song Glossary' },
             { id: 'publish', name: 'Publish' },
             { id: 'reflection', name: 'Reflection' },
             { id: 'metaTitle', name: 'Meta Title' },
             { id: 'metaKeyword', name: 'Meta Keyword' },
             { id: 'metaDescription', name: 'Meta Description' }
         
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
      return false; // stop submission
    }
  }

  // If all filled, submit the form
  this.submit();
});
</script>

       <script>
    // Get Elements
    const editModal = document.getElementById('editModal');
    const addModal = document.getElementById('addModal');
    const editBtn = document.getElementById('editBtn');
    const addBtn = document.getElementById('addBtn');
    const closeEdit = document.getElementById('closeEdit');
    const closeAdd = document.getElementById('closeAdd');
    const cancelEdit = document.getElementById('cancelEdit');
    const cancelAdd = document.getElementById('cancelAdd');
    const updateTitle = document.getElementById('updateTitle');
    const addTitle = document.getElementById('addTitle');
    const deleteBtn = document.getElementById('deleteBtn');
    const umbrellaSelect = document.getElementById('umbrellaTitle');

    // Open modals
    editBtn.onclick = () => { editModal.style.display = 'block'; };
    addBtn.onclick = () => { addModal.style.display = 'block'; };

    // Close modals
    [closeEdit, cancelEdit].forEach(btn => btn.onclick = () => editModal.style.display = 'none');
    [closeAdd, cancelAdd].forEach(btn => btn.onclick = () => addModal.style.display = 'none');

    // Outside click close
    window.onclick = (event) => {
      if (event.target === editModal) editModal.style.display = 'none';
      if (event.target === addModal) addModal.style.display = 'none';
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

    // Delete selected title
    deleteBtn.onclick = () => {
      const selectedIndex = umbrellaSelect.selectedIndex;
      if (selectedIndex > -1) {
        umbrellaSelect.remove(selectedIndex);
        alert("🗑️ Title deleted successfully!");
      } else {
        alert("⚠️ Please select a title to delete!");
      }
    };
  </script>

<?php include('inc/footer.php'); ?>