<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_songs_rename_interview_about_to_about extends CI_Migration {

    public function up()
    {
        if (!$this->db->table_exists('songs')) {
            return;
        }
        $fields = $this->db->list_fields('songs');
        if (in_array('about', $fields, true)) {
            return;
        }
        if (!in_array('interview_about', $fields, true)) {
            return;
        }
        $this->db->query('ALTER TABLE `songs` CHANGE `interview_about` `about` LONGTEXT NULL');
    }

    public function down()
    {
        if (!$this->db->table_exists('songs')) {
            return;
        }
        $fields = $this->db->list_fields('songs');
        if (in_array('interview_about', $fields, true)) {
            return;
        }
        if (!in_array('about', $fields, true)) {
            return;
        }
        $this->db->query('ALTER TABLE `songs` CHANGE `about` `interview_about` LONGTEXT NULL');
    }
}
