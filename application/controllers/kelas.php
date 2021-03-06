<?php if(! defined('BASEPATH')) exit('No direct script access allowed');

class Kelas extends CI_Controller {
	function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->library(array('session', 'pagination'));
		$this->load->helper(array('url', 'form', 'date', 'security', 'string'));
		$this->load->model('m_kelas','',TRUE);
	}
	
	function get_kelas() {
		if($this->session->userdata('admin') == '') {
			redirect('wonggundul/index');
		}
		
		//gunakan ini untuk data kelas tanpa paging
		/*
		$kelas = $this->m_kelas->get_all_kelas();
		
		$data = array(
			'kelas' => $kelas,
			'url' => base_url()
		);
		$this->load->view('admin/data_kelas', $data);
		*/
		
		$jumlah = $this->m_kelas->get_jum_kelas();
		
		$config['base_url'] = base_url(). "/kelas/get_kelas";
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 5;
		
		//gunakan config ini untuk pagination bootstrap
		$config['full_tag_open'] = '<div class="pagination"><ul>';
		$config['full_tag_close'] = '</ul></div>';
		 
		$config['first_link'] = '&laquo; First';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';
		 
		$config['last_link'] = 'Last &raquo;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';
		 
		$config['next_link'] = 'Next &rarr;';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';
		 
		$config['prev_link'] = '&larr; Previous';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';
		 
		$config['cur_tag_open'] = '<li class="active"><a href="">';
		$config['cur_tag_close'] = '</a></li>';
		 
		$config['num_tag_open'] = '<li class="page">';
		$config['num_tag_close'] = '</li>';
		
		$this->pagination->initialize($config);
	   
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
	   
		$data['kelas'] = $this->m_kelas->get_all_kelas($config["per_page"], $page);
		$data['links'] = $this->pagination->create_links();
		$data['url'] = base_url();
		$this->load->view('admin/data_kelas', $data);
	}
	
	function tambah_kelas() {
		if($this->session->userdata('admin') == '') {
			redirect('wonggundul/index');
		}
		
		$this->load->view('admin/tambah_kelas');
	}
	
	function add_kelas() {
		$nama = $this->input->post('nama');
		$this->m_kelas->insert_kelas($nama);
		
		$this->session->set_flashdata('success', 'Data Berhasil Ditambahkan!');
		redirect('Kelas/get_kelas');
	}
	
	function hapus_kelas() {
		$id = $this->uri->segment('3');
		$this->m_kelas->delete_kelas($id);
		
		$this->session->set_flashdata('hapus', 'Data Berhasil Dihapus!');
		redirect('Kelas/get_kelas');
	}
	
	function edit_kelas() {
		if($this->session->userdata('admin') == '') {
			redirect('wonggundul/index');
		}
		
		$id = $this->uri->segment('3');
		$kelas = $this->m_kelas->get_kelas_by_id($id);
		
		$data = array(
			'kelas' => $kelas,
			'url' => base_url()
		);
		$this->load->view('admin/update_kelas', $data);
	}
	
	function update_kelas() {
		$id = $this->input->post('id');
		$nama = $this->input->post('nama');
		$this->m_kelas->update_kelas($id, $nama);
		
		$this->session->set_flashdata('update', 'Data Berhasil Diperbarui!');
		redirect('Kelas/get_kelas');
	}
}