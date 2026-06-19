<?php
include('inc/header.php');
include('inc/sidebar.php');
?>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" />
</head>

<style>
    /* Same CSS as Reflection */
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

    .custom-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.45);
        align-items: center;
        justify-content: center;
    }

    .custom-modal-content {
        background: #fff;
        width: 90%;
        max-width: 420px;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    }

    .custom-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 15px;
    }

    #personAboutGjs {
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
                    <h1>People Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="add_new">Home</a></li>
                        <li class="breadcrumb-item active">Add/Edit People Details</li>
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

                    $selected_keywords = isset($person->keywords) ? $readSelectedValues($person->keywords) : [];
                    $selected_songs = isset($person->songs) ? $readSelectedValues($person->songs) : [];
                    $selected_poems = isset($person->poems) ? $readSelectedValues($person->poems) : [];
                    $selected_reflections = isset($person->reflections) ? $readSelectedValues($person->reflections) : [];
                    $selected_films = isset($person->films) ? $readSelectedValues($person->films) : [];
                    $selected_film_episodes = isset($person->film_episode) ? $readSelectedValues($person->film_episode) : [];
                    $displayRaw = isset($person->display) ? strtolower(trim((string)$person->display)) : '';
                    $publishRaw = isset($person->publish) ? strtolower(trim((string)$person->publish)) : '';
                    $displaySelected = in_array($displayRaw, ['1', 'true', 'yes'], true) ? 'true' : 'false';
                    $publishSelected = in_array($publishRaw, ['1', 'true', 'yes'], true) ? 'true' : 'false';
                    $selected_occupations = [];
                    if (isset($person->id) && $this->db->table_exists('person_category')) {
                        $person_category_rows = $this->db
                            ->select('category_id')
                            ->from('person_category')
                            ->where('person_id', (int) $person->id)
                            ->get()
                            ->result();
                        foreach ($person_category_rows as $person_category_row) {
                            if (isset($person_category_row->category_id) && $person_category_row->category_id !== '') {
                                $selected_occupations[] = (string) $person_category_row->category_id;
                            }
                        }
                    }
                    $selected_occupations = array_values(array_unique($selected_occupations));

                    $keyword_rows = $this->db->table_exists('word')
                        ? $this->db->query("SELECT id, word_transliteration FROM word ORDER BY LOWER(TRIM(COALESCE(word_transliteration,''))) DESC, id DESC")->result()
                        : [];
                    $song_rows = $this->db->query("SELECT id, umbrellaTitle FROM songs ORDER BY id DESC")->result();
                    $poem_rows = $this->db->query("SELECT id, COALESCE(NULLIF(couplet_transliteration, ''), original_title) AS poem_label FROM couplet ORDER BY id DESC")->result();
                    $reflection_rows = $this->db->query("SELECT id, title FROM reflection ORDER BY id DESC")->result();
                    $film_rows = $this->db->query("SELECT id, COALESCE(NULLIF(english_transliteration, ''), NULLIF(english_translation, ''), original_title) AS film_label FROM film ORDER BY id DESC")->result();
                    $episode_rows = $this->db->query("SELECT id, COALESCE(NULLIF(english_transliteration, ''), NULLIF(english_translation, ''), original_title) AS episode_label FROM film_episode ORDER BY id DESC")->result();
                    $cleanOccLabel = function ($name) {
                        $name = trim((string) $name);
                        return preg_replace('/^_+/u', '', $name);
                    };
                    $occupation_rows = [];
                    if ($this->db->table_exists('category')) {
                        $occupation_rows = $this->db
                            ->select('id, name')
                            ->from('category')
                            ->where('category_type', 'person')
                            ->where('name IS NOT NULL', null, false)
                            ->where("TRIM(name) !=", '')
                            ->get()
                            ->result();
                        usort($occupation_rows, function ($a, $b) use ($cleanOccLabel) {
                            $nameA = $cleanOccLabel(isset($a->name) ? $a->name : '');
                            $nameB = $cleanOccLabel(isset($b->name) ? $b->name : '');
                            $cmp = strcasecmp($nameA, $nameB);
                            if ($cmp !== 0) {
                                return $cmp;
                            }
                            return (int) (isset($a->id) ? $a->id : 0) - (int) (isset($b->id) ? $b->id : 0);
                        });
                    }
                    $occupation_primary_options = [];
                    $occupation_profile_tag_options = [];
                    
                    foreach ($occupation_rows as $row) {
                        $name = isset($row->name) ? trim((string)$row->name) : '';
                        if (strpos($name, '_') === 0) {
                            // Underscore-prefixed names → Occupation field (swapped)
                            $occupation_primary_options[] = $row;
                        } else {
                            // Plain names → Profile Tags field (swapped)
                            $occupation_profile_tag_options[] = $row;
                        }
                    }
                    $topOccIds = array_map(function ($o) {
                        return isset($o->id) ? (string) $o->id : '';
                    }, $occupation_primary_options);
                    $tagOccIds = array_map(function ($o) {
                        return isset($o->id) ? (string) $o->id : '';
                    }, $occupation_profile_tag_options);
                    // ============================================================
                    // Occupation field source: person.primary_occupation column
                    // (NOT the person_category junction). This matches the
                    // people-list column logic so what you see on the list and
                    // what's pre-selected in the Edit form stay in sync.
                    // primary_occupation is typically a single category.id;
                    // we still accept a CSV defensively.
                    // ============================================================
                    $selected_occupation_only = [];
                    if (isset($person) && isset($person->primary_occupation)) {
                        $rawPrim = trim((string) $person->primary_occupation);
                        if ($rawPrim !== '') {
                            foreach (explode(',', $rawPrim) as $pp) {
                                $pp = trim($pp);
                                if ($pp !== '') { $selected_occupation_only[] = $pp; }
                            }
                            $selected_occupation_only = array_values(array_unique($selected_occupation_only));
                        }
                    }
                    // Profile Tags still come from person_category (the junction);
                    // we intersect with the "profile tag" group only, and pull in
                    // any junction ids that didn't slot into either group (legacy data).
                    $selected_profile_tags = array_values(array_intersect($selected_occupations, $tagOccIds));
                    foreach ($selected_occupations as $sid) {
                        if (!in_array($sid, $topOccIds, true) && !in_array($sid, $tagOccIds, true) && ctype_digit((string) $sid)) {
                            $selected_profile_tags[] = $sid;
                        }
                    }
                    $selected_profile_tags = array_values(array_unique($selected_profile_tags));
                    ?>

                    <form name="personForm" id="personForm" method="post" enctype="multipart/form-data" action="<?php echo isset($person) ? base_url('PersonController/update/' . $person->id) : base_url('PersonController/save'); ?>">
                       <label>Name</label>
                       <div style="padding-left:20px;"> 
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ First Name <span style="color:red">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo isset($person->first_name) ? $person->first_name : ''; ?>" placeholder="Enter First Name" required>
                            </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Middle Name</label>
                            <div class="col-md-4">
                                <input type="text" name="middle_name" id="middle_name" class="form-control" value="<?php echo isset($person->middle_name) ? $person->middle_name : ''; ?>" placeholder="Enter Middle Name">
                            </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Last Name <span style="color:red">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo isset($person->last_name) ? $person->last_name : ''; ?>" placeholder="Enter Last Name" required>
                            </div>
                        </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Occupation <span style="color:red">*</span></label>
                            <div class="col-md-4 d-flex align-items-center">
                                <!-- Single-select Occupation. Backend (PersonController) already accepts
                                     either a single string or an array for this POST key, so no controller
                                     change is needed. Selecting a single existing occupation also clears any
                                     previously chosen one. -->
                                <!-- Plain single-select (no Select2). Using the native browser dropdown
                                     avoids any conflict with the admin-wide Select2 matcher / Bootstrap
                                     Multiselect init which were filtering this select down to "No results
                                     found". `data-skip-select2="true"` tells the global init code to leave
                                     this element alone. -->
                                <select name="occupation" id="occupation" class="form-control" style="height: 38px;" data-skip-select2="true" required>
                                    <option value="">Select Occupation</option>
                                    <?php
                                    // For single-select, only the first matching previously-saved id should be
                                    // pre-selected — anything beyond that would be silently dropped on submit.
                                    $primary_selected = '';
                                    foreach ($selected_occupation_only as $sid) {
                                        if ($sid !== '' && $sid !== null) { $primary_selected = (string) $sid; break; }
                                    }
                                    foreach ($occupation_primary_options as $occupation_row) {
                                        $occ_id = isset($occupation_row->id) ? (string)$occupation_row->id : '';
                                        $occ_label = isset($occupation_row->name) ? trim((string)$occupation_row->name) : '';
                                        if ($occ_label === '') {
                                            $occ_label = $occ_id !== '' ? ('Occupation #' . $occ_id) : 'Occupation';
                                        }
                                        $occ_display = $cleanOccLabel($occ_label);
                                        $occ_value = $occ_id !== '' ? $occ_id : $occ_label;
                                        $is_selected = ((string) $occ_value === $primary_selected) || ($occ_label === $primary_selected);
                                    ?>
                                        <option value="<?php echo htmlspecialchars((string)$occ_value); ?>" <?php echo $is_selected ? 'selected' : ''; ?> >
                                            <?php echo htmlspecialchars($occ_display !== '' ? $occ_display : $occ_label); ?>
                                        </option>
                                    <?php }
                                    ?>
                                </select>
                                <button type="button" class="btn btn-success btn-sm ml-2" id="addOccupationBtn">Add New</button>
                                <button type="button" class="btn btn-primary btn-sm ml-1" id="editOccupationBtn">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm ml-1" id="deleteOccupationBtn">Delete</button>
                            </div>
                        </div>

                        <div class="custom-modal" id="addOccupationModal">
                            <div class="custom-modal-content">
                                <h5 style="margin-bottom: 15px;">Add New Occupation</h5>
                                <div class="form-group">
                                    <label>Occupation Name <span style="color:red">*</span></label>
                                    <input type="text" id="newOccupationName" class="form-control" placeholder="Enter Occupation Name">
                                </div>
                                <div class="custom-modal-actions">
                                    <button type="button" class="btn btn-secondary btn-sm" id="cancelOccupationBtn">Cancel</button>
                                    <button type="button" class="btn btn-success btn-sm" id="saveOccupationBtn">Save</button>
                                </div>
                            </div>
                        </div>
                        <!-- Add New Profile Tag Modal -->
                        <div class="custom-modal" id="addProfileTagModal">
                            <div class="custom-modal-content">
                                <h5 style="margin-bottom: 15px;">Add New Profile Tag</h5>
                                <div class="form-group">
                                    <label>Profile Tag Name <span style="color:red">*</span></label>
                                    <input type="text" id="newProfileTagName" class="form-control" placeholder="Enter Profile Tag Name">
                                </div>
                                <div class="custom-modal-actions">
                                    <button type="button" class="btn btn-secondary btn-sm" id="cancelProfileTagBtn">Cancel</button>
                                    <button type="button" class="btn btn-success btn-sm" id="saveProfileTagBtn">Save</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Profile Tags</label>
                            <div class="col-md-9 d-flex align-items-center">
                                <select name="profile_tags[]" id="profile_tags" class="form-control select2" style="width:100%;" multiple="multiple" data-placeholder="Select profile tags">
                                    <?php
                                    foreach ($occupation_profile_tag_options as $occupation_row) {
                                        $occ_id = isset($occupation_row->id) ? (string)$occupation_row->id : '';
                                        $occ_label = isset($occupation_row->name) ? trim((string)$occupation_row->name) : '';
                                        if ($occ_label === '') {
                                            $occ_label = $occ_id !== '' ? ('Tag #' . $occ_id) : 'Tag';
                                        }
                                        $occ_display = $cleanOccLabel($occ_label);
                                        $occ_value = $occ_id !== '' ? $occ_id : $occ_label;
                                        $is_selected = in_array((string)$occ_value, $selected_profile_tags, true);
                                        ?>
                                        <option value="<?php echo htmlspecialchars((string)$occ_value); ?>" <?php echo $is_selected ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($occ_display !== '' ? $occ_display : $occ_label); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <button type="button" class="btn btn-success btn-sm ml-2" id="addProfileTagBtn">Add New</button>
                                <button type="button" class="btn btn-primary btn-sm ml-1" id="editProfileTagBtn">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm ml-1" id="deleteProfileTagBtn">Delete</button>
                            </div>
                        </div>
                        <?php
                        $existingPersonThumb = '';
                        if (isset($person->thumbnail_image_upload) && trim((string)$person->thumbnail_image_upload) !== '') {
                            $existingPersonThumb = trim((string)$person->thumbnail_image_upload);
                        } elseif (isset($person->thumbnail_url) && trim((string)$person->thumbnail_url) !== '') {
                            $existingPersonThumb = trim((string)$person->thumbnail_url);
                        }
                        $existingPersonThumbUrl = isset($person->thumbnail_url) ? trim((string)$person->thumbnail_url) : '';
                        $personPublicBase = 'https://ajab.designanddevelopment.in/admin';
                        $personThumbPreviewSrc = '';
                        if ($existingPersonThumb !== '') {
                            if (preg_match('#^https?://#i', $existingPersonThumb)) {
                                $personThumbPreviewSrc = $existingPersonThumb;
                            } else {
                                $normalizedThumb = ltrim($existingPersonThumb, '/');
                                $thumbCandidates = [$normalizedThumb];
                                if (stripos($normalizedThumb, 'Uploads/') !== 0 && stripos($normalizedThumb, 'uploads/') !== 0 && stripos($normalizedThumb, 'images/') !== 0) {
                                    $thumbCandidates[] = 'Uploads/' . $normalizedThumb;
                                    $thumbCandidates[] = 'uploads/' . $normalizedThumb;
                                    $thumbCandidates[] = 'uploads/thumbnails/' . $normalizedThumb;
                                    $thumbCandidates[] = 'images/' . $normalizedThumb;
                                }
                                $thumbCandidates = array_values(array_unique($thumbCandidates));

                                foreach ($thumbCandidates as $candidate) {
                                    if (file_exists(FCPATH . $candidate)) {
                                        $personThumbPreviewSrc = base_url($candidate);
                                        break;
                                    }
                                }

                                if ($personThumbPreviewSrc === '') {
                                    if (isset($existingPersonThumb[0]) && $existingPersonThumb[0] === '/') {
                                        $personThumbPreviewSrc = rtrim($personPublicBase, '/') . $existingPersonThumb;
                                    } else {
                                        $personThumbPreviewSrc = rtrim($personPublicBase, '/') . '/' . $normalizedThumb;
                                    }
                                }
                            }
                        }
                        ?>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Thumbnail Image Upload <?php if ($existingPersonThumb === ''): ?><span style="color:red">*</span><?php endif; ?></label>
                            <div class="col-md-4">
                                <?php if ($existingPersonThumb !== ''): ?>
                                <input type="hidden" name="existing_thumbnail_image_upload" id="existing_thumbnail_image_upload" value="<?php echo htmlspecialchars($existingPersonThumb); ?>">
                                <input type="hidden" name="existing_thumbnail_url" id="existing_thumbnail_url" value="<?php echo htmlspecialchars($existingPersonThumbUrl !== '' ? $existingPersonThumbUrl : $existingPersonThumb); ?>">
                                <?php endif; ?>
                                <input type="file" name="thumbnail_image_upload" id="thumbnail_image_upload" class="form-control" accept="image/*" <?php echo ($existingPersonThumb === '') ? 'required' : ''; ?>>
                                <?php if ($existingPersonThumb !== '' && $personThumbPreviewSrc !== ''): ?>
                                <div class="mt-2" id="personThumbnailPreviewWrap">
                                    <img src="<?php echo htmlspecialchars($personThumbPreviewSrc); ?>" alt="Current thumbnail" id="personThumbnailPreviewImg" style="max-width:200px;height:auto;border:1px solid #ddd;border-radius:4px;"
                                        onerror="this.style.display='none';var w=document.getElementById('personThumbnailPreviewBroken');if(w)w.style.display='block';">
                                    <p class="small text-muted mt-1" id="personThumbnailPreviewBroken" style="display:none;">Preview could not be loaded.</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">About</label>
                            <div class="col-md-9">
                                <input type="file" id="personAboutMediaInput" accept="image/*" multiple style="display:none;">
                                <textarea id="about" name="about" class="form-control" style="display:none;"><?php echo isset($person->about) ? htmlspecialchars($person->about) : ''; ?></textarea>
                                <div id="personAboutGjs"></div>
                            </div>
                        </div>
<label>Related Content</label>
                        <div style="padding-left:20px;">
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Keywords</label>
                            <div class="col-md-4 d-flex align-items-center">
                                <select class="select2 form-control" multiple="multiple" name="keywords[]" id="keywords" data-placeholder="Select Keywords">
                                    <option value="">None Selected</option>
                                    <?php foreach ($keyword_rows as $keyword): ?>
                                        <?php
                                        $keyword_id = (string)$keyword->id;
                                        $keyword_label = !empty($keyword->word_transliteration) ? $keyword->word_transliteration : ('Keyword #' . $keyword->id);
                                        $is_selected = in_array($keyword_id, $selected_keywords, true);
                                        ?>
                                        <option value="<?php echo htmlspecialchars($keyword_id); ?>" <?php echo $is_selected ? 'selected' : ''; ?> >
                                            <?php echo htmlspecialchars($keyword_label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn btn-success btn-sm ml-2" id="addNewKeywordBtn">Add New</button>
                                <button type="button" class="btn btn-primary btn-sm ml-1" id="editKeywordBtn">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm ml-1" id="deleteKeywordBtn">Delete</button>
                            </div>
                            <!-- Add New Keyword Modal -->
                            <div class="custom-modal" id="addNewKeywordModal">
                                <div class="custom-modal-content">
                                    <h5>Add New Keyword</h5>
                                    <div style="margin-bottom:10px;">
                                        <label style="display:block;font-weight:600;margin-bottom:4px;">Original</label>
                                        <input type="text" id="newKeywordOriginal" class="form-control" placeholder="Enter Original Keyword">
                                    </div>
                                    <div style="margin-bottom:10px;">
                                        <label style="display:block;font-weight:600;margin-bottom:4px;">Translation</label>
                                        <input type="text" id="newKeywordTranslation" class="form-control" placeholder="Enter Keyword Translation">
                                    </div>
                                    <div style="margin-bottom:10px;">
                                        <label style="display:block;font-weight:600;margin-bottom:4px;">Transliteration</label>
                                        <input type="text" id="newKeywordTransliteration" class="form-control" placeholder="Enter Keyword Transliteration">
                                    </div>
                                    <div style="margin-bottom:10px;">
                                        <label style="display:block;font-weight:600;margin-bottom:4px;">Word Meaning</label>
                                        <textarea id="newKeywordMeaning" class="form-control" rows="3" placeholder="Enter word meaning"></textarea>
                                    </div>
                                    <div class="custom-modal-actions">
                                        <button type="button" class="btn btn-secondary btn-sm" id="cancelAddNewKeyword">Cancel</button>
                                        <button type="button" class="btn btn-success btn-sm" id="addKeywordConfirm">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Songs</label>
                            <div class="col-md-4 d-flex align-items-center">
                                <select class="select2 form-control" multiple="multiple" name="songs[]" id="songs" data-placeholder="Select Songs">
                                    <option value="">None Selected</option>
                                    <?php foreach ($song_rows as $song): ?>
                                        <?php
                                        $song_id = (string)$song->id;
                                        $song_label = !empty($song->umbrellaTitle) ? $song->umbrellaTitle : ('Song #' . $song->id);
                                        $is_selected = in_array($song_id, $selected_songs, true);
                                        ?>
                                        <option value="<?php echo htmlspecialchars($song_id); ?>" <?php echo $is_selected ? 'selected' : ''; ?> >
                                            <?php echo htmlspecialchars($song_label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Add New Song Modal -->
                            <div class="custom-modal" id="addNewSongModal">
                                <div class="custom-modal-content">
                                    <h5>Add New Song</h5>
                                    <input type="text" id="newSongTitle" class="form-control" placeholder="Enter Song Title">
                                    <div class="custom-modal-actions">
                                        <button type="button" class="btn btn-secondary btn-sm" id="cancelAddNewSong">Cancel</button>
                                        <button type="button" class="btn btn-success btn-sm" id="addSongConfirm">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Poems</label>
                            <div class="col-md-4 d-flex align-items-center">
                                <select class="select2 form-control" multiple="multiple" name="poems[]" id="poems" data-placeholder="Select Poems">
                                    <option value="">None Selected</option>
                                    <?php foreach ($poem_rows as $poem): ?>
                                        <?php
                                        $poem_id = (string)$poem->id;
                                        $poem_label = !empty($poem->poem_label) ? $poem->poem_label : ('Poem #' . $poem->id);
                                        $is_selected = in_array($poem_id, $selected_poems, true);
                                        ?>
                                        <option value="<?php echo htmlspecialchars($poem_id); ?>" <?php echo $is_selected ? 'selected' : ''; ?> >
                                            <?php echo htmlspecialchars($poem_label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Add New Poem Modal -->
                            <div class="custom-modal" id="addNewPoemModal">
                                <div class="custom-modal-content">
                                    <h5>Add New Poem</h5>
                                    <input type="text" id="newPoemTitle" class="form-control" placeholder="Enter Poem Title">
                                    <div class="custom-modal-actions">
                                        <button type="button" class="btn btn-secondary btn-sm" id="cancelAddNewPoem">Cancel</button>
                                        <button type="button" class="btn btn-success btn-sm" id="addPoemConfirm">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Reflections</label>
                            <div class="col-md-4 d-flex align-items-center">
                                <select class="select2 form-control" multiple="multiple" name="reflections[]" id="reflections" data-placeholder="Select Reflections">
                                    <option value="">None Selected</option>
                                    <?php foreach ($reflection_rows as $reflection): ?>
                                        <?php
                                        $reflection_id = (string)$reflection->id;
                                        $reflection_label = !empty($reflection->title) ? $reflection->title : ('Reflection #' . $reflection->id);
                                        $is_selected = in_array($reflection_id, $selected_reflections, true);
                                        ?>
                                        <option value="<?php echo htmlspecialchars($reflection_id); ?>" <?php echo $is_selected ? 'selected' : ''; ?> >
                                            <?php echo htmlspecialchars($reflection_label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Add New Reflection Modal -->
                            <div class="custom-modal" id="addNewReflectionModal">
                                <div class="custom-modal-content">
                                    <h5>Add New Reflection</h5>
                                    <input type="text" id="newReflectionTitle" class="form-control" placeholder="Enter Reflection Title">
                                    <div class="custom-modal-actions">
                                        <button type="button" class="btn btn-secondary btn-sm" id="cancelAddNewReflection">Cancel</button>
                                        <button type="button" class="btn btn-success btn-sm" id="addReflectionConfirm">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Films</label>
                            <div class="col-md-4 d-flex align-items-center">
                                <select class="select2 form-control" multiple="multiple" name="films[]" id="films" data-placeholder="Select Films">
                                    <option value="">None Selected</option>
                                    <?php foreach ($film_rows as $film): ?>
                                        <?php
                                        $film_id = (string)$film->id;
                                        $film_label = !empty($film->film_label) ? $film->film_label : ('Film #' . $film->id);
                                        $is_selected = in_array($film_id, $selected_films, true);
                                        ?>
                                        <option value="<?php echo htmlspecialchars($film_id); ?>" <?php echo $is_selected ? 'selected' : ''; ?> >
                                            <?php echo htmlspecialchars($film_label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Add New Film Modal -->
                            <div class="custom-modal" id="addNewFilmModal">
                                <div class="custom-modal-content">
                                    <h5>Add New Film</h5>
                                    <input type="text" id="newFilmTitle" class="form-control" placeholder="Enter Film Title">
                                    <div class="custom-modal-actions">
                                        <button type="button" class="btn btn-secondary btn-sm" id="cancelAddNewFilm">Cancel</button>
                                        <button type="button" class="btn btn-success btn-sm" id="addFilmConfirm">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Film Episode</label>
                            <div class="col-md-4 d-flex align-items-center">
                                <select class="select2 form-control" multiple="multiple" name="film_episode[]" id="film_episode" data-placeholder="Select Film Episode">
                                    <option value="">None Selected</option>
                                    <?php foreach ($episode_rows as $episode): ?>
                                        <?php
                                        $episode_id = (string)$episode->id;
                                        $episode_label = !empty($episode->episode_label) ? $episode->episode_label : ('Episode #' . $episode->id);
                                        $is_selected = in_array($episode_id, $selected_film_episodes, true);
                                        ?>
                                        <option value="<?php echo htmlspecialchars($episode_id); ?>" <?php echo $is_selected ? 'selected' : ''; ?> >
                                            <?php echo htmlspecialchars($episode_label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Add New Episode Modal -->
                            <div class="custom-modal" id="addNewEpisodeModal">
                                <div class="custom-modal-content">
                                    <h5>Add New Film Episode</h5>
                                    <input type="text" id="newEpisodeTitle" class="form-control" placeholder="Enter Episode Title">
                                    <div class="custom-modal-actions">
                                        <button type="button" class="btn btn-secondary btn-sm" id="cancelAddNewEpisode">Cancel</button>
                                        <button type="button" class="btn btn-success btn-sm" id="addEpisodeConfirm">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Display on People Page</label>
                            <div class="col-md-4">
                                <select name="display" id="display" class="form-control" style="height: 38px;">
                                    <option value="">Select</option>
                                    <option value="true" <?php echo ($displaySelected === 'true') ? 'selected' : ''; ?>>Yes</option>
                                    <option value="false" <?php echo ($displaySelected === 'false') ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>
                        </div>

                        <label>Meta Data </label>
                         <div style="padding-left:20px;">
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Meta Title</label>
                            <div class="col-md-4">
                                <input type="text" name="meta_title" id="meta_title" class="form-control" value="<?php echo isset($person->meta_title) ? $person->meta_title : ''; ?>" placeholder="Enter Meta Title">
                            </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Meta Keywords</label>
                            <div class="col-md-4">
                                <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" value="<?php echo isset($person->meta_keywords) ? $person->meta_keywords : ''; ?>" placeholder="Enter Meta Keywords">
                            </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">⊙ Meta Description</label>
                            <div class="col-md-4">
                                <textarea class="form-control" name="meta_description" id="meta_description" rows="3" placeholder="Enter Meta Description"><?php echo isset($person->meta_description) ? $person->meta_description : ''; ?></textarea>
                            </div>
                        </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Publish Status</label>
                            <div class="col-md-4">
                                <select name="publish" id="publish" class="form-control" style="height: 38px;">
                                    <option value="false" <?php echo ($publishSelected === 'false') ? 'selected' : ''; ?>>No</option>
                                    <option value="true" <?php echo ($publishSelected === 'true') ? 'selected' : ''; ?>>Yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="save-btn-container">
                            <?= admin_edit_preview_button(isset($person) ? $person : null) ?>
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
$(document).ready(function() {
    // --- BEGIN: Add New AJAX logic for all Related Content fields ---
    const createOccupationUrl = '<?php echo base_url('person/occupation/create'); ?>';
    const createKeywordUrl = '<?php echo base_url('SongController/ajax_create_keyword'); ?>';
    const createSongUrl = '<?php echo base_url('SongController/ajax_create_song'); ?>';
    const createPoemUrl = '<?php echo base_url('SongController/ajax_create_poem'); ?>';
    const createReflectionUrl = '<?php echo base_url('SongController/ajax_create_reflection'); ?>';
    const createFilmUrl = '<?php echo base_url('SongController/ajax_create_film'); ?>';
    const createEpisodeUrl = '<?php echo base_url('SongController/ajax_create_episode'); ?>';

    $('.select2').select2({
        placeholder: "Select an option",
        allowClear: true
    });

    // --- Occupation Modal Logic (already present) ---
    function openOccupationModal() {
        $('#addOccupationModal').css('display', 'flex');
        $('#newOccupationName').val('').focus();
    }
    function closeOccupationModal() {
        $('#addOccupationModal').hide();
    }
    // --- Profile Tag Modal ---
    function openProfileTagModal() { $('#addProfileTagModal').css('display', 'flex'); $('#newProfileTagName').val('').focus(); }
    function closeProfileTagModal() { $('#addProfileTagModal').hide(); }
    
    $('#addOccupationBtn').on('click', function() { openOccupationModal(); });
    $('#cancelOccupationBtn').on('click', function() { closeOccupationModal(); });
    $('#addOccupationModal').on('click', function(e) { if (e.target.id === 'addOccupationModal') { closeOccupationModal(); } });
    $('#addProfileTagBtn').on('click', function() { openProfileTagModal(); });
    $('#cancelProfileTagBtn').on('click', function() { closeProfileTagModal(); });
    $('#addProfileTagModal').on('click', function(e) { if (e.target.id === 'addProfileTagModal') { closeProfileTagModal(); } });
    $('#saveOccupationBtn').on('click', function() {
        const occupationName = $('#newOccupationName').val().trim();
        if (!occupationName) {
            Swal.fire({ icon: 'warning', title: 'Missing Input', text: 'Please enter Occupation Name' });
            $('#newOccupationName').focus();
            return;
        }
        const $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
            url: createOccupationUrl,
            type: 'POST',
            dataType: 'json',
            data: { occupation_name: occupationName },
            success: function(response) {
                if (response && response.status === 'success') {
                    const optionValue = response.occupation_id ? String(response.occupation_id) : response.occupation_name;
                    const optionText = response.occupation_name;
                    const $target = $('#occupation');
                    if ($target.find('option[value="' + optionValue.replace(/(["\\])/g, '\\$1') + '"]').length === 0) {
                        $target.append(new Option(optionText, optionValue));
                    }
                    // Single-select: just set the value (no array push).
                    $target.val(optionValue).trigger('change');
                    closeOccupationModal();
                    Swal.fire({ icon: 'success', title: 'Success', text: response.message || 'Occupation added successfully' });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: (response && response.message) ? response.message : 'Unable to add occupation' });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Server error while adding occupation' });
            },
            complete: function() { $btn.prop('disabled', false); }
        });
    });

    // Profile Tag Save Logic
    $('#saveProfileTagBtn').on('click', function() {
        const profileTagName = $('#newProfileTagName').val().trim();
        if (!profileTagName) {
            Swal.fire({ icon: 'warning', title: 'Missing Input', text: 'Please enter Profile Tag Name' });
            $('#newProfileTagName').focus();
            return;
        }
        const $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
            url: createOccupationUrl,
            type: 'POST',
            dataType: 'json',
            data: { occupation_name: '_' + profileTagName },
            success: function(response) {
                if (response && response.status === 'success') {
                    const optionValue = response.occupation_id ? String(response.occupation_id) : '_' + profileTagName;
                    const optionText = response.occupation_name;
                    const $target = $('#profile_tags');
                    if ($target.find('option[value="' + optionValue.replace(/(["\\])/g, '\\$1') + '"]').length === 0) {
                        $target.append(new Option(optionText, optionValue));
                    }
                    let cur = $target.val() || [];
                    cur.push(optionValue);
                    $target.val(cur).trigger('change');
                    closeProfileTagModal();
                    Swal.fire({ icon: 'success', title: 'Success', text: response.message || 'Profile Tag added successfully' });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: (response && response.message) ? response.message : 'Unable to add profile tag' });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Unable to add profile tag' });
            },
            complete: function() {
                $btn.prop('disabled', false);
            }
        });
    });

    // --- Add New Keyword ---
    function __personKwClear() {
        ['newKeywordOriginal','newKeywordTranslation','newKeywordTransliteration','newKeywordMeaning'].forEach(function (id) {
            var el = document.getElementById(id); if (el) el.value = '';
        });
    }
    function openKeywordModal() { __personKwClear(); $('#addNewKeywordModal').css('display', 'flex'); setTimeout(function(){ $('#newKeywordTransliteration').focus(); }, 200); }
    function closeKeywordModal() { $('#addNewKeywordModal').hide(); }
    $('#addNewKeywordBtn').on('click', openKeywordModal);
    $('#cancelAddNewKeyword').on('click', closeKeywordModal);
    $('#addNewKeywordModal').on('click', function(e) { if (e.target.id === 'addNewKeywordModal') { closeKeywordModal(); } });
        $('#addKeywordConfirm').on('click', function() {
        const original    = $('#newKeywordOriginal').val().trim();
        const translation = $('#newKeywordTranslation').val().trim();
        const keyword     = $('#newKeywordTransliteration').val().trim();
        const meaning     = $('#newKeywordMeaning').val().trim();
        if (!keyword) {
            Swal.fire({ icon: 'warning', title: 'Missing Input', text: 'Please enter Transliteration' });
            $('#newKeywordTransliteration').focus();
            return;
        }
        const $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
            url: createKeywordUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                word_original:        original,
                word_translation:     translation,
                word_transliteration: keyword,
                glossary_meaning:     meaning
            },
            success: function(response) {
                if (response && (response.status === 'success' || response.success)) {
                    const optionValue = response.keyword_id ? String(response.keyword_id) : (response.id ? String(response.id) : response.word_transliteration);
                    const optionText = response.word_transliteration;
                    if ($('#keywords option[value="' + optionValue.replace(/(["\\])/g, '\\$1') + '"]').length === 0) {
                        $('#keywords').append(new Option(optionText, optionValue));
                    }
                    let selected = $('#keywords').val() || [];
                    if (!selected.includes(optionValue)) { selected.push(optionValue); }
                    $('#keywords').val(selected).trigger('change');
                    if (window.__adminRefreshSelect) {
                        window.__adminRefreshSelect('#keywords', optionValue);
                    }
                    closeKeywordModal();
                    __personKwClear();
                    Swal.fire({ icon: 'success', title: 'Success', text: response.message || 'Keyword saved successfully' });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: (response && response.message) ? response.message : 'Unable to save keyword' });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Server error while adding keyword' });
            },
            complete: function() { $btn.prop('disabled', false); }
        });
    });

    // --- Add New Song ---
    function openSongModal() { $('#addNewSongModal').css('display', 'flex'); $('#newSongTitle').val('').focus(); }
    function closeSongModal() { $('#addNewSongModal').hide(); }
    $('#addNewSongBtn').on('click', openSongModal);
    $('#cancelAddNewSong').on('click', closeSongModal);
    $('#addNewSongModal').on('click', function(e) { if (e.target.id === 'addNewSongModal') { closeSongModal(); } });
        $('#addSongConfirm').on('click', function() {
        const songTitle = $('#newSongTitle').val().trim();
        if (!songTitle) {
            Swal.fire({ icon: 'warning', title: 'Missing Input', text: 'Please enter Song Title' });
            $('#newSongTitle').focus();
            return;
        }
        const $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
            url: createSongUrl,
            type: 'POST',
            dataType: 'json',
            data: { umbrellaTitle: songTitle },
            success: function(response) {
                if (response && response.status === 'success') {
                    const optionValue = response.song_id ? String(response.song_id) : response.umbrellaTitle;
                    const optionText = response.umbrellaTitle;
                    if ($('#songs option[value="' + optionValue.replace(/(["\\])/g, '\\$1') + '"]').length === 0) {
                        $('#songs').append(new Option(optionText, optionValue));
                    }
                    let selected = $('#songs').val() || [];
                    selected.push(optionValue);
                    $('#songs').val(selected).trigger('change');
                    closeSongModal();
                    Swal.fire({ icon: 'success', title: 'Success', text: response.message || 'Song added successfully' });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: (response && response.message) ? response.message : 'Unable to add song' });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Server error while adding song' });
            },
            complete: function() { $btn.prop('disabled', false); }
        });
    });

    // --- Add New Poem ---
    function openPoemModal() { $('#addNewPoemModal').css('display', 'flex'); $('#newPoemTitle').val('').focus(); }
    function closePoemModal() { $('#addNewPoemModal').hide(); }
    $('#addNewPoemBtn').on('click', openPoemModal);
    $('#cancelAddNewPoem').on('click', closePoemModal);
    $('#addNewPoemModal').on('click', function(e) { if (e.target.id === 'addNewPoemModal') { closePoemModal(); } });
        $('#addPoemConfirm').on('click', function() {
        const poemTitle = $('#newPoemTitle').val().trim();
        if (!poemTitle) {
            Swal.fire({ icon: 'warning', title: 'Missing Input', text: 'Please enter Poem Title' });
            $('#newPoemTitle').focus();
            return;
        }
        const $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
            url: createPoemUrl,
            type: 'POST',
            dataType: 'json',
            data: { original_title: poemTitle },
            success: function(response) {
                if (response && response.status === 'success') {
                    const optionValue = response.poem_id ? String(response.poem_id) : response.original_title;
                    const optionText = response.original_title;
                    if ($('#poems option[value="' + optionValue.replace(/(["\\])/g, '\\$1') + '"]').length === 0) {
                        $('#poems').append(new Option(optionText, optionValue));
                    }
                    let selected = $('#poems').val() || [];
                    selected.push(optionValue);
                    $('#poems').val(selected).trigger('change');
                    closePoemModal();
                    Swal.fire({ icon: 'success', title: 'Success', text: response.message || 'Poem added successfully' });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: (response && response.message) ? response.message : 'Unable to add poem' });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Server error while adding poem' });
            },
            complete: function() { $btn.prop('disabled', false); }
        });
    });

    // --- Add New Reflection ---
    function openReflectionModal() { $('#addNewReflectionModal').css('display', 'flex'); $('#newReflectionTitle').val('').focus(); }
    function closeReflectionModal() { $('#addNewReflectionModal').hide(); }
    $('#addNewReflectionBtn').on('click', openReflectionModal);
    $('#cancelAddNewReflection').on('click', closeReflectionModal);
    $('#addNewReflectionModal').on('click', function(e) { if (e.target.id === 'addNewReflectionModal') { closeReflectionModal(); } });
        $('#addReflectionConfirm').on('click', function() {
        const reflectionTitle = $('#newReflectionTitle').val().trim();
        if (!reflectionTitle) {
            Swal.fire({ icon: 'warning', title: 'Missing Input', text: 'Please enter Reflection Title' });
            $('#newReflectionTitle').focus();
            return;
        }
        const $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
            url: createReflectionUrl,
            type: 'POST',
            dataType: 'json',
            data: { title: reflectionTitle },
            success: function(response) {
                if (response && response.status === 'success') {
                    const optionValue = response.reflection_id ? String(response.reflection_id) : response.title;
                    const optionText = response.title;
                    if ($('#reflections option[value="' + optionValue.replace(/(["\\])/g, '\\$1') + '"]').length === 0) {
                        $('#reflections').append(new Option(optionText, optionValue));
                    }
                    let selected = $('#reflections').val() || [];
                    selected.push(optionValue);
                    $('#reflections').val(selected).trigger('change');
                    closeReflectionModal();
                    Swal.fire({ icon: 'success', title: 'Success', text: response.message || 'Reflection added successfully' });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: (response && response.message) ? response.message : 'Unable to add reflection' });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Server error while adding reflection' });
            },
            complete: function() { $btn.prop('disabled', false); }
        });
    });

    // --- Add New Film ---
    function openFilmModal() { $('#addNewFilmModal').css('display', 'flex'); $('#newFilmTitle').val('').focus(); }
    function closeFilmModal() { $('#addNewFilmModal').hide(); }
    $('#addNewFilmBtn').on('click', openFilmModal);
    $('#cancelAddNewFilm').on('click', closeFilmModal);
    $('#addNewFilmModal').on('click', function(e) { if (e.target.id === 'addNewFilmModal') { closeFilmModal(); } });
        $('#addFilmConfirm').on('click', function() {
        const filmTitle = $('#newFilmTitle').val().trim();
        if (!filmTitle) {
            Swal.fire({ icon: 'warning', title: 'Missing Input', text: 'Please enter Film Title' });
            $('#newFilmTitle').focus();
            return;
        }
        const $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
            url: createFilmUrl,
            type: 'POST',
            dataType: 'json',
            data: { main_title: filmTitle },
            success: function(response) {
                if (response && response.status === 'success') {
                    const optionValue = response.film_id ? String(response.film_id) : response.main_title;
                    const optionText = response.main_title;
                    if ($('#films option[value="' + optionValue.replace(/(["\\])/g, '\\$1') + '"]').length === 0) {
                        $('#films').append(new Option(optionText, optionValue));
                    }
                    let selected = $('#films').val() || [];
                    selected.push(optionValue);
                    $('#films').val(selected).trigger('change');
                    closeFilmModal();
                    Swal.fire({ icon: 'success', title: 'Success', text: response.message || 'Film added successfully' });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: (response && response.message) ? response.message : 'Unable to add film' });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Server error while adding film' });
            },
            complete: function() { $btn.prop('disabled', false); }
        });
    });

    // --- Add New Film Episode ---
    function openEpisodeModal() { $('#addNewEpisodeModal').css('display', 'flex'); $('#newEpisodeTitle').val('').focus(); }
    function closeEpisodeModal() { $('#addNewEpisodeModal').hide(); }
    $('#addNewEpisodeBtn').on('click', openEpisodeModal);
    $('#cancelAddNewEpisode').on('click', closeEpisodeModal);
    $('#addNewEpisodeModal').on('click', function(e) { if (e.target.id === 'addNewEpisodeModal') { closeEpisodeModal(); } });
        $('#addEpisodeConfirm').on('click', function() {
        const episodeTitle = $('#newEpisodeTitle').val().trim();
        if (!episodeTitle) {
            Swal.fire({ icon: 'warning', title: 'Missing Input', text: 'Please enter Episode Title' });
            $('#newEpisodeTitle').focus();
            return;
        }
        const $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
            url: createEpisodeUrl,
            type: 'POST',
            dataType: 'json',
            data: { film_episode_title: episodeTitle },
            success: function(response) {
                if (response && response.status === 'success') {
                    const optionValue = response.episode_id ? String(response.episode_id) : response.film_episode_title;
                    const optionText = response.film_episode_title;
                    if ($('#film_episode option[value="' + optionValue.replace(/(["\\])/g, '\\$1') + '"]').length === 0) {
                        $('#film_episode').append(new Option(optionText, optionValue));
                    }
                    let selected = $('#film_episode').val() || [];
                    selected.push(optionValue);
                    $('#film_episode').val(selected).trigger('change');
                    closeEpisodeModal();
                    Swal.fire({ icon: 'success', title: 'Success', text: response.message || 'Episode added successfully' });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: (response && response.message) ? response.message : 'Unable to add episode' });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Server error while adding episode' });
            },
            complete: function() { $btn.prop('disabled', false); }
        });
    });
    // --- END: Add New AJAX logic for all Related Content fields ---

    // Initialize CKEditor
    setTimeout(function() {
        CKEDITOR.replace('profile', {
            height: 140,
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

    window.personAboutBuilder = null;

    function decodeHtmlEntities(value) {
        var temp = document.createElement('textarea');
        temp.innerHTML = value || '';
        return temp.value || '';
    }

    function getPersonAboutBuilderOutput() {
        if (!window.personAboutBuilder) {
            return $('#about').val() || '';
        }
        var html = window.personAboutBuilder.getHtml() || '';
        var css = window.personAboutBuilder.getCss() || '';
        var js = window.personAboutBuilder.getJs() || '';
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

    if (document.getElementById('personAboutGjs') && typeof grapesjs !== 'undefined') {
        var initialAbout = decodeHtmlEntities($('#about').val() || '');
        window.personAboutBuilder = grapesjs.init({
            container: '#personAboutGjs',
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
                    blocksBasicOpts: { flexGrid: true }
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
                            window.personAboutBuilder.AssetManager.add({
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
        window.personAboutBuilder.setComponents(initialAbout || '');
    }

    // Form validation
    $('#personForm').on('submit', function(e) {
        e.preventDefault();
        if (window.personAboutBuilder) {
            $('#about').val(getPersonAboutBuilderOutput());
        }
        // Update CKEditor data
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        // Only fields marked with red `*` in the form are required.
        const fields = [
            { id: 'first_name',        name: 'First Name',        type: 'input' },
            { id: 'last_name',         name: 'Last Name',         type: 'input' },
            { id: 'occupation',        name: 'Occupation',        type: 'select' }
        ];
        for (let field of fields) {
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
                let selectedOptions = element.selectedOptions;
                isEmpty = selectedOptions.length === 0;
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
  bindEdit('editOccupationBtn', {
    selectId: '#occupation', modalId: '#addOccupationModal', addSaveBtnId: '#saveOccupationBtn',
    updateUrl: BASE + 'person/occupation/update', editTitle: 'Edit Occupation',
    fields: [{ inputId: '#newOccupationName', postKey: 'occupation_name', primary: true }]
  });
  bindEdit('editProfileTagBtn', {
    selectId: '#profile_tags', modalId: '#addProfileTagModal', addSaveBtnId: '#saveProfileTagBtn',
    updateUrl: BASE + 'person/occupation/update', editTitle: 'Edit Profile Tag',
    fields: [{ inputId: '#newProfileTagName', postKey: 'occupation_name', primary: true }]
  });
  bindEdit('editKeywordBtn', {
    selectId: '#keywords', modalId: '#addNewKeywordModal', addSaveBtnId: '#addKeywordConfirm',
    updateUrl:  BASE + 'song/ajax_update_keyword',
    prefillUrl: BASE + 'song/ajax_get_keyword',
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
    __bindAdminDelete('deleteOccupationBtn', { selectId: '#occupation',   entity: 'category', label: 'Occupation' });
    __bindAdminDelete('deleteProfileTagBtn', { selectId: '#profile_tags', entity: 'category', label: 'Profile Tag' });
    __bindAdminDelete('deleteKeywordBtn',    { selectId: '#keywords',     entity: 'word',     label: 'Keyword' });
  });
})();
</script>
<?php include('inc/footer.php'); ?>