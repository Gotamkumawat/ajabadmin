<?php
include('inc/header.php');
include('inc/sidebar.php');

// Ensure schema is bootstrapped (safe no-op if controller already ran)
if (!$this->db->table_exists('about_sections')) {
    $this->db->query("CREATE TABLE IF NOT EXISTS `about_sections` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `slug` VARCHAR(120) NOT NULL,
        `label` VARCHAR(180) NOT NULL,
        `color` VARCHAR(40) NOT NULL DEFAULT 'bg-info',
        `status_value` INT(11) NOT NULL DEFAULT 0,
        `sort_order` INT(11) NOT NULL DEFAULT 0,
        `created_at` DATETIME NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `slug` (`slug`),
        UNIQUE KEY `status_value` (`status_value`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    foreach ([
        ['slug'=>'ajab-shahar','label'=>'Ajab Shahar','color'=>'bg-info','status_value'=>0,'sort_order'=>1],
        ['slug'=>'kabir-project','label'=>'Kabir Project','color'=>'bg-info','status_value'=>1,'sort_order'=>2],
    ] as $r) { $r['created_at']=date('Y-m-d H:i:s'); $this->db->insert('about_sections', $r); }
}
$sections = $this->db->order_by('sort_order','ASC')->order_by('id','ASC')->get('about_sections')->result();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0">About List</h1></div>
                <div class="col-sm-6"></div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div style="display:flex; justify-content:flex-end; margin-bottom:10px;">
                <button type="button" id="addSectionBtn" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Add New Section
                </button>
            </div>

            <div class="row" id="sectionTilesRow">
                <?php foreach ($sections as $sec):
                    $slug = htmlspecialchars($sec->slug, ENT_QUOTES, 'UTF-8');
                    $label = htmlspecialchars($sec->label, ENT_QUOTES, 'UTF-8');
                    $color = htmlspecialchars($sec->color ?: 'bg-info', ENT_QUOTES, 'UTF-8');
                    $isDefault = in_array($sec->slug, ['ajab-shahar','kabir-project'], true);
                ?>
                <div class="col-lg-2 col-md-4 col-sm-6" data-tile-id="<?php echo (int)$sec->id; ?>">
                    <a href="<?php echo base_url('about-section/' . $slug); ?>" style="text-decoration:none;">
                        <div class="small-box <?php echo $color; ?>" style="cursor:pointer; position:relative;">
                            <div class="inner">
                                <h3 style="color:white; font-size:20px; margin:8px 0;"><?php echo $label; ?></h3>
                            </div>
                            <?php if (!$isDefault): ?>
                                <button type="button" class="delete-section-btn" data-id="<?php echo (int)$sec->id; ?>" title="Delete section"
                                        style="position:absolute; top:6px; right:8px; background:transparent; border:none; color:#fff; opacity:.7; cursor:pointer; font-size:14px;">
                                    <i class="fa fa-times"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>

<!-- Add New Section Modal -->
<div id="addSectionModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; width:420px; max-width:92%; border-radius:8px; padding:18px; box-shadow:0 10px 40px rgba(0,0,0,0.2);">
        <h5 style="margin:0 0 12px;">Add New Section</h5>
        <div class="form-group" style="margin-bottom:12px;">
            <label for="newSectionLabel">Section Name</label>
            <input type="text" id="newSectionLabel" class="form-control" placeholder="e.g. Library" maxlength="120">
        </div>
        <div style="display:flex; gap:8px; justify-content:flex-end;">
            <button type="button" class="btn btn-secondary" id="addSectionCancel">Cancel</button>
            <button type="button" class="btn btn-primary" id="addSectionSave">Add</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function(){
    const createUrl = <?php echo json_encode(base_url('about-section/sections/create')); ?>;
    const deleteBaseUrl = <?php echo json_encode(base_url('about-section/sections/delete')); ?>;
    const sectionBaseUrl = <?php echo json_encode(base_url('about-section')); ?>;

    const $modal = $('#addSectionModal');
    $('#addSectionBtn').on('click', function(){ $('#newSectionLabel').val(''); $modal.css('display','flex'); setTimeout(function(){ $('#newSectionLabel').focus(); }, 50); });
    $('#addSectionCancel').on('click', function(){ $modal.hide(); });
    $modal.on('click', function(e){ if (e.target === this) $modal.hide(); });

    $('#addSectionSave').on('click', function(){
        const label = ($('#newSectionLabel').val() || '').trim();
        if (!label) { Swal.fire({icon:'warning', title:'Missing name', text:'Please enter a section name'}); return; }
        const $btn = $(this).prop('disabled', true).text('Adding...');
        $.post(createUrl, { label: label }, function(resp){
            if (resp && resp.status && resp.data) {
                const d = resp.data;
                const tile = $(
                    '<div class="col-lg-2 col-md-4 col-sm-6" data-tile-id="'+d.id+'">' +
                      '<a href="'+ sectionBaseUrl + '/' + d.slug +'" style="text-decoration:none;">' +
                        '<div class="small-box bg-info" style="cursor:pointer; position:relative;">' +
                          '<div class="inner"><h3 style="color:white; font-size:20px; margin:8px 0;"></h3></div>' +
                          '<button type="button" class="delete-section-btn" data-id="'+d.id+'" title="Delete section" style="position:absolute; top:6px; right:8px; background:transparent; border:none; color:#fff; opacity:.7; cursor:pointer; font-size:14px;"><i class="fa fa-times"></i></button>' +
                        '</div>' +
                      '</a>' +
                    '</div>'
                );
                tile.find('h3').text(d.label);
                $('#sectionTilesRow').append(tile);
                $modal.hide();
                Swal.fire({icon:'success', title:'Section added', timer:1100, showConfirmButton:false});
            } else {
                Swal.fire({icon:'error', title:'Error', text:(resp && resp.message) ? resp.message : 'Failed to add section'});
            }
        }, 'json').fail(function(){
            Swal.fire({icon:'error', title:'Network error', text:'Could not reach server'});
        }).always(function(){ $btn.prop('disabled', false).text('Add'); });
    });

    $('#sectionTilesRow').on('click', '.delete-section-btn', function(e){
        e.preventDefault(); e.stopPropagation();
        const $btn = $(this);
        const id = $btn.data('id');
        Swal.fire({title:'Delete this section?', text:'This cannot be undone.', icon:'warning', showCancelButton:true, confirmButtonText:'Delete'})
            .then(function(r){
                if (!r.isConfirmed) return;
                $.post(deleteBaseUrl + '/' + id, {}, function(resp){
                    if (resp && resp.status) {
                        $btn.closest('[data-tile-id]').remove();
                        Swal.fire({icon:'success', title:'Deleted', timer:900, showConfirmButton:false});
                    } else {
                        Swal.fire({icon:'error', title:'Error', text:(resp && resp.message) ? resp.message : 'Delete failed'});
                    }
                }, 'json').fail(function(){
                    Swal.fire({icon:'error', title:'Network error', text:'Could not reach server'});
                });
            });
    });
});
</script>

<?php include('inc/footer.php'); ?>
