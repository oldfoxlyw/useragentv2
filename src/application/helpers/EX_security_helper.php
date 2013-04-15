<?php
function encrypt_pass($pass)
{
	return do_hash(do_hash($pass, 'md5'));
}
?>