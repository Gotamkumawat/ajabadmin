<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lists extends CI_Controller {


	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
	}
	public function index()
	{
		$this->load->view('list');
	}

	public function songs()
	{
		$this->load->view('songs-list');
	}
	public function couplets()
	{
		$this->load->view('couplets-list');
	}
	public function words()
	{
		$this->load->view('words-list');
	}
	public function reflections()
	{
		$this->load->view('reflections-list');
	}
	public function people()
	{
		$this->load->view('people-list');
	}
	public function occupations()
	{
		$this->load->view('occupation-list');
	}
	public function films()
	{
		$this->load->view('filmsSectionList');
	}
	public function about()
	{
		$this->load->view('about-list');
	}
	public function stories()
	{
		$this->load->view('stories-list');
	}
	public function resources()
	{
		$this->load->view('resources-list');
	}
	public function contributions()
	{
		$this->load->view('contributions-list');
	}
	public function echoes()
	{
		$this->load->view('echoes-list');
	}
	public function cartoons()
	{
		$this->load->view('cartoons-list');
	}

	public function filmsSectionList()
	{
		$this->load->view('filmList');
	}

	public function filmEpisodesList()
	{
		$this->load->view('filmEpisodesList');
	}

	public function SignIn()
	{
		$this->load->view('signin');
	}
    public function list()
	{
		$this->load->view('news-list');
	}

	public function ajabShareList()
	{
		$this->load->view('ajab-share-list');
	}

	public function kabirProjectList()
	{
		$this->load->view('kabir-project-list');
	}

	public function aboutImageList()
	{
		$this->load->view('about-image-list');
	}

	public function EpisodeDetails()
	{
		$this->load->view('filmEpisodeDetails-list');
	}

	public function Details()
	{
		$this->load->view('filmDetails-list');
	}
	
	
}
