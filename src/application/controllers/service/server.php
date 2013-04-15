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
		$gameId = $this->input->post('gameId', TRUE);

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
			echo $this->return_format->format($result);
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