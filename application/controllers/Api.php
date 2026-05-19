<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('SongModel');
        $this->load->model('ReflectionModel');
        $this->load->model('PersonModel');
        $this->load->model('FilmModel');
        $this->load->model('CoupletModel');
    }

    private function csv_ids($value) {
        $parts = array_filter(array_map('trim', explode(',', (string) $value)));
        $ids = [];
        foreach ($parts as $part) {
            if (ctype_digit($part)) {
                $pid = (int) $part;
                if ($pid > 0) {
                    $ids[] = $pid;
                }
            }
        }
        return array_values(array_unique($ids));
    }

    private function csv_text_tokens($value) {
        $parts = array_filter(array_map('trim', explode(',', (string) $value)));
        $tokens = [];
        foreach ($parts as $part) {
            if (!ctype_digit($part)) {
                $tokens[] = $part;
            }
        }
        return array_values(array_unique($tokens));
    }

    private function person_name_by_id($personId) {
        $personId = (int) $personId;
        if ($personId <= 0) {
            return '';
        }
        $person = $this->db->select('first_name, middle_name, last_name')
            ->from('person')
            ->where('id', $personId)
            ->get()
            ->row_array();
        if (empty($person)) {
            return '';
        }
        return trim(
            ($person['first_name'] ?? '') . ' ' .
            ($person['middle_name'] ?? '') . ' ' .
            ($person['last_name'] ?? '')
        );
    }

    private function title_text_by_id($titleId) {
        $titleId = (int) $titleId;
        if ($titleId <= 0 || !$this->db->table_exists('title')) {
            return '';
        }
        $title = $this->db->get_where('title', ['id' => $titleId])->row_array();
        if (empty($title)) {
            return '';
        }
        $translit = trim((string)($title['english_transliteration'] ?? ''));
        if ($translit !== '') {
            return $translit;
        }
        return trim((string)($title['original_title'] ?? ''));
    }

    private function song_api_record_by_id($songId) {
        $songId = (int) $songId;
        if ($songId <= 0) {
            return null;
        }
        $songTable = $this->SongModel->song_table_name();
        $baseRow = $this->db->get_where($songTable, ['id' => $songId])->row_array();
        if (empty($baseRow)) {
            return null;
        }
        $song = $this->SongModel->get_song_by_id($songId);
        if (empty($song)) {
            $song = $baseRow;
        }

        // Keep singer/poet source same as song-lists page (raw song row first).
        $rawSingerValue = isset($baseRow['singer']) ? trim((string) $baseRow['singer']) : '';
        $rawPoetValue = isset($baseRow['poet']) ? trim((string) $baseRow['poet']) : '';

        $singerIds = $this->csv_ids($rawSingerValue);
        $poetIds = [];
        $singerTextFallback = $this->csv_text_tokens($rawSingerValue);

        if (empty($singerIds) && $this->db->table_exists('song_singer')) {
            $rows = $this->db->select('singer_id')->from('song_singer')->where('song_id', $songId)->get()->result_array();
            foreach ($rows as $row) {
                $sid = (int) ($row['singer_id'] ?? 0);
                if ($sid > 0) {
                    $singerIds[] = $sid;
                }
            }
            $singerIds = array_values(array_unique($singerIds));
            if (empty($rawSingerValue) && !empty($singerIds)) {
                $rawSingerValue = implode(',', $singerIds);
            }
        }

        if ($this->db->table_exists('song_poet')) {
            $rows = $this->db->select('poet_id')->from('song_poet')->where('song_id', $songId)->get()->result_array();
            foreach ($rows as $row) {
                $pid = (int) ($row['poet_id'] ?? 0);
                if ($pid > 0) {
                    $poetIds[] = $pid;
                }
            }
            $poetIds = array_values(array_unique($poetIds));
            $rawPoetValue = !empty($poetIds) ? implode(',', $poetIds) : '';
        }

        $singerNames = [];
        foreach ($singerIds as $sid) {
            $name = $this->person_name_by_id($sid);
            if ($name !== '') {
                $singerNames[] = $name;
            }
        }
        if (empty($singerNames) && !empty($singerTextFallback)) {
            $singerNames = $singerTextFallback;
        }

        $poetNames = [];
        foreach ($poetIds as $pid) {
            $name = $this->person_name_by_id($pid);
            if ($name !== '') {
                $poetNames[] = $name;
            }
        }
        // Poet display must come from person table only using song_poet mapping.

        if (empty($song['umbrellaTitle']) && !empty($song['umbrellaTitleText'])) {
            $song['umbrellaTitle'] = $song['umbrellaTitleText'];
        }
        if (empty($song['Songtitle_transliteration']) && !empty($song['songTitle'])) {
            $song['Songtitle_transliteration'] = $song['songTitle'];
        }
        if (empty($song['Songtitle_transliteration'])) {
            if (!empty($baseRow['Songtitle_transliteration'])) {
                $song['Songtitle_transliteration'] = $baseRow['Songtitle_transliteration'];
            } elseif (!empty($baseRow['song_title_id'])) {
                $song['Songtitle_transliteration'] = $this->title_text_by_id($baseRow['song_title_id']);
            } elseif (!empty($baseRow['umbrella_title_id'])) {
                $song['Songtitle_transliteration'] = $this->title_text_by_id($baseRow['umbrella_title_id']);
            }
        }

        $song['singer_ids'] = $singerIds;
        $song['poet_ids'] = $poetIds;
        $song['singer_raw'] = $rawSingerValue;
        $song['poet_raw'] = $rawPoetValue;
        $song['singer_names'] = array_values(array_unique($singerNames));
        $song['poet_names'] = array_values(array_unique($poetNames));
        // API display names come from person table (same source as edit-form dropdown labels).
        $song['singer'] = implode(', ', $song['singer_names']);
        $song['poet'] = implode(', ', $song['poet_names']);
        $song['singer_display'] = $song['singer'];
        $song['poet_display'] = $song['poet'];

        $song = $this->SongModel->merge_legacy_year_location_for_song($songId, $song);
        $y = isset($song['year']) ? trim((string) $song['year']) : '';
        $loc = isset($song['location']) ? trim((string) $song['location']) : '';
        if ($y === '' && isset($song['interview_year'])) {
            $y = trim((string) $song['interview_year']);
        }
        if ($loc === '' && isset($song['interview_place'])) {
            $loc = trim((string) $song['interview_place']);
        }
        $song['year'] = $y;
        $song['location'] = $loc;
        $song['Year'] = $y;
        $song['Location'] = $loc;

        return (object) $song;
    }

    private function map_song_rows_to_admin_shape($rows) {
        $mapped = [];
        foreach ((array) $rows as $row) {
            $songId = 0;
            if (is_array($row) && isset($row['id'])) {
                $songId = (int) $row['id'];
            } elseif (is_object($row) && isset($row->id)) {
                $songId = (int) $row->id;
            }
            if ($songId <= 0) {
                continue;
            }
            $record = $this->song_api_record_by_id($songId);
            if (!empty($record)) {
                $mapped[] = $record;
            }
        }
        return $mapped;
    }

    private function latest_id_from_table($tableName) {
        if (!$this->db->table_exists($tableName)) {
            return 0;
        }
        $row = $this->db->select('id')->from($tableName)->order_by('id', 'DESC')->limit(1)->get()->row_array();
        return !empty($row['id']) ? (int)$row['id'] : 0;
    }

    private function reflection_speaker_names_from_csv($speakerCsv) {
        $names = [];
        foreach ($this->csv_ids($speakerCsv) as $personId) {
            $name = $this->person_name_by_id($personId);
            if ($name !== '') {
                $names[] = $name;
            }
        }
        return implode(', ', array_values(array_unique($names)));
    }

    private function occupation_names_for_person($personId, $legacyCsv = '') {
        $personId = (int)$personId;
        $occupationMap = [];
        if ($this->db->table_exists('category')) {
            $rows = $this->db->select('id, name')
                ->from('category')
                ->where('category_type', 'person')
                ->where('name IS NOT NULL', null, false)
                ->where("TRIM(name) !=", '')
                ->get()->result_array();
            foreach ($rows as $row) {
                $id = isset($row['id']) ? (string)$row['id'] : '';
                $name = isset($row['name']) ? trim((string)$row['name']) : '';
                if ($id !== '' && $name !== '') {
                    $occupationMap[$id] = $name;
                }
            }
        }

        $ids = [];
        if ($personId > 0 && $this->db->table_exists('person_category')) {
            $rows = $this->db->select('category_id')->from('person_category')->where('person_id', $personId)->get()->result_array();
            foreach ($rows as $row) {
                $cid = isset($row['category_id']) ? (string)$row['category_id'] : '';
                if ($cid !== '') {
                    $ids[] = $cid;
                }
            }
        } elseif (trim((string)$legacyCsv) !== '') {
            $ids = array_values(array_filter(array_map('trim', explode(',', (string)$legacyCsv))));
        }

        $names = [];
        foreach (array_values(array_unique($ids)) as $id) {
            if (isset($occupationMap[$id])) {
                $names[] = $occupationMap[$id];
            } elseif ($id !== '') {
                $names[] = $id;
            }
        }
        return implode(', ', array_values(array_unique($names)));
    }

    private function couplet_poet_names($coupletId, $coupletRow = null) {
        $ids = [];
        $collect = function ($raw) use (&$ids) {
            if ($raw === null || $raw === '') return;
            $arr = @unserialize($raw);
            if ($arr !== false && is_array($arr)) {
                foreach ($arr as $item) {
                    $pid = (int)$item;
                    if ($pid > 0) $ids[$pid] = true;
                }
                return;
            }
            foreach (explode(',', (string)$raw) as $item) {
                $pid = (int)trim($item);
                if ($pid > 0) $ids[$pid] = true;
            }
        };

        if (is_array($coupletRow)) {
            $collect($coupletRow['poet_id'] ?? '');
            $collect($coupletRow['attributed_poet'] ?? '');
        }
        if ($this->db->table_exists('couplet_poet')) {
            $rows = $this->db->select('poet_id')->from('couplet_poet')->where('couplet_id', (int)$coupletId)->get()->result_array();
            foreach ($rows as $row) {
                $pid = (int)($row['poet_id'] ?? 0);
                if ($pid > 0) $ids[$pid] = true;
            }
        }

        $names = [];
        foreach (array_keys($ids) as $pid) {
            $name = $this->person_name_by_id((int)$pid);
            if ($name !== '') {
                $names[] = $name;
            }
        }
        return implode(', ', array_values(array_unique($names)));
    }

    private function fetch_related_ids_csv($table, $fkCol, $idCol, $entityId) {
        if (!$this->db->table_exists($table)) {
            return '';
        }
        $rows = $this->db->select($idCol)->from($table)->where($fkCol, (int)$entityId)->get()->result_array();
        $ids = [];
        foreach ($rows as $row) {
            $id = isset($row[$idCol]) ? (int)$row[$idCol] : 0;
            if ($id > 0) {
                $ids[] = $id;
            }
        }
        $ids = array_values(array_unique($ids));
        return !empty($ids) ? implode(',', $ids) : '';
    }

    private function names_from_ids_csv($csv, $type) {
        $names = [];
        foreach ($this->csv_ids($csv) as $id) {
            if ($type === 'person') {
                $n = $this->person_name_by_id($id);
                if ($n !== '') $names[] = $n;
            } elseif ($type === 'song') {
                $song = $this->song_api_record_by_id($id);
                if (!empty($song)) {
                    $label = trim((string)($song->Songtitle_transliteration ?? $song->umbrellaTitle ?? ''));
                    if ($label !== '') $names[] = $label;
                }
            } elseif ($type === 'poem') {
                $row = $this->db->select("COALESCE(NULLIF(couplet_transliteration, ''), original_title) AS label")->from('couplet')->where('id', $id)->get()->row_array();
                if (!empty($row['label'])) $names[] = (string)$row['label'];
            } elseif ($type === 'reflection') {
                $row = $this->db->select('title')->from('reflection')->where('id', $id)->get()->row_array();
                if (!empty($row['title'])) $names[] = (string)$row['title'];
            } elseif ($type === 'keyword') {
                $table = $this->db->table_exists('word') ? 'word' : 'keywords';
                $row = $this->db->select('word_transliteration')->from($table)->where('id', $id)->get()->row_array();
                if (!empty($row['word_transliteration'])) $names[] = (string)$row['word_transliteration'];
            } elseif ($type === 'film') {
                $row = $this->db->select("COALESCE(NULLIF(english_transliteration,''), english_translation, original_title) AS label")->from('film')->where('id', $id)->get()->row_array();
                if (!empty($row['label'])) $names[] = (string)$row['label'];
            } elseif ($type === 'episode') {
                $row = $this->db->select("COALESCE(NULLIF(english_transliteration,''), english_translation) AS label")->from('film_episode')->where('id', $id)->get()->row_array();
                if (!empty($row['label'])) $names[] = (string)$row['label'];
            }
        }
        return implode(', ', array_values(array_unique($names)));
    }

    private function enrich_poem_related_fields(array $poemRow) {
        $id = isset($poemRow['id']) ? (int)$poemRow['id'] : 0;
        if ($id <= 0) {
            return $poemRow;
        }
        $map = [
            'related_songs' => ['table' => 'couplet_song', 'col' => 'song_id', 'type' => 'song'],
            'related_reflections' => ['table' => 'couplet_reflection', 'col' => 'reflection_id', 'type' => 'reflection'],
            'related_people' => ['table' => 'couplet_people', 'col' => 'person_id', 'type' => 'person'],
            'related_keywords' => ['table' => 'couplet_word', 'col' => 'word_id', 'type' => 'keyword'],
            'related_poems' => ['table' => 'couplet_relatedcouplet', 'col' => 'related_couplet_id', 'type' => 'poem'],
            'films' => ['table' => 'couplet_film', 'col' => 'film_id', 'type' => 'film'],
            'film_episodes' => ['table' => 'couplet_filmepisode', 'col' => 'film_episode_id', 'type' => 'episode'],
        ];
        foreach ($map as $field => $meta) {
            $csv = trim((string)($poemRow[$field] ?? ''));
            if ($csv === '') {
                $csv = $this->fetch_related_ids_csv($meta['table'], 'couplet_id', $meta['col'], $id);
                if ($csv !== '') {
                    $poemRow[$field] = $csv;
                }
            }
            $poemRow[$field . '_names'] = $this->names_from_ids_csv($csv, $meta['type']);
        }
        return $poemRow;
    }

    public function index() {
        echo "API Controller is working";
        exit;
    }

 public function list() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('Content-Type: application/json; charset=utf-8');
        // GET params
        $search = $this->input->get('search');
        $page   = $this->input->get('page');
        $limit  = $this->input->get('limit');
        $singer  = $this->input->get('singer');
        $poet    = $this->input->get('poet');
        $theme   = $this->input->get('theme');

        // FIX: null/undefined → empty
        if ($search === "null" || $search === "undefined" || $search === null || $search === "") {
            $search = "";
        }
        if ($singer === "null" || $singer === "undefined" || $singer === null || $singer === "") {
            $singer = "";
        }
        if ($poet === "null" || $poet === "undefined" || $poet === null || $poet === "") {
            $poet = "";
        }
        if ($theme === "null" || $theme === "undefined" || $theme === null || $theme === "") {
            $theme = "";
        }

        // Pagination defaults
        $page  = (!empty($page)  && is_numeric($page))  ? $page  : 1;
        $limit = (!empty($limit) && is_numeric($limit)) ? $limit : 10;

        $offset = ($page - 1) * $limit;

        $songTable = $this->SongModel->song_table_name();
        $idRows = $this->db->select('id')
            ->from($songTable)
            ->order_by('id', 'DESC')
            ->get()
            ->result_array();

        $filtered = [];
        foreach ($idRows as $row) {
            $songRecord = $this->song_api_record_by_id((int) ($row['id'] ?? 0));
            if (empty($songRecord)) {
                continue;
            }

            if ($search !== '') {
                $titleHaystack = strtolower(trim(
                    ($songRecord->umbrellaTitle ?? '') . ' ' .
                    ($songRecord->Songtitle_transliteration ?? '') . ' ' .
                    ($songRecord->songTitleOriginal ?? '')
                ));
                if (stripos($titleHaystack, strtolower($search)) === false) {
                    continue;
                }
            }

            if ($singer !== '') {
                $singerText = strtolower(implode(', ', (array) ($songRecord->singer_names ?? [])));
                if (stripos($singerText, strtolower($singer)) === false) {
                    continue;
                }
            }

            if ($poet !== '') {
                $poetText = strtolower(implode(', ', (array) ($songRecord->poet_names ?? [])));
                if (stripos($poetText, strtolower($poet)) === false) {
                    continue;
                }
            }

            if ($theme !== '') {
                $relatedKeywordCsv = (string) ($songRecord->relatedkeywords ?? '');
                if (is_numeric($theme)) {
                    $themeIds = $this->csv_ids($relatedKeywordCsv);
                    if (!in_array((int) $theme, $themeIds, true)) {
                        continue;
                    }
                } elseif (stripos($relatedKeywordCsv, $theme) === false) {
                    continue;
                }
            }

            $filtered[] = $songRecord;
        }

        $total = count($filtered);
        $data = array_slice($filtered, $offset, $limit);
        foreach ($data as $idx => $songRecord) {
            $data[$idx]->song_title = (string) ($songRecord->Songtitle_transliteration ?? '');
        }

        echo json_encode([
            "status"       => true,
            "page"         => $page,
            "limit"        => $limit,
            "singer"       => $singer,
            "poet"         => $poet,
            "theme"        => $theme,
            "total"        => $total,
            "total_pages"  => ceil($total / $limit),
            "data"         => $data
        ]);
    }

    public function reflection_list() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header('Content-Type: application/json; charset=utf-8');
// GET params
        $page  = $this->input->get('page');
        $limit = $this->input->get('limit');
        $by_speaker = $this->input->get('by_speaker');
        $theme = $this->input->get('theme');
        $format = $this->input->get('format');

        if ($by_speaker === "null" || $by_speaker === "undefined" || $by_speaker === null || $by_speaker === "") {
            $by_speaker = "";
        }
        if ($theme === "null" || $theme === "undefined" || $theme === null || $theme === "") {
            $theme = "";
        }
        if ($format === "null" || $format === "undefined" || $format === null || $format === "") {
            $format = "";
        }

        // Pagination defaults
        $page  = (!empty($page)  && is_numeric($page))  ? $page  : 1;
        $limit = (!empty($limit) && is_numeric($limit)) ? $limit : 10;

        $offset = ($page - 1) * $limit;

        // --------------------------------------------
        // 1. Total count (FOR pagination)
        // --------------------------------------------

        $this->db->from('reflection');
        $this->db->join('reflection_person rp', 'rp.reflection_id = reflection.id', 'left');
        $this->db->join('person p', 'p.id = rp.person_id', 'left');

        if ($by_speaker != "") {
            $this->db->where('reflection.speaker_id', (int)$by_speaker);
        }

        if ($theme != "") {
            if (is_numeric($theme)) {
                $this->db->where("FIND_IN_SET(" . (int)$theme . ", reflection.related_keywords) !=", 0, false);
            } else {
                $this->db->like('reflection.related_keywords', $theme);
            }
        }

        if ($format != "") {
            $this->db->where('reflection.format', $format);
        }

        $total = $this->db->count_all_results();

        // --------------------------------------------
        // 2. Actual paginated data
        // --------------------------------------------

        $this->db->select("
            reflection.*,
            CONCAT(p.first_name, ' ', COALESCE(p.middle_name,''), ' ', COALESCE(p.last_name,'')) AS person_name_english,
            CONCAT(p.first_name_in_hindi, ' ', COALESCE(p.middle_name_in_hindi,''), ' ', COALESCE(p.last_name_in_hindi,'')) AS person_name_hindi
        ");

        $this->db->from('reflection');
        $this->db->join('reflection_person rp', 'rp.reflection_id = reflection.id', 'left');
        $this->db->join('person p', 'p.id = rp.person_id', 'left');

        if ($by_speaker != "") {
            $this->db->where('reflection.speaker_id', (int)$by_speaker);
        }

        if ($theme != "") {
            if (is_numeric($theme)) {
                $this->db->where("FIND_IN_SET(" . (int)$theme . ", reflection.related_keywords) !=", 0, false);
            } else {
                $this->db->like('reflection.related_keywords', $theme);
            }
        }

        if ($format != "") {
            $this->db->where('reflection.format', $format);
        }

        // Latest first
        $this->db->order_by('reflection.date_of_upload', 'DESC');

        $this->db->group_by('reflection.id');
        $this->db->limit($limit, $offset);

        $query = $this->db->get();
        $data = $query->result();

        echo json_encode([
            "status"       => true,
            "page"         => $page,
            "limit"        => $limit,
            "by_speaker"   => $by_speaker,
            "theme"        => $theme,
            "format"       => $format,
            "total"        => $total,
            "total_pages"  => ceil($total / $limit),
            "data"         => $data
        ]);
    }

    public function person_list() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('Content-Type: application/json; charset=utf-8');

        // GET params
        $page   = $this->input->get('page');
        $limit  = $this->input->get('limit');
        $search = $this->input->get('search');
        if ($search === "null" || $search === "undefined" || $search === null) $search = "";

        $page  = (!empty($page)  && is_numeric($page))  ? (int)$page  : 1;
        $limit = (!empty($limit) && is_numeric($limit)) ? (int)$limit : 10;
        $offset = ($page - 1) * $limit;

        // ---------- Total count (single distinct person rows) ----------
        $this->db->from('person');
        if ($search !== "") {
            $this->db->group_start();
                $this->db->like('person.first_name', $search);
                $this->db->or_like('person.middle_name', $search);
                $this->db->or_like('person.last_name', $search);
                $this->db->or_like("TRIM(CONCAT_WS(' ', person.first_name, person.middle_name, person.last_name))", $search, 'both', false);
            $this->db->group_end();
        }
        $total = $this->db->count_all_results();

        // ---------- Paginated person rows (no JOIN — keeps one row per person) ----------
        $this->db->select("
            person.*,
            CONCAT_WS(' ', person.first_name, NULLIF(TRIM(COALESCE(person.middle_name,'')),''), person.last_name) AS person_name_english,
            CONCAT_WS(' ', person.first_name_in_hindi, NULLIF(TRIM(COALESCE(person.middle_name_in_hindi,'')),''), person.last_name_in_hindi) AS person_name_hindi,
            person.thumbnail_url
        ");
        $this->db->from('person');
        if ($search !== "") {
            $this->db->group_start();
                $this->db->like('person.first_name', $search);
                $this->db->or_like('person.middle_name', $search);
                $this->db->or_like('person.last_name', $search);
                $this->db->or_like("TRIM(CONCAT_WS(' ', person.first_name, person.middle_name, person.last_name))", $search, 'both', false);
            $this->db->group_end();
        }
        $this->db->order_by("LOWER(TRIM(CONCAT_WS(' ', person.first_name, person.middle_name, person.last_name)))", 'ASC', false);
        $this->db->limit($limit, $offset);
        $people = $this->db->get()->result();

        // ---------- Build category map { id => name } ----------
        $occupation_map = [];
        if ($this->db->table_exists('category')) {
            $rows = $this->db
                ->select('id, name')
                ->from('category')
                ->where('category_type', 'person')
                ->where('name IS NOT NULL', null, false)
                ->where("TRIM(name) !=", '')
                ->get()->result_array();
            foreach ($rows as $row) {
                $cid  = isset($row['id']) ? (string)$row['id'] : null;
                $name = isset($row['name']) ? trim((string)$row['name']) : '';
                if ($cid !== null && $name !== '') $occupation_map[$cid] = $name;
            }
        }

        // ---------- Build person → categories[] map (only for paginated ids) ----------
        $person_category_map = [];
        $personIds = array_filter(array_map(function ($p) { return isset($p->id) ? (int)$p->id : 0; }, $people));
        if (!empty($personIds) && $this->db->table_exists('person_category')) {
            $rows = $this->db
                ->select('person_id, category_id')
                ->from('person_category')
                ->where_in('person_id', $personIds)
                ->get()->result_array();
            foreach ($rows as $r) {
                $pid = isset($r['person_id']) ? (string)$r['person_id'] : '';
                $cid = isset($r['category_id']) ? (string)$r['category_id'] : '';
                if ($pid === '' || $cid === '') continue;
                if (!isset($person_category_map[$pid])) $person_category_map[$pid] = [];
                $person_category_map[$pid][] = $cid;
            }
        }

        // ---------- Mirror admin (PersonController::fetch_person) Occupation / Profile Tags split ----------
        // Rule: category name with leading `_`  → Occupation
        //       plain category name             → Profile Tags
        $display_map = ['1' => 'Yes', '0' => 'No', 'yes' => 'Yes', 'no' => 'No', 'Yes' => 'Yes', 'No' => 'No'];
        $data = [];
        foreach ($people as $p) {
            $personId = isset($p->id) ? (string)$p->id : '';

            // category ids: junction first; fallback to legacy person.occupation CSV
            $catIds = ($personId !== '' && isset($person_category_map[$personId])) ? $person_category_map[$personId] : [];
            if (empty($catIds) && !empty($p->occupation)) {
                $catIds = array_filter(array_map('trim', explode(',', (string)$p->occupation)));
            }

            $occupationNames = [];
            $profileTagsArr  = [];
            foreach ($catIds as $cid) {
                $catName = isset($occupation_map[$cid]) ? $occupation_map[$cid] : '';
                if ($catName === '') continue;
                if (strpos($catName, '_') === 0) $occupationNames[] = ltrim($catName, '_');
                else                              $profileTagsArr[]  = $catName;
            }

            // Profile Tags fallback to legacy person.profile_tags CSV
            if (empty($profileTagsArr) && !empty($p->profile_tags)) {
                $rawTagIds = array_filter(array_map('trim', explode(',', (string)$p->profile_tags)));
                foreach ($rawTagIds as $tid) {
                    if (isset($occupation_map[$tid])) $profileTagsArr[] = $occupation_map[$tid];
                    elseif ($tid !== '')              $profileTagsArr[] = $tid;
                }
            }

            $occupationNames = array_values(array_unique($occupationNames));
            $profileTagsArr  = array_values(array_unique($profileTagsArr));

            $fullName = trim(preg_replace('/\s+/', ' ', implode(' ', [
                isset($p->first_name) ? $p->first_name : '',
                isset($p->middle_name) ? $p->middle_name : '',
                isset($p->last_name) ? $p->last_name : '',
            ])));
            if ($fullName === '') $fullName = 'Unnamed';

            $row = (array)$p;
            $row['person_name']      = $fullName;
            $row['occupation_list']  = $occupationNames;
            $row['profile_tags_list']= $profileTagsArr;
            $row['occupation_text']  = !empty($occupationNames) ? implode(', ', $occupationNames) : '—';
            $row['profile_tags_text']= !empty($profileTagsArr)  ? implode(', ', $profileTagsArr)  : '—';
            $row['display_label']    = isset($display_map[$p->display]) ? $display_map[$p->display] : $p->display;
            $row['publish_label']    = ($p->publish == 1 || strtolower((string)$p->publish) === 'yes') ? 'Yes' : 'No';
            $data[] = $row;
        }

        echo json_encode([
            "status"      => true,
            "page"        => $page,
            "limit"       => $limit,
            "search"      => $search,
            "total"       => $total,
            "total_pages" => (int)ceil($total / $limit),
            "data"        => $data,
        ]);
    }

    public function film_list() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header('Content-Type: application/json; charset=utf-8');

        // ========== PAGINATION INPUT ==========
        $page  = $this->input->get('page');
        $limit = $this->input->get('limit');
        $page  = (!empty($page)  && is_numeric($page))  ? (int)$page  : 1;
        $limit = (!empty($limit) && is_numeric($limit)) ? (int)$limit : 10;
        $offset = ($page - 1) * $limit;

        // Source = same `film` table the admin reads from (FilmModel::fetch_all).
        $total = $this->db->count_all_results('film');

        $films = $this->db->select('*')
            ->from('film')
            ->order_by('id', 'DESC')
            ->limit($limit, $offset)
            ->get()->result();

        // Bulk-load director names per film via film_director junction
        $directorsByFilm = [];
        $filmIds = array_map(function ($f) { return (int)$f->id; }, $films);
        if (!empty($filmIds) && $this->db->table_exists('film_director')) {
            $jrows = $this->db->select('fd.film_id, p.first_name, p.middle_name, p.last_name, p.first_name_in_hindi, p.middle_name_in_hindi, p.last_name_in_hindi')
                ->from('film_director fd')
                ->join('person p', 'p.id = fd.director_id', 'left')
                ->where_in('fd.film_id', $filmIds)
                ->get()->result();
            foreach ($jrows as $r) {
                $fid = (int)$r->film_id;
                $en = trim(implode(' ', array_filter([$r->first_name ?? '', $r->middle_name ?? '', $r->last_name ?? ''], function($v){return trim((string)$v) !== '';})));
                $hi = trim(implode(' ', array_filter([$r->first_name_in_hindi ?? '', $r->middle_name_in_hindi ?? '', $r->last_name_in_hindi ?? ''], function($v){return trim((string)$v) !== '';})));
                if (!isset($directorsByFilm[$fid])) $directorsByFilm[$fid] = ['en' => [], 'hi' => []];
                if ($en !== '') $directorsByFilm[$fid]['en'][] = $en;
                if ($hi !== '') $directorsByFilm[$fid]['hi'][] = $hi;
            }
        }

        // Decorate each film with the same fields admin's edit-form sees + director arrays
        $data = [];
        foreach ($films as $f) {
            $fid = (int)$f->id;
            // Admin's mapping (FilmModel::get_film_by_id):
            $f->main_title   = isset($f->english_transliteration) ? (string)$f->english_transliteration : '';
            $f->second_title = isset($f->english_translation)     ? (string)$f->english_translation     : '';
            $f->series_title = isset($f->original_title)          ? (string)$f->original_title          : '';
            $f->publish      = isset($f->publish) && $f->publish !== '' ? $f->publish : (isset($f->is_published) ? $f->is_published : '');
            $f->director_names_english = isset($directorsByFilm[$fid]) ? array_values(array_unique($directorsByFilm[$fid]['en'])) : [];
            $f->director_names_hindi   = isset($directorsByFilm[$fid]) ? array_values(array_unique($directorsByFilm[$fid]['hi'])) : [];
            $f->director_name_english  = !empty($f->director_names_english) ? implode(', ', $f->director_names_english) : '';
            $f->director_name_hindi    = !empty($f->director_names_hindi)   ? implode(', ', $f->director_names_hindi)   : '';
            $data[] = $f;
        }

        echo json_encode([
            "status"       => true,
            "page"         => $page,
            "limit"        => $limit,
            "total"        => $total,
            "total_pages"  => $limit > 0 ? (int)ceil($total / $limit) : 1,
            "data"         => $data,
        ]);
    }

    

public function first_items()
{
    header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json; charset=utf-8');
    ////////////////////////////////
    // 1️⃣ FIRST SONG
    ////////////////////////////////
    $this->db->select("
        song.id,
        song.thumbnail_url,
        song.about,
        song.published_date,
        title.english_transliteration AS song_title,
        CONCAT(person.first_name, ' ', person.last_name) AS singer_name
    ");
    $this->db->from('song');
    $this->db->join('title', 'title.id = song.song_title_id', 'left');
    $this->db->join('song_singer ss', 'ss.song_id = song.id', 'left');
    $this->db->join('person', 'person.id = ss.singer_id', 'left');
    $this->db->order_by('song.published_date', 'DESC');
    $this->db->group_by('song.id');
    $this->db->limit(1);
    $song = $this->db->get()->row();


    ////////////////////////////////
    // 2️⃣ FIRST REFLECTION
    ////////////////////////////////
    $this->db->select("
        reflection.id,
        reflection.title,
        reflection.meta_description,
        CONCAT(p.first_name, ' ', COALESCE(p.middle_name,''), ' ', COALESCE(p.last_name,'')) AS person_name_english,
        CONCAT(p.first_name_in_hindi, ' ', COALESCE(p.middle_name_in_hindi,''), ' ', COALESCE(p.last_name_in_hindi,'')) AS person_name_hindi
    ");
    $this->db->from('reflection');
    $this->db->join('reflection_person rp', 'rp.reflection_id = reflection.id', 'left');
    $this->db->join('person p', 'p.id = rp.person_id', 'left');
    $this->db->order_by('reflection.date_of_upload', 'DESC');
    $this->db->group_by('reflection.id');
    $this->db->limit(1);
    $reflection = $this->db->get()->row();


    ////////////////////////////////
    // 3️⃣ FIRST PERSON
    ////////////////////////////////
    $this->db->select("
        person.id,
        CONCAT(person.first_name, ' ', COALESCE(person.middle_name,''), ' ', COALESCE(person.last_name,'')) AS person_name_english,
        CONCAT(person.first_name_in_hindi, ' ', COALESCE(person.middle_name_in_hindi,''), ' ', COALESCE(person.last_name_in_hindi,'')) AS person_name_hindi,
        person.thumbnail_url,
        category.name AS category_name,
        category.category_type
    ");
    $this->db->from('person');
    $this->db->join('person_category pc', 'pc.person_id = person.id', 'left');
    $this->db->join('category', 'category.id = pc.category_id', 'left');
    $this->db->order_by('person.id', 'DESC');
    $this->db->group_by('person.id');
    $this->db->limit(1);
    $person = $this->db->get()->row();


    ////////////////////////////////
    // 4️⃣ FIRST FILM
    ////////////////////////////////
    $this->db->select("
        film.id,
        film.english_translation,
        film.english_transliteration,
        film.duration,
        film.year_of_production,
        film.thumbnail_url,
        film.about_text,
        CONCAT(p.first_name, ' ', COALESCE(p.middle_name,''), ' ', COALESCE(p.last_name,'')) AS director_name_english,
        CONCAT(p.first_name_in_hindi, ' ', COALESCE(p.middle_name_in_hindi,''), ' ', COALESCE(p.last_name_in_hindi,'')) AS director_name_hindi
    ");
    $this->db->from('film');
    $this->db->join('film_director fd', 'fd.film_id = film.id', 'left');
    $this->db->join('person p', 'p.id = fd.director_id', 'left');
    $this->db->order_by('film.year_of_production', 'DESC');
    $this->db->group_by('film.id');
    $this->db->limit(1);
    $film = $this->db->get()->row();


    ////////////////////////////////
    // FINAL COMBINED JSON OUTPUT
    ////////////////////////////////
    echo json_encode([
        "status" => true,
        "song" => $song,
        "reflection" => $reflection,
        "person" => $person,
        "film" => $film,
        "poem" => $film
    ]);
}


public function poems()
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header('Content-Type: application/json; charset=utf-8');

    // GET params
    $theme  = $this->input->get('theme');
    $poet   = $this->input->get('poet');

    // FIX: null/undefined → empty
    if ($theme === "null" || $theme === "undefined" || $theme === null || $theme === "") {
        $theme = "";
    }
    if ($poet === "null" || $poet === "undefined" || $poet === null || $poet === "") {
        $poet = "";
    }

    // Pagination defaults
    $page  = (!empty($page)  && is_numeric($page))  ? $page  : 1;
    $limit = (!empty($limit) && is_numeric($limit)) ? $limit : 10;

    $offset = ($page - 1) * $limit;

    // ========================================
    // 1. Total count (for pagination)
    // ========================================
    $this->db->from('couplet');

    if ($theme != "") {
        $this->db->like('couplet.original_title', $theme);
    }
    if ($poet != "") {
        $this->db->where('couplet.poet_id', (int)$poet);
    }

    $total = $this->db->count_all_results();

    // ========================================
    // 2. Actual records (with limit + offset)
    // ========================================
    $this->db->select("*");
    $this->db->from('couplet');

    if ($theme != "") {
        $this->db->like('couplet.original_title', $theme);
    }
    if ($poet != "") {
        $this->db->where('couplet.poet_id', (int)$poet);
    }

    $this->db->order_by('couplet.id', 'DESC');
    $this->db->limit($limit, $offset);

    $query = $this->db->get();
    $data = $query->result();

    echo json_encode([
        "status"       => true,
        "theme"        => $theme,
        "poet"         => $poet,
        "total"        => $total,
        "data"         => $data
    ]);
}

public function explore_songs()
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

    // ⭐ Long Text Hindi Translation Function
    function translateToHindi($text)
    {
        if ($text === null || $text === '') {
            return $text;
        }

        // Split by new line and sentence enders to avoid huge single requests
        $parts = preg_split('/(?:\r\n|\r|\n)+|(?<=[.?!।])\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        if (!is_array($parts) || empty($parts)) {
            $parts = [$text];
        }

        $translated = '';

        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '') {
                continue;
            }

            // Keep each request small for Google translate endpoint
            $chunks = [];
            $maxLen = 1200;
            if (mb_strlen($part, 'UTF-8') <= $maxLen) {
                $chunks[] = $part;
            } else {
                $offset = 0;
                $length = mb_strlen($part, 'UTF-8');
                while ($offset < $length) {
                    $chunks[] = mb_substr($part, $offset, $maxLen, 'UTF-8');
                    $offset += $maxLen;
                }
            }

            foreach ($chunks as $chunk) {
                $encoded = urlencode($chunk);
                $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=hi&dt=t&q={$encoded}";

                $response = @file_get_contents($url);
                if ($response === false) {
                    $translated .= $chunk . ' ';
                    continue;
                }

                $responseArray = json_decode($response, true);
                if (!isset($responseArray[0]) || !is_array($responseArray[0])) {
                    $translated .= $chunk . ' ';
                    continue;
                }

                // Google response returns multiple translated fragments in [0]
                $translatedChunk = '';
                foreach ($responseArray[0] as $fragment) {
                    if (isset($fragment[0])) {
                        $translatedChunk .= $fragment[0];
                    }
                }

                $translated .= trim($translatedChunk) . ' ';
            }
        }

        return trim($translated);
    }

    // Get song_id
    $song_id = $this->input->get('song_id');
    if (empty($song_id)) {
        $query_string = trim($_SERVER['QUERY_STRING']);
        $parts = explode('&', $query_string);
        $first = trim($parts[0]);
        if (is_numeric($first)) {
            $song_id = $first;
        }
    }

    // Get language
    $language = strtolower($this->input->get('language'));
    if (!in_array($language, ['english', 'hindi'])) {
        $language = 'english';
    }

    if (empty($song_id) || !is_numeric($song_id)) {
        echo json_encode([
            "status"  => false,
            "message" => "Invalid or missing song_id parameter."
        ]);
        return;
    }

    $song = $this->song_api_record_by_id((int) $song_id);

    if (empty($song)) {
        echo json_encode([
            "status"  => false,
            "message" => "Song not found."
        ]);
        return;
    }

    // ⭐ Apply Accurate Hindi Translation (FULL paragraph)
    if ($language == 'hindi' && isset($song->songLyricsTranslated)) {
        $song->songLyricsTranslated = translateToHindi($song->songLyricsTranslated);
    }

    echo json_encode([
        "status" => true,
        "data"   => $song,
    ]);
}

public function explore_reflection()
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header('Content-Type: application/json; charset=utf-8');

    // Get reflection_id
    $reflection_id = $this->input->get('reflection_id');
    if (empty($reflection_id)) {
        $query_string = trim($_SERVER['QUERY_STRING']);
        $parts = explode('&', $query_string);
        $first = trim($parts[0]);
        if (is_numeric($first)) {
            $reflection_id = $first;
        }
    }

    if (empty($reflection_id) || !is_numeric($reflection_id)) {
        echo json_encode([
            "status"  => false,
            "message" => "Invalid or missing reflection_id parameter."
        ]);
        return;
    }

    // Fetch reflection with person details
    $this->db->select("
        reflection.*,
        CONCAT(p.first_name, ' ', COALESCE(p.middle_name,''), ' ', COALESCE(p.last_name,'')) AS person_name_english,
        CONCAT(p.first_name_in_hindi, ' ', COALESCE(p.middle_name_in_hindi,''), ' ', COALESCE(p.last_name_in_hindi,'')) AS person_name_hindi,
        p.id AS person_id,
        p.thumbnail_url AS person_thumbnail_url
    ");
    $this->db->from('reflection');
    $this->db->join('reflection_person rp', 'rp.reflection_id = reflection.id', 'left');
    $this->db->join('person p', 'p.id = rp.person_id', 'left');
    $this->db->where('reflection.id', (int)$reflection_id);
    $this->db->group_by('reflection.id');
    $reflection = $this->db->get()->row();

    if (empty($reflection)) {
        echo json_encode([
            "status"  => false,
            "message" => "Reflection not found."
        ]);
        return;
    }

    echo json_encode([
        "status" => true,
        "data"   => $reflection,
    ]);
}

public function explore_person()
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header('Content-Type: application/json; charset=utf-8');

    $person_id = $this->input->get('person_id');
    if (empty($person_id)) {
        $query_string = trim($_SERVER['QUERY_STRING']);
        $parts = explode('&', $query_string);
        $first = trim($parts[0]);
        if (is_numeric($first)) {
            $person_id = $first;
        }
    }

    if (empty($person_id) || !is_numeric($person_id)) {
        echo json_encode([
            "status"  => false,
            "message" => "Invalid or missing person_id parameter."
        ]);
        return;
    }

    $person = $this->db->select("person.*, CONCAT(person.first_name, ' ', COALESCE(person.middle_name,''), ' ', COALESCE(person.last_name,'')) AS person_name_english, CONCAT(person.first_name_in_hindi, ' ', COALESCE(person.middle_name_in_hindi,''), ' ', COALESCE(person.last_name_in_hindi,'')) AS person_name_hindi, category.name AS category_name, category.category_type")
                       ->from('person')
                       ->join('person_category pc', 'pc.person_id = person.id', 'left')
                       ->join('category', 'category.id = pc.category_id', 'left')
                       ->where('person.id', (int)$person_id)
                       ->group_by('person.id')
                       ->get()
                       ->row();

    if (empty($person)) {
        echo json_encode([
            "status"  => false,
            "message" => "Person not found."
        ]);
        return;
    }

    echo json_encode([
        "status" => true,
        "data"   => $person,
    ]);
}

public function explore_film()
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header('Content-Type: application/json; charset=utf-8');

    $film_id = $this->input->get('film_id');
    if (empty($film_id)) {
        $query_string = trim($_SERVER['QUERY_STRING']);
        $parts = explode('&', $query_string);
        $first = trim($parts[0]);
        if (is_numeric($first)) {
            $film_id = $first;
        }
    }

    if (empty($film_id) || !is_numeric($film_id)) {
        echo json_encode([
            "status"  => false,
            "message" => "Invalid or missing film_id parameter."
        ]);
        return;
    }

    $film = $this->db->select("film.*, CONCAT(p.first_name, ' ', COALESCE(p.middle_name,''), ' ', COALESCE(p.last_name,'')) AS director_name_english, CONCAT(p.first_name_in_hindi, ' ', COALESCE(p.middle_name_in_hindi,''), ' ', COALESCE(p.last_name_in_hindi,'')) AS director_name_hindi")
                     ->from('film')
                     ->join('film_director fd', 'fd.film_id = film.id', 'left')
                     ->join('person p', 'p.id = fd.director_id', 'left')
                     ->where('film.id', (int)$film_id)
                     ->group_by('film.id')
                     ->get()
                     ->row();

    if (empty($film)) {
        echo json_encode([
            "status"  => false,
            "message" => "Film not found."
        ]);
        return;
    }

    echo json_encode([
        "status" => true,
        "data"   => $film,
    ]);
}

public function glossary()
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header('Content-Type: application/json; charset=utf-8');

    $data = $this->db->order_by('id', 'DESC')->get('glossary')->result();

    echo json_encode([
        "status" => true,
        "total"  => count($data),
        "data"   => $data
    ]);
}

public function about()
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header('Content-Type: application/json; charset=utf-8');

    if (!$this->db->table_exists('about')) {
        echo json_encode([
            "status" => false,
            "message" => "about table not found"
        ]);
        return;
    }

    $ajabTypeMap = [];
    if ($this->db->table_exists('ajab_menus')) {
        $ajabMenuRows = $this->db->order_by('sort_order', 'ASC')->order_by('id', 'ASC')->get('ajab_menus')->result();
        foreach ($ajabMenuRows as $m) { $ajabTypeMap[(int)$m->id] = $m->slug; }
    }
    if (empty($ajabTypeMap)) {
        $ajabTypeMap = [1 => 'intro', 2 => 'translit guide', 3 => 'copyrights'];
    }

    $kabirTypeMap = [];
    if ($this->db->table_exists('kabir_menus')) {
        $menuRows = $this->db->order_by('sort_order', 'ASC')->order_by('id', 'ASC')->get('kabir_menus')->result();
        foreach ($menuRows as $m) { $kabirTypeMap[(int)$m->id] = $m->slug; }
    }
    if (empty($kabirTypeMap)) {
        $kabirTypeMap = [1 => 'intro', 2 => 'team', 3 => 'films', 4 => 'books', 5 => 'shabad shaala'];
    }

    $ajabRows = $this->db->where('status', 0)
                         ->order_by('id', 'DESC')
                         ->get('about')
                         ->result();

    $kabirRows = $this->db->where('status', 1)
                          ->order_by('id', 'DESC')
                          ->get('about')
                          ->result();

    $ajabMenuData = [];
    foreach ($ajabTypeMap as $slug) { $ajabMenuData[$slug] = []; }

    $kabirMenuData = [];
    foreach ($kabirTypeMap as $slug) { $kabirMenuData[$slug] = []; }

    foreach ($ajabRows as $row) {
        $typeKey = (int)$row->ajab_type;
        $row->type_label = isset($ajabTypeMap[$typeKey]) ? $ajabTypeMap[$typeKey] : ('type_' . $typeKey);

        if (!isset($ajabMenuData[$row->type_label])) {
            $ajabMenuData[$row->type_label] = [];
        }
        $ajabMenuData[$row->type_label][] = $row;
    }

    foreach ($kabirRows as $row) {
        $typeKey = (int)$row->kabir_type;
        $row->type_label = isset($kabirTypeMap[$typeKey]) ? $kabirTypeMap[$typeKey] : ('type_' . $typeKey);

        if (!isset($kabirMenuData[$row->type_label])) {
            $kabirMenuData[$row->type_label] = [];
        }
        $kabirMenuData[$row->type_label][] = $row;
    }

    $ajabMenuCounts = [];
    foreach ($ajabMenuData as $menuKey => $menuRows) {
        $ajabMenuCounts[$menuKey] = count($menuRows);
    }

    $kabirMenuCounts = [];
    foreach ($kabirMenuData as $menuKey => $menuRows) {
        $kabirMenuCounts[$menuKey] = count($menuRows);
    }

    // Dynamic sections (about_sections / section_menus)
    $sectionsData = [];
    $sectionsCounts = [];
    if ($this->db->table_exists('about_sections') && $this->db->table_exists('section_menus')) {
        $sectionRows = $this->db->order_by('sort_order','ASC')->order_by('id','ASC')->get('about_sections')->result();
        foreach ($sectionRows as $sec) {
            $menuRows = $this->db->where('section_id', (int)$sec->id)
                                  ->order_by('sort_order','ASC')->order_by('id','ASC')
                                  ->get('section_menus')->result();
            $menusBySlug = [];
            foreach ($menuRows as $m) { $menusBySlug[$m->slug] = []; }
            $contentRows = $this->db->where('status', (int)$sec->status_value)
                                    ->order_by('id','DESC')->get('about')->result();
            foreach ($contentRows as $row) {
                $menuSlug = '';
                if (!empty($row->menu_id)) {
                    foreach ($menuRows as $m) { if ((int)$m->id === (int)$row->menu_id) { $menuSlug = $m->slug; break; } }
                }
                if ($menuSlug === '') continue;
                $row->type_label = $menuSlug;
                if (!isset($menusBySlug[$menuSlug])) $menusBySlug[$menuSlug] = [];
                $menusBySlug[$menuSlug][] = $row;
            }
            $counts = [];
            foreach ($menusBySlug as $k => $v) { $counts[$k] = count($v); }
            $sectionsData[$sec->slug] = ['label' => $sec->label, 'menus' => $menusBySlug];
            $sectionsCounts[$sec->slug] = ['label' => $sec->label, 'menus' => $counts];
        }
    }

    echo json_encode([
        "status" => true,
        "data" => [
            "ajab_shahar" => [
                "menus" => $ajabMenuData
            ],
            "kabir_project" => [
                "menus" => $kabirMenuData
            ],
            "sections" => $sectionsData
        ],
        "counts" => [
            "ajab_shahar" => [
                "menus" => $ajabMenuCounts
            ],
            "kabir_project" => [
                "menus" => $kabirMenuCounts
            ],
            "sections" => $sectionsCounts
        ]
    ]);
}


public function song_filters()
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    $singers = $this->db->select("person.id, CONCAT(person.first_name, ' ', person.last_name) AS singer_name")
                        ->from('person')
                        ->join('song_singer ss', 'ss.singer_id = person.id', 'inner')
                        ->order_by('singer_name', 'ASC')
                        ->get()
                        ->result();

    $poets = $this->db->select("couplet.id, original_title AS poet_name")
                      ->from('couplet')
                      ->order_by('poet_name', 'ASC')
                      ->get()
                      ->result();
                    
    $kewords = $this->db->select("id, word_transliteration")
                      ->from('keywords')
                      ->where('is_keyword', 1)
                      ->where('is_published', 1)
                      ->get()
                      ->result();

    echo json_encode([
        "status" => true,
        "data" => [
            "song" => $singers,
            "poet" => $poets,
            "them" => $kewords
        ]
    ]);
}

    public function poem_filters()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('Content-Type: application/json; charset=utf-8');

        // ========================================
        // 1️⃣ GET POETS (from person table)
        // ========================================
        $poets = $this->db->select("person.id, person.first_name, person.middle_name, person.last_name, CONCAT(person.first_name, ' ', COALESCE(person.middle_name,''), ' ', COALESCE(person.last_name,'')) AS poet_name")
                        ->from('person')
                        ->order_by('person.first_name', 'ASC')
                        ->get()
                        ->result();

        // ========================================
        // 2️⃣ GET THEMES (from keywords table)
        // ========================================
        $themes = $this->db->select("keywords.id, keywords.word_transliteration")
                        ->from('keywords')
                        ->where('is_keyword', 1)
                        ->where('is_published', 1)
                        ->order_by('keywords.word_transliteration', 'ASC')
                        ->get()
                        ->result();

        echo json_encode([
            "status" => true,
            "data" => [
                "poets" => $poets,
                "themes" => $themes
            ]
        ]);
    }

    public function reflection_filter()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('Content-Type: application/json; charset=utf-8');

        $speaker = $this->db->select('id, first_name')
                            ->from('person')
                            ->order_by('first_name', 'ASC')
                            ->get()
                            ->result();

        $theme = $this->db->select('id, word_transliteration')
                          ->from('keywords')
                          ->order_by('word_transliteration', 'ASC')
                          ->get()
                          ->result();

        $format = $this->db->select('id, first_name')
                           ->from('person')
                           ->order_by('first_name', 'ASC')
                           ->get()
                           ->result();

        echo json_encode([
            'status' => true,
            'data' => [
                'speaker' => $speaker,
                'theme' => $theme,
                'format' => $format
            ]
        ]);
    }

    public function people_filter()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('Content-Type: application/json; charset=utf-8');

        // Map any category name (e.g. "_Poet", "Singers", "Theatre Artist") to one of 6 buckets.
        // Same rule as admin (PersonController::fetch_person → $bucket_for_name).
        $bucket_for_name = function ($name) {
            $n = strtolower(trim((string)$name));
            $n = ltrim($n, '_');
            if ($n === '') return null;
            if (strpos($n, 'poet') !== false)        return 'poets';
            if (strpos($n, 'singer') !== false ||
                strpos($n, 'qawwal') !== false ||
                strpos($n, 'musician') !== false)    return 'singers';
            if (strpos($n, 'writer') !== false ||
                strpos($n, 'scholar') !== false ||
                strpos($n, 'researcher') !== false ||
                strpos($n, 'translator') !== false ||
                strpos($n, 'journalist') !== false ||
                strpos($n, 'historian') !== false)   return 'writers';
            if (strpos($n, 'artist') !== false ||
                strpos($n, 'painter') !== false ||
                strpos($n, 'photographer') !== false ||
                strpos($n, 'filmmaker') !== false ||
                strpos($n, 'designer') !== false ||
                strpos($n, 'animator') !== false ||
                strpos($n, 'illustrator') !== false ||
                strpos($n, 'novelist') !== false ||
                strpos($n, 'dancer') !== false ||
                strpos($n, 'theatre') !== false ||
                strpos($n, 'actor') !== false ||
                strpos($n, 'performing') !== false)  return 'artists';
            if (strpos($n, 'legendary') !== false)   return 'legendary_figures';
            return 'other';
        };

        $buckets = [
            'poets' => [], 'singers' => [], 'writers' => [],
            'artists' => [], 'legendary_figures' => [], 'other' => []
        ];
        $seenInBucket = [
            'poets' => [], 'singers' => [], 'writers' => [],
            'artists' => [], 'legendary_figures' => [], 'other' => []
        ];

        // ---------- Build category map { id => name } ----------
        $occupation_map = [];
        if ($this->db->table_exists('category')) {
            $rows = $this->db
                ->select('id, name')
                ->from('category')
                ->where('category_type', 'person')
                ->where('name IS NOT NULL', null, false)
                ->where("TRIM(name) !=", '')
                ->get()->result_array();
            foreach ($rows as $r) {
                $cid  = isset($r['id']) ? (string)$r['id'] : '';
                $name = isset($r['name']) ? trim((string)$r['name']) : '';
                if ($cid !== '' && $name !== '') $occupation_map[$cid] = $name;
            }
        }

        // ---------- Pull all persons (id + first_name) ----------
        $allPersons = $this->db
            ->select('person.id, person.first_name, person.middle_name, person.last_name, person.occupation')
            ->from('person')
            ->order_by("LOWER(TRIM(CONCAT_WS(' ', person.first_name, person.middle_name, person.last_name)))", 'ASC', false)
            ->get()->result();

        // ---------- Build person → category-ids map (junction; fallback to legacy CSV) ----------
        $person_category_map = [];
        if ($this->db->table_exists('person_category')) {
            $jrows = $this->db->select('person_id, category_id')->from('person_category')->get()->result_array();
            foreach ($jrows as $jr) {
                $pid = isset($jr['person_id']) ? (string)$jr['person_id'] : '';
                $cid = isset($jr['category_id']) ? (string)$jr['category_id'] : '';
                if ($pid === '' || $cid === '') continue;
                $person_category_map[$pid][] = $cid;
            }
        }

        foreach ($allPersons as $p) {
            $pid = (string)$p->id;
            $catIds = isset($person_category_map[$pid]) ? $person_category_map[$pid] : [];
            if (empty($catIds) && !empty($p->occupation)) {
                $catIds = array_filter(array_map('trim', explode(',', (string)$p->occupation)));
            }

            $bucketsForThisPerson = [];
            foreach ($catIds as $cid) {
                $catName = isset($occupation_map[$cid]) ? $occupation_map[$cid] : '';
                if ($catName === '') continue;
                $b = $bucket_for_name($catName);
                if ($b !== null) $bucketsForThisPerson[$b] = true;
            }

            if (empty($bucketsForThisPerson)) continue;

            $row = (object)[
                'id'         => $p->id,
                'first_name' => $p->first_name,
            ];
            foreach (array_keys($bucketsForThisPerson) as $b) {
                if (!isset($seenInBucket[$b][$pid])) {
                    $buckets[$b][] = $row;
                    $seenInBucket[$b][$pid] = true;
                }
            }
        }

        echo json_encode([
            'status' => true,
            'data' => [
                'poets'             => $buckets['poets'],
                'singers'           => $buckets['singers'],
                'writers'           => $buckets['writers'],
                'artists'           => $buckets['artists'],
                'legendary_figures' => $buckets['legendary_figures'],
                'other'             => $buckets['other'],
            ]
        ]);
    }

    public function poems_list()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('Content-Type: application/json; charset=utf-8');

        // GET params
        $page   = $this->input->get('page');
        $limit  = $this->input->get('limit');
        $theme  = $this->input->get('theme');
        $poet   = $this->input->get('poet');

        // FIX: null/undefined → empty
        if ($theme === "null" || $theme === "undefined" || $theme === null || $theme === "") {
            $theme = "";
        }
        if ($poet === "null" || $poet === "undefined" || $poet === null || $poet === "") {
            $poet = "";
        }

        // Pagination defaults
        $page  = (!empty($page)  && is_numeric($page))  ? $page  : 1;
        $limit = (!empty($limit) && is_numeric($limit)) ? $limit : 10;

        $offset = ($page - 1) * $limit;

        // ========================================
        // 1. Total count (for pagination)
        // ========================================
        $this->db->from('couplet');

        if ($theme != "") {
            $this->db->where('couplet.original_title', $theme);
        }
        if ($poet != "") {
            $this->db->where('couplet.id', (int)$poet);
        }

        $total = $this->db->count_all_results();

        // ========================================
        // 2. Actual records (with limit + offset)
        // ========================================
        $this->db->select("couplet.id, couplet.original_title, couplet.thumbnail_url");
        $this->db->from('couplet');

        if ($theme != "") {
            $this->db->where('couplet.original_title', $theme);
        }
        if ($poet != "") {
            $this->db->where('couplet.id', (int)$poet);
        }

        $this->db->order_by('couplet.id', 'DESC');
        $this->db->limit($limit, $offset);

        $query = $this->db->get();
        $data = $query->result();

        echo json_encode([
            "status"       => true,
            "page"         => $page,
            "limit"        => $limit,
            "theme"        => $theme,
            "poet"         => $poet,
            "total"        => $total,
            "total_pages"  => ceil($total / $limit),
            "data"         => $data
        ]);
    }

    public function news()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

        $newsId = $this->input->get('news_id');
        if (empty($newsId)) {
            $newsId = $this->input->get('id');
        }

        if (!empty($newsId)) {
            if (!is_numeric($newsId)) {
                echo json_encode([
                    "status" => false,
                    "message" => "Invalid news_id. Please pass numeric news_id."
                ]);
                return;
            }

            $item = $this->db->where('id', (int)$newsId)->get('news')->row();

            if (empty($item)) {
                echo json_encode([
                    "status" => false,
                    "message" => "News not found for given news_id."
                ]);
                return;
            }

            $item->popup_items = [];
            if (!empty($item->news_content)) {
                $decoded = json_decode($item->news_content, true);
                if (is_array($decoded)) {
                    $item->popup_items = $decoded;
                }
            }

            echo json_encode([
                "status" => true,
                "news_id" => (int)$newsId,
                "data" => $item,
            ]);
            return;
        }

        $data = $this->db->order_by('id', 'DESC')->get("news")->result();

        foreach ($data as $index => $row) {
            $data[$index]->popup_items = [];
            if (!empty($row->news_content)) {
                $decoded = json_decode($row->news_content, true);
                if (is_array($decoded)) {
                    $data[$index]->popup_items = $decoded;
                }
            }
        }

        echo json_encode([
            "status" => true,
            "total" => count($data),
            "data" => $data,
        ]);
    }

public function home()
{
     header("Access-Control-Allow-Origin: *");
     header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
     header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
     header('Content-Type: application/json; charset=utf-8');

    // ========= Latest Song =========
    $songTable = $this->SongModel->song_table_name();
    $latestSongId = $this->latest_id_from_table($songTable);
    $song = $latestSongId > 0 ? $this->song_api_record_by_id($latestSongId) : null;

    // ========= Latest Reflection =========
    $reflection = null;
    $latestReflectionId = $this->latest_id_from_table('reflection');
    if ($latestReflectionId > 0) {
        $reflection = $this->ReflectionModel->get_reflection_by_id($latestReflectionId);
        if (!empty($reflection)) {
            $speakerIdsCsv = isset($reflection->speaker_id) ? (string)$reflection->speaker_id : '';
            $speakerNames = $this->reflection_speaker_names_from_csv($speakerIdsCsv);
            $reflection->speaker_id_raw = $speakerIdsCsv;
            $reflection->speaker_id = $speakerNames;
            $reflection->speaker_names = $speakerNames;
        }
    }

    // ========= Latest Poem =========
    $poem = null;
    $latestPoemId = $this->latest_id_from_table('couplet');
    if ($latestPoemId > 0) {
        $poemRow = $this->CoupletModel->get_couplet_by_id($latestPoemId);
        if (is_array($poemRow)) {
            $poemRow = $this->enrich_poem_related_fields($poemRow);
            $poem = (object)$poemRow;
            $poetNames = $this->couplet_poet_names($latestPoemId, $poemRow);
            $poem->poet_names = $poetNames;
            $poem->poet_id_raw = isset($poemRow['poet_id']) ? (string)$poemRow['poet_id'] : '';
            $poem->poet_id = $poetNames;
        }
    }

    // ========= Latest Person =========
    $person = null;
    $latestPersonId = $this->latest_id_from_table('person');
    if ($latestPersonId > 0) {
        $person = $this->PersonModel->get_person_by_id($latestPersonId);
        if (!empty($person)) {
            $person->person_name = trim(($person->first_name ?? '') . ' ' . ($person->middle_name ?? '') . ' ' . ($person->last_name ?? ''));
            $person->occupation_names = $this->occupation_names_for_person($person->id ?? 0, $person->occupation ?? '');
            $displayValue = isset($person->display) ? strtolower(trim((string)$person->display)) : '';
            $publishValue = isset($person->publish) ? strtolower(trim((string)$person->publish)) : '';
            $person->display_label = in_array($displayValue, ['1', 'yes', 'true'], true) ? 'Yes' : 'No';
            $person->publish_label = in_array($publishValue, ['1', 'yes', 'true'], true) ? 'Yes' : 'No';
        }
    }

    // ========= Latest Film =========
    $film = null;
    $latestFilmId = $this->latest_id_from_table('film');
    if ($latestFilmId > 0) {
        $film = $this->FilmModel->get_film_by_id($latestFilmId);
        if (is_object($film)) {
            // directors: convert CSV ids to person names (same display intent as admin)
            $directorNames = [];
            $directorIdsCsv = isset($film->directors) ? (string)$film->directors : '';
            foreach ($this->csv_ids($directorIdsCsv) as $directorId) {
                $dn = $this->person_name_by_id((int)$directorId);
                if ($dn !== '') {
                    $directorNames[] = $dn;
                }
            }
            $film->directors_ids = $directorIdsCsv;
            $film->directors = implode(', ', array_values(array_unique($directorNames)));

            // thumbnail_excerpt should show description column data.
            $descriptionText = isset($film->description) ? trim((string)$film->description) : '';
            if ($descriptionText !== '') {
                $film->thumbnail_excerpt = $descriptionText;
            }
        }
    }

    // Final hard enforcement for poem payload in home API.
    if (is_object($poem) && isset($poem->id)) {
        $poemId = (int)$poem->id;
        if ($poemId > 0) {
            $poemArray = get_object_vars($poem);
            $poemArray = $this->enrich_poem_related_fields($poemArray);
            $poetNames = $this->couplet_poet_names($poemId, $poemArray);
            $poemArray['poet_id_raw'] = isset($poemArray['poet_id']) ? (string)$poemArray['poet_id'] : '';
            $poemArray['poet_id'] = $poetNames;
            $poemArray['poet_names'] = $poetNames;
            $poem = (object)$poemArray;
        }
    }
    
    // ========= Final JSON Output =========
    echo json_encode([
        "status" => true,
        "home_source" => "api_home_v3",
        "latest" => [
            "song"       => $song,
            "reflection" => $reflection,
            "person"     => $person,
            "film"       => $film,
            "poem"       => $poem
        ]
        ]);
}

public function nitesh()
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

    // Get search query
    $search = $this->input->get('search');
    $query = $this->input->get('query');
    
    // Use either search or query parameter
    $searchTerm = !empty($search) ? $search : $query;
    
    // Handle null/undefined/empty values
    if ($searchTerm === "null" || $searchTerm === "undefined" || $searchTerm === null || $searchTerm === "") {
        echo json_encode([
            "status" => false,
            "message" => "Please provide a search query parameter (?search=yourterm or ?query=yourterm)"
        ]);
        return;
    }

    $results = [];
    $counts = [
        'songs' => 0,
        'poems' => 0,
        'reflections' => 0,
        'people' => 0,
        'films' => 0
    ];

    // ========================================
    // 1️⃣ SEARCH SONGS (title, singer)
    // ========================================
    $songTable = $this->SongModel->song_table_name();
    $songIdRows = $this->db->select('id')
        ->from($songTable)
        ->order_by('id', 'DESC')
        ->limit(60)
        ->get()
        ->result();
    $songsAll = $this->map_song_rows_to_admin_shape($songIdRows);
    $songs = [];
    foreach ($songsAll as $songRow) {
        $titleText = strtolower(trim((string)($songRow->umbrellaTitle ?? '')));
        $singerText = strtolower(trim((string)($songRow->singer ?? '')));
        if (stripos($titleText, strtolower($searchTerm)) !== false || stripos($singerText, strtolower($searchTerm)) !== false) {
            $songRow->title = (string)($songRow->umbrellaTitle ?? '');
            $songs[] = $songRow;
        }
        if (count($songs) >= 10) {
            break;
        }
    }
    $results['songs'] = $songs;
    $counts['songs'] = count($songs);

    // ========================================
    // 2️⃣ SEARCH POEMS (original_title)
    // ========================================
    $this->db->select("id, original_title, thumbnail_url");
    $this->db->from('couplet');
    $this->db->like('original_title', $searchTerm);
    $this->db->order_by('id', 'DESC');
    $this->db->limit(10);
    $poems = $this->db->get()->result();
    $results['poems'] = $poems;
    $counts['poems'] = count($poems);

    // ========================================
    // 3️⃣ SEARCH REFLECTIONS (title, meta_description)
    // ========================================
    $this->db->select("
        reflection.id,
        reflection.title,
        reflection.meta_description,
        CONCAT(p.first_name, ' ', COALESCE(p.middle_name,''), ' ', COALESCE(p.last_name,'')) AS person_name_english,
        CONCAT(p.first_name_in_hindi, ' ', COALESCE(p.middle_name_in_hindi,''), ' ', COALESCE(p.last_name_in_hindi,'')) AS person_name_hindi
    ");
    $this->db->from('reflection');
    $this->db->join('reflection_person rp', 'rp.reflection_id = reflection.id', 'left');
    $this->db->join('person p', 'p.id = rp.person_id', 'left');
    $this->db->group_start();
        $this->db->like('reflection.title', $searchTerm);
        $this->db->or_like('reflection.meta_description', $searchTerm);
    $this->db->group_end();
    $this->db->group_by('reflection.id');
    $this->db->order_by('reflection.date_of_upload', 'DESC');
    $this->db->limit(10);
    $reflections = $this->db->get()->result();
    $results['reflections'] = $reflections;
    $counts['reflections'] = count($reflections);

    // ========================================
    // 4️⃣ SEARCH PEOPLE (first_name, last_name)
    // ========================================
    $this->db->select("
        person.id,
        CONCAT(person.first_name, ' ', COALESCE(person.middle_name,''), ' ', COALESCE(person.last_name,'')) AS person_name_english,
        CONCAT(person.first_name_in_hindi, ' ', COALESCE(person.middle_name_in_hindi,''), ' ', COALESCE(person.last_name_in_hindi,'')) AS person_name_hindi,
        person.thumbnail_image_upload,
        category.name AS category_name,
        category.category_type
    ");
    $this->db->from('person');
    $this->db->join('person_category pc', 'pc.person_id = person.id', 'left');
    $this->db->join('category', 'category.id = pc.category_id', 'left');
    $this->db->group_start();
        $this->db->like('person.first_name', $searchTerm);
        $this->db->or_like('person.last_name', $searchTerm);
        $this->db->or_like('person.first_name_in_hindi', $searchTerm);
        $this->db->or_like('person.last_name_in_hindi', $searchTerm);
    $this->db->group_end();
    $this->db->group_by('person.id');
    $this->db->order_by('person.id', 'DESC');
    $this->db->limit(10);
    $people = $this->db->get()->result();
    $results['people'] = $people;
    $counts['people'] = count($people);

    // ========================================
    // 5️⃣ SEARCH FILMS (english_translation, english_transliteration)
    // ========================================
    $this->db->select("
        film.id,
        film.english_translation,
        film.english_transliteration,
        film.duration,
        film.year_of_production,
        film.thumbnail_url,
        film.about_text,
        CONCAT(p.first_name, ' ', COALESCE(p.middle_name,''), ' ', COALESCE(p.last_name,'')) AS director_name_english,
        CONCAT(p.first_name_in_hindi, ' ', COALESCE(p.middle_name_in_hindi,''), ' ', COALESCE(p.last_name_in_hindi,'')) AS director_name_hindi
    ");
    $this->db->from('film');
    $this->db->join('film_director fd', 'fd.film_id = film.id', 'left');
    $this->db->join('person p', 'p.id = fd.director_id', 'left');
    $this->db->group_start();
        $this->db->like('film.english_translation', $searchTerm);
        $this->db->or_like('film.english_transliteration', $searchTerm);
        $this->db->or_like('film.about_text', $searchTerm);
    $this->db->group_end();
    $this->db->group_by('film.id');
    $this->db->order_by('film.year_of_production', 'DESC');
    $this->db->limit(10);
    $films = $this->db->get()->result();
    $results['films'] = $films;
    $counts['films'] = count($films);

    // ========================================
    // TOTAL RESULTS COUNT
    // ========================================
    $totalResults = array_sum($counts);

    // ========================================
    // FINAL JSON RESPONSE
    // ========================================
    echo json_encode([
        "status" => true,
        "query" => $searchTerm,
        "total" => $totalResults,
        "counts" => $counts,
        "results" => $results
    ]);
}

public function song_versions()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('Content-Type: application/json; charset=utf-8');

        $song_id = $this->input->get_post('song_id');
        if (empty($song_id) || !is_numeric($song_id)) {
            echo json_encode([
                "status" => false,
                "message" => "Invalid or missing song_id parameter."
            ]);
            return;
        }

        // Fetch umbrellaTitle for given song_id
        $row = $this->db->select('umbrellaTitle')->get_where('songs', ['id' => (int)$song_id])->row();
        if (empty($row) || empty($row->umbrellaTitle)) {
            echo json_encode([
                "status" => false,
                "message" => "Song not found or umbrellaTitle is empty."
            ]);
            return;
        }

        $umbrella = $row->umbrellaTitle;

        // Get all songs that share the same umbrellaTitle
        $songTable = $this->SongModel->song_table_name();
        $idRows = $this->db->select('id')
            ->from($songTable)
            ->where('umbrellaTitle', $umbrella)
            ->where('id !=', (int)$song_id)
            ->order_by('id', 'DESC')
            ->get()
            ->result();
        $results = $this->map_song_rows_to_admin_shape($idRows);

        echo json_encode([
            "status" => true,
            "umbrellaTitle" => $umbrella,
            "count" => count($results),
            "data" => $results
        ]);
    }

    public function related()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('Content-Type: application/json; charset=utf-8');

        $song_id = $this->input->get_post('song_id');
        $poem_id = $this->input->get_post('poem_id');
        $reflection_id = $this->input->get_post('reflection_id');
        $people_id = $this->input->get_post('people_id');

        if (empty($song_id) && (empty($poem_id) || !is_numeric($poem_id)) && (empty($reflection_id) || !is_numeric($reflection_id)) && (empty($people_id) || !is_numeric($people_id))) {
            echo json_encode([
                "status" => false,
                "message" => "Please provide song_id or poem_id or reflection_id or people_id."
            ]);
            return;
        }

        // if poem_id is provided (and song_id absent), switch context
        if (empty($song_id) && !empty($poem_id)) {
            // treat as poem-based related call
            $poem = $this->db->get_where('couplet', ['id' => (int)$poem_id])->row();
            if (empty($poem)) {
                echo json_encode([
                    "status" => false,
                    "message" => "Poem not found."
                ]);
                return;
            }

            $results = [
                "keywords" => [],
                "songs" => [],
                "poems" => [],
                "reflections" => [],
                "people" => [],
                "films" => []
            ];
            $counts = [
                "songs" => 0,
                "poems" => 0,
                "reflections" => 0,
                "people" => 0,
                "films" => 0
            ];
            // keywords same logic
            $keywords = $this->db->select("id, word_transliteration, word_translation, meta_title, meta_description")
                                ->from('keywords')
                                ->where('is_keyword', 1)
                                ->where('is_published', 1)
                                ->limit(10)
                                ->get()->result();
            $results['keywords'] = $keywords;

            // songs that have this poem as poet
            $this->db->select("songs.id");
            $this->db->from('songs');
            $this->db->where('songs.poet', (int)$poem_id);
            $this->db->order_by('songs.id', 'DESC');
            $this->db->group_by('songs.id');
            $this->db->limit(5);
            $songs = $this->map_song_rows_to_admin_shape($this->db->get()->result());
            $results['songs'] = $songs;
            $counts['songs'] = count($songs);

            // you could add other related items if required (left empty)

            $totalCount = array_sum($counts);
            echo json_encode([
                "status" => true,
                "poem_id" => (int)$poem_id,
                "poem_title" => $poem->original_title,
                "total_related" => $totalCount,
                "counts" => $counts,
                "data" => $results
            ]);
            return;
        }

        if (empty($song_id) && empty($poem_id) && !empty($reflection_id)) {
            // treat as reflection-based related call
            $reflection = $this->db->get_where('reflection', ['id' => (int)$reflection_id])->row();
            if (empty($reflection)) {
                echo json_encode([
                    "status" => false,
                    "message" => "Reflection not found."
                ]);
                return;
            }

            $results = [
                "keywords" => [],
                "songs" => [],
                "poems" => [],
                "reflections" => [],
                "people" => [],
                "films" => []
            ];
            $counts = [
                "songs" => 0,
                "poems" => 0,
                "reflections" => 0,
                "people" => 0,
                "films" => 0
            ];
            // keywords same logic
            $keywords = $this->db->select("id, word_transliteration, word_translation, meta_title, meta_description")
                                ->from('keywords')
                                ->where('is_keyword', 1)
                                ->where('is_published', 1)
                                ->limit(10)
                                ->get()->result();
            $results['keywords'] = $keywords;

            // songs that have this poem as poet
            $this->db->select("songs.id");
            $this->db->from('songs');
            $this->db->where('songs.poet', (int)$reflection_id);
            $this->db->order_by('songs.id', 'DESC');
            $this->db->group_by('songs.id');
            $this->db->limit(5);
            $songs = $this->map_song_rows_to_admin_shape($this->db->get()->result());
            $results['songs'] = $songs;
            $counts['songs'] = count($songs);

            // you could add other related items if required (left empty)

            $totalCount = array_sum($counts);
            echo json_encode([
                "status" => true,
                "reflection_id" => (int)$reflection_id,
                "reflection_title" => $reflection->title,
                "total_related" => $totalCount,
                "counts" => $counts,
                "data" => $results
            ]);
            return;
        }

        if (empty($song_id) && empty($poem_id) && empty($reflection_id) && !empty($people_id)) {
            // treat as people-based related call
            $person = $this->db->select("id, first_name, middle_name, last_name")
                               ->from('person')
                               ->where('id', (int)$people_id)
                               ->get()
                               ->row();

            if (empty($person)) {
                echo json_encode([
                    "status" => false,
                    "message" => "Person not found."
                ]);
                return;
            }

            $results = [
                "keywords" => [],
                "songs" => [],
                "poems" => [],
                "reflections" => [],
                "people" => [],
                "films" => []
            ];

            $counts = [
                "songs" => 0,
                "poems" => 0,
                "reflections" => 0,
                "people" => 0,
                "films" => 0
            ];

            $keywords = $this->db->select("id, word_transliteration, word_translation, meta_title, meta_description")
                                ->from('keywords')
                                ->where('is_keyword', 1)
                                ->where('is_published', 1)
                                ->limit(10)
                                ->get()
                                ->result();
            $results['keywords'] = $keywords;

            $songs = $this->db->select("songs.id")
                              ->from('songs')
                              ->join('song_singer ss', 'ss.song_id = songs.id', 'inner')
                              ->where('ss.singer_id', (int)$people_id)
                              ->group_by('songs.id')
                              ->order_by('songs.id', 'DESC')
                              ->limit(5)
                              ->get()
                              ->result();
            $songs = $this->map_song_rows_to_admin_shape($songs);
            $results['songs'] = $songs;
            $counts['songs'] = count($songs);

            $poems = $this->db->select('couplet.id, couplet.original_title, couplet.thumbnail_url')
                              ->from('couplet')
                              ->join('songs', 'songs.poet = couplet.id', 'inner')
                              ->join('song_singer ss', 'ss.song_id = songs.id', 'inner')
                              ->where('ss.singer_id', (int)$people_id)
                              ->group_by('couplet.id')
                              ->order_by('couplet.id', 'DESC')
                              ->limit(5)
                              ->get()
                              ->result();
            $results['poems'] = $poems;
            $counts['poems'] = count($poems);

            $reflections = $this->db->select("reflection.id, reflection.title, reflection.meta_description")
                                    ->from('reflection')
                                    ->join('reflection_person rp', 'rp.reflection_id = reflection.id', 'inner')
                                    ->where('rp.person_id', (int)$people_id)
                                    ->group_by('reflection.id')
                                    ->order_by('reflection.date_of_upload', 'DESC')
                                    ->limit(5)
                                    ->get()
                                    ->result();
            $results['reflections'] = $reflections;
            $counts['reflections'] = count($reflections);

            $people = $this->db->select("person.id, CONCAT(person.first_name, ' ', COALESCE(person.middle_name,''), ' ', COALESCE(person.last_name,'')) AS person_name, person.thumbnail_url, category.name AS category_name")
                               ->from('person')
                               ->join('person_category pc', 'pc.person_id = person.id', 'left')
                               ->join('category', 'category.id = pc.category_id', 'left')
                               ->where('person.id !=', (int)$people_id)
                               ->where('pc.category_id IN (SELECT category_id FROM person_category WHERE person_id = '.(int)$people_id.')', null, false)
                               ->group_by('person.id')
                               ->limit(5)
                               ->get()
                               ->result();
            $results['people'] = $people;
            $counts['people'] = count($people);

            $films = $this->db->select("film.id, film.english_translation, film.english_transliteration, film.year_of_production, film.thumbnail_url")
                              ->from('film')
                              ->join('film_director fd', 'fd.film_id = film.id', 'inner')
                              ->where('fd.director_id', (int)$people_id)
                              ->group_by('film.id')
                              ->order_by('film.year_of_production', 'DESC')
                              ->limit(5)
                              ->get()
                              ->result();
            $results['films'] = $films;
            $counts['films'] = count($films);

            $totalCount = array_sum($counts);

            echo json_encode([
                "status" => true,
                "people_id" => (int)$people_id,
                "person_name" => trim($person->first_name . ' ' . $person->middle_name . ' ' . $person->last_name),
                "total_related" => $totalCount,
                "counts" => $counts,
                "data" => $results
            ]);
            return;
        }

        // Fetch the song details
        $song = $this->song_api_record_by_id((int)$song_id);

        if (empty($song)) {
            echo json_encode([
                "status" => false,
                "message" => "Song not found."
            ]);
            return;
        }

        $results = [
            "keywords" => [],
            "songs" => [],
            "poems" => [],
            "reflections" => [],
            "people" => [],
            "films" => []
        ];

        $counts = [
            "songs" => 0,
            "poems" => 0,
            "reflections" => 0,
            "people" => 0,
            "films" => 0
        ];

        // ========================================
        // 1️⃣ KEYWORDS - from keywords table
        // ========================================
        $keywords = $this->db->select("id, word_transliteration, word_translation, meta_title, meta_description")
                            ->from('keywords')
                            ->where('is_keyword', 1)
                            ->where('is_published', 1)
                            ->limit(10)
                            ->get()->result();
        $results['keywords'] = $keywords;

        // ========================================
        // 2️⃣ RELATED SONGS (same poet or singer)
        // ========================================
        // Get singer_id from current song
        $singer_row = $this->db->select('singer_id')->from('song_singer')->where('song_id', (int)$song_id)->get()->row();
        $singer_id = !empty($singer_row) ? $singer_row->singer_id : null;

        $this->db->select("songs.id");
        $this->db->from('songs');
        $this->db->join('song_singer ss', 'ss.song_id = songs.id', 'left');
        $this->db->where('songs.id !=', (int)$song_id);
        $this->db->group_start();
            if (!empty($song->poet_raw)) {
                $this->db->where('songs.poet', $song->poet_raw);
            }
            if (!empty($singer_id)) {
                $this->db->or_where('ss.singer_id', $singer_id);
            }
        $this->db->group_end();
        $this->db->group_by('songs.id');
        $this->db->limit(5);
        $songs = $this->map_song_rows_to_admin_shape($this->db->get()->result());
        $results['songs'] = $songs;
        $counts['songs'] = count($songs);

        // ========================================
        // 3️⃣ RELATED POEMS (same poet)
        // ========================================
        if (!empty($song->poet)) {
            $poems = $this->db->select("couplet.id, couplet.original_title, couplet.thumbnail_url")
                            ->from('couplet')
                            ->where('couplet.original_title !=', $song->poet)
                            ->limit(5)
                            ->get()->result();
            $results['poems'] = $poems;
            $counts['poems'] = count($poems);
        }

        // ========================================
        // 4️⃣ RELATED REFLECTIONS (by related people)
        // ========================================
        $reflections = $this->db->select("
            reflection.id,
            reflection.title,
            reflection.meta_description,
            CONCAT(p.first_name, ' ', COALESCE(p.middle_name,''), ' ', COALESCE(p.last_name,'')) AS person_name_english
        ")
        ->from('reflection')
        ->join('reflection_person rp', 'rp.reflection_id = reflection.id', 'left')
        ->join('person p', 'p.id = rp.person_id', 'left')
        ->order_by('reflection.date_of_upload', 'DESC')
        ->group_by('reflection.id')
        ->limit(5)
        ->get()->result();
        $results['reflections'] = $reflections;
        $counts['reflections'] = count($reflections);

        // ========================================
        // 5️⃣ RELATED PEOPLE (singers, poets)
        // ========================================
        $people = $this->db->select("
            person.id,
            CONCAT(person.first_name, ' ', COALESCE(person.middle_name,''), ' ', COALESCE(person.last_name,'')) AS person_name,
            person.thumbnail_url,
            category.name AS category_name
        ")
        ->from('person')
        ->join('person_category pc', 'pc.person_id = person.id', 'left')
        ->join('category', 'category.id = pc.category_id', 'left')
        ->join('song_singer ss', 'ss.singer_id = person.id', 'inner')
        ->where('ss.song_id', (int)$song_id)
        ->group_by('person.id')
        ->limit(5)
        ->get()->result();
        $results['people'] = $people;
        $counts['people'] = count($people);

        // ========================================
        // 6️⃣ RELATED FILMS
        // ========================================
        $films = $this->db->select("
            film.id,
            film.english_translation,
            film.english_transliteration,
            film.year_of_production,
            film.thumbnail_url,
            CONCAT(p.first_name, ' ', COALESCE(p.middle_name,''), ' ', COALESCE(p.last_name,'')) AS director_name
        ")
        ->from('film')
        ->join('film_director fd', 'fd.film_id = film.id', 'left')
        ->join('person p', 'p.id = fd.director_id', 'left')
        ->order_by('film.year_of_production', 'DESC')
        ->group_by('film.id')
        ->limit(5)
        ->get()->result();
        $results['films'] = $films;
        $counts['films'] = count($films);

        // ========================================
        // TOTAL COUNT
        // ========================================
        $totalCount = array_sum($counts);

        echo json_encode([
            "status" => true,
            "song_id" => (int)$song_id,
            "song_title" => $song->umbrellaTitle,
            "total_related" => $totalCount,
            "counts" => $counts,
            "data" => $results
        ]);
    }



}