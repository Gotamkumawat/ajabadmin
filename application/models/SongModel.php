<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SongModel extends CI_Model {
    private $songTable = null;
    private $songFields = null;

    public function song_table_name() {
        if ($this->songTable !== null) {
            return $this->songTable;
        }
        $this->songTable = $this->db->table_exists('song') ? 'song' : 'songs';
        return $this->songTable;
    }

    private function song_table_fields() {
        if ($this->songFields !== null) {
            return $this->songFields;
        }
        $table = $this->song_table_name();
        $this->songFields = $this->db->table_exists($table) ? $this->db->list_fields($table) : [];
        return $this->songFields;
    }

    private function filter_to_song_table_columns(array $data) {
        $fields = $this->song_table_fields();
        if (empty($fields)) {
            return $data;
        }
        return array_intersect_key($data, array_flip($fields));
    }

    private function remap_song_keys_for_storage(array $data) {
        $map = [
            'showOnLandingPage' => 'show_on_landing_page',
            'youtubeVideoId' => 'youtube_video_id',
            'soundCloudTrackUrl' => 'soundcloud_track_id',
            'thumbnailUrl' => 'thumbnail_url',
            'metaTitle' => 'meta_title',
            'metaKeyword' => 'meta_keywords',
            'metaDescription' => 'meta_description',
            'songcategory' => 'song_category',
            'gatherings' => 'gathering_id',
        ];
        foreach ($map as $from => $to) {
            if (!array_key_exists($from, $data)) {
                continue;
            }
            $val = $data[$from];
            if (is_array($val)) {
                $val = reset($val);
            }
            $data[$to] = $val;
            unset($data[$from]);
        }
        return $data;
    }

    private function merge_song_aliases_for_display(array $song) {
        $aliases = [
            'show_on_landing_page' => 'showOnLandingPage',
            'youtube_video_id' => 'youtubeVideoId',
            'soundcloud_track_id' => 'soundCloudTrackUrl',
            'thumbnail_url' => 'thumbnailUrl',
            'meta_title' => 'metaTitle',
            'meta_keywords' => 'metaKeyword',
            'meta_description' => 'metaDescription',
            'song_category' => 'songcategory',
            'gathering_id' => 'gatherings',
        ];
        foreach ($aliases as $src => $dst) {
            if (isset($song[$src]) && (!isset($song[$dst]) || $song[$dst] === '')) {
                $song[$dst] = $song[$src];
            }
        }
        return $song;
    }

    private function extract_song_text_payload(array &$data) {
        $payload = [
            'original' => array_key_exists('songLyricsOriginal', $data) ? $data['songLyricsOriginal'] : null,
            'transliteration' => array_key_exists('songLyricsNotes', $data) ? $data['songLyricsNotes'] : null,
            'translation' => array_key_exists('songLyricsTranslated', $data) ? $data['songLyricsTranslated'] : null,
        ];
        unset($data['songLyricsOriginal'], $data['songLyricsNotes'], $data['songLyricsTranslated']);
        return $payload;
    }

    private function upsert_song_text($songTextId, array $payload) {
        if (!$this->db->table_exists('song_text')) {
            return $songTextId;
        }
        $allEmpty = true;
        foreach ($payload as $v) {
            if ($v !== null && trim((string) $v) !== '') {
                $allEmpty = false;
                break;
            }
        }
        if ($allEmpty) {
            return $songTextId;
        }
        $row = [
            'original' => $payload['original'],
            'transliteration' => $payload['transliteration'],
            'translation' => $payload['translation'],
        ];
        $songTextId = (int) $songTextId;
        if ($songTextId > 0) {
            $exists = $this->db->get_where('song_text', ['id' => $songTextId])->row_array();
            if ($exists) {
                $this->db->where('id', $songTextId)->update('song_text', $row);
                return $songTextId;
            }
        }
        $this->db->insert('song_text', $row);
        return (int) $this->db->insert_id();
    }

    private function upsert_title($titleId, array $payload) {
        if (!$this->db->table_exists('title')) {
            return (int) $titleId;
        }
        $allEmpty = true;
        foreach ($payload as $v) {
            if ($v !== null && trim((string) $v) !== '') {
                $allEmpty = false;
                break;
            }
        }
        if ($allEmpty) {
            return (int) $titleId;
        }
        $row = [
            'original_title' => $payload['original_title'],
            'english_transliteration' => $payload['english_transliteration'],
            'english_translation' => $payload['english_translation'],
        ];
        $titleId = (int) $titleId;
        if ($titleId > 0) {
            $exists = $this->db->get_where('title', ['id' => $titleId])->row_array();
            if ($exists) {
                $this->db->where('id', $titleId)->update('title', $row);
                return $titleId;
            }
        }
        $this->db->insert('title', $row);
        return (int) $this->db->insert_id();
    }

    private function normalize_single_value($value) {
        if (is_array($value)) {
            $value = reset($value);
        }
        return trim((string) $value);
    }

    private function resolve_title_id_from_input($input) {
        if (!$this->db->table_exists('title')) {
            return 0;
        }
        $v = $this->normalize_single_value($input);
        if ($v === '') {
            return 0;
        }
        if (ctype_digit($v)) {
            $id = (int) $v;
            $exists = $this->db->get_where('title', ['id' => $id])->row_array();
            if ($exists) {
                return $id;
            }
        }
        $row = $this->db->where('english_transliteration', $v)->get('title')->row_array();
        if ($row) {
            return (int) $row['id'];
        }
        $this->db->insert('title', ['english_transliteration' => $v]);
        return (int) $this->db->insert_id();
    }

    /**
     * Actual DB column for song "About" text: `about` or legacy `interview_about`.
     */
    public function songs_about_column_name() {
        static $cached = null;
        if ($cached !== null) {
            return $cached;
        }
        $cached = 'about';
        if (!$this->db->table_exists($this->song_table_name())) {
            return $cached;
        }
        $fields = $this->song_table_fields();
        if (in_array('about', $fields, true)) {
            $cached = 'about';
        } elseif (in_array('interview_about', $fields, true)) {
            $cached = 'interview_about';
        }
        return $cached;
    }

    /**
     * Form/controllers send `about`; map to real column for INSERT/UPDATE.
     */
    public function remap_about_key_for_storage(array $data) {
        if (!array_key_exists('about', $data)) {
            return $data;
        }
        $col = $this->songs_about_column_name();
        $val = $data['about'];
        unset($data['about']);
        $data[$col] = $val;
        return $data;
    }

    /** Ensure $song['about'] is set when only interview_about exists (legacy DB). */
    public function merge_about_for_display(array $song) {
        $col = $this->songs_about_column_name();
        if ($col === 'interview_about' && array_key_exists('interview_about', $song)) {
            if (!isset($song['about']) || trim((string) $song['about']) === '') {
                $song['about'] = $song['interview_about'];
            }
        }
        return $song;
    }

    /**
     * When the primary row is from normalized `song` (no year/location), pull from legacy `songs`
     * or use interview_* fallbacks. Safe no-op if `songs` is missing or row absent.
     */
    public function merge_legacy_year_location_for_song($songId, array $song) {
        $songId = (int) $songId;
        if ($songId <= 0) {
            return $song;
        }
        $needYear = !isset($song['year']) || trim((string) $song['year']) === '';
        $needLoc = !isset($song['location']) || trim((string) $song['location']) === '';
        if (!$needYear && !$needLoc) {
            return $song;
        }
        if (!$this->db->table_exists('songs')) {
            return $song;
        }
        $legacy = $this->db->select('year, location, interview_year, interview_place, interview_audio')
            ->from('songs')
            ->where('id', $songId)
            ->get()
            ->row_array();
        if (empty($legacy)) {
            return $song;
        }
        $needAudio = !isset($song['interview_audio']) || trim((string) $song['interview_audio']) === '';
        if ($needAudio && !empty($legacy['interview_audio'])) {
            $song['interview_audio'] = trim((string) $legacy['interview_audio']);
        }
        if ($needYear) {
            $y = isset($legacy['year']) ? trim((string) $legacy['year']) : '';
            if ($y === '' && isset($legacy['interview_year'])) {
                $y = trim((string) $legacy['interview_year']);
            }
            if ($y !== '') {
                $song['year'] = $y;
            }
        }
        if ($needLoc) {
            $loc = isset($legacy['location']) ? trim((string) $legacy['location']) : '';
            if ($loc === '' && isset($legacy['interview_place'])) {
                $loc = trim((string) $legacy['interview_place']);
            }
            if ($loc !== '') {
                $song['location'] = $loc;
            }
        }
        return $song;
    }

    /**
     * If canonical data lives on `song` but a parallel `songs` row exists (same id), keep year/location in sync.
     */
    private function sync_year_location_to_legacy_songs($songId, $year, $location, array $extra = []) {
        $songId = (int) $songId;
        if ($songId <= 0 || $this->song_table_name() !== 'song' || !$this->db->table_exists('songs')) {
            return;
        }
        $songsCols = $this->db->list_fields('songs');
        $exists = $this->db->get_where('songs', ['id' => $songId])->row_array();
        $row = [];
        if ($year !== null && in_array('year', $songsCols, true)) {
            $row['year'] = $year;
        }
        if ($location !== null && in_array('location', $songsCols, true)) {
            $row['location'] = $location;
        }
        // Sync singer/thumbnail/excerpt (and any extra fields) only if column exists in legacy songs table
        foreach ($extra as $k => $v) {
            if ($v !== null && in_array($k, $songsCols, true)) {
                $row[$k] = $v;
            }
        }
        if (empty($row)) {
            return;
        }
        if (empty($exists)) {
            // Insert new legacy row with same id so edit/read merges find data
            $row['id'] = $songId;
            // year column is NOT NULL in legacy schema — provide default if missing
            if (in_array('year', $songsCols, true) && !isset($row['year'])) {
                $row['year'] = '';
            }
            $this->db->insert('songs', $row);
        } else {
            $this->db->where('id', $songId)->update('songs', $row);
        }
    }

    /**
     * Fill songLyrics* from song_text (via song.song_text_id or legacy songs.song_text_id if present).
     * When primary table is `song` and lyrics are still empty, use denormalized columns on parallel `songs` row.
     */
    public function hydrate_song_lyrics_from_song_text($songId, array $song) {
        $songId = (int) $songId;
        if ($songId <= 0 || !$this->db->table_exists('song_text')) {
            return $song;
        }
        $textId = 0;
        if (isset($song['song_text_id'])) {
            $textId = (int) trim((string) $song['song_text_id']);
        }
        $legacySongs = null;
        if ($textId <= 0 && $this->song_table_name() === 'song' && $this->db->table_exists('songs')) {
            $legacySongs = $this->db->get_where('songs', ['id' => $songId])->row_array();
            if (!empty($legacySongs) && isset($legacySongs['song_text_id'])) {
                $textId = (int) trim((string) $legacySongs['song_text_id']);
            }
        }
        if ($textId > 0) {
            $text = $this->db->get_where('song_text', ['id' => $textId])->row_array();
            if (!empty($text)) {
                $song['songLyricsOriginal'] = (string) ($text['original'] ?? '');
                $song['songLyricsNotes'] = (string) ($text['transliteration'] ?? '');
                $song['songLyricsTranslated'] = (string) ($text['translation'] ?? '');
                if (!isset($song['song_text_id']) || (int) trim((string) $song['song_text_id']) <= 0) {
                    $song['song_text_id'] = $textId;
                }
            }
        }
        if ($this->song_table_name() === 'song' && $this->db->table_exists('songs')) {
            if ($legacySongs === null) {
                $legacySongs = $this->db->get_where('songs', ['id' => $songId])->row_array();
            }
            if (!empty($legacySongs)) {
                foreach (['songLyricsOriginal', 'songLyricsNotes', 'songLyricsTranslated'] as $k) {
                    $cur = isset($song[$k]) ? trim((string) $song[$k]) : '';
                    if ($cur === '' && isset($legacySongs[$k]) && trim((string) $legacySongs[$k]) !== '') {
                        $song[$k] = (string) $legacySongs[$k];
                    }
                }
            }
        }
        return $song;
    }

    public function insert_song($data) {
        $yearSnap = array_key_exists('year', $data) ? $data['year'] : null;
        $locationSnap = array_key_exists('location', $data) ? $data['location'] : null;
        // Capture legacy-only fields before they get filtered out (saved to `songs` table for edit page reads)
        $legacyExtra = [
            'singer'           => array_key_exists('singer', $data) ? $data['singer'] : null,
            'poet'             => array_key_exists('poet', $data) ? $data['poet'] : null,
            'thumbnailUrl'     => array_key_exists('thumbnailUrl', $data) ? $data['thumbnailUrl'] : null,
            'thumbnailexcerpt' => array_key_exists('thumbnailexcerpt', $data) ? $data['thumbnailexcerpt'] : null,
        ];
        $songText = $this->extract_song_text_payload($data);
        $titleOriginal = isset($data['songTitleOriginal']) ? $data['songTitleOriginal'] : null;
        $titleTranslit = isset($data['Songtitle_transliteration']) ? $data['Songtitle_transliteration'] : null;
        $titleTranslation = isset($data['songtitletraan']) ? $data['songtitletraan'] : null;
        $data = $this->remap_about_key_for_storage($data);
        $data = $this->remap_song_keys_for_storage($data);
        $fields = $this->song_table_fields();
        if (in_array('umbrella_title_id', $fields, true) && array_key_exists('umbrellaTitle', $data)) {
            $data['umbrella_title_id'] = $this->resolve_title_id_from_input($data['umbrellaTitle']);
        }
        if (in_array('song_title_id', $fields, true)) {
            $data['song_title_id'] = $this->upsert_title(0, [
                'original_title' => $titleOriginal,
                'english_transliteration' => $titleTranslit,
                'english_translation' => $titleTranslation,
            ]);
        }
        if (in_array('song_text_id', $fields, true)) {
            $data['song_text_id'] = $this->upsert_song_text(0, $songText);
        } else {
            // Legacy denormalized schema
            $data['songLyricsOriginal'] = $songText['original'];
            $data['songLyricsNotes'] = $songText['transliteration'];
            $data['songLyricsTranslated'] = $songText['translation'];
        }
        $data = $this->filter_to_song_table_columns($data);
        $ok = $this->db->insert($this->song_table_name(), $data);
        if ($ok) {
            $newId = (int) $this->db->insert_id();
            $this->sync_year_location_to_legacy_songs($newId, $yearSnap, $locationSnap, $legacyExtra);
            return $newId; // return the new song id so controller doesn't need insert_id()
        }
        return false;
    }

//     public function get_all_songs() {
//     $query = $this->db->get('songs');
//     return $query->result_array();
// }
             // Get cartoon by id
            public function get_songs($id) {
                $query = $this->db->get_where($this->song_table_name(), ['id' => $id]);
                return $query->row_array();
            }




            public function get_all_songs() {
                $this->db->select('id, songTitle, Songtitle_transliteration, singer, words, publish, added_date');
                $this->db->from($this->song_table_name());
                $query = $this->db->get();
                return $query->result_array();
            }

            public function get_song_by_id($id) {
                $song = $this->db->get_where($this->song_table_name(), ['id' => $id])->row_array();
                if (!$song) {
                    return null;
                }
                $id = (int) $id;
                if (
                    isset($song['umbrella_title_id']) &&
                    (int) $song['umbrella_title_id'] > 0 &&
                    $this->db->table_exists('title')
                ) {
                    $umb = $this->db->get_where('title', ['id' => (int) $song['umbrella_title_id']])->row_array();
                    if ($umb) {
                        $song['umbrellaTitle'] = (string) $umb['id'];
                        $song['umbrellaTitleText'] = (string) ($umb['english_transliteration'] ?: $umb['original_title']);
                    }
                }
                if (
                    isset($song['song_title_id']) &&
                    (int) $song['song_title_id'] > 0 &&
                    $this->db->table_exists('title')
                ) {
                    $ttl = $this->db->get_where('title', ['id' => (int) $song['song_title_id']])->row_array();
                    if ($ttl) {
                        if (!isset($song['songTitleOriginal']) || trim((string) $song['songTitleOriginal']) === '') {
                            $song['songTitleOriginal'] = (string) ($ttl['original_title'] ?? '');
                        }
                        if (!isset($song['Songtitle_transliteration']) || trim((string) $song['Songtitle_transliteration']) === '') {
                            $song['Songtitle_transliteration'] = (string) ($ttl['english_transliteration'] ?? '');
                        }
                        if (!isset($song['songtitletraan']) || trim((string) $song['songtitletraan']) === '') {
                            $song['songtitletraan'] = (string) ($ttl['english_translation'] ?? '');
                        }
                    }
                }
                // Prefer CSV on songs row; if empty, legacy rows may live only in junction tables.
                $pairs = [
                    'singer' => ['table' => 'song_singer', 'col' => 'singer_id'],
                    'poet'   => ['table' => 'song_poet', 'col' => 'poet_id'],
                ];
                foreach ($pairs as $field => $meta) {
                    $csv = isset($song[$field]) ? trim((string) $song[$field]) : '';
                    if ($csv === '' && $this->db->table_exists($meta['table'])) {
                        $rows = $this->db->select($meta['col'])
                            ->from($meta['table'])
                            ->where('song_id', $id)
                            ->get()->result_array();
                        if (!empty($rows)) {
                            $song[$field] = implode(',', array_column($rows, $meta['col']));
                        }
                    } elseif ($csv !== '') {
                        $parts = array_filter(array_map('trim', explode(',', $csv)), function ($v) {
                            return $v !== '';
                        });
                        $song[$field] = implode(',', $parts);
                    }
                }

                $this->merge_singer_poet_from_song_person($id, $song);

                $this->merge_related_content_from_junction_tables($id, $song);

                $song = $this->merge_song_aliases_for_display($song);
                $song = $this->merge_about_for_display($song);
                $song = $this->hydrate_song_lyrics_from_song_text($id, $song);
                return $this->merge_legacy_year_location_for_song($id, $song);
            }

            /**
             * When CSV columns on `songs` are empty, fill from normalized junction tables (legacy / import).
             */
            private function merge_related_content_from_junction_tables($songId, array &$song) {
                $songId = (int) $songId;
                if ($songId <= 0) {
                    return;
                }

                // --- Reflections (reflection_song.reflection_id) ---
                if ($this->db->table_exists('reflection_song')) {
                    $csv = isset($song['reflections']) ? trim((string) $song['reflections']) : '';
                    if ($csv === '') {
                        $rows = $this->db->select('reflection_id')
                            ->from('reflection_song')
                            ->where('song_id', $songId)
                            ->get()->result_array();
                        $ids = array_values(array_unique(array_filter(array_map('intval', array_column($rows, 'reflection_id')))));
                        if (!empty($ids)) {
                            $song['reflections'] = implode(',', $ids);
                        }
                    }
                }

                // --- Related songs (related_songs.related_song_id) ---
                if ($this->db->table_exists('related_songs')) {
                    $csv = isset($song['related_songs']) ? trim((string) $song['related_songs']) : '';
                    if ($csv === '') {
                        $rows = $this->db->select('related_song_id')
                            ->from('related_songs')
                            ->where('song_id', $songId)
                            ->get()->result_array();
                        $ids = array_values(array_unique(array_filter(array_map('intval', array_column($rows, 'related_song_id')))));
                        if (!empty($ids)) {
                            $song['related_songs'] = implode(',', $ids);
                        }
                    }
                }

                // --- Keywords: song_word.word_id (`word.id`) ---
                if ($this->db->table_exists('song_word')) {
                    $csv = isset($song['relatedkeywords']) ? trim((string) $song['relatedkeywords']) : '';
                    if ($csv === '') {
                        $rows = $this->db->select('word_id')
                            ->from('song_word')
                            ->where('song_id', $songId)
                            ->get()->result_array();
                        $ids = array_values(array_unique(array_filter(array_map('intval', array_column($rows, 'word_id')))));
                        if (!empty($ids)) {
                            $song['relatedkeywords'] = implode(',', $ids);
                        }
                    }
                }

                // --- Poems / couplets (song_couplet.couplet_id) ---
                if ($this->db->table_exists('song_couplet') || $this->db->table_exists('couplet_song')) {
                    $rp = isset($song['relatedpoems']) ? trim((string) $song['relatedpoems']) : '';
                    $cp = isset($song['couplets']) ? trim((string) $song['couplets']) : '';
                    if ($rp === '' && $cp === '') {
                        $rows = [];
                        if ($this->db->table_exists('song_couplet')) {
                            $rows = $this->db->select('couplet_id')
                                ->from('song_couplet')
                                ->where('song_id', $songId)
                                ->get()->result_array();
                        }
                        if (empty($rows) && $this->db->table_exists('couplet_song')) {
                            $rows = $this->db->select('couplet_id')
                                ->from('couplet_song')
                                ->where('song_id', $songId)
                                ->get()->result_array();
                        }
                        $ids = array_values(array_unique(array_filter(array_map('intval', array_column($rows, 'couplet_id')))));
                        if (!empty($ids)) {
                            $joined = implode(',', $ids);
                            $song['relatedpoems'] = $joined;
                            $song['couplets'] = $joined;
                        }
                    } elseif ($rp === '' && $cp !== '') {
                        $song['relatedpoems'] = $cp;
                    } elseif ($rp !== '' && $cp === '') {
                        $song['couplets'] = $rp;
                    }
                }

                if ($this->db->table_exists('song_film') || $this->db->table_exists('film_primary_song') || $this->db->table_exists('film_secondary_song')) {
                    $csv = isset($song['films']) ? trim((string) $song['films']) : '';
                    if ($csv === '') {
                        $rows = [];
                        if ($this->db->table_exists('song_film')) {
                            $rows = $this->db->select('film_id')
                                ->from('song_film')
                                ->where('song_id', $songId)
                                ->get()->result_array();
                        }
                        if (empty($rows) && $this->db->table_exists('film_primary_song')) {
                            $rows = $this->db->select('film_id')
                                ->from('film_primary_song')
                                ->where('song_id', $songId)
                                ->get()->result_array();
                        }
                        if ($this->db->table_exists('film_secondary_song')) {
                            $rows2 = $this->db->select('film_id')
                                ->from('film_secondary_song')
                                ->where('song_id', $songId)
                                ->get()->result_array();
                            $rows = array_merge($rows, $rows2);
                        }
                        $ids = array_values(array_unique(array_filter(array_map('intval', array_column($rows, 'film_id')))));
                        if (!empty($ids)) {
                            $song['films'] = implode(',', $ids);
                        }
                    }
                }

                if ($this->db->table_exists('song_film_episode') || $this->db->table_exists('film_episode_song')) {
                    $csv = isset($song['film_episodes']) ? trim((string) $song['film_episodes']) : '';
                    if ($csv === '') {
                        $rows = [];
                        if ($this->db->table_exists('song_film_episode')) {
                            $rows = $this->db->select('film_episode_id')
                                ->from('song_film_episode')
                                ->where('song_id', $songId)
                                ->get()->result_array();
                        }
                        if (empty($rows) && $this->db->table_exists('film_episode_song')) {
                            $rows = $this->db->select('film_episode_id')
                                ->from('film_episode_song')
                                ->where('song_id', $songId)
                                ->get()->result_array();
                        }
                        $ids = array_values(array_unique(array_filter(array_map('intval', array_column($rows, 'film_episode_id')))));
                        if (!empty($ids)) {
                            $song['film_episodes'] = implode(',', $ids);
                        }
                    }
                }

                if ($this->db->table_exists('story_song')) {
                    $csv = isset($song['related_stories']) ? trim((string) $song['related_stories']) : '';
                    if ($csv === '') {
                        $rows = $this->db->select('story_id')
                            ->from('story_song')
                            ->where('song_id', $songId)
                            ->get()->result_array();
                        $ids = array_values(array_unique(array_filter(array_map('intval', array_column($rows, 'story_id')))));
                        if (!empty($ids)) {
                            $song['related_stories'] = implode(',', $ids);
                        }
                    }
                }

                // --- Related people: song_person minus singer/poet IDs ---
                if ($this->db->table_exists('song_person')) {
                    $csv = isset($song['related_people']) ? trim((string) $song['related_people']) : '';
                    if ($csv === '') {
                        $rows = $this->db->select('person_id')
                            ->from('song_person')
                            ->where('song_id', $songId)
                            ->get()->result_array();
                        $personIds = array_values(array_unique(array_filter(array_map('intval', array_column($rows, 'person_id')))));
                        $exclude = [];
                        foreach (['singer', 'poet'] as $f) {
                            if (empty($song[$f])) {
                                continue;
                            }
                            foreach (explode(',', (string) $song[$f]) as $p) {
                                $p = (int) trim($p);
                                if ($p > 0) {
                                    $exclude[$p] = true;
                                }
                            }
                        }
                        $related = [];
                        foreach ($personIds as $pid) {
                            if ($pid > 0 && empty($exclude[$pid])) {
                                $related[] = $pid;
                            }
                        }
                        if (!empty($related)) {
                            $song['related_people'] = implode(',', array_unique($related));
                        }
                    }
                }

                // "Is this also a Reflection?" when linked reflections exist but flag was never set
                $reflCsv = isset($song['reflections']) ? trim((string) $song['reflections']) : '';
                $refFlag = isset($song['reflection']) ? trim((string) $song['reflection']) : '';
                if (
                    $reflCsv !== '' &&
                    ($refFlag === '' || $refFlag === '0' || strcasecmp($refFlag, 'false') === 0)
                ) {
                    $song['reflection'] = 'true';
                }
            }

            /**
             * Some imports only fill song_person; split person_id by person.type into singer/poet CSV.
             */
            private function merge_singer_poet_from_song_person($songId, array &$song) {
                if (!$this->db->table_exists('song_person') || !$this->db->table_exists('person')) {
                    return;
                }
                $rows = $this->db->get_where('song_person', ['song_id' => (int) $songId])->result_array();
                if (empty($rows)) {
                    return;
                }
                $singerIds = [];
                $poetIds = [];
                $csvSingers = isset($song['singer']) ? trim((string) $song['singer']) : '';
                if ($csvSingers !== '') {
                    foreach (explode(',', $csvSingers) as $p) {
                        $p = trim($p);
                        if ($p !== '' && ctype_digit($p)) {
                            $singerIds[] = (int) $p;
                        }
                    }
                }
                $csvPoets = isset($song['poet']) ? trim((string) $song['poet']) : '';
                if ($csvPoets !== '') {
                    foreach (explode(',', $csvPoets) as $p) {
                        $p = trim($p);
                        if ($p !== '' && ctype_digit($p)) {
                            $poetIds[] = (int) $p;
                        }
                    }
                }
                foreach ($rows as $r) {
                    $pid = (int) ($r['person_id'] ?? 0);
                    if ($pid <= 0) {
                        continue;
                    }
                    $person = $this->db->get_where('person', ['id' => $pid])->row_array();
                    if (!$person) {
                        continue;
                    }
                    $type = isset($person['type']) ? (int) $person['type'] : 0;
                    if ($type === 1 && !in_array($pid, $singerIds, true)) {
                        $singerIds[] = $pid;
                    } elseif ($type === 2 && !in_array($pid, $poetIds, true)) {
                        $poetIds[] = $pid;
                    }
                }
                if (!empty($singerIds)) {
                    $song['singer'] = implode(',', array_unique($singerIds));
                }
                if (!empty($poetIds)) {
                    $song['poet'] = implode(',', array_unique($poetIds));
                }
            }

            /**
             * Keeps song_singer / song_poet in sync with songs.singer / songs.poet when those columns are updated.
             */
            public function sync_singer_poet_junction_tables($songId, array $data) {
                $songId = (int) $songId;
                if ($songId <= 0) {
                    return;
                }
                if (isset($data['singer']) && $this->db->table_exists('song_singer')) {
                    $this->db->where('song_id', $songId)->delete('song_singer');
                    $ids = array_unique(array_filter(array_map('intval', array_map('trim', explode(',', (string) $data['singer'])))));
                    foreach ($ids as $sid) {
                        if ($sid > 0) {
                            $this->db->insert('song_singer', ['song_id' => $songId, 'singer_id' => $sid]);
                        }
                    }
                }
                if (isset($data['poet']) && $this->db->table_exists('song_poet')) {
                    $this->db->where('song_id', $songId)->delete('song_poet');
                    $ids = array_unique(array_filter(array_map('intval', array_map('trim', explode(',', (string) $data['poet'])))));
                    foreach ($ids as $pid) {
                        if ($pid > 0) {
                            $this->db->insert('song_poet', ['song_id' => $songId, 'poet_id' => $pid]);
                        }
                    }
                }
            }

            /**
             * Mirror of merge_related_content_from_junction_tables (read path):
             * Sync ALL related-content junction tables from posted CSV fields.
             * Called after both insert AND update so new + existing entries land in the same tables
             * that the edit page reads from.
             */
            public function sync_related_junction_tables($songId, array $data) {
                $songId = (int) $songId;
                if ($songId <= 0) {
                    return;
                }
                $csvToIds = function ($csv) {
                    return array_unique(array_filter(array_map('intval', array_map('trim', explode(',', (string) $csv)))));
                };
                $replaceJunction = function ($table, $fkCol, $ids) use ($songId) {
                    if (!$this->db->table_exists($table)) {
                        return;
                    }
                    $this->db->where('song_id', $songId)->delete($table);
                    foreach ($ids as $vid) {
                        if ((int) $vid > 0) {
                            $this->db->insert($table, ['song_id' => $songId, $fkCol => (int) $vid]);
                        }
                    }
                };

                // Reflections → reflection_song.reflection_id
                if (array_key_exists('reflections', $data)) {
                    $replaceJunction('reflection_song', 'reflection_id', $csvToIds($data['reflections']));
                }
                // Related songs → related_songs.related_song_id
                if (array_key_exists('related_songs', $data)) {
                    $replaceJunction('related_songs', 'related_song_id', $csvToIds($data['related_songs']));
                }
                // Keywords → song_word.word_id
                if (array_key_exists('relatedkeywords', $data)) {
                    $replaceJunction('song_word', 'word_id', $csvToIds($data['relatedkeywords']));
                }
                // Poems / couplets → song_couplet.couplet_id (fallback couplet_song)
                if (array_key_exists('relatedpoems', $data) || array_key_exists('couplets', $data)) {
                    $src = !empty($data['relatedpoems']) ? $data['relatedpoems']
                         : (!empty($data['couplets']) ? $data['couplets'] : '');
                    $ids = $csvToIds($src);
                    if ($this->db->table_exists('song_couplet')) {
                        $replaceJunction('song_couplet', 'couplet_id', $ids);
                    } elseif ($this->db->table_exists('couplet_song')) {
                        $replaceJunction('couplet_song', 'couplet_id', $ids);
                    }
                }
                // Films → song_film.film_id (fallback film_primary_song)
                if (array_key_exists('films', $data)) {
                    $ids = $csvToIds($data['films']);
                    if ($this->db->table_exists('song_film')) {
                        $replaceJunction('song_film', 'film_id', $ids);
                    } elseif ($this->db->table_exists('film_primary_song')) {
                        $replaceJunction('film_primary_song', 'film_id', $ids);
                    }
                }
                // Film episodes → song_film_episode.film_episode_id (fallback film_episode_song)
                if (array_key_exists('film_episodes', $data)) {
                    $ids = $csvToIds($data['film_episodes']);
                    if ($this->db->table_exists('song_film_episode')) {
                        $replaceJunction('song_film_episode', 'film_episode_id', $ids);
                    } elseif ($this->db->table_exists('film_episode_song')) {
                        $replaceJunction('film_episode_song', 'film_episode_id', $ids);
                    }
                }
                // Stories → story_song.story_id
                if (array_key_exists('related_stories', $data)) {
                    $replaceJunction('story_song', 'story_id', $csvToIds($data['related_stories']));
                }
                // Related people → song_person.person_id (in addition to singer/poet which use song_singer/song_poet)
                if (array_key_exists('related_people', $data) && $this->db->table_exists('song_person')) {
                    // Don't wipe singer/poet entries — preserve those. Replace only "related_people" set
                    // by deleting rows that are NOT in singer/poet sets, then inserting fresh.
                    $singerIds = !empty($data['singer']) ? $csvToIds($data['singer']) : [];
                    $poetIds = !empty($data['poet']) ? $csvToIds($data['poet']) : [];
                    $keep = array_unique(array_merge($singerIds, $poetIds));
                    $this->db->where('song_id', $songId);
                    if (!empty($keep)) {
                        $this->db->where_not_in('person_id', $keep);
                    }
                    $this->db->delete('song_person');
                    $relatedIds = $csvToIds($data['related_people']);
                    foreach ($relatedIds as $pid) {
                        if ($pid > 0 && !in_array($pid, $keep, true)) {
                            $this->db->insert('song_person', ['song_id' => $songId, 'person_id' => $pid]);
                        }
                    }
                }
            }

            public function update_song($id, $data) {
                $yearSnap = array_key_exists('year', $data) ? $data['year'] : null;
                $locationSnap = array_key_exists('location', $data) ? $data['location'] : null;
                $legacyExtra = [
                    'singer'           => array_key_exists('singer', $data) ? $data['singer'] : null,
                    'poet'             => array_key_exists('poet', $data) ? $data['poet'] : null,
                    'thumbnailUrl'     => array_key_exists('thumbnailUrl', $data) ? $data['thumbnailUrl'] : null,
                    'thumbnailexcerpt' => array_key_exists('thumbnailexcerpt', $data) ? $data['thumbnailexcerpt'] : null,
                ];
                $songText = $this->extract_song_text_payload($data);
                $titleOriginal = isset($data['songTitleOriginal']) ? $data['songTitleOriginal'] : null;
                $titleTranslit = isset($data['Songtitle_transliteration']) ? $data['Songtitle_transliteration'] : null;
                $titleTranslation = isset($data['songtitletraan']) ? $data['songtitletraan'] : null;
                $data = $this->remap_about_key_for_storage($data);
                $data = $this->remap_song_keys_for_storage($data);
                $fields = $this->song_table_fields();
                if (in_array('umbrella_title_id', $fields, true) && array_key_exists('umbrellaTitle', $data)) {
                    $data['umbrella_title_id'] = $this->resolve_title_id_from_input($data['umbrellaTitle']);
                }
                if (in_array('song_title_id', $fields, true)) {
                    $existingTitle = 0;
                    $existingRow = $this->db->get_where($this->song_table_name(), ['id' => $id])->row_array();
                    if ($existingRow && isset($existingRow['song_title_id'])) {
                        $existingTitle = (int) $existingRow['song_title_id'];
                    }
                    $data['song_title_id'] = $this->upsert_title($existingTitle, [
                        'original_title' => $titleOriginal,
                        'english_transliteration' => $titleTranslit,
                        'english_translation' => $titleTranslation,
                    ]);
                }
                if (in_array('song_text_id', $fields, true)) {
                    $existing = $this->db->get_where($this->song_table_name(), ['id' => $id])->row_array();
                    $existingSongTextId = isset($existing['song_text_id']) ? (int) $existing['song_text_id'] : 0;
                    $data['song_text_id'] = $this->upsert_song_text($existingSongTextId, $songText);
                } else {
                    $data['songLyricsOriginal'] = $songText['original'];
                    $data['songLyricsNotes'] = $songText['transliteration'];
                    $data['songLyricsTranslated'] = $songText['translation'];
                }
                $data = $this->filter_to_song_table_columns($data);
                $this->db->where('id', $id);
                $ok = $this->db->update($this->song_table_name(), $data);
                if ($ok) {
                    $this->sync_year_location_to_legacy_songs((int) $id, $yearSnap, $locationSnap, $legacyExtra);
                }
                return $ok;
            }

            public function delete_song($id)
                {
                    // Pehle check kar lo ki record exist karta hai ya nahi
                    $query = $this->db->get_where($this->song_table_name(), ['id' => $id]);
                    if ($query->num_rows() == 0) {
                        return false; // record nahi mila
                    }

                    // Delete karo
                    $this->db->where('id', $id);
                    return $this->db->delete($this->song_table_name());
                }


}
