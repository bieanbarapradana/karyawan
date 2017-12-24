<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan_model extends CI_Model {

	var $table = 'karyawan';
	
	var $column_order = array('firstname','lastname','gender','jalan','kota','kode_pos','dob',null); //set column field database for datatable orderable
	var $column_search = array('firstname','lastname','jalan','kota','kode_pos'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	
	var $order = array('id_karyawan' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		
		$this->db->from($this->table);

		$i = 0;
	
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	function count_filtered_tetap()
	{
		$this->_get_datatables_query_tetap();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	public function count_all_tetap()
	{
		$this->db->from($this->table_tetap);
		return $this->db->count_all_results();
	}

	public function get_by_id($id_karyawan)
	{
		$this->db->from($this->table);
		$this->db->where('id_karyawan',$id_karyawan);
		$query = $this->db->get();

		return $query->row();
	}
	
	
	public function buat_kode()   
	{
		 
		  $this->db->select('RIGHT(karyawan.id_karyawan,4) as kode', FALSE);
		  $this->db->order_by('id_karyawan','DESC');    
		  $this->db->limit(1);    
		  $query = $this->db->get('karyawan');      //cek dulu apakah ada sudah ada kode di tabel.
		  if($query->num_rows() !=  0)
		  {      
		   //jika kode ternyata sudah ada.      
		   $data = $query->row();      
		   $kode = intval($data->kode) + 1;    
		  }
		  else 
		  {      
		   //jika kode belum ada      
		   $kode = 1;    
		  }
		  $kodemax = str_pad($kode, 4, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
		  $kodejadi = "ODJ-9921-".$kodemax;    // hasilnya ODJ-9921-0001 dst.
		 
		  return $kodejadi;
		  
		  
	}
	
	public function buat_kontrak()   
	{
		 
		  $this->db->select('RIGHT(karyawan_kontrak.no_kontrak,4) as kode_kontrak', FALSE);
		  $this->db->order_by('no_kontrak','DESC');    
		  $this->db->limit(1);    
		  $query = $this->db->get('karyawan_kontrak');      //cek dulu apakah ada sudah ada kode di tabel.
		  if($query->num_rows() !=  0)
		  {      
		   //jika kode ternyata sudah ada.      
		   $data = $query->row();      
		   $kode_kontrak = intval($data->kode_kontrak) + 1;    
		  }
		  else 
		  {      
		   //jika kode belum ada      
		   $kode_kontrak = 1;    
		  }
		  $kodemax = str_pad($kode_kontrak, 4, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
		  $kodejadi = "CTR-9921-".$kodemax;    // hasilnya ODJ-9921-0001 dst.
		 
		  return $kodejadi;
		  
		  
	}
	
	public function buat_tetap()   
	{
		 
		  $this->db->select('RIGHT(karyawan_tetap.nip,4) as kode_tetap', FALSE);
		  $this->db->order_by('nip','DESC');    
		  $this->db->limit(1);    
		  $query = $this->db->get('karyawan_tetap');      //cek dulu apakah ada sudah ada kode di tabel.
		  if($query->num_rows() !=  0)
		  {      
		   //jika kode ternyata sudah ada.      
		   $data = $query->row();      
		   $kode_tetap = intval($data->kode_tetap) + 1;    
		  }
		  else 
		  {      
		   //jika kode belum ada      
		   $kode_tetap = 1;    
		  }
		  $kodemax = str_pad($kode_tetap, 4, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
		  $kodejadi = "EMP-9921-".$kodemax;    // hasilnya ODJ-9921-0001 dst.
		 
		  return $kodejadi;
		  
		  
	}
	
	public function get_list_divisi()
	{
		$this->db->from('divisi');
		$query = $this->db->get();

		return $query->result();
	}

	public function save($data,$table)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}
	
	public function save_tetap($data,$table)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id_karyawan)
	{
		$this->db->where('id_karyawan', $id_karyawan);
		$this->db->delete($this->table);
	}
	
	
  
}



