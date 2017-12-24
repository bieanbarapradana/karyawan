<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Auth_Model','auth');
	}



	public function index()
	{

		$this->load->view('back-end/auth/contentAuth');

	}

	public function login($username, $password){
		$username = $this->input->post('$username');

			$password = md5($this->input->post('password'));




		$cek = $this->auth->validate($username,$password)->row();

		//var_dump($cek);

		if ($cek)

		{

			if ($cek->flag==0) {

				if ($cek->label=='super admin')

				{

					$data = array(

						'id_users' =>$cek->id_users,

						'username' =>$cek->username,

						'full_name' =>$cek->firstName.' '.$cek->lastName,

						'akses' =>$cek->label,

						'foto' =>$cek->foto,

						'login_in' =>true,

					);

					$this->session->set_userdata($data);



					//$this->session->set_flashdata('sukses_login', 'sukses_login');

					redirect(base_url('Karyawan'));

				}

				elseif ($cek->label=='admin')

				{

					$data = array(

						'id_users' =>$cek->id_users,

						'username' =>$cek->username,

						'full_name' =>$cek->firstName.' '.$cek->lastName,

						'akses' =>$cek->label,

						'foto' =>$cek->foto,

						'login_in' =>true,

					);

					$this->session->set_userdata($data);



					//$this->session->set_flashdata('sukses_login', 'sukses_login');

					redirect(base_url('Dashboard'));

				}


				else

				{

					//$this->session->set_flashdata('no_akses','no_akses');

					redirect(base_url('Auth'));

				}

			}

			else

			{

				//$this->session->set_flashdata('lock_account','lock_account');

				redirect(base_url('Auth'));

			}

		}

		else

		{

			//$this->session->set_flashdata('gagal_login','gagal_login');

			redirect(base_url('Auth'));

		}
	}


}
