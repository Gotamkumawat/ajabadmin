<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SongController extends CI_Controller {
        // AJAX: Add new umbrella title (normalized `title` table when present)
        public function ajax_add_umbrella_title() {
            $this->output->set_content_type('application/json');
            // Accept either combined (legacy `title`) or split fields
            $translit = trim((string) $this->input->post('english_transliteration'));
            $translation = trim((string) $this->input->post('english_translation'));
            $original = trim((string) $this->input->post('original_title'));
            $legacyTitle = trim((string) $this->input->post('title'));
            if ($translit === '' && $legacyTitle !== '') { $translit = $legacyTitle; }
            if ($translit === '') {
                echo json_encode(['success' => false, 'message' => 'Transliteration required']);
                return;
            }
            if ($original === '') { $original = $translit; }
            $label = $translit;
            if ($this->db->table_exists('title')) {
                $dup = $this->db->where('english_transliteration', $translit)->get('title')->row_array();
                if (!empty($dup)) {
                    echo json_encode(['success' => true, 'id' => (int) $dup['id'], 'label' => $label]);
                    return;
                }
                $this->db->insert('title', [
                    'original_title' => $original,
                    'english_transliteration' => $translit,
                    'english_translation' => $translation,
                ]);
                $id = (int) $this->db->insert_id();
                echo json_encode(['success' => true, 'id' => $id, 'label' => $label]);
                return;
            }
            $table = $this->SongModel->song_table_name();
            $exists = $this->db->where('Songtitle_transliteration', $translit)->get($table)->row_array();
            if ($exists) {
                echo json_encode(['success' => false, 'message' => 'Title already exists']);
                return;
            }
            $this->db->insert($table, ['Songtitle_transliteration' => $translit]);
            echo json_encode(['success' => true, 'id' => (int) $this->db->insert_id(), 'label' => $label]);
        }

        /** AJAX: Load one `title` row for umbrella inline editor */
        public function ajax_get_title_row() {
            $this->output->set_content_type('application/json');
            $id = (int) $this->input->post('id');
            if ($id <= 0 || !$this->db->table_exists('title')) {
                echo json_encode(['success' => false, 'message' => 'Invalid id']);
                return;
            }
            $row = $this->db->get_where('title', ['id' => $id])->row_array();
            if (empty($row)) {
                echo json_encode(['success' => false, 'message' => 'Not found']);
                return;
            }
            echo json_encode([
                'success' => true,
                'id' => (int) $row['id'],
                'original_title' => (string) ($row['original_title'] ?? ''),
                'english_transliteration' => (string) ($row['english_transliteration'] ?? ''),
                'english_translation' => (string) ($row['english_translation'] ?? ''),
            ]);
        }

        /** AJAX: Save umbrella fields on `title` row */
        public function ajax_save_title_row() {
            $this->output->set_content_type('application/json');
            $id = (int) $this->input->post('id');
            if ($id <= 0 || !$this->db->table_exists('title')) {
                echo json_encode(['success' => false, 'message' => 'Invalid id']);
                return;
            }
            $orig = trim((string) $this->input->post('original_title'));
            $translit = trim((string) $this->input->post('english_transliteration'));
            $trans = trim((string) $this->input->post('english_translation'));
            if ($translit === '') {
                echo json_encode(['success' => false, 'message' => 'Transliteration is required']);
                return;
            }
            $this->db->where('id', $id)->update('title', [
                'original_title' => $orig,
                'english_transliteration' => $translit,
                'english_translation' => $trans,
            ]);
            $label = $translit !== '' ? $translit : $orig;
            echo json_encode(['success' => true, 'id' => $id, 'label' => $label]);
        }

        // AJAX: Update umbrella title (legacy: songs.Songtitle_transliteration by old text)
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

        // AJAX: Delete umbrella title (legacy)
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
        $title = trim((string) $this->input->post('umbrellaTitle'));
        if ($title === '') {
            echo json_encode(['status' => 'error', 'message' => 'Song title is required']);
            return;
        }

        $songTable = $this->SongModel->song_table_name();
        if (!$this->db->table_exists($songTable)) {
            echo json_encode(['status' => 'error', 'message' => 'song table not found']);
            return;
        }
        $cols = $this->db->list_fields($songTable);

        // Build the insert row by writing into whichever title column the
        // active schema actually has. Canonical `song` uses umbrella_title_id
        // (FK to `title`); legacy `songs` has the denormalised text columns.
        $insertRow = [];
        if (in_array('umbrella_title_id', $cols, true) && $this->db->table_exists('title')) {
            $this->db->insert('title', ['english_transliteration' => $title, 'original_title' => $title]);
            $insertRow['umbrella_title_id'] = (int) $this->db->insert_id();
            if (in_array('song_title_id', $cols, true)) {
                $insertRow['song_title_id'] = $insertRow['umbrella_title_id'];
            }
        }
        // Legacy denormalised columns — fill whichever ones exist.
        foreach (['Songtitle_transliteration', 'umbrellaTitle', 'songTitleOriginal', 'songTitle'] as $legacyCol) {
            if (in_array($legacyCol, $cols, true)) {
                $insertRow[$legacyCol] = $title;
            }
        }

        if (empty($insertRow)) {
            echo json_encode(['status' => 'error', 'message' => 'No writable title column found on song table']);
            return;
        }

        if (!$this->db->insert($songTable, $insertRow)) {
            $err = $this->db->error();
            echo json_encode(['status' => 'error', 'message' => !empty($err['message']) ? $err['message'] : 'Insert failed']);
            return;
        }
        echo json_encode([
            'status'        => 'success',
            'message'       => 'Song added successfully',
            'song_id'       => (int) $this->db->insert_id(),
            'umbrellaTitle' => $title,
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
        $this->load->model('WordModel');
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
        $attributed_poet = $this->input->post('attributed_poet');
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
            if (!is_dir($config['upload_path'])) { @mkdir($config['upload_path'], 0755, true); }
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

        $interviewAudioPathSave = trim((string) $this->input->post('interview_audio_existing'));
        if (!empty($_FILES['interview_audio_upload']['name'])) {
            $uploadDir = FCPATH . 'uploads/song_audio/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $config = [
                'upload_path'   => $uploadDir,
                'allowed_types' => 'mp3|wav|m4a|aac|ogg',
                'max_size'      => 80000,
                'file_name'     => time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['interview_audio_upload']['name']),
            ];
            $this->upload->initialize($config);
            if ($this->upload->do_upload('interview_audio_upload')) {
                $interviewAudioPathSave = 'uploads/song_audio/' . $this->upload->data('file_name');
            }
        }

        $thumbnailexcerpt = $this->input->post('thumbnailexcerpt');
        $songLyricsOriginal = $this->input->post('songLyricsOriginal');
        $songLyricsTranslated = $this->input->post('songLyricsTranslated');
        $songLyricsNotes = $this->input->post('songLyricsNotes');
        $about = $this->input->post('about');
        $songnotes = $this->input->post('songnotes');
        $songglossary = $this->input->post('songglossary');
        // songglossary is now a multi-select of word IDs — convert array to CSV
        if (is_array($songglossary)) {
            $songglossary = implode(',', array_filter($songglossary, function ($v) { return trim((string)$v) !== ''; }));
        }
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
            'attributed_poet' => is_array($this->input->post('attributed_poet')) ? implode(',', $this->input->post('attributed_poet')) : $this->input->post('attributed_poet'),
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
        // insert_song now returns the new id (int) on success, false on failure
        if (is_int($insert) && $insert > 0) {
            $newId = $insert;
            $insertOk = true;
        } else {
            $newId = (int) $this->db->insert_id();
            $insertOk = (bool) $insert;
        }
        @file_put_contents(FCPATH . 'song_save_debug.log',
            "[".date('Y-m-d H:i:s')."] insert=".var_export($insert,true)." newId=$newId\n"
            ."POST keys: ".implode(',', array_keys($_POST))."\n"
            ."data[singer]=".(isset($data['singer'])?var_export($data['singer'],true):'(unset)')."\n"
            ."data[poet]=".(isset($data['poet'])?var_export($data['poet'],true):'(unset)')."\n"
            ."data[reflections]=".(isset($data['reflections'])?var_export($data['reflections'],true):'(unset)')."\n"
            ."data[relatedkeywords]=".(isset($data['relatedkeywords'])?var_export($data['relatedkeywords'],true):'(unset)')."\n"
            ."data[films]=".(isset($data['films'])?var_export($data['films'],true):'(unset)')."\n"
            ."POST[singer]=".(isset($_POST['singer'])?var_export($_POST['singer'],true):'(unset)')."\n"
            ."POST[poet]=".(isset($_POST['poet'])?var_export($_POST['poet'],true):'(unset)')."\n"
            ."POST[attributed_poet]=".(isset($_POST['attributed_poet'])?var_export($_POST['attributed_poet'],true):'(unset)')."\n"
            ."---\n", FILE_APPEND);
        if ($insertOk && $newId > 0) {
            $this->SongModel->sync_singer_poet_junction_tables($newId, $data);
            $this->SongModel->sync_related_junction_tables($newId, $data);
            if ($newId > 0 && $interviewAudioPathSave !== '' && $this->db->table_exists('songs')) {
                $this->db->where('id', $newId)->update('songs', ['interview_audio' => $interviewAudioPathSave]);
            }
            if ($newId > 0 && $this->input->post('add_to_radio')) {
                $this->_upsert_radio_for_song($newId);
            }
        }

        if ($isAjax) {
            $this->output->set_content_type('application/json');
            if ($insertOk) {
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
                'action' => '<button type="button" class="btn btn-sm btn-info admin-preview-btn" data-id="'.$row['id'].'">Preview</button>
                            <a href="'.base_url('song/edit/'.$row['id']).'" class="btn btn-sm btn-primary">Edit</a>
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

    $interviewAudioPath = trim((string) $this->input->post('interview_audio_existing'));
    if (!empty($_FILES['interview_audio_upload']['name'])) {
        $this->load->library('upload');
        $uploadDir = FCPATH . 'uploads/song_audio/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $config = [
            'upload_path'   => $uploadDir,
            'allowed_types' => 'mp3|wav|m4a|aac|ogg',
            'max_size'      => 80000,
            'file_name'     => time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['interview_audio_upload']['name']),
        ];
        $this->upload->initialize($config);
        if ($this->upload->do_upload('interview_audio_upload')) {
            $interviewAudioPath = 'uploads/song_audio/' . $this->upload->data('file_name');
        }
    }

    // ⭐ 1) Thumbnail Upload (fixed)
    if (!empty($_FILES['thumbnailUrl']['name'])) {

        $config['upload_path'] = FCPATH . 'uploads/thumbnails/';
        if (!is_dir($config['upload_path'])) { @mkdir($config['upload_path'], 0755, true); }
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
        'translator',
        'songglossary'
    ];

    // ⭐ 3) Convert every POST field safely
    foreach ($this->input->post() as $key => $value) {

        if ($key === 'id' || $key === 'thumbnailUrl_existing' || $key === 'add_to_radio' || $key === 'interview_audio_existing') {
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
        $this->SongModel->sync_related_junction_tables((int) $songId, $data);
        if ($interviewAudioPath !== '' && $this->db->table_exists('songs')) {
            $this->db->where('id', (int) $songId)->update('songs', ['interview_audio' => $interviewAudioPath]);
        }
        if ($this->input->post('add_to_radio')) {
            $this->_upsert_radio_for_song((int) $songId);
        }
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

        // Split the entered name into first / middle / last:
        //   1 word  → first only
        //   2 words → first + last
        //   3+      → first + middle (everything between) + last
        $parts = preg_split('/\s+/', $name);
        $first = isset($parts[0]) ? $parts[0] : '';
        $middle = '';
        $last = '';
        if (count($parts) === 2) {
            $last = $parts[1];
        } elseif (count($parts) > 2) {
            $last = array_pop($parts);            // strip last word
            array_shift($parts);                  // strip first word
            $middle = implode(' ', $parts);       // what's left = middle
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

    /**
     * AJAX: Create a new glossary word in the `word` table — supports original / translation / transliteration / meaning.
     */
    public function ajax_create_glossary_word() {
        $this->output->set_content_type('application/json');
        $original = trim((string) $this->input->post('word_original'));
        $translation = trim((string) $this->input->post('word_translation'));
        $translit = trim((string) $this->input->post('word_transliteration'));
        $meaning = trim((string) $this->input->post('glossary_meaning'));
        if ($translit === '') {
            echo json_encode(['status' => 'error', 'message' => 'Transliteration is required']);
            return;
        }
        if (!$this->db->table_exists('word')) {
            echo json_encode(['status' => 'error', 'message' => 'word table not found']);
            return;
        }
        // Check duplicate by transliteration
        $existing = $this->db->where('word_transliteration', $translit)->get('word')->row_array();
        if (!empty($existing)) {
            echo json_encode([
                'status' => 'success',
                'id' => (int) $existing['id'],
                'label' => $translit,
                'message' => 'Word already exists, selected.'
            ]);
            return;
        }
        // Build payload only with columns that actually exist (keep insert robust against schema variants)
        $cols = $this->db->list_fields('word');
        $isGlossaryRaw = strtolower((string) $this->input->post('is_glossary_word'));
        $isGlossary = in_array($isGlossaryRaw, ['1', 'true', 'yes', 'on'], true) ? '1' : '0';
        $maybe = [
            'word_original' => $original,
            'word_translation' => $translation,
            'word_transliteration' => $translit,
            'glossary_meaning' => $meaning,
            'meaning' => $meaning,
            'is_glossary_word' => $isGlossary,
        ];
        $payload = [];
        foreach ($maybe as $k => $v) {
            if (in_array($k, $cols, true)) { $payload[$k] = $v; }
        }
        if (empty($payload)) {
            echo json_encode(['status' => 'error', 'message' => 'No matching columns to insert']);
            return;
        }
        $ok = $this->db->insert('word', $payload);
        if (!$ok) {
            $err = $this->db->error();
            echo json_encode(['status' => 'error', 'message' => !empty($err['message']) ? $err['message'] : 'Insert failed']);
            return;
        }
        $newId = (int) $this->db->insert_id();
        echo json_encode(['status' => 'success', 'id' => $newId, 'label' => $translit, 'message' => 'Glossary word added.']);
    }

    public function ajax_create_keyword() {
        $this->output->set_content_type('application/json');
        $translit = trim((string) $this->input->post('word_transliteration'));
        $original = trim((string) $this->input->post('word_original'));
        $translation = trim((string) $this->input->post('word_translation'));
        $meaning  = trim((string) $this->input->post('glossary_meaning'));
        if ($translit === '') {
            echo json_encode([
                'success' => false,
                'status'  => 'error',
                'message' => 'Transliteration required',
            ]);
            return;
        }
        if (!$this->db->table_exists('word')) {
            echo json_encode(['success' => false, 'status' => 'error', 'message' => 'word table not found']);
            return;
        }
        // Look for an existing word by transliteration so we don't create duplicates.
        $existing = $this->db->get_where('word', ['word_transliteration' => $translit])->row_array();
        $row = [
            'word_transliteration' => $translit,
            'word_original'        => $original,
            'word_translation'     => $translation,
            'glossary_meaning'     => $meaning,
        ];
        if ($existing) {
            $id = (int) $existing['id'];
            // Update any newly-provided fields without wiping existing non-empty values.
            $update = [];
            foreach ($row as $k => $v) {
                if ($v !== '') { $update[$k] = $v; }
            }
            if (!empty($update)) { $this->db->where('id', $id)->update('word', $update); }
        } else {
            $this->db->insert('word', $row);
            $id = (int) $this->db->insert_id();
        }
        echo json_encode([
            'success' => true,
            'status'  => 'success',
            'id'      => $id,
            'keyword_id'           => $id,
            'word_transliteration' => $translit,
            'word_original'        => $original,
            'word_translation'     => $translation,
            'glossary_meaning'     => $meaning,
            'message' => 'Keyword saved successfully',
        ]);
    }

    /**
     * Fetch a single word's full data so the Edit popup can pre-fill every field
     * from the DB (Original / Translation / Transliteration / Word Meaning).
     */
    public function ajax_get_keyword() {
        $this->output->set_content_type('application/json');
        $id = (int) ($this->input->post('id') ?: $this->input->get('id'));
        if ($id <= 0) { echo json_encode(['success' => false, 'message' => 'id required']); return; }
        if (!$this->db->table_exists('word')) { echo json_encode(['success' => false, 'message' => 'word table not found']); return; }
        $row = $this->db->select('id, word_original, word_translation, word_transliteration, glossary_meaning')
            ->get_where('word', ['id' => $id])->row_array();
        if (!$row) { echo json_encode(['success' => false, 'message' => 'not found']); return; }
        echo json_encode([
            'success' => true,
            'status'  => 'success',
            'id'      => (int) $row['id'],
            'word_original'        => (string) $row['word_original'],
            'word_translation'     => (string) $row['word_translation'],
            'word_transliteration' => (string) $row['word_transliteration'],
            'glossary_meaning'     => (string) $row['glossary_meaning'],
        ]);
    }

    /**
     * Fetch a single person row so the Edit popup (Singer / Poet / Attributed
     * Poet / Translator / Director / Speaker) can pre-fill BOTH the name AND
     * the optional hyperlink from the database.
     */
    public function ajax_get_person() {
        $this->output->set_content_type('application/json');
        $id = (int) ($this->input->post('id') ?: $this->input->get('id'));
        if ($id <= 0) { echo json_encode(['success' => false, 'message' => 'id required']); return; }
        $cols = $this->db->list_fields('person');
        $select = ['id', 'first_name', 'middle_name', 'last_name'];
        if (in_array('hyperlink', $cols, true)) { $select[] = 'hyperlink'; }
        $row = $this->db->select(implode(',', $select))->get_where('person', ['id' => $id])->row_array();
        if (!$row) { echo json_encode(['success' => false, 'message' => 'not found']); return; }
        $parts = array_filter([
            trim((string) ($row['first_name']  ?? '')),
            trim((string) ($row['middle_name'] ?? '')),
            trim((string) ($row['last_name']   ?? '')),
        ], 'strlen');
        $name = implode(' ', $parts);
        echo json_encode([
            'success'   => true,
            'status'    => 'success',
            'id'        => (int) $row['id'],
            'name'      => $name,
            'fullName'  => $name,
            'hyperlink' => (string) ($row['hyperlink'] ?? ''),
        ]);
    }

    /**
     * Like ajax_get_person but for the glossary-word modal: returns all 4
     * fields (original / translation / transliteration / meaning) so the Edit
     * popup pre-fills everything from the database.
     */
    public function ajax_get_glossary_word() {
        // Same shape as ajax_get_keyword — they read the same `word` table.
        $this->ajax_get_keyword();
    }

    /**
     * Admin-wide safe delete used by the "Delete" button next to Add/Edit
     * on every select field across the admin forms.
     *
     * Accepts POST { entity, id }. `entity` is one of:
     *   person   → deletes from `person` table (Singer/Poet/Translator/Director/Speaker/etc.)
     *   word     → deletes from `word` table   (Keyword/Glossary word)
     *   category → deletes from `category` table (Occupation / Profile Tag)
     *
     * Before deleting, we count how many other rows reference this id in known
     * junction/foreign-key tables. If any reference exists, the delete is
     * rejected with the count so the admin can clean up the links first.
     * This prevents the "broken option" problem where a record disappears but
     * is still listed as a CSV id on songs/couplets/etc.
     */
    public function ajax_delete_entity() {
        $this->output->set_content_type('application/json');
        $entity = strtolower(trim((string) $this->input->post('entity')));
        $id     = (int) $this->input->post('id');
        if ($id <= 0) { echo json_encode(['success' => false, 'message' => 'Invalid id']); return; }

        // entity → [primary_table, [ [ref_table, ref_column, friendly_name], ... ]]
        $registry = [
            'person'   => [
                'table' => 'person',
                'refs'  => [
                    ['song_singer',                    'singer_id',  'songs (as singer)'],
                    ['song_poet',                      'poet_id',    'songs (as poet)'],
                    ['song_person',                    'person_id',  'songs (other roles)'],
                    ['couplet_poet',                   'poet_id',    'couplets (as poet)'],
                    ['couplet_people',                 'person_id',  'couplets (other roles)'],
                    ['couplet_translation_translators','person_id',  'couplet translators'],
                    ['reflection_person',              'person_id',  'reflections'],
                    ['film_director',                  'director_id','films (as director)'],
                    ['film_episode_director',          'director_id','film episodes (as director)'],
                    ['film_episode_people',            'person_id',  'film episodes (people)'],
                    ['film_primary_people',            'person_id',  'films (primary people)'],
                    ['film_secondary_people',          'person_id',  'films (secondary people)'],
                    ['story_author',                   'person_id',  'stories (as author)'],
                    ['story_people',                   'person_id',  'stories (people)'],
                    ['word_person',                    'person_id',  'words'],
                    ['contribute_people',              'person_id',  'contributions'],
                    ['person_category',                'person_id',  'category links'],
                ],
            ],
            'word'     => [
                'table' => 'word',
                'refs'  => [
                    ['song_word',         'word_id', 'songs'],
                    ['couplet_word',      'word_id', 'couplets'],
                    ['echo_word',         'word_id', 'echoes'],
                    ['film_episode_word', 'word_id', 'film episodes'],
                    ['film_primary_word', 'word_id', 'films (primary)'],
                    ['film_secondary_word','word_id','films (secondary)'],
                    ['related_words',     'word_id', 'related words'],
                    ['story_word',        'word_id', 'stories'],
                    ['word_introduction', 'word_id', 'word introductions'],
                    ['word_person',       'word_id', 'people'],
                    ['word_reflection',   'word_id', 'reflections'],
                    ['word_synonyms',     'word_id', 'synonyms'],
                    ['word_writer',       'word_id', 'writers'],
                    ['contribute_word',   'word_id', 'contributions'],
                ],
            ],
            'category' => [
                'table' => 'category',
                'refs'  => [
                    ['person_category', 'category_id', 'people'],
                    ['title',           'category_id', 'titles'],
                ],
            ],
            'title'    => [
                // Umbrella Title — used as song.umbrella_title_id / song.song_title_id.
                // We can't easily know which sibling-id row in the duplicate group is
                // referenced, so we check both columns on the songs/song tables.
                'table' => 'title',
                'refs'  => [
                    ['song',  'umbrella_title_id', 'songs (as umbrella title)'],
                    ['song',  'song_title_id',     'songs (as song title)'],
                    ['songs', 'umbrella_title_id', 'songs legacy (as umbrella title)'],
                    ['songs', 'song_title_id',     'songs legacy (as song title)'],
                ],
            ],
        ];

        if (!isset($registry[$entity])) {
            echo json_encode(['success' => false, 'message' => 'Unsupported entity']); return;
        }
        $cfg = $registry[$entity];
        if (!$this->db->table_exists($cfg['table'])) {
            echo json_encode(['success' => false, 'message' => 'Table not found']); return;
        }
        // Confirm the row actually exists.
        $row = $this->db->where('id', $id)->get($cfg['table'])->row();
        if (!$row) {
            echo json_encode(['success' => false, 'message' => 'Record not found']); return;
        }

        // Check every junction/reference table — if ANY still points at this id, refuse.
        // Skip tables that don't exist OR are missing the expected column (the schema
        // varies between legacy `songs` and modern `song`, so being defensive avoids
        // "Unknown column" DB errors that would otherwise turn the JSON response
        // into an HTML 500 page).
        $blockers = [];
        foreach ($cfg['refs'] as $ref) {
            list($refTable, $refCol, $friendly) = $ref;
            if (!$this->db->table_exists($refTable)) { continue; }
            $cols = $this->db->list_fields($refTable);
            if (!in_array($refCol, $cols, true)) { continue; }
            $cnt = (int) $this->db->where($refCol, $id)->count_all_results($refTable);
            if ($cnt > 0) { $blockers[] = $friendly . ' (' . $cnt . ')'; }
        }
        if (!empty($blockers)) {
            echo json_encode([
                'success' => false,
                'in_use'  => true,
                'message' => 'Cannot delete — this item is still linked to: ' . implode(', ', $blockers) . '. Please remove those links first.',
                'blockers'=> $blockers,
            ]);
            return;
        }

        $this->db->where('id', $id)->delete($cfg['table']);
        echo json_encode([
            'success' => true,
            'message' => 'Deleted successfully',
            'id'      => $id,
            'entity'  => $entity,
        ]);
    }

    // ----- Generic AJAX updaters used by admin "Edit" buttons next to "Add New" -----

    public function ajax_update_person() {
        $this->output->set_content_type('application/json');
        $id = (int)$this->input->post('id');
        $name = trim((string)$this->input->post('name'));
        if ($id <= 0 || $name === '') {
            echo json_encode(['success' => false, 'message' => 'id and name are required']); return;
        }
        // Same name-splitting rule as ajax_create_person: 1 word → first only,
        // 2 words → first + last, 3+ words → first + middle + last.
        $parts = preg_split('/\s+/', $name);
        $first = isset($parts[0]) ? $parts[0] : '';
        $middle = '';
        $last = '';
        if (count($parts) === 2) {
            $last = $parts[1];
        } elseif (count($parts) > 2) {
            $last = array_pop($parts);
            array_shift($parts);
            $middle = implode(' ', $parts);
        }
        $data = ['first_name' => $first, 'middle_name' => $middle, 'last_name' => $last];
        $hyperlink = $this->input->post('hyperlink');
        if ($hyperlink !== null && in_array('hyperlink', $this->db->list_fields('person'), true)) {
            $data['hyperlink'] = trim((string)$hyperlink);
        }
        $this->db->where('id', $id)->update('person', $data);
        $fullName = trim($first . ' ' . ($middle ? $middle . ' ' : '') . $last);
        echo json_encode(['success' => true, 'id' => (string)$id, 'fullName' => $fullName !== '' ? $fullName : $name]);
    }

    public function ajax_update_translator() {
        $this->output->set_content_type('application/json');
        $id = (int)$this->input->post('id');
        $name = trim((string)$this->input->post('name'));
        if ($id <= 0 || $name === '') {
            echo json_encode(['success' => false, 'message' => 'id and name are required']); return;
        }
        if (!$this->db->table_exists('translator')) {
            echo json_encode(['success' => false, 'message' => 'translator table not found']); return;
        }
        $fields = $this->db->list_fields('translator');
        $idCol = in_array('id', $fields) ? 'id' : $fields[0];
        $nameCol = null;
        foreach (['name','translator_name','title'] as $cand) { if (in_array($cand, $fields)) { $nameCol = $cand; break; } }
        if ($nameCol === null) { echo json_encode(['success' => false, 'message' => 'No translator name column']); return; }
        $data = [$nameCol => $name];
        $hyperlink = $this->input->post('hyperlink');
        if ($hyperlink !== null) {
            if (in_array('hyperlink', $fields)) $data['hyperlink'] = trim((string)$hyperlink);
            else if (in_array('hyperling', $fields)) $data['hyperling'] = trim((string)$hyperlink);
        }
        $this->db->where($idCol, $id)->update('translator', $data);
        echo json_encode(['success' => true, 'id' => (string)$id, 'name' => $name]);
    }

    public function ajax_update_glossary_word() {
        $this->output->set_content_type('application/json');
        $id = (int)$this->input->post('id');
        $translit = trim((string)$this->input->post('word_transliteration'));
        if ($id <= 0 || $translit === '') {
            echo json_encode(['status' => 'error', 'message' => 'id and transliteration are required']); return;
        }
        if (!$this->db->table_exists('word')) { echo json_encode(['status' => 'error', 'message' => 'word table not found']); return; }
        $cols = $this->db->list_fields('word');
        $maybe = [
            'word_transliteration' => $translit,
            'word_original' => trim((string)$this->input->post('word_original')),
            'word_translation' => trim((string)$this->input->post('word_translation')),
            'glossary_meaning' => trim((string)$this->input->post('glossary_meaning')),
            'meaning' => trim((string)$this->input->post('glossary_meaning')),
        ];
        $payload = [];
        foreach ($maybe as $k => $v) { if (in_array($k, $cols, true) && $v !== '') $payload[$k] = $v; }
        if (empty($payload)) { echo json_encode(['status' => 'error', 'message' => 'No matching columns to update']); return; }
        $this->db->where('id', $id)->update('word', $payload);
        echo json_encode(['status' => 'success', 'id' => $id, 'label' => $translit]);
    }

    public function ajax_update_keyword() {
        $this->output->set_content_type('application/json');
        $id       = (int) $this->input->post('id');
        $translit = trim((string) $this->input->post('word_transliteration'));
        $original = $this->input->post('word_original');
        $translation = $this->input->post('word_translation');
        $meaning  = $this->input->post('glossary_meaning');
        if ($id <= 0 || $translit === '') {
            echo json_encode(['success' => false, 'status' => 'error', 'message' => 'id and transliteration required']); return;
        }
        if (!$this->db->table_exists('word')) { echo json_encode(['success' => false, 'status' => 'error', 'message' => 'word table not found']); return; }
        // Only update keys that were actually posted, so absent fields in the request
        // don't accidentally wipe existing DB values.
        $update = ['word_transliteration' => $translit];
        if ($original    !== null) { $update['word_original']    = trim((string) $original); }
        if ($translation !== null) { $update['word_translation'] = trim((string) $translation); }
        if ($meaning     !== null) { $update['glossary_meaning'] = trim((string) $meaning); }
        $this->db->where('id', $id)->update('word', $update);
        echo json_encode([
            'success' => true,
            'status'  => 'success',
            'id'      => $id,
            'word_transliteration' => $translit,
            'word_original'        => isset($update['word_original'])    ? $update['word_original']    : null,
            'word_translation'     => isset($update['word_translation']) ? $update['word_translation'] : null,
            'glossary_meaning'     => isset($update['glossary_meaning']) ? $update['glossary_meaning'] : null,
        ]);
    }

    private function _radio_resolve_singer_csv($singer_field) {
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

    private function _upsert_radio_for_song($songId) {
        $songId = (int) $songId;
        if ($songId <= 0 || !$this->db->table_exists('radio')) {
            return;
        }
        $this->load->model('SongModel');
        $songRow = $this->SongModel->get_song_by_id($songId);
        if (!is_array($songRow)) {
            return;
        }
        $song_name = trim((string) ($songRow['Songtitle_transliteration'] ?? $songRow['songTitle'] ?? ''));
        if ($song_name === '') {
            $song_name = trim((string) ($songRow['songTitleOriginal'] ?? ''));
        }
        $singerCsv = isset($songRow['singer']) ? trim((string) $songRow['singer']) : '';
        $singer_name = $singerCsv !== '' ? $this->_radio_resolve_singer_csv($singerCsv) : '';
        $audio = '';
        if ($this->db->table_exists('songs')) {
            $r = $this->db->select('interview_audio')->from('songs')->where('id', $songId)->get()->row_array();
            $audio = trim((string) ($r['interview_audio'] ?? ''));
        }
        $row = [
            'songs'                => (string) $songId,
            'song_name'            => $song_name !== '' ? $song_name : null,
            'singer_name'          => $singer_name !== '' ? $singer_name : null,
            'location'             => trim((string) ($songRow['location'] ?? '')) ?: null,
            'year'                 => trim((string) ($songRow['year'] ?? '')) ?: null,
            'upload_song_mp3_file' => $audio !== '' ? $audio : null,
            'radio_excerpt'        => trim((string) ($songRow['thumbnailexcerpt'] ?? '')) ?: null,
        ];
        $existing = $this->db->where('songs', (string) $songId)->get('radio')->row();
        if ($existing) {
            $this->db->where('id', (int) $existing->id)->update('radio', $row);
        } else {
            $row['publish'] = 0;
            $this->db->insert('radio', $row);
        }
    }
}

