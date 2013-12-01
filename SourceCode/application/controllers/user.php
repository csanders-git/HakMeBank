<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function index()
	{
		$this->load->view('login');
	}
	
	function login()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|alpha_numeric|required|max_length[64]');
		$this->form_validation->set_rules('password', 'Password', 'required|callback__valid_login');
		
		if ($this->form_validation->run() == TRUE)
		{
			date_default_timezone_set('America/New_York');
			$username = $this->input->post('username');
			$this->user_model->add_audit("User Login Success",1,"",$username);
			$verificationCode = $this->user_model->setVerify($username);
			// Get the email
			$email = $this->user_model->getEmail($username);
			if( $email != FALSE){
				$this->load->library('email');
				$this->email->from('noreply@secureDB.com','Secure DB Federal Credit');
				$this->email->to($email);
				$this->email->subject('Secure DB Verfication Code');
				$this->email->message('Your verification code is: '. $verificationCode);
				$this->email->send();
				// Optionally print debug
				//echo $this->email->print_debugger();

				$this->user_model->add_audit("Verification email sent",1,"",$verificationCode);
				$this->load->view('verification');
			}else{

				$this->user_model->add_audit("Email failed to send",0,"","");
				echo "There was a failure sending your verification email, please contact support at ces1509@rit.edu";
			}
		} else {
			$this->index();
		}		
	}
	
	function logout()
	{
		
		$this->user_model->add_audit("User Logged Out",1,"",$this->session->userdata('username'));
		$this->session->set_userdata('logged_in', FALSE);
		$this->session->sess_destroy();
		redirect('/');
	}

/*
** Callbacks
*
*/
	function _valid_login()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');		
		if ($this->user_model->user_login($username, $password))
		{
			return TRUE;	
		} else {
			
			$this->user_model->add_audit("Login failed",0,"",$username);
			$this->form_validation->set_message('_valid_login', 'Invalid login.');
			return FALSE;		
		}
	}
	
	
	function verification()
	{
		$this->form_validation->set_rules('verification', 'Verify Code', 'trim|alpha_numeric|required|max_length[6]');
		
		if ($this->form_validation->run() == TRUE)
		{
			// Check that we have the right verification code
			$verify = $this->input->post('verification');
			$validVerify = $this->user_model->checkVerify($verify);
			if($validVerify){
				
				$this->user_model->add_audit("Verification code verified",1,"",$this->session->userdata('username'));
				redirect('/account');
			}else{
				
				$this->user_model->add_audit("Verification code incorrect",0,"",$this->session->userdata('username'));
				$data['success'] = FALSE;
				$this->load->view('verification',$data);
			}
		} else {
			$this->user_model->add_audit("Verification code incorrect",0,"",$this->session->userdata('username'));
			$data['success'] = FALSE;
			$this->load->view('verification',$data);
		}
	}
}



/* End of file login.php */
/* Location: ./application/controllers/login.php */
