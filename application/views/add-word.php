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
</style>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Word Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="add_new">Home</a></li>
                        <li class="breadcrumb-item active">Add/Edit Word Details</li>
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
                        <a href="javascript:void(0);" 
                            onclick="window.history.back();" 
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

                    <form name="wordForm" id="wordForm" method="post" action="<?php echo isset($word) ? base_url('word/update/' . $word->id) : base_url('word/save'); ?>">
                        <div class="row">
                            <div class="col-12">
                                <label>1. Word Transliteration</label>
                                <input type="text" name="word_transliteration" id="word_transliteration" class="form-control" value="<?php echo isset($word->word_transliteration) ? $word->word_transliteration : ''; ?>" placeholder="Enter Word Transliteration" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label>2. Is this a Keyword?</label>
                                <input type="checkbox" name="is_this_keyword" id="is_this_keyword" onchange="toggleKeywordFields()" <?php echo (isset($word->is_this_keyword) && $word->is_this_keyword == 'true') ? 'checked' : ''; ?>>
                            </div>
                        </div>
                        <div id="keyword_fields" style="display:<?php echo (isset($word->is_this_keyword) && $word->is_this_keyword == 'true') ? 'block' : 'none'; ?>;">
                            <div class="row">
                                <div class="col-12">
                                    <label>Word Translation</label>
                                    <input type="text" name="word_translation" id="word_translation" class="form-control" value="<?php echo isset($word->word_translation) ? $word->word_translation : ''; ?>" placeholder="Enter Word Translation" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label>Related Content - Songs</label>
                                    <div class="multi-select-container">
                                        <select class="select2" multiple="multiple" name="related_songs[]" id="related_songs" data-placeholder="Select Related Songs">
                                            <option value="">None Selected</option>
                                            <option value="1" <?php echo (isset($word->related_songs) && in_array('1', explode(',', $word->related_songs))) ? 'selected' : ''; ?>>Die before your death</option>
                                            <option value="2" <?php echo (isset($word->related_songs) && in_array('2', explode(',', $word->related_songs))) ? 'selected' : ''; ?>>Live without regrets</option>
                                            <option value="3" <?php echo (isset($word->related_songs) && in_array('3', explode(',', $word->related_songs))) ? 'selected' : ''; ?>>Chase your dreams</option>
                                            <option value="4" <?php echo (isset($word->related_songs) && in_array('4', explode(',', $word->related_songs))) ? 'selected' : ''; ?>>Silence is golden</option>
                                            <option value="5" <?php echo (isset($word->related_songs) && in_array('5', explode(',', $word->related_songs))) ? 'selected' : ''; ?>>Happiness is a choice</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label>Related Content - Poems</label>
                                    <div class="multi-select-container">
                                        <select class="select2" multiple="multiple" name="related_poems[]" id="related_poems" data-placeholder="Select Related Poems">
                                            <option value="">None Selected</option>
                                            <option value="1" <?php echo (isset($word->related_poems) && in_array('1', explode(',', $word->related_poems))) ? 'selected' : ''; ?>>John Smith</option>
                                            <option value="2" <?php echo (isset($word->related_poems) && in_array('2', explode(',', $word->related_poems))) ? 'selected' : ''; ?>>Emily Johnson</option>
                                            <option value="3" <?php echo (isset($word->related_poems) && in_array('3', explode(',', $word->related_poems))) ? 'selected' : ''; ?>>Michael Brown</option>
                                            <option value="4" <?php echo (isset($word->related_poems) && in_array('4', explode(',', $word->related_poems))) ? 'selected' : ''; ?>>Sarah Davis</option>
                                            <option value="5" <?php echo (isset($word->related_poems) && in_array('5', explode(',', $word->related_poems))) ? 'selected' : ''; ?>>David Wilson</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label>Related Content - Reflections</label>
                                    <div class="multi-select-container">
                                        <select class="select2" multiple="multiple" name="related_reflections[]" id="related_reflections" data-placeholder="Select Related Reflections">
                                            <option value="">None Selected</option>
                                            <option value="1" <?php echo (isset($word->related_reflections) && in_array('1', explode(',', $word->related_reflections))) ? 'selected' : ''; ?>>John Smith</option>
                                            <option value="2" <?php echo (isset($word->related_reflections) && in_array('2', explode(',', $word->related_reflections))) ? 'selected' : ''; ?>>Emily Johnson</option>
                                            <option value="3" <?php echo (isset($word->related_reflections) && in_array('3', explode(',', $word->related_reflections))) ? 'selected' : ''; ?>>Michael Brown</option>
                                            <option value="4" <?php echo (isset($word->related_reflections) && in_array('4', explode(',', $word->related_reflections))) ? 'selected' : ''; ?>>Sarah Davis</option>
                                            <option value="5" <?php echo (isset($word->related_reflections) && in_array('5', explode(',', $word->related_reflections))) ? 'selected' : ''; ?>>David Wilson</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label>Related Content - Films</label>
                                    <div class="multi-select-container">
                                        <select class="select2" multiple="multiple" name="related_films[]" id="related_films" data-placeholder="Select Related Films">
                                            <option value="">None Selected</option>
                                            <option value="1" <?php echo (isset($word->related_films) && in_array('1', explode(',', $word->related_films))) ? 'selected' : ''; ?>>John Smith</option>
                                            <option value="2" <?php echo (isset($word->related_films) && in_array('2', explode(',', $word->related_films))) ? 'selected' : ''; ?>>Emily Johnson</option>
                                            <option value="3" <?php echo (isset($word->related_films) && in_array('3', explode(',', $word->related_films))) ? 'selected' : ''; ?>>Michael Brown</option>
                                            <option value="4" <?php echo (isset($word->related_films) && in_array('4', explode(',', $word->related_films))) ? 'selected' : ''; ?>>Sarah Davis</option>
                                            <option value="5" <?php echo (isset($word->related_films) && in_array('5', explode(',', $word->related_films))) ? 'selected' : ''; ?>>David Wilson</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label>Related Content - Film Episode</label>
                                    <div class="multi-select-container">
                                        <select class="select2" multiple="multiple" name="Related_film_episode[]" id="Related_film_episode" data-placeholder="Select Related Film Episode">
                                            <option value="">None Selected</option>
                                            <option value="1" <?php echo (isset($word->Related_film_episode) && in_array('1', explode(',', $word->Related_film_episode))) ? 'selected' : ''; ?>>John Smith</option>
                                            <option value="2" <?php echo (isset($word->Related_film_episode) && in_array('2', explode(',', $word->Related_film_episode))) ? 'selected' : ''; ?>>Emily Johnson</option>
                                            <option value="3" <?php echo (isset($word->Related_film_episode) && in_array('3', explode(',', $word->Related_film_episode))) ? 'selected' : ''; ?>>Michael Brown</option>
                                            <option value="4" <?php echo (isset($word->Related_film_episode) && in_array('4', explode(',', $word->Related_film_episode))) ? 'selected' : ''; ?>>Sarah Davis</option>
                                            <option value="5" <?php echo (isset($word->Related_film_episode) && in_array('5', explode(',', $word->Related_film_episode))) ? 'selected' : ''; ?>>David Wilson</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label>3. Is this a Glossary Word?</label>
                                <input type="checkbox" name="is_glossary_word" id="is_glossary_word" onchange="toggleGlossaryFields()" <?php echo (isset($word->is_glossary_word) && $word->is_glossary_word == 'true') ? 'checked' : ''; ?>>
                            </div>
                        </div>
                        <div id="glossary_fields" style="display:<?php echo (isset($word->is_glossary_word) && $word->is_glossary_word == 'true') ? 'block' : 'none'; ?>;">
                            <div class="row">
                                <div class="col-12">
                                    <label>Diacritic text entry box</label>
                                    <input type="text" name="entry_box" id="entry_box" class="form-control" value="<?php echo isset($word->entry_box) ? $word->entry_box : ''; ?>" placeholder="Enter Diacritic text entry box">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label>Glossary Meaning</label>
                                    <textarea id="glossary_meaning" name="glossary_meaning"><?php echo isset($word->glossary_meaning) ? $word->glossary_meaning : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label>Meta Data - Meta Title</label>
                                    <input type="text" name="meta_title" id="meta_title" class="form-control" value="<?php echo isset($word->meta_title) ? $word->meta_title : ''; ?>" placeholder="Enter Meta Title">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label>Meta Data - Meta Keywords</label>
                                    <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" value="<?php echo isset($word->meta_keywords) ? $word->meta_keywords : ''; ?>" placeholder="Enter Meta Keywords">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label>Meta Data - Meta Description</label>
                                    <textarea class="form-control" name="meta_description" id="meta_description" rows="3" placeholder="Enter Meta Description"><?php echo isset($word->meta_description) ? $word->meta_description : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label>5. Publish Status</label>
                                <select name="publish" id="publish" class="form-control">
                                    <option value="">Select</option>
                                    <option value="true" <?php echo (isset($word->publish) && $word->publish == 'true') ? 'selected' : ''; ?>>Yes</option>
                                    <option value="false" <?php echo (isset($word->publish) && $word->publish == 'false') ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="save-btn-container" style="margin-top: 30px;">
                            <?= admin_edit_preview_button(isset($word) ? $word : null) ?>
                            <button type="submit" class="btn btn-primary btn-lg save-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });
    });

    function toggleKeywordFields() {
        const isChecked = document.getElementById('is_this_keyword').checked;
        document.getElementById('keyword_fields').style.display = isChecked ? 'block' : 'none';
    }

    function toggleGlossaryFields() {
        const isChecked = document.getElementById('is_glossary_word').checked;
        document.getElementById('glossary_fields').style.display = isChecked ? 'block' : 'none';
    }

    document.getElementById('wordForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Update CKEditor data
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }

        const fields = [
            { id: 'word_transliteration', name: 'Word Transliteration', type: 'input', required: true },
            { id: 'is_this_keyword', name: 'Is this a Keyword?', type: 'checkbox' },
            { id: 'word_translation', name: 'Word Translation', type: 'input', required: true, conditional: 'is_this_keyword' },
            { id: 'related_songs', name: 'Related Songs', type: 'multiselect', conditional: 'is_this_keyword' },
            { id: 'related_poems', name: 'Related Poems', type: 'multiselect', conditional: 'is_this_keyword' },
            { id: 'related_reflections', name: 'Related Reflections', type: 'multiselect', conditional: 'is_this_keyword' },
            { id: 'related_films', name: 'Related Films', type: 'multiselect', conditional: 'is_this_keyword' },
            { id: 'Related_film_episode', name: 'Related Film Episode', type: 'multiselect', conditional: 'is_this_keyword' },
            { id: 'is_glossary_word', name: 'Is this a Glossary Word?', type: 'checkbox' },
            { id: 'entry_box', name: 'Diacritic text entry box', type: 'input', conditional: 'is_glossary_word' },
            { id: 'glossary_meaning', name: 'Glossary Meaning', type: 'textarea', conditional: 'is_glossary_word' },
            { id: 'meta_title', name: 'Meta Title', type: 'input', conditional: 'is_glossary_word' },
            { id: 'meta_keywords', name: 'Meta Keywords', type: 'input', conditional: 'is_glossary_word' },
            { id: 'meta_description', name: 'Meta Description', type: 'textarea', conditional: 'is_glossary_word' },
            { id: 'publish', name: 'Publish Status', type: 'select', required: true }
        ];

        for (let field of fields) {
            if (field.conditional) {
                const checkbox = document.getElementById(field.conditional);
                if (!checkbox.checked) continue;
            }
            let element = document.getElementById(field.id);
            if (!element) continue;
            let isEmpty = false;
            if (field.type === 'input' || field.type === 'textarea') {
                let value = element.value.trim();
                isEmpty = value === '';
            } else if (field.type === 'select') {
                let value = element.value;
                isEmpty = value === '' || value === null;
            } else if (field.type === 'multiselect') {
                let selectedOptions = element.selectedOptions;
                isEmpty = selectedOptions.length === 0;
            }
            if (field.required && isEmpty) {
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

    // Initialize CKEditor
    setTimeout(function() {
        const editorIDs = ['glossary_meaning'];
        editorIDs.forEach(function(id) {
            CKEDITOR.replace(id, {
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
        });
    }, 500);
</script>

<?php include('inc/footer.php'); ?>