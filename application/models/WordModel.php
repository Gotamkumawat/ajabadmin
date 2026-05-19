<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WordModel extends CI_Model {

    public function insert_word($data) {
        return $this->db->insert('word', $data);
    }

    public function fetch_words() {
        $this->db->select('*');
        $this->db->from('word');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_word_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('word');
        return $query->row();
    }

    public function update_word($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('word', $data);
    }

    public function delete_word($id) {
        $this->db->where('id', $id);
        return $this->db->delete('word');
    }

    /**
     * Related-keywords selects store word.id. Match or create a row in `word` by transliteration.
     *
     * @return array{id: int|string, word_transliteration: string}|null
     */
    public function get_or_create_word_keyword($word) {
        $word = trim((string) $word);
        if ($word === '' || !$this->db->table_exists('word')) {
            return null;
        }
        $low = strtolower($word);
        $existing = $this->db->query(
            'SELECT id, word_transliteration FROM word WHERE LOWER(TRIM(COALESCE(word_transliteration, \'\'))) = ? LIMIT 1',
            [$low]
        )->row_array();
        if (!empty($existing)) {
            $label = isset($existing['word_transliteration']) ? trim((string) $existing['word_transliteration']) : '';
            if ($label === '') {
                $label = $word;
            }

            return [
                'id' => $existing['id'],
                'word_transliteration' => $label,
            ];
        }
        $insert = ['word_transliteration' => $word];
        if ($this->db->field_exists('word_original', 'word')) {
            $insert['word_original'] = $word;
        }
        if ($this->db->field_exists('is_this_keyword', 'word')) {
            $insert['is_this_keyword'] = 1;
        }
        if (!$this->db->insert('word', $insert)) {
            return null;
        }

        return [
            'id' => $this->db->insert_id(),
            'word_transliteration' => $word,
        ];
    }
}