<?php
include('inc/header.php');
include('inc/sidebar.php');
?>

<style>
    table.dataTable td, table.dataTable th {
        white-space: nowrap;
    }
</style>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Film Details List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('list') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Film Details List</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
            <?php endif; ?>
            <div class="card">
                <div class="card-body">
                    <div class="list-total-entries" style="margin:0 0 12px 0;font-weight:600;font-size:15px;color:#333;">Total Entries: <span id="filmDetailsTableCount">0</span></div>
                    <table id="filmDetailsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:50px;">Sl.No</th>
                                <th>Film Title</th>
                                <th>Series Title</th>
                                <th>Publish</th>
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

<?php include('inc/footer.php'); ?>

<!-- DataTables & jQuery -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#filmDetailsTable').DataTable({
        ajax: {
            url: "<?= base_url('FilmController/fetch_filmDetails') ?>",
            type: "GET",
            dataSrc: 'data'
        },
        columns: [
            { data: null, title: 'Sl.No', orderable: false, searchable: false, width: '50px', render: function(d,t,r,m){ return m.row + 1 + m.settings._iDisplayStart; } },
            { data: 'main_title', title: 'Film Title' },
            { data: 'series_title', title: 'Series Title' },
            { data: 'publish', title: 'Publish', render: function(data) {
                if (data === 1 || data === '1' || data === true) return 'Yes';
                if (typeof data === 'string' && ['true','yes','y','1'].indexOf(data.toLowerCase()) !== -1) return 'Yes';
                return 'No';
            } },
            {
                data: 'id',
                title: 'Action',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `
                        ${__adminPreviewBtn(data)}<a href="<?= base_url('filmDetails/edit/') ?>${data}" class="btn btn-sm btn-primary">Edit</a>
                        <button class="btn btn-sm btn-danger delete-film" data-id="${data}">Delete</button>
                    `;
                }
            }
        ],
        drawCallback: function(settings) { var api = this.api(); var total = api.page.info().recordsTotal; document.getElementById('filmDetailsTableCount').textContent = total; },
        responsive: true,
        lengthChange: true,
        autoWidth: false
    });

    // Delete functionality
    $('#filmDetailsTable').on('click', '.delete-film', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('filmDetails/delete/') ?>' + id,
                    type: 'POST',
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status == 'success') {
                            Swal.fire('Deleted!', res.message, 'success');
                            $('#filmDetailsTable').DataTable().ajax.reload();
                        } else {
                            Swal.fire('Error!', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Failed to delete film.', 'error');
                    }
                });
            }
        });
    });
});
</script>