<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FilmController extends CI_Controller {

    // Word limits enforced on the server side to mirror the frontend caps in
    // application/views/inc/footer.php (LIMIT_RULES). Keep these in sync.
    const SERIES_DESCRIPTION_WORD_LIMIT = 100;

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Database connect
        $this->load->helper('url');
        $this->load->model('FilmModel'); // Model load
        $this->load->model('WordModel');
        $this->load->library('session'); // Load session library for flash messages
    }

    /**
     * Trim a string to at most $limit whitespace-separated words.
     * Mirrors the frontend hard cap so submissions that bypass the UI
     * (curl, browser tools, etc.) still respect the limit.
     */
    private function clamp_words($value, $limit) {
        $s = trim((string) $value);
        if ($s === '' || $limit <= 0) {
            return $s;
        }
        // Collapse internal whitespace to a single space for an accurate word count.
        $normalized = preg_replace('/\s+/u', ' ', $s);
        $words = explode(' ', $normalized);
        if (count($words) <= $limit) {
            return $s;
        }
        return implode(' ', array_slice($words, 0, $limit));
    }

  public function add_film() {
        $this->load->view('add-filmDetails');
    }

    public function save() {
        // Edit submission must update, not insert
        $editId = (int) $this->input->post('id');
        if ($editId > 0) {
            $this->update($editId);
            return;
        }
        // Handle thumbnail upload
        $thumbnail_Image = '';
        if (!empty($_FILES['thumbnail_Image']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/thumbnails/'; // Folder path
            if (!is_dir($config['upload_path'])) { @mkdir($config['upload_path'], 0755, true); }
            $config['allowed_types'] = 'jpg|jpeg|png|gif|webp|avif';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['thumbnail_Image']['name']);
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('thumbnail_Image')) {
                $uploadData = $this->upload->data();
                $thumbnail_Image = 'uploads/thumbnails/' . $uploadData['file_name'];
            } else {
                // Handle error, maybe set flashdata
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('add-filmDetails');
                return;
            }
        } else {
            $thumbnail_Image = $this->input->post('thumbnail_Image'); // Fallback if no upload
        }

        $languages = $this->input->post('film_language');
        $youtubeLinks = $this->input->post('film_language_youtube_link');
        $languageRows = [];
        if (is_array($languages) || is_array($youtubeLinks)) {
            $max = max(is_array($languages) ? count($languages) : 0, is_array($youtubeLinks) ? count($youtubeLinks) : 0);
            for ($i = 0; $i < $max; $i++) {
                $lang = is_array($languages) && isset($languages[$i]) ? trim((string)$languages[$i]) : '';
                $link = is_array($youtubeLinks) && isset($youtubeLinks[$i]) ? trim((string)$youtubeLinks[$i]) : '';
                if ($lang === '' && $link === '') {
                    continue;
                }
                $languageRows[] = ['language' => $lang, 'youtube_link' => $link];
            }
        }
        $primaryYoutube = '';
        foreach ($languageRows as $row) {
            if (!empty($row['youtube_link'])) {
                $primaryYoutube = (string)$row['youtube_link'];
                break;
            }
        }

        $data = array(
            'main_title' => $this->input->post('main_title'),
            'second_title' => $this->input->post('second_title'),
            'series_title' => $this->input->post('series_title'),
            'series_description' => $this->clamp_words($this->input->post('series_description'), self::SERIES_DESCRIPTION_WORD_LIMIT),
            'thumbnail_Image' => $thumbnail_Image,
            'thumbnail_excerpt' => $this->input->post('thumbnail_excerpt'),
            'directors' => is_array($this->input->post('directors')) ? implode(',', $this->input->post('directors')) : ($this->input->post('directors') ?? ''),
            'related_primary_songs' => is_array($this->input->post('related_primary_songs')) ? implode(',', $this->input->post('related_primary_songs')) : ($this->input->post('related_primary_songs') ?? ''),
            'related_keywords' => is_array($this->input->post('related_keywords')) ? implode(',', $this->input->post('related_keywords')) : ($this->input->post('related_keywords') ?? ''),
            'related_words' => is_array($this->input->post('related_words')) ? implode(',', $this->input->post('related_words')) : ($this->input->post('related_words') ?? ''),
            'related_people' => is_array($this->input->post('related_people')) ? implode(',', $this->input->post('related_people')) : ($this->input->post('related_people') ?? ''),
            'related_couplets' => is_array($this->input->post('related_couplets')) ? implode(',', $this->input->post('related_couplets')) : ($this->input->post('related_couplets') ?? ''),
            'related_reflections' => is_array($this->input->post('related_reflections')) ? implode(',', $this->input->post('related_reflections')) : ($this->input->post('related_reflections') ?? ''),
            'related_poems' => is_array($this->input->post('related_poems')) ? implode(',', $this->input->post('related_poems')) : ($this->input->post('related_poems') ?? ''),           
            'films' => is_array($this->input->post('films')) ? implode(',', $this->input->post('films')) : ($this->input->post('films') ?? ''),
            'film_episodes' => is_array($this->input->post('film_episodes')) ? implode(',', $this->input->post('film_episodes')) : ($this->input->post('film_episodes') ?? ''),
            'related_stories' => is_array($this->input->post('related_stories')) ? implode(',', $this->input->post('related_stories')) : ($this->input->post('related_stories') ?? ''),
            'film_youtube_id' => $primaryYoutube !== '' ? $primaryYoutube : $this->input->post('film_youtube_id'),
            'film_language_links' => !empty($languageRows) ? json_encode($languageRows) : '',
            'duration' => $this->input->post('duration'),
            'year' => $this->input->post('year'),
            'about' => $this->input->post('about'),      
            'publish' => $this->input->post('publish'),
            'meta_title' => $this->input->post('meta_title'),
            'meta_keyword' => $this->input->post('meta_keyword'),
            'meta_description' => $this->input->post('meta_description'),
            'date_of_upload' => date('Y-m-d H:i:s') // Always set current date/time
        );

        $insert = $this->FilmModel->insert_film($data);

        if ($insert) {
            $this->session->set_flashdata('success', 'Film details saved successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to save film details: ' . $this->db->_error_message());
        }

        redirect('add-filmDetails');
    }

    public function edit($id) {
        $data['film'] = $this->FilmModel->get_film_by_id($id);
        $data['is_edit'] = true;
        if (!$data['film']) {
            $this->session->set_flashdata('error', 'Film not found!');
            redirect('filmDetails-list');
        }
        $this->load->view('add-filmDetails', $data);
    }

    public function update($id) {
        @file_put_contents(FCPATH . 'film_update_debug.log',
            "[".date('Y-m-d H:i:s')."] film update id=$id\n"
            ."POST keys: ".implode(',', array_keys($_POST))."\n"
            ."POST[related_keywords]=".(isset($_POST['related_keywords'])?var_export($_POST['related_keywords'],true):'(unset)')."\n"
            ."POST[related_songs]=".(isset($_POST['related_songs'])?var_export($_POST['related_songs'],true):'(unset)')."\n"
            ."POST[related_poems]=".(isset($_POST['related_poems'])?var_export($_POST['related_poems'],true):'(unset)')."\n"
            ."POST[related_reflections]=".(isset($_POST['related_reflections'])?var_export($_POST['related_reflections'],true):'(unset)')."\n"
            ."POST[related_people]=".(isset($_POST['related_people'])?var_export($_POST['related_people'],true):'(unset)')."\n"
            ."POST[directors]=".(isset($_POST['directors'])?var_export($_POST['directors'],true):'(unset)')."\n"
            ."---\n", FILE_APPEND);
        // Handle thumbnail upload
        $thumbnail_Image = '';
        if (!empty($_FILES['thumbnail_Image']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/thumbnails/'; // Folder path
            if (!is_dir($config['upload_path'])) { @mkdir($config['upload_path'], 0755, true); }
            $config['allowed_types'] = 'jpg|jpeg|png|gif|webp|avif';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['thumbnail_Image']['name']);
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('thumbnail_Image')) {
                $uploadData = $this->upload->data();
                $thumbnail_Image = 'uploads/thumbnails/' . $uploadData['file_name'];
            } else {
                // Handle error, maybe set flashdata
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('filmDetails-list');
                return;
            }
        } else {
            $thumbnail_Image = $this->input->post('thumbnail_Image'); // Fallback if no upload
        }

        $languages = $this->input->post('film_language');
        $youtubeLinks = $this->input->post('film_language_youtube_link');
        $languageRows = [];
        if (is_array($languages) || is_array($youtubeLinks)) {
            $max = max(is_array($languages) ? count($languages) : 0, is_array($youtubeLinks) ? count($youtubeLinks) : 0);
            for ($i = 0; $i < $max; $i++) {
                $lang = is_array($languages) && isset($languages[$i]) ? trim((string)$languages[$i]) : '';
                $link = is_array($youtubeLinks) && isset($youtubeLinks[$i]) ? trim((string)$youtubeLinks[$i]) : '';
                if ($lang === '' && $link === '') {
                    continue;
                }
                $languageRows[] = ['language' => $lang, 'youtube_link' => $link];
            }
        }
        $primaryYoutube = '';
        foreach ($languageRows as $row) {
            if (!empty($row['youtube_link'])) {
                $primaryYoutube = (string)$row['youtube_link'];
                break;
            }
        }

        $data = array(
            'main_title' => $this->input->post('main_title'),
            'second_title' => $this->input->post('second_title'),
            'series_title' => $this->input->post('series_title'),
            'series_description' => $this->clamp_words($this->input->post('series_description'), self::SERIES_DESCRIPTION_WORD_LIMIT),
            'thumbnail_Image' => $thumbnail_Image,
            'thumbnail_excerpt' => $this->input->post('thumbnail_excerpt'),
            'directors' => is_array($this->input->post('directors')) ? implode(',', $this->input->post('directors')) : ($this->input->post('directors') ?? ''),
            'related_primary_songs' => is_array($this->input->post('related_primary_songs')) ? implode(',', $this->input->post('related_primary_songs')) : ($this->input->post('related_primary_songs') ?? ''),
            'related_keywords' => is_array($this->input->post('related_keywords')) ? implode(',', $this->input->post('related_keywords')) : ($this->input->post('related_keywords') ?? ''),
            'related_words' => is_array($this->input->post('related_words')) ? implode(',', $this->input->post('related_words')) : ($this->input->post('related_words') ?? ''),
            'related_people' => is_array($this->input->post('related_people')) ? implode(',', $this->input->post('related_people')) : ($this->input->post('related_people') ?? ''),
            'related_couplets' => is_array($this->input->post('related_couplets')) ? implode(',', $this->input->post('related_couplets')) : ($this->input->post('related_couplets') ?? ''),
            'related_reflections' => is_array($this->input->post('related_reflections')) ? implode(',', $this->input->post('related_reflections')) : ($this->input->post('related_reflections') ?? ''),
            'related_poems' => is_array($this->input->post('related_poems')) ? implode(',', $this->input->post('related_poems')) : ($this->input->post('related_poems') ?? ''),
            'films' => is_array($this->input->post('films')) ? implode(',', $this->input->post('films')) : ($this->input->post('films') ?? ''),
            'film_episodes' => is_array($this->input->post('film_episodes')) ? implode(',', $this->input->post('film_episodes')) : ($this->input->post('film_episodes') ?? ''),
            'related_stories' => is_array($this->input->post('related_stories')) ? implode(',', $this->input->post('related_stories')) : ($this->input->post('related_stories') ?? ''),
            'film_youtube_id' => $primaryYoutube !== '' ? $primaryYoutube : $this->input->post('film_youtube_id'),
            'film_language_links' => !empty($languageRows) ? json_encode($languageRows) : '',
            'duration' => $this->input->post('duration'),
            'year' => $this->input->post('year'),
            'about' => $this->input->post('about'),
            'publish' => $this->input->post('publish'),
            'meta_title' => $this->input->post('meta_title'),
            'meta_keyword' => $this->input->post('meta_keyword'),
            'meta_description' => $this->input->post('meta_description'),
            'date_of_upload' => date('Y-m-d H:i:s')
        );

        $update = $this->FilmModel->update_film($id, $data);

        if ($update) {
            $this->session->set_flashdata('success', 'Film details updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update film details: ' . $this->db->_error_message());
        }

        redirect('filmDetails-list');
    }

    public function delete($id) {
        $delete = $this->FilmModel->delete_film($id);
        if ($delete) {
            echo json_encode(['status' => 'success', 'message' => 'Film deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete film: ' . $this->db->_error_message()]);
        }
    }

    public function fetch_filmDetails() {
        // Film details list source: film table.
        $films = $this->FilmModel->fetch_all();
        $data = [];
        $sl_no = 1;
        foreach ($films as $f) {
            $mainTitle = isset($f->main_title) && $f->main_title !== '' ? $f->main_title : (isset($f->english_transliteration) ? $f->english_transliteration : '');
            $seriesTitle = isset($f->series_title) && $f->series_title !== '' ? $f->series_title : (isset($f->english_translation) ? $f->english_translation : '');
            $publish = isset($f->publish) ? $f->publish : (isset($f->is_published) ? $f->is_published : '');
            $data[] = [
                'id' => $f->id,
                'sl_no' => $sl_no++,
                'date_of_upload' => $f->date_of_upload ?? date('Y-m-d H:i:s'),
                'main_title' => $mainTitle,
                'series_title' => $seriesTitle,
                'publish' => $publish,
            ];
        }

        echo json_encode(['data' => $data]);
    }


    ///////////////// Film Episode Details Functions /////////////////

    public function add_films() {
        $this->load->view('add-filmEpisodeDetails'); // Load the view file (add-film.php)
    }



    
       public function fetch_filmEpisode() {
        $episodes = $this->FilmModel->fetch_data();
        $data = [];
        $sl_no = 1;
        foreach ($episodes as $ep) {
            $mainTitle = isset($ep->main_title_label) && trim((string)$ep->main_title_label) !== ''
                ? $ep->main_title_label
                : (isset($ep->main_title) ? $ep->main_title : '');
            $publish = isset($ep->publish) ? $ep->publish : (isset($ep->is_published) ? ((string)$ep->is_published === '1' ? 'true' : 'false') : 'false');
            $data[] = [
                'id' => $ep->id,
                'sl_no' => $sl_no++,
                'date_of_upload' => !empty($ep->date_of_upload) ? date('d-m-Y H:i', strtotime($ep->date_of_upload)) : '—',
                'main_title' => $mainTitle,
                'episode_no' => $ep->episode_no ?? '',
                'film_episode_title' => $ep->film_episode_title ?? '',
                'publish' => $publish,
            ];
        }
        echo json_encode(['data' => $data]);
    }

     public function editfilmep($id) {
            $data['filmEpisode'] = $this->FilmModel->get_filmEpisode_by_id($id);

            if (!$data['filmEpisode']) {
                $this->session->set_flashdata('error', 'Film Episode not found!');
                redirect('filmEpisodeDetails-list');
            }

            $this->load->view('add-filmEpisodeDetails', $data);
        }

 public function update_filmEpisode() {
    $id = $this->input->post('id');
    if (!$id) {
        $this->session->set_flashdata('error', 'Invalid Request! ID not found.');
        redirect('filmEpisodeDetails-list');
        return;
    }

    // ✅ 1. Thumbnail Upload Handling
    $thumbnail_image = $this->input->post('old_thumbnail_image'); // पुरानी image by default
    if (!empty($_FILES['thumbnail_image_upload']['name'])) {
        $config['upload_path'] = FCPATH . 'uploads/thumbnails/';
        if (!is_dir($config['upload_path'])) { @mkdir($config['upload_path'], 0755, true); }
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp|avif';
        $config['file_name'] = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['thumbnail_image_upload']['name']);

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('thumbnail_image_upload')) {
            // नई image upload हो गई
            $uploadData = $this->upload->data();
            $thumbnail_image = 'uploads/thumbnails/' . $uploadData['file_name'];

            // पुरानी image delete (optional)
            $old_image_path = './uploads/thumbnails/' . $this->input->post('old_thumbnail_image');
            if (file_exists($old_image_path) && is_file($old_image_path)) {
                unlink($old_image_path);
            }
        } else {
            // upload fail
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('add-filmEpisodeDetails/' . $id);
            return;
        }
    }

    // ✅ 2. Prepare Data Array (mapped to correct database columns)
    $data = [
        'film_episode_title' => $this->input->post('film_episode_title'),
        'main_title' => $this->input->post('main_title'),
        'episode_no' => $this->input->post('episode_no'),
        'thumbnail_image_upload' => $thumbnail_image,
        'thumbnail_excerpt' => $this->input->post('thumbnail_excerpt'),
        'duration' => $this->input->post('duration'),
        'year' => $this->input->post('year'),
        'about_text' => $this->input->post('about_text'),
        'youtube_id' => $this->input->post('youtube_id'),
        'publish' => $this->input->post('publish'),
        'meta_title' => $this->input->post('meta_title'),
        'meta_keyword' => $this->input->post('meta_keywords') ?? $this->input->post('meta_keyword'),
        'meta_description' => $this->input->post('meta_description'),
    ];

    // ✅ 3. Update Database
    $updated = $this->FilmModel->update_filmEpisode($id, $data);

    if ($updated) {
        $this->session->set_flashdata('success', '🎬 Film Episode updated successfully!');
    } else {
        $this->session->set_flashdata('error', '⚠️ Failed to update Film Episode!');
    }

    redirect('filmEpisodeDetails-list');
}


                public function add_edit_filmEpisode($id = null) {
                    $data = [];

                    if ($id) {
                        $data['filmEpisode'] = $this->FilmModel->get_filmEpisode_by_id($id);
                        $data['is_edit'] = true;
                    } else {
                        $data['filmEpisode'] = null;
                        $data['is_edit'] = false;
                    }

                    $this->load->view('add-filmEpisodeDetails', $data);
                }
public function save_filmEpisode() {
    $id = $this->input->post('id');
    $thumbnail_image = null;

    // 1. Image Upload Logic
    if (!empty($_FILES['thumbnail_image_upload']['name'])) {
        $config['upload_path'] = FCPATH . 'uploads/thumbnails/';
        if (!is_dir($config['upload_path'])) { @mkdir($config['upload_path'], 0755, true); }
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp|avif';
        $config['file_name'] = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['thumbnail_image_upload']['name']);

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('thumbnail_image_upload')) {
            $uploadData = $this->upload->data();
            $thumbnail_image = 'uploads/thumbnails/' . $uploadData['file_name'];
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('add-filmEpisodeDetails');
            return;
        }
    } else {
        $thumbnail_image = $this->input->post('old_thumbnail_image') ?? 'default.jpg';
    }

    // 2. Data array (scalar + related fields used by sync_episode_relations)
    $arrToCsv = function ($v) {
        if (is_array($v)) return implode(',', array_filter($v, function ($x) { return trim((string)$x) !== ''; }));
        return (string)($v ?? '');
    };
    $data = [
        'film_episode_title' => $this->input->post('film_episode_title'),
        'main_title' => $this->input->post('main_title'),
        'episode_no' => $this->input->post('episode_no'),
        'thumbnail_image_upload' => $thumbnail_image,
        'thumbnail_excerpt' => $this->input->post('thumbnail_excerpt'),
        'duration' => $this->input->post('duration'),
        'year' => $this->input->post('year'),
        'about_text' => $this->input->post('about_text'),
        'youtube_link' => $this->input->post('youtube_link'),
        'publish' => $this->input->post('publish'),
        'meta_title' => $this->input->post('meta_title'),
        'meta_keyword' => $this->input->post('meta_keywords') ?? $this->input->post('meta_keyword'),
        'meta_description' => $this->input->post('meta_description'),
        // Related-content arrays for junction sync
        'related_keywords'    => $arrToCsv($this->input->post('related_keywords')),
        'related_songs'       => $arrToCsv($this->input->post('related_songs')),
        'related_poems'       => $arrToCsv($this->input->post('related_poems')),
        'related_reflections' => $arrToCsv($this->input->post('episode_related_reflections') ?: $this->input->post('related_reflections')),
        'related_people'      => $arrToCsv($this->input->post('episode_related_people') ?: $this->input->post('related_people')),
        'related_films'       => $arrToCsv($this->input->post('episode_related_films') ?: $this->input->post('related_films')),
        'date_of_upload' => date('Y-m-d H:i:s') // Always set current date/time
    ];

    // Remove any keys with null values (optional, but keeps SQL clean)
    $data = array_filter($data, function($v) { return $v !== null; });

    if ($id) {
        $this->FilmModel->update_filmEpisode($id, $data);
        $this->session->set_flashdata('success', '🎬 Film Episode updated successfully!');
        redirect('filmEpisodeDetails-list');
    } else {
        $this->FilmModel->insert_filmEpicode($data);
        $this->session->set_flashdata('success', '🎥 Film Episode added successfully!');
        redirect('add-filmDetails');
    }

}


public function deleteFilmEpisode($id)
{
    if ($this->FilmModel->deleteFilmEpisode($id)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

public function ajax_create_language() {
    $this->output->set_content_type('application/json');

    $language_name = trim((string)$this->input->post('language_name', true));
    if ($language_name === '') {
        echo json_encode(['status' => 'error', 'message' => 'Language name is required']);
        return;
    }

    if (!$this->db->table_exists('category')) {
        echo json_encode(['status' => 'error', 'message' => 'category table not found']);
        return;
    }

    $existing = $this->db
        ->select('id, name')
        ->from('category')
        ->where('category_type', 'film_language')
        ->where('LOWER(TRIM(name)) =', strtolower($language_name))
        ->get()
        ->row_array();

    if (!empty($existing)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Language already exists',
            'language_id' => (string)$existing['id'],
            'language_name' => (string)$existing['name']
        ]);
        return;
    }

    $inserted = $this->db->insert('category', [
        'name' => $language_name,
        'category_type' => 'film_language'
    ]);

    if (!$inserted) {
        $db_error = $this->db->error();
        $message = !empty($db_error['message']) ? $db_error['message'] : 'Failed to add language';
        echo json_encode(['status' => 'error', 'message' => $message]);
        return;
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Language added successfully',
        'language_id' => (string)$this->db->insert_id(),
        'language_name' => $language_name
    ]);
}

    /**
     * Add song from film/episode "Add New" (JSON body: { "name": "..." }) — stores in `songs` table.
     */
    public function ajax_add_song() {
        $this->output->set_content_type('application/json');
        $title = '';
        $raw = json_decode((string) file_get_contents('php://input'), true);
        if (is_array($raw) && isset($raw['name'])) {
            $title = trim((string) $raw['name']);
        }
        if ($title === '') {
            $title = trim((string) $this->input->post('umbrellaTitle'));
        }
        if ($title === '') {
            echo json_encode(['status' => 'error', 'message' => 'Song title required']);
            return;
        }
        
        // Check if songs table exists
        if (!$this->db->table_exists('songs')) {
            echo json_encode(['status' => 'error', 'message' => 'songs table not found']);
            return;
        }
        
        // Check for duplicate
        $existing = $this->db->where('umbrellaTitle', $title)->get('songs')->row_array();
        if (!empty($existing)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Song already exists',
                'id' => (string) $existing['id'],
                'umbrellaTitle' => $title
            ]);
            return;
        }
        
        // Insert new song
        $data = [
            'umbrellaTitle' => $title,
            'date_of_upload' => date('Y-m-d H:i:s')
        ];
        
        $inserted = $this->db->insert('songs', $data);
        if (!$inserted) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add song']);
            return;
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Song added successfully',
            'id' => (string) $this->db->insert_id(),
            'umbrellaTitle' => $title
        ]);
    }

    /**
     * Add poem from film/episode "Add New" (JSON body: { "name": "..." }) — stores in `couplet` table.
     */
    public function ajax_add_poem() {
        $this->output->set_content_type('application/json');
        $title = '';
        $raw = json_decode((string) file_get_contents('php://input'), true);
        if (is_array($raw) && isset($raw['name'])) {
            $title = trim((string) $raw['name']);
        }
        if ($title === '') {
            $title = trim((string) $this->input->post('original_title'));
        }
        if ($title === '') {
            echo json_encode(['status' => 'error', 'message' => 'Poem title required']);
            return;
        }
        
        // Check if couplet table exists
        if (!$this->db->table_exists('couplet')) {
            echo json_encode(['status' => 'error', 'message' => 'couplet table not found']);
            return;
        }
        
        // Check for duplicate
        $existing = $this->db->where('original_title', $title)->get('couplet')->row_array();
        if (!empty($existing)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Poem already exists',
                'id' => (string) $existing['id'],
                'original_title' => $title
            ]);
            return;
        }
        
        // Insert new poem
        $data = [
            'original_title' => $title,
            'date_of_upload' => date('Y-m-d H:i:s')
        ];
        
        $inserted = $this->db->insert('couplet', $data);
        if (!$inserted) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add poem']);
            return;
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Poem added successfully',
            'id' => (string) $this->db->insert_id(),
            'original_title' => $title
        ]);
    }

    /**
     * Add reflection from film/episode "Add New" (JSON body: { "name": "..." }) — stores in `reflection` table.
     */
    public function ajax_add_reflection() {
        $this->output->set_content_type('application/json');
        $title = '';
        $raw = json_decode((string) file_get_contents('php://input'), true);
        if (is_array($raw) && isset($raw['name'])) {
            $title = trim((string) $raw['name']);
        }
        if ($title === '') {
            $title = trim((string) $this->input->post('title'));
        }
        if ($title === '') {
            echo json_encode(['status' => 'error', 'message' => 'Reflection title required']);
            return;
        }
        
        // Check if reflection table exists
        if (!$this->db->table_exists('reflection')) {
            echo json_encode(['status' => 'error', 'message' => 'reflection table not found']);
            return;
        }
        
        // Check for duplicate
        $existing = $this->db->where('title', $title)->get('reflection')->row_array();
        if (!empty($existing)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Reflection already exists',
                'id' => (string) $existing['id'],
                'title' => $title
            ]);
            return;
        }
        
        // Insert new reflection
        $data = [
            'title' => $title,
            'date_of_upload' => date('Y-m-d H:i:s')
        ];
        
        $inserted = $this->db->insert('reflection', $data);
        if (!$inserted) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add reflection']);
            return;
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Reflection added successfully',
            'id' => (string) $this->db->insert_id(),
            'title' => $title
        ]);
    }

    /**
     * Add film from film/episode "Add New" (JSON body: { "name": "..." }) — stores in `film` table.
     */
    public function ajax_add_film() {
        $this->output->set_content_type('application/json');
        $title = '';
        $raw = json_decode((string) file_get_contents('php://input'), true);
        if (is_array($raw) && isset($raw['name'])) {
            $title = trim((string) $raw['name']);
        }
        if ($title === '') {
            $title = trim((string) $this->input->post('main_title'));
        }
        if ($title === '') {
            echo json_encode(['status' => 'error', 'message' => 'Film title required']);
            return;
        }
        
        // Check if film table exists
        if (!$this->db->table_exists('film')) {
            echo json_encode(['status' => 'error', 'message' => 'film table not found']);
            return;
        }
        
        // Check for duplicate
        $existing = $this->db->where('english_transliteration', $title)->or_where('main_title', $title)->get('film')->row_array();
        if (!empty($existing)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Film already exists',
                'id' => (string) $existing['id'],
                'main_title' => $title
            ]);
            return;
        }
        
        // Insert new film
        $data = [
            'english_transliteration' => $title,
            'main_title' => $title,
            'date_of_upload' => date('Y-m-d H:i:s')
        ];
        
        $inserted = $this->db->insert('film', $data);
        if (!$inserted) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add film']);
            return;
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Film added successfully',
            'id' => (string) $this->db->insert_id(),
            'main_title' => $title
        ]);
    }

    /**
     * Add film episode from film/episode "Add New" (JSON body: { "name": "..." }) — stores in `film_episode` table.
     */
    public function ajax_add_film_episode() {
        $this->output->set_content_type('application/json');
        $title = '';
        $raw = json_decode((string) file_get_contents('php://input'), true);
        if (is_array($raw) && isset($raw['name'])) {
            $title = trim((string) $raw['name']);
        }
        if ($title === '') {
            $title = trim((string) $this->input->post('film_episode_title'));
        }
        if ($title === '') {
            echo json_encode(['status' => 'error', 'message' => 'Film episode title required']);
            return;
        }
        
        // Check if film_episode table exists
        if (!$this->db->table_exists('film_episode')) {
            echo json_encode(['status' => 'error', 'message' => 'film_episode table not found']);
            return;
        }
        
        // Check for duplicate
        $existing = $this->db->where('english_transliteration', $title)->or_where('film_episode_title', $title)->get('film_episode')->row_array();
        if (!empty($existing)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Film episode already exists',
                'id' => (string) $existing['id'],
                'film_episode_title' => $title
            ]);
            return;
        }
        
        // Insert new film episode
        $data = [
            'english_transliteration' => $title,
            'film_episode_title' => $title,
            'date_of_upload' => date('Y-m-d H:i:s')
        ];
        
        $inserted = $this->db->insert('film_episode', $data);
        if (!$inserted) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add film episode']);
            return;
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Film episode added successfully',
            'id' => (string) $this->db->insert_id(),
            'film_episode_title' => $title
        ]);
    }

    /**
     * Add keyword from film/episode "Add New" (JSON body: { "name": "..." }) — stores in `word` table.
     */
    public function ajax_add_keyword() {
        $this->output->set_content_type('application/json');
        $word = '';
        $raw = json_decode((string) file_get_contents('php://input'), true);
        if (is_array($raw) && isset($raw['name'])) {
            $word = trim((string) $raw['name']);
        }
        if ($word === '') {
            $word = trim((string) $this->input->post('word_transliteration'));
        }
        if ($word === '') {
            echo json_encode(['status' => 'error', 'message' => 'Keyword required']);
            return;
        }
        $row = $this->WordModel->get_or_create_word_keyword($word);
        if ($row === null) {
            echo json_encode([
                'status' => 'error',
                'message' => $this->db->table_exists('word') ? 'Failed to save keyword' : 'word table not found',
            ]);
            return;
        }
        echo json_encode([
            'status' => 'success',
            'id' => (string) $row['id'],
            'word_transliteration' => $row['word_transliteration'],
            'message' => 'Keyword added successfully',
        ]);
    }

    // ===================================================================
    //  Series master (film_series table) — AJAX endpoints for the
    //  "Add New Series" popup on the Film Details form.
    // ===================================================================

    /**
     * Insert/update a series in the film_series master table and return it.
     * Called by the popup's Save button so a new series persists immediately.
     */
    public function ajax_save_series() {
        $this->output->set_content_type('application/json');

        $title = trim((string) $this->input->post('series_title', true));
        $desc  = (string) $this->input->post('series_description'); // allow HTML/long text

        if ($title === '') {
            echo json_encode(['status' => 'error', 'message' => 'Series title is required']);
            return;
        }
        if (!$this->db->table_exists('film_series')) {
            echo json_encode(['status' => 'error', 'message' => 'film_series table not found']);
            return;
        }

        // Cap title to the column width (varchar(100)).
        $title = mb_substr($title, 0, 100);

        $existing = $this->db->select('id')->from('film_series')
            ->where('LOWER(TRIM(series_title)) =', strtolower($title))
            ->get()->row_array();

        if (!empty($existing)) {
            // Update the description on the existing series.
            $this->db->where('id', (int) $existing['id'])
                     ->update('film_series', ['series_description' => $desc]);
            $id = (int) $existing['id'];
        } else {
            $this->db->insert('film_series', [
                'series_title'       => $title,
                'series_description' => $desc,
            ]);
            $id = (int) $this->db->insert_id();
            if ($id <= 0) {
                $err = $this->db->error();
                echo json_encode(['status' => 'error', 'message' => !empty($err['message']) ? $err['message'] : 'Failed to save series']);
                return;
            }
        }

        echo json_encode([
            'status'             => 'success',
            'id'                 => (string) $id,
            'series_title'       => $title,
            'series_description' => $desc,
            'message'            => 'Series saved',
        ]);
    }

    /**
     * Return all series (title + description) so the form can populate the
     * dropdown and auto-fill the description when a title is selected.
     */
    public function ajax_list_series() {
        $this->output->set_content_type('application/json');
        $items = [];
        if ($this->db->table_exists('film_series')) {
            $rows = $this->db->select('id, series_title, series_description')
                ->from('film_series')
                ->order_by('series_title', 'ASC')
                ->get()->result_array();
            foreach ($rows as $r) {
                $items[] = [
                    'id'                 => (string) $r['id'],
                    'series_title'       => (string) $r['series_title'],
                    'series_description' => (string) ($r['series_description'] ?? ''),
                ];
            }
        }
        echo json_encode(['status' => 'success', 'data' => $items]);
    }
}