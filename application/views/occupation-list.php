<?php
include('inc/header.php');
include('inc/sidebar.php');
?>

<style>
    table.dataTable td, table.dataTable th {
        white-space: nowrap;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Occupation List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('list'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Occupation List</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">All Occupations</h3>
                    <a href="<?php echo base_url('add-occupation'); ?>" class="btn btn-primary btn-sm">Add Occupation</a>
                </div>
                <div class="card-body">
                    <div class="list-total-entries" style="margin:0 0 12px 0;font-weight:600;font-size:15px;color:#333;">Total Entries: <span id="occupationTableCount">0</span></div>
                    <table id="occupationTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:50px;">Sl.No</th>
                                <th>Occupation Name</th>
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

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    var table = $('#occupationTable').DataTable({
        ajax: {
            url: "<?php echo base_url('fetch-occupations'); ?>",
            type: "GET",
            dataSrc: 'data'
        },
        columns: [
            { data: null, title: 'Sl.No', orderable: false, searchable: false, width: '50px', render: function(d,t,r,m){ return m.row + 1 + m.settings._iDisplayStart; } },
            { data: 'name', title: 'Occupation Name' },
            {
                data: 'id',
                title: 'Action',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return __adminPreviewBtn(data) + '<a href="<?php echo base_url('occupation/edit/'); ?>' + data + '" class="btn btn-sm btn-primary mr-1">Edit</a>' +
                           '<button class="btn btn-sm btn-danger delete-occupation" data-id="' + data + '">Delete</button>';
                }
            }
        ],
        drawCallback: function(settings) { var api = this.api(); var total = api.page.info().recordsTotal; document.getElementById('occupationTableCount').textContent = total; },
        responsive: true,
        lengthChange: true,
        autoWidth: false
    });

    $('#occupationTable').on('click', '.delete-occupation', function() {
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
            if (!result.isConfirmed) return;

            $.ajax({
                url: '<?php echo base_url('occupation/delete/'); ?>' + id,
                type: 'POST',
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire('Deleted!', res.message, 'success');
                        table.ajax.reload();
                    } else {
                        Swal.fire('Error!', res.message || 'Delete failed', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Failed to delete occupation.', 'error');
                }
            });
        });
    });
});
</script>

