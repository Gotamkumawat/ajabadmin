<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller
{
    public function run()
    {
        if (!$this->input->is_cli_request()) {
            show_error('This endpoint is CLI-only.', 403);
            return;
        }

        $this->load->config('migration', true);
        $migrationConfig = $this->config->item('migration');
        if (!is_array($migrationConfig)) {
            $migrationConfig = [];
        }
        $migrationConfig['migration_enabled'] = true;

        $this->load->library('migration', $migrationConfig);

        if ($this->migration->latest() === false) {
            echo "Migration failed: " . $this->migration->error_string() . PHP_EOL;
            return;
        }

        $version = (int) $this->db->select('version')->get('migrations')->row('version');
        echo "Migration successful. Current version: {$version}" . PHP_EOL;
    }
}

