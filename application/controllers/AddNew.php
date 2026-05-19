<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AddNew extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}
	public function song()
	{
		$this->load->view('add-song');
	}
		public function couplet()
	{
		$this->load->view('add-couplet');
	}
		public function word()
	{
		$this->load->view('add-word');
	}
		public function reflection()
	{
		$this->load->view('add-reflection');
	}
		public function person()
	{
		$this->load->view('add-person');
	}
			public function film()
	{
		$this->load->view('add-film');
	}
			public function about()
	{
		$this->load->view('add-about');
	}
			public function story()
	{
		$this->load->view('add-story');
	}
			public function radio()
	{
		$this->load->view('add-radio');
	}
			public function resource()
	{
		redirect('add_new');
	}
			public function upload()
	{
		$this->load->view('add-upload');
	}

		public function wordDetails()
	{
		$this->load->view('add-wordDetails');
	}

	public function Details()
	{
		$this->load->view('add-filmDetails');
	}

	public function filmEpisodeDetails()
	{
		$this->load->view('add-filmEpisodeDetails');
	}
	public function addAdvanceForm()
	{
		$this->load->view('add-advance-form');
	}
	public function pastAddSong()
	{
		$this->load->view('past-add-song');
	}
	public function news()
	{
		$this->load->view('add-news');
	}

	public function ajabShahar($id = null)
	{
		$data = [];
		if (!$this->db->table_exists('ajab_menus')) {
			$sql = "CREATE TABLE IF NOT EXISTS `ajab_menus` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`slug` VARCHAR(100) NOT NULL,
				`label` VARCHAR(150) NOT NULL,
				`sort_order` INT(11) NOT NULL DEFAULT 0,
				`created_at` DATETIME NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `slug` (`slug`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
			$this->db->query($sql);
			$seed = [
				['slug' => 'intro',          'label' => 'Intro',          'sort_order' => 1],
				['slug' => 'translit guide', 'label' => 'Translit Guide', 'sort_order' => 2],
				['slug' => 'copyrights',     'label' => 'Copyrights',     'sort_order' => 3],
			];
			foreach ($seed as $r) {
				$r['created_at'] = date('Y-m-d H:i:s');
				$this->db->insert('ajab_menus', $r);
			}
		}
		$ajabMenus = $this->db->order_by('sort_order', 'ASC')->order_by('id', 'ASC')->get('ajab_menus')->result();
		$typeMap = [];
		foreach ($ajabMenus as $m) { $typeMap[(int)$m->id] = $m->slug; }
		$data['ajab_menus'] = $ajabMenus;

		if ($id !== null && is_numeric($id) && $this->db->table_exists('about')) {
			$row = $this->db->where('id', (int)$id)->where('status', 0)->get('about')->row();
			if (!empty($row)) {
				$row->type_label = isset($typeMap[(int)$row->ajab_type]) ? $typeMap[(int)$row->ajab_type] : '';
				$data['ajab_shahar'] = $row;
			}
		}
		$this->load->view('ajab-shahar', $data);
	}

	public function kabirProject($id = null)
	{
		$data = [];
		// Ensure dynamic kabir_menus table exists & load menus
		$this->load->library('session');
		$this->load->helper('url');
		// Lazy create table if missing (mirrors AddAboutController helper)
		if (!$this->db->table_exists('kabir_menus')) {
			$sql = "CREATE TABLE IF NOT EXISTS `kabir_menus` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`slug` VARCHAR(100) NOT NULL,
				`label` VARCHAR(150) NOT NULL,
				`sort_order` INT(11) NOT NULL DEFAULT 0,
				`created_at` DATETIME NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `slug` (`slug`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
			$this->db->query($sql);
			$seed = [
				['slug' => 'intro',         'label' => 'Intro',         'sort_order' => 1],
				['slug' => 'team',          'label' => 'Team',          'sort_order' => 2],
				['slug' => 'films',         'label' => 'Films',         'sort_order' => 3],
				['slug' => 'books',         'label' => 'Books',         'sort_order' => 4],
				['slug' => 'shabad shaala', 'label' => 'Shabad Shaala', 'sort_order' => 5],
			];
			foreach ($seed as $r) {
				$r['created_at'] = date('Y-m-d H:i:s');
				$this->db->insert('kabir_menus', $r);
			}
		}
		$kabirMenus = $this->db->order_by('sort_order', 'ASC')->order_by('id', 'ASC')->get('kabir_menus')->result();
		$typeMap = [];
		foreach ($kabirMenus as $m) { $typeMap[(int)$m->id] = $m->slug; }
		$data['kabir_menus'] = $kabirMenus;

		if ($id !== null && is_numeric($id) && $this->db->table_exists('about')) {
			$row = $this->db->where('id', (int)$id)->where('status', 1)->get('about')->row();
			if (!empty($row)) {
				$row->type_label = isset($typeMap[(int)$row->kabir_type]) ? $typeMap[(int)$row->kabir_type] : '';
				$data['kabir_project'] = $row;
			}
		}
		$this->load->view('kabir-project', $data);
	}

	public function aboutImages()
	{
		$this->load->view('about-images');
	}
	
}