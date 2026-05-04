<?php 
include('inc/header.php');
include('inc/sidebar.php');
?>
 <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Add Film</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="add_new">Home</a></li>
            </ol>
          </div>
        </div>
      </div>
    </div>

<section class="content">
  <div class="container-fluid">
    <div class="row">

      <!-- 🎬 Film -->
      <div class="col-lg-2 col-md-4 col-sm-6">
        <a href="add-filmDetails" style="text-decoration: none;">
          <div class="small-box bg-info" style="cursor: pointer;">
            <div class="inner">
              <h3 style="color: white; font-size: 20px; margin: 8px 0;">Film</h3>
            </div>
            <div class="icon">
              <i class="fas fa-film" style="font-size: 20px;"></i>
            </div>
          </div>
        </a>
      </div>

      <!-- 🎞️ Film Episode -->
      <div class="col-lg-2 col-md-4 col-sm-6">
        <a href="add-filmEpisodeDetails" style="text-decoration: none;">
          <div class="small-box bg-info" style="cursor: pointer;">
            <div class="inner">
              <h3 style="color: white; font-size: 20px; margin: 8px 0;">Film Episode</h3>
            </div>
            <div class="icon">
              <i class="fas fa-video" style="font-size: 20px;"></i>
            </div>
          </div>
        </a>
      </div>

    </div>
  </div>
</section>

</div>
<?php
include('inc/footer.php');
?>




