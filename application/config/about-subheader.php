<!DOCTYPE html>
<html ng-app="aboutSubheaderAdminApp" ng-controller="aboutSubheaderDetailsController">
<head lang="">
    <meta charset="UTF-8">
    <title ng-cloak>About Subheader {{pageName}} - admin</title>
    <link href="/admin/css/admin.css" rel="stylesheet" type="text/css">
    <link href="/common/lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/common/lib/angular-multi-select/angular-multi-select.css">
    <link rel='stylesheet' href='https://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.min.css'>
</head>
<body ng-init="getAboutSubheaderData()">

<div>
    <a ng-href="/admin/partials/aboutSection/aboutDetails.html" target="_self">
        <button type="button" class="btn btn-info admin-home" style="margin-left: 83%;">About home</button>
    </a>
</div>

<div>
    <a ng-href="/admin/partials/home.html" target="_self">
        <button type="button" class="btn btn-info admin-home">Admin home</button>
    </a>
</div>

<div class="left">
    <h2 ng-cloak>{{pageName}} About Subheader Details</h2>

    <div class="alert alert-danger" role="alert" ng-show="alert.length != 0">
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
        <span class="sr-only" ng-cloak>Error:</span>
        {{alert}}
    </div>
    <div class="row">
        <div class="form-group col-sm-2">
            <label>Header Name</label>
        </div>
        <div class="form-group col-sm-3">
            <input type="text" name="subHeaderName" ng-model="formInfo.subHeaderName" class="form-control" required>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-2">
            <label>Header Text</label>
        </div>
        <div class="col-sm-8">
            <text-angular name="subHeaderText" ng-model="formInfo.subHeaderText"></text-angular>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-2">
            <label>Order No</label>
        </div>
<!--        <div class="form-group col-sm-3">
            <input type="text" name="sortOrderNo" ng-model="formInfo.sortOrderNo" class="form-control" required>
        </div>-->
        <div class="form-group col-sm-3">
            <select ng-model="formInfo.sortOrderNo"
                    ng-options="number for number in orderNumbers"
                    class="form-control"  >
                <option value=""></option>
            </select>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label>About Header</label>
        </div>
        <div class="col-sm-2">
            <select class="form-control col-sm-5" ng-model="formInfo.aboutHeaderId"
                    ng-options="aboutHeader as aboutHeader.headerName for aboutHeader in aboutHeaderList track by aboutHeader.id"></select>
        </div>
        <div class="clearfix"> </div>

            <div class="form-group col-sm-2">
                <label>Publish</label>
            </div>
            <div class="form-group col-sm-2">
                <select name="isPublished" ng-model="formInfo.isPublished" class="form-control"
                        ng-init="formInfo.isPublished = formInfo.isPublished || false">
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                </select>
            </div>
        
    </div>

    <button type="submit" ng-click="saveData()" class="btn btn-primary btn-lg">Save</button>
</div>
<script src="/common/lib/angular/angular.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.25/angular-cookies.js"></script>
<script type="text/javascript" src="/admin/js/services/aboutHeaderContentService.js"></script>
<script type="text/javascript" src="/admin/js/services/aboutSubheaderContentService.js"></script>
<script type="text/javascript" src="/admin/js/services/loginVerifyService.js"></script>

<script type="text/javascript" src="/common/lib/angular-filter/dist/angular-filter.js"></script>
<script type="text/javascript" src="/common/lib/textAngular/src/textAngular-sanitize.js"></script><script type="text/javascript" src="/common/lib/textAngular/src/textAngularSetup.js"></script>
<script type="text/javascript" src="/common/lib/textAngular/src/textAngular.js"></script>

<script type="text/javascript" src="/common/lib/rangy/rangy-core.min.js"></script>
<script type="text/javascript" src="/common/lib/rangy/rangy-classapplier.min.js"></script>

<script type="text/javascript" src="/admin/js/aboutSubheaders/aboutSubheaderAdminApp.js"></script>
<script type="text/javascript" src="/admin/js/controllers/aboutSubheader/aboutSubheaderDetailsController.js"></script>
</body>
</html>
