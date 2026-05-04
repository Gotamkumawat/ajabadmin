<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FilmController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Database connect
        $this->load->helper('url'); 
        $this->load->model('FilmModel'); // Model load
        $this->load->library('session'); // Load session library for flash messages
    }

  public function add_film() {
        $this->load->view('add-filmDetails');
    }

    public function save() {
        // Handle thumbnail upload
        $thumbnail_Image = '';
        if (!empty($_FILES['thumbnail_Image']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/thumbnails/'; // Folder path
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = time() . '_' . $_FILES['thumbnail_Image']['name'];
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
            'series_description' => $this->input->post('series_description'),
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
        // Handle thumbnail upload
        $thumbnail_Image = '';
        if (!empty($_FILES['thumbnail_Image']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/thumbnails/'; // Folder path
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = time() . '_' . $_FILES['thumbnail_Image']['name'];
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
            'series_description' => $this->input->post('series_description'),
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
                'date_of_upload' => $ep->date_of_upload ?? '',
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
        $config['upload_path'] = './uploads/thumbnails/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = time().'_'.$_FILES['thumbnail_image_upload']['name'];

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
        'meta_keyword' => $this->input->post('meta_keyword'),
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
        $config['upload_path'] = './uploads/thumbnails/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = time() . '_' . $_FILES['thumbnail_image_upload']['name'];

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

    // 2. Data array (only fields present in the form)
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
        'meta_keyword' => $this->input->post('meta_keyword'),
        'meta_description' => $this->input->post('meta_description'),
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





 
}