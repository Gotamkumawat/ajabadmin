<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CoupletModel extends CI_Model {

    /**
     * Legacy DB stores poem title transliteration/translation in swapped columns relative
     * to UI labels. Apply on both read AND write — swap is self-inverse, so storage stays
     * stable while UI shows the right field for the right label.
     */
    public function swap_transliteration_translation_columns(array $row) {
        if (array_key_exists('couplet_transliteration', $row) || array_key_exists('couplet_translation', $row)) {
            $tmp = $row['couplet_transliteration'] ?? '';
            $row['couplet_transliteration'] = $row['couplet_translation'] ?? '';
            $row['couplet_translation'] = $tmp;
        }
        return $row;
    }

    public function get_couplet_translation_text($coupletId) {
        $coupletId = (int) $coupletId;
        if ($coupletId <= 0 || !$this->db->table_exists('couplet_translation')) {
            return null;
        }
        $row = $this->db->select('english_translation_text')
            ->from('couplet_translation')
            ->where('couplet_id', $coupletId)
            ->order_by('id', 'ASC')
            ->limit(1)
            ->get()
            ->row_array();
        if (!$row) {
            return null;
        }
        return $row['english_translation_text'] ?? null;
    }

    public function get_couplet_translation_rows($coupletId) {
        $coupletId = (int) $coupletId;
        if ($coupletId <= 0 || !$this->db->table_exists('couplet_translation')) {
            return [];
        }
        return $this->db->select('id, english_translation_text')
            ->from('couplet_translation')
            ->where('couplet_id', $coupletId)
            ->order_by('id', 'ASC')
            ->get()
            ->result_array();
    }

    public function sync_couplet_translations($coupletId, $primaryText, $extraTexts = []) {
        $coupletId = (int) $coupletId;
        if ($coupletId <= 0 || !$this->db->table_exists('couplet_translation')) {
            return;
        }
        $texts = [];
        $primary = trim((string)$primaryText);
        if ($primary !== '') {
            $texts[] = $primary;
        }
        if (is_array($extraTexts)) {
            foreach ($extraTexts as $txt) {
                $t = trim((string)$txt);
                if ($t !== '') {
                    $texts[] = $t;
                }
            }
        }
        // keep order and remove duplicates
        $texts = array_values(array_unique($texts));

        $this->db->where('couplet_id', $coupletId)->delete('couplet_translation');
        foreach ($texts as $txt) {
            $this->db->insert('couplet_translation', [
                'couplet_id' => $coupletId,
                'english_translation_text' => $txt
            ]);
        }
    }

    public function upsert_couplet_translation($coupletId, $translationText) {
        $coupletId = (int) $coupletId;
        if ($coupletId <= 0 || !$this->db->table_exists('couplet_translation')) {
            return;
        }
        $existing = $this->db->get_where('couplet_translation', ['couplet_id' => $coupletId])->row_array();
        if ($existing) {
            $this->db->where('id', (int)$existing['id'])->update('couplet_translation', [
                'english_translation_text' => $translationText
            ]);
            return;
        }
        $this->db->insert('couplet_translation', [
            'couplet_id' => $coupletId,
            'english_translation_text' => $translationText
        ]);
    }

    public function insert_couplet($data) {
        return $this->db->insert('couplet', $data);
    }

    public function get_all_couplets() {
        $query = $this->db->get('couplet');
        $rows = $query->result_array();
        foreach ($rows as &$r) {
            $r = $this->swap_transliteration_translation_columns($r);
        }
        unset($r);
        return $rows;
    }

    public function get_couplet_by_id($id) {
        $row = $this->db->get_where('couplet', ['id' => $id])->row_array();
        if (!$row) {
            return null;
        }
        $id = (int) $id;
        if ($id > 0 && $this->db->table_exists('couplet_poet')) {
            $jrows = $this->db->select('poet_id')
                ->from('couplet_poet')
                ->where('couplet_id', $id)
                ->get()->result_array();
            $jids = array_values(array_unique(array_filter(array_map('intval', array_column($jrows, 'poet_id')))));
            if (!empty($jids)) {
                $poetRaw = isset($row['poet_id']) ? $row['poet_id'] : '';
                $poetEmpty = true;
                if ($poetRaw !== null && $poetRaw !== '') {
                    $u = @unserialize($poetRaw);
                    if ($u !== false && is_array($u)) {
                        foreach ($u as $x) {
                            if ((int) $x > 0) {
                                $poetEmpty = false;
                                break;
                            }
                        }
                    } elseif (is_string($poetRaw) && preg_match('/^\d+$/', trim($poetRaw))) {
                        $poetEmpty = false;
                    }
                }
                if ($poetEmpty) {
                    $row['poet_id'] = serialize($jids);
                }
            }
        }

        // Keep title field legacy swap behavior only.
        $row = $this->swap_transliteration_translation_columns($row);

        // IMPORTANT: Poem Text Transliteration must always come from couplet.english_transliteration_text.
        $row['english_transliteration_text'] = isset($row['english_transliteration_text'])
            ? $row['english_transliteration_text']
            : '';

        // Translation comes from couplet_translation table (if available).
        $translationRows = $this->get_couplet_translation_rows($id);
        if (!empty($translationRows)) {
            $row['english_translation_text'] = (string)($translationRows[0]['english_translation_text'] ?? '');
            $extra = [];
            foreach (array_slice($translationRows, 1) as $tr) {
                $txt = trim((string)($tr['english_translation_text'] ?? ''));
                if ($txt !== '') {
                    $extra[] = ['text' => $txt];
                }
            }
            $row['extra_translation_rows'] = $extra;
        } else {
            $row['extra_translation_rows'] = [];
        }
        return $row;
    }

        public function update_couplet($id, $data) {
            $this->db->where('id', $id);
            return $this->db->update('couplet', $data);
        }
}