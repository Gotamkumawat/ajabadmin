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
                    <table id="songsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sl. No</th>
                                <th>Date of Upload</th>
                                <th>Poem Title (Transliteration)</th>
                                <th>Poet OR Attributed Poet (appearing in same column)</th>
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
            { data: 'sl_no', title: 'Sl. No' },
            { data: 'created_at', title: 'Date of Upload' },
            { data: 'couplet_transliteration', title: 'Poem Title (Transliteration)' },
            { data: 'poet_id', title: 'Poet OR Attributed Poet (appearing in same column)' },
            // { data: 'show_on_landing_page', title: 'Show on landing Page' },
            { data: 'is_published', title: 'Published' },
            { data: 'action', title: 'Action', orderable: false, searchable: false }
        ],
        responsive: true,
        lengthChange: true,
        autoWidth: false
    });
});
</script>