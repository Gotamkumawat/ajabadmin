<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['SongController/ajax_create_keyword'] = 'SongController/ajax_create_keyword';
$route['SongController/ajax_get_title_row'] = 'SongController/ajax_get_title_row';
$route['SongController/ajax_save_title_row'] = 'SongController/ajax_save_title_row';
$route['SongController/ajax_create_song'] = 'SongController/ajax_create_song';
$route['SongController/ajax_create_reflection'] = 'SongController/ajax_create_reflection';
$route['SongController/ajax_create_poem'] = 'SongController/ajax_create_poem';
$route['SongController/ajax_create_person'] = 'SongController/ajax_create_person';
$route['SongController/ajax_create_film'] = 'SongController/ajax_create_film';
$route['SongController/ajax_create_episode'] = 'SongController/ajax_create_episode';


/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['login'] = 'AuthController/login';
$route['auth/login'] = 'AuthController/login';
$route['authenticate'] = 'AuthController/authenticate';
$route['logout'] = 'AuthController/logout';
$route['default_controller'] = 'welcome';




$route['add_new'] = 'addNew/index';
$route['add-advance-form'] = 'addNew/addAdvanceForm';
$route['past-add-song'] = 'addNew/pastAddSong';

$route['add-songs'] = 'SongController/songs';
$route['add-song'] = 'SongController/add_songs';

$route['song-lists'] = 'SongController/songlists';
$route['edit-song'] = 'SongController/editSong';
$route['edit-song/(:num)'] = 'SongController/edit/$1'; // Load edit page with song ID
$route['update-song'] = 'SongController/update';     // Handle update form submit

$route['keywords'] = 'KeywordsController/keywords';
$route['add-keyword'] = 'KeywordsController/add_keywords';
$route['edit-keyword/(:num)'] = 'KeywordsController/edit/$1';
$route['delete-keyword/(:num)'] = 'KeywordsController/delete/$1';
$route['keywords/save'] = 'KeywordsController/save';
$route['keywords-lists'] = 'KeywordsController/keywordslists';
$route['fetch-keywords'] = 'KeywordsController/fetch_keywords';

$route['glossary-lists'] = 'GlossaryController/glossary_list';
$route['add-glossary'] = 'GlossaryController/add_glossary';
$route['glossary/edit/(:num)'] = 'GlossaryController/edit/$1';
$route['glossary/delete/(:num)'] = 'GlossaryController/delete/$1';
$route['glossary/save'] = 'GlossaryController/save';
$route['fetch-glossaries'] = 'GlossaryController/fetch_glossaries';

$route['playlist-lists'] = 'GlossaryController/playlist_list';
$route['add-playlist'] = 'GlossaryController/add_playlist';
$route['add-playlist/(:num)'] = 'GlossaryController/add_playlist/$1';
$route['playlist/save'] = 'GlossaryController/save_playlist';
$route['playlist/delete/(:num)'] = 'GlossaryController/delete_playlist/$1';
$route['fetch-playlists'] = 'GlossaryController/fetch_playlists';
$route['radio/ajax-create-playlist'] = 'GlossaryController/ajax_create_playlist';


// $route['song/save'] = 'SongController/save';
$route['add-couplet'] = 'addNew/couplet';
$route['fetch-couplets'] = 'CoupletController/fetch_couplets';

$route['add-word'] = 'addNew/word';
$route['word/save'] = 'WordController/save';
$route['word/edit/(:num)'] = 'WordController/edit/$1';
$route['word/update/(:num)'] = 'WordController/update/$1';
$route['word/delete/(:num)'] = 'WordController/delete/$1';

$route['add-reflection'] = 'addNew/reflection';
$route['reflection/edit/(:num)'] = 'ReflectionController/edit/$1';
$route['reflection/update/(:num)'] = 'ReflectionController/update/$1';
$route['reflection/delete/(:num)'] = 'ReflectionController/delete/$1';

$route['add-person'] = 'addNew/person';
$route['person/edit/(:num)'] = 'PersonController/edit/$1';
$route['person/update/(:num)'] = 'PersonController/update/$1';
$route['person/delete/(:num)'] = 'PersonController/delete/$1';
$route['person/occupation/create'] = 'PersonController/ajax_create_occupation';
$route['person/occupation/update'] = 'PersonController/ajax_update_occupation';
$route['add-occupation'] = 'OccupationController/add';
$route['occupation/edit/(:num)'] = 'OccupationController/add/$1';
$route['occupation/save'] = 'OccupationController/save';
$route['occupation/delete/(:num)'] = 'OccupationController/delete/$1';
$route['occupation-list'] = 'lists/occupations';
$route['fetch-occupations'] = 'OccupationController/fetch_occupations';

$route['add-film'] = 'addNew/film';
$route['add-about'] = 'addNew/about';
$route['add-story'] = 'addNew/story';

// $route['add-radio'] = 'addNew/radio';
// $route['radio-list'] = 'RadioController/radio_list';
// $route['fetch-radio'] = 'RadioController/fetch_radio';
$route['radio-list'] = 'RadioController/radio_list';
$route['fetch-radio'] = 'RadioController/fetch_radio';
$route['add-radio'] = 'RadioController/add_radio'; // same view for add/edit
$route['add-radio/(:num)'] = 'RadioController/add_radio/$1';
$route['radio/save'] = 'RadioController/save';
$route['radio/ajax_song_meta'] = 'RadioController/ajax_song_meta';
$route['radio/delete/(:num)'] = 'RadioController/delete/$1';




$route['add-resource'] = 'Resource/add';
$route['resource/add'] = 'resource/add';
$route['resource/edit/(:num)'] = 'resource/edit/$1';
$route['resource/delete/(:num)'] = 'resource/delete/$1';
$route['resource/list'] = 'resource/list';
$route['resource/fetch_resources'] = 'resource/fetch_resources';
$route['resource/save'] = 'resource/save';




// $route['add-upload'] = 'addNew/upload';
$route['add-upload'] = 'CartoonController/add_upload';
$route['add-filmDetails'] = 'addNew/Details';
$route['filmDetails/edit/(:num)'] = 'FilmController/edit/$1';
$route['filmDetails/update/(:num)'] = 'FilmController/update/$1';
$route['filmDetails/delete/(:num)'] = 'FilmController/delete/$1';
// $route['add-filmEpisodeDetails'] = 'addNew/filmEpisodeDetails';
// Add this route for edit
// $route['filmepisode/edit/(:num)'] = 'FilmController/editfilmep/$1';
$route['add-filmEpisodeDetails'] = 'FilmController/add_edit_filmEpisode'; 
$route['filmepisode/edit/(:num)'] = 'FilmController/add_edit_filmEpisode/$1'; 
$route['filmepisode/save'] = 'FilmController/save_filmEpisode';
$route['film/language/create'] = 'FilmController/ajax_create_language';
$route['film/series/save'] = 'FilmController/ajax_save_series';
$route['film/series/list'] = 'FilmController/ajax_list_series';

// Route for update form submission
$route['filmepisode/update'] = 'FilmController/update_filmEpisode';

$route['add-wordDetails'] = 'addNew/wordDetails';
$route['fetch-words'] = 'WordController/fetch_words';
$route['add-news'] = 'addNew/news';
$route['edit-news/(:num)'] = 'NewsController/edit/$1';

$route['delete-news/(:num)'] = 'NewsController/delete/$1';

$route['fetch-about-header'] = 'addAboutController/fetch_about_header';
$route['fetch-about-image'] = 'addAboutController/fetch_about_images';

$route['ajab-shahar'] = 'addNew/ajabShahar';
$route['ajab-shahar/edit/(:num)'] = 'addNew/ajabShahar/$1';
$route['ajab-shahar/save'] = 'AddAboutController/save_ajab_shahar';
$route['ajab-shahar/update/(:num)'] = 'AddAboutController/update_ajab_shahar/$1';
$route['ajab-shahar/menus'] = 'AddAboutController/get_ajab_menus';
$route['ajab-shahar/menus/create'] = 'AddAboutController/create_ajab_menu';
$route['ajab-shahar/menus/delete/(:num)'] = 'AddAboutController/delete_ajab_menu/$1';
$route['kabir-project'] = 'addNew/kabirProject';
$route['kabir-project/edit/(:num)'] = 'addNew/kabirProject/$1';
$route['kabir-project/save'] = 'AddAboutController/save_kabir_project';
$route['kabir-project/update/(:num)'] = 'AddAboutController/update_kabir_project/$1';
$route['kabir-project/menus'] = 'AddAboutController/get_kabir_menus';
$route['kabir-project/menus/create'] = 'AddAboutController/create_kabir_menu';
$route['kabir-project/menus/delete/(:num)'] = 'AddAboutController/delete_kabir_menu/$1';

// Generic dynamic About sections
$route['about-section/sections']                          = 'AboutSection/sections_list';
$route['about-section/sections/create']                   = 'AboutSection/sections_create';
$route['about-section/sections/delete/(:num)']            = 'AboutSection/sections_delete/$1';
$route['about-section/sections/update/(:num)']            = 'AboutSection/sections_update/$1';
$route['about-section/(:any)/menus']                      = 'AboutSection/menus_list/$1';
$route['about-section/(:any)/menus/create']               = 'AboutSection/menus_create/$1';
$route['about-section/(:any)/menus/delete/(:num)']        = 'AboutSection/menus_delete/$1/$2';
$route['about-section/(:any)/menus/update/(:num)']        = 'AboutSection/menus_update/$1/$2';
$route['about-section/(:any)/save']                       = 'AboutSection/save/$1';
$route['about-section/(:any)/update/(:num)']              = 'AboutSection/update/$1/$2';
$route['about-section/(:any)/edit/(:num)']                = 'AboutSection/index/$1/$2';
$route['about-section/(:any)']                            = 'AboutSection/index/$1';


$route['about-images'] = 'addNew/aboutImages';

$route['fetch-news'] = 'newsController/fetch_news';

$route['cartoon/add'] = 'CartoonController/add_upload';
$route['cartoon/edit/(:num)'] = 'CartoonController/add_upload/$1';
$route['cartoon/save'] = 'CartoonController/save';
$route['cartoon/update'] = 'CartoonController/update';
$route['fetch-cartoons'] = 'CartoonController/fetch_cartoons';
$route['cartoon/delete/(:num)'] = 'CartoonController/delete/$1';



$route['list'] = 'lists/index';
$route['sign-in'] = 'lists/SignIn';

$route['songs-list'] = 'lists/songs';
$route['fetch-songs'] = 'SongController/fetch_songs';
$route['song/edit/(:num)'] = 'SongController/edit/$1';

$route['song/update'] = 'SongController/update';
$route['song/update/(:num)'] = 'SongController/update/$1';
$route['song/save'] = 'SongController/save';
$route['song/delete/(:num)'] = 'SongController/delete/$1';
// Keyword AJAX endpoints (shared by Singer/Poet/Glossary add+edit popups across forms).
$route['song/ajax_create_keyword']        = 'SongController/ajax_create_keyword';
$route['song/ajax_update_keyword']        = 'SongController/ajax_update_keyword';
$route['song/ajax_get_keyword']           = 'SongController/ajax_get_keyword';
$route['song/ajax_get_person']            = 'SongController/ajax_get_person';
$route['song/ajax_get_glossary_word']     = 'SongController/ajax_get_glossary_word';
// Shared safe-delete used by Delete button next to Add/Edit on every select field.
$route['song/ajax_delete_entity']         = 'SongController/ajax_delete_entity';
$route['song/ajax_update_glossary_word']  = 'SongController/ajax_update_glossary_word';
$route['song/ajax_update_person']         = 'SongController/ajax_update_person';
$route['song/ajax_update_translator']     = 'SongController/ajax_update_translator';
$route['person/ajax-create'] = 'SongController/ajax_create_person';

$route['couplets-list'] = 'lists/couplets';
$route['couplet/save'] = 'CoupletController/save';
$route['edit-couplet/(:num)'] = 'CoupletController/edit/$1';
$route['couplet/update/(:num)'] = 'CoupletController/update/$1';
$route['delete-couplet/(:num)'] = 'CoupletController/delete/$1';

$route['words-list'] = 'lists/words';
$route['reflections-list'] = 'lists/reflections';
$route['fetch-reflections'] = 'ReflectionController/fetch_reflections';
$route['people-list'] = 'lists/people';
$route['fetch-person'] = 'PersonController/fetch_person';
$route['filmsSectionList'] = 'lists/films';
$route['about-list'] = 'lists/about';


$route['fetch-filmDetails'] = 'FilmController/fetch_filmDetails';
$route['stories-list'] = 'lists/stories';
$route['fetch-story'] = 'storyController/fetch_story';
// Edit / Update / Delete routes for story
$route['story/edit/(:num)'] = 'StoryController/edit/$1';
$route['story/update/(:num)'] = 'StoryController/update/$1';
$route['story/delete/(:num)'] = 'StoryController/delete/$1';


$route['resources-list'] = 'lists/resources';
$route['fetch-resources'] = 'resource/fetch_resources';

$route['add-contribute'] = 'Contribute/add';       // Add contribution page
$route['contribute/save'] = 'Contribute/save';
$route['contribute/edit/(:num)'] = 'Contribute/edit/$1'; // Edit contribution
$route['contribute/update/(:num)'] = 'Contribute/update/$1'; // Update contribution
$route['contribute/delete/(:num)'] = 'Contribute/delete/$1'; // Delete contribution
$route['contributions-list'] = 'Contribute/index';
$route['fetch-contributes'] = 'contribute/fetch_contributes';



// $route['echoes-list'] = 'lists/echoes';
$route['echoes-list'] = 'Echoes/index';
$route['fetch-echoes'] = 'Echoes/fetch_echoes';
$route['cartoons-list'] = 'lists/cartoons';
$route['filmList'] = 'lists/filmsSectionList';
$route['filmEpisodesList'] = 'lists/filmEpisodesList';
$route['filmDetails-list'] = 'lists/Details';
$route['filmEpisodeDetails-list'] = 'lists/EpisodeDetails';
$route['filmepisode/deleteFilmEpisode/(:num)'] = 'FilmController/deleteFilmEpisode/$1';

$route['signin'] = 'lists/songs';
// $route['fetch-echoes'] = 'addAboutController/fetch_about_header';
// $route['fetch-echoes'] = 'addAboutController/fetch_about_images';
$route['news-list'] = 'lists/list';
$route['ajab-share-list'] = 'lists/ajabShareList';
$route['kabir-project-list'] = 'lists/kabirProjectList';
$route['fetch-ajab-share-list'] = 'AddAboutController/fetch_ajab_share_list';
$route['fetch-kabir-project-list'] = 'AddAboutController/fetch_kabir_project_list';
$route['delete-ajab-share/(:num)'] = 'AddAboutController/delete_ajab_share/$1';
$route['delete-kabir-project/(:num)'] = 'AddAboutController/delete_kabir_project/$1';
$route['about-image-list'] = 'lists/aboutImageList';




$route['SongController/ajax_create_keyword'] = 'SongController/ajax_create_keyword';


$route['import-panel'] = 'ImportController/index';
$route['import-panel/import'] = 'ImportController/import';
$route['import-panel/import-all'] = 'ImportController/import_all';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


