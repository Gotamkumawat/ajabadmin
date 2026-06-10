<?php 
include 'inc/header.php';
include 'inc/sidebar.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Radio</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('list') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Radio</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <!-- Tab buttons -->
            <div class="card">
                <div class="card-header" style="background:#f8f9fa; border-bottom:1px solid #e9ecef;">
                    <ul class="nav nav-pills" id="radioTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-radio-track" data-tab="radio-track" href="#">Radio Track</a>
                        </li>
                        <li class="nav-item ml-2">
                            <a class="nav-link" id="tab-playlist" data-tab="playlist" href="#">Playlist</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <!-- Radio Track table -->
                    <div id="radio-track-pane">
                        <div class="list-total-entries" style="margin:0 0 12px 0;font-weight:600;font-size:15px;color:#333;">Total Entries: <span id="radioTableCount">0</span></div>
                    <table id="radioTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:50px;">Sl.No</th>
                                    <th>Song Name</th>
                                    <th>Singer</th>
                                    <th>Playlist</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <!-- Playlist table -->
                    <div id="playlist-pane" style="display:none;">
                        <table id="playlistTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:50px;">Sl.No</th>
                                    <th>Playlist Name</th>
                                    <th>Description</th>
                                    <th>Total Tracks</th>
                                    <th>Published</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
<!-- /.content-wrapper -->

<style>
    #radioTabs .nav-link {
        background: #fff;
        border: 1px solid #ced4da;
        color: #495057;
        padding: 6px 18px;
        border-radius: 4px;
        font-weight: 500;
    }
    #radioTabs .nav-link.active {
        background: #007bff;
        color: #fff;
        border-color: #007bff;
    }
</style>

<?php include 'inc/footer.php'; ?>

<!-- DataTables & jQuery -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    var radioDt = $('#radioTable').DataTable({
        ajax: {
            url: "<?= base_url('RadioController/fetch_radio') ?>",
            type: "GET",
            dataSrc: 'data'
        },
        columns: [
            { data: null, title: 'Sl.No', orderable: false, searchable: false, width: '50px', render: function(d,t,r,m){ return m.row + 1 + m.settings._iDisplayStart; } },
            { data: 'song_name' },
            { data: 'singer_name' },
            { data: 'playlist' },
            { data: 'action', title: 'Action' }
        ],
        drawCallback: function(settings) { var api = this.api(); var total = api.page.info().recordsTotal; document.getElementById('radioTableCount').textContent = total; },
        responsive: true,
        lengthChange: true,
        autoWidth: false
    });

    var playlistDt = null;
    function initPlaylistDt() {
        if (playlistDt) return;
        playlistDt = $('#playlistTable').DataTable({
            ajax: {
                url: "<?= base_url('fetch-playlists') ?>",
                type: "GET",
                dataSrc: 'data'
            },
            columns: [
                { data: null, title: 'Sl.No', orderable: false, searchable: false, width: '50px', render: function(d,t,r,m){ return m.row + 1 + m.settings._iDisplayStart; } },
                { data: 'name', title: 'Playlist Name' },
                { data: 'description', title: 'Description' },
                { data: 'track_count', title: 'Total Tracks' },
                { data: 'is_published', title: 'Published', render: function(data) { return data == 'Yes' ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-danger">No</span>'; } },
                {
                    data: 'id',
                    title: 'Action',
                    orderable: false,
                    searchable: false,
                    responsivePriority: 2,
                    render: function(data) {
                        return __adminPreviewBtn(data) + '<a href="<?= base_url('add-playlist/') ?>' + data + '" class="btn btn-sm btn-primary">Edit</a> ' +
                            '<button class="btn btn-sm btn-danger delete-playlist" data-id="' + data + '">Delete</button>';
                    }
                }
            ],
            responsive: true,
            lengthChange: true,
            autoWidth: false
        });
    }

    // Tab switch
    $('#radioTabs .nav-link').on('click', function(e) {
        e.preventDefault();
        $('#radioTabs .nav-link').removeClass('active');
        $(this).addClass('active');
        var tab = $(this).data('tab');
        if (tab === 'radio-track') {
            $('#radio-track-pane').show();
            $('#playlist-pane').hide();
        } else {
            $('#radio-track-pane').hide();
            $('#playlist-pane').show();
            initPlaylistDt();
            if (playlistDt) {
                setTimeout(function() { playlistDt.columns.adjust(); }, 50);
            }
        }
    });

    // Playlist delete
    $('#playlistTable').on('click', '.delete-playlist', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('playlist/delete/') ?>' + id,
                    type: 'POST',
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status == 'success') {
                            Swal.fire('Deleted!', res.message, 'success');
                            playlistDt.ajax.reload();
                        } else {
                            Swal.fire('Error!', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Failed to delete playlist.', 'error');
                    }
                });
            }
        });
    });
});
</script>