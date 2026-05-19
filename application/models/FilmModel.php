
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FilmModel extends CI_Model {

private function apply_optional_film_language_fields(&$filmData, $data) {
        if (!$this->db->table_exists('film') || !is_array($data)) {
            return;
        }
        $fields = $this->db->list_fields('film');
        if (!is_array($fields) || empty($fields)) {
            return;
        }
        $languageLinksJson = isset($data['film_language_links']) ? (string)$data['film_language_links'] : '';
        $firstLanguage = '';
        if ($languageLinksJson !== '') {
            $decoded = json_decode($languageLinksJson, true);
            if (is_array($decoded) && !empty($decoded[0]) && is_array($decoded[0])) {
                $firstLanguage = isset($decoded[0]['language']) ? trim((string)$decoded[0]['language']) : '';
            }
        }
        $optionalMap = [
            'language_video_links' => $languageLinksJson,
            'language_links' => $languageLinksJson,
            'video_links' => $languageLinksJson,
            'youtube_links' => $languageLinksJson,
            'language' => $firstLanguage,
            'lang' => $firstLanguage
        ];
        foreach ($optionalMap as $col => $val) {
            if (in_array($col, $fields, true)) {
                $filmData[$col] = $val;
            }
        }
    }

public function insert_film($data) {
        if ($this->db->table_exists('film')) {
            $filmData = [
                'english_transliteration' => $data['main_title'] ?? null,
                'english_translation' => $data['second_title'] ?? ($data['series_title'] ?? null),
                'original_title' => $data['series_title'] ?? null,
                'description' => $data['series_description'] ?? null,
                'duration' => $data['duration'] ?? null,
                'year_of_production' => $data['year'] ?? null,
                'about_text' => $data['about'] ?? null,
                'thumbnail_url' => $data['thumbnail_Image'] ?? null,
                'thumbnail_excerpt' => $data['thumbnail_excerpt'] ?? null,
                'youtube_video_id' => $data['film_youtube_id'] ?? null,
                'meta_title' => $data['meta_title'] ?? null,
                'meta_keywords' => $data['meta_keyword'] ?? ($data['meta_keywords'] ?? null),
                'meta_description' => $data['meta_description'] ?? null,
                'is_published' => isset($data['publish']) && in_array(strtolower((string)$data['publish']), ['1', 'true', 'yes'], true) ? 1 : 0,
            ];
            $this->apply_optional_film_language_fields($filmData, $data);
            $filmData = array_filter($filmData, function ($v) { return $v !== null; });
            $inserted = $this->db->insert('film', $filmData);
            if ($inserted) {
                $filmId = (int)$this->db->insert_id();
                $this->sync_film_relations($filmId, $data);
            }
            return $inserted;
        }
        return $this->db->insert('film_details', $data);
    }

    public function fetch_all() {
        $this->db->select('*');
        $this->db->from('film');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_film_by_id($id) {
        // Primary source for edit form: normalized film table.
        if ($this->db->table_exists('film')) {
            $filmRow = $this->db
                ->select('*')
                ->from('film')
                ->where('id', $id)
                ->get()
                ->row();
            if ($filmRow) {
                // Deterministic mapping from film table columns for edit form.
                $filmRow->main_title = isset($filmRow->english_transliteration) ? (string)$filmRow->english_transliteration : '';
                $filmRow->second_title = isset($filmRow->english_translation) ? (string)$filmRow->english_translation : '';
                $filmRow->series_title = isset($filmRow->original_title) ? (string)$filmRow->original_title : '';
                $filmRow->series_description = isset($filmRow->description) ? (string)$filmRow->description : '';
                $filmRow->thumbnail_Image = isset($filmRow->thumbnail_url) ? (string)$filmRow->thumbnail_url : '';
                $filmRow->thumbnail_excerpt = isset($filmRow->thumbnail_excerpt) ? (string)$filmRow->thumbnail_excerpt : '';
                $filmRow->film_youtube_id = isset($filmRow->youtube_video_id) ? (string)$filmRow->youtube_video_id : '';
                $filmRow->film_language = '';
                foreach (['language', 'lang'] as $langField) {
                    if (isset($filmRow->$langField) && trim((string)$filmRow->$langField) !== '') {
                        $filmRow->film_language = trim((string)$filmRow->$langField);
                        break;
                    }
                }
                $filmRow->film_language_links = '';
                foreach (['language_video_links', 'language_links', 'video_links', 'youtube_links'] as $langCol) {
                    if (isset($filmRow->$langCol) && trim((string)$filmRow->$langCol) !== '') {
                        $filmRow->film_language_links = (string)$filmRow->$langCol;
                        break;
                    }
                }
                if ($filmRow->film_language_links === '') {
                    $ytRaw = trim((string)$filmRow->film_youtube_id);
                    if ($ytRaw !== '' && ($ytRaw[0] === '[' || $ytRaw[0] === '{')) {
                        $filmRow->film_language_links = $ytRaw;
                    } elseif ($ytRaw !== '') {
                        $filmRow->film_language_links = json_encode([[
                            'language' => $filmRow->film_language,
                            'youtube_link' => $ytRaw
                        ]]);
                    }
                }
                $filmRow->duration = isset($filmRow->duration) ? (string)$filmRow->duration : '';
                $filmRow->year = isset($filmRow->year_of_production) ? (string)$filmRow->year_of_production : '';
                $filmRow->about = isset($filmRow->about_text) && trim((string)$filmRow->about_text) !== ''
                    ? (string)$filmRow->about_text
                    : (isset($filmRow->profile_text) ? (string)$filmRow->profile_text : '');
                $filmRow->meta_title = isset($filmRow->meta_title) ? (string)$filmRow->meta_title : '';
                $filmRow->meta_keyword = isset($filmRow->meta_keywords) ? (string)$filmRow->meta_keywords : '';
                $filmRow->meta_keywords = isset($filmRow->meta_keywords) ? (string)$filmRow->meta_keywords : '';
                $filmRow->meta_description = isset($filmRow->meta_description) ? (string)$filmRow->meta_description : '';
                $filmRow->publish = (isset($filmRow->is_published) && ((string)$filmRow->is_published === '1' || $filmRow->is_published === true)) ? 'true' : 'false';

                // Relational fields from mapping tables.
                $fetchIds = function ($table, $fkCol, $idCol) use ($id) {
                    if (!$this->db->table_exists($table)) return '';
                    $rows = $this->db->select($idCol)->from($table)->where($fkCol, (int)$id)->get()->result_array();
                    $ids = [];
                    foreach ($rows as $r) {
                        if (isset($r[$idCol]) && $r[$idCol] !== null && $r[$idCol] !== '') {
                            $ids[] = (string)$r[$idCol];
                        }
                    }
                    return implode(',', array_values(array_unique($ids)));
                };
                $filmRow->directors = $fetchIds('film_director', 'film_id', 'director_id');
                $filmRow->related_people = $fetchIds('film_primary_people', 'film_id', 'person_id');
                $filmRow->related_reflections = $fetchIds('film_primary_reflection', 'film_id', 'reflection_id');
                $filmRow->related_primary_songs = $fetchIds('film_primary_song', 'film_id', 'song_id');
                $filmRow->related_songs = $filmRow->related_primary_songs;
                $filmRow->related_words = $fetchIds('film_primary_word', 'film_id', 'word_id');
                $filmRow->related_couplets = $fetchIds('film_couplet', 'film_id', 'couplet_id');
                $filmRow->related_poems = $filmRow->related_couplets;
                $filmRow->related_keywords = $filmRow->related_words;
                $filmRow->films = '';
                $filmRow->film_episodes = '';
                $filmRow->related_stories = '';

                // Optional same-id fallback from legacy table only for fields still empty.
                if ($this->db->table_exists('film_details')) {
                    $legacyRow = $this->db->from('film_details')->where('id', $id)->get()->row();
                    if ($legacyRow) {
                        foreach (['thumbnail_excerpt', 'series_title', 'series_description', 'related_stories', 'films', 'film_episodes'] as $k) {
                            if ((!isset($filmRow->$k) || trim((string)$filmRow->$k) === '') && isset($legacyRow->$k) && trim((string)$legacyRow->$k) !== '') {
                                $filmRow->$k = (string)$legacyRow->$k;
                            }
                        }
                    }
                }
            }
            return $filmRow;
        }

        // Final fallback only if film table has no row.
        $this->db->where('id', $id);
        $query = $this->db->get('film_details');
        $row = $query->row();
        if ($row) {
            return $row;
        }

        return null;
    }

    public function update_film($id, $data) {
        if ($this->db->table_exists('film')) {
            $filmData = [
                'english_transliteration' => $data['main_title'] ?? null,
                'english_translation' => $data['second_title'] ?? ($data['series_title'] ?? null),
                'original_title' => $data['series_title'] ?? null,
                'description' => $data['series_description'] ?? null,
                'duration' => $data['duration'] ?? null,
                'year_of_production' => $data['year'] ?? null,
                'about_text' => $data['about'] ?? null,
                'thumbnail_url' => $data['thumbnail_Image'] ?? null,
                'thumbnail_excerpt' => $data['thumbnail_excerpt'] ?? null,
                'youtube_video_id' => $data['film_youtube_id'] ?? null,
                'meta_title' => $data['meta_title'] ?? null,
                'meta_keywords' => $data['meta_keyword'] ?? ($data['meta_keywords'] ?? null),
                'meta_description' => $data['meta_description'] ?? null,
                'is_published' => isset($data['publish']) && in_array(strtolower((string)$data['publish']), ['1', 'true', 'yes', 'on'], true) ? 1 : 0,
            ];
            $this->apply_optional_film_language_fields($filmData, $data);
            $filmData = array_filter($filmData, function ($v) { return $v !== null; });
            if (!empty($filmData)) {
                $this->db->where('id', $id);
                $updated = $this->db->update('film', $filmData);
                if ($updated) {
                    $this->sync_film_relations((int)$id, $data);
                }
                return $updated;
            }
            // Even if scalar columns are unchanged, keep relations synced.
            $this->sync_film_relations((int)$id, $data);
            return true;
        }

        $this->db->where('id', $id);
        $existsInFilmDetails = $this->db->count_all_results('film_details') > 0;
        if ($existsInFilmDetails) {
            $this->db->where('id', $id);
            return $this->db->update('film_details', $data);
        }
        return false;
    }

    public function delete_film($id) {
        $this->db->where('id', $id);
        $existsInFilmDetails = $this->db->count_all_results('film_details') > 0;
        if ($existsInFilmDetails) {
            $this->db->where('id', $id);
            return $this->db->delete('film_details');
        }

        if ($this->db->table_exists('film')) {
            $this->db->where('id', $id);
            return $this->db->delete('film');
        }
        return false;
    }

    public function insert_filmEpicode($data) {
        if ($this->db->table_exists('film_episode')) {
            $payload = $this->map_to_film_episode_payload($data);
            $inserted = $this->db->insert('film_episode', $payload);
            if ($inserted) {
                $episodeId = (int)$this->db->insert_id();
                $this->sync_episode_relations($episodeId, $data);
            }
            return $inserted;
        }
        return $this->db->insert('film_episode_details', $data);
    }

    public function fetch_data() {
        if ($this->db->table_exists('film_episode')) {
            $rows = $this->db
                ->select('fe.*, f.english_transliteration AS film_english_transliteration, f.english_translation AS film_english_translation, f.original_title AS film_original_title')
                ->from('film_episode fe')
                ->join('film f', 'f.id = fe.film_id', 'left')
                ->order_by('fe.id', 'DESC')
                ->get()
                ->result();
            $out = [];
            foreach ($rows as $row) {
                $out[] = $this->normalize_film_episode_row($row);
            }
            return $out;
        }

        $this->db->select('*');
        $this->db->from('film_episode_details');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filmEpisode_by_id($id) {
        if ($this->db->table_exists('film_episode')) {
            $row = $this->db
                ->select('fe.*, f.english_transliteration AS film_english_transliteration, f.english_translation AS film_english_translation, f.original_title AS film_original_title')
                ->from('film_episode fe')
                ->join('film f', 'f.id = fe.film_id', 'left')
                ->where('fe.id', $id)
                ->get()
                ->row();
            if ($row) {
                return $this->normalize_film_episode_row($row);
            }
        }

        $this->db->where('id', $id);
        $query = $this->db->get('film_episode_details');
        return $query->row();
    }

    public function update_filmEpisode($id, $data) {
        if ($this->db->table_exists('film_episode')) {
            $payload = $this->map_to_film_episode_payload($data);
            $payload = array_filter($payload, function ($v) { return $v !== null; });
            if (!empty($payload)) {
                $this->db->where('id', $id);
                $this->db->update('film_episode', $payload);
            }
            // Always sync relations regardless of scalar update return
            $this->sync_episode_relations((int)$id, $data);
            return true;
        }
        $this->db->where('id', $id);
        return $this->db->update('film_episode_details', $data);
    }

    public function deleteFilmEpisode($id) {
        if ($this->db->table_exists('film_episode')) {
            return $this->db->delete('film_episode', ['id' => $id]);
        }
        return $this->db->delete('film_episode_details', ['id' => $id]);
    }

    private function map_to_film_episode_payload($data) {
        $toBoolInt = function ($v) {
            if ($v === null) return null;
            $s = strtolower(trim((string)$v));
            return in_array($s, ['1', 'true', 'yes'], true) ? 1 : 0;
        };

        return [
            'english_transliteration' => $data['film_episode_title'] ?? null,
            'english_translation' => $data['film_episode_title'] ?? null,
            'episode_number' => $data['episode_no'] ?? null,
            'duration' => $data['duration'] ?? null,
            'about_text' => $data['about_text'] ?? null,
            'description' => $data['about_text'] ?? null,
            'thumbnail_url' => $data['thumbnail_image_upload'] ?? null,
            'thumbnail_excerpt' => $data['thumbnail_excerpt'] ?? null,
            'year_of_production' => $data['year'] ?? null,
            'youtube_video_id' => $data['youtube_link'] ?? ($data['youtube_id'] ?? null),
            'film_id' => $data['main_title'] ?? null,
            'show_on_landing_page' => isset($data['show_on_landing_page']) ? $toBoolInt($data['show_on_landing_page']) : null,
            'is_published' => isset($data['publish']) ? $toBoolInt($data['publish']) : null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_keywords' => $data['meta_keyword'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
        ];
    }

    private function ids_from_csv($raw) {
        $ids = [];
        foreach (explode(',', (string)$raw) as $part) {
            $val = trim($part);
            if ($val === '' || !ctype_digit($val)) {
                continue;
            }
            $num = (int)$val;
            if ($num > 0) {
                $ids[$num] = true;
            }
        }
        return array_keys($ids);
    }

    private function sync_relation_table($table, $fkCol, $entityCol, $entityId, $csvIds) {
        if (!$this->db->table_exists($table)) {
            return;
        }
        $fields = $this->db->list_fields($table);
        if (!in_array($fkCol, $fields, true) || !in_array($entityCol, $fields, true)) {
            return;
        }
        $this->db->where($fkCol, (int)$entityId)->delete($table);
        foreach ($this->ids_from_csv($csvIds) as $id) {
            $this->db->insert($table, [$fkCol => (int)$entityId, $entityCol => (int)$id]);
        }
    }

    private function sync_film_relations($filmId, $data) {
        $filmId = (int)$filmId;
        if ($filmId <= 0 || !is_array($data)) {
            return;
        }
        @file_put_contents(FCPATH . 'film_sync_debug.log',
            "[".date('Y-m-d H:i:s')."] sync_film_relations filmId=$filmId\n"
            ."data[related_songs]=".(isset($data['related_songs'])?var_export($data['related_songs'],true):'(unset)')."\n"
            ."data[related_primary_songs]=".(isset($data['related_primary_songs'])?var_export($data['related_primary_songs'],true):'(unset)')."\n"
            ."data[related_keywords]=".(isset($data['related_keywords'])?var_export($data['related_keywords'],true):'(unset)')."\n"
            ."data[related_words]=".(isset($data['related_words'])?var_export($data['related_words'],true):'(unset)')."\n"
            ."---\n", FILE_APPEND);
        // Prefer non-empty value (?? only catches null/unset, not empty string)
        $pickFirstNonEmpty = function () use ($data) {
            foreach (func_get_args() as $key) {}
            $args = func_get_args();
            foreach ($args as $key) {
                if (isset($data[$key]) && trim((string)$data[$key]) !== '') {
                    return trim((string)$data[$key]);
                }
            }
            return '';
        };
        $songsCsv    = $pickFirstNonEmpty('related_primary_songs', 'related_songs');
        $poemsCsv    = $pickFirstNonEmpty('related_couplets', 'related_poems');
        $keywordsCsv = $pickFirstNonEmpty('related_words', 'related_keywords');

        $this->sync_relation_table('film_director', 'film_id', 'director_id', $filmId, (string)($data['directors'] ?? ''));
        $this->sync_relation_table('film_primary_people', 'film_id', 'person_id', $filmId, (string)($data['related_people'] ?? ''));
        $this->sync_relation_table('film_primary_reflection', 'film_id', 'reflection_id', $filmId, (string)($data['related_reflections'] ?? ''));
        $this->sync_relation_table('film_primary_song', 'film_id', 'song_id', $filmId, $songsCsv);
        $this->sync_relation_table('film_primary_word', 'film_id', 'word_id', $filmId, $keywordsCsv);
        $this->sync_relation_table('film_couplet', 'film_id', 'couplet_id', $filmId, $poemsCsv);
    }

    private function sync_episode_relations($episodeId, $data) {
        $episodeId = (int)$episodeId;
        if ($episodeId <= 0 || !is_array($data)) {
            return;
        }
        $this->sync_relation_table('film_episode_song', 'film_episode_id', 'song_id', $episodeId, (string)($data['related_songs'] ?? ''));
        $this->sync_relation_table('film_episode_couplet', 'film_episode_id', 'couplet_id', $episodeId, (string)($data['related_poems'] ?? ''));
        $this->sync_relation_table('film_episode_reflection', 'film_episode_id', 'reflection_id', $episodeId, (string)($data['related_reflections'] ?? ''));
        $peopleCsv = (string)($data['related_people'] ?? '');
        // Handle schema differences: some DBs use people_id, some person_id.
        $this->sync_relation_table('film_episode_people', 'film_episode_id', 'people_id', $episodeId, $peopleCsv);
        $this->sync_relation_table('film_episode_people', 'film_episode_id', 'person_id', $episodeId, $peopleCsv);
        $this->sync_relation_table('film_episode_word', 'film_episode_id', 'word_id', $episodeId, (string)($data['related_keywords'] ?? ''));
        $this->sync_relation_table('film_episode_film', 'film_episode_id', 'film_id', $episodeId, (string)($data['related_films'] ?? ''));
    }

    private function normalize_film_episode_row($row) {
        $r = is_object($row) ? clone $row : (object)$row;
        $filmTitle = '';
        foreach (['film_english_transliteration', 'film_english_translation', 'film_original_title'] as $k) {
            if (isset($r->$k) && trim((string)$r->$k) !== '') {
                $filmTitle = trim((string)$r->$k);
                break;
            }
        }
        $r->film_episode_title = isset($r->english_transliteration) && trim((string)$r->english_transliteration) !== ''
            ? (string)$r->english_transliteration
            : (isset($r->english_translation) ? (string)$r->english_translation : '');
        $r->main_title = isset($r->film_id) ? (string)$r->film_id : (isset($r->main_title) ? (string)$r->main_title : '');
        $r->main_title_label = $filmTitle !== '' ? $filmTitle : (isset($r->film_id) ? ('Film #' . $r->film_id) : (isset($r->main_title) ? (string)$r->main_title : ''));
        $r->episode_no = isset($r->episode_number) ? (string)$r->episode_number : '';
        $r->thumbnail_image_upload = isset($r->thumbnail_url) ? (string)$r->thumbnail_url : '';
        $r->year = isset($r->year_of_production) ? (string)$r->year_of_production : (isset($r->year) ? (string)$r->year : '');
        $r->publish = (isset($r->is_published) && ((string)$r->is_published === '1' || $r->is_published === true)) ? 'true' : 'false';
        $r->meta_keyword = isset($r->meta_keywords) ? (string)$r->meta_keywords : (isset($r->meta_keyword) ? (string)$r->meta_keyword : '');
        // Pull date_of_upload from legacy film_episode_details when normalized table lacks it
        if ((!isset($r->date_of_upload) || trim((string)$r->date_of_upload) === '') && isset($r->id)) {
            if ($this->db->table_exists('film_episode_details')) {
                $legacy = $this->db->select('date_of_upload')->from('film_episode_details')->where('id', (int)$r->id)->get()->row_array();
                if ($legacy && !empty($legacy['date_of_upload'])) {
                    $r->date_of_upload = $legacy['date_of_upload'];
                }
            }
        }

        // Populate related-content CSV fields from junction tables for edit preselection.
        $episodeId = isset($r->id) ? (int)$r->id : 0;
        if ($episodeId > 0) {
            $songsCsv = $this->fetch_episode_related_ids('film_episode_song', 'film_episode_id', 'song_id', $episodeId);
            $poemsCsv = $this->fetch_episode_related_ids('film_episode_couplet', 'film_episode_id', 'couplet_id', $episodeId);
            $reflectionsCsv = $this->fetch_episode_related_ids('film_episode_reflection', 'film_episode_id', 'reflection_id', $episodeId);
            $peopleCsv = $this->fetch_episode_related_ids('film_episode_people', 'film_episode_id', 'people_id', $episodeId);
            if ($peopleCsv === '') {
                // Some schemas use person_id naming.
                $peopleCsv = $this->fetch_episode_related_ids('film_episode_people', 'film_episode_id', 'person_id', $episodeId);
            }
            $wordsCsv = $this->fetch_episode_related_ids('film_episode_word', 'film_episode_id', 'word_id', $episodeId);

            $r->related_songs = $songsCsv;
            $r->related_poems = $poemsCsv;
            $r->related_reflections = $reflectionsCsv;
            $r->related_people = $peopleCsv;
            $r->related_keywords = $wordsCsv;
        }
        return $r;
    }

    private function fetch_episode_related_ids($table, $episodeFkCol, $entityIdCol, $episodeId) {
        if (!$this->db->table_exists($table)) {
            return '';
        }
        $fields = $this->db->list_fields($table);
        if (!in_array($episodeFkCol, $fields, true) || !in_array($entityIdCol, $fields, true)) {
            return '';
        }
        $rows = $this->db
            ->select($entityIdCol)
            ->from($table)
            ->where($episodeFkCol, (int)$episodeId)
            ->get()
            ->result_array();
        $ids = [];
        foreach ($rows as $row) {
            if (!isset($row[$entityIdCol])) {
                continue;
            }
            $val = trim((string)$row[$entityIdCol]);
            if ($val !== '') {
                $ids[$val] = true;
            }
        }
        return implode(',', array_keys($ids));
    }



}