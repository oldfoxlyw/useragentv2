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
		$this->load->model('utils/logs');
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
			'account_pass'		=>	$this->encryptPass($accountPass),
			'account_regtime'	=>	$time,
			'account_lastlogin'	=>	$time
		);
		$accountId = $this->maccount->create($row);

		$this->logs->write(array(
			'log_type'		=>	'ACCOUNT_DEMO_SUCCESS',
			'user_name'		=>	$accountName
		));
		$parameter = array(
			'message'		=>	'ACCOUNT_DEMO_SUCCESS',
			'accountId'		=>	$accountId,
			'accountName'	=>	$accountName,
			'accountPass'	=>	$accountPass
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
				'account_pass'	=>	$this->encryptPass($accountPass)
			);
			$result = $this->maccount->read($parameter);

			if($result !== FALSE)
			{
				$accountId = $result[0]->account_id;

				$this->logs->write(array(
					'log_type'		=>	'ACCOUNT_LOGIN_SUCCESS',
					'user_name'		=>	$accountName
				));
				$parameter = array(
					'message'	=>	'ACCOUNT_LOGIN_SUCCESS',
					'accountId'	=>	$accountId
				);
				echo $this->return_format->format($parameter, $format);
			}
			else
			{
				$this->logs->write(array(
					'log_type'		=>	'ACCOUNT_LOGIN_ERROR_NO_RESULT',
					'user_name'		=>	$accountName
				));
				$parameter = array(
					'message'		=>	'ACCOUNT_LOGIN_ERROR_NO_RESULT'
				);
				echo $this->return_format->format($parameter, $format);
			}
		}
		else
		{
			$parameter = array(
				'message'		=>	'ACCOUNT_LOGIN_ERROR_NO_PARAM'
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
			if($this->isDuplicated($accountName))
			{
				$this->logs->write(array(
					'log_type'		=>	'ACCOUNT_REGISTER_ERROR_DUPLICATED',
					'user_name'		=>	$accountName
				));
				$parameter = array(
					'message'		=>	'ACCOUNT_REGISTER_ERROR_DUPLICATED'
				);
				echo $this->return_format->format($parameter, $format);
			}
			else
			{
				$time = time();
				$row = array(
					'account_name'		=>	$accountName,
					'account_pass'		=>	$this->encryptPass($accountPass),
					'account_regtime'	=>	$time,
					'account_lastlogin'	=>	$time
				);
				$accountId = $this->maccount->create($row);

				$this->logs->write(array(
					'log_type'		=>	'ACCOUNT_REGISTER_SUCCESS',
					'user_name'		=>	$accountName
				));
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
				'message'		=>	'ACCOUNT_REGISTER_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($parameter, $format);
		}
	}

	public function check_duplicate($format = 'json')
	{
		$accountName = $this->input->get_post('accountName', TRUE);

		if(!empty($accountName))
		{
			$parameter = array(
				'account_name'	=>	$accountName
			);
			$result = $this->maccount->read($parameter);

			if($result !== FALSE)
			{
				$parameter = array(
					'message'		=>	'ACCOUNT_CHECK_FAIL'
				);
				echo $this->return_format->format($parameter, $format);
			}
			else
			{
				$parameter = array(
					'message'	=>	'ACCOUNT_CHECK_SUCCESS'
				);
				echo $this->return_format->format($parameter, $format);
			}
		}
		else
		{
			$parameter = array(
				'message'		=>	'ACCOUNT_CHECK_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($parameter, $format);
		}
	}

	public function modify($format = 'json')
	{
		$accountId = $this->input->get_post('accountId', TRUE);
		$accountName = $this->input->get_post('accountName', TRUE);
		$accountPass = $this->input->get_post('accountPass', TRUE);

		if(!empty($accountId))
		{
			$row = array();
			if(!empty($accountName))
			{
				if(!$this->isDuplicated($accountName))
				{
					$row['account_name'] = $accountName;
				}
				else
				{
					$this->logs->write(array(
						'log_type'		=>	'ACCOUNT_MODIFY_ERROR_DUPLICATED',
						'user_name'		=>	$accountName
					));
					$parameter = array(
						'message'		=>	'ACCOUNT_MODIFY_ERROR_DUPLICATED'
					);
					echo $this->return_format->format($parameter, $format);
					exit();
				}
			}
			if(!empty($accountPass))
			{
				$row['account_pass'] = $this->encryptPass($accountPass);
			}
			if(!empty($row))
			{
				$this->maccount->update($accountId, $row);

				$this->logs->write(array(
					'log_type'		=>	'ACCOUNT_MODIFY_SUCCESS',
					'user_name'		=>	$accountName
				));
				$parameter = array(
					'message'		=>	'ACCOUNT_MODIFY_SUCCESS'
				);
				echo $this->return_format->format($parameter, $format);
			}
			else
			{
				$parameter = array(
					'message'		=>	'ACCOUNT_MODIFY_ERROR_NO_CHANGE'
				);
				echo $this->return_format->format($parameter, $format);
			}
		}
		else
		{
			$parameter = array(
				'message'		=>	'ACCOUNT_MODIFY_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($parameter, $format);
		}
	}

	private function isDuplicated($accountName)
	{
		if(!empty($accountName))
		{
			$parameter = array(
				'account_name'	=>	$accountName
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
		return do_hash(do_hash($pass . $this->encryptKey, 'md5'));
	}
}
?>