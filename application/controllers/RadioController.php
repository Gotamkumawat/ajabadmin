<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RadioController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Database connect
        $this->load->helper('url'); 
        $this->load->model('RadioModel'); // Model load
        $this->load->library('session');
		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
    }

    // public function add_radio() {
    //     $this->load->view('add-radio'); // View load
    // }
    //     public function add_radio($id = null) {
    //     $data = [];

    //     if ($id) {
    //         // If editing existing record
    //         $data['radio'] = $this->RadioModel->get_radio_by_id($id);
    //     }

    //     $this->load->view('add-radio', $data);
    // }

    public function add_radio($id = null) {
        $data = [];
        if ($id) {
            $data['radio'] = $this->RadioModel->get_radio_by_id($id);
        }
        $this->load->view('add-radio', $data);
    }

    // public function save() {
    //     $singer_name = $this->input->post('singer_name');
    //     $singer_profile = $this->input->post('singer_profile'); 
    //     $profile_url = $this->input->post('profile_url');
    //     $song_name = $this->input->post('song_name');
    //     $singer_contact = $this->input->post('singer_contact');
    //     $location = $this->input->post('location');
    //     $year = $this->input->post('year');
    //     $song_url = $this->input->post('song_url');
    //     $buy_cd_url = $this->input->post('buy_cd_url');
    //     $download_url = $this->input->post('download_url');
    //     $upload_singer_image = $this->input->post('upload_singer_image');
    //     $upload_song_mp3_file = $this->input->post('upload_song_mp3_file');
    //     $about = $this->input->post('about');
    //     $keywords = $this->input->post('keywords');
    //     $songs = $this->input->post('songs');
    //     $reflections = $this->input->post('reflections');
    //     $poems = $this->input->post('poems');
    //     $people = $this->input->post('people');
    //     $films = $this->input->post('films');
    //     $film_episode = $this->input->post('film_episode');
    //     $display_in_people_page = $this->input->post('display_in_people_page');
    //     $publish = $this->input->post('publish');
    //     $meta_title = $this->input->post('meta_title');
    //     $meta_keyword = $this->input->post('meta_keyword');
    //     $meta_description = $this->input->post('meta_description');
        

    //     $data = array(
    //         'singer_name' => $singer_name,
    //         'singer_profile' => $singer_profile,
    //         'profile_url' => $profile_url,
    //         'song_name' => $song_name,
    //         'singer_contact' => $singer_contact,
    //         'location' => $location,
    //         'year' => $year,
    //         'song_url' => $song_url,
    //         'buy_cd_url' => $buy_cd_url,
    //         'download_url' => $download_url,
    //         'upload_singer_image' => $upload_singer_image,
    //         'upload_song_mp3_file' => $upload_song_mp3_file,
    //         'about' => $about,
    //         'keywords' => $keywords,
    //         'songs' => $songs,
    //         'reflections' => $reflections,
    //         'poems' => $poems,
    //         'people' => $people,
    //         'films' => $films,
    //         'film_episode' => $film_episode,
    //         'display_in_people_page' => $display_in_people_page,
    //         'publish' => $publish,
    //         'meta_title' => $meta_title,
    //         'meta_keyword' => $meta_keyword,
    //         'meta_description' => $meta_description
            
    //     );

    //     // Model se insert function call
    //     $insert = $this->RadioModel->insert_radio($data);

    //     // if ($insert) {
    //     //     echo "Song Notes saved successfully!";
    //     // } else {
    //     //     echo "Error saving data.";
    //     // }

    //      redirect('add-radio');
    // }


    public function save() {
        $this->load->model('SongModel');

        $keywords     = $this->input->post('keywords');
        $songIds      = $this->input->post('song');
        $reflections  = $this->input->post('reflections');
        $poems        = $this->input->post('poems');
        $people       = $this->input->post('people');
        $films        = $this->input->post('films');
        $filmEpisode  = $this->input->post('film_episode');

        $keywords    = is_array($keywords)    ? implode(',', $keywords)    : null;
        $reflections = is_array($reflections) ? implode(',', $reflections) : null;
        $poems       = is_array($poems)       ? implode(',', $poems)       : null;
        $people      = is_array($people)      ? implode(',', $people)      : null;
        $films       = is_array($films)       ? implode(',', $films)       : null;
        $filmEpisode = is_array($filmEpisode) ? implode(',', $filmEpisode) : null;

        $songIdRaw = $this->input->post('song_id');
        if (is_array($songIdRaw)) {
            $songIdRaw = reset($songIdRaw); // single-pick — take first
        }
        $songPostId = (int) $songIdRaw;
        if ($songPostId <= 0 && is_array($songIds) && !empty($songIds)) {
            $songPostId = (int) reset($songIds);
        }
        $songsCsv = $songPostId > 0 ? (string) $songPostId : (is_array($songIds) ? implode(',', $songIds) : null);

        $radioDir = FCPATH . 'uploads/radio/';
        if (!is_dir($radioDir)) { @mkdir($radioDir, 0755, true); }

        $singer_image = $this->input->post('old_singer_image');
        if (!empty($_FILES['upload_singer_image']['name'])) {
            $config = [
                'upload_path'   => $radioDir,
                'allowed_types' => 'jpg|jpeg|png|webp',
                'max_size'      => 5000
            ];
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('upload_singer_image')) {
                $singer_image = $this->upload->data('file_name');
            }
        }

        $mp3_file = $this->input->post('old_mp3_file');
        if (!empty($_FILES['upload_song_mp3_file']['name'])) {
            $config = [
                'upload_path'   => $radioDir,
                'allowed_types' => 'mp3|wav|m4a',
                'max_size'      => 80000
            ];
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('upload_song_mp3_file')) {
                $mp3_file = $this->upload->data('file_name');
            }
        }

        $singer_name = $this->input->post('singer_name', true);
        $song_name   = $this->input->post('song_name', true);
        $location    = $this->input->post('location', true);
        $year        = $this->input->post('year', true);

        if ($songPostId > 0) {
            $songRow = $this->SongModel->get_song_by_id($songPostId);
            if (is_array($songRow)) {
                $song_name = trim((string) ($songRow['Songtitle_transliteration'] ?? $songRow['songTitle'] ?? $song_name));
                if ($song_name === '') {
                    $song_name = trim((string) ($songRow['songTitleOriginal'] ?? ''));
                }
                $singerCsv = isset($songRow['singer']) ? trim((string) $songRow['singer']) : '';
                if ($singerCsv !== '') {
                    $singer_name = $this->_radio_resolve_singer_display($singerCsv);
                }
                if (!empty($songRow['location'])) {
                    $location = trim((string) $songRow['location']);
                }
                if (!empty($songRow['year'])) {
                    $year = trim((string) $songRow['year']);
                }
                if (empty($mp3_file)) {
                    $fromSong = trim((string) ($songRow['interview_audio'] ?? ''));
                    if ($fromSong !== '') {
                        $mp3_file = $fromSong;
                    }
                }
            }
        }

        $playlist_id = $this->input->post('playlist_id', true);
        if (is_array($playlist_id)) {
            $playlist_id = reset($playlist_id);
        }
        $playlists   = ($playlist_id !== null && $playlist_id !== '') ? (string) $playlist_id : null;

        $data = [
            'singer_name'            => $singer_name ?: null,
            'singer_profile'         => $this->input->post('singer_profile', true),
            'profile_url'            => $this->input->post('profile_url', true),
            'song_name'              => $song_name ?: null,
            'location'               => $location ?: null,
            'year'                   => $year ?: null,
            'song_url'               => $this->input->post('song_url', true),
            'buy_cd_url'             => $this->input->post('buy_cd_url', true),
            'download_url'           => $this->input->post('download_url', true),
            'upload_singer_image'    => $singer_image ?: null,
            'upload_song_mp3_file'   => $mp3_file ?: null,
            'about'                  => $this->input->post('about'),
            'keywords'               => $keywords,
            'songs'                  => $songsCsv,
            'playlists'              => $playlists,
            'radio_excerpt'          => $this->input->post('radio_excerpt', true),
            'reflections'            => $reflections,
            'poems'                  => $poems,
            'people'                 => $people,
            'films'                  => $films,
            'film_episode'           => $filmEpisode,
            'display_in_people_page' => $this->input->post('display_in_people_page'),
            'publish'                => $this->input->post('publish'),
            'meta_title'             => $this->input->post('meta_title', true),
            'meta_keyword'           => $this->input->post('meta_keyword', true),
            'meta_description'       => $this->input->post('meta_description', true),
        ];

        $id = $this->input->post('id');
        if ($id) {
            $this->RadioModel->update_radio($id, $data);
            $this->session->set_flashdata('success', 'Radio record updated successfully!');
        } else {
            $this->RadioModel->insert_radio($data);
            $this->session->set_flashdata('success', 'Radio record added successfully!');
        }

        redirect('radio-list');
    }

    /**
     * JSON for Add Radio: fill readonly fields from canonical song row.
     */
    public function ajax_song_meta() {
        $id = (int) $this->input->get('id');
        if ($id <= 0) {
            $this->output->set_content_type('application/json')->set_output(json_encode(['ok' => false]));
            return;
        }
        $this->load->model('SongModel');
        $songRow = $this->SongModel->get_song_by_id($id);
        if (!is_array($songRow)) {
            $this->output->set_content_type('application/json')->set_output(json_encode(['ok' => false]));
            return;
        }
        $song_name = trim((string) ($songRow['Songtitle_transliteration'] ?? $songRow['songTitle'] ?? ''));
        if ($song_name === '') {
            $song_name = trim((string) ($songRow['songTitleOriginal'] ?? ''));
        }
        $singerCsv = isset($songRow['singer']) ? trim((string) $songRow['singer']) : '';
        $singer_name = $singerCsv !== '' ? $this->_radio_resolve_singer_display($singerCsv) : '';
        $this->output->set_content_type('application/json')->set_output(json_encode([
            'ok'            => true,
            'singer_name'   => $singer_name,
            'location'      => trim((string) ($songRow['location'] ?? '')),
            'year'          => trim((string) ($songRow['year'] ?? '')),
            'song_name'     => $song_name,
            'radio_excerpt' => trim((string) ($songRow['thumbnailexcerpt'] ?? '')),
        ]));
    }

    private function _radio_resolve_singer_display($singer_field) {
        $singer_field = trim((string) $singer_field);
        if ($singer_field === '') {
            return '';
        }
        $parts = array_map('trim', explode(',', $singer_field));
        $allNumeric = true;
        foreach ($parts as $p) {
            if ($p === '') {
                continue;
            }
            if (!ctype_digit($p)) {
                $allNumeric = false;
                break;
            }
        }
        if (!$allNumeric) {
            return $singer_field;
        }
        $names = [];
        foreach ($parts as $p) {
            if ($p === '' || !ctype_digit($p)) {
                continue;
            }
            $row = $this->db->select('first_name, middle_name, last_name')
                ->from('person')
                ->where('id', (int) $p)
                ->get()
                ->row();
            if (!$row) {
                continue;
            }
            $bits = array_filter([
                trim((string) ($row->first_name ?? '')),
                trim((string) ($row->middle_name ?? '')),
                trim((string) ($row->last_name ?? '')),
            ]);
            $names[] = !empty($bits) ? implode(' ', $bits) : ('Person #' . $p);
        }
        return implode(', ', $names);
    }



//    public function fetch_radio() {
//     $radio = $this->RadioModel->get_all_radio();
//     $data = [];
//     $sl = 1;

//     foreach ($radio as $r) {
//         $data[] = [
//             'sl_no' => $sl++,
//             'date_of_upload' => date('Y-m-d', strtotime($r->created_at)),
//             'song_name' => $r->song_name,
//             'singer_name' => $r->singer_name,
//             // 'playlist' => $r->songs, // agar tumhare DB me playlist ka naam “songs” hai
//             'published' => ($r->publish == 1) ? 'Yes' : 'No',
//             'action' => '
//                 <a href="' . base_url('RadioController/edit/' . $r->id) . '" class="btn btn-sm btn-primary">Edit</a>
//                 <a href="' . base_url('RadioController/delete/' . $r->id) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>
//             '
//         ];
//     }

//     echo json_encode(['data' => $data]);
// }


                public function fetch_radio() {
        $radio = $this->RadioModel->get_all_radio();
        $data = [];
        $sl = 1;

        // Build a map of song_id => Songtitle_transliteration for fast lookup
        $song_title_map = [];
        if ($this->db->table_exists('song') && $this->db->table_exists('title')) {
            // Normalized schema
            $rows = $this->db->select('song.id, title.english_transliteration AS translit, title.original_title AS orig_title')
                ->from('song')
                ->join('title', 'title.id = song.song_title_id', 'left')
                ->get()->result_array();
            foreach ($rows as $row) {
                $label = trim((string) ($row['translit'] ?? ''));
                if ($label === '') $label = trim((string) ($row['orig_title'] ?? ''));
                if ($label !== '') $song_title_map[(int) $row['id']] = $label;
            }
        }
        if ($this->db->table_exists('songs')) {
            // Legacy fallback
            $rows = $this->db->select('id, Songtitle_transliteration')->from('songs')->get()->result_array();
            foreach ($rows as $row) {
                $sid = (int) $row['id'];
                if (!isset($song_title_map[$sid])) {
                    $label = trim((string) ($row['Songtitle_transliteration'] ?? ''));
                    if ($label !== '') $song_title_map[$sid] = $label;
                }
            }
        }

        foreach ($radio as $r) {
            // Resolve song name: prefer DB lookup via radio.songs (CSV of song IDs); fallback to radio.song_name
            $songNameDisplay = trim((string) ($r->song_name ?? ''));
            if ($songNameDisplay === '' && !empty($r->songs)) {
                $names = [];
                foreach (explode(',', (string) $r->songs) as $sid) {
                    $sid = (int) trim($sid);
                    if ($sid > 0 && isset($song_title_map[$sid])) {
                        $names[] = $song_title_map[$sid];
                    }
                }
                if (!empty($names)) {
                    $songNameDisplay = implode(', ', $names);
                }
            }
            if ($songNameDisplay === '') $songNameDisplay = '—';

            $data[] = [
                'sl_no' => $sl++,
                'date_of_upload' => isset($r->created_at) ? date('Y-m-d', strtotime($r->created_at)) : '-',
                'song_name' => $songNameDisplay,
                'singer_name' => trim((string) ($r->singer_name ?? '')) !== '' ? $r->singer_name : '—',
                'playlist' => trim((string) ($r->playlists ?? '')) !== '' ? $r->playlists : '—',
                'action' => '
                    <a href="' . base_url('RadioController/add_radio/' . $r->id) . '" class="btn btn-sm btn-primary">Edit</a>
                    <a href="' . base_url('RadioController/delete/' . $r->id) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>
                '
            ];
        }

        echo json_encode(['data' => $data]);
    }


    public function delete($id) {
        $this->RadioModel->delete_radio($id);
        $this->session->set_flashdata('success', 'Radio record deleted successfully!');
        redirect('radio-list');
    }
    
    public function radio_list() {
        $this->load->view('radio-list', []);
    }
}