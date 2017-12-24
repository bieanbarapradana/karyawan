<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Divisi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('divisi_model','divisi');
	}

	function _render_page($view, $data=null)

	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$this->load->view('back-end/admin/include/header',$this->viewdata);

		$view_html = $this->load->view($view);

		$this->load->view('back-end/admin/include/footer');

	}

	public function index()
	{

		$this->data['add_css'] = array(

	       	base_url('assets/back-end/plugins/morris/morris.css'),
			base_url('assets/back-end/css/bootstrap.min.css'),
			base_url('assets/back-end/css/metisMenu.min.css'),
			base_url('assets/back-end/css/icons.css'),
			base_url('assets/back-end/css/style.css'),


		);

		$this->data['add_js'] = array(

			base_url('assets/back-end/js/metisMenu.min.js'),
			base_url('assets/back-end/js/jquery.slimscroll.min.js'),
			base_url('assets/back-end/plugins/morris/morris.min.js'),
			base_url('assets/back-end/plugins/raphael/raphael-min.js'),
			base_url('assets/back-end/js/jquery.app.js'),

		);


		$this->_render_page('back-end/admin/divisi_view');

	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->divisi->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $divisi) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$divisi->kode_divisi.'">';
			$row[] = $divisi->nama_divisi;
			$row[] = $divisi->gaji_dasar;


			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_divisi('."'".$divisi->kode_divisi."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_divisi('."'".$divisi->kode_divisi."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->divisi->count_all(),
						"recordsFiltered" => $this->divisi->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}


	public function ajax_edit($kode_divisi)
	{
		$data = $this->divisi->get_by_id($kode_divisi);
		echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate();

		$data = array(
				'kode_divisi' => $this->input->post('kode_divisi'),
				'nama_divisi' => $this->input->post('nama_divisi'),
				'gaji_dasar' => $this->input->post('gaji_dasar'),
			);

		$insert = $this->karyawan->save($data);

		echo json_encode(array("status" => TRUE));
	}



	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'nama_divisi' => $this->input->post('nama_divisi'),
				'gaji_dasar' => $this->input->post('gaji_dasar'),
			);

		$this->karyawan->update(array('kode_divisi' => $this->input->post('kode_divisi')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($kode_divisi)
	{
		//delete file
		$kode_divisi = $this->divisi->get_by_id($kode_divisi);
		$this->divisi->delete_by_id($kode_divisi);
		echo json_encode(array("status" => TRUE));
	}


	public function ajax_bulk_delete()
	{
		$list_id_divisi = $this->input->post('kode_divisi');
		foreach ($list_id_divisi as $kode_divisi) {
			$this->divisi->delete_by_id($kode_divisi);
		}
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('nama_divisi') == '')
		{
			$data['inputerror'][] = 'nama_divisi';
			$data['error_string'][] = 'Departments name is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('gaji_dasar') == '')
		{
			$data['inputerror'][] = 'gaji_dasar';
			$data['error_string'][] = 'Basic Salary is required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}



}
