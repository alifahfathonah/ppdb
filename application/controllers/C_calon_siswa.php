<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_calon_siswa extends CI_Controller {

	public function index()
	{
		$data['title'] = 'Dashboard';
		$data['subtitle'] = 'SMK RISTEK INDRAMAYU';
		$data['content'] = 'calon_siswa/home';

		$this->load->view('calon_siswa/header', $data); 
		$this->load->view('calon_siswa/menu', $data); 
		$this->load->view('calon_siswa/template', $data);
		$this->load->view('calon_siswa/footer', $data);
		
	
	}

}