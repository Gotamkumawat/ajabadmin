<?php 
include 'inc/header.php';
include 'inc/sidebar.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Cartoons List</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('list') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Cartoons List</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <!-- Flashdata for SweetAlert -->
            <?php if ($this->session->flashdata('success')): ?>
                <input type="hidden" id="flash-success" value="<?= $this->session->flashdata('success'); ?>">
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <input type="hidden" id="flash-error" value="<?= $this->session->flashdata('error'); ?>">
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <div class="list-total-entries" style="margin:0 0 12px 0;font-weight:600;font-size:15px;color:#333;">Total Entries: <span id="cartoonTableCount">0</span></div>
                    <table id="cartoonTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:50px;">Sl.No</th>
                                <th>Title</th>
                                <th>Thumbnail</th>
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

<?php include 'inc/footer.php'; ?>

<!-- DataTables & SweetAlert -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* ✅ Popup look smaller and cleaner */
.swal2-popup {
    border-radius: 12px !important;
    padding: 20px !important;
}
.swal2-title {
    font-size: 20px !important;
    margin-top: 10px !important;
}
.swal2-html-container {
    font-size: 15px !important;
}
</style>

<script>
$(document).ready(function() {
    // ✅ DataTable Initialization
    $('#cartoonTable').DataTable({
        ajax: {
            url: "<?= base_url('fetch-cartoons') ?>",
            type: "GET",
            dataSrc: 'data'
        },
        columns: [
            { data: null, title: 'Sl.No', orderable: false, searchable: false, width: '50px', render: function(d,t,r,m){ return m.row + 1 + m.settings._iDisplayStart; } },
            { data: 'title' },
            { data: 'thumbnail_url' },
            { data: 'is_publish' },
            { data: 'action' }
        ],
        drawCallback: function(settings) { var api = this.api(); var total = api.page.info().recordsTotal; document.getElementById('cartoonTableCount').textContent = total; },
        responsive: true,
        lengthChange: true,
        autoWidth: false
    });

    // ✅ Flash Message Popup
    var successMsg = $('#flash-success').val();
    var errorMsg = $('#flash-error').val();

    if (successMsg) {
        Swal.fire({
            title: 'Success!',
            text: successMsg,
            showConfirmButton: false,
            timer: 1800,
            width: '320px', // ✅ Compact popup
            html: `
                <div style="display:flex;justify-content:center;align-items:center;flex-direction:column;">
                    <svg width="65" height="65" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="54" fill="none" stroke="#28a745" stroke-width="6"/>
                        <path fill="none" stroke="#28a745" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" d="M38 62l14 14 30-34"/>
                    </svg>
                    <h2 style="margin:10px 0 5px;font-size:20px;">Success!</h2>
                    <p style="font-size:15px;">${successMsg}</p>
                </div>
            `,
        });
    }

    if (errorMsg) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: errorMsg,
            showConfirmButton: false,
            timer: 1800,
            width: '320px'
        });
    }
});
</script>
