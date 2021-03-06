<?php
defined('BASEPATH') OR exit ('No direct script access allowed');

require('./application/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
/**
 * 
 */
class O_perbank extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_perbank');
		$this->load->model('M_excel');
		$this->load->helper('url');
	}

	public function index()
	{
		$data['user'] = $this->db->get_where('tb_users', ['username' => $this->session->userdata('username')])->row_array();
		$data['data_perbank'] = $this->db->get('tb_perbank')->result();

		$this->load->view('operator/header', $data);
		$this->load->view('operator/navbar', $data);
		$this->load->view('operator/sidebar', $data);
		$this->load->view('operator/V_perbank', $data);
		$this->load->view('operator/footer', $data);
	}

	public function tambahPerbank()
	{
		$nama_perbank = $this->input->post('nama_perbank');
		$jml_siswa_perbank = $this->input->post('jml_siswa_perbank');
		$motto_perbank = $this->input->post('motto_perbank');
		$acara_perbank = $this->input->post('acara_perbank');
		$ketua_perbank = $this->input->post('ketua_perbank');
		$id_perbank = $this->input->post('id_perbank');
		$config['upload_path'] = './upload/perbank/';
        $config['allowed_types'] = 'pdf|img|jpg|png';

         $this->load->library('upload', $config);
        if (!$this->upload->do_upload('logo_perbank')) {
        	$data = array(
				
			'nama_perbank' => $nama_perbank,
			'jml_siswa_perbank' => $jml_siswa_perbank,
			'motto_perbank' => $motto_perbank,
			'acara_perbank' => $acara_perbank,
			'ketua_perbank' => $ketua_perbank,
			'id_perbank' => $id_perbank,
			'logo_perbank' => 'kosong'
		);	
		$this->M_perbank->input($data);
		redirect('operator/O_perbank');
        } else {
            $result = $this->upload->data();
            $result1 =$result['file_name'];
            
            $data = array(
				
			'nama_perbank' => $nama_perbank,
			'jml_siswa_perbank' => $jml_siswa_perbank,
			'motto_perbank' => $motto_perbank,
			'acara_perbank' => $acara_perbank,
			'ketua_perbank' => $ketua_perbank,
			'id_perbank' => $id_perbank,
			'logo_perbank' => $result1
		);	
		$this->M_perbank->input($data);
		redirect('operator/O_perbank');
        }
	}

	public function hapus($id)
	{
		$this->M_perbank->hapus($id);
		redirect('operator/O_perbank');
	}

	public function edit($id_perbank)
	{
		$data['perbank'] = $this->db->get_where('tb_perbank', ['id_perbank' => $id_perbank])->row_array();
		$data['user'] = $this->db->get_where('tb_users', ['username' => $this->session->userdata('username')])->row_array();


		$this->load->view('operator/V_edit_perbank', $data);
		$this->load->view('operator/header', $data);
		$this->load->view('operator/footer', $data);
		$this->load->view('operator/sidebar', $data);
	}

	public function prosesEdit()
	{
		$nama_perbank = $this->input->post('nama_perbank');
		$jml_siswa_perbank = $this->input->post('jml_siswa_perbank');
		$motto_perbank = $this->input->post('motto_perbank');
		$acara_perbank = $this->input->post('acara_perbank');
		$ketua_perbank = $this->input->post('ketua_perbank');
		$id_perbank = $this->input->post('id_perbank');
		$config['upload_path'] = './upload/perbank/';
        $config['allowed_types'] = 'pdf|img|jpg|png';

         $this->load->library('upload', $config);
        if (!$this->upload->do_upload('logo_perbank')) {
        	$data = array(
				
			'nama_perbank' => $nama_perbank,
			'jml_siswa_perbank' => $jml_siswa_perbank,
			'motto_perbank' => $motto_perbank,
			'acara_perbank' => $acara_perbank,
			'ketua_perbank' => $ketua_perbank,
			'id_perbank' => $id_perbank,
			'logo_perbank' => 'kosong'
		);	
		$this->db->where('id_perbank', $id_perbank);
        $this->db->update('tb_perbank', $data);
		redirect('operator/O_perbank');
        } else {
            $result = $this->upload->data();
            $result1 =$result['file_name'];
            
            $data = array(
				
			'nama_perbank' => $nama_perbank,
			'jml_siswa_perbank' => $jml_siswa_perbank,
			'motto_perbank' => $motto_perbank,
			'acara_perbank' => $acara_perbank,
			'ketua_perbank' => $ketua_perbank,
			'id_perbank' => $id_perbank,
			'logo_perbank' => $result1
		);	
		$this->db->where('id_perbank', $id_perbank);
        $this->db->update('tb_perbank', $data);
		redirect('operator/O_perbank');
        }
    }

    public function editForm()
	{
		$id_jurusan = $this->input->post('id_perbank');
		$data['data_perbank'] = $this->M_perbank->perbankId($id_multimedia);
		$this->load->view('operator/V_edit_perbank', $data);
	}

	public function export()
  {
    $data['data_perbank'] = $this->db->get('tb_perbank')->result();
    $data_perbank = $this->M_excel->tampilPerbank()->result();

    $spreadsheet = new Spreadsheet;
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A1','No')
    ->setCellValue('B1','Nama Jurusan')
    ->setCellValue('C1','Jumlah Siswa Jurusan')
    ->setCellValue('D1','Motto Jurusan')
    ->setCellValue('E1','Acara Multimedia')
    ->setCellValue('F1','Ketua Jurusan');


    $kolom = 2;
    $no = 1;
    foreach ($data_perbank as $perbank) {
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A' . $kolom, $no)
      ->setCellValue('B' . $kolom, $perbank->nama_perbank)
      ->setCellValue('C' . $kolom, $perbank->jml_siswa_perbank)
      ->setCellValue('D' . $kolom, $perbank->motto_perbank)
      ->setCellValue('E' . $kolom, $perbank->acara_perbank)
      ->setCellValue('F' . $kolom, $perbank->ketua_perbank);


      $kolom++;
      $no++;    
    }

    $writer = new Xlsx($spreadsheet);

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Perbank.xlsx"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
  }
}