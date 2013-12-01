<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

	public function index()
	{
		if ($this->session->userdata('logged_in'))
		{
			$tmpl = array ('table_open' => '<table class="table table-striped">');
			$this->table->set_template($tmpl);

			# Get the current 'logged' in users transactions			
			$tabledata = $this->user_model->getTransactions();
			$mytable = $this->table->generate($tabledata);	
			$data['table'] = $mytable;
#			$data['userID'] = $this->session->userdata('user_id');
			$data['manager'] = $this->session->userdata('manager');
#			# Load our base account transactions page

			$this->load->view('base',$data);
		} else {
			redirect('/');
		}
	}
	
	function getBalance()
	{
		$data['funds'] = $this->user_model->getBalance();
		$this->load->view('balance', $data);				
	}
}

/* End of file account.php */
/* Location: ./application/controllers/account.php */
