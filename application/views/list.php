<?php
include('inc/header.php');
include('inc/sidebar.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">List</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="add_new">Home</a></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <!-- Song -->
        <div class="col-lg-2 col-md-4 col-sm-6">
          <a href="song-lists" style="text-decoration: none;">
            <div class="small-box bg-info" style="cursor: pointer;">
              <div class="inner">
                <h3 style="color: white; font-size: 20px; margin: 8px 0;">Song</h3>
              </div>
              <div class="icon">
                <i class="fas fa-music" style="font-size: 20px;"></i>
              </div>
            </div>
          </a>
        </div>

        <!-- Couplets -->
        <div class="col-lg-2 col-md-4 col-sm-6">
          <a href="couplets-list" style="text-decoration: none;">
            <div class="small-box bg-info" style="cursor: pointer;">
              <div class="inner">
                <h3 style="color: white; font-size: 20px; margin: 8px 0;">Poem</h3>
              </div>
              <div class="icon">
                <i class="fas fa-scroll" style="font-size: 20px;"></i>
              </div>
            </div>
          </a>
        </div>

        <!-- Words -->
        <!-- <div class="col-lg-2 col-md-4 col-sm-6">
          <a href="words-list" style="text-decoration: none;">
            <div class="small-box bg-info" style="cursor: pointer;">
              <div class="inner">
                <h3 style="color: white; font-size: 20px; margin: 8px 0;">Words</h3>
              </div>
              <div class="icon">
                <i class="fas fa-language" style="font-size: 20px;"></i>
              </div>
            </div>
          </a>
        </div> -->

        <!-- Reflections -->
        <div class="col-lg-2 col-md-4 col-sm-6">
          <a href="reflections-list" style="text-decoration: none;">
            <div class="small-box bg-info" style="cursor: pointer;">
              <div class="inner">
                <h3 style="color: white; font-size: 20px; margin: 8px 0;">Reflections</h3>
              </div>
              <div class="icon">
                <i class="fas fa-lightbulb" style="font-size: 20px;"></i>
              </div>
            </div>
          </a>
        </div>

        <!-- People -->
        <div class="col-lg-2 col-md-4 col-sm-6">
          <a href="people-list" style="text-decoration: none;">
            <div class="small-box bg-info" style="cursor: pointer;">
              <div class="inner">
                <h3 style="color: white; font-size: 20px; margin: 8px 0;">People</h3>
              </div>
              <div class="icon">
                <i class="fas fa-user-friends" style="font-size: 20px;"></i>
              </div>
            </div>
          </a>
        </div>

        <!-- Films -->
        <div class="col-lg-2 col-md-4 col-sm-6">
          <a href="filmsSectionList" style="text-decoration: none;">
            <div class="small-box bg-info" style="cursor: pointer;">
              <div class="inner">
                <h3 style="color: white; font-size: 20px; margin: 8px 0;">Films</h3>
              </div>
              <div class="icon">
                <i class="fas fa-film" style="font-size: 20px;"></i>
              </div>
            </div>
          </a>
        </div>

        <!-- About -->
        <div class="col-lg-2 col-md-4 col-sm-6">
          <a href="about-list" style="text-decoration: none;">
            <div class="small-box bg-info" style="cursor: pointer;">
              <div class="inner">
                <h3 style="color: white; font-size: 20px; margin: 8px 0;">About</h3>
              </div>
              <div class="icon">
                <i class="fas fa-info-circle" style="font-size: 20px;"></i>
              </div>
            </div>
          </a>
        </div>

        <!-- Stories -->
        <!-- <div class="col-lg-2 col-md-4 col-sm-6">
          <a href="stories-list" style="text-decoration: none;">
            <div class="small-box bg-info" style="cursor: pointer;">
              <div class="inner">
                <h3 style="color: white; font-size: 20px; margin: 8px 0;">Stories</h3>
              </div>
              <div class="icon">
                <i class="fas fa-book-open" style="font-size: 20px;"></i>
              </div>
            </div>
          </a>
        </div> -->

        <!-- Resources -->
        <!-- <div class="col-lg-2 col-md-4 col-sm-6">
          <a href="resources-list" style="text-decoration: none;">
            <div class="small-box bg-info" style="cursor: pointer;">
              <div class="inner">
                <h3 style="color: white; font-size: 20px; margin: 8px 0;">Resources</h3>
              </div>
              <div class="icon">
                <i class="fas fa-folder-open" style="font-size: 20px;"></i>
              </div>
            </div>
          </a>
        </div> -->

        <!-- Contributions -->
        <!-- <div class="col-lg-2 col-md-4 col-sm-6">
          <a href="contributions-list" style="text-decoration: none;">
            <div class="small-box bg-info" style="cursor: pointer;">
              <div class="inner">
                <h3 style="color: white; font-size: 19px; margin: 8px 0;">Contributions</h3>
              </div>
              <div class="icon">
                <i class="fas fa-hand-holding-heart" style="font-size: 20px;"></i>
              </div>
            </div>
          </a>
        </div> -->

        <!-- Echoes -->
        <!-- <div class="col-lg-2 col-md-4 col-sm-6">
          <a href="echoes-list" style="text-decoration: none;">
            <div class="small-box bg-info" style="cursor: pointer;">
              <div class="inner">
                <h3 style="color: white; font-size: 20px; margin: 8px 0;">Echoes</h3>
              </div>
              <div class="icon">
                <i class="fas fa-wave-square" style="font-size: 20px;"></i>
              </div>
            </div>
          </a>
        </div> -->

        <!-- Cartoons -->
        <!-- <div class="col-lg-2 col-md-4 col-sm-6">
          <a href="cartoons-list" style="text-decoration: none;">
            <div class="small-box bg-info" style="cursor: pointer;">
              <div class="inner">
                <h3 style="color: white; font-size: 20px; margin: 8px 0;">Cartoons</h3>
              </div>
              <div class="icon">
                <i class="fas fa-smile-beam" style="font-size: 20px;"></i>
              </div>
            </div>
          </a>
        </div> -->

         <!-- Cartoons -->
        <div class="col-lg-2 col-md-4 col-sm-6">
          <a href="radio-list" style="text-decoration: none;">
            <div class="small-box bg-info" style="cursor: pointer;">
              <div class="inner">
                <h3 style="color: white; font-size: 20px; margin: 8px 0;">Radio</h3>
              </div>
              <div class="icon">
                <i class="fas fa-smile-beam" style="font-size: 20px;"></i>
              </div>
            </div>
          </a>
        </div>

        <!-- News -->
        <div class="col-lg-2 col-md-4 col-sm-6">
          <a href="news-list" style="text-decoration: none;">
            <div class="small-box bg-info" style="cursor: pointer;">
              <div class="inner">
                <h3 style="color: white; font-size: 20px; margin: 8px 0;">News</h3>
              </div>
              <div class="icon">
                <i class="fas fa-newspaper" style="font-size: 20px;"></i>
              </div>
            </div>
          </a>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
          <a href="keywords-lists" style="text-decoration: none;">
            <div class="small-box bg-info" style="cursor: pointer;">
              <div class="inner">
                <h3 style="color: white; font-size: 20px; margin: 8px 0;">Keywords</h3>
              </div>
              <div class="icon">
                <i class="fas fa-newspaper" style="font-size: 20px;"></i>
              </div>
            </div>
          </a>
        </div>
        <!-- <div class="col-lg-2 col-md-4 col-sm-6">
          <a href="playlist-lists" style="text-decoration: none;">
            <div class="small-box bg-info" style="cursor: pointer;">
              <div class="inner">
                <h3 style="color: white; font-size: 20px; margin: 8px 0;">Playlist</h3>
              </div>
              <div class="icon">
                <i class="fas fa-newspaper" style="font-size: 20px;"></i>
              </div>
            </div>
          </a>
        </div> -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<?php
include('inc/footer.php');
?>