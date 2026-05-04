<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImportController extends CI_Controller {

    private $old_db;

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        $this->old_db = $this->load->database('ajab_old', TRUE);
    }

    // Menu-to-tables mapping
    private function get_menu_mapping() {
        return array(
            'Songs' => array('songs'),
            'Couplets' => array('couplet', 'couplet_audio', 'couplet_film', 'couplet_filmepisode', 'couplet_people', 'couplet_poet', 'couplet_reflection', 'couplet_relatedcouplet', 'couplet_song', 'couplet_translation', 'couplet_translation_translators', 'couplet_word'),
            'Words' => array('word', 'word_introduction', 'word_person', 'word_reflection', 'word_synonyms', 'word_writer', 'related_words'),
            'Reflections' => array('reflection', 'reflection_couplet', 'reflection_filmepisode', 'reflection_person', 'reflection_song'),
            'People' => array('person', 'person_category', 'occupation'),
            'Films' => array('film', 'film_couplet', 'film_director', 'film_episode', 'film_episode_couplet', 'film_episode_director', 'film_episode_people', 'film_episode_reflection', 'film_episode_song', 'film_episode_word', 'film_primary_people', 'film_primary_reflection', 'film_primary_song', 'film_primary_word', 'film_producer', 'film_secondary_people', 'film_secondary_reflection', 'film_secondary_song', 'film_secondary_word', 'film_details'),
            'Stories' => array('story', 'story_author', 'story_couplet', 'story_episode', 'story_film', 'story_people', 'story_reflection', 'story_song', 'story_word'),
            'Radio' => array('radio'),
            'Echoes' => array('echo', 'echo_couplet', 'echo_song', 'echo_word'),
            'Cartoons' => array('cartoon'),
            'News' => array('news'),
            'Resources' => array('resource'),
            'Contributions' => array('contribute', 'contribute_couplet', 'contribute_episode', 'contribute_film', 'contribute_people', 'contribute_reflection', 'contribute_song', 'contribute_story', 'contribute_word'),
            'About' => array('about', 'about_header', 'about_images', 'about_subheader'),
            'Glossary' => array('glossary'),
            'Keywords' => array('keywords'),
            'Genres' => array('genre'),
            'Categories' => array('category'),
            'Splash Screen' => array('splash_screen'),
            'Gathering' => array('gathering'),
            'Playlists' => array('playlist', 'playlist_tracks'),
            'Users' => array('user', 'users', 'client_user'),
        );
    }

    public function index() {
        $menu_mapping = $this->get_menu_mapping();

        // Get row counts from both databases for each menu
        $menu_data = array();
        foreach ($menu_mapping as $menu_name => $tables) {
            $old_count = 0;
            $live_count = 0;
            $old_tables_exist = array();
            $live_tables_exist = array();

            foreach ($tables as $table) {
                // Check ajab_old
                if ($this->old_db->table_exists($table)) {
                    $old_count += $this->old_db->count_all($table);
                    $old_tables_exist[] = $table;
                }
                // Check ajab_live
                if ($this->db->table_exists($table)) {
                    $live_count += $this->db->count_all($table);
                    $live_tables_exist[] = $table;
                }
            }

            $menu_data[] = array(
                'name' => $menu_name,
                'tables' => $tables,
                'old_count' => $old_count,
                'live_count' => $live_count,
                'old_tables' => $old_tables_exist,
                'live_tables' => $live_tables_exist,
            );
        }

        $data['menu_data'] = $menu_data;
        $this->load->view('inc/header');
        $this->load->view('inc/sidebar');
        $this->load->view('import-panel', $data);
        $this->load->view('inc/footer');
    }

    /**
     * Legacy platform used normalized table `song` (singular) with `about` text.
     * Some exports use `songs` (denormalized) with `interview_about` / `about`.
     */
    private function old_song_master_table() {
        if ($this->old_db->table_exists('song')) {
            return 'song';
        }
        return 'songs';
    }

    /**
     * Resolve "About" text for a song from the old database.
     */
    private function resolve_old_song_about_text($songId, array $songRow) {
        $try = function ($v) {
            if ($v === null) {
                return null;
            }
            $s = trim((string) $v);
            return $s !== '' ? $s : null;
        };
        foreach (array('about', 'interview_about') as $k) {
            if (isset($songRow[$k])) {
                $t = $try($songRow[$k]);
                if ($t !== null) {
                    return $t;
                }
            }
        }
        $songId = (int) $songId;
        if ($songId <= 0) {
            return '';
        }
        if ($this->old_db->table_exists('song')) {
            $row = $this->old_db->get_where('song', array('id' => $songId), 1)->row_array();
            if ($row) {
                foreach (array('about', 'interview_about') as $k) {
                    if (isset($row[$k])) {
                        $t = $try($row[$k]);
                        if ($t !== null) {
                            return $t;
                        }
                    }
                }
            }
        }
        if ($this->old_db->table_exists('songs')) {
            $row = $this->old_db->get_where('songs', array('id' => $songId), 1)->row_array();
            if ($row) {
                foreach (array('about', 'interview_about') as $k) {
                    if (isset($row[$k])) {
                        $t = $try($row[$k]);
                        if ($t !== null) {
                            return $t;
                        }
                    }
                }
            }
        }
        return '';
    }

    // Transform old normalized song data into new denormalized songs table
    private function import_songs_transformed() {
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->empty_table('songs');

        $masterTable = $this->old_song_master_table();

        // Get all songs from old db with title join
        $this->old_db->select('s.*, t.original_title, t.english_translation, t.english_transliteration, ut.english_transliteration as umbrella_title');
        $this->old_db->from($masterTable . ' s');
        $this->old_db->join('title t', 't.id = s.song_title_id', 'left');
        $this->old_db->join('title ut', 'ut.id = s.umbrella_title_id', 'left');
        $old_songs = $this->old_db->get()->result_array();

        $imported_count = 0;
        foreach ($old_songs as $song) {
            $song_id = $song['id'];

            // Get singers (comma separated IDs)
            $singers = $this->old_db->select('singer_id')->get_where('song_singer', array('song_id' => $song_id))->result_array();
            $singer_ids = implode(',', array_column($singers, 'singer_id'));

            // Get poets (comma separated IDs)
            $poets = $this->old_db->select('poet_id')->get_where('song_poet', array('song_id' => $song_id))->result_array();
            $poet_ids = implode(',', array_column($poets, 'poet_id'));

            // Get song text
            $song_text = null;
            if (!empty($song['song_text_id'])) {
                $st = $this->old_db->get_where('song_text', array('id' => $song['song_text_id']))->row_array();
                if ($st) $song_text = $st;
            }

            // Get related songs (peer id is related_song_id, not song_id)
            $rel_songs = $this->old_db->select('related_song_id')->get_where('related_songs', array('song_id' => $song_id))->result_array();
            $related_song_ids = implode(',', array_filter(array_column($rel_songs, 'related_song_id')));

            // Get genres
            $genres = $this->old_db->select('genre_id')->get_where('song_genre', array('song_id' => $song_id))->result_array();
            $genre_ids = implode(',', array_column($genres, 'genre_id'));

            // Get related words
            $words = $this->old_db->select('word_id')->get_where('song_word', array('song_id' => $song_id))->result_array();
            $word_ids = implode(',', array_column($words, 'word_id'));

            // Get related couplets
            $couplets = $this->old_db->select('couplet_id')->get_where('song_couplet', array('song_id' => $song_id))->result_array();
            $couplet_ids = implode(',', array_column($couplets, 'couplet_id'));

            // Get related people
            $people = $this->old_db->select('person_id')->get_where('song_person', array('song_id' => $song_id))->result_array();
            $people_ids = implode(',', array_column($people, 'person_id'));

            // Get related films
            $films = $this->old_db->select('film_id')->get_where('song_film', array('song_id' => $song_id))->result_array();
            $film_ids = implode(',', array_column($films, 'film_id'));

            // Get related film episodes
            $film_eps = $this->old_db->select('film_episode_id')->get_where('song_film_episode', array('song_id' => $song_id))->result_array();
            $film_ep_ids = implode(',', array_column($film_eps, 'film_episode_id'));

            // Reflections linked via junction
            $refl_rows = $this->old_db->select('reflection_id')->get_where('reflection_song', array('song_id' => $song_id))->result_array();
            $reflection_ids = implode(',', array_filter(array_column($refl_rows, 'reflection_id')));

            $new_row = array(
                'id' => $song_id,
                'umbrellaTitle' => $song['umbrella_title'] ?? '',
                'songTitle' => $song['english_translation'] ?? '',
                'Songtitle_transliteration' => $song['english_transliteration'] ?? '',
                'songtitletraan' => $song['english_transliteration'] ?? '',
                'singer' => $singer_ids,
                'words' => $word_ids,
                'songTitleOriginal' => $song['original_title'] ?? '',
                'poet' => $poet_ids,
                'song_title_original' => $song['original_title'] ?? '',
                'year' => '',
                'relatedkeywords' => $word_ids,
                'relatedpoems' => $couplet_ids,
                'location' => '',
                'reflections' => $reflection_ids,
                'couplets' => $couplet_ids,
                'films' => $film_ids,
                'film_episodes' => $film_ep_ids,
                'related_stories' => '',
                'related_people' => $people_ids,
                'related_songs' => $related_song_ids,
                'gatherings' => $song['gathering_id'] ?? '',
                'showOnLandingPage' => $song['show_on_landing_page'] ?? '',
                'songcategory' => $song['song_category'] ?? '',
                'duration' => $song['duration'] ?? '',
                'youtubeVideoId' => $song['youtube_video_id'] ?? '',
                'soundCloudTrackUrl' => $song['soundcloud_track_id'] ?? '',
                'thumbnailUrl' => $song['thumbnail_url'] ?? '',
                'thumbnailexcerpt' => '',
                'downloadUrl' => $song['download_url'] ?? '',
                'genres' => $genre_ids,
                'songLyricsOriginal' => ($song_text ? ($song_text['original'] ?? '') : ''),
                'songLyricsTranslated' => ($song_text ? ($song_text['translation'] ?? '') : ''),
                'songLyricsNotes' => '',
                'songnotes' => '',
                'songglossary' => '',
                'publish' => ($song['is_authoring_complete'] == 1) ? 'true' : 'false',
                'reflection' => ($reflection_ids !== '') ? 'true' : '',
                'metaTitle' => $song['meta_title'] ?? '',
                'metaKeyword' => $song['meta_keywords'] ?? '',
                'metaDescription' => $song['meta_description'] ?? '',
                'added_date' => $song['published_date'] ?? null,
            );

            $this->load->model('SongModel');
            $aboutCol = $this->SongModel->songs_about_column_name();
            $new_row[$aboutCol] = $this->resolve_old_song_about_text($song_id, $song);

            $this->db->insert('songs', $new_row);
            $imported_count++;
        }

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        return $imported_count;
    }

    public function import() {
        $menu_name = $this->input->post('menu_name');
        $menu_mapping = $this->get_menu_mapping();

        if (!isset($menu_mapping[$menu_name])) {
            $this->session->set_flashdata('error', 'Invalid menu selected.');
            redirect('import-panel');
            return;
        }

        // Special handling for Songs - transform from old normalized to new denormalized
        if ($menu_name === 'Songs') {
            $count = $this->import_songs_transformed();
            $this->session->set_flashdata('success', "\"Songs\" imported successfully! $count songs transformed and imported into songs table.");
            redirect('import-panel');
            return;
        }

        $tables = $menu_mapping[$menu_name];
        $imported = 0;
        $skipped = 0;
        $errors = array();

        // Disable foreign key checks on live db
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($tables as $table) {
            // Check if table exists in old db
            if (!$this->old_db->table_exists($table)) {
                $skipped++;
                continue;
            }

            // Check if table exists in live db
            if (!$this->db->table_exists($table)) {
                $skipped++;
                continue;
            }

            // Get columns that exist in BOTH tables
            $live_fields = $this->db->list_fields($table);
            $old_fields = $this->old_db->list_fields($table);
            $common_fields = array_intersect($old_fields, $live_fields);

            if (empty($common_fields)) {
                $skipped++;
                continue;
            }

            // Delete old data from live
            $this->db->empty_table($table);

            // Get only common columns from old db
            $this->old_db->select(implode(',', $common_fields));
            $rows = $this->old_db->get($table)->result_array();

            if (!empty($rows)) {
                // Insert in batches of 500
                $batches = array_chunk($rows, 500);
                foreach ($batches as $batch) {
                    $this->db->insert_batch($table, $batch);
                }
            }

            $imported++;
        }

        // Re-enable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        $this->session->set_flashdata('success', "\"$menu_name\" imported successfully! $imported tables imported, $skipped skipped.");
        redirect('import-panel');
    }

    public function import_all() {
        $menu_mapping = $this->get_menu_mapping();
        $total_imported = 0;
        $total_skipped = 0;

        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($menu_mapping as $menu_name => $tables) {
            foreach ($tables as $table) {
                if (!$this->old_db->table_exists($table) || !$this->db->table_exists($table)) {
                    $total_skipped++;
                    continue;
                }

                $live_fields = $this->db->list_fields($table);
                $old_fields = $this->old_db->list_fields($table);
                $common_fields = array_intersect($old_fields, $live_fields);

                if (empty($common_fields)) {
                    $total_skipped++;
                    continue;
                }

                $this->db->empty_table($table);
                $this->old_db->select(implode(',', $common_fields));
                $rows = $this->old_db->get($table)->result_array();

                if (!empty($rows)) {
                    $batches = array_chunk($rows, 500);
                    foreach ($batches as $batch) {
                        $this->db->insert_batch($table, $batch);
                    }
                }
                $total_imported++;
            }
        }

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        $this->session->set_flashdata('success', "All data imported! $total_imported tables imported, $total_skipped skipped.");
        redirect('import-panel');
    }
}
