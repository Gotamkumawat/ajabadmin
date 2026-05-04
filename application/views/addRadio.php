<!DOCTYPE html>
<html ng-app="radioAdminApp">

<head lang="en">
  <meta charset="UTF-8">
  <title>Radio details page</title>
  <link href="/admin/css/admin.css" rel="stylesheet" type="text/css">
  <link href="/common/lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="/admin/css/radio-details.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="/common/lib/angular-multi-select/angular-multi-select.css">
  <link rel='stylesheet' href='https://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.min.css'>
</head>

<body>
  <a ng-href="/admin/partials/home.html" target="_self">
    <button type="button" class="btn btn-info admin-home">Admin home</button>
  </a>

  <div class="left" style="opacity:0; height:0px; overflow:hidden;">
    <h2 ng-cloak>{{pageName}} Radio Track Details</h2>

    <div class="left">
      <div>
        <form name="radioForm" ng-controller="radioDetailsController" ng-init="init()">


          <div class="form-group">

            <div class="col-sm-2">

            </div>
            <div class="col-sm-12" style="padding-left:0px">

              <div  style="margin-bottom:10px">
                <div class="col-xs-8 col-xs-offset-2">
                  <div class="col-sm-7">
                    <label> Sound Cloud TrackId </label>
                    <input type="text" ng-model="formInfo.soundCloudUrl" class="form-control">
                  </div>
                  <div class="col-sm-7">
                    <label> Song Url on Ajab Shahar </label>
                    <input type="text" ng-model="formInfo.songUrl" class="form-control">
                  </div>
                  <div class="col-sm-7">
                    <label> Singer Name </label>
                    <input type="text" ng-model="formInfo.singerName" class="form-control">
                  </div>
                  <div class="col-sm-7">
                    <label> Singer Profile </label>
                    <input type="text" ng-model="formInfo.singerProfile" class="form-control">
                  </div>
                  <div class="col-sm-7">
                    <label> Singer ProfileUrl on Ajab Shahar</label>
                    <input type="text" ng-model="formInfo.profileUrl" class="form-control">
                  </div>
                  <div class="col-sm-7">
                    <label>Singer Contact Info </label>
                    <input type="text" ng-model="formInfo.singerContact" class="form-control">
                  </div>
                  <div class="col-sm-7">
                    <label> Shop url </label>
                    <input type="text" ng-model="formInfo.shopUrl" class="form-control">
                  </div>
                  <div class="col-sm-7">
                    <label>Download url </label>
                    <input type="text" ng-model="formInfo.downloadUrl" class="form-control">
                  </div>
                  <div class="col-sm-7">
                    <div class="form-group">
                      <label>Publish</label>
                      <select name="isPublished" ng-model="formInfo.isPublished" class="form-control" ng-init="formInfo.isPublished = formInfo.isPublished || false">
                        <option value="true">Yes</option>
                        <option value="false">No</option>
                      </select>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="col-sm-7 admin-ratio-delete-button" ng-hide="formInfo.id == null" >
                    <button type="delete-audio" ng-click="deleteRadioTrack(formInfo.id)" class="btn btn-danger btn-sm">Delete</button>
                  </div>
                </div>
                <hr>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div>
            <button type="submit" ng-click="saveData()" ng-disabled="!radioForm.$valid" class="btn btn-primary btn-lg">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="/common/lib/underscore/underscore.js"></script>
  <script src="/common/lib/angular/angular.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.25/angular-cookies.js"></script>
  <script type="text/javascript" src="/common/lib/angular-multi-select/angular-multi-select.js"></script>
  <script type="text/javascript" src="/common/lib/angular-filter/dist/angular-filter.js"></script>
  <script type="text/javascript" src="/common/lib/textAngular/src/textAngular-sanitize.js"></script><script type="text/javascript" src="/common/lib/textAngular/src/textAngularSetup.js"></script>
  <script type="text/javascript" src="/common/lib/textAngular/src/textAngular.js"></script>
  <script type="text/javascript" src="/common/lib/rangy/rangy-core.min.js"></script>
  <script type="text/javascript" src="/common/lib/rangy/rangy-classapplier.min.js"></script>
  <script src="/common/lib/angular-route/angular-route.min.js"></script>
  <script type="text/javascript" src="/admin/js/services/contentService.js"></script>
  <script type="text/javascript" src="/admin/js/services/loginVerifyService.js"></script>
  <script type="text/javascript" src="/admin/js/common/app.js"></script>
  <script type="text/javascript" src="/admin/js/common/errorMessage.js"></script>
  <script type="text/javascript" src="/admin/js/controllers/couplets/angular-bootstrap-multiselect.js"></script>
  <script type="text/javascript" src="/admin/js/services/radioContentService.js"></script>
  <script type="text/javascript" src="/admin/css/angular-bootstrap-toggle.js"></script>
  <script type="text/javascript" src="/admin/js/radio/radioApp.js"></script>

  <script type="text/javascript" src="/admin/js/controllers/radio/radioDetailsController.js"></script>
  <iframe src="../../../player/uploader/upload.php?key=993025ed5c09ccbba3d8e31c55edec54" style="width:100%; height:95%; position:fixed; top:40px; bottom:0px; border:none;"

</body>

</html>

