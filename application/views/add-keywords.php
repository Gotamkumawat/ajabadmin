<?php
include('inc/header.php');
include('inc/sidebar.php');
?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
.form-group { margin-bottom: 1rem; }
.form-group label { font-weight: 600; margin-bottom: 0.5rem; display: block; }
.form-control { font-size: 14px; line-height: 1.5; color: #495057; background-color: #fff; border: 1px solid #ced4da; border-radius: 4px; padding: 6px 12px; width: 100%; transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; }
.form-control:focus { border-color: #80bdff; outline: 0; box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25); }
textarea.form-control { resize: vertical; }
.btn-primary { background-color: #007bff; border-color: #007bff; padding: 8px 20px; font-size: 16px; border-radius: 4px; cursor: pointer; }
.btn-primary:hover { background-color: #0056b3; border-color: #004085; }
.btn-secondary { background-color: #6c757d; color: #fff; padding: 3px 8px; font-size: 13px; border-radius: 4px; border: none; cursor: pointer; }
input[type="checkbox"] { width: auto; height: auto; margin-right: 10px; }
.conditional-section { display: none; padding-left: 20px; margin-top: 10px; border-left: 3px solid #007bff; }
.conditional-section.active { display: block; }
.save-btn-container { display: flex; justify-content: flex-end; margin-top: 20px; margin-bottom: 20px; }
.select2-container { width: 100% !important; }
.select2-container--default .select2-selection--multiple { min-height: 38px; border: 1px solid #ccc; border-radius: 4px; padding: 4px 6px; }
.select2-container--default .select2-selection--multiple .select2-selection__choice { background-color: #007bff; border: none; color: #fff; padding: 2px 6px; margin: 2px; border-radius: 4px; font-size: 12px; }
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove { color: #fff; margin-right: 4px; }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Add Keyword</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
                        <li class="breadcrumb-item active">Add Keyword</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <?php
    $word_original = isset($keyword['word_original']) ? $keyword['word_original'] : '';
    $word_transliteration = isset($keyword['word_transliteration']) ? $keyword['word_transliteration'] : '';
    $word_translation = isset($keyword['word_translation']) ? $keyword['word_translation'] : '';
    $is_keyword = !empty($keyword['is_keyword']);
    $is_glossary = !empty($keyword['is_glossary']);
    $diacritic_text = isset($keyword['diacritic_text']) ? $keyword['diacritic_text'] : '';
    $glossary_meaning = isset($keyword['glossary_meaning']) ? $keyword['glossary_meaning'] : '';
    $meta_title = isset($keyword['meta_title']) ? $keyword['meta_title'] : '';
    $meta_keywords = isset($keyword['meta_keywords']) ? $keyword['meta_keywords'] : '';
    $meta_description = isset($keyword['meta_description']) ? $keyword['meta_description'] : '';
    $is_published = isset($keyword['is_published']) ? $keyword['is_published'] : '';
    $related_songs = isset($keyword['related_songs']) ? explode(',', $keyword['related_songs']) : [];
    $related_poems = isset($keyword['related_poems']) ? explode(',', $keyword['related_poems']) : [];
    $related_reflections = isset($keyword['related_reflections']) ? explode(',', $keyword['related_reflections']) : [];
    $related_films = isset($keyword['related_films']) ? explode(',', $keyword['related_films']) : [];
    $related_film_episodes = isset($keyword['related_film_episodes']) ? explode(',', $keyword['related_film_episodes']) : [];

    // Preload related content options to reuse across sections
    $songs_options = $this->db->query("SELECT id, Songtitle_transliteration FROM songs")->result();
    $poems_options = $this->db->query("SELECT id, COALESCE(NULLIF(couplet_transliteration, ''), original_title) AS poem_label FROM couplet")->result();
    $refs_options = $this->db->query("SELECT id, title FROM reflection")->result();
    $films_options = $this->db->query("SELECT id, COALESCE(NULLIF(english_transliteration, ''), NULLIF(english_translation, ''), original_title) AS film_label FROM film")->result();
    $eps_options = $this->db->query("SELECT id, COALESCE(NULLIF(english_transliteration, ''), NULLIF(english_translation, ''), original_title) AS episode_label FROM film_episode")->result();
    ?>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header" style="padding:4px 8px; margin:0;">
                    <a href="javascript:void(0);" class="btn btn-secondary" onclick="window.history.back();">
                        <i class="fas fa-arrow-left" style="margin-right:4px;"></i> Back
                    </a>
                </div>

                <div class="card-body">
                    <?php if ($this->session->flashdata('success')): ?>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            setTimeout(function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Upload Successful',
                                    text: '<?php echo addslashes($this->session->flashdata('success')); ?>',
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
                            }, 200);
                        </script>
                    <?php endif; ?>
                    <form name="keywordForm" id="keywordForm" method="post" action="<?= isset($form_action) ? $form_action : base_url('keywords/save') ?>">
                        <?php if (!empty($keyword['id'])): ?>
                            <input type="hidden" name="id" value="<?= $keyword['id'] ?>">
                        <?php endif; ?>

                        <label>Keywords</label>
                        <div style="padding-left:20px;">
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Original <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <input type="text" name="word_original" id="word_original" class="form-control" value="<?= htmlspecialchars($word_original) ?>" placeholder="Enter Word Original" required>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Translation <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <input type="text" name="word_translation" id="word_translation" class="form-control" value="<?= htmlspecialchars($word_translation) ?>" placeholder="Enter Word Translation" required>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Transliteration <span style="color:red">*</span></label>
                                <div class="col-md-4">
                                    <input type="text" name="word_transliteration" id="word_transliteration" class="form-control" value="<?= htmlspecialchars($word_transliteration) ?>" placeholder="Enter Word Transliteration" required>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label style="display:flex; align-items:center;">
                                        <input type="checkbox" name="is_keyword" id="is_keyword" value="1" <?= $is_keyword ? 'checked' : '' ?>>
                                        Is this a Keyword?
                                    </label>
                                </div>
                            </div>
                        </div> -->


                        <!-- Related Content (only once) -->
                        <label>Related Content</label>
                        <div style="padding-left:20px;">
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Songs</label>
                                <div class="col-md-4 d-flex align-items-center gap-2">
                                    <select class="form-control select2" multiple="multiple" name="related_songs[]" id="related_songs">
                                        <?php foreach ($songs_options as $song) {
                                            $selected = in_array($song->id, $related_songs) ? 'selected' : '';
                                            echo '<option value="'.$song->id.'" '.$selected.'>'.htmlspecialchars($song->Songtitle_transliteration).'</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Poems</label>
                                <div class="col-md-4 d-flex align-items-center gap-2">
                                    <select class="form-control select2" multiple="multiple" name="related_poems[]" id="related_poems">
                                        <?php foreach ($poems_options as $poem) {
                                            $selected = in_array($poem->id, $related_poems) ? 'selected' : '';
                                            echo '<option value="'.$poem->id.'" '.$selected.'>'.htmlspecialchars($poem->poem_label).'</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Reflections</label>
                                <div class="col-md-4 d-flex align-items-center gap-2">
                                    <select class="form-control select2" multiple="multiple" name="related_reflections[]" id="related_reflections">
                                        <?php foreach ($refs_options as $ref) {
                                            $selected = in_array($ref->id, $related_reflections) ? 'selected' : '';
                                            echo '<option value="'.$ref->id.'" '.$selected.'>'.htmlspecialchars($ref->title).'</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Films</label>
                                <div class="col-md-4 d-flex align-items-center gap-2">
                                    <select class="form-control select2" multiple="multiple" name="related_films[]" id="related_films">
                                        <?php foreach ($films_options as $film) {
                                            $selected = in_array($film->id, $related_films) ? 'selected' : '';
                                            $displayTitle = !empty($film->film_label) ? $film->film_label : ('Film #' . $film->id);
                                            echo '<option value="'.$film->id.'" '.$selected.'>'.htmlspecialchars($displayTitle).'</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Film Episodes</label>
                                <div class="col-md-4 d-flex align-items-center gap-2">
                                    <select class="form-control select2" multiple="multiple" name="related_film_episodes[]" id="related_film_episodes">
                                        <?php foreach ($eps_options as $ep) {
                                            $selected = in_array($ep->id, $related_film_episodes) ? 'selected' : '';
                                            $displayTitle = !empty($ep->episode_label) ? $ep->episode_label : ('Episode #' . $ep->id);
                                            echo '<option value="'.$ep->id.'" '.$selected.'>'.htmlspecialchars($displayTitle).'</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <label style="display:flex; align-items:center; margin-bottom:0;">
                                    <input type="checkbox" name="is_glossary" id="is_glossary" value="1" <?= $is_glossary ? 'checked' : '' ?>>
                                    Is this a Glossary Word?
                                </label>
                            </div>
                        </div>

                        <div id="glossarySection" class="conditional-section <?= $is_glossary ? 'active' : '' ?>">
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Diacritic Text</label>
                                <div class="col-md-4">
                                    <input type="text" name="diacritic_text" id="diacritic_text" class="form-control" value="<?= htmlspecialchars($diacritic_text) ?>" placeholder="Enter Diacritic Text">
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Glossary Meaning</label>
                                <div class="col-md-4">
                                    <textarea name="glossary_meaning" id="glossary_meaning" class="form-control" rows="4" placeholder="Enter Glossary Meaning"><?= htmlspecialchars($glossary_meaning) ?></textarea>
                                </div>
                            </div>
                        </div>

                        <label><strong>Meta Data</strong></label>
                        <div style="padding-left:20px;">
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Meta Title</label>
                                <div class="col-md-4">
                                    <input type="text" name="meta_title" id="meta_title" class="form-control" value="<?= htmlspecialchars($meta_title) ?>" placeholder="Enter Meta Title">
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Meta Keywords</label>
                                <div class="col-md-4">
                                    <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" value="<?= htmlspecialchars($meta_keywords) ?>" placeholder="Enter Meta Keywords">
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Meta Description</label>
                                <div class="col-md-4">
                                    <textarea class="form-control" name="meta_description" id="meta_description" rows="3" placeholder="Enter Meta Description"><?= htmlspecialchars($meta_description) ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Publish Status <span style="color:red">*</span></label>
                            <div class="col-md-4">
                                <select class="form-control" name="is_published" id="is_published" required>
                                    <option value="">Select</option>
                                    <option value="1" <?= $is_published === '1' ? 'selected' : '' ?>>Yes</option>
                                    <option value="0" <?= $is_published === '0' ? 'selected' : '' ?>>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="save-btn-container">
                            <button type="submit" class="btn btn-primary">Save Keyword</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function() {
    $('.select2').select2({ placeholder: 'Select options', allowClear: true });

    // Add New logic for Related Content fields
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

    addNewHandler('#addSongBtn', '#related_songs', '/FilmController/ajax_add_song', 'Song', 'umbrellaTitle', 'id');
    addNewHandler('#addPoemBtn', '#related_poems', '/FilmController/ajax_add_poem', 'Poem', 'original_title', 'id');
    addNewHandler('#addReflectionBtn', '#related_reflections', '/FilmController/ajax_add_reflection', 'Reflection', 'title', 'id');
    addNewHandler('#addFilmBtn', '#related_films', '/FilmController/ajax_add_film', 'Film', 'main_title', 'id');
    addNewHandler('#addFilmEpisodeBtn', '#related_film_episodes', '/FilmController/ajax_add_film_episode', 'Film Episode', 'film_episode_title', 'id');

    // ...existing code for toggles and validation...
    const toggleKeyword = () => {
        if ($('#is_keyword').is(':checked')) {
            $('#keywordSection').addClass('active');
            $('#word_translation').attr('required', true);
        } else {
            $('#keywordSection').removeClass('active');
            $('#word_translation').attr('required', false);
        }
    };

    const toggleGlossary = () => {
        if ($('#is_glossary').is(':checked')) {
            $('#glossarySection').addClass('active');
        } else {
            $('#glossarySection').removeClass('active');
        }
    };

    $('#is_keyword').on('change', toggleKeyword);
    $('#is_glossary').on('change', toggleGlossary);
    toggleKeyword();
    toggleGlossary();

    $('#keywordForm').on('submit', function(e) {
        const wt = $('#word_transliteration').val().trim();
        const pub = $('#is_published').val();
        if (!wt) { e.preventDefault(); alert('Please enter Word Transliteration'); $('#word_transliteration').focus(); return false; }
        if (!pub) { e.preventDefault(); alert('Please select Publish Status'); $('#is_published').focus(); return false; }
        if ($('#is_keyword').is(':checked')) {
            const wtr = $('#word_translation').val().trim();
            if (!wtr) { e.preventDefault(); alert('Please enter Word Translation (required for Keywords)'); $('#word_translation').focus(); return false; }
        }
        return true;
    });
});
</script>

<?php include('inc/footer.php'); ?>