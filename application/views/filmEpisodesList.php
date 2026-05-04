<!DOCTYPE html>
<html ng-app="filmEpisodesAdminApp">
<head lang="en">
    <meta charset="UTF-8">
    <title>Film Episode list page</title>
    <script src="/common/lib/angular/angular.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.25/angular-cookies.js"></script>
    <script src="/common/lib/underscore/underscore.js"></script>
    <script type="text/javascript" src="/common/lib/angular-multi-select/angular-multi-select.js"></script>
    <script type="text/javascript" src="/common/lib/angular-filter/dist/angular-filter.js"></script>
    <script type="text/javascript" src="/common/lib/textAngular/src/textAngular-sanitize.js"></script><script type="text/javascript" src="/common/lib/textAngular/src/textAngularSetup.js"></script>
    <script type="text/javascript" src="/common/lib/textAngular/src/textAngular.js"></script>
    <script type="text/javascript" src="/admin/js/services/loginVerifyService.js"></script>
    <script type="text/javascript" src="/admin/js/services/filmEpisodeContentService.js"></script>
    <script type="text/javascript" src="/admin/js/filmEpisodes/filmEpisodesAdminApp.js"></script>
    <script type="text/javascript" src="/admin/js/controllers/filmEpisode/filmEpisodeListController.js"></script>
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

<div ng-controller="filmEpisodeListController">
    <table class="table">
        <tr>
            <td><b>Episode Name</b></td>
            <td><b>Film</b></td>
            <td><b>Episode No</b></td>
            <td><b>Show on landing Page</b></td>
            <td><b>Published</b></td>

            <td><b>Actions</b></td>
        </tr>
        <tr>

            <td><input ng-model="search.englishTransliteration" class="form-control"><br></td>
            <td><input ng-model="search.film.englishTransliteration" class="form-control"><br></td>
            <td><input ng-model="search.episodeNumber" class="form-control"><br></td>
            <td><input ng-model="search.showOnLandingPage" class="form-control"><br></td>
            <td><input ng-model="search.publish" class="form-control"><br></td>
        </tr>
        <tr ng-cloak ng-repeat="filmEpisode in filmEpisodes | orderBy:'episodeNumber' | filter:search">

            <td>{{filmEpisode.englishTransliteration}}</td>
            <td>{{filmEpisode.film.englishTransliteration}}</td>
            <td>{{filmEpisode.episodeNumber}}</td>
            <td>{{filmEpisode.showOnLandingPage}}</td>
            <td>{{filmEpisode.publish}}</td>
            <td>
                <a href="filmEpisodeDetails.html?id={{filmEpisode.id}}" class="btn btn-info">Edit</a>
                <a ng-click="deleteFilmEpisodes(filmEpisode.id)" class="btn btn-danger">Delete</a>
            </td>
        </tr>
    </table>

    <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
           
                  <tbody>
                  <tr>
                    <td >The Ayodhya Encounter</td>
                    <td>Had Anhad</td>
                    <td>1</td>
                    <td>No
                    </td>
                    <td>Yes
                    </td>
                    <!-- <td>Win 95+</td>
                    <td> 4</td> -->
                       <td><a href="details.html?id={{couplet.id}}" class="btn btn-info">Edit</a>&nbsp;&nbsp;&nbsp;
                       <a ng-click="deleteCouplet(couplet.id)" class="btn btn-danger">Delete</a></td>                  
                    </tr>
                  <tr>
                    <td>Secular or Sacred Kabir</td>
                    <td>Kabira Khada Baazaar Mein</td>
                    <td>1</td>
                    <td>No
                    </td>
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
</div>
</body>
</html>
