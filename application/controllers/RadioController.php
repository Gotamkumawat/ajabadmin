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
            if ($id) {
                // Same form me data fetch karke bhej rahe hain
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
    // 1) Multiselects -> comma separated string
    $keywords     = $this->input->post('keywords');      // array ya null
    $songIds      = $this->input->post('song');          // array ya null  (NOTE: form name "song[]")
    $reflections  = $this->input->post('reflections');
    $poems        = $this->input->post('poems');
    $people       = $this->input->post('people');
    $films        = $this->input->post('films');
    $filmEpisode  = $this->input->post('film_episode');

    $keywords    = is_array($keywords)    ? implode(',', $keywords)    : null;
    $songs       = is_array($songIds)     ? implode(',', $songIds)     : null; // DB column name "songs"
    $reflections = is_array($reflections) ? implode(',', $reflections) : null;
    $poems       = is_array($poems)       ? implode(',', $poems)       : null;
    $people      = is_array($people)      ? implode(',', $people)      : null;
    $films       = is_array($films)       ? implode(',', $films)       : null;
    $filmEpisode = is_array($filmEpisode) ? implode(',', $filmEpisode) : null;

    // 2) File uploads (keep old if not replaced)
    $singer_image = $this->input->post('old_singer_image'); // from hidden input
    if (!empty($_FILES['upload_singer_image']['name'])) {
        $config = [
            'upload_path'   => './uploads/radio/',
            'allowed_types' => 'jpg|jpeg|png|webp',
            'max_size'      => 5000
        ];
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('upload_singer_image')) {
            $singer_image = $this->upload->data('file_name');
        }
    }

    $mp3_file = $this->input->post('old_mp3_file'); // from hidden input
    if (!empty($_FILES['upload_song_mp3_file']['name'])) {
        $config = [
            'upload_path'   => './uploads/radio/',
            'allowed_types' => 'mp3|wav|m4a',
            'max_size'      => 80000 // ~80 MB
        ];
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('upload_song_mp3_file')) {
            $mp3_file = $this->upload->data('file_name');
        }
    }

    // 3) Final data
    $data = [
        'singer_name'            => $this->input->post('singer_name', true),
        'singer_profile'         => $this->input->post('singer_profile', true),
        'profile_url'            => $this->input->post('profile_url', true),
        'song_name'              => $this->input->post('song_name', true),
        'location'               => $this->input->post('location', true),
        'year'                   => $this->input->post('year', true),
        'song_url'               => $this->input->post('song_url', true),
        'buy_cd_url'             => $this->input->post('buy_cd_url', true),
        'download_url'           => $this->input->post('download_url', true),
        'upload_singer_image'    => $singer_image ?: null,
        'upload_song_mp3_file'   => $mp3_file ?: null,
        'about'                  => $this->input->post('about'), // CKEditor HTML

        // multiselects (strings)
        'keywords'               => $keywords,
        'songs'                  => $songs,
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

    // 4) Insert/Update
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

        foreach ($radio as $r) {
            $data[] = [
                'sl_no' => $sl++,
                'date_of_upload' => isset($r->created_at) ? date('Y-m-d', strtotime($r->created_at)) : '-',
                'song_name' => $r->song_name,
                'singer_name' => $r->singer_name,
                'playlist' => $r->playlists,
                // 'published' => ($r->publish == 1) ? 'Yes' : 'No',
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
        $this->load->view('radio-list', $data); 
        
    }
}