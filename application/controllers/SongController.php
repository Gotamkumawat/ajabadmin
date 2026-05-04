<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SongController extends CI_Controller {
        // AJAX: Add new umbrella title
        public function ajax_add_umbrella_title() {
            $this->output->set_content_type('application/json');
            $title = trim($this->input->post('title'));
            if ($title === '') {
                echo json_encode(['success' => false, 'message' => 'Title required']);
                return;
            }
            // Check if already exists
            $table = $this->SongModel->song_table_name();
            $exists = $this->db->where('Songtitle_transliteration', $title)->get($table)->row_array();
            if ($exists) {
                echo json_encode(['success' => false, 'message' => 'Title already exists']);
                return;
            }
            $this->db->insert($table, ['Songtitle_transliteration' => $title]);
            echo json_encode(['success' => true]);
        }

        // AJAX: Update umbrella title (all songs with old_title)
        public function ajax_update_umbrella_title() {
            $this->output->set_content_type('application/json');
            $old = trim($this->input->post('old_title'));
            $new = trim($this->input->post('new_title'));
            if ($old === '' || $new === '') {
                echo json_encode(['success' => false, 'message' => 'Both titles required']);
                return;
            }
            $this->db->where('Songtitle_transliteration', $old)->update($this->SongModel->song_table_name(), ['Songtitle_transliteration' => $new]);
            echo json_encode(['success' => true]);
        }

        // AJAX: Delete umbrella title (delete all songs with that title)
        public function ajax_delete_umbrella_title() {
            $this->output->set_content_type('application/json');
            $title = trim($this->input->post('title'));
            if ($title === '') {
                echo json_encode(['success' => false, 'message' => 'Title required']);
                return;
            }
            $this->db->where('Songtitle_transliteration', $title)->delete($this->SongModel->song_table_name());
            echo json_encode(['success' => true]);
        }
    // AJAX: Create new Song
    public function ajax_create_song() {
        $this->output->set_content_type('application/json');
        $title = trim($this->input->post('umbrellaTitle'));
        if ($title === '') {
                echo json_encode(['status' => 'error', 'message' => 'Song title is required']);
            return;
        }
        $this->db->insert($this->SongModel->song_table_name(), ['Songtitle_transliteration' => $title]);
        $id = $this->db->insert_id();
            echo json_encode([
                'status' => 'success',
                'message' => 'Song added successfully',
                'song_id' => $id,
                'umbrellaTitle' => $title
            ]);
    }

    // AJAX: Create new Reflection
    public function ajax_create_reflection() {
        $this->output->set_content_type('application/json');
        $title = trim($this->input->post('title'));
        if ($title === '') {
                echo json_encode(['status' => 'error', 'message' => 'Reflection title is required']);
            return;
        }
        $this->db->insert('reflection', ['title' => $title]);
        $id = $this->db->insert_id();
            echo json_encode([
                'status' => 'success',
                'message' => 'Reflection added successfully',
                'reflection_id' => $id,
                'title' => $title
            ]);
    }

    // AJAX: Create new Poem
    public function ajax_create_poem() {
        $this->output->set_content_type('application/json');
        $title = trim($this->input->post('original_title'));
        if ($title === '') {
                echo json_encode(['status' => 'error', 'message' => 'Poem title is required']);
            return;
        }
        $this->db->insert('couplet', ['original_title' => $title]);
        $id = $this->db->insert_id();
            echo json_encode([
                'status' => 'success',
                'message' => 'Poem added successfully',
                'poem_id' => $id,
                'original_title' => $title
            ]);
    }

    // AJAX: Create new Film
    public function ajax_create_film() {
        $this->output->set_content_type('application/json');
        $title = trim($this->input->post('main_title'));
        if ($title === '') {
                echo json_encode(['status' => 'error', 'message' => 'Film title is required']);
            return;
        }
        if ($this->db->table_exists('film')) {
            $this->db->insert('film', ['english_transliteration' => $title]);
        } else {
            $this->db->insert('film_details', ['main_title' => $title]);
        }
        $id = $this->db->insert_id();
            echo json_encode([
                'status' => 'success',
                'message' => 'Film added successfully',
                'film_id' => $id,
                'main_title' => $title
            ]);
    }

    // AJAX: Create new Film Episode
    public function ajax_create_episode() {
        $this->output->set_content_type('application/json');
        $title = trim($this->input->post('film_episode_title'));
        if ($title === '') {
                echo json_encode(['status' => 'error', 'message' => 'Episode title is required']);
            return;
        }
        if ($this->db->table_exists('film_episode')) {
            $this->db->insert('film_episode', ['english_transliteration' => $title]);
        } else {
            $this->db->insert('film_episode_details', ['film_episode_title' => $title]);
        }
        $id = $this->db->insert_id();
            echo json_encode([
                'status' => 'success',
                'message' => 'Episode added successfully',
                'episode_id' => $id,
                'film_episode_title' => $title
            ]);
    }

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Database connect
        $this->load->helper('url'); 
        $this->load->model('SongModel'); // Model load
        $this->load->library('session');
        $this->load->library('upload'); // Add upload library
		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
    }


    public function songs(){
        $this->load->view('add-songs'); // View load
    }
     // Add / Edit page
    //  public function add_songs($id = null) {
    //     $data = [];
    //     if($id) {
    //         // Edit mode
    //         $song = $this->SongModel->get_song_by_id($id);
    //         if(!$song) show_404();
    //         $data['song'] = $song;
    //         $data['form_action'] = base_url('song/update');
    //         $data['button_text'] = 'Update Song';
    //     } else {
    //         // Add mode
    //         $data['song'] = null;
    //         $data['form_action'] = base_url('song/save');
    //         $data['button_text'] = 'Add Song';
    //     }
    //     $this->load->view('add-song', $data);
    // }

                 public function add_songs($id = null) {
                        $data = [];

                        if ($id) {
                            // Agar edit ke liye call hua hai (URL me ID hai)
                            $song = $this->SongModel->get_song_by_id($id);
                            if ($song) {
                                $data['song'] = $song;
                                $data['form_action'] = base_url('song/update/' . $id);
                            } else {
                                show_404(); // ID galat hai to 404
                            }
                        } else {
                            
                            $data['form_action'] = base_url('SongController/save');
                        }

                        $this->load->view('add-song', $data);
                    }



    public function add_song() {
        $this->load->view('add-song'); // View load
    }

    public function save() {
        // Edit submissions must update, not insert (form may have pointed at save by mistake).
        $songId = $this->input->post('id');
        if (!empty($songId)) {
            $this->update();
            return;
        }

        $umbrellaTitle = $this->input->post('umbrellaTitle');
        $songTitle = $this->input->post('songTitle');
        // ✅ Convert arrays to comma separated values
        $umbrellaTitle = is_array($umbrellaTitle) ? implode(',', $umbrellaTitle) : $umbrellaTitle;
        $songTitle     = is_array($songTitle) ? implode(',', $songTitle) : $songTitle;
        $Songtitle_transliteration = $this->input->post('Songtitle_transliteration');
        $songtitletraan = $this->input->post('songtitletraan');
        $singer = $this->input->post('singer');
        $singer = is_array($singer) ? implode(',', $singer) : $singer;
        // $words = $this->input->post('words');
        $songTitleOriginal = $this->input->post('songTitleOriginal');
        $poet = $this->input->post('poet');
        // --- AJAX/POST Save Logic ---
        $isAjax = $this->input->is_ajax_request();
        $umbrellaTitle = $this->input->post('umbrellaTitle');
        $songTitle = $this->input->post('songTitle');
        // ✅ Convert arrays to comma separated values
        $umbrellaTitle = is_array($umbrellaTitle) ? implode(',', $umbrellaTitle) : $umbrellaTitle;
        $songTitle     = is_array($songTitle) ? implode(',', $songTitle) : $songTitle;
        $Songtitle_transliteration = $this->input->post('Songtitle_transliteration');
        $songtitletraan = $this->input->post('songtitletraan');
        
        $songTitleOriginal = $this->input->post('songTitleOriginal');
        $poet = $this->input->post('poet');
        $year = $this->input->post('year');
        $relatedkeywords = $this->input->post('relatedkeywords');
        $relatedpoems = $this->input->post('relatedpoems');
        $location = $this->input->post('location');
        $reflections = $this->input->post('reflections');
        $films = $this->input->post('films');
        $film_episodes = $this->input->post('film_episodes');
        $related_people = $this->input->post('related_people');
        $related_songs = $this->input->post('related_songs');
        $youtubeVideoId = $this->input->post('youtubeVideoId');
        $soundCloudTrackUrl = $this->input->post('soundCloudTrackUrl');
        // Handle thumbnail upload
        $thumbnailUrl = '';
        if (!empty($_FILES['thumbnailUrl']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/thumbnails/'; // Folder path
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = time() . '_' . $_FILES['thumbnailUrl']['name'];
            $this->upload->initialize($config);
            if ($this->upload->do_upload('thumbnailUrl')) {
                $uploadData = $this->upload->data();
                $thumbnailUrl = 'uploads/thumbnails/' . $uploadData['file_name'];
            } else {
                if ($isAjax) {
                    $this->output->set_content_type('application/json');
                    echo json_encode(['success' => false, 'message' => $this->upload->display_errors()]);
                    return;
                }
                $thumbnailUrl = '';
            }
        } else {
            $thumbnailUrl = $this->input->post('thumbnailUrl_existing');
            if ($thumbnailUrl === null || $thumbnailUrl === '') {
                $thumbnailUrl = $this->input->post('thumbnailUrl');
            }
        }
        $thumbnailexcerpt = $this->input->post('thumbnailexcerpt');
        $songLyricsOriginal = $this->input->post('songLyricsOriginal');
        $songLyricsTranslated = $this->input->post('songLyricsTranslated');
        $songLyricsNotes = $this->input->post('songLyricsNotes');
        $about = $this->input->post('about');
        $songnotes = $this->input->post('songnotes');
        $songglossary = $this->input->post('songglossary');
        $publish = $this->input->post('publish');
        $reflection = $this->input->post('reflection');
        $metaTitle = $this->input->post('metaTitle');
        $metaKeyword = $this->input->post('metaKeyword');
        $metaDescription = $this->input->post('metaDescription');
        $formattedContent = $this->input->post('formattedContent');

        $data = array(
            'umbrellaTitle' => $umbrellaTitle,
            'songTitle' => $songTitle,
            'Songtitle_transliteration' => $Songtitle_transliteration,
            'songtitletraan' => $songtitletraan,
            'singer' => $singer,
            'songTitleOriginal' => is_array($this->input->post('songTitleOriginal')) ? implode(',', $this->input->post('songTitleOriginal')) : $this->input->post('songTitleOriginal'),
            'poet' => is_array($this->input->post('poet')) ? implode(',', $this->input->post('poet')) : $this->input->post('poet'),
            'year' => $year,
            'relatedkeywords' => is_array($this->input->post('relatedkeywords')) ? implode(',', $this->input->post('relatedkeywords')) : $this->input->post('relatedkeywords'),
            'relatedpoems' => is_array($this->input->post('relatedpoems')) ? implode(',', $this->input->post('relatedpoems')) : $this->input->post('relatedpoems'),
            'location' => $location,
            'reflections' => is_array($this->input->post('reflections')) ? implode(',', $this->input->post('reflections')) : $this->input->post('reflections'),
            'films' => is_array($this->input->post('films')) ? implode(',', $this->input->post('films')) : $this->input->post('films'),
            'film_episodes' => is_array($this->input->post('film_episodes')) ? implode(',', $this->input->post('film_episodes')) : $this->input->post('film_episodes'),
            'related_people' => is_array($this->input->post('related_people')) ? implode(',', $this->input->post('related_people')) : $this->input->post('related_people'),
            'related_songs' => is_array($this->input->post('related_songs')) ? implode(',', $this->input->post('related_songs')) : $this->input->post('related_songs'),
            'translator' => is_array($this->input->post('translator')) ? implode(',', $this->input->post('translator')) : $this->input->post('translator'),
            'youtubeVideoId' => $youtubeVideoId,
            'soundCloudTrackUrl' => $soundCloudTrackUrl,
            'thumbnailUrl' => $thumbnailUrl,
            'thumbnailexcerpt' => $thumbnailexcerpt,
            'songLyricsOriginal' => $songLyricsOriginal,
            'songLyricsTranslated' => $songLyricsTranslated,
            'songLyricsNotes' => $songLyricsNotes,
            'about' => $about,
            'songnotes' => $songnotes,
            'songglossary' => $songglossary,
            'publish' => $publish,
            'reflection' => $reflection,
            'metaTitle' => $metaTitle,
            'metaKeyword' => $metaKeyword,
            'metaDescription' => $metaDescription,
            'formattedContent' => $formattedContent,
            // Add created date
            'added_date' => date('Y-m-d H:i:s')
        );

        // Debug: print data and exit before saving
        // Model se insert function call
        $insert = $this->SongModel->insert_song($data);

        if ($insert) {
            $newId = (int) $this->db->insert_id();
            $this->SongModel->sync_singer_poet_junction_tables($newId, $data);
        }

        if ($isAjax) {
            $this->output->set_content_type('application/json');
            if ($insert) {
                echo json_encode(['success' => true, 'message' => 'Song saved successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error saving data.']);
            }
            return;
        }
        // Normal POST: redirect
        redirect('song-lists');
    }

    public function fetch_songs()
    {
        $songs = $this->db->get($this->SongModel->song_table_name())->result_array();
        $data = [];
        $i = 1;

        $personNameById = function ($pid) {
            $pid = (int) $pid;
            if ($pid <= 0) return '';
            $person = $this->db->get_where('person', ['id' => $pid])->row_array();
            if (!$person) return '';
            $parts = [];
            if (!empty($person['first_name'])) $parts[] = $person['first_name'];
            if (!empty($person['middle_name'])) $parts[] = $person['middle_name'];
            if (!empty($person['last_name'])) $parts[] = $person['last_name'];
            $fullName = trim(implode(' ', $parts));
            return $fullName !== '' ? $fullName : 'Unnamed';
        };

        $titleTextById = function ($titleId) {
            $titleId = (int) $titleId;
            if ($titleId <= 0) return '';
            $t = $this->db->get_where('title', ['id' => $titleId])->row_array();
            if (!$t) return '';
            return trim((string)($t['english_transliteration'] ?? '')) !== ''
                ? (string)$t['english_transliteration']
                : (string)($t['original_title'] ?? '');
        };

        foreach ($songs as $row) {
            $val = isset($row['publish']) ? $row['publish'] : (isset($row['is_authoring_complete']) ? $row['is_authoring_complete'] : '');
            $publishedVal = 'No';
            if (
                $val === 1 || $val === '1' || $val === true || $val === 'true' ||
                (is_string($val) && strtolower($val) === 'true')
            ) {
                $publishedVal = 'Yes';
            }
            $formattedDate = '';
            $rawDate = !empty($row['added_date']) ? $row['added_date'] : (!empty($row['published_date']) ? $row['published_date'] : '');
            if (!empty($rawDate)) {
                $timestamp = strtotime($rawDate);
                if ($timestamp !== false) {
                    $formattedDate = date('d M Y', $timestamp);
                }
            }

            $songTitle = '';
            if (!empty($row['Songtitle_transliteration'])) {
                $songTitle = $row['Songtitle_transliteration'];
            } elseif (!empty($row['song_title_id'])) {
                $songTitle = $titleTextById($row['song_title_id']);
            } elseif (!empty($row['umbrella_title_id'])) {
                $songTitle = $titleTextById($row['umbrella_title_id']);
            }

            $singers = [];
            if (!empty($row['singer'])) {
                foreach (array_filter(array_map('trim', explode(',', (string)$row['singer']))) as $sid) {
                    $n = $personNameById($sid);
                    if ($n !== '') $singers[] = $n;
                }
            } elseif ($this->db->table_exists('song_singer')) {
                $sRows = $this->db->select('singer_id')->from('song_singer')->where('song_id', (int)$row['id'])->get()->result_array();
                foreach ($sRows as $sr) {
                    $n = $personNameById($sr['singer_id'] ?? 0);
                    if ($n !== '') $singers[] = $n;
                }
            }

            $poets = [];
            if (!empty($row['poet'])) {
                foreach (array_filter(array_map('trim', explode(',', (string)$row['poet']))) as $pid) {
                    $n = $personNameById($pid);
                    if ($n !== '') $poets[] = $n;
                }
            } elseif ($this->db->table_exists('song_poet')) {
                $pRows = $this->db->select('poet_id')->from('song_poet')->where('song_id', (int)$row['id'])->get()->result_array();
                foreach ($pRows as $pr) {
                    $n = $personNameById($pr['poet_id'] ?? 0);
                    if ($n !== '') $poets[] = $n;
                }
            }

            $data[] = [
                'id' => $i++,
                'upload_date' => $formattedDate,
                'song_title' => $songTitle,
                'singer' => implode(', ', array_values(array_unique($singers))),
                'poet' => implode(', ', array_values(array_unique($poets))),
                'published' => $publishedVal,
                'action' => '<a href="'.base_url('song/edit/'.$row['id']).'" class="btn btn-sm btn-primary">Edit</a>
                            <a href="'.base_url('song/delete/'.$row['id']).'" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>'
            ];
        }

        echo json_encode(['data' => $data]);
    }

            public function songlists() {
                $this->load->view('song-lists'); // Load the view for displaying songs
            }





            
               public function editSong() {
                $this->load->view('add-song'); // Load the view for displaying songs
            }

            public function get_song() {
                $id = $this->input->get('id');
                $song = $this->SongModel->get_song_by_id($id);
                echo json_encode($song);
            }

            public function edit($id) {
                    $data['song'] = $this->SongModel->get_song_by_id($id); // Fetch single song data
                    if(!$data['song']) {
                        show_404();
                    }
                    $this->load->view('add-song', $data); // Load edit page with data
                }


           

        public function update() {

    $songId = $this->input->post('id');

    if (!$songId) {
        show_error('Invalid song ID', 400);
        return;
    }

    // ⭐ FIRST: Create empty data array
    $data = [];

    // ⭐ 1) Thumbnail Upload (fixed)
    if (!empty($_FILES['thumbnailUrl']['name'])) {

        $config['upload_path'] = FCPATH . 'uploads/thumbnails/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 2048; 
        $config['file_name'] = time() . '_' . $_FILES['thumbnailUrl']['name'];

        $this->upload->initialize($config);

        if ($this->upload->do_upload('thumbnailUrl')) {
            $uploadData = $this->upload->data();
            $data['thumbnailUrl'] = 'uploads/thumbnails/' . $uploadData['file_name'];
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('song-lists');
            return;
        }
    }

    // ⭐ 2) These fields allow multiple values
    $arrayFields = [
        'umbrellaTitle',
        'songTitle',
        'singer',
        'words',
        'songTitleOriginal',
        'poet',
        'reflections',
        'relatedpoems',
        'relatedkeywords',
        'couplets',
        'films',
        'film_episodes',
        'related_stories',
        'related_people',
        'related_songs',
        'genres',
        'gatherings',
        'songcategory',
        'translator'
    ];

    // ⭐ 3) Convert every POST field safely
    foreach ($this->input->post() as $key => $value) {

        if ($key == 'id' || $key === 'thumbnailUrl_existing') {
            continue;
        }

        if (in_array($key, $arrayFields)) {
            $data[$key] = is_array($value) ? implode(',', $value) : $value;
        } else {
            $data[$key] = is_array($value) ? implode(',', $value) : $value;
        }
    }

    if (empty($_FILES['thumbnailUrl']['name'])) {
        $existingThumb = $this->input->post('thumbnailUrl_existing');
        if ($existingThumb !== null && $existingThumb !== '') {
            $data['thumbnailUrl'] = $existingThumb;
        }
    }

    // ⭐ 4) Update the database
    $updated = $this->SongModel->update_song($songId, $data);

    if ($updated) {
        $this->SongModel->sync_singer_poet_junction_tables($songId, $data);
        $this->session->set_flashdata('success', '✅ Song updated successfully!');
    } else {
        $this->session->set_flashdata('error', '⚠️ Failed to update song.');
    }

    redirect('song-lists');
}




        public function delete($id)
            {
                // ID valid hai ya nahi check karo
                if (empty($id) || !is_numeric($id)) {
                    $this->session->set_flashdata('error', 'Invalid song ID!');
                    redirect('song-lists');
                    return;
                }

                // Model se delete function call
                $deleted = $this->SongModel->delete_song($id);

                if ($deleted) {
                    $this->session->set_flashdata('success', 'Song deleted successfully!');
                } else {
                    $this->session->set_flashdata('error', 'Failed to delete the song or song not found.');
                }

                // List page par redirect
                redirect('song-lists');
            }
            
    public function ajax_create_person() {
        $name = trim($this->input->post('name'));
        $hyperlink = trim($this->input->post('hyperlink') ?? '');
        $typeId = (int)$this->input->post('type_id');
        $this->output->set_content_type('application/json');

        if ($name === '') {
            echo json_encode(['success' => false, 'message' => 'Name is required']);
            return;
        }

        $parts = preg_split('/\s+/', $name);
        $first = isset($parts[0]) ? $parts[0] : '';
        if (count($parts) > 1) {
            $last = array_pop($parts);
            $middle = implode(' ', $parts);
        } else {
            $last = '';
            $middle = '';
        }

        $data = [
            'first_name' => $first,
            'middle_name' => $middle,
            'last_name' => $last,
            'hyperlink' => $hyperlink
        ];

        if (in_array($typeId, [1, 2], true)) {
            $personFields = $this->db->list_fields('person');
            if (in_array('type', $personFields)) {
                $data['type'] = $typeId;
            }
        }

        $inserted = $this->db->insert('person', $data);
        if (!$inserted) {
            echo json_encode(['success' => false, 'message' => 'DB insert failed']);
            return;
        }

        $id = $this->db->insert_id();
        $fullName = trim($first . ' ' . ($middle ? $middle . ' ' : '') . $last);
        echo json_encode(['success' => true, 'id' => (string)$id, 'fullName' => $fullName !== '' ? $fullName : $name]);
    }

    public function ajax_create_translator() {
        $this->output->set_content_type('application/json');

        $name = trim((string)$this->input->post('name'));
        $hyperlink = trim((string)$this->input->post('hyperlink'));
        if ($name === '') {
            echo json_encode(['success' => false, 'message' => 'Name is required']);
            return;
        }

        if (!$this->db->table_exists('translator')) {
            echo json_encode(['success' => false, 'message' => 'translator table not found']);
            return;
        }

        $fields = $this->db->list_fields('translator');
        if (empty($fields)) {
            echo json_encode(['success' => false, 'message' => 'translator table schema not found']);
            return;
        }

        $idColumn = in_array('id', $fields) ? 'id' : $fields[0];

        $nameColumn = null;
        if (in_array('name', $fields)) {
            $nameColumn = 'name';
        } elseif (in_array('translator_name', $fields)) {
            $nameColumn = 'translator_name';
        } elseif (in_array('title', $fields)) {
            $nameColumn = 'title';
        } else {
            foreach ($fields as $f) {
                $lower = strtolower($f);
                if ($lower !== strtolower($idColumn) && $lower !== 'created_at' && $lower !== 'updated_at') {
                    $nameColumn = $f;
                    break;
                }
            }
        }

        if ($nameColumn === null) {
            echo json_encode(['success' => false, 'message' => 'No translator name column found']);
            return;
        }

        // Check for existing
        $existing = $this->db->where($nameColumn, $name)->get('translator')->row_array();
        if (!empty($existing)) {
            $existingId = isset($existing[$idColumn]) ? (string)$existing[$idColumn] : '';
            $existingName = isset($existing[$nameColumn]) ? (string)$existing[$nameColumn] : $name;
            echo json_encode(['success' => true, 'id' => $existingId, 'name' => $existingName]);
            return;
        }

        $insertData = [$nameColumn => $name];
        // Add hyperlink if column exists (handle typo 'hyperling')
        if (in_array('hyperlink', $fields)) {
            $insertData['hyperlink'] = $hyperlink;
        } elseif (in_array('hyperling', $fields)) {
            $insertData['hyperling'] = $hyperlink;
        }
        if (in_array('created_at', $fields)) {
            $insertData['created_at'] = date('Y-m-d H:i:s');
        }

        $inserted = $this->db->insert('translator', $insertData);
        if (!$inserted) {
            $dbError = $this->db->error();
            $message = !empty($dbError['message']) ? $dbError['message'] : 'Failed to insert translator';
            echo json_encode(['success' => false, 'message' => $message]);
            return;
        }

        $newId = $this->db->insert_id();
        if (!$newId) {
            $insertedRow = $this->db->where($nameColumn, $name)->order_by($idColumn, 'DESC')->get('translator')->row_array();
            $newId = !empty($insertedRow[$idColumn]) ? $insertedRow[$idColumn] : '';
        }

        echo json_encode(['success' => true, 'id' => (string)$newId, 'name' => $name]);
    }

    public function ajax_create_keyword() {
        $this->output->set_content_type('application/json');
        $word = trim($this->input->post('word_transliteration'));
        if ($word === '') {
            echo json_encode(['success' => false, 'message' => 'Keyword required']);
            return;
        }
        // Check if already exists
        $existing = $this->db->where('word_transliteration', $word)->get('keywords')->row_array();
        if (!empty($existing)) {
            echo json_encode(['success' => true, 'id' => $existing['id'], 'word_transliteration' => $existing['word_transliteration']]);
            return;
        }
        $this->db->insert('keywords', ['word_transliteration' => $word]);
        $id = $this->db->insert_id();
        echo json_encode(['success' => true, 'id' => $id, 'word_transliteration' => $word]);
    }
}

