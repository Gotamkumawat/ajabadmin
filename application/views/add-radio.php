
<?php 
include('inc/header.php');
include('inc/sidebar.php');
?>
<head>

</head>
<style>
        /* Clean multi-select box look */
    .select2-container--default .select2-selection--multiple {
        min-height: 38px;
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 4px 6px;
        display: flex;
        flex-wrap: wrap; /* allows proper wrapping */
        align-items: center;
        overflow: hidden; /* remove scrollbar */
    }

    /* Smaller and clean selected tags */
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

    /* Remove scrollbar completely */
    .select2-container--default .select2-selection--multiple::-webkit-scrollbar {
        display: none;
    }

    /* Adjust close icon spacing */
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        margin-right: 4px;
    }

    /* Responsive container */
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

    /* Additional CSS for new form sections */
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
    .cke_notification{
    	display:none;
    }
 .select2-container .select2-selection--multiple {
    /* min-height: 38px; */
    height: 5px;
    padding-top: 2px;
    display: flex !important;
    align-items: center !important;
    font-size: 14px !important; /* 👈 placeholder छोटा */
    border: 1px solid #ccc !important;
    border-radius: 4px !important;
}

.select2-selection__placeholder {
    font-size: 14px !important; /* 👈 placeholder छोटा */
    line-height: normal !important;
    color: #777 !important;
}

.save-btn-container {

    display: flex;
    justify-content: flex-end; /* Button right side me */
    margin-bottom: 0px; /* Neeche thoda space */
}

.save-btn {
    position: relative;
    top: -20px; /* 👉 button ko upar karega (value badha/samjha ke adjust kar sakte ho) */
    padding: 8px 30px;
    font-size: 16px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Radio Track</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="add_new">Home</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
                    <div class="card-header" style="padding: 4px 8px; margin: 0;">
                        <div>
                            <a href="<?php echo base_url('add_new'); ?>" 
                            class="btn btn-secondary"
                            style="padding: 3px 8px; font-size: 13px; border-radius: 4px;">
                            <i class="fas fa-arrow-left" style="font-size: 13px; margin-right: 4px;"></i> Back
                            </a>
                        </div>
                    </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- <form name="radioForm" method="post" action="<?php echo base_url('RadioController/save'); ?>"> -->
                        
                    <!-- <form action="<?= base_url('radio/save') ?>" method="post"> -->
                        <form id="radioForm" action="<?= base_url('radio/save') ?>" method="post" enctype="multipart/form-data">
                            

                            <?php if (!empty($radio) && !empty($radio->id)): ?>
                                <input type="hidden" name="id" value="<?= $radio->id ?>">
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('success')): ?>
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
                            <!-- Rest of form fields -->
                    <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            	<div class="row">
                      
								
						                </div>
                    	</div>
                    	<div class="row">
                            <!-- /.col -->
                       
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- Singers, Words, Reflections, Couplets -->
                        <?php
                        // Same pattern as add-song.php "Related Songs" — proven to work.
                        $radioSongRows = $this->db->query("
                            SELECT
                                s.id,
                                COALESCE(NULLIF(TRIM(ts.english_transliteration), ''), NULLIF(TRIM(ts.original_title), ''), NULLIF(TRIM(tu.english_transliteration), ''), NULLIF(TRIM(tu.original_title), ''), CONCAT('Song #', s.id)) AS Songtitle_transliteration
                            FROM song s
                            LEFT JOIN title ts ON ts.id = s.song_title_id
                            LEFT JOIN title tu ON tu.id = s.umbrella_title_id
                            ORDER BY LOWER(TRIM(COALESCE(ts.english_transliteration, ts.original_title, tu.english_transliteration, tu.original_title, ''))) ASC, s.id DESC
                        ")->result();
                        // DEBUG — write count to file
                        @file_put_contents(FCPATH . 'add_radio_debug.log',
                            "[".date('Y-m-d H:i:s')."] radioSongRows count=" . (is_array($radioSongRows) ? count($radioSongRows) : 'NOT ARRAY')
                            . " | last_error=" . print_r($this->db->error(), true)
                            . "\n", FILE_APPEND);
                        $selSongId = '';
                        if (!empty($radio)) {
                            if (isset($radio->song_id) && (int) $radio->song_id > 0) {
                                $selSongId = (string) (int) $radio->song_id;
                            } elseif (!empty($radio->songs)) {
                                $bits = array_filter(array_map('trim', explode(',', (string) $radio->songs)));
                                $selSongId = !empty($bits) ? (string) (int) $bits[0] : '';
                            }
                            // Fallback: lookup by song_name when no FK present (legacy / migrated radio rows)
                            if ($selSongId === '' && isset($radio->song_name) && trim((string)$radio->song_name) !== '') {
                                $songName = trim((string)$radio->song_name);
                                foreach ($radioSongRows as $sr) {
                                    $opt = trim((string)$sr->Songtitle_transliteration);
                                    if ($opt !== '' && strcasecmp($opt, $songName) === 0) {
                                        $selSongId = (string)(int)$sr->id;
                                        break;
                                    }
                                }
                            }
                        }
                        ?>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">1. Select Song <span style="color:red">*</span></label>
                            <div class="col-md-6">
                                <select name="song_id" id="radioSongSelect" class="form-control select2" multiple="multiple" data-skip-select2="true" data-single-pick="true" data-placeholder="Select Song" style="width:100%;">
                                    <?php foreach ($radioSongRows as $sr): ?>
                                        <option value="<?= (int) $sr->id ?>" <?= ((string) $sr->id === $selSongId) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars((string) $sr->Songtitle_transliteration) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div style="padding-left:20px;">
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Singer Name</label>
                                <div class="col-md-4">
                                    <input type="text" name="singer_name" id="radioDisplaySinger" value="<?= (!empty($radio) && isset($radio->singer_name)) ? htmlspecialchars($radio->singer_name) : '' ?>" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Location</label>
                                <div class="col-md-4">
                                    <input type="text" name="location" id="radioDisplayLocation" value="<?= (!empty($radio) && isset($radio->location)) ? htmlspecialchars($radio->location) : '' ?>" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">⊙ Year</label>
                                <div class="col-md-4">
                                    <input type="text" name="year" id="radioDisplayYear" value="<?= (!empty($radio) && isset($radio->year)) ? htmlspecialchars($radio->year) : '' ?>" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">2. Radio Excerpt Text</label>
                            <div class="col-md-4">
                                <input type="text" name="radio_excerpt" id="radioExcerptInput" value="<?= (!empty($radio) && isset($radio->radio_excerpt)) ? htmlspecialchars($radio->radio_excerpt) : '' ?>" class="form-control">
                            </div>
                        </div>
                        <input type="hidden" name="old_mp3_file" value="<?= (!empty($radio) && !empty($radio->upload_song_mp3_file)) ? htmlspecialchars($radio->upload_song_mp3_file) : '' ?>">
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">4. Playlist</label>
                            <div class="col-md-4 d-flex align-items-end" style="gap:10px;">
                                <div style="flex: 1; padding-left:0;">
                                    <?php
                                        // Fetch playlists directly (ordered by name)
                                        $playlists = $this->db->order_by('name', 'ASC')->get('playlist')->result();
                                        $selectedPlaylist = '';
                                        if (!empty($radio)) {
                                            if (isset($radio->playlist_id) && $radio->playlist_id !== '' && $radio->playlist_id !== null) {
                                                $selectedPlaylist = $radio->playlist_id;
                                            } elseif (!empty($radio->playlists)) {
                                                $pbits = array_filter(array_map('trim', explode(',', (string) $radio->playlists)));
                                                $selectedPlaylist = !empty($pbits) ? $pbits[0] : '';
                                            }
                                        }
                                    ?>
                                    <select name="playlist_id" id="playlistSelect" class="form-control select2" multiple="multiple" data-skip-select2="true" data-single-pick="true" data-placeholder="Select Playlist" style="width:100%;">
                                        <?php if (!empty($playlists)): ?>
                                            <?php foreach ($playlists as $pl): ?>
                                                <option value="<?= $pl->id; ?>" <?= ($selectedPlaylist == $pl->id) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($pl->name); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-success" onclick="openPlaylistModal()" style="padding: 6px 20px;">
                                    <i class="fas fa-plus"></i> Add New Playlist
                                </button>
                            </div>
                        </div>

                        <!-- Playlist Modal -->
                        <div id="playlistModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; padding: 20px;">
                            <div style="background: white; border-radius: 8px; max-width: 500px; margin: 50px auto; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
                                <h4 style="margin-top: 0; color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px;">Create New Playlist</h4>
                                
                                <div class="form-group">
                                    <label>Playlist Title <span style="color:red">*</span></label>
                                    <input type="text" id="playlistTitle" class="form-control" placeholder="Enter Playlist Title" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                                </div>

                                <div class="form-group">
                                    <label>Playlist Description</label>
                                    <textarea id="playlistDescription" class="form-control" rows="3" placeholder="Enter Playlist Description" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
                                </div>

                                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                                    <button type="button" class="btn btn-secondary" onclick="closePlaylistModal()" style="padding: 8px 15px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Cancel</button>
                                    <button type="button" class="btn btn-primary" onclick="savePlaylist()" style="padding: 8px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Create Playlist</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label">5. Publish Radio Song</label>
                            <div class="col-md-4">
                                <select name="publish" class="form-control">
                                    <option value="">Select</option>
                                    <option value="1" <?= (!empty($radio) && isset($radio->publish) && $radio->publish == "1") ? 'selected' : '' ?>>Yes</option>
                                    <option value="0" <?= (!empty($radio) && isset($radio->publish) && $radio->publish == "0") ? 'selected' : '' ?>>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="save-btn-container">
                            <button type="submit" class="btn btn-primary save-btn">Save</button>
                        </div>
                        
</div>
                        <!-- Reflection Checkbox Row -->
                        <br/>
                        <!-- <button type="submit" ng-click="saveData()" ng-disabled="!songForm.$valid" class="btn btn-primary btn-lg">Save</button> -->
                         
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

                <script>
                function togglePopup(selectElem) {
                    const popup = selectElem.parentElement.querySelector('.popup-dropdown');
                    // Close all other popups
                    document.querySelectorAll('.popup-dropdown').forEach(p => {
                        if (p !== popup) p.style.display = 'none';
                    });
                    // Toggle this one
                    popup.style.display = (popup.style.display === 'block') ? 'none' : 'block';
                }

                function filterOptions(inputElem) {
                    const filter = inputElem.value.toLowerCase();
                    const options = inputElem.parentElement.querySelectorAll('.optionsList label');
                    options.forEach(label => {
                        label.style.display = label.textContent.toLowerCase().includes(filter) ? '' : 'none';
                    });
                }

                function selectAll(button) {
                    const checkboxes = button.closest('.popup-dropdown').querySelectorAll('input[type="checkbox"]');
                    checkboxes.forEach(cb => cb.checked = true);
                }

                function selectNone(button) {
                    const checkboxes = button.closest('.popup-dropdown').querySelectorAll('input[type="checkbox"]');
                    checkboxes.forEach(cb => cb.checked = false);
                }

                function resetSelection(button) {
                    const checkboxes = button.closest('.popup-dropdown').querySelectorAll('input[type="checkbox"]');
                    checkboxes.forEach(cb => cb.checked = false);
                    const selectElem = button.closest('.multi-select-container').querySelector('select');
                    selectElem.value = '';
                }
                
                </script>
             <script>
                (function () {
                    const yearSelect = document.getElementById('yearSelect');
                    if (!yearSelect) return;
                    const currentYear = new Date().getFullYear();
                    for (let y = currentYear; y >= 1900; y--) {
                        const option = document.createElement('option');
                        option.value = y;
                        option.text = y;
                        yearSelect.appendChild(option);
                    }
                })();
            </script>
<script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>

                <script>
                        setTimeout(function () {

                            // Initialize all 4 editors (add all textarea IDs here)
                            const editorIDs = [
                                'songLyricsOriginal',       // 1️⃣
                                'songLyricsTranslated',     // 2️⃣
                                'songLyricsNotes',          // 3️⃣
                                'songLyricsMeaning'         // 4️⃣
                            ];

                            editorIDs.forEach(function (id) {
                                // Guard: only init CKEditor if the textarea exists on this page
                                var el = document.getElementById(id);
                                if (!el || el.tagName !== 'TEXTAREA') {
                                    return;
                                }
                                if (typeof CKEDITOR === 'undefined' || !CKEDITOR.replace) {
                                    return;
                                }
                                try {
                                    CKEDITOR.replace(id, {
                                        height: 200,
                                        extraPlugins: 'colorbutton,font,justify',
                                        toolbar: [
                                            { name: 'document', items: [ 'Source', '-', 'NewPage', 'Preview' ] },
                                            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat' ] },
                                            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                                            { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
                                            { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                                            { name: 'colors', items: [ 'TextColor', 'BGColor' ] }
                                        ]
                                    });
                                } catch (e) {
                                    console.warn('CKEditor init skipped for', id, e);
                                    return;
                                }

                                // AngularJS model sync — also guarded
                                if (CKEDITOR.instances && CKEDITOR.instances[id] && typeof CKEDITOR.instances[id].on === 'function') {
                                    CKEDITOR.instances[id].on('change', function () {
                                        var data = CKEDITOR.instances[id].getData();
                                        if (typeof angular === 'undefined') return;
                                        var $el = angular.element(document.querySelector('[id="' + id + '"]'));
                                        var scope = $el && $el.scope ? $el.scope() : null;
                                        if (!scope || !scope.song || !scope.song.songText) return;
                                        scope.$apply(function () {
                                            if (id === 'songLyricsOriginal') scope.song.songText.original = data;
                                            else if (id === 'songLyricsTranslated') scope.song.songText.translated = data;
                                            else if (id === 'songLyricsNotes') scope.song.songText.notes = data;
                                            else if (id === 'songLyricsMeaning') scope.song.songText.meaning = data;
                                        });
                                    });
                                }
                            });

                        }, 500);
                        </script>
                        
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Playlist Modal Functions
function openPlaylistModal() {
    document.getElementById('playlistModal').style.display = 'block';
    document.getElementById('playlistTitle').focus();
    document.getElementById('playlistTitle').value = '';
    document.getElementById('playlistDescription').value = '';
}

function closePlaylistModal() {
    document.getElementById('playlistModal').style.display = 'none';
    document.getElementById('playlistTitle').value = '';
    document.getElementById('playlistDescription').value = '';
}

function savePlaylist() {
    const title = document.getElementById('playlistTitle').value.trim();
    const description = document.getElementById('playlistDescription').value.trim();

    if (!title) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Title',
            text: 'Please enter Playlist Title',
            confirmButtonText: 'OK'
        });
        document.getElementById('playlistTitle').focus();
        return;
    }

    // Show loading
    Swal.fire({
        title: 'Creating Playlist...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // AJAX request to create playlist
    fetch('<?= base_url('radio/ajax-create-playlist') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            title: title,
            description: description
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Playlist created successfully',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                // Add new playlist to dropdown
                const select = document.getElementById('playlistSelect');
                const option = document.createElement('option');
                option.value = data.id;
                option.text = data.title;
                option.selected = true;
                select.appendChild(option);

                closePlaylistModal();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to create playlist',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to create playlist',
            confirmButtonText: 'OK'
        });
    });
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('playlistModal');
    if (event.target === modal) {
        closePlaylistModal();
    }
});

// Allow Enter key to submit
document.addEventListener('keypress', function(event) {
    const modal = document.getElementById('playlistModal');
    if (modal.style.display === 'block' && event.key === 'Enter') {
        event.preventDefault();
        savePlaylist();
    }
});
</script>

<script>
$(function () {
    var metaUrl = <?= json_encode(base_url('radio/ajax_song_meta')) ?>;
    function applySongMeta(id, opts) {
        opts = opts || {};
        var fillExcerpt = opts.fillExcerpt !== false;
        if (!id) return;
        fetch(metaUrl + '?id=' + encodeURIComponent(id))
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data || !data.ok) return;
                var s = document.getElementById('radioDisplaySinger');
                var loc = document.getElementById('radioDisplayLocation');
                var yr = document.getElementById('radioDisplayYear');
                var ex = document.getElementById('radioExcerptInput');
                if (s) s.value = data.singer_name || '';
                if (loc) loc.value = data.location || '';
                if (yr) yr.value = data.year || '';
                if (ex && fillExcerpt) ex.value = data.radio_excerpt || '';
            })
            .catch(function () {});
    }
    var $song = $('#radioSongSelect');
    if ($song.length) {
        // Treat as single-pick: when user selects a new value, deselect any prior selection
        $song.on('change', function () {
            var vals = $(this).val();
            if (Array.isArray(vals) && vals.length > 1) {
                // keep only the most recent selection
                var keep = vals[vals.length - 1];
                $(this).val([keep]);
                if ($(this).data('bs.multiselect') && $.fn.multiselect) {
                    $(this).multiselect('refresh');
                }
                vals = [keep];
            }
            var v = Array.isArray(vals) ? (vals[0] || '') : (vals || '');
            applySongMeta(v, { fillExcerpt: true });
        });
        var initVal = $song.val();
        if (initVal && (Array.isArray(initVal) ? initVal.length : true)) {
            var ex0 = document.getElementById('radioExcerptInput');
            var skipEx = ex0 && ex0.value.trim() !== '';
            var firstVal = Array.isArray(initVal) ? initVal[0] : initVal;
            applySongMeta(firstVal, { fillExcerpt: !skipEx });
        }
    }
    // Single-pick playlist
    var $pl = $('#playlistSelect');
    if ($pl.length) {
        $pl.on('change', function () {
            var vals = $(this).val();
            if (Array.isArray(vals) && vals.length > 1) {
                var keep = vals[vals.length - 1];
                $(this).val([keep]);
                if ($(this).data('bs.multiselect') && $.fn.multiselect) {
                    $(this).multiselect('refresh');
                }
            }
        });
    }
});
</script>
<?php 
include('inc/footer.php');
?>