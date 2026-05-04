
<!DOCTYPE html>
<html ng-app="filmsAdminApp">
<head lang="en">
    <meta charset="UTF-8">
    <title>Film list page</title>
    <script src="/common/lib/angular/angular.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.25/angular-cookies.js"></script>
    <script src="/common/lib/underscore/underscore.js"></script>
    <script type="text/javascript" src="/common/lib/angular-multi-select/angular-multi-select.js"></script>
    <script type="text/javascript" src="/common/lib/angular-filter/dist/angular-filter.js"></script>
    <script type="text/javascript" src="/common/lib/textAngular/src/textAngular-sanitize.js"></script><script type="text/javascript" src="/common/lib/textAngular/src/textAngularSetup.js"></script>
    <script type="text/javascript" src="/common/lib/textAngular/src/textAngular.js"></script>
    <script type="text/javascript" src="/admin/js/services/loginVerifyService.js"></script>
    <script type="text/javascript" src="/admin/js/services/filmContentService.js"></script>
    <script type="text/javascript" src="/admin/js/films/filmsAdminApp.js"></script>
    <script type="text/javascript" src="/admin/js/controllers/film/filmListController.js"></script>
    <script type="text/javascript" src="/admin/js/participate/angular-base64.js"></script>
    <link href="/common/lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="/admin/css/admin.css" rel="stylesheet" type="text/css">
</head>
<body>

<div>
    <a ng-href="filmsSectionList" target="_self">
        <button type="button" class="btn btn-info admin-home" style="margin-left: 83%;">Film home</button>
    </a>
</div>

<div>
    <a ng-href="list" target="_self">
        <button type="button" class="btn btn-info admin-home">Admin home</button>
    </a>
</div>


<br>
<br>

<div ng-controller="filmListController">
    <table class="table">
        <tr>

            <td><b>Main title</b></td>
            <td><b>Second Title</b></td>
            <td><b>Show on Landing page</b></td>
            <td><b>Published</b></td>
            <td><b>Actions</b></td>
        </tr>
        <tr>
            <td><input ng-model="search.englishTransliteration" class="form-control"><br></td>
            <td><input ng-model="search.englishTranslation" class="form-control"><br></td>
            <td><input ng-model="search.showOnLandingPage" class="form-control"><br></td>
            <td><input ng-model="search.publish" class="form-control"><br></td>
        </tr>
        <tr ng-cloak ng-repeat="film in films | orderBy:'englishTransliteration' | filter:search">

            <td>{{film.englishTransliteration}}</td>
            <td>{{film.englishTranslation}}</td>
            <td>{{film.showOnLandingPage}}</td>
            <td>{{film.publish}}</td>

            <td>
                <a href="filmDetails.html?id={{film.id}}" class="btn btn-info">Edit</a>
                <a ng-click="deleteFilm(film.id)" class="btn btn-danger">Delete</a>
            </td>
        </tr>
    </table>

    <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
           
                  <tbody>
                  <tr>
                    <td >Chalo Hamara Des</td>
                    <td>journeys with Kabir and Friends</td>
                    <td>Yes</td>
                    <td>Yes
                    </td>
                    <!-- <td>Win 95+</td>
                    <td> 4</td> -->
                       <td><a href="details.html?id={{couplet.id}}" class="btn btn-info">Edit</a>&nbsp;&nbsp;&nbsp;
                       <a ng-click="deleteCouplet(couplet.id)" class="btn btn-danger">Delete</a></td>                  
                    </tr>
                  <tr>
                    <td>Don't Fall In Love With Those Who Wander In Boats</td>
                    <td>कश्ती वालों से इश्क़ ना करना</td>
                    <td>Yes</td>
                    <td>Yes
                    </td>
                    <!-- <td>Win 95+</td>
                    <td>5</td> -->
<td><a href="details.html?id={{couplet.id}}" class="btn btn-info">Edit</a>&nbsp;&nbsp;&nbsp;
                       <a ng-click="deleteCouplet(couplet.id)" class="btn btn-danger">Delete</a></td>  
                    </tr>
 
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
</div>
</body>
</html>
