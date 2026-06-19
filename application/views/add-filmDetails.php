<?php
include('inc/header.php');
include('inc/sidebar.php');
?>

<head>
    <link rel="stylesheet" href="/common/lib/angular-multi-select/angular-multi-select.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
</head>

<style>
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
        padding: 8px 20px;
        font-size: 16px;
        border-radius: 4px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .nav-tabs {
        border-bottom: none;
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
    }

    .nav-tabs .nav-link {
        color: #495057;
        background-color: #f0f0f0;
        border: 1px solid #ddd;
        padding: 10px 20px;
        cursor: pointer;
        font-weight: 500;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link.active {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .nav-tabs .nav-link:hover {
        background-color: #e0e0e0;
    }

    .nav-tabs .nav-link.active:hover {
        background-color: #0056b3;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .save-btn-container {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .cke_notification {
        display: none;
    }
</style>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Film & Film Episode Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="add_new">Home</a></li>
                        <li class="breadcrumb-item active">Film & Film Episode</li>
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
                    <a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-secondary" style="padding: 3px 8px; font-size: 13px; border-radius: 4px;">
                        <i class="fas fa-arrow-left" style="font-size: 13px; margin-right: 4px;"></i> Back
                    </a>
                </div>

                <div class="card-body">
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                    <?php endif; ?>

                    <?php
                    $keyword_rows = $this->db->table_exists('word')
                        ? $this->db->query("SELECT id, word_transliteration FROM word ORDER BY LOWER(TRIM(COALESCE(word_transliteration,''))) DESC, id DESC")->result()
                        : [];
                    $song_rows = $this->db->query("SELECT id, umbrellaTitle FROM songs ORDER BY id DESC")->result();
                    $poem_rows = $this->db->query("SELECT id, COALESCE(NULLIF(couplet_transliteration, ''), original_title) AS poem_label FROM couplet ORDER BY id DESC")->result();
                    $reflection_rows = $this->db->query("SELECT id, title FROM reflection ORDER BY id DESC")->result();
                    $person_rows = $this->db->query("SELECT id, first_name, middle_name, last_name FROM person ORDER BY id DESC")->result();
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

                    $selected_film_keywords = isset($film) ? $readSelectedValues($film->related_keywords ?? '') : [];
                    $selected_film_songs = isset($film) ? $readSelectedValues($film->related_songs ?? ($film->related_primary_songs ?? '')) : [];
                    $selected_film_poems = isset($film) ? $readSelectedValues($film->related_poems ?? ($film->related_couplets ?? '')) : [];
                    $selected_film_reflections = isset($film) ? $readSelectedValues($film->related_reflections ?? '') : [];
                    $selected_film_people = isset($film) ? $readSelectedValues($film->related_people ?? '') : [];

                    // Film Episode dropdown options (for "Related Film Episode" multi-select)
                    $episode_rows = $this->db->table_exists('film_episode')
                        ? $this->db->query("SELECT id, COALESCE(NULLIF(english_transliteration, ''), NULLIF(english_translation, ''), original_title) AS episode_label FROM film_episode ORDER BY id DESC")->result()
                        : [];
                    // Pre-selected episodes for THIS film: read from film_episode_song / film_episode rows linked to this film
                    $selected_film_episodes = [];
                    if (isset($film) && !empty($film->id)) {
                        // Episodes whose film_id equals this film
                        if ($this->db->table_exists('film_episode')) {
                            $erows = $this->db->select('id')->from('film_episode')->where('film_id', (int)$film->id)->get()->result_array();
                            foreach ($erows as $r) { if (!empty($r['id'])) { $selected_film_episodes[] = (string)(int)$r['id']; } }
                        }
                        // Fallback: legacy CSV column on film row
                        if (empty($selected_film_episodes) && isset($film->film_episodes) && trim((string)$film->film_episodes) !== '') {
                            foreach (array_filter(array_map('trim', explode(',', (string)$film->film_episodes))) as $v) {
                                if (ctype_digit($v)) { $selected_film_episodes[] = (string)(int)$v; }
                            }
                        }
                        $selected_film_episodes = array_values(array_unique($selected_film_episodes));
                    }
                    $selected_episode_keywords = isset($filmEpisode) ? $readSelectedValues($filmEpisode->related_keywords ?? '') : [];
                    $selected_episode_songs = isset($filmEpisode) ? $readSelectedValues($filmEpisode->related_songs ?? '') : [];
                    $selected_episode_poems = isset($filmEpisode) ? $readSelectedValues($filmEpisode->related_poems ?? '') : [];
                    $selected_episode_reflections = isset($filmEpisode) ? $readSelectedValues($filmEpisode->related_reflections ?? '') : [];
                    $selected_episode_people = isset($filmEpisode) ? $readSelectedValues($filmEpisode->related_people ?? '') : [];
                    ?>

                    <!-- Tabs Navigation -->
                    <?php
                    // Determine which tab to show in edit mode
                    $showFilmTab = true;
                    $showEpisodeTab = true;
                    $activeTab = 'film-content';
                    if (isset($is_edit) && $is_edit && isset($film)) {
                        // Editing Film Details only
                        $showEpisodeTab = false;
                        $activeTab = 'film-content';
                    }
                    if (isset($filmEpisode)) {
                        // Editing Film Episode only
                        $showFilmTab = false;
                        $activeTab = 'episode-content';
                    }
                    ?>
                    <div class="nav-tabs" role="tablist">
                        <?php if ($showFilmTab): ?>
                        <button class="nav-link<?php echo ($activeTab=='film-content')?' active':''; ?>" id="film-tab" data-tab="film-content">
                            <i class="fas fa-film"></i> Film Details
                        </button>
                        <?php endif; ?>
                        <?php if ($showEpisodeTab): ?>
                        <button class="nav-link<?php echo ($activeTab=='episode-content')?' active':''; ?>" id="episode-tab" data-tab="episode-content">
                            <i class="fas fa-video"></i> Film Episode
                        </button>
                        <?php endif; ?>
                    </div>

                    <!-- Film Details Tab -->
                    <div id="film-content" class="tab-content<?php echo ($activeTab=='film-content')?' active':''; ?>">
                        <form name="filmForm" id="filmForm" method="post" action="<?php echo isset($film) && !empty($film->id) ? base_url('FilmController/update/' . (int)$film->id) : base_url('FilmController/save'); ?>" enctype="multipart/form-data">
                            
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Film Title <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <input type="text" name="main_title" id="main_title" class="form-control" placeholder="Enter Film Title" required value="<?php echo isset($film) ? htmlspecialchars($film->main_title) : ''; ?>">
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Second Title</label>
                                <div class="col-md-4">
                                    <input type="text" name="second_title" id="second_title" class="form-control" placeholder="Enter Second Title" value="<?php echo isset($film) ? htmlspecialchars($film->second_title) : ''; ?>">
                                </div>
                            </div>

                            <?php
                            $defaultFilmLanguageOptions = ['English', 'Hindi', 'Kannada'];
                            $filmLanguageOptions = $defaultFilmLanguageOptions;
                            if ($this->db->table_exists('category')) {
                                $languageRows = $this->db
                                    ->select('name')
                                    ->from('category')
                                    ->where('category_type', 'film_language')
                                    ->where('name IS NOT NULL', null, false)
                                    ->where("TRIM(name) !=", '')
                                    ->order_by('name', 'ASC')
                                    ->get()
                                    ->result();
                                foreach ($languageRows as $langRowDb) {
                                    $nm = isset($langRowDb->name) ? trim((string)$langRowDb->name) : '';
                                    if ($nm !== '' && !in_array($nm, $filmLanguageOptions, true)) {
                                        $filmLanguageOptions[] = $nm;
                                    }
                                }
                            }
                            $filmLanguageRows = [];
                            // Read from any of the JSON columns (language_video_links is canonical; film_language_links is legacy)
                            $rawLangJson = '';
                            foreach (['language_video_links', 'film_language_links', 'language_links', 'video_links', 'youtube_links'] as $colKey) {
                                if (isset($film) && isset($film->$colKey) && trim((string)$film->$colKey) !== '') {
                                    $rawLangJson = (string)$film->$colKey; break;
                                }
                            }
                            if ($rawLangJson !== '') {
                                $decodedLangRows = json_decode($rawLangJson, true);
                                if (is_array($decodedLangRows)) {
                                    foreach ($decodedLangRows as $row) {
                                        if (!is_array($row)) { continue; }
                                        $languageVal = isset($row['language']) ? trim((string)$row['language']) : '';
                                        $youtubeVal = isset($row['youtube_link']) ? trim((string)$row['youtube_link']) : '';
                                        if ($languageVal === '' && $youtubeVal === '') { continue; }
                                        $filmLanguageRows[] = [
                                            'language' => $languageVal,
                                            'youtube_link' => $youtubeVal
                                        ];
                                    }
                                }
                            }
                            if (empty($filmLanguageRows) && isset($film) && isset($film->film_youtube_id) && trim((string)$film->film_youtube_id) !== '') {
                                $filmLanguageRows[] = [
                                    'language' => '',
                                    'youtube_link' => trim((string)$film->film_youtube_id)
                                ];
                            }
                            if (empty($filmLanguageRows)) {
                                $filmLanguageRows[] = ['language' => '', 'youtube_link' => ''];
                            }
                            ?>
                            <div class="form-group row align-items-start">
                                <label class="col-md-2 col-form-label">Video Link</label>
                                <div class="col-md-7">
                                    <div id="filmLanguageRows">
                                        <?php foreach ($filmLanguageRows as $idx => $langRow): ?>
                                        <?php $selectedLanguage = trim((string)$langRow['language']); ?>
                                        <div class="film-language-row d-flex align-items-center mb-2" style="gap:8px;">
                                            <!-- Language picker replaced with a free-text input box per UX request.
                                                 Backend (FilmController) still reads film_language[] so the existing
                                                 save/update logic continues to work unchanged. -->
                                            <input type="text" name="film_language[]" class="form-control film-language-input" placeholder="Enter Language" value="<?php echo htmlspecialchars($selectedLanguage); ?>" style="max-width:220px;">
                                            <input type="text" name="film_language_youtube_link[]" class="form-control film-language-link-input" placeholder="Video Link" value="<?php echo htmlspecialchars((string)$langRow['youtube_link']); ?>">
                                            <button type="button" class="btn btn-danger btn-sm film-language-remove" <?php echo ($idx === 0) ? 'style="display:none;"' : ''; ?>>Remove</button>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="d-flex" style="gap:8px;">
                                        <!-- "Add Language" (repeat-row) button removed per UX request.
                                             "Add New Language" dialog kept — it manages the saved language
                                             options list (category_type='film_language') for future use. -->
                                        <button type="button" class="btn btn-info btn-sm" id="addFilmLanguageOptionBtn">Add New Language</button>
                                    </div>
                                </div>
                            </div>

                            <?php
                            // Series master list comes from the film_series table (title + description).
                            // The dropdown is populated from it; selecting a title auto-fills its
                            // description (the JS map below). "Add New" saves a new series to this
                            // table immediately via AJAX.
                            $series_master = [];           // [ ['title'=>..,'desc'=>..], ... ]
                            $series_desc_map = [];         // title => description (for JS auto-fill)
                            if ($this->db->table_exists('film_series')) {
                                $rows = $this->db
                                    ->select('series_title, series_description')
                                    ->from('film_series')
                                    ->order_by('series_title', 'ASC')
                                    ->get()->result();
                                foreach ($rows as $r) {
                                    $st = trim((string) $r->series_title);
                                    if ($st === '') { continue; }
                                    $series_master[] = ['title' => $st, 'desc' => (string) $r->series_description];
                                    $series_desc_map[$st] = (string) $r->series_description;
                                }
                            }
                            $existing_series_titles = array_column($series_master, 'title');

                            $current_series_title = isset($film) ? trim((string) $film->series_title) : '';
                            $current_series_desc  = isset($film) ? (string) $film->series_description : '';
                            // If this film's saved series isn't in the master list, still show it so the
                            // dropdown reflects the saved value (and keep its description for auto-fill).
                            if ($current_series_title !== '' && !in_array($current_series_title, $existing_series_titles, true)) {
                                $existing_series_titles[] = $current_series_title;
                                $series_desc_map[$current_series_title] = $current_series_desc;
                                sort($existing_series_titles);
                            }
                            ?>
                            <div class="form-group row align-items-center">
                                <label for="series_title_select" class="col-md-2 col-form-label">Series Title</label>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center" style="gap:8px;">
                                        <select class="form-control" id="series_title_select" style="max-width:280px;">
                                            <option value="">Select Series Title</option>
                                            <?php foreach ($existing_series_titles as $st): ?>
                                                <option value="<?= htmlspecialchars($st) ?>" <?= ($st === $current_series_title) ? 'selected' : '' ?>><?= htmlspecialchars($st) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-success btn-sm" id="addNewSeriesBtn" style="white-space:nowrap;">Add New</button>
                                        <button type="button" class="btn btn-primary btn-sm" id="editSeriesBtn" style="white-space:nowrap;">Edit</button>
                                        <button type="button" class="btn btn-danger btn-sm" id="deleteSeriesBtn" style="white-space:nowrap;">Delete</button>
                                    </div>
                                    <!-- Hidden field that actually posts the chosen/new series title. -->
                                    <input type="hidden" id="series_title" name="series_title" value="<?= htmlspecialchars($current_series_title) ?>">
                                </div>
                            </div>

                            <!-- Series Description — visible on the page, right under Series Title.
                                 Auto-filled from the DB when a series is selected; posts as
                                 series_description with the film (backend unchanged). -->
                            <div class="form-group row align-items-start">
                                <label for="series_description" class="col-md-2 col-form-label">Series Description</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" id="series_description" name="series_description" rows="4" placeholder="Enter Series Description (auto-fills when you pick a series)"><?= htmlspecialchars($current_series_desc) ?></textarea>
                                </div>
                            </div>

                            <!-- ================= Add/Edit Series popup ================= -->
                            <div class="modal fade" id="addSeriesModal" tabindex="-1" role="dialog" aria-labelledby="addSeriesModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addSeriesModalLabel">Add New Series</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group" style="display:block;">
                                                <label>Series Title <span style="color:red">*</span></label>
                                                <input type="text" class="form-control" id="series_title_new" placeholder="Enter series title">
                                            </div>
                                            <div class="form-group" style="display:block;">
                                                <label>Series Description</label>
                                                <textarea class="form-control" id="series_description_new" rows="4" placeholder="Enter Series Description"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-primary" id="saveNewSeriesBtn">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <style>
                                #addSeriesModal .form-group { display:block !important; align-items:initial !important; }
                                #addSeriesModal .form-group > label { display:block !important; flex:none !important; max-width:none !important; width:auto !important; margin-bottom:6px !important; padding-right:0 !important; }
                                #addSeriesModal .form-group > *:not(label) { width:100% !important; flex:none !important; }
                                #addSeriesModal { z-index: 100050; }
                            </style>

                            <script>
                            (function () {
                                var sel        = document.getElementById('series_title_select');
                                var hidden     = document.getElementById('series_title');
                                var descPost   = document.getElementById('series_description');   // VISIBLE page field that posts
                                var addBtn     = document.getElementById('addNewSeriesBtn');
                                var editBtn    = document.getElementById('editSeriesBtn');
                                var deleteBtn  = document.getElementById('deleteSeriesBtn');
                                var modal      = document.getElementById('addSeriesModal');
                                var modalTitle = document.getElementById('addSeriesModalLabel');
                                var saveBtn    = document.getElementById('saveNewSeriesBtn');
                                var titleNew   = document.getElementById('series_title_new');
                                var descNew    = document.getElementById('series_description_new');
                                if (!sel || !hidden || !modal) return;

                                // title -> description map (from the film_series table).
                                var seriesDescMap = <?= json_encode($series_desc_map, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?> || {};
                                var editMode = false;      // false = adding new, true = editing selected
                                var editOriginalTitle = '';

                                function showModal() {
                                    try {
                                        if (window.jQuery && $.fn && $.fn.modal) { $(modal).modal('show'); return; }
                                        if (window.bootstrap && bootstrap.Modal) { bootstrap.Modal.getOrCreateInstance(modal).show(); return; }
                                    } catch (e) {}
                                    modal.classList.add('show'); modal.style.display = 'block';
                                    document.body.classList.add('modal-open');
                                }
                                function hideModal() {
                                    try {
                                        if (window.jQuery && $.fn && $.fn.modal) { $(modal).modal('hide'); return; }
                                        if (window.bootstrap && bootstrap.Modal) { var inst = bootstrap.Modal.getInstance(modal); if (inst) { inst.hide(); return; } }
                                    } catch (e) {}
                                    modal.classList.remove('show'); modal.style.display = 'none';
                                    document.body.classList.remove('modal-open');
                                }

                                // ---- Add New: open an empty popup ----
                                if (addBtn) addBtn.addEventListener('click', function () {
                                    editMode = false; editOriginalTitle = '';
                                    if (modalTitle) modalTitle.textContent = 'Add New Series';
                                    titleNew.value = '';
                                    descNew.value  = '';
                                    showModal();
                                    setTimeout(function () { try { titleNew.focus(); } catch (e) {} }, 200);
                                });

                                // ---- Edit: open the popup pre-filled with the selected series ----
                                if (editBtn) editBtn.addEventListener('click', function () {
                                    var t = sel.value || '';
                                    if (t === '') { alert('Please select a series to edit.'); return; }
                                    editMode = true; editOriginalTitle = t;
                                    if (modalTitle) modalTitle.textContent = 'Edit Series';
                                    titleNew.value = t;
                                    descNew.value  = seriesDescMap.hasOwnProperty(t) ? seriesDescMap[t] : (descPost ? descPost.value : '');
                                    showModal();
                                    setTimeout(function () { try { titleNew.focus(); } catch (e) {} }, 200);
                                });

                                // ---- Delete: remove the selected series from the master table ----
                                if (deleteBtn) deleteBtn.addEventListener('click', function () {
                                    var t = sel.value || '';
                                    if (t === '') { alert('Please select a series to delete.'); return; }
                                    if (!confirm('Delete series "' + t + '"? This removes it from the series list.')) return;

                                    var afterDelete = function () {
                                        // remove option, clear selection + description
                                        for (var i = 0; i < sel.options.length; i++) {
                                            if (sel.options[i].value === t) { sel.remove(i); break; }
                                        }
                                        delete seriesDescMap[t];
                                        sel.value = ''; hidden.value = '';
                                        if (descPost) descPost.value = '';
                                    };
                                    var url = '<?= base_url('film/series/delete') ?>';
                                    if (window.jQuery) {
                                        $.post(url, { series_title: t })
                                         .done(function (res) {
                                            try { if (typeof res === 'string') res = JSON.parse(res); } catch (e) {}
                                            if (res && res.status === 'success') { afterDelete(); }
                                            else { alert((res && res.message) || 'Failed to delete series'); }
                                         })
                                         .fail(function () { alert('Network error deleting series'); });
                                    } else { afterDelete(); }
                                });

                                // ---- Save (Add or Edit): persist via AJAX, then reflect in the form ----
                                if (saveBtn) saveBtn.addEventListener('click', function () {
                                    var t = (titleNew.value || '').trim();
                                    var d = (descNew.value || '');
                                    if (t === '') { titleNew.focus(); return; }

                                    saveBtn.disabled = true;
                                    var origText = saveBtn.textContent; saveBtn.textContent = 'Saving...';

                                    var done = function (savedTitle, savedDesc) {
                                        // If editing and the title changed, drop the old option.
                                        if (editMode && editOriginalTitle && editOriginalTitle !== savedTitle) {
                                            for (var j = 0; j < sel.options.length; j++) {
                                                if (sel.options[j].value === editOriginalTitle) { sel.remove(j); break; }
                                            }
                                            delete seriesDescMap[editOriginalTitle];
                                        }
                                        // add option if missing, then select it
                                        var exists = false;
                                        for (var i = 0; i < sel.options.length; i++) {
                                            if (sel.options[i].value === savedTitle) { exists = true; break; }
                                        }
                                        if (!exists) {
                                            var opt = document.createElement('option');
                                            opt.value = savedTitle; opt.textContent = savedTitle;
                                            sel.appendChild(opt);
                                        }
                                        sel.value = savedTitle;
                                        seriesDescMap[savedTitle] = savedDesc;
                                        hidden.value = savedTitle;
                                        if (descPost) {
                                            descPost.value = savedDesc;
                                            // refresh the word counter if one is attached
                                            descPost.dispatchEvent(new Event('input'));
                                        }
                                        saveBtn.disabled = false; saveBtn.textContent = origText;
                                        hideModal();
                                    };

                                    var url = '<?= base_url('film/series/save') ?>';
                                    if (window.jQuery) {
                                        $.post(url, { series_title: t, series_description: d })
                                         .done(function (res) {
                                            try { if (typeof res === 'string') res = JSON.parse(res); } catch (e) {}
                                            if (res && res.status === 'success') { done(res.series_title, res.series_description); }
                                            else { alert((res && res.message) || 'Failed to save series'); saveBtn.disabled = false; saveBtn.textContent = origText; }
                                         })
                                         .fail(function () { alert('Network error saving series'); saveBtn.disabled = false; saveBtn.textContent = origText; });
                                    } else {
                                        done(t, d);
                                    }
                                });

                                // ---- Picking an existing series: auto-fill the page description field ----
                                sel.addEventListener('change', function () {
                                    var t = sel.value || '';
                                    hidden.value = t;
                                    if (descPost) {
                                        descPost.value = (t && seriesDescMap.hasOwnProperty(t)) ? seriesDescMap[t] : '';
                                        descPost.dispatchEvent(new Event('input')); // refresh word counter
                                    }
                                });
                            })();
                            </script>

                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Director(s) <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center" style="gap:8px;">
                                        <select class="form-control select2" name="directors[]" id="directors" multiple required style="flex:1;" data-placeholder="Select Director">
                                            <?php
                                            $selected_directors = [];
                                            if (isset($film) && isset($film->id) && $this->db->table_exists('film_director')) {
                                                $director_map_rows = $this->db
                                                    ->select('director_id')
                                                    ->from('film_director')
                                                    ->where('film_id', (int)$film->id)
                                                    ->get()
                                                    ->result();
                                                foreach ($director_map_rows as $dmr) {
                                                    if (isset($dmr->director_id) && $dmr->director_id !== '') {
                                                        $selected_directors[] = (string)$dmr->director_id;
                                                    }
                                                }
                                            }
                                            if (empty($selected_directors) && isset($film) && isset($film->directors)) {
                                                $selected_directors = array_map('trim', explode(',', (string)$film->directors));
                                            }
                                            $director_rows = $this->db->query("
                                                SELECT id, first_name, middle_name, last_name FROM person
                                                ORDER BY LOWER(TRIM(CONCAT_WS(' ', IFNULL(first_name,''), IFNULL(middle_name,''), IFNULL(last_name,'')))) ASC, id ASC
                                            ")->result();
                                            foreach ($director_rows as $person) {
                                                $parts = [];
                                                if (!empty(trim($person->first_name))) { $parts[] = trim($person->first_name); }
                                                if (!empty(trim($person->middle_name))) { $parts[] = trim($person->middle_name); }
                                                if (!empty(trim($person->last_name))) { $parts[] = trim($person->last_name); }
                                                $label = !empty($parts) ? implode(' ', $parts) : ('Person #' . $person->id);
                                                $selected = in_array((string)$person->id, $selected_directors, true) ? 'selected' : '';
                                                echo '<option value="'.htmlspecialchars($person->id).'" '.$selected.'>'.htmlspecialchars($label).'</option>';
                                            }
                                            ?>
                                        </select>
                                        <button type="button" class="btn btn-success btn-sm" id="addDirectorBtn" style="white-space:nowrap;">Add New</button>
                                        <button type="button" class="btn btn-primary btn-sm ml-1" id="editDirectorBtn" style="white-space:nowrap;">Edit</button>
                                        <button type="button" class="btn btn-danger btn-sm ml-1" id="deleteDirectorBtn" style="white-space:nowrap;">Delete</button>
                                    </div>

                                    <!-- Add New Director popup — saves a new person via /person/ajax-create
                                         (same endpoint used by Singer/Poet "Add New") and selects the new
                                         option in the Director(s) dropdown in real time. -->
                                    <div class="modal fade" id="addDirectorModal" tabindex="-1" role="dialog" aria-labelledby="addDirectorModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addDirectorModalLabel">Add New Director</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group" style="display:block;">
                                                        <label>Name <span style="color:red">*</span></label>
                                                        <input type="text" class="form-control" id="addDirectorName" placeholder="Enter director's full name">
                                                    </div>
                                                    <div class="form-group" style="display:block;">
                                                        <label>Hyperlink (optional)</label>
                                                        <input type="url" class="form-control" id="addDirectorLink" placeholder="https://example.com">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-primary" id="saveNewDirectorBtn">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <style>
                                        /* Override the page's flex .form-group so labels/inputs stack normally
                                           inside this modal (same pattern used elsewhere in the admin). */
                                        #addDirectorModal .form-group { display:block !important; align-items:initial !important; }
                                        #addDirectorModal .form-group > label { display:block !important; flex:none !important; max-width:none !important; width:auto !important; margin-bottom:6px !important; padding-right:0 !important; }
                                        #addDirectorModal .form-group > *:not(label) { width:100% !important; flex:none !important; }
                                        #addDirectorModal { z-index: 100050; }
                                    </style>
                                    <script>
                                    (function () {
                                        function showModal(modal) {
                                            try {
                                                if (window.jQuery && $.fn && $.fn.modal) { $(modal).modal('show'); return; }
                                                if (window.bootstrap && bootstrap.Modal) { bootstrap.Modal.getOrCreateInstance(modal).show(); return; }
                                            } catch (e) {}
                                            modal.classList.add('show'); modal.style.display = 'block';
                                            document.body.classList.add('modal-open');
                                        }
                                        function hideModal(modal) {
                                            try {
                                                if (window.jQuery && $.fn && $.fn.modal) { $(modal).modal('hide'); return; }
                                                if (window.bootstrap && bootstrap.Modal) {
                                                    var inst = bootstrap.Modal.getInstance(modal); if (inst) { inst.hide(); return; }
                                                }
                                            } catch (e) {}
                                            modal.classList.remove('show'); modal.style.display = 'none';
                                            document.body.classList.remove('modal-open');
                                        }
                                        function init() {
                                            var btn     = document.getElementById('addDirectorBtn');
                                            var modal   = document.getElementById('addDirectorModal');
                                            var saveBtn = document.getElementById('saveNewDirectorBtn');
                                            var sel     = document.getElementById('directors');
                                            if (!btn || !modal || !saveBtn || !sel) { setTimeout(init, 200); return; }
                                            modal.querySelectorAll('[data-dismiss="modal"], .close, .btn-secondary').forEach(function (b) {
                                                b.addEventListener('click', function (e) { e.preventDefault(); hideModal(modal); });
                                            });
                                            btn.onclick = function (e) {
                                                e.preventDefault(); e.stopPropagation();
                                                document.getElementById('addDirectorName').value = '';
                                                document.getElementById('addDirectorLink').value = '';
                                                setTimeout(function () {
                                                    showModal(modal);
                                                    setTimeout(function () { var f = document.getElementById('addDirectorName'); if (f) f.focus(); }, 200);
                                                }, 0);
                                            };
                                            saveBtn.onclick = async function () {
                                                var name = (document.getElementById('addDirectorName').value || '').trim();
                                                var link = (document.getElementById('addDirectorLink').value || '').trim();
                                                if (!name) { alert('Please enter the director\'s name'); return; }
                                                saveBtn.disabled = true;
                                                try {
                                                    var res = await fetch('<?= base_url('person/ajax-create') ?>', {
                                                        method: 'POST',
                                                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                        body: 'name=' + encodeURIComponent(name) + '&hyperlink=' + encodeURIComponent(link)
                                                    });
                                                    var data = await res.json();
                                                    if (data && data.success && data.id) {
                                                        // Add the new <option>, mark it selected, refresh select2.
                                                        var opt = document.createElement('option');
                                                        opt.value = String(data.id);
                                                        opt.text  = data.fullName || name;
                                                        opt.selected = true;
                                                        sel.add(opt);
                                                        if (window.__adminRefreshSelect) {
                                                            window.__adminRefreshSelect('#directors', String(data.id));
                                                        } else if (window.jQuery) {
                                                            $('#directors').trigger('change');
                                                        }
                                                        hideModal(modal);
                                                        if (window.Swal) Swal.fire({ icon:'success', title:'Director added', timer:1200, showConfirmButton:false });
                                                    } else {
                                                        alert('Failed: ' + (data && data.message ? data.message : 'Unknown error'));
                                                    }
                                                } catch (e) {
                                                    alert('Error: ' + e.message);
                                                } finally {
                                                    saveBtn.disabled = false;
                                                }
                                            };

                                            // Edit: reuse the "Add New Director" modal via the admin-wide helper.
                                            // Reads the director currently selected in #directors, prefills the
                                            // modal from the DB, and swaps Save for an Update button. Same pattern
                                            // as Poet/Translator edit on the song & couplet pages.
                                            var editBtn = document.getElementById('editDirectorBtn');
                                            if (editBtn) {
                                                editBtn.addEventListener('click', function () {
                                                    if (!window.__adminEditOption) { alert('Edit helper not loaded yet. Please try again in a moment.'); return; }
                                                    var BASE = '<?= base_url() ?>';
                                                    window.__adminEditOption({
                                                        selectId:     '#directors',
                                                        modalId:      '#addDirectorModal',
                                                        addSaveBtnId: '#saveNewDirectorBtn',
                                                        updateUrl:    BASE + 'song/ajax_update_person',
                                                        prefillUrl:   BASE + 'song/ajax_get_person',
                                                        editTitle:    'Edit Director',
                                                        fields: [
                                                            { inputId: '#addDirectorName', postKey: 'name', primary: true },
                                                            { inputId: '#addDirectorLink', postKey: 'hyperlink' }
                                                        ]
                                                    });
                                                });
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

                            <?php
                            $filmThumbPath = '';
                            if (isset($film) && !empty($film->thumbnail_Image)) {
                                $filmThumbPath = trim((string) $film->thumbnail_Image);
                            } elseif (isset($film) && !empty($film->thumbnail_url)) {
                                $filmThumbPath = trim((string) $film->thumbnail_url);
                            } elseif (isset($film) && !empty($film->thumbnail_image_upload)) {
                                $filmThumbPath = trim((string) $film->thumbnail_image_upload);
                            }
                            $filmThumbSrc = '';
                            if ($filmThumbPath !== '') {
                                if (preg_match('#^https?://#i', $filmThumbPath)) {
                                    $filmThumbSrc = $filmThumbPath;
                                } else {
                                    $rawThumb = ltrim($filmThumbPath, '/');
                                    $thumbCandidates = [$rawThumb];
                                    if (stripos($rawThumb, 'uploads/') !== 0 && stripos($rawThumb, 'Uploads/') !== 0 && stripos($rawThumb, 'images/') !== 0) {
                                        $thumbCandidates[] = 'uploads/thumbnails/' . $rawThumb;
                                        $thumbCandidates[] = 'Uploads/' . $rawThumb;
                                        $thumbCandidates[] = 'images/' . $rawThumb;
                                    }
                                    $thumbCandidates = array_values(array_unique($thumbCandidates));
                                    foreach ($thumbCandidates as $candidate) {
                                        if (file_exists(FCPATH . $candidate)) {
                                            $filmThumbSrc = base_url($candidate);
                                            break;
                                        }
                                    }
                                    if ($filmThumbSrc === '') {
                                        if (isset($filmThumbPath[0]) && $filmThumbPath[0] === '/') {
                                            $filmThumbSrc = rtrim(base_url(), '/') . $filmThumbPath;
                                        } else {
                                            $filmThumbSrc = rtrim(base_url(), '/') . '/' . $rawThumb;
                                        }
                                    }
                                }
                            }
                            ?>
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Thumbnail Image Upload <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <?php if (isset($film) && $filmThumbPath !== ''): ?>
                                        <p class="mb-2 text-muted small">Current file is kept unless you choose a new image.</p>
                                        <input type="hidden" name="thumbnail_Image" value="<?php echo htmlspecialchars($filmThumbPath); ?>">
                                    <?php endif; ?>
                                    <input type="file" name="thumbnail_Image" id="thumbnail_Image" class="form-control" accept="image/*">
                                    <?php if (isset($film) && $filmThumbSrc !== ''): ?>
                                        <div style="margin-top:8px;" id="filmThumbPreviewWrap">
                                            <img src="<?php echo htmlspecialchars($filmThumbSrc); ?>" alt="Current thumbnail" id="filmThumbPreviewImg" style="max-width:200px;height:auto;border:1px solid #ddd;border-radius:4px;"
                                                onerror="this.style.display='none';var w=document.getElementById('filmThumbPreviewBroken');if(w)w.style.display='block';">
                                            <p class="small text-muted mt-1" id="filmThumbPreviewBroken" style="display:none;">Preview could not be loaded.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Thumbnail Excerpt <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <input type="text" name="thumbnail_excerpt" id="thumbnail_excerpt" class="form-control" placeholder="Enter Thumbnail Excerpt" required value="<?php echo isset($film) ? htmlspecialchars($film->thumbnail_excerpt) : ''; ?>">
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">About</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" id="about" name="about" rows="4" placeholder="Enter About"><?php echo isset($film) ? htmlspecialchars($film->about) : ''; ?></textarea>
                                </div>
                            </div>
                                

                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Duration <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <input type="text" name="duration" id="duration" class="form-control" placeholder="Enter Duration" required value="<?php echo isset($film) ? htmlspecialchars($film->duration) : ''; ?>">
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Year of Production <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <select name="year" id="year" class="form-control" required>
                                        <option value="">Select Year</option>
                                        <?php 
                                        $currentYear = date("Y"); 
                                        $selected_year = isset($film) ? $film->year : '';
                                        for($i=$currentYear; $i>=1900; $i--) {
                                            $sel = ($selected_year == $i) ? 'selected' : '';
                                            echo "<option value='$i' $sel>$i</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <label><strong>Related Content</strong></label>
                            <div style="padding-left:20px;">
                                <!-- Keywords -->
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ Keywords</label>
                                    <div class="col-md-4 d-flex align-items-center gap-2">
                                        <select class="form-control select2" name="related_keywords[]" id="related_keywords" multiple>
                                            <option value="">None Selected</option>
                                            <?php foreach ($keyword_rows as $keyword): ?>
                                                <?php $label = !empty($keyword->word_transliteration) ? $keyword->word_transliteration : ('Keyword #' . $keyword->id); ?>
                                                <option value="<?php echo htmlspecialchars((string)$keyword->id); ?>" <?php echo in_array((string)$keyword->id, $selected_film_keywords, true) ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-success btn-sm ml-2" id="addNewKeywordBtn">Add New</button>
                                        <button type="button" class="btn btn-primary btn-sm ml-1" id="editKeywordBtn">Edit</button>
                                        <button type="button" class="btn btn-danger btn-sm ml-1" id="deleteKeywordBtn">Delete</button>
                                    </div>
                                </div>
                                <!-- Songs -->
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ Songs</label>
                                    <div class="col-md-4 d-flex align-items-center gap-2">
                                        <select class="form-control select2" name="related_songs[]" id="related_songs" multiple>
                                            <option value="">None Selected</option>
                                            <?php foreach ($song_rows as $song): ?>
                                                <?php $label = !empty($song->umbrellaTitle) ? $song->umbrellaTitle : ('Song #' . $song->id); ?>
                                                <option value="<?php echo htmlspecialchars((string)$song->id); ?>" <?php echo in_array((string)$song->id, $selected_film_songs, true) ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Poems -->
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ Poems</label>
                                    <div class="col-md-4 d-flex align-items-center gap-2">
                                        <select class="form-control select2" name="related_poems[]" id="related_poems" multiple>
                                            <option value="">None Selected</option>
                                            <?php foreach ($poem_rows as $poem): ?>
                                                <?php $label = !empty($poem->poem_label) ? $poem->poem_label : ('Poem #' . $poem->id); ?>
                                                <option value="<?php echo htmlspecialchars((string)$poem->id); ?>" <?php echo in_array((string)$poem->id, $selected_film_poems, true) ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Reflections -->
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ Reflections</label>
                                    <div class="col-md-4 d-flex align-items-center gap-2">
                                        <select class="form-control select2" name="related_reflections[]" id="related_reflections" multiple>
                                            <option value="">None Selected</option>
                                            <?php foreach ($reflection_rows as $reflection): ?>
                                                <?php $label = !empty($reflection->title) ? $reflection->title : ('Reflection #' . $reflection->id); ?>
                                                <option value="<?php echo htmlspecialchars((string)$reflection->id); ?>" <?php echo in_array((string)$reflection->id, $selected_film_reflections, true) ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Film Episode -->
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ Film Episode</label>
                                    <div class="col-md-4 d-flex align-items-center gap-2">
                                        <select class="form-control select2" name="related_film_episodes[]" id="related_film_episodes" multiple>
                                            <option value="">None Selected</option>
                                            <?php foreach ($episode_rows as $episode): ?>
                                                <?php
                                                    $label = !empty($episode->episode_label) ? $episode->episode_label : ('Episode #' . $episode->id);
                                                ?>
                                                <option value="<?php echo htmlspecialchars((string)$episode->id); ?>" <?php echo in_array((string)$episode->id, $selected_film_episodes, true) ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- People -->
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ People</label>
                                    <div class="col-md-4 d-flex align-items-center gap-2">
                                        <select class="form-control select2" name="related_people[]" id="related_people" multiple>
                                            <option value="">None Selected</option>
                                            <?php foreach ($person_rows as $person): ?>
                                                <?php
                                                    $parts = [];
                                                    if (!empty(trim($person->first_name))) { $parts[] = trim($person->first_name); }
                                                    if (!empty(trim($person->middle_name))) { $parts[] = trim($person->middle_name); }
                                                    if (!empty(trim($person->last_name))) { $parts[] = trim($person->last_name); }
                                                    $label = !empty($parts) ? implode(' ', $parts) : ('Person #' . $person->id);
                                                ?>
                                                <option value="<?php echo htmlspecialchars((string)$person->id); ?>" <?php echo in_array((string)$person->id, $selected_film_people, true) ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <label><strong>Meta Data</strong></label>
                            <div style="padding-left:20px;">
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ Meta Title</label>
                                    <div class="col-md-4">
                                        <input type="text" name="meta_title" class="form-control" placeholder="Enter Meta Title" value="<?php echo isset($film) ? htmlspecialchars($film->meta_title) : ''; ?>">
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ Meta Keywords</label>
                                    <div class="col-md-4">
                                        <input type="text" name="meta_keywords" class="form-control" placeholder="Enter Meta Keywords" value="<?php echo isset($film) ? htmlspecialchars(isset($film->meta_keywords) ? $film->meta_keywords : (isset($film->meta_keyword) ? $film->meta_keyword : '')) : ''; ?>">
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ Meta Description</label>
                                    <div class="col-md-4">
                                        <textarea name="meta_description" class="form-control" rows="3" placeholder="Enter Meta Description"><?php echo isset($film) ? htmlspecialchars($film->meta_description) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Publish Status</label>
                                <div class="col-md-4">
                                    <?php
                                        $publishYes = false;
                                        if (isset($film) && isset($film->publish)) {
                                            $publishYes = in_array(strtolower((string)$film->publish), ['1', 'true', 'yes'], true);
                                        }
                                    ?>
                                    <select name="publish" id="publish" class="form-control">
                                        <option value="0" <?php echo !$publishYes ? 'selected' : ''; ?>>No</option>
                                        <option value="1" <?php echo $publishYes ? 'selected' : ''; ?>>Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="save-btn-container">
                                <button type="button" class="btn btn-primary" onclick="switchTab('episode-content')">
                                    Next: Film Episode <i class="fas fa-arrow-right"></i>
                                </button>
                                <?= admin_edit_preview_button(isset($film) ? $film : null) ?>
                                <button type="submit" class="btn btn-primary">Save Film</button>
                            </div>
                        </form>
                    </div>

                    <!-- Film Episode Tab -->
                    <div id="episode-content" class="tab-content<?php echo ($activeTab=='episode-content')?' active':''; ?>">

                        <form name="episodeForm" id="episodeForm" method="post" action="<?php echo base_url('FilmController/save_filmEpisode'); ?>" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo isset($filmEpisode) ? htmlspecialchars($filmEpisode->id) : ''; ?>">

                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Film Episode Title <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <input type="text" name="film_episode_title" class="form-control" placeholder="Enter Film Episode Title" required value="<?php echo isset($filmEpisode) ? htmlspecialchars($filmEpisode->film_episode_title) : ''; ?>">
                                </div>
                            </div>

                            <!-- YouTube Video ID field -->
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">YouTube Video ID</label>
                                <div class="col-md-4">
                                    <input type="text" name="youtube_link" id="youtube_link" class="form-control" placeholder="Enter YouTube Video ID" value="<?php echo isset($filmEpisode) ? htmlspecialchars($filmEpisode->youtube_link) : ''; ?>">
                                </div>
                            </div>


                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Main Film Title <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <select name="main_title" id="main_title_ep" class="form-control" required>
                                        <option value="">Select Main Film</option>
                                        <?php
                                        $mainVal = '';
                                        if (isset($filmEpisode)) {
                                            $mainVal = isset($filmEpisode->film_id) && (int)$filmEpisode->film_id > 0
                                                ? (string)(int)$filmEpisode->film_id
                                                : (isset($filmEpisode->main_title) ? (string)$filmEpisode->main_title : '');
                                        }
                                        // Source: same `film` table that filmDetails-list reads from
                                        $filmsList = $this->db->table_exists('film')
                                            ? $this->db->query("SELECT id, COALESCE(NULLIF(TRIM(english_transliteration), ''), NULLIF(TRIM(english_translation), ''), NULLIF(TRIM(original_title), ''), CONCAT('Film #', id)) AS film_label FROM film ORDER BY film_label ASC")->result()
                                            : [];
                                        foreach ($filmsList as $f) {
                                            $sel = ((string)$mainVal === (string)$f->id) ? 'selected' : '';
                                            echo '<option value="'.htmlspecialchars((string)$f->id).'" '.$sel.'>'.htmlspecialchars((string)$f->film_label).'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Episode Number <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <input type="text" name="episode_no" class="form-control" placeholder="Enter Episode Number" required value="<?php echo isset($filmEpisode) ? htmlspecialchars($filmEpisode->episode_no) : ''; ?>">
                                </div>
                            </div>

                            <?php
                            $epThumbPath = '';
                            if (isset($filmEpisode) && !empty($filmEpisode->thumbnail_image_upload)) {
                                $epThumbPath = trim((string) $filmEpisode->thumbnail_image_upload);
                            }
                            $epThumbSrc = '';
                            if ($epThumbPath !== '') {
                                $epThumbSrc = preg_match('#^https?://#i', $epThumbPath) ? $epThumbPath : (rtrim(base_url(), '/') . '/' . ltrim($epThumbPath, '/'));
                            }
                            ?>
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Thumbnail Image Upload <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <input type="file" name="thumbnail_image_upload" class="form-control" id="ep_thumbnail" accept="image/*">
                                    <?php if (isset($filmEpisode) && $epThumbSrc !== ''): ?>
                                        <div style="margin-top:8px;" id="episodeThumbPreviewWrap">
                                            <img src="<?php echo htmlspecialchars($epThumbSrc); ?>" alt="Current Thumbnail" style="max-width:200px;height:auto;border:1px solid #ddd;border-radius:4px;"
                                                onerror="this.style.display='none';">
                                            <input type="hidden" name="old_thumbnail_image" value="<?php echo htmlspecialchars($filmEpisode->thumbnail_image_upload); ?>">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Thumbnail Excerpt <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <input type="text" name="thumbnail_excerpt" class="form-control" placeholder="Enter Thumbnail Excerpt" required value="<?php echo isset($filmEpisode) ? htmlspecialchars($filmEpisode->thumbnail_excerpt) : ''; ?>">
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">About</label>
                                <div class="col-md-9">
                                    <textarea name="about_text" id="about_text" class="form-control" rows="4" placeholder="Enter About"><?php echo isset($filmEpisode) ? htmlspecialchars($filmEpisode->about_text) : ''; ?></textarea>
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Duration <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <input type="text" name="duration" class="form-control" placeholder="Enter Duration" required value="<?php echo isset($filmEpisode) ? htmlspecialchars($filmEpisode->duration) : ''; ?>">
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Year of Production <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <select name="year" class="form-control" required>
                                        <option value="">Select Year</option>
                                        <?php 
                                        $currentYear = date("Y"); 
                                        $selected_year = isset($filmEpisode) ? $filmEpisode->year : '';
                                        for($i=$currentYear; $i>=1900; $i--) {
                                            $sel = ($selected_year == $i) ? 'selected' : '';
                                            echo "<option value='$i' $sel>$i</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <label><strong>Related Content</strong></label>
                            <div style="padding-left:20px;">
                                <!-- Keywords -->
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ Keywords</label>
                                    <div class="col-md-4 d-flex align-items-center gap-2">
                                        <select class="form-control select2" name="related_keywords[]" id="related_keywords_ep" multiple>
                                            <option value="">None Selected</option>
                                            <?php foreach ($keyword_rows as $keyword): ?>
                                                <?php $label = !empty($keyword->word_transliteration) ? $keyword->word_transliteration : ('Keyword #' . $keyword->id); ?>
                                                <option value="<?php echo htmlspecialchars((string)$keyword->id); ?>" <?php echo in_array((string)$keyword->id, $selected_episode_keywords, true) ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-success btn-sm ml-2" id="addNewKeywordBtnEp">Add New</button>
                                        <button type="button" class="btn btn-primary btn-sm ml-1" id="editKeywordBtnEp">Edit</button>
                                        <button type="button" class="btn btn-danger btn-sm ml-1" id="deleteKeywordBtnEp">Delete</button>
                                    </div>
                                </div>
                                <!-- Songs -->
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ Songs</label>
                                    <div class="col-md-4 d-flex align-items-center gap-2">
                                        <select class="form-control select2" name="related_songs[]" id="related_songs_ep" multiple>
                                            <option value="">None Selected</option>
                                            <?php foreach ($song_rows as $song): ?>
                                                <?php $label = !empty($song->umbrellaTitle) ? $song->umbrellaTitle : ('Song #' . $song->id); ?>
                                                <option value="<?php echo htmlspecialchars((string)$song->id); ?>" <?php echo in_array((string)$song->id, $selected_episode_songs, true) ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Poems -->
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ Poems</label>
                                    <div class="col-md-4 d-flex align-items-center gap-2">
                                        <select class="form-control select2" name="related_poems[]" id="related_poems_ep" multiple>
                                            <option value="">None Selected</option>
                                            <?php foreach ($poem_rows as $poem): ?>
                                                <?php $label = !empty($poem->poem_label) ? $poem->poem_label : ('Poem #' . $poem->id); ?>
                                                <option value="<?php echo htmlspecialchars((string)$poem->id); ?>" <?php echo in_array((string)$poem->id, $selected_episode_poems, true) ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Reflections -->
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ Reflections</label>
                                    <div class="col-md-4 d-flex align-items-center gap-2">
                                        <select class="form-control select2" name="episode_related_reflections[]" id="episode_related_reflections" multiple>
                                            <option value="">None Selected</option>
                                            <?php foreach ($reflection_rows as $reflection): ?>
                                                <?php $label = !empty($reflection->title) ? $reflection->title : ('Reflection #' . $reflection->id); ?>
                                                <option value="<?php echo htmlspecialchars((string)$reflection->id); ?>" <?php echo in_array((string)$reflection->id, $selected_episode_reflections, true) ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- People -->
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ People</label>
                                    <div class="col-md-4 d-flex align-items-center gap-2">
                                        <select class="form-control select2" name="episode_related_people[]" id="episode_related_people" multiple>
                                            <option value="">None Selected</option>
                                            <?php foreach ($person_rows as $person): ?>
                                                <?php
                                                    $parts = [];
                                                    if (!empty(trim($person->first_name))) { $parts[] = trim($person->first_name); }
                                                    if (!empty(trim($person->middle_name))) { $parts[] = trim($person->middle_name); }
                                                    if (!empty(trim($person->last_name))) { $parts[] = trim($person->last_name); }
                                                    $label = !empty($parts) ? implode(' ', $parts) : ('Person #' . $person->id);
                                                ?>
                                                <option value="<?php echo htmlspecialchars((string)$person->id); ?>" <?php echo in_array((string)$person->id, $selected_episode_people, true) ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Films -->
                                <?php
                                    $ep_film_rows = $this->db->table_exists('film')
                                        ? $this->db->query("SELECT id, COALESCE(NULLIF(TRIM(english_transliteration), ''), NULLIF(TRIM(english_translation), ''), NULLIF(TRIM(original_title), ''), CONCAT('Film #', id)) AS film_label FROM film ORDER BY film_label ASC")->result()
                                        : [];
                                    $selected_episode_films = [];
                                    if (isset($filmEpisode) && !empty($filmEpisode->id)) {
                                        if ($this->db->table_exists('film_episode_film')) {
                                            $efr = $this->db->select('film_id')->from('film_episode_film')->where('film_episode_id', (int)$filmEpisode->id)->get()->result_array();
                                            foreach ($efr as $r) { if (!empty($r['film_id'])) { $selected_episode_films[] = (string)(int)$r['film_id']; } }
                                        }
                                        // Fallback: parent film_id of this episode
                                        if (empty($selected_episode_films) && !empty($filmEpisode->film_id)) {
                                            $selected_episode_films[] = (string)(int)$filmEpisode->film_id;
                                        }
                                        $selected_episode_films = array_values(array_unique($selected_episode_films));
                                    }
                                ?>
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ Films</label>
                                    <div class="col-md-4 d-flex align-items-center gap-2">
                                        <select class="form-control select2" name="episode_related_films[]" id="episode_related_films" multiple>
                                            <option value="">None Selected</option>
                                            <?php foreach ($ep_film_rows as $f): ?>
                                                <option value="<?php echo htmlspecialchars((string)$f->id); ?>" <?php echo in_array((string)$f->id, $selected_episode_films, true) ? 'selected' : ''; ?>><?php echo htmlspecialchars((string)$f->film_label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <label><strong>Meta Data</strong></label>
                            <div style="padding-left:20px;">
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ Meta Title</label>
                                    <div class="col-md-4">
                                        <input type="text" name="meta_title" class="form-control" placeholder="Enter Meta Title" value="<?php echo isset($filmEpisode) ? htmlspecialchars($filmEpisode->meta_title) : ''; ?>">
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ Meta Keywords</label>
                                    <div class="col-md-4">
                                        <input type="text" name="meta_keywords" class="form-control" placeholder="Enter Meta Keywords" value="<?php echo isset($filmEpisode) ? htmlspecialchars(isset($filmEpisode->meta_keywords) ? $filmEpisode->meta_keywords : (isset($filmEpisode->meta_keyword) ? $filmEpisode->meta_keyword : '')) : ''; ?>">
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">⊙ Meta Description</label>
                                    <div class="col-md-4">
                                        <textarea name="meta_description" class="form-control" rows="3" placeholder="Enter Meta Description"><?php echo isset($filmEpisode) ? htmlspecialchars($filmEpisode->meta_description) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Publish Status</label>
                                <div class="col-md-4">
                                    <select name="publish" class="form-control">
                                        <option value="">Select</option>
                                        <option value="true">Yes</option>
                                        <option value="false">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="save-btn-container">
                                <button type="button" class="btn btn-secondary" onclick="switchTab('film-content')">
                                    <i class="fas fa-arrow-left"></i> Back: Film Details
                                </button>
                                <button type="submit" class="btn btn-primary">Save Episode</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

</script>
<!-- Load jQuery and plugins BEFORE custom script -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    window.filmLanguageOptions = <?php echo json_encode(array_values($filmLanguageOptions)); ?>;
    var createFilmLanguageUrl = <?php echo json_encode(base_url('film/language/create')); ?>;
    $('.select2').select2({
        placeholder: "Select options",
        allowClear: true
    });

    // Add New logic for Related Content fields (Film tab)
    function addNewHandler(btnId, selectId, apiUrl, fieldLabel, optionLabelKey, optionIdKey) {
        $(btnId).on('click', function() {
            Swal.fire({
                title: `Add New ${fieldLabel}`,
                input: 'text',
                inputLabel: `${fieldLabel} Name`,
                inputPlaceholder: `Enter new ${fieldLabel.toLowerCase()}`,
                showCancelButton: true,
                confirmButtonText: 'Add',
                cancelButtonText: 'Cancel',
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage(`Please enter a ${fieldLabel.toLowerCase()} name`);
                    }
                    return value;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    fetch(apiUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ name: result.value })
                    })
                    .then(res => res.json())
                    .then(response => {
                        if (response.status === 'success') {
                            // Add new option and select it
                            let newOption = new Option(response[optionLabelKey], response[optionIdKey], true, true);
                            $(selectId).append(newOption).trigger('change');
                            Swal.fire({ icon: 'success', title: `${fieldLabel} Added`, text: response.message });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: response.message || 'Failed to add.' });
                        }
                    })
                    .catch(() => {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to add.' });
                    });
                }
            });
        });
    }

    // Film tab
    // (Keyword Add/Edit moved to its own 4-field modal at the bottom of this file
    //  — uses the same SongController/ajax_create_keyword + ajax_get_keyword +
    //  ajax_update_keyword endpoints as add-song / add-couplet / add-reflection.)
    addNewHandler('#addSongBtn', '#related_songs', '/FilmController/ajax_add_song', 'Song', 'umbrellaTitle', 'id');
    addNewHandler('#addPoemBtn', '#related_poems', '/FilmController/ajax_add_poem', 'Poem', 'original_title', 'id');
    addNewHandler('#addReflectionBtn', '#related_reflections', '/FilmController/ajax_add_reflection', 'Reflection', 'title', 'id');
    addNewHandler('#addPersonBtn', '#related_people', '/FilmController/ajax_add_person', 'Person', 'full_name', 'id');

    // Episode tab — same notice as above for keyword.
    addNewHandler('#addSongBtnEp', '#related_songs_ep', '/FilmController/ajax_add_song', 'Song', 'umbrellaTitle', 'id');
    addNewHandler('#addPoemBtnEp', '#related_poems_ep', '/FilmController/ajax_add_poem', 'Poem', 'original_title', 'id');

    // ...existing code for CKEditor, language, tab switching, and validation...
    setTimeout(function() {
        CKEDITOR.replace('about', {
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

        CKEDITOR.replace('about_text', {
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
    }, 500);

    // Film language + youtube dynamic rows
    var filmLanguageRows = document.getElementById('filmLanguageRows');
    var addFilmLanguageOptionBtn = document.getElementById('addFilmLanguageOptionBtn');

    function bindFilmLanguageRemoveButtons() {
        if (!filmLanguageRows) return;
        var removeButtons = filmLanguageRows.querySelectorAll('.film-language-remove');
        removeButtons.forEach(function(btn, idx) {
            btn.style.display = (idx === 0) ? 'none' : '';
            btn.onclick = function() {
                var row = btn.closest('.film-language-row');
                if (!row) return;
                row.remove();
                bindFilmLanguageRemoveButtons();
            };
        });
    }

    if (filmLanguageRows) {
        // "Add New Language" — simply appends a fresh Language + YouTube link
        // input row to the list. No popup; users can add as many language /
        // YouTube link pairs as they need, then save the form normally.
        if (addFilmLanguageOptionBtn) {
            addFilmLanguageOptionBtn.addEventListener('click', function() {
                var row = document.createElement('div');
                row.className = 'film-language-row d-flex align-items-center mb-2';
                row.style.gap = '8px';
                row.innerHTML =
                    '<input type="text" name="film_language[]" class="form-control film-language-input" placeholder="Enter Language" style="max-width:220px;">' +
                    '<input type="text" name="film_language_youtube_link[]" class="form-control film-language-link-input" placeholder="Video Link">' +
                    '<button type="button" class="btn btn-danger btn-sm film-language-remove">Remove</button>';
                filmLanguageRows.appendChild(row);
                bindFilmLanguageRemoveButtons();
                // Focus the new Language input so the user can start typing immediately.
                var newInput = row.querySelector('input[name="film_language[]"]');
                if (newInput) newInput.focus();
            });
        }
        bindFilmLanguageRemoveButtons();
    }

    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        document.getElementById(tabId).classList.add('active');
        if (tabId === 'film-content') {
            document.getElementById('film-tab').classList.add('active');
        } else if (tabId === 'episode-content') {
            document.getElementById('episode-tab').classList.add('active');
        }
    }
    document.getElementById('film-tab').addEventListener('click', function() {
        switchTab('film-content');
    });
    document.getElementById('episode-tab').addEventListener('click', function() {
        switchTab('episode-content');
    });

    document.getElementById('filmForm').addEventListener('submit', function(e) {
        e.preventDefault();
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        const requiredFields = ['main_title', 'directors', 'thumbnail_Image', 'thumbnail_excerpt', 'duration', 'year'];
        for (let fieldId of requiredFields) {
            let element = document.getElementById(fieldId);
            if (!element || !element.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Input',
                    text: `Please fill all required fields`,
                    confirmButtonText: 'OK'
                });
                return false;
            }
        }
        this.submit();
    });

    document.getElementById('episodeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        const idField = this.querySelector('[name="id"]');
        const isEdit = idField && idField.value && idField.value.trim();
        const requiredFields = [
            { name: 'film_episode_title', label: 'Film Episode Title' },
            { name: 'main_title', label: 'Main Film Title' },
            { name: 'episode_no', label: 'Episode Number' },
            { name: 'thumbnail_excerpt', label: 'Thumbnail Excerpt' },
            { name: 'duration', label: 'Duration' },
            { name: 'year', label: 'Year of Production' }
        ];
        if (!isEdit) {
            requiredFields.push({ name: 'thumbnail_image_upload', label: 'Thumbnail Image' });
        }
        let isValid = true;
        let firstMissingField = null;
        for (let field of requiredFields) {
            let element = this.querySelector('[name="' + field.name + '"]');
            if (!element) {
                console.warn('Field not found: ' + field.name);
                continue;
            }
            let value = '';
            if (field.name === 'thumbnail_image_upload') {
                value = (element.files && element.files.length > 0) ? 1 : '';
            } else {
                value = element.value ? element.value.trim() : '';
            }
            if (!value) {
                if (!firstMissingField) {
                    firstMissingField = field.label;
                }
                isValid = false;
                break;
            }
        }
        if (isValid) {
            this.submit();
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Input',
                text: `Please fill: ${firstMissingField}`,
                confirmButtonText: 'OK'
            });
        }
    });
});
</script>

<!-- ============================================================
     KEYWORD ADD/EDIT POPUP — shared by both Film and Film Episode tabs.
     Uses the same 4 fields + endpoints as add-song / add-couplet /
     add-reflection (SongController::ajax_create_keyword / ajax_get_keyword /
     ajax_update_keyword), so the experience is consistent across the admin.
     ============================================================ -->
<div class="modal fade" id="addNewKeywordModal" tabindex="-1" role="dialog" aria-labelledby="addNewKeywordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewKeywordModalLabel">Add New Keyword</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <!-- Fields/labels mirror the standalone "Add Keyword" page (add-keywords.php)
                     Keywords section so the inline popup matches it: Original, Translation,
                     Transliteration (required *), Word Meaning. -->
                <div class="form-group">
                    <label>Original</label>
                    <input type="text" class="form-control" id="newKeywordOriginal" placeholder="Enter Word Original">
                </div>
                <div class="form-group">
                    <label>Translation</label>
                    <input type="text" class="form-control" id="newKeywordTranslation" placeholder="Enter Word Translation">
                </div>
                <div class="form-group">
                    <label>Transliteration <span style="color:red">*</span></label>
                    <input type="text" class="form-control" id="newKeywordTransliteration" placeholder="Enter Word Transliteration" required>
                </div>
                <div class="form-group">
                    <label>Word Meaning</label>
                    <textarea class="form-control" id="newKeywordMeaning" rows="3" placeholder="Enter Word Meaning"></textarea>
                </div>
            </div>
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
(function () {
    var BASE = '<?php echo base_url(); ?>';

    function kwReadModal() {
        return {
            word_original:        ((document.getElementById('newKeywordOriginal')        || {}).value || '').trim(),
            word_translation:     ((document.getElementById('newKeywordTranslation')     || {}).value || '').trim(),
            word_transliteration: ((document.getElementById('newKeywordTransliteration') || {}).value || '').trim(),
            glossary_meaning:     ((document.getElementById('newKeywordMeaning')         || {}).value || '').trim()
        };
    }
    function kwClearModal() {
        ['newKeywordOriginal','newKeywordTranslation','newKeywordTransliteration','newKeywordMeaning'].forEach(function (id) {
            var el = document.getElementById(id); if (el) el.value = '';
        });
    }
    function showKwModal() {
        var $m = $('#addNewKeywordModal');
        // Force-clean stale state from any half-open attempt.
        $('.modal-backdrop').remove();
        $m.css('display','block').removeAttr('aria-hidden').attr('aria-modal','true').attr('role','dialog');
        void $m[0].offsetWidth;
        $m.addClass('show');
        $('body').addClass('modal-open');
        $('body').append('<div class="modal-backdrop fade show __kw_backdrop"></div>');
        // Reset title + Save button (in case we were in Edit mode previously).
        $m.find('.modal-title').text('Add New Keyword');
        $m.find('.__kw_update_btn').remove();
        $('#saveNewKeywordBtn').show();
        setTimeout(function(){ var f = document.getElementById('newKeywordTransliteration'); if (f) f.focus(); }, 150);
    }
    function hideKwModal() {
        var $m = $('#addNewKeywordModal');
        $m.removeClass('show').css('display','none').attr('aria-hidden','true').removeAttr('aria-modal');
        $('.__kw_backdrop, .modal-backdrop').remove();
        if (!$('.modal.show').length) $('body').removeClass('modal-open');
        // Reset title + button state for next open.
        $m.find('.modal-title').text('Add New Keyword');
        $m.find('.__kw_update_btn').remove();
        $('#saveNewKeywordBtn').show();
    }
    // Wire close buttons (Cancel + ×).
    $('#addNewKeywordModal').on('click', '[data-dismiss="modal"], .close', function (e) {
        e.preventDefault(); hideKwModal();
    });

    /**
     * targetSelectId — which <select> (with `[name="related_keywords[]"]`) the new
     * keyword should be appended to and auto-selected in.
     */
    function bindAddKeyword(btnId, targetSelectId) {
        var btn = document.getElementById(btnId);
        if (!btn) return;
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            kwClearModal();
            showKwModal();
        });
    }

    // Single Save handler — works whichever Add New button opened the modal.
    // We look at the currently-active tab to decide which select to update.
    $(document).on('click', '#saveNewKeywordBtn', async function () {
        var fields = kwReadModal();
        if (!fields.word_transliteration) { alert('Transliteration is required!'); return; }
        var $btn = $(this).prop('disabled', true);
        try {
            var body = new URLSearchParams();
            Object.keys(fields).forEach(function (k) { body.append(k, fields[k]); });
            var res = await fetch(BASE + 'SongController/ajax_create_keyword', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: body.toString()
            });
            var data = await res.json();
            if (data && data.success) {
                // Add the option to BOTH film and episode select if they exist
                // (saves the user adding the same keyword twice across tabs).
                ['#related_keywords', '#related_keywords_ep'].forEach(function (sel) {
                    var $sel = $(sel);
                    if (!$sel.length) return;
                    var found = $sel.find('option[value="' + String(data.id).replace(/(["\\])/g,'\\$1') + '"]');
                    if (found.length) {
                        found.text(data.word_transliteration || fields.word_transliteration);
                    } else {
                        $sel.append(new Option(data.word_transliteration || fields.word_transliteration, data.id));
                    }
                });
                // Auto-select in the currently-visible tab's select only.
                var activeSel = $('#filmTab.tab-content.active').length || $('#filmTab').is(':visible') ? '#related_keywords' : '#related_keywords_ep';
                // Fallback: pick whichever tab is more obviously visible.
                if (!$(activeSel).is(':visible')) {
                    activeSel = $('#related_keywords').is(':visible') ? '#related_keywords' : '#related_keywords_ep';
                }
                if (window.__adminRefreshSelect) window.__adminRefreshSelect(activeSel, String(data.id));
                else $(activeSel).trigger('change');
                hideKwModal();
                if (window.Swal) Swal.fire({icon:'success', title:'Keyword saved!', timer:1200, showConfirmButton:false});
            } else {
                alert('Failed: ' + (data && data.message ? data.message : 'Unknown error'));
            }
        } catch (e) {
            alert('Error: ' + e.message);
        } finally {
            $btn.prop('disabled', false);
        }
    });

    /**
     * bindEditKeyword: when Edit clicked, fetch full word row from DB and open
     * the same modal pre-filled, swapping the Save button for Update.
     */
    function bindEditKeyword(btnId, targetSelectId) {
        var btn = document.getElementById(btnId);
        if (!btn) return;
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var $sel = $(targetSelectId);
            var vals = $sel.val() || [];
            if (!Array.isArray(vals)) vals = [vals];
            vals = vals.filter(function (v) { return v; });
            if (vals.length !== 1) {
                if (window.Swal) Swal.fire({icon:'info', title:'Pick one', text:'Please select exactly one keyword to edit.'});
                else alert('Please select exactly one keyword to edit.');
                return;
            }
            var id = String(vals[0]);
            kwClearModal();
            showKwModal();
            // Title + button swap for edit mode.
            $('#addNewKeywordModal .modal-title').text('Edit Keyword');
            $('#saveNewKeywordBtn').hide();
            var $updateBtn = $('<button type="button" class="btn btn-primary __kw_update_btn">Update</button>');
            $('#addNewKeywordModal .modal-footer').append($updateBtn);

            // Prefill from DB.
            $.post(BASE + 'song/ajax_get_keyword', { id: id }, function (resp) {
                if (!resp || (resp.success !== true && resp.status !== 'success')) return;
                $('#newKeywordOriginal').val(resp.word_original || '');
                $('#newKeywordTranslation').val(resp.word_translation || '');
                $('#newKeywordTransliteration').val(resp.word_transliteration || '');
                $('#newKeywordMeaning').val(resp.glossary_meaning || '');
            }, 'json');

            $updateBtn.on('click', async function () {
                var fields = kwReadModal();
                if (!fields.word_transliteration) { alert('Transliteration is required!'); return; }
                $updateBtn.prop('disabled', true).text('Updating...');
                try {
                    var body = new URLSearchParams();
                    body.append('id', id);
                    Object.keys(fields).forEach(function (k) { body.append(k, fields[k]); });
                    var res = await fetch(BASE + 'song/ajax_update_keyword', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: body.toString()
                    });
                    var data = await res.json();
                    if (data && (data.success === true || data.status === 'success')) {
                        // Refresh option label in BOTH selects (same id shared).
                        ['#related_keywords', '#related_keywords_ep'].forEach(function (sel) {
                            var $opt = $(sel).find('option[value="' + id.replace(/(["\\])/g,'\\$1') + '"]');
                            if ($opt.length) $opt.text(data.word_transliteration || fields.word_transliteration);
                        });
                        if (window.__adminRefreshSelect) window.__adminRefreshSelect(targetSelectId, id);
                        hideKwModal();
                        if (window.Swal) Swal.fire({icon:'success', title:'Updated', timer:1100, showConfirmButton:false});
                    } else {
                        alert('Failed: ' + (data && data.message ? data.message : 'Update failed'));
                    }
                } catch (e) {
                    alert('Error: ' + e.message);
                } finally {
                    $updateBtn.prop('disabled', false).text('Update');
                }
            });
        });
    }

    bindAddKeyword('addNewKeywordBtn',   '#related_keywords');
    bindAddKeyword('addNewKeywordBtnEp', '#related_keywords_ep');
    bindEditKeyword('editKeywordBtn',    '#related_keywords');
    bindEditKeyword('editKeywordBtnEp',  '#related_keywords_ep');

    // ----- Delete buttons (helper in footer.php loads after; defer via $(function)). -----
    $(function () {
        if (!window.__bindAdminDelete) return;
        __bindAdminDelete('deleteDirectorBtn',  { selectId: '#directors',            entity: 'person', label: 'Director' });
        __bindAdminDelete('deleteKeywordBtn',   { selectId: '#related_keywords',     entity: 'word',   label: 'Keyword' });
        __bindAdminDelete('deleteKeywordBtnEp', { selectId: '#related_keywords_ep',  entity: 'word',   label: 'Keyword' });
    });
})();
</script>

<?php include('inc/footer.php'); ?>
