<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Server extends CI_Controller
{
	/**
	 * 
	 * 用户平台服务器相关操作
	 * 
	 * @author johnnyEven
	 * @version Pulse/service server.php - 1.0.1.20130409 10:52
	 */
	private $rootPath;
	
	public function __construct()
	{
		parent::__construct();
		$this->rootPath = $this->config->item('root_path');
		
		$this->load->helper('url');
	}

	public function lists($format = 'json')
	{
		$this->load->model('status');
		$result = $this->status->read();
		if($result->server_status != '1')
		{
			$parameter = array(
				'lists'		=>	'',
				'message'	=>	$result->closed_message
			);
			echo $this->return_format->format($parameter);
			exit();
		}

		$gameId = 1002;

		if(!empty($gameId))
		{
			$this->load->model('mserver');
			$result = $this->mserver->read(array(
				'product_id'	=>	$gameId
			), array(
				'orderby'		=>	array(
					'server_sort',
					'desc'
				)
			));
			$parameter = array(
				'lists'	=>	$result
			);
			echo $this->return_format->format($parameter);
		}
		else
		{
			$parameter = array(
				'message'	=>	'SERVER_LIST_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($parameter);
		}
	}
}
?>