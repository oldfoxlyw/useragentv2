<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class logs extends CI_Model {
	private $logTable = 'pulse_log';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function write($parameter) {
		if(!empty($parameter) && !empty($parameter['log_type'])) {
			$relativePage				=	$this->input->server('REQUEST_URI');
			$relativeMethod			=	$this->input->server('REQUEST_METHOD');
			$relativeParameter 	=	json_encode($_REQUEST);
			$currentTime				=	date("Y-m-d H:i:s", time());
			
			$currentUser = empty($parameter['user_name']) ? '' : $parameter['user_name'];
			$row = array(
				'log_type'				=>	$parameter['log_type'],
				'log_account_name'		=>	$currentUser,
				'log_uri'				=>	$relativePage,
				'log_method'			=>	$relativeMethod,
				'log_parameter'			=>	$relativeParameter,
				'log_time_local'		=>	$currentTime
			);
			$this->db->insert($this->logTable, $row);
		}
	}
}
?>