<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function showMessage($type, $info, $message, $redirect = '', $auteRedirect = false, $delay = 0)
{
	$redirect = empty($redirect) ? '' : urlencode(site_url($redirect));
	$message = urlencode($message);
	$auteRedirect = $auteRedirect ? 1 : 0;
	redirect(site_url("message?type={$type}&info={$info}&message={$message}&redirect={$redirect}&auto_redirect={$auteRedirect}&auto_delay={$delay}"));
}

function showSyncLogin($redirect)
{
	$redirect = empty($redirect) ? '' : urlencode(site_url($redirect));
	redirect(site_url("utils/sync/sync_login?redirect={$redirect}"));
}

function showSyncLogout($redirect)
{
	$redirect = empty($redirect) ? '' : urlencode(site_url($redirect));
	redirect(site_url("utils/sync/sync_logout?redirect={$redirect}"));
}

?>