
<?php
include('inc/header.php');
include('inc/sidebar.php');

// Set related fields from $glossary if present (edit mode)
if (isset($glossary) && $glossary) {
    $related_songs = isset($glossary['related_songs']) ? $glossary['related_songs'] : '';
    $related_poems = isset($glossary['related_poems']) ? $glossary['related_poems'] : '';
    $related_reflections = isset($glossary['related_reflections']) ? $glossary['related_reflections'] : '';
    $related_films = isset($glossary['related_films']) ? $glossary['related_films'] : '';
    $related_film_episodes = isset($glossary['related_film_episodes']) ? $glossary['related_film_episodes'] : '';
}

// Ensure related fields are arrays for edit mode
$related_songs = isset($related_songs) ? (is_array($related_songs) ? $related_songs : (is_string($related_songs) ? array_filter(explode(',', $related_songs)) : [])) : [];
$related_poems = isset($related_poems) ? (is_array($related_poems) ? $related_poems : (is_string($related_poems) ? array_filter(explode(',', $related_poems)) : [])) : [];
$related_reflections = isset($related_reflections) ? (is_array($related_reflections) ? $related_reflections : (is_string($related_reflections) ? array_filter(explode(',', $related_reflections)) : [])) : [];
$related_films = isset($related_films) ? (is_array($related_films) ? $related_films : (is_string($related_films) ? array_filter(explode(',', $related_films)) : [])) : [];
$related_film_episodes = isset($related_film_episodes) ? (is_array($related_film_episodes) ? $related_film_episodes : (is_string($related_film_episodes) ? array_filter(explode(',', $related_film_episodes)) : [])) : [];
?>

<head>
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

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        padding: 8px 20px;
        font-size: 16px;
        border-radius: 4px;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .save-btn-container {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 30px;
    }

    .form-control-file {
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    .card-header {
        padding: 4px 8px;
        margin: 0;
    }

    .back-btn {
        padding: 3px 8px;
        font-size: 13px;
        border-radius: 4px;
    }
</style>
<?php
$songs_options = $this->db->query("SELECT id, Songtitle_transliteration FROM songs")->result();
    $poems_options = $this->db->query("SELECT id, COALESCE(NULLIF(couplet_transliteration, ''), original_title) AS poem_label FROM couplet")->result();
    $refs_options = $this->db->query("SELECT id, title FROM reflection")->result();
    $films_options = $this->db->query("SELECT id, COALESCE(NULLIF(english_transliteration, ''), NULLIF(english_translation, ''), original_title) AS film_label FROM film")->result();
    $eps_options = $this->db->query("SELECT id, COALESCE(NULLIF(english_transliteration, ''), NULLIF(english_translation, ''), original_title) AS episode_label FROM film_episode")->result();
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Glossary Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('add_new'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Add/Edit Glossary Details</li>
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
                <div class="card-header">
                    <div>
                        <a href="javascript:void(0);" 
                           onclick="window.history.back();" 
                           class="btn btn-secondary back-btn">
                            <i class="fas fa-arrow-left" style="font-size: 13px; margin-right: 4px;"></i> Back
                        </a>
                    </div>
                </div>
                <!-- Card Body -->
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
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                    <?php endif; ?>

                    <form method="POST" action="<?= base_url('glossary/save') ?>" enctype="multipart/form-data" id="glossaryForm">
                        <!-- Hidden ID field for edit -->
                        <?php if (isset($glossary) && $glossary): ?>
                            <input type="hidden" name="id" value="<?php echo $glossary['id']; ?>">
                            <input type="hidden" name="existing_image" value="<?php echo $glossary['poetic_images']; ?>">
                        <?php endif; ?>


                        <!-- 1. Glossary Term Section -->
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Glossary Term <span style="color:red">*</span></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="glossary_term" name="glossary_term" 
                                       value="<?php echo isset($glossary) && $glossary ? $glossary['glossary_term'] : ''; ?>" 
                                       placeholder="Enter the glossary term/word" required>
                            </div>
                        </div>

                        <!-- Diacritic Text Box -->
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Diacritic</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="diacritic" name="diacritic" 
                                       value="<?php echo isset($glossary) && $glossary ? $glossary['diacritic'] : ''; ?>" 
                                       placeholder="Enter diacritic (if any)">
                            </div>
                        </div>

                        <!-- 2. Glossary Meaning Section -->
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Glossary Meaning <span style="color:red">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="glossary_meaning" name="glossary_meaning" rows="4" 
                                          placeholder="Enter detailed meaning/definition" required><?php echo isset($glossary) && $glossary ? $glossary['glossary_meaning'] : ''; ?></textarea>
                            </div>
                        </div>


                        <!-- 4. Related Songs & Poems -->
                        <label>Related Content</label>
                        <div style="padding-left:20px;">
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Songs</label>
                                <div class="col-md-4 d-flex align-items-center gap-2">
                                    <select class="form-control select2" multiple="multiple" name="related_songs[]" id="related_songs">
                                        <?php 
                                        $related_songs = isset($related_songs) && is_array($related_songs) ? $related_songs : [];
                                        foreach ($songs_options as $song) {
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
                                        <?php 
                                        $related_poems = isset($related_poems) && is_array($related_poems) ? $related_poems : [];
                                        foreach ($poems_options as $poem) {
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
                                        <?php 
                                        $related_reflections = isset($related_reflections) && is_array($related_reflections) ? $related_reflections : [];
                                        foreach ($refs_options as $ref) {
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
                                        <?php 
                                        $related_films = isset($related_films) && is_array($related_films) ? $related_films : [];
                                        foreach ($films_options as $film) {
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
                                        <?php 
                                        $related_film_episodes = isset($related_film_episodes) && is_array($related_film_episodes) ? $related_film_episodes : [];
                                        foreach ($eps_options as $ep) {
                                            $selected = in_array($ep->id, $related_film_episodes) ? 'selected' : '';
                                            $displayTitle = !empty($ep->episode_label) ? $ep->episode_label : ('Episode #' . $ep->id);
                                            echo '<option value="'.$ep->id.'" '.$selected.'>'.htmlspecialchars($displayTitle).'</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>


                        
                        <!-- 7. SEO Information -->
                        <label>Meta Data</label>
                        <div style="padding-left:20px;">
                            <div class="form-group row align-items-center">
                                <label for="meta_title" class="col-md-2 col-form-label">⊙ Meta Title</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                           value="<?php echo isset($glossary) && $glossary ? $glossary['meta_title'] : ''; ?>"
                                           placeholder="Enter SEO meta title">
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label for="meta_keywords" class="col-md-2 col-form-label">⊙ Meta Keywords</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                                           value="<?php echo isset($glossary) && $glossary ? $glossary['meta_keywords'] : ''; ?>"
                                           placeholder="Enter SEO meta keywords">
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label for="meta_description" class="col-md-2 col-form-label">⊙ Meta Description</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="3"
                                              placeholder="Enter SEO meta description"><?php echo isset($glossary) && $glossary ? $glossary['meta_description'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- 7. Publish Status -->
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Publish Status</label>
                            <div class="col-md-4" style="padding-left:20px;">
                                <label style="margin-bottom:0;">
                                    <input type="checkbox" name="is_published" value="1" 
                                           <?php echo (isset($glossary) && $glossary && $glossary['is_published'] == 1) ? 'checked' : ''; ?> >
                                    <strong>Publish this Glossary</strong> (Make it visible on frontend)
                                </label>
                            </div>
                        </div>

                        <div class="save-btn-container">
                            <button type="submit" class="btn btn-primary">
                                <?php echo isset($glossary) && $glossary ? 'Update Glossary' : 'Save Glossary'; ?>
                            </button>
                            <a href="<?= base_url('glossary-lists') ?>" class="btn btn-secondary">Cancel</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include('inc/footer.php'); ?>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery + Select2 already loaded by header/footer; only page-specific extras here -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        width: '100%',
        placeholder: "Select an option",
        allowClear: true
    });

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

    // ...existing code for CKEditor and validation...
    CKEDITOR.config.versionCheck = false;

    setTimeout(function() {
        CKEDITOR.replace('glossary_meaning', {
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

        CKEDITOR.replace('poetic_image_description', {
            height: 150,
            extraPlugins: 'colorbutton,font',
            toolbar: [
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', '-', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
            ]
        });

        CKEDITOR.replace('etymology', {
            height: 150,
            extraPlugins: 'colorbutton,font',
            toolbar: [
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', '-', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
            ]
        });

        CKEDITOR.replace('cultural_context', {
            height: 150,
            extraPlugins: 'colorbutton,font,justify',
            toolbar: [
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', '-', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight'] },
            ]
        });

        CKEDITOR.replace('examples', {
            height: 200,
            extraPlugins: 'colorbutton,font,justify',
            toolbar: [
                { name: 'document', items: ['Source'] },
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight'] },
                { name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar'] },
                { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
            ]
        });

        CKEDITOR.replace('meta_description', {
            height: 100,
            extraPlugins: 'colorbutton',
            toolbar: [
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
            ]
        });
    }, 500);

    // Form validation
    $('#glossaryForm').on('submit', function(e) {
        e.preventDefault();

        // Update CKEditor data before submission
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }

        const fields = [
            { id: 'glossary_term', name: 'Glossary Term', type: 'input', required: true },
            { id: 'glossary_meaning', name: 'Glossary Meaning', type: 'textarea', required: true }
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

