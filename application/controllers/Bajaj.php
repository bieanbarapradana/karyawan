<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bajaj extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('bajaj_model','bajaj');
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


		$this->_render_page('back-end/admin/bajaj_view');

	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->bajaj->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $bajaj) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$bajaj->no_pol.'">';
			$row[] = $bajaj->no_pol;
			$row[] = $bajaj->warna;
			$row[] = $bajaj->tahun;


			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_bajaj('."'".$bajaj->no_pol."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_bajaj('."'".$bajaj->no_pol."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->bajaj->count_all(),
						"recordsFiltered" => $this->bajaj->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}


	public function ajax_edit($no_pol)
	{
		$data = $this->bajaj->get_by_id($no_pol);

		echo json_encode($data);
	}


	public function ajax_add()
	{
		//1. Menjalan fungsi validate, untuk memastikan form telah diisi dengan nilai.
		$this->_validate();

		//2. Kemudian memasukan nilai yang telah diinputkan dari form ke dalam variabel array sbg berikut, nilai dimasukan dalam index-index array.

		$data = array(
				'no_pol' => $this->input->post('no_pol'),
				'warna' => $this->input->post('warna'),
				'tahun' => $this->input->post('tahun'),

			);

		//3. Melakukan pengecekan dari database, atas nilai yang telah diinputkan, apakah sudah ada atau belum ada 
			
		//4. Membentuk variabel untuk menyimpan nilai dari query_model.
		$insert = $this->bajaj->save($data);

		//5. Memparsing data/nilai ke dalam format JSON.
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'no_pol' => $this->input->post('no_pol'),
				'warna' => $this->input->post('warna'),
				'tahun' => $this->input->post('tahun'),

			);


		$this->bajaj->update(array('no_pol' => $this->input->post('no_pol')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($no_pol)
	{
		//delete file
		$bajaj = $this->bajaj->get_by_id($no_pol);

		$this->bajaj->delete_by_id($no_pol);
		echo json_encode(array("status" => TRUE));
	}



	public function ajax_bulk_delete()
	{
		$list_no_pol = $this->input->post('no_pol');
		foreach ($list_no_pol as $no_pol) {
			$this->bajaj->delete_by_id($no_pol);
		}
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		
		  
		if($this->input->post('no_pol') == '')
		{
			$data['inputerror'][] = 'no_pol';
			$data['error_string'][] = 'No Pol harus diisi';
			$data['status'] = FALSE;
		}

		if($this->input->post('warna') == '')
		{
			$data['inputerror'][] = 'warna';
			$data['error_string'][] = 'Color is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('tahun') == '')
		{
			$data['inputerror'][] = 'tahun';
			$data['error_string'][] = 'Date of released is required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}



}
