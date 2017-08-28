<?php
class Auth extends Controller {

	public function __construct()
	{
		parent::__construct();
		$this->db = load_library('db');
	}

	// 会員登録
	public function join($actor)
	{
		if ($_POST === [])
		{
			if (isset($_SESSION[$actor]['auth']['id']) OR ! isset(config('auth')[$actor]['login']))
			{
				redirect($actor, TRUE, FALSE);
			}
			load_template('auth/join');
			exit;
		}
		$sql = config('auth')[$actor]['join'];
		foreach ($_POST as $key => $value)
		{
			if (config('password')['hash'] === TRUE && $key === 'password')
			{
				$value = password_hash($value, PASSWORD_DEFAULT);
			}
			$sql = str_replace('$'.$key, $value, $sql);
		}
		$result = $this->db->query($sql);
		if ($result === TRUE)
		{
			flash(l('auth_join_succeeded', [], TRUE), 'success');
			$row = $this->db->query(str_replace('$1', $this->db->insert_id, config('auth')[$actor]['proxy']))->fetch_assoc();
			$_SESSION[$actor] = ['auth' => $row];
			redirect($actor, TRUE, FALSE);
		}
		flash(l('auth_join_failed', [], TRUE), 'warning');
		redirect("auth/join/{$actor}", TRUE, FALSE);
	}

	// ログイン
	public function login($actor)
	{
		if ($_POST === [])
		{
			if (isset($_SESSION[$actor]['auth']['id']) OR ! isset(config('auth')[$actor]['login']))
			{
				redirect($actor, TRUE, FALSE);
			}
			load_template('auth/login');
			exit;
		}
		$row = $this->db->query(str_replace('$1', $_POST['email'], config('auth')[$actor]['login']))->fetch_assoc();
		if ($row !== NULL && (config('password')['hash'] === TRUE ? password_verify($_POST['password'], $row['password']) : $_POST['password'] === $row['password']))
		{
			$redirect = isset($_SESSION[$actor]['auth']['redirect']) ? $_SESSION[$actor]['auth']['redirect'] : $actor;
			unset($row['password']);
			$_SESSION[$actor] = ['auth' => $row];
			flash(l('auth_login_succeeded', [], TRUE), 'success');
			redirect($redirect, FALSE, FALSE);
		}
		flash(l('auth_login_failed', [], TRUE), 'danger');
		redirect("auth/login/{$actor}");
	}

	// ログアウト
	public function logout($actor)
	{
		unset($_SESSION[$actor]);
		flash(l('auth_logout_succeeded', [], TRUE), 'success');
		redirect('', TRUE, FALSE);
	}
}
