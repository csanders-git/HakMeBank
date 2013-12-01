<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manager extends CI_Controller {

	public function index()
	{
		if ($this->session->userdata('logged_in'))
		{
			$tmpl = array ('table_open' => '<table class="table table-striped">');
			$this->table->set_template($tmpl);

			# Get the current 'logged' in users transactions			
			$tabledata = $this->getTransactions();
			$mytable = $this->table->generate($tabledata);	
			$data['table'] = $mytable;
			$data['manager'] = $this->session->userdata('manager');
#			$data['userID'] = $this->session->userdata('user_id');
#			# Load our base account transactions page

			$this->load->view('manager',$data);
		} else {
			redirect('/');
		}
	}
	public function getTransactions(){
		$return_data = array(
			array('User','Date','Description','Amount','Remaining Balance')
		);
		date_default_timezone_set('America/New_York');
		$this->db->order_by('time','desc');
		$query = $this->db->get('transactions');
#		$id = $this->session->userdata('user_id');
#		$this->db->where('user_id', $id);
#		$this->db->order_by('time','desc');
#		$query = $this->db->get('transactions');
		if ($query->num_rows() > 0){
			foreach($query->result() as $row)
			{
				#$foundArray = array($row->date,$row->description,$row->withdrawl,$row->deposit, $row->balance);
				$foundArray = array($this->getUsername($row->user_id),date('D jS M Y h:i:s A',$row->time),$row->description,($row->withdrawl != NULL ? $row->withdrawl:$row->deposit),$row->balance);
				array_push($return_data,$foundArray);
			}
		}else{
			# If there is no data display as much
			$temp = array('','',"No data to display",'','');
		}
		return $return_data;
	
	}
	public function getUsername($id){
		$this->db->where('id',$id);
		$query = $this->db->get('users');
		foreach($query->result() as $row){
			return $row->fname . ' ' . $row->lname;
		}
	}
}

/* End of file account.php */
/* Location: ./application/controllers/account.php */
