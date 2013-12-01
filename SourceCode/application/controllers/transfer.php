<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transfer extends CI_Controller {

	public function index()
	{
		$data['users'] = $this->user_model->getUsers();
		$this->load->view('transfer_view',$data);
	}
	
	function sendFunds()
	{

		$max = $this->user_model->getBalance() + 1;
		$this->form_validation->set_rules('recipent', 'Recipient', 'trim|required|xss_clean');
		$this->form_validation->set_rules('amount', 'Transfer Amount', 'trim|required|integer|less_than['.$max.']|greater_than[0]|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
		{
			$data['users'] = $this->user_model->getUsers();
			$this->load->view('transfer_view',$data);
		} else {
			// Update Balance
			$recipient = $this->input->post('recipent');
			$amount = $this->input->post('amount');
			$result = $this->user_model->updateBalance($recipient,$amount);	
			if($result == TRUE){

				$this->user_model->add_audit("Transfer funds succesfully",1,$recipient,$amount);
				$this->index();
			}else{

				$this->user_model->add_audit("Fund value failed validation",0,$recipient,$amount);
				$data['users'] = $this->user_model->getUsers();
				$data['success'] = FALSE;
				$this->load->view('transfer_view',$data);
			}
		}		
	}
	
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */
