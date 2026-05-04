

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Songs List</title>
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Optional Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    
    <style>
        thead input {
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Songs List</h2>
    <table id="songsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
           
            <tr>
                <td>20</td>
                <td>23/12/2024</td>
                <td>Dil Ki Baat</td>
                <td>Rafiq Anwar</td>
                <td>Poetry</td>
                <td>No</td>
                <td>
                    <a href="#" class="btn btn-info btn-sm">Edit</a>
                    <a href="#" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
            
        </tbody>
    </table>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<!-- <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script> -->
<!-- <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script> -->
<!-- <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script> -->
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#songsTable').DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        dom: 'Bfrtip',
        // buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });

    // Apply column search
    $('#songsTable thead th').each(function() {
        var title = $(this).text();
        $(this).find('input').on('keyup change', function() {
            if (table.column($(this).parent().index()).search() !== this.value) {
                table
                    .column($(this).parent().index())
                    .search(this.value)
                    .draw();
            }
        });
    });

    // Move buttons to top
    table.buttons().container().appendTo('#songsTable_wrapper .col-md-6:eq(0)');
});
</script>

</body>
</html>
