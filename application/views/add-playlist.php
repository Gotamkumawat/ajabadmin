<?php
include('inc/header.php');
include('inc/sidebar.php');
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

    .nav-tabs {
        border-bottom: none;
        margin-bottom: 20px;
    }

    .nav-tabs .nav-link {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 10px 20px;
        margin-right: 10px;
        color: #495057;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
    }

    .nav-tabs .nav-link.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .tab-content {
        padding: 20px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        background-color: #fff;
    }

    .track-item {
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        background-color: #f8f9fa;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .track-item .track-info {
        flex: 1;
    }

    .track-item .btn-remove {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
    }

    .track-item .btn-remove:hover {
        background-color: #c82333;
    }
</style>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Playlist Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('add_new'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Add/Edit Playlist</li>
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
                        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                    <?php endif; ?>

                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs" id="playlistTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab">
                                Playlist Info
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tracks-tab" data-toggle="tab" href="#tracks" role="tab">
                                Tracks
                            </a>
                        </li>
                    </ul>

                    <form method="POST" action="<?= base_url('playlist/save') ?>" enctype="multipart/form-data" id="playlistForm">
                        <!-- Hidden ID field for edit -->
                        <?php if (isset($playlist) && $playlist): ?>
                            <input type="hidden" name="id" value="<?php echo $playlist['id']; ?>">
                        <?php endif; ?>
                        <input type="hidden" name="tracks" id="tracks_data" value="">

                        <div class="tab-content" id="playlistTabContent">
                            
                            <!-- Tab 1: Playlist Info -->
                            <div class="tab-pane fade show active" id="info" role="tabpanel">
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">Playlist Name <span style="color:red">*</span></label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="playlist_name" name="playlist_name" 
                                               value="<?php echo isset($playlist) && $playlist ? $playlist['name'] : ''; ?>" 
                                               placeholder="Enter playlist name" required>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">Description</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" id="playlist_description" name="playlist_description" rows="5" 
                                                  placeholder="Enter playlist description"><?php echo isset($playlist) && $playlist ? $playlist['description'] : ''; ?></textarea>
                                    </div>
                                </div>
                                <?php
                                $plCoverPath = (isset($playlist) && $playlist && !empty($playlist['cover_image'])) ? trim((string) $playlist['cover_image']) : '';
                                $plCoverSrc = '';
                                if ($plCoverPath !== '') {
                                    $plCoverSrc = preg_match('#^https?://#i', $plCoverPath) ? $plCoverPath : (rtrim(base_url(), '/') . '/' . ltrim($plCoverPath, '/'));
                                }
                                ?>
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">Cover Image</label>
                                    <div class="col-md-4">
                                        <input type="file" class="form-control-file" name="cover_image" accept="image/*">
                                        <?php if ($plCoverSrc !== ''): ?>
                                            <div class="mt-2">
                                                <img src="<?= htmlspecialchars($plCoverSrc) ?>" alt="Cover preview" style="max-width:200px;height:auto;border:1px solid #ddd;border-radius:4px;"
                                                    onerror="this.style.display='none';">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">Publish Status</label>
                                    <div class="col-md-4" style="padding-left:20px; padding-top:8px;">
                                        <label style="margin-bottom:0;">
                                            <input type="checkbox" name="is_published" value="1" 
                                                   <?php echo (isset($playlist) && $playlist && $playlist['is_published'] == 1) ? 'checked' : ''; ?> >
                                            <strong>Publish this Playlist</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 2: Tracks -->
                            <div class="tab-pane fade" id="tracks" role="tabpanel">
                                <div class="form-group row align-items-center mb-3">
                                    <label class="col-md-2 col-form-label">Select Track/Song to Add</label>
                                    <div class="col-md-4 d-flex align-items-end" style="gap:10px;">
                                        <select class="form-control select2" id="track_select">
                                            <option value="">Select a song...</option>
                                            <?php if (isset($songs) && !empty($songs)): ?>
                                                <?php foreach ($songs as $song): ?>
                                                    <option value="<?php echo $song['id']; ?>" 
                                                            data-name="<?php echo isset($song['songTitle']) ? htmlspecialchars($song['songTitle']) : ''; ?>"
                                                            data-artist="<?php echo isset($song['singer']) ? htmlspecialchars($song['singer']) : ''; ?>">
                                                        <?php echo isset($song['songTitle']) ? $song['songTitle'] : 'Untitled'; ?>
                                                        <?php if (isset($song['singer']) && !empty($song['singer'])): ?>
                                                            - <?php echo $song['singer']; ?>
                                                        <?php endif; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <button type="button" class="btn btn-primary" id="add_track_btn">
                                            <i class="fas fa-plus"></i> Add Track
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 col-form-label">Tracks in Playlist</label>
                                    <div class="col-md-4">
                                        <div id="tracks_list" style="min-height: 100px;">
                                            <?php if (isset($playlist_tracks) && !empty($playlist_tracks)): ?>
                                                <?php foreach ($playlist_tracks as $index => $track): ?>
                                                    <div class="track-item" data-track-id="<?php echo $track['song_id']; ?>">
                                                        <div class="track-info">
                                                            <strong><?php echo $index + 1; ?>. <?php echo $track['song_name']; ?></strong>
                                                            <?php if (!empty($track['artist'])): ?>
                                                                <span class="text-muted"> - <?php echo $track['artist']; ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <button type="button" class="btn-remove" onclick="removeTrack(this)">
                                                            <i class="fas fa-times"></i> Remove
                                                        </button>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p class="text-muted" id="no_tracks_msg">No tracks added yet. Select songs from above to add them.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="save-btn-container">
                            <button type="submit" class="btn btn-primary">
                                <?php echo isset($playlist) && $playlist ? 'Update Playlist' : 'Save Playlist'; ?>
                            </button>
                            <a href="<?= base_url('playlist-lists') ?>" class="btn btn-secondary">Cancel</a>
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

<!-- Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/4.25.1-lts/standard-all/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        width: '100%',
        placeholder: "Select an option",
        allowClear: true
    });

    // Initialize CKEditor for description
    CKEDITOR.config.versionCheck = false;
    setTimeout(function() {
        CKEDITOR.replace('playlist_description', {
            height: 200,
            extraPlugins: 'colorbutton,font,justify',
            toolbar: [
                { name: 'document', items: ['Source'] },
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight'] },
                { name: 'insert', items: ['Link', 'Image', 'Table'] },
                { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] }
            ]
        });
    }, 500);

    // Add track functionality
    let tracksList = [];
    
    <?php if (isset($playlist_tracks) && !empty($playlist_tracks)): ?>
        tracksList = <?php echo json_encode(array_map(function($t) { 
            return ['id' => $t['song_id'], 'name' => $t['song_name'], 'artist' => $t['artist'] ?? '']; 
        }, $playlist_tracks)); ?>;
    <?php endif; ?>

    $('#add_track_btn').on('click', function() {
        const select = $('#track_select');
        const selectedOption = select.find('option:selected');
        const trackId = selectedOption.val();
        const trackName = selectedOption.data('name');
        const trackArtist = selectedOption.data('artist') || '';

        if (!trackId) {
            Swal.fire({
                icon: 'warning',
                title: 'No Track Selected',
                text: 'Please select a song to add',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Check if already added
        if (tracksList.some(t => t.id == trackId)) {
            Swal.fire({
                icon: 'info',
                title: 'Already Added',
                text: 'This track is already in the playlist',
                confirmButtonText: 'OK'
            });
            return;
        }

        tracksList.push({ id: trackId, name: trackName, artist: trackArtist });
        updateTracksList();
        select.val('').trigger('change');
    });

    function updateTracksList() {
        const container = $('#tracks_list');
        const noTracksMsg = $('#no_tracks_msg');
        
        if (tracksList.length === 0) {
            if (noTracksMsg.length === 0) {
                container.html('<p class="text-muted" id="no_tracks_msg">No tracks added yet. Select songs from above to add them.</p>');
            }
        } else {
            noTracksMsg.remove();
            let html = '';
            tracksList.forEach((track, index) => {
                html += `
                    <div class="track-item" data-track-id="${track.id}">
                        <div class="track-info">
                            <strong>${index + 1}. ${track.name}</strong>
                            ${track.artist ? '<span class="text-muted"> - ' + track.artist + '</span>' : ''}
                        </div>
                        <button type="button" class="btn-remove" onclick="removeTrack(this)">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                `;
            });
            container.html(html);
        }
        
        // Update hidden field
        $('#tracks_data').val(JSON.stringify(tracksList.map(t => t.id)));
    }

    window.removeTrack = function(btn) {
        const trackItem = $(btn).closest('.track-item');
        const trackId = trackItem.data('track-id');
        tracksList = tracksList.filter(t => t.id != trackId);
        updateTracksList();
    };

    // Form validation
    $('#playlistForm').on('submit', function(e) {
        e.preventDefault();

        // Update CKEditor data before submission
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }

        // Validate playlist name
        const playlistName = $('#playlist_name').val().trim();
        if (!playlistName) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Input',
                text: 'Please enter playlist name',
                confirmButtonText: 'OK'
            });
            $('#playlist_name').focus();
            return false;
        }

        // Update tracks data before submission
        $('#tracks_data').val(JSON.stringify(tracksList.map(t => t.id)));

        this.submit();
    });
});
</script>

