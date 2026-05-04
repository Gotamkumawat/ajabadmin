<?php 
include('inc/header.php');
include('inc/sidebar.php');

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

$selected_related_keywords = isset($filmEpisode->related_keywords) ? $readSelectedValues($filmEpisode->related_keywords) : [];
$selected_related_songs = isset($filmEpisode->related_songs) ? $readSelectedValues($filmEpisode->related_songs) : [];
$selected_related_poems = isset($filmEpisode->related_poems) ? $readSelectedValues($filmEpisode->related_poems) : [];
$selected_related_reflections = isset($filmEpisode->related_reflections) ? $readSelectedValues($filmEpisode->related_reflections) : [];
$selected_related_people = isset($filmEpisode->related_people) ? $readSelectedValues($filmEpisode->related_people) : [];

$keyword_rows = $this->db->query("SELECT id, word_transliteration FROM keywords ORDER BY id DESC")->result();
$song_rows = $this->db->query("SELECT id, umbrellaTitle FROM songs ORDER BY id DESC")->result();
$poem_rows = $this->db->query("SELECT id, COALESCE(NULLIF(couplet_transliteration, ''), original_title) AS poem_label FROM couplet ORDER BY id DESC")->result();
$reflection_rows = $this->db->query("SELECT id, title FROM reflection ORDER BY id DESC")->result();
$person_rows = $this->db->query("SELECT id, first_name, middle_name, last_name FROM person ORDER BY id DESC")->result();
$film_id_rows = $this->db->query("
    SELECT DISTINCT fe.film_id, f.english_transliteration, f.english_translation, f.original_title
    FROM film_episode fe
    LEFT JOIN film f ON f.id = fe.film_id
    WHERE fe.film_id IS NOT NULL AND fe.film_id != ''
    ORDER BY fe.film_id ASC
")->result();

$thumbRaw = isset($filmEpisode->thumbnail_image_upload) ? trim((string)$filmEpisode->thumbnail_image_upload) : '';
$aboutTextValue = '';
if (isset($filmEpisode) && isset($filmEpisode->about_text)) {
    $aboutTextValue = (string)$filmEpisode->about_text;
    // Some migrated rows are entity-encoded multiple times.
    for ($i = 0; $i < 3; $i++) {
        $decoded = html_entity_decode($aboutTextValue, ENT_QUOTES, 'UTF-8');
        if ($decoded === $aboutTextValue) {
            break;
        }
        $aboutTextValue = $decoded;
    }
}
$thumbSrc = '';
if ($thumbRaw !== '') {
    if (preg_match('#^https?://#i', $thumbRaw)) {
        $thumbSrc = $thumbRaw;
    } else {
        $raw = ltrim($thumbRaw, '/');
        $candidates = [$raw];
        if (stripos($raw, 'uploads/') !== 0 && stripos($raw, 'Uploads/') !== 0 && stripos($raw, 'images/') !== 0) {
            $candidates[] = 'uploads/thumbnails/' . $raw;
            $candidates[] = 'Uploads/' . $raw;
            $candidates[] = 'images/' . $raw;
        }
        $candidates = array_values(array_unique($candidates));
        foreach ($candidates as $candidate) {
            if (file_exists(FCPATH . $candidate)) {
                $thumbSrc = base_url($candidate);
                break;
            }
        }
        if ($thumbSrc === '') {
            $thumbSrc = rtrim(base_url(), '/') . '/' . $raw;
        }
    }
}
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
    .select2-container--default .select2-selection--multiple::-webkit-scrollbar {
        display: none;
    }
    .cke_notification {
        display: none;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Film Episode Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="add_new">Home</a></li>
                        <li class="breadcrumb-item active">Add/Edit Film Episode</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header" style="padding: 4px 8px; margin: 0;">
                    <a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-secondary" style="padding: 3px 8px; font-size: 13px; border-radius: 4px;">
                        <i class="fas fa-arrow-left" style="font-size: 13px; margin-right: 4px;"></i> Back
                    </a>
                </div>
                <div class="card-body">
                        <form name="episodeForm" id="episodeForm" method="post" action="<?php echo base_url('FilmController/save_filmEpisode'); ?>" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo isset($filmEpisode) ? htmlspecialchars($filmEpisode->id) : ''; ?>">

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Film Episode Title <span style="color:red">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="film_episode_title" class="form-control" required value="<?php echo isset($filmEpisode) ? htmlspecialchars($filmEpisode->film_episode_title) : ''; ?>">
                            </div>
                                    </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Main Film Title <span style="color:red">*</span></label>
                            <div class="col-md-4">
                                        <select name="main_title" id="main_title_ep" class="form-control" required>
                                            <option value="">Select Main Film</option>
                                    <?php $mainVal = isset($filmEpisode) ? (string)$filmEpisode->main_title : ''; ?>
                                    <?php foreach ($film_id_rows as $r): ?>
                                        <?php $idVal = isset($r->film_id) ? (string)$r->film_id : ''; if ($idVal === '') continue; ?>
                                            <?php 
                                        $filmLabel = isset($r->english_transliteration) ? trim((string)$r->english_transliteration) : '';
                                        if ($filmLabel === '') { $filmLabel = isset($r->english_translation) ? trim((string)$r->english_translation) : ''; }
                                        if ($filmLabel === '') { $filmLabel = isset($r->original_title) ? trim((string)$r->original_title) : ''; }
                                        if ($filmLabel === '') { $filmLabel = 'Film #' . $idVal; }
                                        ?>
                                        <option value="<?php echo htmlspecialchars($idVal); ?>" <?php echo ($mainVal === $idVal) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($filmLabel); ?>
                                        </option>
                                    <?php endforeach; ?>
                                        </select>
                            </div>
                                    </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Episode Number <span style="color:red">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="episode_no" class="form-control" required value="<?php echo isset($filmEpisode) ? htmlspecialchars($filmEpisode->episode_no) : ''; ?>">
                            </div>
                                            </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Thumbnail Image Upload <span style="color:red">*</span></label>
                            <div class="col-md-4">
                                <?php if (isset($filmEpisode) && $thumbRaw !== ''): ?>
                                    <p class="mb-2 text-muted small">Current file is kept unless you choose a new image.</p>
                                    <input type="hidden" name="old_thumbnail_image" value="<?php echo htmlspecialchars($thumbRaw); ?>">
                                        <?php endif; ?>
                                <input type="file" name="thumbnail_image_upload" id="ep_thumbnail" class="form-control" accept="image/*">
                                <?php if (isset($filmEpisode) && $thumbSrc !== ''): ?>
                                    <div class="mt-2">
                                        <img src="<?php echo htmlspecialchars($thumbSrc); ?>" alt="Current thumbnail" style="max-width:200px;height:auto;border:1px solid #ddd;border-radius:4px;"
                                             onerror="this.style.display='none';">
                                    </div>
                                <?php endif; ?>
                            </div>
                                    </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Thumbnail Excerpt <span style="color:red">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="thumbnail_excerpt" class="form-control" required value="<?php echo isset($filmEpisode) ? htmlspecialchars($filmEpisode->thumbnail_excerpt) : ''; ?>">
                            </div>
                                    </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">About</label>
                            <div class="col-md-9">
                                <textarea name="about_text" id="about_text" class="form-control" rows="4"><?php echo $aboutTextValue; ?></textarea>
                            </div>
                                    </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Duration <span style="color:red">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="duration" class="form-control" required value="<?php echo isset($filmEpisode) ? htmlspecialchars($filmEpisode->duration) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Year of Production <span style="color:red">*</span></label>
                            <div class="col-md-4">
                                        <select name="year" class="form-control" required>
                                            <option value="">Select Year</option>
                                    <?php $selected_year = isset($filmEpisode) ? $filmEpisode->year : ''; ?>
                                    <?php for ($i = (int)date('Y'); $i >= 1900; $i--): ?>
                                        <option value="<?php echo $i; ?>" <?php echo ((string)$selected_year === (string)$i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>

                        <label>Related Content</label>
                            <div style="padding-left:20px;">
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Keywords</label>
                                <div class="col-md-4 d-flex align-items-center gap-2">
                                    <select class="form-control select2" name="related_keywords[]" id="related_keywords" multiple>
                                                <?php foreach ($keyword_rows as $keyword): ?>
                                            <?php $kid = (string)$keyword->id; ?>
                                            <option value="<?php echo htmlspecialchars($kid); ?>" <?php echo in_array($kid, $selected_related_keywords, true) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($keyword->word_transliteration); ?>
                                            </option>
                                                <?php endforeach; ?>
                                            </select>
                                    <button type="button" class="btn btn-success btn-sm ml-2" id="addNewKeywordBtn">Add New</button>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Songs</label>
                                <div class="col-md-4 d-flex align-items-center gap-2">
                                    <select class="form-control select2" name="related_songs[]" id="related_songs" multiple>
                                        <?php foreach ($song_rows as $song): $sid=(string)$song->id; ?>
                                            <option value="<?php echo htmlspecialchars($sid); ?>" <?php echo in_array($sid, $selected_related_songs, true) ? 'selected' : ''; ?>><?php echo htmlspecialchars($song->umbrellaTitle); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Poems</label>
                                <div class="col-md-4 d-flex align-items-center gap-2">
                                    <select class="form-control select2" name="related_poems[]" id="related_poems" multiple>
                                        <?php foreach ($poem_rows as $poem): $pid=(string)$poem->id; ?>
                                            <option value="<?php echo htmlspecialchars($pid); ?>" <?php echo in_array($pid, $selected_related_poems, true) ? 'selected' : ''; ?>><?php echo htmlspecialchars($poem->poem_label); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Reflections</label>
                                <div class="col-md-4 d-flex align-items-center gap-2">
                                    <select class="form-control select2" name="related_reflections[]" id="related_reflections" multiple>
                                        <?php foreach ($reflection_rows as $reflection): $rid=(string)$reflection->id; ?>
                                            <option value="<?php echo htmlspecialchars($rid); ?>" <?php echo in_array($rid, $selected_related_reflections, true) ? 'selected' : ''; ?>><?php echo htmlspecialchars($reflection->title); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ People</label>
                                <div class="col-md-4 d-flex align-items-center gap-2">
                                    <select class="form-control select2" name="related_people[]" id="related_people" multiple>
                                        <?php foreach ($person_rows as $person): $pid=(string)$person->id; ?>
                                            <?php $full = trim(($person->first_name ?? '') . ' ' . ($person->middle_name ?? '') . ' ' . ($person->last_name ?? '')); ?>
                                            <option value="<?php echo htmlspecialchars($pid); ?>" <?php echo in_array($pid, $selected_related_people, true) ? 'selected' : ''; ?>><?php echo htmlspecialchars($full !== '' ? $full : ('Person #'.$pid)); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">Publish Status</label>
                            <div class="col-md-4">
                                        <select name="publish" class="form-control">
                                    <?php $pub = isset($filmEpisode) ? strtolower((string)$filmEpisode->publish) : ''; ?>
                                    <option value="false" <?php echo ($pub === 'false' || $pub === '0' || $pub === '') ? 'selected' : ''; ?>>No</option>
                                    <option value="true" <?php echo ($pub === 'true' || $pub === '1') ? 'selected' : ''; ?>>Yes</option>
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
<script>
$(document).ready(function() {
    $('.select2').select2({ placeholder: 'Select options', allowClear: true, width: '100%' });

    function initAboutEditor() {
        if (!window.CKEDITOR || !document.getElementById('about_text')) {
            return false;
        }
        try {
            if (CKEDITOR.instances.about_text) {
                CKEDITOR.instances.about_text.destroy(true);
            }
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
            return true;
        } catch (e) {
            return false;
        }
    }

    // filmDetails-style delayed init
    setTimeout(function () {
        initAboutEditor();
    }, 500);

    // fallback retries to avoid race with other scripts
    var attempts = 0;
    var maxAttempts = 20;
    var retry = setInterval(function () {
        attempts++;
        if (initAboutEditor() || attempts >= maxAttempts) {
            clearInterval(retry);
        }
    }, 400);

    $('#addNewKeywordBtn').on('click', function() {
            Swal.fire({
            title: 'Add New Keyword',
            input: 'text',
            inputPlaceholder: 'Enter keyword',
            showCancelButton: true
        }).then(async (result) => {
            if (!result.isConfirmed || !result.value) return;
            const res = await fetch('<?= base_url('SongController/ajax_create_keyword') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'word_transliteration=' + encodeURIComponent(result.value.trim())
            });
            const data = await res.json();
            if (data && data.status === 'success') {
                const val = String(data.keyword_id || data.id);
                const txt = data.word_transliteration || result.value.trim();
                if ($('#related_keywords option[value="' + val.replace(/(["\\])/g, '\\$1') + '"]').length === 0) {
                    $('#related_keywords').append(new Option(txt, val));
                }
                let selected = $('#related_keywords').val() || [];
                selected.push(val);
                $('#related_keywords').val(selected).trigger('change');
                Swal.fire('Success', data.message || 'Keyword added', 'success');
            } else {
                Swal.fire('Error', (data && data.message) ? data.message : 'Failed to add keyword', 'error');
            }
        });
    });

    // Ensure editor content posts correctly on submit.
    $('#episodeForm').on('submit', function() {
        if (window.CKEDITOR && CKEDITOR.instances && CKEDITOR.instances.about_text) {
            CKEDITOR.instances.about_text.updateElement();
        }
    });
});
</script>

<?php include('inc/footer.php'); ?>
