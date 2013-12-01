<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends CI_Controller {

	public function index()
	{
		$this->load->view('register_view');
	}
	function registerUser()
	{
		// Validations
		$this->form_validation->set_message('is_unique','Please choose a different option, this option has already been registered');	
		
		$this->form_validation->set_rules('fname','First Name','trim|required|min_length[1]|max_length[32]|xss_clean');

		$this->form_validation->set_rules('lname','Last Name','trim|required|min_length[1]|max_length[32]|xss_clean');
		
		$this->form_validation->set_rules('username','Username','trim|required|min_length[6]|max_length[32]|xss_clean|is_unique[users.username]');

		$this->form_validation->set_rules('password','Password','trim|required|min_length[8]|max_length[64]|matches[password_confirm]');

		$this->form_validation->set_rules('password_confirm','Password Confirm','trim|required|min_length[8]|max_length[64]|matches[password]');

		$this->form_validation->set_rules('email', 'Email','trim|required|valid_email|xss_clean|is_unique[users.email]');
		
		if($this->form_validation->run() == FALSE)
		{
			$this->load->view('register_view');
		}else{
			// Attempt to register the user
			$result = $this->_registerUser();
			if($result == TRUE){
				$this->user_model->add_audit("Registered new user",1,"","");
				// Show the user the success message
				$data['success'] = TRUE;			
				$this->load->view('login',$data);
			}else{
				$this->user_model->add_audit("Failed to register user",0,"","");
				// This shouldn't ever happen unless hinky stuff is going on, we check just in case
				$data['failure'] = TRUE;
				$this->load->view('register_view',$data);
			}		
		}
	}
	function _registerUser()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$fname = $this->input->post('fname');
		$lname = $this->input->post('lname');
		$email = $this->input->post('email');
		return $this->user_model->user_register($fname,$lname,$username,$password,$email);
	
	}
}

