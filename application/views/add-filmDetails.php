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
                    $keyword_rows = $this->db->query("SELECT id, word_transliteration FROM keywords ORDER BY id DESC")->result();
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

                    $selected_episode_keywords = isset($filmEpisode) ? $readSelectedValues($filmEpisode->related_keywords ?? '') : [];
                    $selected_episode_songs = isset($filmEpisode) ? $readSelectedValues($filmEpisode->related_songs ?? '') : [];
                    $selected_episode_poems = isset($filmEpisode) ? $readSelectedValues($filmEpisode->related_poems ?? '') : [];
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
                        <form name="filmForm" id="filmForm" method="post" action="<?php echo base_url('FilmController/save'); ?>" enctype="multipart/form-data">
                            
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
                            if (isset($film) && isset($film->film_language_links) && trim((string)$film->film_language_links) !== '') {
                                $decodedLangRows = json_decode((string)$film->film_language_links, true);
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
                                <label class="col-md-2 col-form-label">Language + YouTube Link</label>
                                <div class="col-md-7">
                                    <div id="filmLanguageRows">
                                        <?php foreach ($filmLanguageRows as $idx => $langRow): ?>
                                        <div class="film-language-row d-flex align-items-center mb-2" style="gap:8px;">
                                            <select name="film_language[]" class="form-control" style="max-width:220px;">
                                                <option value="">Select Language</option>
                                                <?php
                                                $selectedLanguage = trim((string)$langRow['language']);
                                                foreach ($filmLanguageOptions as $optionLang) {
                                                    $isSel = ($selectedLanguage === $optionLang) ? 'selected' : '';
                                                    echo '<option value="' . htmlspecialchars($optionLang) . '" ' . $isSel . '>' . htmlspecialchars($optionLang) . '</option>';
                                                }
                                                if ($selectedLanguage !== '' && !in_array($selectedLanguage, $filmLanguageOptions, true)) {
                                                    echo '<option value="' . htmlspecialchars($selectedLanguage) . '" selected>' . htmlspecialchars($selectedLanguage) . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <input type="text" name="film_language_youtube_link[]" class="form-control film-language-link-input" placeholder="YouTube Link" value="<?php echo htmlspecialchars((string)$langRow['youtube_link']); ?>" <?php echo ($selectedLanguage === '') ? 'style="display:none;" disabled' : ''; ?>>
                                            <button type="button" class="btn btn-danger btn-sm film-language-remove" <?php echo ($idx === 0) ? 'style="display:none;"' : ''; ?>>Remove</button>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="d-flex" style="gap:8px;">
                                        <button type="button" class="btn btn-secondary btn-sm" id="addFilmLanguageRowBtn">Add Language</button>
                                        <button type="button" class="btn btn-info btn-sm" id="addFilmLanguageOptionBtn">Add New Language Option</button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label for="series_title" class="col-md-2 col-form-label">Series Title</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="series_title" name="series_title" value="<?php echo isset($film) ? htmlspecialchars($film->series_title) : ''; ?>" placeholder="Enter Series Title">
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label for="series_description" class="col-md-2 col-form-label">Series Description</label>
                                <div class="col-md-4">
                                    <textarea class="form-control" id="series_description" name="series_description" placeholder="Enter Series Description"><?php echo isset($film) ? htmlspecialchars($film->series_description) : ''; ?></textarea>
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Director(s) <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <select class="form-control select2" name="directors[]" id="directors" multiple required>
                                        <option value="">Select Director</option>
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
                                        $director_rows = $this->db->query("SELECT id, first_name, middle_name, last_name FROM person ORDER BY id DESC")->result();
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
                                    <textarea name="about" id="about" class="form-control" rows="4" placeholder="Enter About"><?php echo isset($film) ? htmlspecialchars($film->about) : ''; ?></textarea>
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
                                        <button type="button" class="btn btn-secondary btn-sm ml-2" id="addKeywordBtn"><i class="fas fa-plus"></i> Add New</button>
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

                            <div class="save-btn-container">
                                <button type="button" class="btn btn-primary" onclick="switchTab('episode-content')">
                                    Next: Film Episode <i class="fas fa-arrow-right"></i>
                                </button>
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

                            <!-- YouTube Link field -->
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">YouTube Link</label>
                                <div class="col-md-4">
                                    <input type="text" name="youtube_link" id="youtube_link" class="form-control" placeholder="Enter YouTube Link" value="<?php echo isset($filmEpisode) ? htmlspecialchars($filmEpisode->youtube_link) : ''; ?>">
                                </div>
                            </div>


                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Main Film Title <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <select name="main_title" id="main_title_ep" class="form-control" required>
                                        <option value="">Select Main Film</option>
                                        <?php 
                                        $mainVal = isset($filmEpisode) ? $filmEpisode->main_title : '';
                                        $films = $this->db->query("SELECT id, main_title FROM film_details ORDER BY main_title ASC")->result();
                                        foreach ($films as $film) {
                                            $sel = ($mainVal == $film->id) ? 'selected' : '';
                                            echo '<option value="'.htmlspecialchars($film->id).'" '.$sel.'>'.htmlspecialchars($film->main_title).'</option>';
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
                                        <button type="button" class="btn btn-secondary btn-sm ml-2" id="addKeywordBtnEp"><i class="fas fa-plus"></i> Add New</button>
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
    addNewHandler('#addKeywordBtn', '#related_keywords', '/FilmController/ajax_add_keyword', 'Keyword', 'word_transliteration', 'id');
    addNewHandler('#addSongBtn', '#related_songs', '/FilmController/ajax_add_song', 'Song', 'umbrellaTitle', 'id');
    addNewHandler('#addPoemBtn', '#related_poems', '/FilmController/ajax_add_poem', 'Poem', 'original_title', 'id');
    addNewHandler('#addReflectionBtn', '#related_reflections', '/FilmController/ajax_add_reflection', 'Reflection', 'title', 'id');
    addNewHandler('#addPersonBtn', '#related_people', '/FilmController/ajax_add_person', 'Person', 'full_name', 'id');

    // Episode tab
    addNewHandler('#addKeywordBtnEp', '#related_keywords_ep', '/FilmController/ajax_add_keyword', 'Keyword', 'word_transliteration', 'id');
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
    var addFilmLanguageRowBtn = document.getElementById('addFilmLanguageRowBtn');
    var addFilmLanguageOptionBtn = document.getElementById('addFilmLanguageOptionBtn');
    function escapeHtml(str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
    function buildLanguageOptionsHtml(selectedValue) {
        var html = '<option value="">Select Language</option>';
        var opts = Array.isArray(window.filmLanguageOptions) ? window.filmLanguageOptions : [];
        opts.forEach(function(opt) {
            var val = String(opt || '').trim();
            if (!val) return;
            var selected = (String(selectedValue || '').trim() === val) ? ' selected' : '';
            html += '<option value="' + escapeHtml(val) + '"' + selected + '>' + escapeHtml(val) + '</option>';
        });
        var selectedTrim = String(selectedValue || '').trim();
        if (selectedTrim && opts.indexOf(selectedTrim) === -1) {
            html += '<option value="' + escapeHtml(selectedTrim) + '" selected>' + escapeHtml(selectedTrim) + '</option>';
        }
        return html;
    }
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
    function bindFilmLanguageSelectBehavior() {
        if (!filmLanguageRows) return;
        var rows = filmLanguageRows.querySelectorAll('.film-language-row');
        rows.forEach(function(row) {
            var select = row.querySelector('select[name="film_language[]"]');
            var linkInput = row.querySelector('.film-language-link-input');
            if (!select || !linkInput) return;
            var toggleLinkInput = function() {
                if ((select.value || '').trim() !== '') {
                    linkInput.style.display = '';
                    linkInput.disabled = false;
                } else {
                    linkInput.value = '';
                    linkInput.style.display = 'none';
                    linkInput.disabled = true;
                }
            };
            select.onchange = toggleLinkInput;
            toggleLinkInput();
        });
    }
    if (filmLanguageRows && addFilmLanguageRowBtn) {
        addFilmLanguageRowBtn.addEventListener('click', function() {
            var row = document.createElement('div');
            row.className = 'film-language-row d-flex align-items-center mb-2';
            row.style.gap = '8px';
            row.innerHTML = '<select name="film_language[]" class="form-control" style="max-width:220px;">' +
                buildLanguageOptionsHtml('') +
                '</select>' +
                '<input type="text" name="film_language_youtube_link[]" class="form-control film-language-link-input" placeholder="YouTube Link" style="display:none;" disabled>' +
                '<button type="button" class="btn btn-danger btn-sm film-language-remove">Remove</button>';
            filmLanguageRows.appendChild(row);
            bindFilmLanguageRemoveButtons();
            bindFilmLanguageSelectBehavior();
        });
        if (addFilmLanguageOptionBtn) {
            addFilmLanguageOptionBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Add New Language',
                    input: 'text',
                    inputLabel: 'Language Name',
                    inputPlaceholder: 'Enter language name',
                    showCancelButton: true,
                    confirmButtonText: 'Add',
                    cancelButtonText: 'Cancel',
                    preConfirm: function(value) {
                        var v = (value || '').trim();
                        if (!v) {
                            Swal.showValidationMessage('Please enter language name');
                        }
                        return v;
                    }
                }).then(function(result) {
                    if (!result.isConfirmed || !result.value) return;
                    var languageName = String(result.value).trim();
                    fetch(createFilmLanguageUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                        body: 'language_name=' + encodeURIComponent(languageName)
                    })
                    .then(function(res) { return res.json(); })
                    .then(function(response) {
                        if (!response || response.status !== 'success') {
                            Swal.fire({ icon: 'error', title: 'Error', text: (response && response.message) ? response.message : 'Failed to add language' });
                            return;
                        }
                        var finalName = (response.language_name || languageName || '').trim();
                        if (!finalName) return;
                        if (!Array.isArray(window.filmLanguageOptions)) {
                            window.filmLanguageOptions = [];
                        }
                        if (window.filmLanguageOptions.indexOf(finalName) === -1) {
                            window.filmLanguageOptions.push(finalName);
                        }
                        var selects = filmLanguageRows.querySelectorAll('select[name="film_language[]"]');
                        selects.forEach(function(sel) {
                            if (!Array.from(sel.options).some(function(o){ return o.value === finalName; })) {
                                var opt = document.createElement('option');
                                opt.value = finalName;
                                opt.text = finalName;
                                sel.appendChild(opt);
                            }
                        });
                        var lastSelect = selects.length ? selects[selects.length - 1] : null;
                        if (lastSelect) {
                            lastSelect.value = finalName;
                            if (typeof lastSelect.onchange === 'function') {
                                lastSelect.onchange();
                            }
                        }
                        Swal.fire({ icon: 'success', title: 'Success', text: response.message || 'Language added successfully' });
                    })
                    .catch(function() {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to add language' });
                    });
                });
            });
        }
        bindFilmLanguageRemoveButtons();
        bindFilmLanguageSelectBehavior();
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

<?php include('inc/footer.php'); ?>
