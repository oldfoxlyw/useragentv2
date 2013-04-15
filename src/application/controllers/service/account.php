<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller
{
	/**
	 * 
	 * 用户中心帐户相关操作
	 * 
	 * @author johnnyEven
	 * @version useragentV2 service/account.php - 1.0.1.20130415 11:18
	 */
	private $rootPath;
	private $encryptKey = 'useragentV2';
	
	public function __construct()
	{
		parent::__construct();
		$this->rootPath = $this->config->item('root_path');
		
		$this->load->model('maccount');
	}

	public function demo($format = 'json')
	{
		$this->load->library('Guid');
		$this->load->helper('security');

		$accountName = do_hash($this->guid->toString(), 'md5');
		$accountPass = do_hash($this->guid->newGuid()->toString(), 'md5');
		$time = time();

		$row = array(
			'account_name'		=>	$accountName,
			'account_pass'		=>	$this->encryptPass($accountPass);
			'account_regtime'	=>	$time,
			'account_lastlogin'	=>	$time
		);
		$accountId = $this->maccount->create($row);

		$parameter = array(
			'message'	=>	'ACCOUNT_DEMO_SUCCESS',
			'accountId'	=>	$accountId
		);
		echo $this->return_format->format($parameter, $format);
	}

	public function login($format = 'json')
	{
		$accountName = $this->input->get_post('accountName', TRUE);
		$accountPass = $this->input->get_post('accountPass', TRUE);

		if(!empty($accountName) && !empty($accountPass))
		{
			$parameter = array(
				'account_name'	=>	$accountName,
				'account_pass'	=>	$this->encryptPass($accountPass);
			);
			$result = $this->maccount->read($parameter);

			if($result !== FALSE)
			{
				$accountId = $result[0]->account_id;
				$parameter = array(
					'message'	=>	'ACCOUNT_LOGIN_SUCCESS'
					'accountId'	=>	$accountId
				);
				echo $this->return_format->format($parameter, $format);
			}
			else
			{
				$parameter = array(
					'error'		=>	'ACCOUNT_LOGIN_ERROR_NO_RESULT',
				);
				echo $this->return_format->format($parameter, $format);
			}
		}
		else
		{
			$parameter = array(
				'error'		=>	'ACCOUNT_LOGIN_ERROR_NO_PARAM',
			);
			echo $this->return_format->format($parameter, $format);
		}
	}

	public function register($format = 'json')
	{
		$accountName = $this->input->get_post('accountName', TRUE);
		$accountPass = $this->input->get_post('accountPass', TRUE);

		if(!empty($accountName) && !empty($accountPass))
		{
			if($this->isDuplicated($accountName, $accountPass))
			{
				$parameter = array(
					'error'		=>	'ACCOUNT_REGISTER_ERROR_DUPLICATED',
				);
				echo $this->return_format->format($parameter, $format);
			}
			else
			{
				$time = time();
				$row = array(
					'account_name'		=>	$accountName,
					'account_pass'		=>	$this->encryptPass($accountPass);
					'account_regtime'	=>	$time,
					'account_lastlogin'	=>	$time
				);
				$accountId = $this->maccount->create($row);

				$parameter = array(
					'message'	=>	'ACCOUNT_REGISTER_SUCCESS',
					'accountId'	=>	$accountId
				);
				echo $this->return_format->format($parameter, $format);
			}
		}
		else
		{
			$parameter = array(
				'error'		=>	'ACCOUNT_REGISTER_ERROR_NO_PARAM',
			);
			echo $this->return_format->format($parameter, $format);
		}
	}

	public function check_duplicate($format = 'json')
	{

	}

	public function modify($format = 'json')
	{

	}

	private function isDuplicated($accountName, $accountPass)
	{
		if(!empty($accountName) && !empty($accountPass))
		{
			$parameter = array(
				'account_name'	=>	$accountName,
				'account_pass'	=>	$this->encryptPass($accountPass)
			);
			$result = $this->maccount->read($parameter);

			if($result !== FALSE)
			{
				return true;
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

	private function encryptPass($pass)
	{
		$this->load->helper('security');
		return do_hash(do_hash($pass . $this->$encryptKey, 'md5'));
	}
}
?>