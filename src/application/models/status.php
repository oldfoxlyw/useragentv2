<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once('ICrud.php');

class Status extends CI_Model {
	private $serverTable = 'pulse_server_status';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function read()
	{
		$result = $this->db->get($this->serverTable);
		if($result !== FALSE)
		{
			return $result->row();
		}
		else
		{
			return FALSE;
		}
	}
}

?>