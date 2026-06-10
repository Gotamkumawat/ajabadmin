<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KeywordsController extends CI_Controller {

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


    public function keywords(){
        $this->load->view('add-keywords'); // View load
    }

    // Add/Edit Keywords
    public function add_keywords($id = null) {
        $data = [];
        if ($id) {
            $keyword = $this->db->get_where('keywords', ['id' => $id])->row_array();
            if (!$keyword) show_404();
            $data['keyword'] = $keyword;
            $data['form_action'] = base_url('keywords/save');
        } else {
            $data['keyword'] = [];
            $data['form_action'] = base_url('keywords/save');
        }
        $this->load->view('add-keywords', $data);
    }

    // Edit Keywords
    public function edit($id) {
        // List view sources from `word` table now — try there first, fallback to legacy `keywords`
        $row = null;
        if ($this->db->table_exists('word')) {
            $row = $this->db->get_where('word', ['id' => $id])->row_array();
        }
        if (empty($row) && $this->db->table_exists('keywords')) {
            $row = $this->db->get_where('keywords', ['id' => $id])->row_array();
        }
        if (empty($row)) { show_404(); }
        $data['keyword'] = $row;
        $data['form_action'] = base_url('keywords/save');
        $this->load->view('add-keywords', $data);
    }

    // Delete Keyword (from `word` table)
    public function delete($id) {
        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid keyword ID!');
            redirect('keywords-lists');
            return;
        }
        $deleted = false;
        if ($this->db->table_exists('word')) {
            $this->db->where('id', $id);
            $deleted = $this->db->delete('word');
        }
        if (!$deleted && $this->db->table_exists('keywords')) {
            $this->db->where('id', $id);
            $deleted = $this->db->delete('keywords');
        }
        if ($deleted) {
            $this->session->set_flashdata('success', 'Keyword deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete the keyword.');
        }
        redirect('keywords-lists');
    }

    public function save() {
        $id = $this->input->post('id');

        $arrToCsv = function ($v) {
            if (is_array($v)) return implode(',', array_filter($v, function ($x) { return trim((string)$x) !== ''; }));
            return (string)($v ?? '');
        };

        if ($this->db->table_exists('word')) {
            // Word Meaning: prefer canonical `meaning` POST field; legacy `glossary_meaning` fallback
            $wordMeaning = $this->input->post('meaning');
            if ($wordMeaning === null || trim((string)$wordMeaning) === '') {
                $wordMeaning = $this->input->post('glossary_meaning') ?? '';
            }
            // Map form fields → `word` table columns
            $data = [
                'word_original'        => $this->input->post('word_original') ?? '',
                'word_transliteration' => $this->input->post('word_transliteration') ?? '',
                'word_translation'     => $this->input->post('word_translation') ?? '',
                'diacritic'            => $this->input->post('diacritic_text') ?? '',
                'related_songs'        => $arrToCsv($this->input->post('related_songs')),
                'related_poems'        => $arrToCsv($this->input->post('related_poems')),
                'related_reflections'  => $arrToCsv($this->input->post('related_reflections')),
                'related_films'        => $arrToCsv($this->input->post('related_films')),
                'related_episodes'     => $arrToCsv($this->input->post('related_film_episodes') ?: $this->input->post('related_episodes')),
                'is_glossary_word'     => $this->input->post('is_glossary') ? '1' : '0',
                // Save Word Meaning to `meaning` (canonical); also mirror to `glossary_meaning` for legacy reads
                'meaning'              => $wordMeaning,
                'glossary_meaning'     => $wordMeaning,
                'meta_title'           => $this->input->post('meta_title') ?? '',
                'meta_keywords'        => $this->input->post('meta_keywords') ?? '',
                'meta_description'     => $this->input->post('meta_description') ?? '',
                'publish'              => in_array(strtolower((string)$this->input->post('is_published')), ['1', 'true', 'yes', 'on'], true) ? '1' : '0',
            ];
            // Filter to actual columns present
            $cols = $this->db->list_fields('word');
            $data = array_intersect_key($data, array_flip($cols));
            $wordId = 0;
            if ($id) {
                $this->db->where('id', $id);
                $this->db->update('word', $data);
                $wordId = (int) $id;
                $this->session->set_flashdata('success', 'Keyword updated successfully!');
            } else {
                $this->db->insert('word', $data);
                $wordId = (int) $this->db->insert_id();
                $this->session->set_flashdata('success', 'Keyword added successfully!');
            }
            // Sync canonical junction tables for Related Content
            $this->sync_song_word_junction($wordId, $this->input->post('related_songs'));
            $this->sync_word_junction('couplet_word',      'couplet_id',      $wordId, $this->input->post('related_poems'));
            $this->sync_word_junction('word_reflection',   'reflection_id',   $wordId, $this->input->post('related_reflections'));
            $this->sync_word_junction('film_primary_word', 'film_id',         $wordId, $this->input->post('related_films'));
            $this->sync_word_junction('film_episode_word', 'film_episode_id', $wordId, $this->input->post('related_film_episodes'));
        } else {
            // Legacy fallback to `keywords` table
            $data = [
                'word_original'        => $this->input->post('word_original'),
                'word_transliteration' => $this->input->post('word_transliteration'),
                'is_keyword'           => $this->input->post('is_keyword') ? 1 : 0,
                'word_translation'     => $this->input->post('word_translation'),
                'related_songs'        => $arrToCsv($this->input->post('related_songs')),
                'related_poems'        => $arrToCsv($this->input->post('related_poems')),
                'related_reflections'  => $arrToCsv($this->input->post('related_reflections')),
                'related_films'        => $arrToCsv($this->input->post('related_films')),
                'related_film_episodes'=> $arrToCsv($this->input->post('related_film_episodes')),
                'is_glossary'          => $this->input->post('is_glossary') ? 1 : 0,
                'diacritic_text'       => $this->input->post('diacritic_text'),
                'glossary_meaning'     => $this->input->post('glossary_meaning'),
                'meta_title'           => $this->input->post('meta_title'),
                'meta_keywords'        => $this->input->post('meta_keywords'),
                'meta_description'     => $this->input->post('meta_description'),
                'is_published'         => $this->input->post('is_published'),
            ];
            if ($id) {
                $this->db->where('id', $id);
                $this->db->update('keywords', $data);
                $this->session->set_flashdata('success', 'Keyword updated successfully!');
            } else {
                $this->db->insert('keywords', $data);
                $this->session->set_flashdata('success', 'Keyword added successfully!');
            }
        }

        redirect('keywords-lists');
    }

    // Fetch Keywords for DataTable — sourced from `word` table
    /**
     * Mirror song selections into song_word junction (song_id, word_id).
     */
    private function sync_song_word_junction($wordId, $songs) {
        $this->sync_word_junction('song_word', 'song_id', $wordId, $songs);
    }

    /**
     * Generic helper: replace junction rows for a word with given IDs.
     * Junction is assumed to have: word_id + $fkCol.
     */
    private function sync_word_junction($table, $fkCol, $wordId, $ids) {
        if (!$this->db->table_exists($table)) return;
        $wordId = (int) $wordId;
        if ($wordId <= 0) return;
        $clean = [];
        if (is_array($ids)) {
            foreach ($ids as $v) { $v = (int) $v; if ($v > 0) { $clean[$v] = true; } }
        } elseif ($ids !== null && trim((string)$ids) !== '') {
            foreach (explode(',', (string)$ids) as $v) { $v = (int) trim($v); if ($v > 0) { $clean[$v] = true; } }
        }
        $this->db->where('word_id', $wordId)->delete($table);
        foreach (array_keys($clean) as $vid) {
            $this->db->insert($table, ['word_id' => $wordId, $fkCol => $vid]);
        }
    }

    public function fetch_keywords()
    {
        $words = $this->db->order_by('id', 'DESC')->get('word')->result_array();
        $data = [];
        $i = 1;
        foreach ($words as $row) {
            $translit = trim((string) ($row['word_transliteration'] ?? ''));
            $translat = trim((string) ($row['word_translation'] ?? ''));
            // Fallback: if translation empty, try original
            if ($translat === '') {
                $translat = trim((string) ($row['word_original'] ?? ''));
            }
            // is_glossary_word in `word` table is varchar — accept '1', 'yes', 'true' as truthy
            $glossaryRaw = strtolower(trim((string) ($row['is_glossary_word'] ?? '')));
            $isGlossary = in_array($glossaryRaw, ['1', 'yes', 'true', 'on'], true) ? 'Yes' : 'No';

            $data[] = [
                'id' => $i++,
                'word_transliteration' => $translit !== '' ? $translit : '—',
                'word_translation' => $translat !== '' ? $translat : '—',
                'is_glossary' => $isGlossary,
                'actions' => '<button type="button" class="btn btn-sm btn-info admin-preview-btn" data-id="'.$row['id'].'">Preview</button>
                              <a href="'.base_url('edit-keyword/'.$row['id']).'" class="btn btn-sm btn-primary">Edit</a>
                              <a href="'.base_url('delete-keyword/'.$row['id']).'" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>'
            ];
        }

        echo json_encode(['data' => $data]);
    }

    // Keywords List View
    public function keywordslists() {
        $this->load->view('keywords-lists');
    }
}

