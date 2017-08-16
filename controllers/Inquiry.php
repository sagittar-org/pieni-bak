<?php
class Inquiry extends Controller {

	public function index()
	{
		if ($_POST === [])
		{
			load_template('index');
			exit;
		}
		$result = send_mail_admin([
			'subject' => l('inquiry_subject_admin', [], TRUE),
			'body'    => load_view('inquiry_admin', $_POST, 'email', TRUE),
		]);
		if ($result === TRUE)
		{
			flash(l('inquiry_succeeded', [], TRUE), 'success');
		}
		else
		{
			flash(l('inquiry_failed', [], TRUE), 'danger');
		}
		$result = send_mail([
			'address' => $_POST['email'],
			'subject' => l('inquiry_subject', [], TRUE),
			'body'    => load_view('inquiry', $_POST, 'email', TRUE),
		]);
		redirect('inquiry');
	}
}
