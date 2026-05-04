<?php 
include('inc/header.php');
include('inc/sidebar.php');
?>
<head>
    <link rel="stylesheet" href="/common/lib/angular-multi-select/angular-multi-select.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
</style>
<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Story Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="add_new">Home</a></li>
                        <li class="breadcrumb-item active">Add Story Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header" style="padding: 4px 8px; margin: 0;">
                    <div>
                        <a href="add_new" class="btn btn-secondary" style="padding: 3px 8px; font-size: 13px; border-radius: 4px;">
                            <i class="fas fa-arrow-left" style="font-size: 13px; margin-right: 4px;"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Display flash messages -->
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <?php
                        $action_url = isset($story) ? base_url('story/update/' . $story->id) : base_url('StoryController/save');
                    ?>
                    <form id="storyForm" method="post" action="<?= $action_url ?>">
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>Main Title</label>
                                <input type="text" name="main_title" id="main_title" class="form-control" value="<?= isset($story) ? $story->main_title : '' ?>" placeholder="Enter Main Title">
                            </div>
                            <div class="col-md-3">
                                <label>Second Title</label>
                                <input type="text" name="second_title" id="second_title" class="form-control" value="<?= isset($story) ? $story->second_title : '' ?>" placeholder="Enter Second Title">
                            </div>
                            <div class="col-md-3" style="margin-top: 2px">
                                <label>Author's</label>
                                <div class="multi-select-container">
                                    <select class="select2" name="author[]" id="author" multiple="multiple" data-placeholder="Select Author/s">
                                        <option value="1" <?= (isset($story) && in_array(1, explode(',', $story->author))) ? 'selected' : '' ?>>Die before your death</option>
                                        <option value="2" <?= (isset($story) && in_array(2, explode(',', $story->author))) ? 'selected' : '' ?>>Live without regrets</option>
                                        <option value="3" <?= (isset($story) && in_array(3, explode(',', $story->author))) ? 'selected' : '' ?>>Chase your dreams</option>
                                        <option value="4" <?= (isset($story) && in_array(4, explode(',', $story->author))) ? 'selected' : '' ?>>Silence is golden</option>
                                        <option value="5" <?= (isset($story) && in_array(5, explode(',', $story->author))) ? 'selected' : '' ?>>Happiness is a choice</option>
                                    </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button type="button" onclick="selectAll(this)">Select All</button>
                                            <button type="button" onclick="selectNone(this)">Select None</button>
                                            <button type="button" onclick="resetSelection(this)" class="reset">Reset</button>
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
                                <label>Verb</label>
                                <input type="text" name="verb" id="verb" class="form-control" value="<?= isset($story) ? $story->verb : '' ?>" placeholder="Enter Verb">
                            </div>
                            <div class="col-md-3">
                                <label>Description Text</label>
                                <input type="text" name="description" id="description" class="form-control" value="<?= isset($story) ? $story->description : '' ?>" placeholder="Enter Description Text">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2"><label>Notes</label></div>
                            <div class="col-md-10">
                                <div class="card">
                                    <div class="card-body">
                                        <textarea id="songLyricsOriginal" name="note"><?= isset($story) ? $story->note : '' ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-3" style="margin-top: 2px">
                                <label>Category</label>
                                <div class="multi-select-container">
                                    <?php
                                    $selected_categories = [];
                                    if (isset($story) && !empty($story->category)) {
                                        $selected_categories = explode(',', $story->category);
                                    }
                                    ?>
                                    <select class="select2" multiple="multiple" name="category[]" id="category" data-placeholder="Select Category">
                                        <option value="1" <?= in_array('1', $selected_categories) ? 'selected' : '' ?>>Die before your death</option>
                                        <option value="2" <?= in_array('2', $selected_categories) ? 'selected' : '' ?>>Live without regrets</option>
                                        <option value="3" <?= in_array('3', $selected_categories) ? 'selected' : '' ?>>Chase your dreams</option>
                                        <option value="4" <?= in_array('4', $selected_categories) ? 'selected' : '' ?>>Silence is golden</option>
                                        <option value="5" <?= in_array('5', $selected_categories) ? 'selected' : '' ?>>Happiness is a choice</option>
                                    </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button type="button" onclick="selectAll(this)">Select All</button>
                                            <button type="button" onclick="selectNone(this)">Select None</button>
                                            <button type="button" onclick="resetSelection(this)" class="reset">Reset</button>
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
                            <?php
                            $storyThumbRaw = (isset($story) && !empty($story->thumbnail_url)) ? trim((string) $story->thumbnail_url) : '';
                            $storyThumbPreviewSrc = '';
                            if ($storyThumbRaw !== '') {
                                $storyThumbPreviewSrc = preg_match('#^https?://#i', $storyThumbRaw) ? $storyThumbRaw : (rtrim(base_url(), '/') . '/' . ltrim($storyThumbRaw, '/'));
                            }
                            ?>
                            <div class="col-md-3">
                                <label>Story Thumbnail Url</label>
                                <input type="text" name="thumbnail_url" id="thumbnail_url" class="form-control" value="<?= isset($story) ? htmlspecialchars($story->thumbnail_url) : '' ?>" placeholder="Enter Story Thumbnail Url">
                                <?php if ($storyThumbPreviewSrc !== ''): ?>
                                <div class="mt-2">
                                    <img src="<?= htmlspecialchars($storyThumbPreviewSrc) ?>" alt="Thumbnail preview" style="max-width:200px;height:auto;border:1px solid #ddd;border-radius:4px;"
                                        onerror="this.style.display='none';">
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-3">
                                <label>Is this Story an Echo</label>
                                <select name="is_echo" id="is_echo" class="form-control" style="height: 38px;">
                                    <option value="">Select</option>
                                    <option value="true" <?= (isset($story) && $story->is_echo == 'true') ? 'selected' : '' ?>>Yes</option>
                                    <option value="false" <?= (isset($story) && $story->is_echo == 'false') ? 'selected' : '' ?>>No</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Is this Story a Classroom Idea</label>
                                <select name="is_class_room_idea" id="is_class_room_idea" class="form-control" style="height: 38px;">
                                    <option value="">Select</option>
                                    <option value="true" <?= (isset($story) && $story->is_class_room_idea == 'true') ? 'selected' : '' ?>>Yes</option>
                                    <option value="false" <?= (isset($story) && $story->is_class_room_idea == 'false') ? 'selected' : '' ?>>No</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Classroom Experiment</label>
                                <select name="is_class_room_experiment" id="is_class_room_experiment" class="form-control" style="height: 38px;">
                                    <option value="">Select</option>
                                    <option value="true" <?= (isset($story) && $story->is_class_room_experiment == 'true') ? 'selected' : '' ?>>Yes</option>
                                    <option value="false" <?= (isset($story) && $story->is_class_room_experiment == 'false') ? 'selected' : '' ?>>No</option>
                                </select>
                            </div>
                            <div class="col-md-3" style="margin-top: 2px">
                                <label>Related Songs</label>
                                <div class="multi-select-container">
                                    <?php
                                    $selected_related_songs = [];
                                    if (isset($story) && !empty($story->related_songs)) {
                                        $selected_related_songs = explode(',', $story->related_songs);
                                    }
                                    ?>
                                    <select class="select2" multiple="multiple" name="related_songs[]" id="related_songs" data-placeholder="Select Related Songs">
                                        <option value="">None Selected</option>
                                        <option value="1" <?= in_array('1', $selected_related_songs) ? 'selected' : '' ?>>Die before your death</option>
                                        <option value="2" <?= in_array('2', $selected_related_songs) ? 'selected' : '' ?>>Live without regrets</option>
                                        <option value="3" <?= in_array('3', $selected_related_songs) ? 'selected' : '' ?>>Chase your dreams</option>
                                        <option value="4" <?= in_array('4', $selected_related_songs) ? 'selected' : '' ?>>Silence is golden</option>
                                        <option value="5" <?= in_array('5', $selected_related_songs) ? 'selected' : '' ?>>Happiness is a choice</option>
                                    </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button type="button" onclick="selectAll(this)">Select All</button>
                                            <button type="button" onclick="selectNone(this)">Select None</button>
                                            <button type="button" onclick="resetSelection(this)" class="reset">Reset</button>
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
                            <div class="col-md-3" style="margin-top: 2px">
                                <label>Related Couplets</label>
                                <div class="multi-select-container">
                                    <?php
                                    $selected_related_couplets = [];
                                    if (isset($story) && !empty($story->related_couplets)) {
                                        $selected_related_couplets = explode(',', $story->related_couplets);
                                    }
                                    ?>
                                    <select class="select2" multiple="multiple" name="related_couplets[]" id="related_couplets" data-placeholder="Select Related Couplets">
                                        <option value="">None Selected</option>
                                        <option value="1" <?= in_array('1', $selected_related_couplets) ? 'selected' : '' ?>>Die before your death</option>
                                        <option value="2" <?= in_array('2', $selected_related_couplets) ? 'selected' : '' ?>>Live without regrets</option>
                                        <option value="3" <?= in_array('3', $selected_related_couplets) ? 'selected' : '' ?>>Chase your dreams</option>
                                        <option value="4" <?= in_array('4', $selected_related_couplets) ? 'selected' : '' ?>>Silence is golden</option>
                                        <option value="5" <?= in_array('5', $selected_related_couplets) ? 'selected' : '' ?>>Happiness is a choice</option>
                                    </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button type="button" onclick="selectAll(this)">Select All</button>
                                            <button type="button" onclick="selectNone(this)">Select None</button>
                                            <button type="button" onclick="resetSelection(this)" class="reset">Reset</button>
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
                            <div class="col-md-3" style="margin-top: 2px">
                                <label>Related Words</label>
                                <div class="multi-select-container">
                                    <?php
                                    $selected_related_words = [];
                                    if (isset($story) && !empty($story->related_words)) {
                                        $selected_related_words = explode(',', $story->related_words);
                                    }
                                    ?>
                                    <select class="select2" multiple="multiple" name="related_words[]" id="related_words" data-placeholder="Select Related Words">
                                        <option value="">None Selected</option>
                                        <option value="1" <?= in_array('1', $selected_related_words) ? 'selected' : '' ?>>Die before your death</option>
                                        <option value="2" <?= in_array('2', $selected_related_words) ? 'selected' : '' ?>>Live without regrets</option>
                                        <option value="3" <?= in_array('3', $selected_related_words) ? 'selected' : '' ?>>Chase your dreams</option>
                                        <option value="4" <?= in_array('4', $selected_related_words) ? 'selected' : '' ?>>Silence is golden</option>
                                        <option value="5" <?= in_array('5', $selected_related_words) ? 'selected' : '' ?>>Happiness is a choice</option>
                                    </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button type="button" onclick="selectAll(this)">Select All</button>
                                            <button type="button" onclick="selectNone(this)">Select None</button>
                                            <button type="button" onclick="resetSelection(this)" class="reset">Reset</button>
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
                            <div class="col-md-3" style="margin-top: 2px">
                                <label>Related Reflections</label>
                                <div class="multi-select-container">
                                    <?php
                                    $selected_related_reflections = [];
                                    if (isset($story) && !empty($story->related_reflections)) {
                                        $selected_related_reflections = explode(',', $story->related_reflections);
                                    }
                                    ?>
                                    <select class="select2" multiple="multiple" name="related_reflections[]" id="related_reflections" data-placeholder="Select Related Reflections">
                                        <option value="">None Selected</option>
                                        <option value="1" <?= in_array('1', $selected_related_reflections) ? 'selected' : '' ?>>Die before your death</option>
                                        <option value="2" <?= in_array('2', $selected_related_reflections) ? 'selected' : '' ?>>Live without regrets</option>
                                        <option value="3" <?= in_array('3', $selected_related_reflections) ? 'selected' : '' ?>>Chase your dreams</option>
                                        <option value="4" <?= in_array('4', $selected_related_reflections) ? 'selected' : '' ?>>Silence is golden</option>
                                        <option value="5" <?= in_array('5', $selected_related_reflections) ? 'selected' : '' ?>>Happiness is a choice</option>
                                    </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button type="button" onclick="selectAll(this)">Select All</button>
                                            <button type="button" onclick="selectNone(this)">Select None</button>
                                            <button type="button" onclick="resetSelection(this)" class="reset">Reset</button>
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
                            <div class="col-md-3" style="margin-top: 2px">
                                <label>Related People</label>
                                <div class="multi-select-container">
                                    <?php
                                    $selected_related_people = [];
                                    if (isset($story) && !empty($story->related_people)) {
                                        $selected_related_people = explode(',', $story->related_people);
                                    }
                                    ?>
                                    <select class="select2" multiple="multiple" name="related_people[]" id="related_people" data-placeholder="Select Related People">
                                        <option value="">None Selected</option>
                                        <option value="1" <?= in_array('1', $selected_related_people) ? 'selected' : '' ?>>Die before your death</option>
                                        <option value="2" <?= in_array('2', $selected_related_people) ? 'selected' : '' ?>>Live without regrets</option>
                                        <option value="3" <?= in_array('3', $selected_related_people) ? 'selected' : '' ?>>Chase your dreams</option>
                                        <option value="4" <?= in_array('4', $selected_related_people) ? 'selected' : '' ?>>Silence is golden</option>
                                        <option value="5" <?= in_array('5', $selected_related_people) ? 'selected' : '' ?>>Happiness is a choice</option>
                                    </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button type="button" onclick="selectAll(this)">Select All</button>
                                            <button type="button" onclick="selectNone(this)">Select None</button>
                                            <button type="button" onclick="resetSelection(this)" class="reset">Reset</button>
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
                            <div class="col-md-3" style="margin-top: 2px">
                                <label>Related Films</label>
                                <div class="multi-select-container">
                                    <?php
                                    $selected_related_films = [];
                                    if (isset($story) && !empty($story->related_films)) {
                                        $selected_related_films = explode(',', $story->related_films);
                                    }
                                    ?>
                                    <select class="select2" multiple="multiple" name="related_films[]" id="related_films" data-placeholder="Select Related Films">
                                        <option value="">None Selected</option>
                                        <option value="1" <?= in_array('1', $selected_related_films) ? 'selected' : '' ?>>Die before your death</option>
                                        <option value="2" <?= in_array('2', $selected_related_films) ? 'selected' : '' ?>>Live without regrets</option>
                                        <option value="3" <?= in_array('3', $selected_related_films) ? 'selected' : '' ?>>Chase your dreams</option>
                                        <option value="4" <?= in_array('4', $selected_related_films) ? 'selected' : '' ?>>Silence is golden</option>
                                        <option value="5" <?= in_array('5', $selected_related_films) ? 'selected' : '' ?>>Happiness is a choice</option>
                                    </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button type="button" onclick="selectAll(this)">Select All</button>
                                            <button type="button" onclick="selectNone(this)">Select None</button>
                                            <button type="button" onclick="resetSelection(this)" class="reset">Reset</button>
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
                            <div class="col-md-3" style="margin-top: 2px">
                                <label>Related FilmEpisode</label>
                                <div class="multi-select-container">
                                    <?php
                                    $selected_related_filmEpisode = [];
                                    if (isset($story) && !empty($story->related_filmEpisode)) {
                                        $selected_related_filmEpisode = explode(',', $story->related_filmEpisode);
                                    }
                                    ?>
                                    <select class="select2" multiple="multiple" name="related_filmEpisode[]" id="related_filmEpisode" data-placeholder="Select Related FilmEpisode">
                                        <option value="">None Selected</option>
                                        <option value="1" <?= in_array('1', $selected_related_filmEpisode) ? 'selected' : '' ?>>Die before your death</option>
                                        <option value="2" <?= in_array('2', $selected_related_filmEpisode) ? 'selected' : '' ?>>Live without regrets</option>
                                        <option value="3" <?= in_array('3', $selected_related_filmEpisode) ? 'selected' : '' ?>>Chase your dreams</option>
                                        <option value="4" <?= in_array('4', $selected_related_filmEpisode) ? 'selected' : '' ?>>Silence is golden</option>
                                        <option value="5" <?= in_array('5', $selected_related_filmEpisode) ? 'selected' : '' ?>>Happiness is a choice</option>
                                    </select>
                                    <div class="popup-dropdown" style="display:none;">
                                        <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
                                        <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                                            <button type="button" onclick="selectAll(this)">Select All</button>
                                            <button type="button" onclick="selectNone(this)">Select None</button>
                                            <button type="button" onclick="resetSelection(this)" class="reset">Reset</button>
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
                                <label>Display on Stories Page</label>
                                <select name="is_story" id="is_story" class="form-control" style="height: 38px;">
                                    <option value="">Select</option>
                                    <option value="true" <?= (isset($story) && $story->is_story == 'true') ? 'selected' : '' ?>>Yes</option>
                                    <option value="false" <?= (isset($story) && $story->is_story == 'false') ? 'selected' : '' ?>>No</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Show On Landing Page</label>
                                <select name="show_on_landing_page" id="show_on_landing_page" class="form-control" style="height: 38px;">
                                    <option value="">Select</option>
                                    <option value="true" <?= (isset($story) && $story->show_on_landing_page === 'true') ? 'selected' : '' ?>>Yes</option>
                                    <option value="false" <?= (isset($story) && $story->show_on_landing_page === 'false') ? 'selected' : '' ?>>No</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Publish</label>
                                <select name="is_published" id="is_published" class="form-control" style="height: 38px;">
                                    <option value="">Select</option>
                                    <option value="true" <?= (isset($story) && $story->is_published === 'true') ? 'selected' : '' ?>>Yes</option>
                                    <option value="false" <?= (isset($story) && $story->is_published === 'false') ? 'selected' : '' ?>>No</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Meta Title</label>
                                <input type="text" name="meta_title" id="meta_title" class="form-control" placeholder="Enter Meta Title" value="<?= isset($story) ? $story->meta_title : '' ?>">
                            </div>
                            <div class="col-md-3">
                                <label>Meta Keyword</label>
                                <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" placeholder="Enter Meta Keywords" value="<?= isset($story) ? $story->meta_keywords : '' ?>">
                            </div>
                            <div class="col-md-3">
                                <label>Meta Description</label>
                                <textarea class="form-control" name="meta_description" id="meta_description" rows="3" placeholder="Enter Meta Description" style="width: 207%;"><?= isset($story) ? $story->meta_description : '' ?></textarea>
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

<script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Popup toggle for multi-select
    function togglePopup(selectElem) {
        const popup = selectElem.parentElement.querySelector('.popup-dropdown');
        document.querySelectorAll('.popup-dropdown').forEach(p => {
            if (p !== popup) p.style.display = 'none';
        });
        popup.style.display = (popup.style.display === 'block') ? 'none' : 'block';
    }

    // Filter options in popup
    function filterOptions(inputElem) {
        const filter = inputElem.value.toLowerCase();
        const options = inputElem.parentElement.querySelectorAll('.optionsList label');
        options.forEach(label => {
            label.style.display = label.textContent.toLowerCase().includes(filter) ? '' : 'none';
        });
    }

    // Select all options
    function selectAll(button) {
        const checkboxes = button.closest('.popup-dropdown').querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => cb.checked = true);
    }

    // Select none
    function selectNone(button) {
        const checkboxes = button.closest('.popup-dropdown').querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => cb.checked = false);
    }

    // Reset selection
    function resetSelection(button) {
        const checkboxes = button.closest('.popup-dropdown').querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => cb.checked = false);
        const selectElem = button.closest('.multi-select-container').querySelector('select');
        selectElem.value = '';
    }

    // Initialize CKEditor for Notes
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
    }, 500);

    // Client-side form validation
    document.getElementById('storyForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const fields = [
            { id: 'main_title', name: 'Main Title' },
            { id: 'second_title', name: 'Second Title' },
            { id: 'author', name: 'Author\'s' },
            { id: 'verb', name: 'Verb' },
            { id: 'description', name: 'Description Text' },
            { id: 'songLyricsOriginal', name: 'Notes' },
            { id: 'category', name: 'Category' },
            { id: 'thumbnail_url', name: 'Story Thumbnail Url' },
            { id: 'is_echo', name: 'Is this Story an Echo' },
            { id: 'is_class_room_idea', name: 'Is this Story a Classroom Idea' },
            { id: 'is_class_room_experiment', name: 'Classroom Experiment' },
            { id: 'related_songs', name: 'Related Songs' },
            { id: 'related_couplets', name: 'Related Couplets' },
            { id: 'related_words', name: 'Related Words' },
            { id: 'related_reflections', name: 'Related Reflections' },
            { id: 'related_people', name: 'Related People' },
            { id: 'related_films', name: 'Related Films' },
            { id: 'related_filmEpisode', name: 'Related FilmEpisode' },
            { id: 'is_story', name: 'Display on Stories Page' },
            { id: 'show_on_landing_page', name: 'Show On Landing Page' },
            { id: 'is_published', name: 'Publish' },
            { id: 'meta_title', name: 'Meta Title' },
            { id: 'meta_keywords', name: 'Meta Keyword' },
            { id: 'meta_description', name: 'Meta Description' }
        ];

        for (let field of fields) {
            let element = document.getElementById(field.id);
            let isEmpty = false;

            if (!element) continue;

            if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
                isEmpty = element.value.trim() === '';
            } else if (element.tagName === 'SELECT' && element.multiple) {
                isEmpty = element.selectedOptions.length === 0;
            } else if (element.tagName === 'SELECT' && !element.multiple) {
                isEmpty = element.value === '' || element.value === null;
            }

            if (isEmpty) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Input',
                    text: `Please fill the ${field.name}`,
                    confirmButtonText: 'OK'
                });
                element.focus();
                return false;
            }
        }

        this.submit();
    });
</script>
<?php 
include('inc/footer.php');
?>