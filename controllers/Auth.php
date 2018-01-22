<?php
fallback_ro('Super_auth.php', 'controllers');

class Auth extends Super_auth {

	public function post_join()
	{
		return TRUE;
	}
}
