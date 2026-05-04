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
		$this->load->view('add-resource');
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
		if ($id !== null && is_numeric($id) && $this->db->table_exists('about')) {
			$row = $this->db->where('id', (int)$id)->where('status', 0)->get('about')->row();
			if (!empty($row)) {
				$typeMap = [
					1 => 'intro',
					2 => 'translit guide',
					3 => 'copyrights'
				];
				$row->type_label = isset($typeMap[(int)$row->ajab_type]) ? $typeMap[(int)$row->ajab_type] : '';
				$data['ajab_shahar'] = $row;
			}
		}
		$this->load->view('ajab-shahar', $data);
	}

	public function kabirProject($id = null)
	{
		$data = [];
		if ($id !== null && is_numeric($id) && $this->db->table_exists('about')) {
			$row = $this->db->where('id', (int)$id)->where('status', 1)->get('about')->row();
			if (!empty($row)) {
				$typeMap = [
					1 => 'intro team',
					2 => 'films',
					3 => 'books',
					4 => 'shabad shaala'
				];
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