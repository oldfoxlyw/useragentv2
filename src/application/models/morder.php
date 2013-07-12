<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Morder extends CI_Model {
	private $accountTable = 'pulse_order';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function count($parameter = null, $extension = null)
	{
		if(!empty($parameter))
		{
			foreach($parameter as $key=>$value)
			{
				$this->db->where($key, $value);
			}
		}
		if(!empty($extension))
		{
			
		}
		return $this->db->count_all_results($this->accountTable);
	}
	
	public function create($row)
	{
		if(!empty($row))
		{
			if($this->db->insert($this->accountTable, $row))
			{
				return $this->db->insert_id();
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	public function read($parameter = null, $extension = null, $limit = 0, $offset = 0)
	{
		if(!empty($parameter))
		{
			foreach($parameter as $key=>$value)
			{
				$this->db->where($key, $value);
			}
		}
		if(!empty($extension))
		{
			
		}
		if($limit==0 && $offset==0) {
			$query = $this->db->get($this->accountTable);
		} else {
			$query = $this->db->get($this->accountTable, $limit, $offset);
		}
		if($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function update($id, $row)
	{
		if(!empty($id) && !empty($row))
		{
			$this->db->where('order_id', $id);
			return $this->db->update($this->accountTable, $row);
		}
		else
		{
			return false;
		}
	}
	
	public function delete($id)
	{
		if(!empty($id))
		{
			$this->db->where('order_id', $id);
			return $this->db->delete($this->accountTable);
		}
		else
		{
			return false;
		}
	}
	
	public function addCount($checksum) {
		if(!empty($checksum)) {
			$this->db->where('checksum', $checksum);
			$this->db->set('checkcount', 'checkcount+1', false);
			return $this->db->update($this->accountTable);
		} else {
			return false;
		}
	}
}
?>