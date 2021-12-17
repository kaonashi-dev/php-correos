<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Main extends CI_Controller
{
	public $mail; # PHPMailer instance
	
	public $name;
	public $user;
	public $pass;

	public $recipient;
	public $subject;
	public $message;

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
		$this->recipient = $request['email'];
		$this->subject = $request['subject'];
		$this->message = $request['message'];

		$this->loadClient($token);
		$this->loadMailer();
	}

	public function loadClient(string $token)
	{
		$client = $this->mainModel->getClient($token);
		$this->name = $client['name'];
		$this->user = $client['email'];
		$this->pass = $client['pass'];
	}

	public function loadMailer(): void
	{

		try {

			// $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
			$this->mail->isSMTP();
			$this->mail->Host       = 'smtp.gmail.com';
			$this->mail->SMTPAuth   = true;
			$this->mail->Username   = $this->user;
			$this->mail->Password   = $this->pass;
			$this->mail->SMTPSecure = 'tls';
			$this->mail->Port       = 587;
			//Recipients
			$this->mail->setFrom($this->user, $this->name);
			$this->mail->addReplyTo($this->user);

			$this->mail->addAddress($this->recipient);

			//Content
			$this->mail->isHTML(true);
			$this->mail->CharSet = "utf-8";

			$this->mail->Subject = $this->subject;
			$this->mail->Body    = $this->message;

			$this->mail->send();
			echo 'Message has been sent';
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
		}
	}
}
