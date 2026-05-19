<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GlossaryController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('upload');
        $this->load->model('GlossaryModel');
        
		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
    }

    // Glossary list view
    public function glossary_list() {
        $this->load->view('glossary-list');
    }

    // Add/Edit Glossary page
    public function add_glossary($id = null) {
        $data = [];
        if ($id) {
            $glossary = $this->GlossaryModel->get_glossary_by_id($id);
            if (!$glossary) {
                $this->session->set_flashdata('error', 'Glossary not found!');
                redirect('glossary-lists');
            }
            $data['glossary'] = $glossary;
        } else {
            $data['glossary'] = null;
        }
        
        // Get related data for dropdowns
        $data['songs'] = $this->db->get('songs')->result_array();
        $data['poems'] = $this->db->get('couplet')->result_array();
        
        $this->load->view('add-glossary', $data);
    }

    // Save or Update Glossary
    public function save() {
        $id = $this->input->post('id');

        // Only keep fields present in the new form
        $data = array(
            'glossary_term' => $this->input->post('glossary_term'),
            'diacritic' => $this->input->post('diacritic'),
            'glossary_meaning' => $this->input->post('glossary_meaning'),
            'related_songs' => is_array($this->input->post('related_songs')) ? implode(',', $this->input->post('related_songs')) : '',
            'related_poems' => is_array($this->input->post('related_poems')) ? implode(',', $this->input->post('related_poems')) : '',
            'related_reflections' => is_array($this->input->post('related_reflections')) ? implode(',', $this->input->post('related_reflections')) : '',
            'related_films' => is_array($this->input->post('related_films')) ? implode(',', $this->input->post('related_films')) : '',
            'related_film_episodes' => is_array($this->input->post('related_film_episodes')) ? implode(',', $this->input->post('related_film_episodes')) : '',
            'meta_title' => $this->input->post('meta_title'),
            'meta_keywords' => $this->input->post('meta_keywords'),
            'meta_description' => $this->input->post('meta_description'),
            'is_published' => $this->input->post('is_published') ? 1 : 0,
            'date_created' => date('Y-m-d H:i:s')
        );

        if ($id) {
            $data['date_updated'] = date('Y-m-d H:i:s');
            $update = $this->GlossaryModel->update_glossary($id, $data);
            if ($update) {
                $this->session->set_flashdata('success', 'Glossary updated successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to update glossary: ' . $this->db->_error_message());
            }
        } else {
            $insert = $this->GlossaryModel->insert_glossary($data);
            if ($insert) {
                $this->session->set_flashdata('success', 'Glossary created successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to create glossary: ' . $this->db->_error_message());
            }
        }

        redirect('glossary-lists');
    }

    // Edit Glossary
    public function edit($id) {
        $glossary = $this->GlossaryModel->get_glossary_by_id($id);
        if (!$glossary) {
            $this->session->set_flashdata('error', 'Glossary not found!');
            redirect('glossary-lists');
        }
        
        $data['glossary'] = $glossary;
        $data['songs'] = $this->db->get('songs')->result_array();
        $data['poems'] = $this->db->get('couplet')->result_array();
        
        $this->load->view('add-glossary', $data);
    }

    // Delete Glossary
    public function delete($id) {
        if (empty($id) || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid glossary ID!']);
            return;
        }

        $delete = $this->GlossaryModel->delete_glossary($id);
        if ($delete) {
            echo json_encode(['status' => 'success', 'message' => 'Glossary deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete glossary: ' . $this->db->_error_message()]);
        }
    }

    // Fetch Glossaries for DataTable
    public function fetch_glossaries() {
        $glossaries = $this->GlossaryModel->get_all_glossaries();
        $data = [];
        $sl_no = 1;

        foreach ($glossaries as $glossary) {
            // Convert related_songs IDs to names
            $song_names = 'N/A';
            if (!empty($glossary['related_songs'])) {
                $song_ids = array_filter(explode(',', $glossary['related_songs']));
                if (!empty($song_ids)) {
                    $this->db->select('Songtitle_transliteration');
                    $this->db->from('songs');
                    $this->db->where_in('id', $song_ids);
                    $songs = $this->db->get()->result_array();
                    $song_names = implode(', ', array_column($songs, 'Songtitle_transliteration'));
                }
            }

            $data[] = [
                'id' => $glossary['id'],
                'sl_no' => $sl_no++,
                'date_created' => $glossary['date_created'] ?? date('Y-m-d H:i:s'),
                'glossary_term' => $glossary['glossary_term'],
                'glossary_meaning' => substr($glossary['glossary_meaning'], 0, 100) . '...',
                'related_songs' => $song_names,
                'is_published' => $glossary['is_published'] == 1 ? 'Yes' : 'No'
            ];
        }

        echo json_encode(['data' => $data]);
    }

    // ============= PLAYLIST METHODS =============

    // Add/Edit Playlist page
    public function add_playlist($id = null) {
        $data = [];
        
        if ($id) {
            // Load existing playlist for editing
            $playlist = $this->db->get_where('playlist', ['id' => $id])->row_array();
            if (!$playlist) {
                $this->session->set_flashdata('error', 'Playlist not found!');
                redirect('playlist-lists');
            }
            $data['playlist'] = $playlist;

            $tracks = [];
            if ($this->db->table_exists('songs')) {
                $this->db->select('playlist_tracks.*, songs.songTitle as song_name, songs.singer as artist');
                $this->db->from('playlist_tracks');
                $this->db->join('songs', 'songs.id = playlist_tracks.song_id', 'left');
                $this->db->where('playlist_tracks.playlist_id', (int) $id);
                $this->db->order_by('playlist_tracks.track_order', 'ASC');
                $tracks = $this->db->get()->result_array();
            } elseif ($this->db->table_exists('song')) {
                $tracks = $this->db->query("
                    SELECT pt.*,
                        COALESCE(NULLIF(TRIM(t.english_transliteration), ''), NULLIF(TRIM(t.original_title), ''), CONCAT('Song #', s.id)) AS song_name,
                        '' AS artist
                    FROM playlist_tracks pt
                    LEFT JOIN song s ON s.id = pt.song_id
                    LEFT JOIN title t ON t.id = s.song_title_id
                    WHERE pt.playlist_id = ?
                    ORDER BY pt.track_order ASC
                ", [(int) $id])->result_array();
            }
            $data['playlist_tracks'] = $tracks;
        } else {
            $data['playlist'] = null;
            $data['playlist_tracks'] = [];
        }

        $this->load->model('SongModel');
        $songTbl = $this->SongModel->song_table_name();
        if ($songTbl === 'songs') {
            $data['songs'] = $this->db->query("
                SELECT id, Songtitle_transliteration AS songTitle, singer
                FROM songs
                ORDER BY LOWER(TRIM(Songtitle_transliteration)) ASC, id ASC
            ")->result_array();
        } else {
            $data['songs'] = $this->db->query("
                SELECT s.id,
                    COALESCE(NULLIF(TRIM(t.english_transliteration), ''), NULLIF(TRIM(t.original_title), ''), CONCAT('Song #', s.id)) AS songTitle,
                    '' AS singer
                FROM song s
                LEFT JOIN title t ON t.id = s.song_title_id
                ORDER BY LOWER(TRIM(COALESCE(t.english_transliteration, t.original_title, ''))) ASC, s.id ASC
            ")->result_array();
        }

        $this->load->view('add-playlist', $data);
    }

    // Save or Update Playlist
    public function save_playlist() {
        $id = $this->input->post('id');
        
        // Handle cover image upload
        $cover_image = '';
        if (!empty($_FILES['cover_image']['name'])) {
            $upload_path = FCPATH . 'uploads/playlist/';
            
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }
            
            $safeName = time() . '_' . basename($_FILES['cover_image']['name']);
            
            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size']      = 2048;
            $config['file_name']     = $safeName;
            $config['overwrite']     = false;
            
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('cover_image')) {
                $uploadData = $this->upload->data();
                $cover_image = 'uploads/playlist/' . $uploadData['file_name'];
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('add-playlist');
                return;
            }
        } else {
            if ($id) {
                $existing = $this->db->get_where('playlist', ['id' => $id])->row_array();
                $cover_image = $existing['cover_image'] ?? '';
            }
        }
        
        // Prepare playlist data
        $playlist_data = array(
            'name' => $this->input->post('playlist_name'),
            'description' => $this->input->post('playlist_description'),
            'cover_image' => $cover_image,
            'is_published' => $this->input->post('is_published') ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        if ($id) {
            // Update existing playlist
            $this->db->where('id', $id);
            $update = $this->db->update('playlist', $playlist_data);
            
            if ($update) {
                // Delete old tracks
                $this->db->where('playlist_id', $id);
                $this->db->delete('playlist_tracks');
                
                // Insert new tracks
                $this->save_playlist_tracks($id);
                
                $this->session->set_flashdata('success', 'Playlist updated successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to update playlist!');
            }
            redirect('add-playlist/' . $id);
        } else {
            // Insert new playlist
            $playlist_data['created_at'] = date('Y-m-d H:i:s');
            $insert = $this->db->insert('playlist', $playlist_data);
            
            if ($insert) {
                $playlist_id = $this->db->insert_id();
                
                // Insert tracks
                $this->save_playlist_tracks($playlist_id);
                
                $this->session->set_flashdata('success', 'Playlist created successfully!');
                redirect('add-playlist/' . $playlist_id);
            } else {
                $this->session->set_flashdata('error', 'Failed to create playlist!');
                redirect('add-playlist');
            }
        }
    }
    
    // Save tracks for playlist
    private function save_playlist_tracks($playlist_id) {
        $tracks_json = $this->input->post('tracks');
        if (!empty($tracks_json)) {
            $track_ids = json_decode($tracks_json, true);
            if (is_array($track_ids) && count($track_ids) > 0) {
                foreach ($track_ids as $index => $song_id) {
                    $track_data = array(
                        'playlist_id' => $playlist_id,
                        'song_id' => $song_id,
                        'track_order' => $index + 1,
                        'added_at' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('playlist_tracks', $track_data);
                }
            }
        }
    }

    // Delete Playlist
    public function delete_playlist($id) {
        if (empty($id) || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid playlist ID!']);
            return;
        }
        
        // Delete tracks first
        $this->db->where('playlist_id', $id);
        $this->db->delete('playlist_tracks');
        
        // Delete playlist
        $this->db->where('id', $id);
        $delete = $this->db->delete('playlist');
        
        if ($delete) {
            echo json_encode(['status' => 'success', 'message' => 'Playlist deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete playlist!']);
        }
    }

    // Playlist list view
    public function playlist_list() {
        $this->load->view('playlist-list');
    }

    // Fetch Playlists for DataTable
    public function fetch_playlists() {
        $this->db->select('playlist.*, COUNT(playlist_tracks.id) as track_count');
        $this->db->from('playlist');
        $this->db->join('playlist_tracks', 'playlist_tracks.playlist_id = playlist.id', 'left');
        $this->db->group_by('playlist.id');
        $this->db->order_by('playlist.created_at', 'DESC');
        $playlists = $this->db->get()->result_array();
        
        $data = [];
        $sl_no = 1;

        foreach ($playlists as $playlist) {
            $data[] = [
                'id' => $playlist['id'],
                'sl_no' => $sl_no++,
                'created_at' => $playlist['created_at'] ?? date('Y-m-d H:i:s'),
                'name' => $playlist['name'],
                'description' => substr(strip_tags($playlist['description']), 0, 100) . '...',
                'track_count' => $playlist['track_count'] ?? 0,
                'is_published' => $playlist['is_published'] == 1 ? 'Yes' : 'No'
            ];
        }

        echo json_encode(['data' => $data]);
    }

    // AJAX: Create playlist from radio form
    public function ajax_create_playlist() {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $title = isset($input['title']) ? trim($input['title']) : '';
        $description = isset($input['description']) ? trim($input['description']) : '';
        
        if (empty($title)) {
            echo json_encode(['success' => false, 'message' => 'Playlist title is required']);
            return;
        }
        
        $data = array(
            'name' => $title,
            'description' => $description,
            'is_published' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        $insert = $this->db->insert('playlist', $data);
        
        if ($insert) {
            $playlist_id = $this->db->insert_id();
            echo json_encode([
                'success' => true,
                'id' => $playlist_id,
                'title' => $title,
                'message' => 'Playlist created successfully'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create playlist']);
        }
    }
}
