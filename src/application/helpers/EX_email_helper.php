<?php
function parseEmailAddress($email) {
	if(valid_email($email)) {
		$mailArray = explode('@', $email);
		$email = '<' . $mailArray[0] . '>' . $email;
		return $email;
	} else {
		return false;
	}
}
?>