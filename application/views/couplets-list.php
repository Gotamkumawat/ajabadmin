<?php 
include 'inc/header.php';
include 'inc/sidebar.php';
?>

<!-- Content Wrapper. Contains page content -->
<?php if ($this->session->flashdata('success')): ?>
    <input type="hidden" id="flash-success" value="<?= htmlspecialchars($this->session->flashdata('success'), ENT_QUOTES) ?>">
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <input type="hidden" id="flash-error" value="<?= htmlspecialchars($this->session->flashdata('error'), ENT_QUOTES) ?>">
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var s = document.getElementById('flash-success');
    var e = document.getElementById('flash-error');
    if (s && window.Swal) {
        Swal.fire({ icon: 'success', title: 'Success', text: s.value, timer: 2200, showConfirmButton: false });
    }
    if (e && window.Swal) {
        Swal.fire({ icon: 'error', title: 'Error', text: e.value });
    }
});
</script>
<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Poem List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('list') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Poem List</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="list-total-entries" style="margin:0 0 12px 0;font-weight:600;font-size:15px;color:#333;">Total Entries: <span id="songsTableCount">0</span></div>
                    <table id="songsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:50px;">Sl.No</th>
                                <th>Poem Title (Transliteration)</th>
                                <th>Poem Title (Translation)</th>
                                <th>Poet OR Attributed Poet</th>
                                <!-- <th>Show on landing Page</th> -->
                                <th>Published</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>
<!-- /.content-wrapper -->

<?php include 'inc/footer.php'; ?>

<!-- DataTables & jQuery -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function() {
    $('#songsTable').DataTable({
        ajax: {
            url: "<?= base_url('fetch-couplets') ?>", // Controller ka fetch method
            type: "GET",
            dataSrc: 'data'
        },
        columns: [
            { data: null, title: 'Sl.No', orderable: false, searchable: false, width: '50px', render: function(d,t,r,m){ return m.row + 1 + m.settings._iDisplayStart; } },
            { data: 'couplet_translation', title: 'Poem Title (Transliteration)' },
            { data: 'couplet_transliteration', title: 'Poem Title (Translation)' },
            { data: 'poet_id', title: 'Poet OR Attributed Poet' },
            // { data: 'show_on_landing_page', title: 'Show on landing Page' },
            { data: 'is_published', title: 'Published' },
            { data: 'action', title: 'Action', orderable: false, searchable: false }
        ],
        drawCallback: function(settings) { var api = this.api(); var total = api.page.info().recordsTotal; document.getElementById('songsTableCount').textContent = total; },
        responsive: true,
        lengthChange: true,
        autoWidth: false
    });
});
</script>