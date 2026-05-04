<!-- Content Wrapper -->
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Import Data Panel</h1>
          <small class="text-muted">Import data from ajab_old to ajab_live</small>
        </div>
        <div class="col-sm-6 text-right">
          <form action="<?php echo base_url('import-panel/import-all'); ?>" method="post" style="display:inline;" onsubmit="return confirm('Are you sure? This will DELETE all existing data in ajab_live and replace with ajab_old data for ALL menus!');">
            <button type="submit" class="btn btn-danger btn-lg">
              <i class="fas fa-sync-alt"></i> Import All
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
          <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
      <?php endif; ?>

      <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
          <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
      <?php endif; ?>

      <div class="row">
        <?php foreach ($menu_data as $menu): ?>
        <div class="col-md-4 col-sm-6 mb-3">
          <div class="card card-outline <?php echo ($menu['old_count'] > 0) ? 'card-primary' : 'card-secondary'; ?>">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-database"></i> <?php echo $menu['name']; ?></h3>
            </div>
            <div class="card-body">
              <table class="table table-sm table-borderless mb-2">
                <tr>
                  <td><strong>ajab_old:</strong></td>
                  <td><span class="badge badge-info"><?php echo $menu['old_count']; ?> rows</span></td>
                </tr>
                <tr>
                  <td><strong>ajab_live:</strong></td>
                  <td><span class="badge badge-success"><?php echo $menu['live_count']; ?> rows</span></td>
                </tr>
                <tr>
                  <td><strong>Tables:</strong></td>
                  <td><small class="text-muted"><?php echo implode(', ', $menu['tables']); ?></small></td>
                </tr>
              </table>

              <?php if ($menu['old_count'] > 0): ?>
              <form action="<?php echo base_url('import-panel/import'); ?>" method="post" onsubmit="return confirm('This will DELETE existing data in ajab_live for <?php echo $menu['name']; ?> and import from ajab_old. Continue?');">
                <input type="hidden" name="menu_name" value="<?php echo $menu['name']; ?>">
                <button type="submit" class="btn btn-primary btn-block">
                  <i class="fas fa-download"></i> Import <?php echo $menu['name']; ?>
                </button>
              </form>
              <?php else: ?>
              <button class="btn btn-secondary btn-block" disabled>
                <i class="fas fa-ban"></i> No data in ajab_old
              </button>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </section>
</div>
