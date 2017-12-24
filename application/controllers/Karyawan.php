<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('karyawan_model','karyawan');
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


		$this->_render_page('back-end/admin/karyawan_view');

	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->karyawan->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $karyawan) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$karyawan->id_karyawan.'">';
			$row[] = $karyawan->firstName;
			$row[] = $karyawan->lastName;
			$row[] = $karyawan->gender;
			$row[] = $karyawan->jalan;
			$row[] = $karyawan->kota;
			$row[] = $karyawan->kode_pos;
			$row[] = $karyawan->dob;
			if($karyawan->photo)
				$row[] = '<a href="'.base_url('upload/'.$karyawan->photo).'" target="_blank"><img src="'.base_url('upload/'.$karyawan->photo).'" class="img-responsive" /></a>';
			else
				$row[] = '(No photo)';

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_karyawan('."'".$karyawan->id_karyawan."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit/Detil</a>

				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_karyawan('."'".$karyawan->id_karyawan."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->karyawan->count_all(),
						"recordsFiltered" => $this->karyawan->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}


	public function ajax_edit($id_karyawan)
	{
		$data = $this->karyawan->get_by_id($id_karyawan);
		$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ambil_kode()
	{
		/*$data_array = array(
		"data"  => $this->karyawan->buat_kode(),
		"data_kontrak" => $this->karyawan->buat_kontrak(),
		);*/
		//echo json_encode($data_array);
		//echo $data_array;


		$data_divisi = $this->karyawan->get_list_divisi();
		$data = $this->karyawan->buat_kode();
		$data_kontrak = $this->karyawan->buat_kontrak();

		echo $data." ".$data_kontrak;
		//echo $data;
	}

	public function ambil_kode_tetap()
	{
		/*$data_array = array(
		"data"  => $this->karyawan->buat_kode(),
		"data_kontrak" => $this->karyawan->buat_kontrak(),
		);*/
		//echo json_encode($data_array);
		//echo $data_array;
		$this->load->helper('form');

		$data = $this->karyawan->buat_kode();
		$data_tetap = $this->karyawan->buat_tetap();



		echo $data." ".$data_tetap;
		//echo $data;

	}
	public function ajax_select(){
		$data = $this->karyawan->get_list_divisi();

		echo json_encode($data);
	}
	public function ajax_add()
	{
		$this->_validate();

		$data = array(
				'id_karyawan' => $this->input->post('id_karyawan'),
				'firstName' => $this->input->post('firstName'),
				'lastName' => $this->input->post('lastName'),
				'gender' => $this->input->post('gender'),
				'jalan' => $this->input->post('jalan'),
				'kota' => $this->input->post('kota'),
				'kode_pos' => $this->input->post('kode_pos'),
				'dob' => $this->input->post('dob'),
			);

		$kontrak = array(
				'id_karyawan' => $this->input->post('id_karyawan'),
				'no_kontrak' => $this->input->post('no_kontrak'),
				'honor' => $this->input->post('honor'),
		);

		if(!empty($_FILES['photo']['name']))
		{
			$upload = $this->_do_upload();
			$data['photo'] = $upload;
		}

		$insert = $this->karyawan->save($data,"karyawan");
		$insert = $this->karyawan->save($kontrak,"karyawan_kontrak");

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_add_tetap()
	{
		$this->_validate_tetap();

		$data = array(
				'id_karyawan' => $this->input->post('id_karyawan_tetap'),
				'firstName' => $this->input->post('firstName'),
				'lastName' => $this->input->post('lastName'),
				'gender' => $this->input->post('gender'),
				'jalan' => $this->input->post('jalan'),
				'kota' => $this->input->post('kota'),
				'kode_pos' => $this->input->post('kode_pos'),
				'dob' => $this->input->post('dob'),
			);

		$tetap = array(
				'id_karyawan' => $this->input->post('id_karyawan_tetap'),
				'nip' => $this->input->post('nip'),
				'gaji' => $this->input->post('gaji'),
				'kode_divisi' => $this->input->post('kode_divisi'),
		);

		if(!empty($_FILES['photo']['name']))
		{
			$upload = $this->_do_upload();
			$data['photo'] = $upload;
		}

		$insert = $this->karyawan->save_tetap($data,"karyawan");
		$insert = $this->karyawan->save_tetap($tetap,"karyawan_tetap");

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'firstName' => $this->input->post('firstName'),
				'lastName' => $this->input->post('lastName'),
				'gender' => $this->input->post('gender'),
				'jalan' => $this->input->post('jalan'),
				'kota' => $this->input->post('kota'),
				'kode_pos' => $this->input->post('kode_pos'),
				'dob' => $this->input->post('dob'),
			);

		if($this->input->post('remove_photo')) // if remove photo checked
		{
			if(file_exists('upload/'.$this->input->post('remove_photo')) && $this->input->post('remove_photo'))
				unlink('upload/'.$this->input->post('remove_photo'));
			$data['photo'] = '';
		}

		if(!empty($_FILES['photo']['name']))
		{
			$upload = $this->_do_upload();

			//delete file
			$karyawan = $this->karyawan->get_by_id($this->input->post('id_karyawan'));
			if(file_exists('upload/'.$karyawan->photo) && $karyawan->photo)
				unlink('upload/'.$karyawan->photo);

			$data['photo'] = $upload;
		}

		$this->karyawan->update(array('id_karyawan' => $this->input->post('id_karyawan')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id_karyawan)
	{
		//delete file
		$karyawan = $this->karyawan->get_by_id($id_karyawan);
		if(file_exists('upload/'.$karyawan->photo) && $karyawan->photo)
			unlink('upload/'.$karyawan->photo);

		$this->karyawan->delete_by_id($id_karyawan);
		echo json_encode(array("status" => TRUE));
	}

	private function _do_upload()
	{
		$config['upload_path']          = 'upload/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 100; //set max size allowed in Kilobyte
        $config['max_width']            = 1000; // set max width image allowed
        $config['max_height']           = 1000; // set max height allowed
        $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('photo')) //upload and validate
        {
            $data['inputerror'][] = 'photo';
			$data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
			$data['status'] = FALSE;
			echo json_encode($data);
			exit();
		}
		return $this->upload->data('file_name');
	}

	public function ajax_bulk_delete()
	{
		$list_id_karyawan = $this->input->post('id_karyawan');
		foreach ($list_id_karyawan as $id_karyawan) {
			$this->karyawan->delete_by_id($id_karyawan);
		}
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('firstName') == '')
		{
			$data['inputerror'][] = 'firstName';
			$data['error_string'][] = 'First name is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('lastName') == '')
		{
			$data['inputerror'][] = 'lastName';
			$data['error_string'][] = 'Last name is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('dob') == '')
		{
			$data['inputerror'][] = 'dob';
			$data['error_string'][] = 'Date of Birth is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('gender') == '')
		{
			$data['inputerror'][] = 'gender';
			$data['error_string'][] = 'Please select gender';
			$data['status'] = FALSE;
		}

		if($this->input->post('jalan') == '')
		{
			$data['inputerror'][] = 'jalan';
			$data['error_string'][] = 'Street is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('kota') == '')
		{
			$data['inputerror'][] = 'kota';
			$data['error_string'][] = 'City is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('kode_pos') == '')
		{
			$data['inputerror'][] = 'kode_pos';
			$data['error_string'][] = 'Postal Code is required';
			$data['status'] = FALSE;
		}



		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	private function _validate_tetap()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('firstName') == '')
		{
			$data['inputerror'][] = 'firstName';
			$data['error_string'][] = 'First name is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('lastName') == '')
		{
			$data['inputerror'][] = 'lastName';
			$data['error_string'][] = 'Last name is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('dob') == '')
		{
			$data['inputerror'][] = 'dob';
			$data['error_string'][] = 'Date of Birth is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('gender') == '')
		{
			$data['inputerror'][] = 'gender';
			$data['error_string'][] = 'Please select gender';
			$data['status'] = FALSE;
		}

		if($this->input->post('jalan') == '')
		{
			$data['inputerror'][] = 'jalan';
			$data['error_string'][] = 'Street is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('kota') == '')
		{
			$data['inputerror'][] = 'kota';
			$data['error_string'][] = 'City is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('kode_pos') == '')
		{
			$data['inputerror'][] = 'kode_pos';
			$data['error_string'][] = 'Postal Code is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('kode_divisi') == '')
		{
			$data['inputerror'][] = 'kode_divisi';
			$data['error_string'][] = 'Please select department';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
