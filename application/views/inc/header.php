
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
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
