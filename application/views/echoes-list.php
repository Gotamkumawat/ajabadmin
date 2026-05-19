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
                    <h1>Echoes list</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('list') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Echoes list</li>
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
                    <div class="list-total-entries" style="margin:0 0 12px 0;font-weight:600;font-size:15px;color:#333;">Total Entries: <span id="echoTableCount">0</span></div>
                    <table id="echoTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:50px;">Sl.No</th>
                                <th>Category</th>
                                 <th>Title</th>
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
    $('#echoTable').DataTable({
        ajax: {
            url: "<?= base_url('fetch-echoes') ?>",
            type: "GET",
            dataSrc: 'data'
        },
        columns: [
            { data: null, title: 'Sl.No', orderable: false, searchable: false, width: '50px', render: function(d,t,r,m){ return m.row + 1 + m.settings._iDisplayStart; } },
            { data: 'category' },
            { data: 'title' },
            { data: 'is_publish' },
            { data: 'action' }
        ],
        drawCallback: function(settings) { var api = this.api(); var total = api.page.info().recordsTotal; document.getElementById('echoTableCount').textContent = total; },
        responsive: true,
        lengthChange: true,
        autoWidth: false
    });
});
</script>




