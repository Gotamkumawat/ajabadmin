<?php
/**
 * Admin EDIT-form "Preview" button helper.
 * Renders the same cyan "Preview" button used in the list-page row actions
 * (class .admin-preview-btn — handled globally in inc/footer.php), but only
 * when a record is being EDITED. Pass the record (array or object) or a raw
 * id; on an Add form (no id) it renders nothing.
 *
 * Usage inside any add/edit view, next to the Save button:
 *     <?= admin_edit_preview_button($couplet) ?>
 *     <?= admin_edit_preview_button($person) ?>
 */
if (!function_exists('admin_edit_preview_button')) {
    function admin_edit_preview_button($record, $idKey = 'id') {
        $id = null;
        if (is_array($record)) {
            $id = isset($record[$idKey]) ? $record[$idKey] : null;
        } elseif (is_object($record)) {
            $id = isset($record->$idKey) ? $record->$idKey : null;
        } elseif (is_numeric($record)) {
            $id = $record;
        }
        if ($id === null || $id === '' || (int) $id <= 0) {
            return ''; // Add mode (or no id) → no Preview button
        }
        return '<button type="button" class="btn btn-info admin-preview-btn mr-2" data-id="'
             . htmlspecialchars((string) $id, ENT_QUOTES) . '">'
             . '<i class="far fa-eye"></i> Preview</button>';
    }
}
?>
<!DOCTYPE html>
<html lang="en" ng-app="cartoonApp" ng-controller="cartoonCtrl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ajab Shahar</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/fontawesome-free/css/all.min.css'); ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css'); ?>">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/jqvmap/jqvmap.min.css'); ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('dist/css/adminlte.min.css'); ?>">
  <!-- Admin editor marks (couplet / refrain) — also injected into CKEditor iframe.
       ?v=<mtime> cache-buster so deploys always pick up the latest CSS. -->
  <link rel="stylesheet" href="<?php echo base_url('assets/css/admin-editor.css') . '?v=' . (is_file(FCPATH . 'assets/css/admin-editor.css') ? filemtime(FCPATH . 'assets/css/admin-editor.css') : time()); ?>">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/overlayScrollbars/css/OverlayScrollbars.min.css'); ?>">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/daterangepicker/daterangepicker.css'); ?>">
  <!-- summernote -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/summernote/summernote-bs4.min.css'); ?>">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css'); ?>">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/select2/css/select2.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css'); ?>">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css'); ?>">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/bs-stepper/css/bs-stepper.min.css'); ?>">
  <!-- Dropzone -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/dropzone/min/dropzone.min.css'); ?>">
   <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <!-- jQuery (needed before sidebar and other early inline scripts) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Bootstrap Multiselect CSS (JS loaded in footer to ensure same jQuery instance as Select2) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
  <script>
    // Wrap $.fn.select2: skip multi-selects (we want Bootstrap Multiselect on those instead).
    (function() {
      function wrap() {
        if (!window.jQuery || !jQuery.fn || !jQuery.fn.select2) { setTimeout(wrap, 50); return; }
        var orig = jQuery.fn.select2;
        jQuery.fn.select2 = function() {
          var filtered = this.filter(function() {
            var $el = jQuery(this);
            if ($el.attr('data-skip-select2') === 'true') return false;
            // For multi-selects: never attach Select2 — Bootstrap Multiselect handles them
            if ($el.prop('multiple')) return false;
            return true;
          });
          if (!filtered.length) return this;
          return orig.apply(filtered, arguments);
        };
      }
      wrap();
    })();
  </script>
  <style>
    /* === Admin-wide Bootstrap Multiselect (old angular-multi-select look) === */
    .multiselect-native-select .dropdown-toggle::after,
    .multiselect-native-select .btn.dropdown-toggle::after,
    .multiselect-native-select button.multiselect::after,
    .multiselect-native-select button.multiselect::before,
    .btn-group > button.multiselect.dropdown-toggle::after,
    .btn-group > button.multiselect.dropdown-toggle::before {
      display: none !important;
      content: none !important;
      border: 0 !important;
    }
    .multiselect-native-select { position: relative; width: 200px !important; max-width: 200px !important; display: inline-block; }
    .multiselect-native-select .btn-group { width: 200px !important; max-width: 200px !important; }
    .multiselect-container.dropdown-menu {
      background-color: #fff !important;
      border: 1px solid rgba(0,0,0,0.15) !important;
      border-radius: 4px !important;
      box-shadow: 0 6px 12px rgba(0,0,0,0.175) !important;
      width: 280px !important;
      min-width: 280px !important;
      max-width: 280px !important;
      padding: 0 !important;
      margin: 2px 0 0 !important;
      max-height: 380px !important;
      overflow-y: auto !important;
      overflow-x: hidden !important;
      box-sizing: border-box !important;
      list-style: none !important;
    }
    .multiselect-container.dropdown-menu > li {
      width: 100% !important;
      max-width: 100% !important;
      box-sizing: border-box !important;
    }
    .multiselect-container > li.multiselect-item.multiselect-all,
    .multiselect-container > li.multiselect-item.multiselect-filter { display: none !important; }
    /* Search filter — hides non-matching items with priority over Bootstrap rules */
    ul.multiselect-container.dropdown-menu > li.ms-hidden,
    .multiselect-container > li.ms-hidden,
    li.ms-hidden { display: none !important; visibility: hidden !important; height: 0 !important; overflow: hidden !important; }
    .ms-helper-container { padding: 8px 8px 0 8px !important; border-bottom: 1px solid #ddd; list-style: none; background: #fff; }
    .ms-action-row { list-style: none; margin: 0; padding: 0; display: block; }
    .ms-action-row .ms-action-btn {
      display: inline-block; text-align: center; cursor: pointer;
      border: 1px solid #ccc; height: 26px; font-size: 13px; border-radius: 2px;
      color: #666; background-color: #f1f1f1; line-height: 1.6;
      margin: 0 4px 8px 0; padding: 0 10px;
    }
    .ms-action-row .ms-action-btn:hover { border: 1px solid #ccc; color: #999; background-color: #f4f4f4; }
    .ms-action-row .ms-action-btn:focus {
      border: 1px solid #66AFE9 !important;
      box-shadow: inset 0 0 1px rgba(0,0,0,.035), 0 0 5px rgba(82,168,236,.7);
      outline: none;
    }
    .ms-search-input {
      border-radius: 2px; border: 1px solid #ccc; height: 26px; font-size: 14px;
      width: 100%; padding-left: 7px; box-sizing: border-box;
      color: #888; margin: 0 0 8px 0;
    }
    .ms-search-input:focus {
      border: 1px solid #66AFE9 !important;
      box-shadow: inset 0 0 1px rgba(0,0,0,.035), 0 0 5px rgba(82,168,236,.7);
      outline: none;
    }
    .multiselect-container > li:not(.ms-helper-container):not(.ms-action-row):not(.ms-search-row) {
      margin: 0 !important;
      padding: 0 !important;
      display: block !important;
      width: 100% !important;
    }
    .multiselect-container li > a {
      padding: 0 !important;
      background: transparent !important;
      display: block !important;
      width: 100% !important;
    }
    .multiselect-container li > a > label.checkbox {
      display: block !important;
      width: 100% !important;
      padding: 6px 32px 6px 10px !important;
      color: #444 !important;
      /* Keep each option on a single line; truncate with ellipsis if too long
         (the full text is still readable on hover via the title attribute and
         the dropdown auto-expands its width — see .multiselect-container below). */
      white-space: nowrap !important;
      overflow: hidden !important;
      text-overflow: ellipsis !important;
      border: 1px solid transparent;
      position: relative;
      margin: 0 !important;
      cursor: pointer;
      font-weight: normal;
      line-height: 1.45;
      box-sizing: border-box !important;
    }
    /* Dropdown panel sizing.
       - min-width = at least as wide as the trigger button (200px wrapper).
       - width auto-fits its content up to max-width so options aren't cramped.
       - max-width capped so the panel never spreads across the screen.
       The button itself stays at its fixed 200px width via .btn-group below. */
    .multiselect-container.dropdown-menu {
      width: auto !important;
      min-width: 280px !important;
      max-width: 520px !important;
      box-sizing: border-box;
    }
    /* Anchor the dropdown to the button so it doesn't escape the column. */
    .btn-group > .multiselect-container.dropdown-menu {
      left: 0 !important;
    }
    .multiselect-container li > a > label.checkbox > input[type=checkbox] {
      position: absolute; left: -9999px; margin: 0;
    }
    .multiselect-container li.active > a > label.checkbox {
      background-image: linear-gradient(#e9e9e9, #f1f1f1);
      color: #555;
      border-top: 1px solid #e4e4e4;
      border-left: 1px solid #e4e4e4;
      border-right: 1px solid #d9d9d9;
    }
    .multiselect-container li.active > a > label.checkbox::after {
      content: "\2714";
      display: inline-block;
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 12px;
      color: #555;
      background: inherit;
      padding-left: 4px;
      pointer-events: none;
    }
    .multiselect-container li:hover > a > label.checkbox {
      background-image: linear-gradient(#c1c1c1, #999) !important;
      color: #fff !important;
      border: 1px solid #ccc !important;
    }
    .multiselect-container li:hover.active > a > label.checkbox::after { color: #fff; }
    .multiselect-container .ms-helper-container:hover { background: #fff !important; }

    /* === Admin-wide modal (Bootstrap + custom .song-person-dialog) — clean, consistent UI === */
    /* Make Bootstrap modals wider for better spacing */
    .modal .modal-dialog {
      max-width: 560px !important;
    }
    .modal .modal-dialog.modal-lg { max-width: 800px !important; }
    .modal .modal-dialog.modal-sm { max-width: 400px !important; }

    /* Custom .song-person-dialog (Add Singer / Poet / Translator) — make it look like Bootstrap modal */
    .song-person-dialog .modal-content {
      width: 560px !important;
      max-width: 92vw !important;
      margin: 8vh auto !important;
      background: #fff !important;
    }

    .modal-content,
    .song-person-dialog .modal-content {
      border: none !important;
      border-radius: 8px !important;
      box-shadow: 0 10px 40px rgba(0,0,0,0.2) !important;
    }
    .modal-header,
    .song-person-dialog .modal-header {
      padding: 14px 20px !important;
      border-bottom: 1px solid #e9ecef !important;
      background: #f8f9fa !important;
      border-radius: 8px 8px 0 0 !important;
      display: flex !important;
      align-items: center !important;
      justify-content: space-between !important;
    }
    .modal-header .modal-title,
    .song-person-dialog .modal-header h2,
    .song-person-dialog .modal-header h5 {
      font-size: 17px !important;
      font-weight: 600 !important;
      color: #212529 !important;
      margin: 0 !important;
    }
    .modal-header .close,
    .song-person-dialog .modal-header .close-btn {
      padding: 4px 10px !important;
      font-size: 22px !important;
      line-height: 1 !important;
      background: transparent !important;
      border: none !important;
      color: #555 !important;
      opacity: 0.6 !important;
      cursor: pointer !important;
    }
    .modal-header .close:hover,
    .song-person-dialog .modal-header .close-btn:hover { opacity: 1 !important; }
    .modal-body,
    .song-person-dialog .modal-body {
      padding: 20px !important;
    }
    .modal-footer,
    .song-person-dialog .modal-footer {
      padding: 12px 20px !important;
      border-top: 1px solid #e9ecef !important;
      background: #fafbfc !important;
      border-radius: 0 0 8px 8px !important;
      display: flex !important;
      justify-content: flex-end !important;
      align-items: center !important;
      gap: 8px !important;
    }
    /* Labels stack on top of inputs inside modal */
    .modal-body .form-group,
    .song-person-dialog .modal-body .form-group {
      display: block !important;
      margin-bottom: 14px !important;
      width: 100% !important;
    }
    .modal-body .form-group:last-child,
    .song-person-dialog .modal-body .form-group:last-child { margin-bottom: 0 !important; }
    .modal-body .form-group > label,
    .song-person-dialog .modal-body .form-group > label {
      flex: none !important;
      max-width: none !important;
      width: 100% !important;
      display: block !important;
      margin-bottom: 6px !important;
      padding-right: 0 !important;
      font-size: 14px !important;
      font-weight: 600 !important;
      color: #333 !important;
    }
    .modal-body .form-group > .form-control,
    .modal-body .form-group > input,
    .modal-body .form-group > select,
    .modal-body .form-group > textarea,
    .song-person-dialog .modal-body .form-group > input,
    .song-person-dialog .modal-body .form-group > select,
    .song-person-dialog .modal-body .form-group > textarea {
      width: 100% !important;
      max-width: 100% !important;
      flex: none !important;
      display: block !important;
      padding: 8px 12px !important;
      font-size: 14px !important;
      border: 1px solid #ced4da !important;
      border-radius: 4px !important;
      background: #fff !important;
      box-sizing: border-box !important;
      height: auto !important;
      line-height: 1.5 !important;
    }
    .modal-body .form-group > .form-control:focus,
    .modal-body .form-group > input:focus,
    .modal-body .form-group > select:focus,
    .modal-body .form-group > textarea:focus,
    .song-person-dialog .modal-body .form-group > input:focus,
    .song-person-dialog .modal-body .form-group > select:focus,
    .song-person-dialog .modal-body .form-group > textarea:focus {
      border-color: #80bdff !important;
      outline: none !important;
      box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15) !important;
    }
    /* Modal buttons consistent sizing */
    .modal-footer .btn,
    .song-person-dialog .modal-footer .btn,
    .song-person-dialog .modal-footer .btn-secondary,
    .song-person-dialog .modal-footer .btn-success {
      padding: 7px 18px !important;
      font-size: 14px !important;
      font-weight: 500 !important;
      border-radius: 4px !important;
      min-width: 80px !important;
      cursor: pointer !important;
      border: 1px solid transparent !important;
    }
    .song-person-dialog .modal-footer .btn-secondary {
      background: #6c757d !important;
      color: #fff !important;
      border-color: #6c757d !important;
    }
    .song-person-dialog .modal-footer .btn-secondary:hover { background: #5a6268 !important; }
    .song-person-dialog .modal-footer .btn-success {
      background: #28a745 !important;
      color: #fff !important;
      border-color: #28a745 !important;
    }
    .song-person-dialog .modal-footer .btn-success:hover { background: #218838 !important; }
    .modal-footer .btn + .btn { margin-left: 8px !important; }

    /* === Old-style theming for single-select Select2 (matches Bootstrap Multiselect button) === */
    .select2-container--default .select2-selection--single {
      height: 38px !important;
      border: 1px solid #c6c6c6 !important;
      border-radius: 4px !important;
      background-color: #fff !important;
      background-image: linear-gradient(#fff, #f7f7f7) !important;
      box-shadow: none !important;
      padding: 0 8px !important;
      display: flex !important;
      align-items: center !important;
    }
    .select2-container--default .select2-selection--single:hover {
      background-image: linear-gradient(#fff, #e9e9e9) !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: #555 !important;
      line-height: 36px !important;
      padding-left: 0 !important;
      padding-right: 20px !important;
      font-size: 14px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
      color: #888 !important;
    }
    /* Old-style caret triangle */
    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 36px !important;
      top: 1px !important;
      right: 6px !important;
      width: 16px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
      border-color: #333 transparent transparent transparent !important;
      border-style: solid !important;
      border-width: 4px 4px 0 4px !important;
      margin-left: -4px !important;
      margin-top: -2px !important;
    }
    /* Focus state */
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
      border: 1px solid #66AFE9 !important;
      box-shadow: inset 0 0 1px rgba(0,0,0,.035), 0 0 5px rgba(82,168,236,.7) !important;
    }
    /* Clear (×) button */
    .select2-container--default .select2-selection--single .select2-selection__clear {
      color: #999 !important;
      margin-right: 6px !important;
      font-weight: bold !important;
    }
    /* Dropdown panel matches multiselect dropdown */
    .select2-container--default .select2-dropdown {
      border: 1px solid rgba(0,0,0,0.15) !important;
      border-radius: 4px !important;
      box-shadow: 0 6px 12px rgba(0,0,0,0.175) !important;
      background-color: #fff !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
      border: 1px solid #ccc !important;
      border-radius: 2px !important;
      height: 26px !important;
      font-size: 14px !important;
      padding-left: 7px !important;
      color: #555 !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
      border: 1px solid #66AFE9 !important;
      box-shadow: inset 0 0 1px rgba(0,0,0,.035), 0 0 5px rgba(82,168,236,.7) !important;
      outline: none !important;
    }
    /* Result rows — match multiselect items */
    .select2-container--default .select2-results__option {
      padding: 4px 10px !important;
      color: #444 !important;
      font-size: 14px !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option--highlighted {
      background-image: linear-gradient(#c1c1c1, #999) !important;
      background-color: transparent !important;
      color: #fff !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
      background-image: linear-gradient(#e9e9e9, #f1f1f1) !important;
      background-color: transparent !important;
      color: #555 !important;
    }
  </style>

  <style>
    /* Global Select2 multi-select fix for admin forms */
    .select2-container .select2-selection--multiple {
      min-height: 38px !important;
      height: auto !important;
      padding: 4px 8px !important;
      display: flex !important;
      align-items: center !important;
      flex-wrap: wrap !important;
      border: 1px solid #ced4da !important;
      border-radius: 4px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
      display: flex !important;
      flex-wrap: wrap !important;
      align-items: center !important;
      gap: 4px;
      padding: 0 !important;
      margin: 0 !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
      margin: 2px 4px 2px 0 !important;
      line-height: 1.2 !important;
      padding: 4px 8px !important;
      display: inline-flex !important;
      align-items: center !important;
      gap: 6px !important;
      max-width: none !important;
      overflow: visible !important;
      text-overflow: clip !important;
      white-space: nowrap !important;
      font-size: 13px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
      margin-top: 0 !important;
      height: 28px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
      position: static !important;
      transform: none !important;
      margin: 0 !important;
      padding: 0 !important;
      font-size: 12px !important;
      line-height: 1 !important;
      order: -1;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
      padding-left: 0 !important;
      margin-left: 0 !important;
    }
  </style>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="<?php echo base_url('dist/img/AdminLTELogo.png'); ?>" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button" title="Toggle Menu">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button" title="Fullscreen">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>

      <?php
        // Resolve a friendly display name for the logged-in admin (set by Login controller).
        $__authUser  = $this->session->userdata('username');
        $__loggedIn  = (bool) $this->session->userdata('logged_in');
        $__authLabel = $__authUser ? $__authUser : 'Admin';
      ?>
      <?php if ($__loggedIn): ?>
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="gap:8px;">
          <span class="header-user-avatar" aria-hidden="true">
            <?= htmlspecialchars(strtoupper(substr($__authLabel, 0, 1))) ?>
          </span>
          <span class="d-none d-sm-inline" style="font-weight:500; color:#333;"><?= htmlspecialchars($__authLabel) ?></span>
          <i class="fas fa-caret-down" style="color:#666;"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right header-user-dropdown" style="min-width:220px; padding:0;">
          <div style="padding:14px 16px; border-bottom:1px solid #eee;">
            <div style="font-weight:600; color:#222; line-height:1.2;"><?= htmlspecialchars($__authLabel) ?></div>
            <div style="font-size:12px; color:#888; margin-top:2px;">Signed in</div>
          </div>
          <a href="<?= base_url('logout') ?>" class="dropdown-item header-logout-link" style="padding:12px 16px; display:flex; align-items:center; gap:10px; color:#dc3545; font-weight:500;">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </div>
      </li>
      <?php endif; ?>
    </ul>
  </nav>

  <style>
    /* Header user dropdown — small, modern */
    .header-user-avatar {
      display:inline-flex; align-items:center; justify-content:center;
      width:32px; height:32px; border-radius:50%;
      background:linear-gradient(135deg, #6366f1, #ec4899);
      color:#fff; font-weight:700; font-size:13px;
      box-shadow:0 2px 6px rgba(99,102,241,.35);
    }
    .header-user-dropdown { border:1px solid #e6e6e6; box-shadow:0 8px 24px rgba(0,0,0,.12); border-radius:8px; overflow:hidden; }
    .header-logout-link:hover { background:#fdecee; color:#b02a37 !important; }
  </style>
  <!-- /.navbar -->
