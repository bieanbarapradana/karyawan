<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

	function validate($username,$password)

	{


		//$query 		= $this->db->get('users');
		$query 		= $this->db->query("select `id_users`, b.id_users_access, `username`, `password`, `firstName`, `lastName`, b.label, `flag` from users a left join users_access b on (b.id_users_access=a.id_users_accsess) where username = '$username ' and password = '$password' ");

		return $query;
	}

}
