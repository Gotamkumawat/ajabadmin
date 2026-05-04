<!DOCTYPE html>
<html ng-app="songsAdminApp">
<head lang="">
    <meta charset="UTF-8">
    <title>Song details - admin</title>
    <link rel="stylesheet" href="/admin/css/admin.css" type="text/css">
    <link rel="stylesheet" href="/common/lib/angular-multi-select/angular-multi-select.css">
    <link rel="stylesheet" href="/common/lib/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.min.css">
</head>
<body>
<a ng-href="add_new" target="_self">
    <button type="button" class="btn btn-info admin-home">Admin home</button>
</a>

<div class="container mt-3">
    <h2>Enter Song Details</h2>
    <form name="songForm" novalidate ng-controller="songDetailsController" ng-init="init()">

   <!-- Umbrella Title + Duration Row -->
            <div class="row form-group align-items-center">
                <div class="col-md-9">
                    <div class="form-group">
                        <label>Umbrella Title</label>
                        <as-admin-title title-data="song.umbrellaTitle" title-list="umbrellaTitles" style="width: 100%;"></as-admin-title>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Song Title - Transliteration</label>
                        <input type="text" name="songtitle" ng-model="song.duration" class="form-control" ng-required="song.isAuthoringComplete">
                        <error-message name="Duration" show-error="song.isAuthoringComplete && isEmpty(song.duration)"></error-message>
                    </div>
                </div>
            </div>

            <!-- Song Title Row -->
            <div class="row form-group align-items-center">
                <div class="col-md-9">
                    <div class="form-group">
                        <label>Song Title</label>
                        <as-admin-title title-data="song.songTitle" title-list="songTitles" validation-required="true" style="width: 100%;"></as-admin-title>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Song Title - Translated (required)</label>
                        <input type="text" name="songtitletraan" ng-model="song.duration" class="form-control" ng-required="song.isAuthoringComplete">
                        <error-message name="Duration" show-error="song.isAuthoringComplete && isEmpty(song.duration)"></error-message>
                    </div>
                </div>
            </div>

           




        <!-- Singers, Words, Reflections, Couplets -->
        <div class="row form-group">
            <div class="col-md-3">
                <label>Singer</label>
              <select name="" id="">None Selected</select>
            </div>
            <div class="col-md-3">
                <label>Words</label>
                <div multi-select input-model="words" button-label="wordTransliteration" item-label="wordTransliteration" tick-property="selected" output-model="song.words"></div>
            </div>
            <div class="col-md-3">
                <label>Reflections</label>
                <div multi-select input-model="reflections" button-label="title" item-label="title" tick-property="selected" output-model="song.reflections"></div>
            </div>
            <div class="col-md-3">
                <label>Couplets</label>
                <div multi-select input-model="couplets" button-label="englishTransliteration" item-label="englishTransliteration" tick-property="selected" output-model="song.couplets"></div>
            </div>
        </div>

        <!-- Films, Episodes, Related Stories, Related People -->
        <div class="row form-group">
            <div class="col-md-3">
                <label>Films</label>
                <div multi-select input-model="films" button-label="englishTransliteration" item-label="englishTransliteration" tick-property="selected" output-model="song.films"></div>
            </div>
            <div class="col-md-3">
                <label>Film Episode</label>
                <div multi-select input-model="episodes" button-label="englishTransliteration" item-label="englishTransliteration" tick-property="selected" output-model="song.episodes"></div>
            </div>
            <div class="col-md-3">
                <label>Related Stories</label>
                <div multi-select input-model="stories" button-label="mainTitle" item-label="mainTitle" tick-property="ticked" output-model="song.stories"></div>
            </div>
            <div class="col-md-3">
                <label>Related People</label>
                <div multi-select input-model="people" button-label="name" item-label="name" tick-property="selected" output-model="song.people"></div>
            </div>
        </div>

        <!-- Related Songs, Gathering, Show on LandingPage, Song Category -->
        <div class="row form-group">
            <div class="col-md-3">
                <label>Related Songs</label>
                <div multi-select input-model="relatedSongs" button-label="menuTitle" item-label="menuTitle" tick-property="ticked" output-model="formInfo.relatedSongs"></div>
            </div>
            <div class="col-md-3">
                <label>Gatherings</label>
                <select ng-model="song.gathering" ng-options="gathering.english for gathering in gatherings" class="form-control"></select>
            </div>
            <div class="col-md-3">
                <label>Show On LandingPage</label>
                <select name="format" ng-model="song.showOnLandingPage" ng-init="song.showOnLandingPage = 'false'" class="form-control">
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Song Category</label>
                <select ng-model="song.songCategory" ng-options="category.name for category in songCategories" class="form-control" ng-required="song.isAuthoringComplete"></select>
                <error-message name="Song category" show-error="song.isAuthoringComplete && isEmpty(song.songCategory)"></error-message>
            </div>
        </div>

        <!-- Duration, Youtube, SoundCloud, Thumbnail -->
        <div class="row form-group">
            <div class="col-md-3">
                <label>Duration</label>
                <input type="text" name="duration" ng-model="song.duration" class="form-control" ng-required="song.isAuthoringComplete">
                <error-message name="Duration" show-error="song.isAuthoringComplete && isEmpty(song.duration)"></error-message>
            </div>
            <div class="col-md-3">
                <label>Youtube Video ID</label>
                <input type="text" name="youtubeVideoId" ng-model="song.youtubeVideoId" class="form-control" ng-required="song.isAuthoringComplete && isMediaUrlEmpty()">
                <error-message name="Youtube URL or SoundCloud" show-error="song.isAuthoringComplete && isMediaUrlEmpty()"></error-message>
            </div>
            <div class="col-md-3">
                <label>SoundCloud track Url</label>
                <input type="text" name="soundCloudTrackID" ng-model="song.soundCloudTrackId" class="form-control" ng-required="song.isAuthoringComplete && isMediaUrlEmpty()">
                <error-message name="Youtube URL or SoundCloud" show-error="song.isAuthoringComplete && isMediaUrlEmpty()"></error-message>
            </div>
           <div class="col-md-3">
                <label>Thumbnail Url</label>
                <input type="file" name="thumbnailUrl" ng-model="song.thumbnailURL" class="form-control" ng-required="song.isAuthoringComplete">
                <error-message name="Thumbnail URL" show-error="song.isAuthoringComplete && isEmpty(song.thumbnailURL)"></error-message>
            </div>
            <div class="col-md-3">
                <label>Thumbnail Excerpt (required)</label>
                <input type="text" name="thumbnailexcerpt" ng-model="song.thumbnailexcerpt" class="form-control" ng-required="song.isAuthoringComplete && isMediaUrlEmpty()">
                <error-message name="Youtube URL or SoundCloud" show-error="song.isAuthoringComplete && isMediaUrlEmpty()"></error-message>
            </div>
            
        </div>

        <!-- Download URL, Genres -->
        <div class="row form-group">
            <div class="col-md-3">
                <label>Download Url</label>
                <input type="text" name="downloadUrl" ng-model="song.downloadURL" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Genres</label>
                <div multi-select input-model="genres" button-label="english" item-label="english" tick-property="selected" output-model="song.songGenre"></div>
                <input name="genres" ng-model="song.songGenre" ng-show="false">
            </div>
        </div>

        <!-- Lyrics Sections -->
 <div class="row form-group">
    <div class="col-md-2"><label>Song Lyrics - Original</label></div>
    <div class="col-md-10">
        <div class="card">
            <div class="card-body">
                <text-angular name="songLyricsOriginal" ng-model="song.songText.original"></text-angular>
            </div>
        </div>
    </div>
</div>
        <div class="row form-group">
            <div class="col-md-2"><label>Song Lyrics - Transliteration</label></div>
            <div class="col-md-10"><text-angular name="songLyricsTransliteration" ng-model="song.songText.transliteration"></text-angular></div>
        </div>
        <div class="row form-group">
            <div class="col-md-2"><label>Song Lyrics - Translation</label></div>
            <div class="col-md-10"><text-angular name="songLyricsTranslation" ng-model="song.songText.translation"></text-angular></div>
        </div>

        <!-- About -->
        <div class="row form-group">
            <div class="col-md-2"><label>About</label></div>
            <div class="col-md-10"><text-angular name="htmlcontent" ng-model="song.about"></text-angular></div>
        </div>
      <!-- Song Notes, Glossary & Reflection Row -->
            <div class="row form-group align-items-center">
                <div class="col-md-3">
                    <label>Song Notes</label>
                    <input type="text" name="songnotes" ng-model="song.songnotes" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Song Glossary</label>
                    <input type="text" name="songglossary" ng-model="song.songglossary" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Meta Title</label>
                    <input type="text" name="metaTitle" ng-model="song.metaTitle" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Meta Keyword</label>
                    <input type="text" name="metaKeyword" ng-model="song.metaKeywords" class="form-control">
                </div>
            </div>

            <!-- Reflection Checkbox Row -->
            <div class="row form-group">
                <div class="col-md-12 d-flex align-items-center">
                    <label class="mr-2 mb-0">Is this also a Reflection?</label>
                    <input type="checkbox" name="reflection" ng-model="song.reflection" style="width:auto; height:auto;">
                </div>
            </div>


        <div class="row form-group">
            <div class="col-md-2"><label>Meta Description</label></div>
            <div class="col-md-6"><textarea class="form-control" name="metaDescription" ng-model="song.metaDescription" rows="5"></textarea></div>
        </div>

        <!-- Publish -->
        <div class="row form-group">
            <div class="col-md-2"><label>Publish</label></div>
            <div class="col-md-2">
                <select class="form-control" ng-init='authoringOptions=[{"value":true,"text":"Yes"},{"value":false,"text":"No"}]'
                        ng-model="song.isAuthoringComplete"
                        ng-options="authoringOption.value as authoringOption.text for authoringOption in authoringOptions">
                </select>
            </div>
        </div>

        <br/>
        <button type="submit" ng-click="saveData()" ng-disabled="!songForm.$valid" class="btn btn-primary btn-lg">Save</button>

    </form>
</div>

<script type="text/javascript" src="/common/lib/angular/angular.min.js"></script>
<script type="text/javascript" src="/common/lib/angular-multi-select/angular-multi-select.js"></script>
<script type="text/javascript" src="/common/lib/underscore/underscore-min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.25/angular-cookies.js"></script>
<script type="text/javascript" src="/common/lib/rangy/rangy-core.min.js"></script>
<script type="text/javascript" src="/common/lib/rangy/rangy-classapplier.min.js"></script>
<script type="text/javascript" src="/common/lib/angular-filter/dist/angular-filter.min.js"></script>
<script type="text/javascript" src="/common/lib/textAngular/src/textAngular-sanitize.js"></script>
<script type="text/javascript" src="/common/lib/textAngular/src/textAngularSetup.js"></script>
<script type="text/javascript" src="/common/lib/textAngular/src/textAngular.js"></script>
<script type="text/javascript" src="/admin/js/services/songContentService.js"></script>
<script type="text/javascript" src="/admin/js/services/loginVerifyService.js"></script>
<script type="text/javascript" src="/admin/js/common/app.js"></script>
<script type="text/javascript" src="/admin/js/Songs/songsAdminApp.js"></script>
<script type="text/javascript" src="/admin/js/common/errorMessage.js"></script>
<script type="text/javascript" src="/admin/js/controllers/songs/songDetailsController.js"></script>
<script type="text/javascript" src="/admin/js/directives/asAdminTitle.js"></script>
<script type="text/javascript" src="/admin/js/participate/angular-base64.js"></script>
</body>
</html>

