
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

  
    .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
        margin: 1px 2px !important;
        padding: 2px 4px !important;
    }
    .cke_notification{
    	display:none;
    }
 .select2-container .select2-selection--multiple {
    /* min-height: 38px; */
    height: 5px;
    padding-top: 2px;
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

.save-btn-container {

    display: flex;
    justify-content: flex-end; /* Button right side me */
    margin-bottom: 0px; /* Neeche thoda space */
}

.save-btn {
    position: relative;
    top: -20px; /* 👉 button ko upar karega (value badha/samjha ke adjust kar sakte ho) */
    padding: 8px 30px;
    font-size: 16px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>About Image</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="add_new">Home</a></li>
                        <li class="breadcrumb-item active">Add About Image</li>
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
                    	<div class="row">
                            <!-- /.col -->
                       
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- Singers, Words, Reflections, Couplets -->
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>Thumbnail URL</label>
                                <input type="text" name="duration" ng-model="song.duration" class="form-control" ng-required="song.isAuthoringComplete" placeholder="Enter Thumbnail URL">
                                <error-message name="Duration" show-error="song.isAuthoringComplete && isEmpty(song.duration)"></error-message>
                             </div>
                          <div class="col-md-3">
                                <label>Description</label>
                                <input type="text" name="duration" ng-model="song.duration" class="form-control" ng-required="song.isAuthoringComplete" placeholder="Enter Header Text">
                                <error-message name="Duration" show-error="song.isAuthoringComplete && isEmpty(song.duration)"></error-message>
                             </div>
                             
                            <div class="col-md-3">
                              <label>Order No</label>
                              <select name="showOnLandingPage" ng-model="song.showOnLandingPage" class="form-control" style="height: 38px;">
                                  <option value="">Select</option>
                                  <option value="true">1</option>
                                  <option value="false">2</option>
                                  <option value="true">3</option>
                                  <option value="false">4</option>
                                  <option value="true">5</option>
                                  <option value="false">6</option>
                                  <option value="true">7</option>
                                  <option value="false">8</option>
                              </select>
                          </div>

                          <div class="col-md-3">
                              <label>About Header</label>
                              <select name="showOnLandingPage" ng-model="song.showOnLandingPage" class="form-control" style="height: 38px;">
                                  <option value="">Select</option>
                                  <option value="true">About us</option>
                                  <option value="false">How we do</option>
                                  <option value="false">Who we are</option>
                              </select>
                          </div>
                          
                            <div class="col-md-3">
                              <label>Publish</label>
                              <select name="showOnLandingPage" ng-model="song.showOnLandingPage" class="form-control" style="height: 38px;">
                                  <option value="">Select</option>
                                  <option value="true">Yes</option>
                                  <option value="false">No</option>
                              </select>
                          </div>
                            

                         </div>
                        <div style="display: flex; justify-content: flex-end;">
                            <button type="submit" ng-click="saveData()" ng-disabled="!songForm.$valid" class="btn btn-primary btn-lg">
                                Save
                            </button>
                        </div>
</div>
                        <!-- Reflection Checkbox Row -->
                        <br/>
                        <!-- <button type="submit" ng-click="saveData()" ng-disabled="!songForm.$valid" class="btn btn-primary btn-lg">Save</button> -->
                         
                    </form>

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