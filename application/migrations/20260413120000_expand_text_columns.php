<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_expand_text_columns extends CI_Migration {

    public function up()
    {
        $changes = [
            'songs' => [
                'relatedkeywords TEXT NULL',
                'relatedpoems TEXT NULL',
                'reflections TEXT NULL',
                'couplets TEXT NULL',
                'films TEXT NULL',
                'film_episodes TEXT NULL',
                'related_stories TEXT NULL',
                'related_people TEXT NULL',
                'related_songs TEXT NULL',
                'gatherings TEXT NULL',
                'genres TEXT NULL',
                'songLyricsOriginal LONGTEXT NULL',
                'songLyricsTranslated LONGTEXT NULL',
                'songLyricsNotes LONGTEXT NULL',
                'songLyricsMeaning LONGTEXT NULL',
                'songnotes LONGTEXT NULL',
                'songglossary LONGTEXT NULL',
                'metaDescription LONGTEXT NULL',
                'interview_text LONGTEXT NULL',
                'interview_about LONGTEXT NULL',
                'about LONGTEXT NULL',
                'essay_content LONGTEXT NULL',
                'visual_story_desc LONGTEXT NULL',
            ],
            'reflection' => [
                'verb TEXT NULL',
                'reflection_excerpt TEXT NULL',
                'interview_about LONGTEXT NULL',
                'interview_text LONGTEXT NULL',
                'essay_content LONGTEXT NULL',
                'visual_story_desc LONGTEXT NULL',
                'original_text LONGTEXT NULL',
                'related_keywords TEXT NULL',
                'related_poems TEXT NULL',
                'related_words TEXT NULL',
                'related_episodes TEXT NULL',
                'related_films TEXT NULL',
                'related_couplets TEXT NULL',
                'related_songs TEXT NULL',
                'related_people TEXT NULL',
                'related_stories TEXT NULL',
                'meta_description LONGTEXT NULL',
            ],
            'couplet' => [
                'couplet_transliteration TEXT NULL',
                'couplet_translation TEXT NULL',
                'related_songs TEXT NULL',
                'related_reflections TEXT NULL',
                'related_words TEXT NULL',
                'related_films TEXT NULL',
                'related_filmEpisode TEXT NULL',
                'related_couplets TEXT NULL',
                'related_people TEXT NULL',
                'related_stories TEXT NULL',
                'related_poems TEXT NULL',
                'related_film_episodes TEXT NULL',
                'attributed_poet TEXT NULL',
                'translator TEXT NULL',
                'original_text LONGTEXT NULL',
                'thumbnail_excerpt TEXT NULL',
                'meta_description LONGTEXT NULL',
            ],
            'film_details' => [
                'series_description LONGTEXT NULL',
                'related_primary_songs TEXT NULL',
                'related_keywords TEXT NULL',
                'related_words TEXT NULL',
                'related_people TEXT NULL',
                'related_couplets TEXT NULL',
                'related_reflections TEXT NULL',
                'related_poems TEXT NULL',
                'films TEXT NULL',
                'film_episodes TEXT NULL',
                'related_stories TEXT NULL',
                'film_description LONGTEXT NULL',
                'profile LONGTEXT NULL',
                'about LONGTEXT NULL',
                'notes LONGTEXT NULL',
                'meta_description LONGTEXT NULL',
            ],
            'film_episode_details' => [
                'description LONGTEXT NOT NULL',
                'about_text LONGTEXT NOT NULL',
                'related_songs TEXT NULL',
                'related_reflections TEXT NULL',
                'related_poems TEXT NULL',
                'related_keywords TEXT NULL',
                'related_words TEXT NULL',
                'related_people TEXT NULL',
                'related_couplets TEXT NULL',
                'related_stories TEXT NULL',
                'meta_description LONGTEXT NOT NULL',
            ],
            'story' => [
                'verb TEXT NULL',
                'description LONGTEXT NULL',
                'note LONGTEXT NULL',
                'related_songs TEXT NULL',
                'related_couplets TEXT NULL',
                'related_words TEXT NULL',
                'related_reflections TEXT NULL',
                'related_people TEXT NULL',
                'related_films TEXT NULL',
                'related_filmEpisode TEXT NULL',
                'meta_description LONGTEXT NULL',
            ],
            'word' => [
                'related_songs TEXT NOT NULL',
                'related_reflections TEXT NOT NULL',
                'related_couplets TEXT NOT NULL',
                'related_episodes TEXT NOT NULL',
                'related_people TEXT NOT NULL',
                'related_films TEXT NOT NULL',
                'Related_film_episode TEXT NOT NULL',
                'related_stories TEXT NOT NULL',
                'related_keywords TEXT NOT NULL',
                'related_poems TEXT NOT NULL',
                'glossary_meaning LONGTEXT NOT NULL',
                'english_intro_excerpt LONGTEXT NOT NULL',
                'hindi_intro_excerpt LONGTEXT NOT NULL',
                'english_transliteration LONGTEXT NOT NULL',
                'english_translation LONGTEXT NOT NULL',
                'synonyms LONGTEXT NOT NULL',
                'related_words LONGTEXT NOT NULL',
                'meta_description LONGTEXT NULL',
                'meaning LONGTEXT NULL',
            ],
            'about_header' => [
                'meta_description LONGTEXT NULL',
                'header_text LONGTEXT NULL',
            ],
            'about_subheader' => [
                'subheader_text LONGTEXT NULL',
            ],
            'radio' => [
                'about LONGTEXT NULL',
            ],
        ];

        foreach ($changes as $table => $columns) {
            $this->alterColumnsSafely($table, $columns);
        }
    }

    public function down()
    {
        // Irreversible migration for safe production rollback behavior.
    }

    private function alterColumnsSafely($table, $columnDefinitions)
    {
        if (!$this->db->table_exists($table)) {
            return;
        }

        $fields = $this->db->list_fields($table);
        if (empty($fields)) {
            return;
        }

        foreach ($columnDefinitions as $definition) {
            $column = strtok($definition, ' ');
            if ($column === false || !in_array($column, $fields, true)) {
                continue;
            }
            $this->db->query("ALTER TABLE `{$table}` MODIFY {$this->escapeModifyDefinition($definition)}");
        }
    }

    private function escapeModifyDefinition($definition)
    {
        $parts = preg_split('/\s+/', trim($definition), 2);
        if (count($parts) < 2) {
            return $definition;
        }

        return '`' . $parts[0] . '` ' . $parts[1];
    }
}

