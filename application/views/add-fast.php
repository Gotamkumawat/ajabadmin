<?php 
include('inc/header.php');
include('inc/sidebar.php');
?>
<head>
        <link rel="stylesheet" href="/common/lib/angular-multi-select/angular-multi-select.css">

</head>
<style>
    .container {
        width: 300px;
        margin: 50px auto;
        font-family: Arial, sans-serif;
    }

    select, input, textarea {
        width: 100%;
        padding: 8px;
        margin: 10px 0;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .multi-select-container {
        position: relative;
    }

    .popup-dropdown {
        display: none;
        position: absolute;
        top: 30px;
        left: 0;
        width: 100%;
        background-color: white;
        border: 1px solid #ccc;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 999;
        padding: 10px;
        max-height: 150px;
        overflow-y: auto;
    }

    .multi-select-container input[type="checkbox"] {
        margin-right: 10px;
    }

    .multi-select-container .action-buttons {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .action-buttons button {
        padding: 5px 10px;
        cursor: pointer;
        font-size: 14px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
    }

    .action-buttons .reset {
        background-color: #f44336;
    }

    .search-box {
        width: 100%;
        padding: 5px;
    }

    .selected-items {
        padding: 10px;
        border: 1px solid #ccc;
        margin-top: 10px;
    }

    /* Additional CSS for new form sections */
    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        font-size: 14px;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 6px 12px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    textarea.form-control {
        resize: vertical;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 6px 20px;
        font-size: 16px;
        border-radius: 4px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .btn-primary:disabled {
        background-color: #6c757d;
        border-color: #6c757d;
        cursor: not-allowed;
    }

    .d-flex.align-items-center {
        display: flex;
        align-items: center;
    }

    .mr-2 {
        margin-right: 0.5rem;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    input[type="checkbox"] {
        width: auto;
        height: auto;
        margin-right: 10px;
    }

    .select2-container .select2-selection--multiple {
    min-height: 38px;
    display: flex !important;
    align-items: center !important;
    font-size: 14px !important; /* 👈 placeholder छोटा */
    border: 1px solid #ccc !important;
    border-radius: 4px !important;
}

.select2-selection__placeholder {
    font-size: 14px !important; /* 👈 placeholder छोटा */
    line-height: normal !important;
    color: #777 !important;
}

    

    .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
        margin: 1px 2px !important;
        padding: 2px 4px !important;
    }
    .cke_notification{
    	display:none;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Enter News Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="add_new">Home</a></li>
                        <li class="breadcrumb-item active">Add News Details</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
                <div class="card-header"></div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form name="songForm">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            	<div class="row">
                            		
								</div>
                            </div>
                    	</div>
                    	<div class="row">
                            <!-- /.col -->
                            <div class="col-md-6">
                                    <!-- <div class="form-group">
                                        <label>Song Title - Transliteration</label>
                                        <input type="text" name="songtitle" ng-model="song.duration" class="form-control" ng-required="song.isAuthoringComplete">
                                        <error-message name="Duration" show-error="song.isAuthoringComplete && isEmpty(song.duration)"></error-message>
                                    </div> -->
                                </div>
                            <div class="col-md-6">
                                <!-- /.form-group -->
                                <!-- <div class="form-group">
                                    <label>Song Title - Translated (required)</label>
                                    <input type="text" name="songtitletraan" ng-model="song.duration" class="form-control" ng-required="song.isAuthoringComplete">
                                    <error-message name="Duration" show-error="song.isAuthoringComplete && isEmpty(song.duration)"></error-message>
                                </div> -->
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- Singers, Words, Reflections, Couplets -->
                        <div class="row form-group">
                            <div class="col-md-3">
    <label>Pop Up Item</label>
    <select class="form-control" id="popupSelect">
        <option value="">Select Pop Up Item</option>
        <option value="1">New Year Offer</option>
        <option value="2">Festive Discount</option>
        <option value="3">Limited Time Sale</option>
        <option value="4">Special Announcement</option>
        <option value="5">New Song Released</option>
        <option value="6">Website Update</option>
        <option value="7">Event Invitation</option>
        <option value="8">Exclusive Access</option>
        <option value="9">Feature Launch</option>
        <option value="10">Maintenance Notice</option>
    </select>
</div>


                            <div class="col-md-3">
    <label>Thumbnail Image</label>
    <select class="form-control" id="yearSelect">
        <option value="">Select Thumbnail</option>
        <option value="1">sunrise_thumbnail.jpg</option>
        <option value="2">mountain_view.png</option>
        <option value="3">forest_night.jpg</option>
        <option value="4">river_flow.png</option>
        <option value="5">city_lights.jpg</option>
        <option value="6">temple_view.jpg</option>
        <option value="7">rainy_day.png</option>
        <option value="8">sunset_horizon.jpg</option>
        <option value="9">garden_flowers.png</option>
        <option value="10">ocean_wave.jpg</option>
    </select>
</div>

                            <div class="col-md-3">
    <label>News Title</label>
    <div class="multi-select-container">
        <input 
            type="text" 
            name="thumbnailexcerpt" 
            ng-model="song.thumbnailexcerpt" 
            class="form-control" 
            placeholder="Enter News Title"
            ng-required="song.isAuthoringComplete && isMediaUrlEmpty()">
            
        <error-message 
            name="Youtube URL or SoundCloud" 
            show-error="song.isAuthoringComplete && isMediaUrlEmpty()">
        </error-message>
    </div>
</div>


                            <div class="col-md-3">
                                <label>News Second Title</label>
                                <div class="multi-select-container">
                                    <input type="text" name="thumbnailexcerpt" placeholder="Enter News Second Title" ng-model="song.thumbnailexcerpt" class="form-control" ng-required="song.isAuthoringComplete && isMediaUrlEmpty() ">
                                <error-message name="Youtube URL or SoundCloud" show-error="song.isAuthoringComplete && isMediaUrlEmpty()"></error-message>
                                    
                                </div>
                            </div>

                             

                            <!-- Location Input -->
                            

                            <div class="col-md-3">
    <label>News Content</label>
    <div class="multi-select-container">
        <select class="select2" multiple="multiple" data-placeholder="Enter News Content">
            <option value="">None Selected</option>
            <option value="1">Government launches new digital policy</option>
            <option value="2">Major rainfall expected this weekend</option>
            <option value="3">Stock market hits all-time high</option>
            <option value="4">New metro line inaugurated in Delhi</option>
            <option value="5">Schools to reopen from next Monday</option>
            <option value="6">New electric vehicle model unveiled</option>
            <option value="7">PM announces startup funding program</option>
            <option value="8">City marathon attracts 20,000 runners</option>
            <option value="9">Scientists discover new species of flower</option>
            <option value="10">Gold prices rise amid global tensions</option>
            <option value="11">Local temple celebrates annual festival</option>
            <option value="12">Farmers demand fair crop pricing</option>
            <option value="13">New AI technology launched in India</option>
            <option value="14">Tourism industry sees record growth</option>
            <option value="15">Flood relief operations continue</option>
            <option value="16">Sports league finals to be held tomorrow</option>
            <option value="17">New express highway inaugurated</option>
            <option value="18">Health ministry warns about rising cases</option>
            <option value="19">Education board releases new syllabus</option>
            <option value="20">Major cyberattack prevented by authorities</option>
            <option value="21">Government introduces new tax reforms</option>
            <option value="22">Celebrity donates to charity foundation</option>
            <option value="23">Railway announces special holiday trains</option>
            <option value="24">Fuel prices likely to decrease soon</option>
            <option value="25">City park to undergo renovation</option>
            <option value="26">Vaccination drive extended till next month</option>
            <option value="27">Weather department predicts heavy winds</option>
            <option value="28">University announces new scholarship plan</option>
            <option value="29">Local art exhibition opens for public</option>
            <option value="30">Major tech company opens new office</option>
            <option value="31">Traffic restrictions announced for parade</option>
            <option value="32">Firefighters rescue 10 people from blaze</option>
            <option value="33">Government to boost rural employment</option>
            <option value="34">New wildlife sanctuary declared</option>
            <option value="35">Public transport fares reduced by 10%</option>
            <option value="36">Cricket team wins international series</option>
            <option value="37">Power outage affects several districts</option>
            <option value="38">Farmers’ market to open this weekend</option>
            <option value="39">Education minister visits local schools</option>
            <option value="40">Rain causes flight delays in metro cities</option>
            <option value="41">New smartphone brand enters Indian market</option>
            <option value="42">Water supply to be restored by evening</option>
            <option value="43">Public awareness campaign launched</option>
            <option value="44">Major IT firm announces hiring plans</option>
            <option value="45">Temple renovation project begins</option>
            <option value="46">Health awareness week starts today</option>
            <option value="47">Government launches clean river mission</option>
            <option value="48">Election commission releases new schedule</option>
            <option value="49">Weather improves after long monsoon</option>
            <option value="50">Traffic police introduce new rules</option>
        </select>
    </div>
</div>

                            
                            <div class="col-md-3">
    <label>Published (y/n)</label>
    <div class="multi-select-container">
        <select class="select2" multiple="multiple" data-placeholder="Enter Published (y/n)">
            <option value="">None Selected</option>
            <option value="1">Yes (Published)</option>
            <option value="2">No (Unpublished)</option>
            <option value="3">Pending Review</option>
            <option value="4">Draft</option>
            <option value="5">Archived</option>
        </select>

        <div class="popup-dropdown" style="display:none;">
            <input type="text" class="search-box" placeholder="Search..." onkeyup="filterOptions(this)">
            <div class="action-buttons" style="margin-top: 10px; display: flex; gap: 8px;">
                <button onclick="selectAll(this)" style="padding:5px 12px; font-size:13px;border-radius:5px;background-color:#007bff;color:#fff;border:none;cursor:pointer;">Select All</button>
                <button onclick="selectNone(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#17a2b8;color:#fff;border:none;cursor:pointer;">Select None</button>
                <button onclick="resetSelection(this)" style="padding:5px 12px;font-size:13px;border-radius:5px;background-color:#dc3545;color:#fff;border:none;cursor:pointer;">Reset</button>
            </div>

            <div class="optionsList" style="margin-top:10px;">
                <label><input type="checkbox" value="1"> ✅ Yes (Published)</label><br>
                <label><input type="checkbox" value="2"> ❌ No (Unpublished)</label><br>
                <label><input type="checkbox" value="3"> ⏳ Pending Review</label><br>
                <label><input type="checkbox" value="4"> 📝 Draft</label><br>
                <label><input type="checkbox" value="5"> 📦 Archived</label><br>
            </div>
        </div>
    </div>
</div>



                            <div class="col-md-3">
                                <label>Ajab News Content</label>
                                <div class="multi-select-container">
                                    <input type="text" name="thumbnailexcerpt" placeholder="Enter Ajab News Content" ng-model="song.thumbnailexcerpt" class="form-control" ng-required="song.isAuthoringComplete && isMediaUrlEmpty()">
                                <error-message name="Youtube URL or SoundCloud" show-error="song.isAuthoringComplete && isMediaUrlEmpty()"></error-message>
                                    
                                </div>
                            </div>
                            
                            
                            
                            
                            <div class="col-md-3">
    <label>Published (y/n)</label>
    <div class="multi-select-container">
        <select class="form-control" ng-model="publishedStatus">
            <option value="">Select Option</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
    </div>
</div>

                            


                        <!-- Reflection Checkbox Row -->
                <div class="row form-group">
                     <!-- Reflection -->
                    <!-- <div class="col-md-4">
                        <label>Is this also a Reflection?</label>
                        <div>
                            <input type="checkbox" name="reflection" style="width:auto; height:auto;">
                        </div>
                    </div> -->
                </div>
                        <br/>


                    </form>
                </div>
                                        <div style="display: flex; justify-content: flex-end;">
                            <button type="submit" ng-click="saveData()" ng-disabled="!songForm.$valid" class="btn btn-primary btn-lg">
                                Save
                            </button>
                            </div>

                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

                <script>
                function togglePopup(selectElem) {
                    const popup = selectElem.parentElement.querySelector('.popup-dropdown');
                    // Close all other popups
                    document.querySelectorAll('.popup-dropdown').forEach(p => {
                        if (p !== popup) p.style.display = 'none';
                    });
                    // Toggle this one
                    popup.style.display = (popup.style.display === 'block') ? 'none' : 'block';
                }

                function filterOptions(inputElem) {
                    const filter = inputElem.value.toLowerCase();
                    const options = inputElem.parentElement.querySelectorAll('.optionsList label');
                    options.forEach(label => {
                        label.style.display = label.textContent.toLowerCase().includes(filter) ? '' : 'none';
                    });
                }

                function selectAll(button) {
                    const checkboxes = button.closest('.popup-dropdown').querySelectorAll('input[type="checkbox"]');
                    checkboxes.forEach(cb => cb.checked = true);
                }

                function selectNone(button) {
                    const checkboxes = button.closest('.popup-dropdown').querySelectorAll('input[type="checkbox"]');
                    checkboxes.forEach(cb => cb.checked = false);
                }

                function resetSelection(button) {
                    const checkboxes = button.closest('.popup-dropdown').querySelectorAll('input[type="checkbox"]');
                    checkboxes.forEach(cb => cb.checked = false);
                    const selectElem = button.closest('.multi-select-container').querySelector('select');
                    selectElem.value = '';
                }
                
                </script>
             <script>
                // Year dropdown fill karna
                const yearSelect = document.getElementById('yearSelect');
                const currentYear = new Date().getFullYear();

                for(let y = currentYear; y >= 1900; y--){
                    const option = document.createElement('option');
                    option.value = y;
                    option.text = y;
                    yearSelect.appendChild(option);
                }
            </script>
<script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>

                <script>
                        setTimeout(function () {

                            // Initialize all 4 editors (add all textarea IDs here)
                            const editorIDs = [
                                'songLyricsOriginal',       // 1️⃣
                                'songLyricsTranslated',     // 2️⃣
                                'songLyricsNotes',          // 3️⃣
                                'songLyricsMeaning'         // 4️⃣
                            ];

                            editorIDs.forEach(function (id) {
                                CKEDITOR.replace(id, {
                                    height: 200,
                                    extraPlugins: 'colorbutton,font,justify',
                                    toolbar: [
                                        { name: 'document', items: [ 'Source', '-', 'NewPage', 'Preview' ] },
                                        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat' ] },
                                        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                                        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
                                        { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                                        { name: 'colors', items: [ 'TextColor', 'BGColor' ] }
                                    ]
                                });

                                // AngularJS model sync
                                CKEDITOR.instances[id].on('change', function () {
                                    var data = CKEDITOR.instances[id].getData();
                                    var scope = angular.element(document.querySelector('[id="' + id + '"]')).scope();
                                    scope.$apply(function () {
                                        if (id === 'songLyricsOriginal') {
                                            scope.song.songText.original = data;
                                        } else if (id === 'songLyricsTranslated') {
                                            scope.song.songText.translated = data;
                                        } else if (id === 'songLyricsNotes') {
                                            scope.song.songText.notes = data;
                                        } else if (id === 'songLyricsMeaning') {
                                            scope.song.songText.meaning = data;
                                        }
                                    });
                                });
                            });

                        }, 500);
                        </script>

<script type="text/javascript" src="/common/lib/angular/angular.min.js"></script>
<script type="text/javascript" src="/common/lib/angular-multi-select/angular-multi-select.js"></script>
<script type="text/javascript" src="/common/lib/textAngular/src/textAngular-sanitize.js"></script>
<script type="text/javascript" src="/common/lib/textAngular/src/textAngularSetup.js"></script>
<script type="text/javascript" src="/common/lib/textAngular/src/textAngular.js"></script>
<?php 
include('inc/footer.php');
?>