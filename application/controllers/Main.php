<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Main extends CI_Controller
{
	public $mail;
	function __construct()
	{
		parent::__construct();
		$this->load->model('Main_model', 'mainModel');

		require_once APPPATH . 'libraries/PHPMailer/Exception.php';
		require_once APPPATH . 'libraries/PHPMailer/PHPMailer.php';
		require_once APPPATH . 'libraries/PHPMailer/SMTP.php';

		$this->mail = new PHPMailer(true);
	}

	public function send()
	{
		$request = json_decode(file_get_contents('php://input'), true);
		$token = $request['token'];

		$user = $this->mainModel->getClient($token);
		$this->loadMailer();
	}

	public function loadMailer()
	{
		//Create an instance; passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try {
			//Server settings
			$mail->SMTPDebug = SMTP::DEBUG_SERVER;
			$mail->isSMTP();
			$mail->Host       = 'smtp.gmail.com';
			$mail->SMTPAuth   = true;
			$mail->Username   = 'user@example.com';
			$mail->Password   = 'secret';
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
			$mail->Port       = 465;
			//Recipients
			$mail->setFrom('from@example.com', 'Mailer');
			$mail->addAddress('joe@example.net', 'Joe User');
			$mail->addAddress('ellen@example.com');
			$mail->addReplyTo('info@example.com', 'Information');
			$mail->addCC('cc@example.com');
			$mail->addBCC('bcc@example.com');

			//Attachments
			// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

			//Content
			$mail->isHTML(true);
			$mail->Subject = 'Here is the subject';
			$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
			$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			$mail->send();
			echo 'Message has been sent';
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}
}
