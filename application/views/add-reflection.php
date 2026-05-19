<?php
include('inc/header.php');
include('inc/sidebar.php');
?>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" />
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
        margin-bottom: 40px;
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

    .btn-secondary { background: #6c757d; color: white; }
    .btn-success { background: #28a745; color: white; }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    #reflectionGjs {
        border: 1px solid #ced4da;
        min-height: 620px;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
    }

    .gjs-one-bg {
        background-color: #f8f9fa;
    }

    .gjs-two-color {
        color: #343a40;
    }

    .gjs-three-bg {
        background-color: #007bff;
        color: #fff;
    }

    .gjs-am-file-uploader {
        border: 1px dashed #b8c2cc;
        border-radius: 6px;
        background: #fff;
    }

    .gjs-am-assets-cont {
        background: #f7f9fc;
    }
</style>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Reflection Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="add_new">Home</a></li>
                        <li class="breadcrumb-item active">Add/Edit Reflection Details</li>
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

                    // Keywords: primary source = word_reflection junction (word_id, reflection_id); fallback to legacy CSV
                    $selected_related_keywords = [];
                    $reflectionIdForKw = isset($reflection->id) ? (int)$reflection->id : 0;
                    if ($reflectionIdForKw > 0 && $this->db->table_exists('word_reflection')) {
                        $jr = $this->db->select('word_id')->from('word_reflection')->where('reflection_id', $reflectionIdForKw)->get()->result_array();
                        foreach ($jr as $r) {
                            if (!empty($r['word_id'])) { $selected_related_keywords[] = (string)(int)$r['word_id']; }
                        }
                        $selected_related_keywords = array_values(array_unique($selected_related_keywords));
                    }
                    if (empty($selected_related_keywords) && isset($reflection->related_keywords)) {
                        $selected_related_keywords = $readSelectedValues($reflection->related_keywords);
                    }
                    // Junction-table sourced multi-selects (with legacy CSV fallback)
                    $reflJunctionRead = function ($table, $fkCol, $legacyField) use ($reflectionIdForKw, $reflection, $readSelectedValues) {
                        $ids = [];
                        if ($reflectionIdForKw > 0 && $this->db->table_exists($table)) {
                            $jr = $this->db->select($fkCol)->from($table)->where('reflection_id', $reflectionIdForKw)->get()->result_array();
                            foreach ($jr as $r) { if (!empty($r[$fkCol])) { $ids[] = (string)(int)$r[$fkCol]; } }
                            $ids = array_values(array_unique($ids));
                        }
                        if (empty($ids) && isset($reflection->$legacyField)) {
                            $ids = $readSelectedValues($reflection->$legacyField);
                        }
                        return $ids;
                    };
                    $selected_related_songs        = $reflJunctionRead('reflection_song',         'song_id',         'related_songs');
                    $selected_related_poems        = $reflJunctionRead('reflection_couplet',      'couplet_id',      'related_poems');
                    $selected_related_people       = $reflJunctionRead('reflection_person',       'person_id',       'related_people');
                    $selected_related_film_episodes = $reflJunctionRead('reflection_filmepisode', 'film_episode_id', 'related_episodes');
                    $selected_related_episodes = $selected_related_film_episodes; // alias used by Episodes select below
                    $selected_related_reflections = isset($reflection->related_reflections) ? $readSelectedValues($reflection->related_reflections) : [];
                    $selected_related_films = isset($reflection->related_films) ? $readSelectedValues($reflection->related_films) : [];
                    $selected_related_episodes = isset($reflection->related_episodes) ? $readSelectedValues($reflection->related_episodes) : [];
                    ?>

                    <form name="reflectionForm" id="reflectionForm" method="post" enctype="multipart/form-data" action="<?php echo isset($reflection) ? base_url('ReflectionController/update/' . $reflection->id) : base_url('ReflectionController/save'); ?>">
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Reflection Title <span style="color:red">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="title" id="title" class="form-control" value="<?php echo isset($reflection->title) ? $reflection->title : ''; ?>" placeholder="Enter Reflection Title" required>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Verb</label>
                            <div class="col-md-4">
                                <input type="text" name="verb" id="verb" class="form-control" value="<?php echo isset($reflection->verb) ? $reflection->verb : 'says'; ?>" placeholder="Enter Verb">
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Speaker/Author <span style="color:red">*</span></label>
                            <div class="col-md-4 d-flex align-items-center">
                                <select class="select2 form-control" multiple="multiple" name="speaker_id[]" id="speaker_id" data-placeholder="Select Speaker/Author" required>
                                    <option value="">None Selected</option>
                                    <?php 
                                    $speaker_rows = $this->db->query("
                                        SELECT id, first_name, middle_name, last_name FROM person
                                        ORDER BY LOWER(TRIM(CONCAT(COALESCE(first_name,''),' ',COALESCE(middle_name,''),' ',COALESCE(last_name,'')))) ASC
                                    ")->result();
                                    $selected_speakers = isset($reflection->speaker_id) ? explode(',', $reflection->speaker_id) : [];
                                    foreach ($speaker_rows as $p): 
                                        $parts = [];
                                        if (!empty(trim($p->first_name)))  { $parts[] = trim($p->first_name); }
                                        if (!empty(trim($p->middle_name))) { $parts[] = trim($p->middle_name); }
                                        if (!empty(trim($p->last_name)))   { $parts[] = trim($p->last_name); }
                                        $fullName = implode(' ', $parts);
                                        if ($fullName === '') { $fullName = 'Unnamed'; }
                                        $pid = (string)$p->id;
                                        $isSelected = in_array($pid, $selected_speakers);
                                    ?>
                                        <option value="<?= htmlspecialchars($pid) ?>" <?= $isSelected ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($fullName) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn btn-success btn-sm ml-2" id="addSpeakerBtn">Add New</button>
                                <button type="button" class="btn btn-primary btn-sm ml-1" id="editSpeakerBtn">Edit</button>
                            </div>
                        </div>

                        <!-- Add Speaker Modal -->
                        <div class="modal" id="addSpeakerModal">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2>Add New Speaker/Author</h2>
                                    <button class="close-btn" id="closeAddSpeaker">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" id="addSpeakerName" placeholder="Enter Speaker/Author Name">
                                    </div>
                                    <div class="form-group">
                                        <label>Hyperlink (Optional)</label>
                                        <input type="url" id="addSpeakerLink" placeholder="https://example.com">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn-secondary" id="cancelAddSpeaker">Cancel</button>
                                    <button class="btn-success" id="addSpeaker">Add</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Place <span style="color:red">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="interview_place" id="place" class="form-control" value="<?php echo isset($reflection->interview_place) ? $reflection->interview_place : (isset($reflection->place) ? $reflection->place : ''); ?>" placeholder="Enter Place" required>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Year <span style="color:red">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="interview_year" id="year" class="form-control" value="<?php echo isset($reflection->interview_year) ? $reflection->interview_year : (isset($reflection->year) ? $reflection->year : ''); ?>" placeholder="Enter Year" required>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Second Title</label>
                            <div class="col-md-4">
                                <input type="text" name="second_title" class="form-control" placeholder="Enter second title" value="<?php echo isset($reflection->second_title) ? $reflection->second_title : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Format <span style="color:red">*</span></label>
                            <div class="col-md-4 d-flex align-items-center">
                                <?php $selectedFormat = isset($reflection->format) ? trim((string) $reflection->format) : ''; ?>
                                <select name="format" id="format" class="form-control" required>
                                    <option value="">Select Format</option>
                                    <option value="Interview" <?php echo ($selectedFormat === 'Interview') ? 'selected' : ''; ?>>Interview</option>
                                    <option value="Essay" <?php echo ($selectedFormat === 'Essay') ? 'selected' : ''; ?>>Essay</option>
                                    <option value="Visual Story" <?php echo ($selectedFormat === 'Visual Story') ? 'selected' : ''; ?>>Visual Story</option>
                                    <option value="Audio Story" <?php echo ($selectedFormat === 'Audio Story') ? 'selected' : ''; ?>>Audio Story</option>
                                </select>
                                <button type="button" id="add_new_category" class="btn btn-secondary btn-sm ml-2">Add New Category</button>
                            </div>
                        </div>


                                <!-- INTERVIEW FIELDS -->
                                 <div style="padding-left:20px;">
                                <div class="format-section interview-section mt-3" style="display:none;">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Video (YouTube ID)</label>
                                            <input type="text" name="interview_video" class="form-control" placeholder="Enter YouTube video ID" value="<?php echo isset($reflection->interview_video) ? $reflection->interview_video : ''; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Audio Link</label>
                                            <input type="text" name="interview_audio" class="form-control" placeholder="Enter audio link" value="<?php echo isset($reflection->interview_audio) ? $reflection->interview_audio : ''; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Text Interview</label>
                                            <textarea name="interview_text" id="interview_text" class="form-control" rows="3" placeholder="Enter text interview"><?php echo isset($reflection->interview_text) ? $reflection->interview_text : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>About</label>
                                            <textarea name="interview_about" class="form-control" rows="3" placeholder="Enter about"><?php echo isset($reflection->interview_about) ? $reflection->interview_about : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                </div>

                                <!-- OTHER FORMAT FIELDS -->
                                <div class="format-section other-section mt-3" style="display:none;">
                                
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Select editor</label>
                                            <select name="editor_type" class="form-control" id="editor_type">
                                                <option value="">Select</option>
                                                <option value="Text Editor" <?php echo (isset($reflection->editor_type) && $reflection->editor_type == 'Text Editor') ? 'selected' : ''; ?>>Text Editor</option>
                                                <option value="Moduler Editor" <?php echo (isset($reflection->editor_type) && $reflection->editor_type == 'Moduler Editor') ? 'selected' : ''; ?>>Moduler Editor</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="textEditorRow" style="display:none;">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="text_editor_content">Text Editor Content</label>
                                            <textarea class="form-control" id="text_editor_content" name="text_editor_content" placeholder="Enter text editor content"><?php echo isset($reflection->text_editor_content) ? htmlspecialchars($reflection->text_editor_content) : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="modulerEditorRow" style="display:none;">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="moduler_editor_content">Moduler Editor Content</label>
                                            <input type="file" id="reflectionMediaImageInput" accept="image/*" multiple style="display:none;">
                                            <textarea class="form-control" id="moduler_editor_content" name="moduler_editor_content" placeholder="Enter moduler editor content" style="display:none;"><?php echo isset($reflection->moduler_editor_content) ? htmlspecialchars($reflection->moduler_editor_content) : ''; ?></textarea>
                                            <div id="reflectionGjs"></div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                </div>
                        <?php
                        $existingReflectionThumb = '';
                        if (isset($reflection->thumbnail_url) && trim((string) $reflection->thumbnail_url) !== '') {
                            $existingReflectionThumb = trim((string) $reflection->thumbnail_url);
                        }
                        $reflectionPublicBase = 'https://ajab.designanddevelopment.in/admin';
                        $reflectionThumbPreviewSrc = '';
                        if ($existingReflectionThumb !== '') {
                            if (preg_match('#^https?://#i', $existingReflectionThumb)) {
                                $reflectionThumbPreviewSrc = $existingReflectionThumb;
                            } else {
                                $rawThumb = ltrim($existingReflectionThumb, '/');
                                $thumbCandidates = [$rawThumb];
                                // Backward compatibility: older rows may store only filename.
                                if (stripos($rawThumb, 'uploads/') !== 0 && stripos($rawThumb, 'Uploads/') !== 0) {
                                    $thumbCandidates[] = 'uploads/thumbnails/' . $rawThumb;
                                    $thumbCandidates[] = 'Uploads/' . $rawThumb;
                                }
                                $thumbCandidates = array_values(array_unique($thumbCandidates));
                                foreach ($thumbCandidates as $candidate) {
                                    if (file_exists(FCPATH . $candidate)) {
                                        $reflectionThumbPreviewSrc = base_url($candidate);
                                        break;
                                    }
                                }
                                if ($reflectionThumbPreviewSrc === '') {
                                    // DB stores relative path like "/images/..." for production CDN/public host.
                                    if (isset($existingReflectionThumb[0]) && $existingReflectionThumb[0] === '/') {
                                        $reflectionThumbPreviewSrc = rtrim($reflectionPublicBase, '/') . $existingReflectionThumb;
                                    } else {
                                        $reflectionThumbPreviewSrc = rtrim($reflectionPublicBase, '/') . '/' . $rawThumb;
                                    }
                                }
                            }
                        }
                        ?>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Thumbnail Image Upload <?php if ($existingReflectionThumb === ''): ?><span style="color:red">*</span><?php endif; ?></label>
                            <div class="col-md-4">
                                <?php if ($existingReflectionThumb !== ''): ?>
                                <input type="hidden" name="thumbnail_url_existing" id="thumbnail_url_existing" value="<?php echo htmlspecialchars($existingReflectionThumb); ?>">
                                <?php endif; ?>
                                <input type="file" name="thumbnail_url" id="thumbnail_url" class="form-control" accept="image/*" <?php echo ($existingReflectionThumb === '') ? 'required' : ''; ?>>
                                <?php if ($existingReflectionThumb !== '' && $reflectionThumbPreviewSrc !== ''): ?>
                                <div class="mt-2" id="thumbnailPreviewWrap">
                                    <img src="<?php echo htmlspecialchars($reflectionThumbPreviewSrc); ?>" alt="Current thumbnail" id="thumbnailPreviewImg" style="max-width:200px;height:auto;border:1px solid #ddd;border-radius:4px;"
                                        onerror="this.style.display='none';var w=document.getElementById('thumbnailPreviewBroken');if(w)w.style.display='block';">
                                    <p class="small text-muted mt-1" id="thumbnailPreviewBroken" style="display:none;">Preview could not be loaded.</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Thumbnail Excerpt <span style="color:red">*</span></label>
                            <div class="col-md-4">
                                <?php
                                    // Thumbnail Excerpt = reflection.reflection_excerpt (canonical), with thumbnail_excerpt as legacy fallback
                                    $thumbExcerpt = '';
                                    if (isset($reflection->reflection_excerpt) && trim((string)$reflection->reflection_excerpt) !== '') {
                                        $thumbExcerpt = $reflection->reflection_excerpt;
                                    } elseif (isset($reflection->thumbnail_excerpt)) {
                                        $thumbExcerpt = $reflection->thumbnail_excerpt;
                                    }
                                ?>
                                <input type="text" name="thumbnail_excerpt" id="thumbnail_excerpt" class="form-control" value="<?php echo htmlspecialchars((string)$thumbExcerpt); ?>" placeholder="Enter Thumbnail Excerpt" required>
                            </div>
                        </div>
                            <!-- <div class="col-md-3">
                                <label>Reflection Type</label>
                                <div class="multi-select-container">
                                    <select class="select2" multiple="multiple" name="reflection_type[]" id="reflection_type" data-placeholder="Select Reflection Type">
                                        <option value="">None Selected</option>
                                        <option value="audio" <?php echo (isset($reflection->reflection_type) && in_array('audio', explode(',', $reflection->reflection_type))) ? 'selected' : ''; ?>>Audio</option>
                                        <option value="video" <?php echo (isset($reflection->reflection_type) && in_array('video', explode(',', $reflection->reflection_type))) ? 'selected' : ''; ?>>Video</option>
                                        <option value="text" <?php echo (isset($reflection->reflection_type) && in_array('text', explode(',', $reflection->reflection_type))) ? 'selected' : ''; ?>>Text</option>
                                    </select>
                                </div>
                            </div> -->
                            <!-- <div class="col-md-3">
                                <label>Youtube Video Id</label>
                                <input type="text" name="youtube_video_id" id="youtube_video_id" class="form-control" value="<?php echo isset($reflection->youtube_video_id) ? $reflection->youtube_video_id : ''; ?>" placeholder="Enter Youtube Video Id">
                            </div> -->
                            <!-- <div class="col-md-3">
                                <label>Duration</label>
                                <input type="text" name="duration" id="duration" class="form-control" value="<?php echo isset($reflection->duration) ? $reflection->duration : ''; ?>" placeholder="Enter Duration">
                            </div> -->
<label>Related Content</label>
                        <div style="padding-left:20px;">
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Keywords</label>
                            <div class="col-md-4 d-flex align-items-center">
                                <select class="select2 form-control" multiple="multiple" name="related_keywords[]" id="related_keywords" data-placeholder="Select Keywords">
                                    <?php
                                    $keyword_rows = $this->db->table_exists('word')
                                        ? $this->db->query("SELECT id, word_transliteration FROM word ORDER BY LOWER(TRIM(COALESCE(word_transliteration,''))) DESC, id DESC")->result()
                                        : [];
                                    foreach ($keyword_rows as $keyword_row):
                                        $kid = (string)$keyword_row->id;
                                        $isSelected = in_array($kid, $selected_related_keywords, true);
                                    ?>
                                        <option value="<?= htmlspecialchars($kid) ?>" <?= $isSelected ? 'selected' : '' ?>><?= htmlspecialchars($keyword_row->word_transliteration) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn btn-success btn-sm ml-2" id="addNewKeywordBtn">Add New</button>
                                <button type="button" class="btn btn-primary btn-sm ml-1" id="editKeywordBtn">Edit</button>
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
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn-secondary" id="cancelAddNewKeyword">Cancel</button>
                                        <button class="btn-success" id="addKeywordConfirm">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Songs</label>
                            <div class="col-md-4 d-flex align-items-center">
                                <select class="select2 form-control" multiple="multiple" name="related_songs[]" id="related_songs" data-placeholder="Select Songs">
                                    <?php
                                    $song_rows = $this->db->query("SELECT id, umbrellaTitle FROM songs ORDER BY id DESC")->result();
                                    foreach ($song_rows as $song_row):
                                        $sid = (string)$song_row->id;
                                        $isSelected = in_array($sid, $selected_related_songs, true);
                                    ?>
                                        <option value="<?= htmlspecialchars($sid) ?>" <?= $isSelected ? 'selected' : '' ?>><?= htmlspecialchars($song_row->umbrellaTitle) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Add New Song Modal -->
                            <div class="modal" id="addNewSongModal">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add New Song</h5>
                                        <button class="close-btn" id="closeAddNewSong">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" id="newSongTitle" class="form-control" placeholder="Enter Song Title">
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn-secondary" id="cancelAddNewSong">Cancel</button>
                                        <button class="btn-success" id="addSongConfirm">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Poems</label>
                            <div class="col-md-4 d-flex align-items-center">
                                <select class="select2 form-control" multiple="multiple" name="related_poems[]" id="related_poems" data-placeholder="Select Poems">
                                    <?php
                                    $poem_rows = $this->db->query("SELECT id, COALESCE(NULLIF(couplet_transliteration, ''), original_title) AS poem_label FROM couplet ORDER BY id DESC")->result();
                                    foreach ($poem_rows as $poem_row):
                                        $pid = (string)$poem_row->id;
                                        $isSelected = in_array($pid, $selected_related_poems, true);
                                    ?>
                                        <option value="<?= htmlspecialchars($pid) ?>" <?= $isSelected ? 'selected' : '' ?>><?= htmlspecialchars($poem_row->poem_label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Add New Poem Modal -->
                            <div class="modal" id="addNewPoemModal">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add New Poem</h5>
                                        <button class="close-btn" id="closeAddNewPoem">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" id="newPoemTitle" class="form-control" placeholder="Enter Poem Title">
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn-secondary" id="cancelAddNewPoem">Cancel</button>
                                        <button class="btn-success" id="addPoemConfirm">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Reflections</label>
                            <div class="col-md-4 d-flex align-items-center">
                                <select class="select2 form-control" multiple="multiple" name="related_reflections[]" id="related_reflections" data-placeholder="Select Reflections">
                                    <?php
                                    $reflection_rows = $this->db->query("SELECT id, title FROM reflection ORDER BY id DESC")->result();
                                    foreach ($reflection_rows as $reflection_row):
                                        $rid = (string)$reflection_row->id;
                                        $isSelected = in_array($rid, $selected_related_reflections, true);
                                    ?>
                                        <option value="<?= htmlspecialchars($rid) ?>" <?= $isSelected ? 'selected' : '' ?>><?= htmlspecialchars($reflection_row->title) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Add New Reflection Modal -->
                            <div class="modal" id="addNewReflectionModal">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add New Reflection</h5>
                                        <button class="close-btn" id="closeAddNewReflection">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" id="newReflectionTitle" class="form-control" placeholder="Enter Reflection Title">
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn-secondary" id="cancelAddNewReflection">Cancel</button>
                                        <button class="btn-success" id="addReflectionConfirm">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ People</label>
                            <div class="col-md-4 d-flex align-items-center">
                                <select class="select2 form-control" multiple="multiple" name="related_people[]" id="related_people" data-placeholder="Select People">
                                    <?php
                                    $person_rows = $this->db->query("SELECT id, first_name, middle_name, last_name FROM person ORDER BY id DESC")->result();
                                    foreach ($person_rows as $person_row):
                                        $personId = (string)$person_row->id;
                                        $isSelected = in_array($personId, $selected_related_people, true);
                                        $parts = [];
                                        if (!empty(trim($person_row->first_name))) { $parts[] = trim($person_row->first_name); }
                                        if (!empty(trim($person_row->middle_name))) { $parts[] = trim($person_row->middle_name); }
                                        if (!empty(trim($person_row->last_name))) { $parts[] = trim($person_row->last_name); }
                                        $fullName = implode(' ', $parts);
                                        if ($fullName === '') { $fullName = 'Unnamed'; }
                                    ?>
                                        <option value="<?= htmlspecialchars($personId) ?>" <?= $isSelected ? 'selected' : '' ?>><?= htmlspecialchars($fullName) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Add New Person Modal -->
                            <div class="modal" id="addNewPersonModal">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add New Person</h5>
                                        <button class="close-btn" id="closeAddNewPerson">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" id="newPersonName" class="form-control" placeholder="Enter Person Name">
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn-secondary" id="cancelAddNewPerson">Cancel</button>
                                        <button class="btn-success" id="addPersonConfirm">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Films</label>
                            <div class="col-md-4 d-flex align-items-center">
                                <select class="select2 form-control" multiple="multiple" name="related_films[]" id="related_films" data-placeholder="Select Films">
                                    <?php
                                    $film_rows = $this->db->query("SELECT id, COALESCE(NULLIF(english_transliteration, ''), NULLIF(english_translation, ''), original_title) AS film_label FROM film ORDER BY id DESC")->result();
                                    foreach ($film_rows as $film_row):
                                        $fid = (string)$film_row->id;
                                        $isSelected = in_array($fid, $selected_related_films, true);
                                    ?>
                                        <option value="<?= htmlspecialchars($fid) ?>" <?= $isSelected ? 'selected' : '' ?>><?= htmlspecialchars($film_row->film_label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Add New Film Modal -->
                            <div class="modal" id="addNewFilmModal">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add New Film</h5>
                                        <button class="close-btn" id="closeAddNewFilm">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" id="newFilmTitle" class="form-control" placeholder="Enter Film Title">
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn-secondary" id="cancelAddNewFilm">Cancel</button>
                                        <button class="btn-success" id="addFilmConfirm">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Film Episode</label>
                            <div class="col-md-4 d-flex align-items-center">
                                <select class="select2 form-control" multiple="multiple" name="related_episodes[]" id="related_episodes" data-placeholder="Select Film Episodes">
                                    <?php
                                    $episode_rows = $this->db->query("SELECT id, COALESCE(NULLIF(english_transliteration, ''), NULLIF(english_translation, ''), original_title) AS episode_label FROM film_episode ORDER BY id DESC")->result();
                                    foreach ($episode_rows as $episode_row):
                                        $eid = (string)$episode_row->id;
                                        $isSelected = in_array($eid, $selected_related_episodes, true);
                                    ?>
                                        <option value="<?= htmlspecialchars($eid) ?>" <?= $isSelected ? 'selected' : '' ?>><?= htmlspecialchars($episode_row->episode_label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Add New Episode Modal -->
                            <div class="modal" id="addNewEpisodeModal">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add New Film Episode</h5>
                                        <button class="close-btn" id="closeAddNewEpisode">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" id="newEpisodeTitle" class="form-control" placeholder="Enter Episode Title">
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn-secondary" id="cancelAddNewEpisode">Cancel</button>
                                        <button class="btn-success" id="addEpisodeConfirm">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <label>Meta Data </label>
                        <div style="padding-left:20px;">
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Meta Title</label>
                            <div class="col-md-4">
                                <input type="text" name="meta_title" id="meta_title" class="form-control" value="<?php echo isset($reflection->meta_title) ? $reflection->meta_title : ''; ?>" placeholder="Enter Meta Title">
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Meta Keywords</label>
                            <div class="col-md-4">
                                <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" value="<?php echo isset($reflection->meta_keywords) ? $reflection->meta_keywords : ''; ?>" placeholder="Enter Meta Keywords">
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Meta Description</label>
                            <div class="col-md-4">
                                <textarea class="form-control" name="meta_description" id="meta_description" rows="3" placeholder="Enter Meta Description"><?php echo isset($reflection->meta_description) ? $reflection->meta_description : ''; ?></textarea>
                            </div>
                        </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Publish Status</label>
                            <div class="col-md-4">
                                <?php
                                    // Normalize publish — DB may store 1 / '1' / true / 'true' / 'yes' (or the inverses)
                                    $rawRefPublish = isset($reflection->publish) ? $reflection->publish : '';
                                    $refPublishVal = 'no';
                                    if ($rawRefPublish === 1 || $rawRefPublish === '1' || $rawRefPublish === true ||
                                        (is_string($rawRefPublish) && in_array(strtolower($rawRefPublish), ['true','yes','y','1'], true))) {
                                        $refPublishVal = 'yes';
                                    }
                                ?>
                                <select name="publish" id="publish" class="form-control">
                                    <option value="no"  <?php echo $refPublishVal === 'no'  ? 'selected' : ''; ?>>No</option>
                                    <option value="yes" <?php echo $refPublishVal === 'yes' ? 'selected' : ''; ?>>Yes</option>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/grapesjs"></script>
<script src="https://unpkg.com/grapesjs-preset-webpage"></script>
<script src="https://unpkg.com/grapesjs-blocks-basic"></script>

<script>
// Utility to open/close modals
function openModal(id) { document.getElementById(id).style.display = 'block'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

// Add New Keyword
document.getElementById('addNewKeywordBtn').onclick = function() { openModal('addNewKeywordModal'); };
document.getElementById('closeAddNewKeyword').onclick = function() { closeModal('addNewKeywordModal'); };
document.getElementById('cancelAddNewKeyword').onclick = function() { closeModal('addNewKeywordModal'); };
document.getElementById('addKeywordConfirm').onclick = async function() {
    var val = document.getElementById('newKeywordTransliteration').value.trim();
    if(val) {
        var btn = document.getElementById('addKeywordConfirm');
        btn.disabled = true;
        try {
            var res = await fetch('<?= base_url('SongController/ajax_create_keyword') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'word_transliteration=' + encodeURIComponent(val)
            });
            var data = await res.json();
            if (data && data.status === 'success') {
                var sel = document.getElementById('related_keywords');
                var option = document.createElement('option');
                option.value = data.keyword_id || data.id;
                option.text = data.word_transliteration || val;
                option.selected = true;
                sel.add(option);
                if (window.jQuery && $('#related_keywords').length) $('#related_keywords').trigger('change');
                closeModal('addNewKeywordModal');
                document.getElementById('newKeywordTransliteration').value = '';
                if (window.Swal) Swal.fire({icon:'success',title:'Success',text:data.message || 'Keyword added!',timer:1200,showConfirmButton:false});
            } else {
                alert((data && data.message) ? data.message : 'Failed to add keyword!');
            }
        } catch (e) {
            alert('Error: ' + e.message);
        }
        btn.disabled = false;
    }
};

</script>

<script>
$(document).ready(function() {
    window.reflectionModularBuilder = null;
    var modularBuilderInitialized = false;

    function decodeHtmlEntities(value) {
        var temp = document.createElement('textarea');
        temp.innerHTML = value || '';
        return temp.value || '';
    }

    function getModularBuilderOutput() {
        if (!window.reflectionModularBuilder) {
            return $('#moduler_editor_content').val() || '';
        }
        var html = window.reflectionModularBuilder.getHtml() || '';
        var css = window.reflectionModularBuilder.getCss() || '';
        var js = window.reflectionModularBuilder.getJs() || '';
        var output = '';
        if (css.trim()) {
            output += '<style>' + css + '</style>';
        }
        output += html;
        if (js.trim()) {
            output += '<script>' + js + '<\/script>';
        }
        return output;
    }

    function ensureModularEditor() {
        if (modularBuilderInitialized || !document.getElementById('reflectionGjs') || typeof grapesjs === 'undefined') {
            return;
        }
        modularBuilderInitialized = true;

        var rawInitial = decodeHtmlEntities($('#moduler_editor_content').val() || '');
        window.reflectionModularBuilder = grapesjs.init({
            container: '#reflectionGjs',
            fromElement: false,
            height: '620px',
            width: 'auto',
            storageManager: false,
            avoidInlineStyle: false,
            showOffsets: true,
            noticeOnUnload: false,
            plugins: ['gjs-preset-webpage', 'gjs-blocks-basic'],
            pluginsOpts: {
                'gjs-preset-webpage': {
                    navbarOpts: false,
                    countdownOpts: false,
                    formsOpts: true,
                    blocksBasicOpts: {
                        flexGrid: true
                    }
                },
                'gjs-blocks-basic': {
                    blocks: ['column1', 'column2', 'column3', 'text', 'link', 'image', 'video'],
                    flexGrid: true,
                    category: 'Basic'
                }
            },
            assetManager: {
                upload: true,
                uploadName: 'files',
                uploadText: 'Upload Images (drag/drop ya click)',
                showUrlInput: true,
                embedAsBase64: true,
                autoAdd: true,
                assets: [],
                uploadFile: function(e) {
                    var files = e && e.dataTransfer ? e.dataTransfer.files : (e && e.target ? e.target.files : []);
                    if (!files || !files.length) {
                        return;
                    }

                    Array.from(files).forEach(function(file) {
                        if (!file.type || file.type.indexOf('image/') !== 0) {
                            return;
                        }
                        var reader = new FileReader();
                        reader.onload = function(event) {
                            window.reflectionModularBuilder.AssetManager.add({
                                src: event.target.result,
                                name: file.name,
                                type: 'image'
                            });
                        };
                        reader.readAsDataURL(file);
                    });
                }
            }
        });

        window.reflectionModularBuilder.setComponents(rawInitial || '');
    }

    window.getReflectionModularBuilderOutput = getModularBuilderOutput;

    function toggleEditorRows() {
        var val = $('#editor_type').val();
        if (val === 'Text Editor') {
            $('#textEditorRow').show();
            $('#modulerEditorRow').hide();
        } else if (val === 'Moduler Editor') {
            $('#textEditorRow').hide();
            $('#modulerEditorRow').show();
            ensureModularEditor();
        } else {
            $('#textEditorRow').hide();
            $('#modulerEditorRow').hide();
        }
    }
    $('#editor_type').on('change', toggleEditorRows);
    toggleEditorRows();

    setTimeout(function() {
        if (document.getElementById('text_editor_content')) {
            CKEDITOR.replace('text_editor_content', {
                height: 200,
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
        }
    }, 500);
});
</script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select an option",
        allowClear: true
    });

    // Initialize CKEditor
    setTimeout(function() {
        CKEDITOR.replace('interview_text', {
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

    $('#add_new_category').click(function(){
        var new_cat = prompt('Enter new category name');
        if(new_cat){
            $('#format').append('<option value="' + new_cat + '">' + new_cat + '</option>');
        }
    });

    // Form validation
    $('#reflectionForm').on('submit', function(e) {
        e.preventDefault();

        if ($('#editor_type').val() === 'Moduler Editor' && window.reflectionModularBuilder && typeof window.getReflectionModularBuilderOutput === 'function') {
            $('#moduler_editor_content').val(window.getReflectionModularBuilderOutput());
        }

        // Update CKEditor data
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }

        // Only fields marked with red `*` in the form are required.
        const fields = [
            { id: 'title',             name: 'Reflection Title',     type: 'input',       required: true },
            { id: 'speaker_id',        name: 'Speaker/Author',       type: 'multiselect', required: true },
            { id: 'format',            name: 'Format',               type: 'select',      required: true },
            { id: 'place',             name: 'Place',                type: 'input',       required: true },
            { id: 'year',              name: 'Year',                 type: 'input',       required: true },
            { id: 'thumbnail_url',     name: 'Thumbnail Image Upload', type: 'file',     required: true },
            { id: 'thumbnail_excerpt', name: 'Thumbnail Excerpt',    type: 'input',       required: true }
        ];

        for (let field of fields) {
            if (!field.required) continue;
            let element = document.getElementById(field.id);
            let isEmpty = false;

            if (!element) {
                console.warn(`Field with id ${field.id} not found`);
                continue;
            }

            if (field.type === 'input' || field.type === 'textarea') {
                let value = element.value.trim();
                isEmpty = value === '';
            } else if (field.type === 'select') {
                let value = element.value;
                isEmpty = value === '' || value === null;
            } else if (field.type === 'multiselect') {
                isEmpty = !element.selectedOptions || element.selectedOptions.length === 0;
            } else if (field.type === 'file') {
                isEmpty = !element.files || element.files.length === 0;
                if (isEmpty && field.id === 'thumbnail_url') {
                    var exThumb = document.getElementById('thumbnail_url_existing');
                    if (exThumb && exThumb.value && exThumb.value.trim() !== '') {
                        isEmpty = false;
                    }
                }
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

        let speakerSelected = document.getElementById('speaker_id').selectedOptions.length > 0;
        let speakerManual = document.getElementById('speaker_manual') ? document.getElementById('speaker_manual').value.trim() !== '' : false;
        if (!speakerSelected && !speakerManual) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Input',
                text: 'Please select Speaker/Author from dropdown',
                confirmButtonText: 'OK'
            });
            document.getElementById('speaker_id').focus();
            return false;
        }

        this.submit();
    });
});
</script>
       
<script>
$(document).ready(function(){
  $('#format').change(function(){
    var selected = $(this).val();

    // Hide all sections first
    $('.format-section').hide();

    // Show only the selected format fields
    if(selected === 'Interview'){
      $('.interview-section').show();
    } else if(selected === 'Essay' || selected === 'Visual Story' || selected === 'Audio Story'){
      $('.other-section').show();
    }
  });

  // Apply format section visibility on page load (edit mode)
  $('#format').trigger('change');

  // Speaker Modal Handlers
  const addSpeakerModal = document.getElementById('addSpeakerModal');
  const addSpeakerBtn = document.getElementById('addSpeakerBtn');
  const closeAddSpeaker = document.getElementById('closeAddSpeaker');
  const cancelAddSpeaker = document.getElementById('cancelAddSpeaker');
  const addSpeaker = document.getElementById('addSpeaker');
  const speakerSelect = document.getElementById('speaker_id');
  const BASE_URL = '<?= base_url() ?>';

  // Open modal
  addSpeakerBtn.onclick = () => { addSpeakerModal.style.display = 'block'; };

  // Close modal
  [closeAddSpeaker, cancelAddSpeaker].forEach(btn => btn.onclick = () => addSpeakerModal.style.display = 'none');

  // Outside click close
  window.onclick = (event) => {
    if (event.target === addSpeakerModal) addSpeakerModal.style.display = 'none';
  };

  // Add new speaker (persist to DB via AJAX)
  addSpeaker.onclick = async () => {
    const btn = addSpeaker;
    const newSpeaker = document.getElementById('addSpeakerName').value.trim();
    const newLink = document.getElementById('addSpeakerLink').value.trim();
    if (!newSpeaker) {
      alert("⚠️ Please enter a name before adding!");
      return;
    }
    try {
      btn.disabled = true;
      const res = await fetch(BASE_URL + 'person/ajax-create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'name=' + encodeURIComponent(newSpeaker) + '&hyperlink=' + encodeURIComponent(newLink)
      });
      const data = await res.json();
      if (data && data.success) {
        const option = document.createElement('option');
        option.value = data.id;
        option.text = data.fullName || newSpeaker;
        option.selected = true;
        speakerSelect.add(option);
        alert("✅ New speaker added and selected!");
        document.getElementById('addSpeakerName').value = '';
        document.getElementById('addSpeakerLink').value = '';
        addSpeakerModal.style.display = 'none';
      } else {
        alert("❌ Failed to add speaker: " + (data && data.message ? data.message : 'Unknown error'));
      }
    } catch (e) {
      alert("❌ Error adding speaker: " + e.message);
    } finally {
      btn.disabled = false;
    }
  };
});
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
  bindEdit('editSpeakerBtn', {
    selectId: '#speaker_id', modalId: '#addSpeakerModal', addSaveBtnId: '#addSpeaker',
    updateUrl: BASE + 'song/ajax_update_person', editTitle: 'Edit Speaker',
    extraPayload: { type_id: 1 },
    fields: [
      { inputId: '#addSpeakerName', postKey: 'name',      primary: true },
      { inputId: '#addSpeakerLink', postKey: 'hyperlink', optionDataKey: 'hyperlink' }
    ]
  });
  bindEdit('editKeywordBtn', {
    selectId: '#related_keywords', modalId: '#addNewKeywordModal', addSaveBtnId: '#addKeywordConfirm',
    updateUrl: BASE + 'song/ajax_update_keyword', editTitle: 'Edit Keyword',
    fields: [{ inputId: '#newKeywordTransliteration', postKey: 'word_transliteration', primary: true }]
  });
})();
</script>
<?php include('inc/footer.php'); ?>