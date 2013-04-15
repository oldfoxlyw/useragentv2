<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once('ICrud.php');

class Mserver extends CI_Model implements ICrud {
	private $serverTable = 'pulse_serverlist';
	
	public function __construct()
	{
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
		return $this->db->count_all_results($this->serverTable);
	}
	
	public function create($row)
	{
		if(!empty($row))
		{
			return $this->db->insert($this->serverTable, $row);
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
			if(!empty($extension['orderby']))
			{
				$this->db->order_by($extension['orderby'][0], $extension['orderby'][1]);
			}
		}
		if($limit==0 && $offset==0) {
			$query = $this->db->get($this->serverTable);
		} else {
			$query = $this->db->get($this->serverTable, $limit, $offset);
		}
		if($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function update($id, $row)
	{
		if(is_array($id))
		{
			if(!empty($id['product_id']) && !empty($id['server_id']) && !empty($row))
			{
				$this->db->where('product_id', $id['product_id']);
				$this->db->where('server_id', $id['server_id']);
				return $this->db->update($this->serverTable, $row);
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
	
	public function delete($id)
	{
		if(is_array($id))
		{
			if(!empty($id['product_id']) && !empty($id['server_id']))
			{
				$this->db->where('product_id', $id['product_id']);
				$this->db->where('server_id', $id['server_id']);
				return $this->db->delete($this->serverTable);
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
}

?>