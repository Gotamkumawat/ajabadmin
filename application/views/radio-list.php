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
                    <h1>Radio List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('list') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Radio List</li>
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
            <div class="card">
                <div class="card-body">
                    <table id="radioTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sl. No.</th>
                                <th>Date of Upload</th>
                                <th>Song Name</th>
                                <th>Singer</th>
                                <th>Playlist</th>
                                <!-- <th>Published (Yes/No)</th> -->
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
    $('#radioTable').DataTable({
        ajax: {
            url: "<?= base_url('RadioController/fetch_radio') ?>", // Updated to match controller
            type: "GET",
            dataSrc: 'data'
        },
        columns: [
           { data: 'sl_no' },
            { data: 'date_of_upload' },
            { data: 'song_name' },
            { data: 'singer_name' },
            { data: 'playlist' },   
            // { data: 'published', title: 'Published (Yes/No)' },
            { data: 'action', title: 'Action' }
        ],
        responsive: true,
        lengthChange: true,
        autoWidth: false
    });
});
</script>