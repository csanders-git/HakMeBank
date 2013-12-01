<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

	function __construct()
	{
        	parent::__construct();
	}

	function add_audit($description,$success,$previousState, $currentState)
	{ 
		$currentUser = $this->session->userdata('username');
		
		$logged_in = $this->session->userdata('logged_in');
		// Transactions
		$audit = array(
			'ip' => mysql_real_escape_string($_SERVER['REMOTE_ADDR']),
			'time' => mysql_real_escape_string(time()),
			'description' => mysql_real_escape_string($description),
			'logged_in' => mysql_real_escape_string($logged_in),
			'previous_value' => mysql_real_escape_string($previousState),
			'current_value' => mysql_real_escape_string($currentState),
			'user' => mysql_real_escape_string($currentUser),
			'success' => mysql_real_escape_string($success),
			'refer' => mysql_real_escape_string($_SERVER['HTTP_REFERER'])
		);
		$this->db->insert('audit', $audit);
		
		# ip addres SESSION['remote']
		# useragent
		# time 
		# refer page
		# if logged in
	}

	function user_login($login_username, $login_password)
	{
		# It is reccommended to salt with a constant so that rainbow tables can't be used
		# We also salt with dynamic information so that each users password is distinct from every others
		$saltConstant = "connelly";
		$saltDynamic = $login_username;
		$hash = hash("sha512",$saltConstant.$login_password.$saltDynamic);
		$this->db->select('id, username, password, manager');
		$this->db->where('username', $login_username);
		$query = $this->db->get('users',1);
		if ($query->num_rows() > 0){
			$row = $query->row();
			if($row->password === $hash){
				// set the session data and log in
				$this->session->set_userdata(array(
					'logged_in' => FALSE,
					'user_id' => $row->id,
					'username' => $row->username,
					'manager' => $row->manager
				));
				return TRUE;
			}else{
				log_message('ERROR', 'Account: '. $login_username .' attempted to log in with the wrong password!.');

				// User exsists but bad password
				return FALSE;
			}
		}else{
			log_message('ERROR', 'Account: '. $login_username .' does not exist.');
			// User does not exsist
			return FALSE;
		}
	}

	function user_register($fname, $lname, $username,$password,$email)
	{
		// Create the hashed password
		$saltConstant = "connelly";
		$saltDynamic = $username;
		$hash = hash("sha512",$saltConstant.$password.$saltDynamic);

		// See if any of the values are two short or to long
		if( (strlen($username) > 32) or (strlen($username) < 6) ){
			return FALSE;
		}
		
		if( (strlen($password) > 64) or (strlen($password) < 8) ){
			return FALSE;
		}
		// See if any of the values already exsist
		$this->db->select('id');
		$this->db->where('username', $username);
		$this->db->or_where('email', $email);
		$query = $this->db->get('users',1);
		if ($query->num_rows() > 0){
			return FALSE;
		}
		
		// If we have no errors then we can insert our new values
		$data = array(
			'username' => mysql_real_escape_string($username),
			'password' => mysql_real_escape_string($hash),
			'fname' => mysql_real_escape_string($fname),
			'lname' => mysql_real_escape_string($lname),
			'email'	=> mysql_real_escape_string($email)
		);
		$this->db->insert('users',$data);
		return TRUE;
	}
	function setVerify($username)
	{
		// Generate a random string
		$rand = substr(md5(microtime()),rand(0,26),6);
		// Update our verify data in the DB
		$verify = array(
			'verify' => $rand
		);
		$this->db->where('username', $username);
		$this->db->update('users',$verify);
		// Return the random value
		return $rand;
	}
	function checkVerify($verify)
	{
		$username = $this->session->userdata('username');
		$this->db->select('verify');
		$this->db->where('username', $username);
		$query = $this->db->get('users',1);
		if ($query->num_rows() != 1){
			return FALSE;
		}else{
			$row = $query->row();
			$got = $row->verify;
		}
		if($got === $verify){
			$this->session->set_userdata('logged_in',TRUE);
			return TRUE;
		}else{
			return FALSE;
		}
		return FALSE;
	}
	function getEmail($username)
	{	
		$this->db->select('email');
		$this->db->where('username', $username);
		$query = $this->db->get('users',1);
		if ($query->num_rows() > 0){
			$row = $query->row();
			return $row->email;
		}else{
			return FALSE;
		}
		return FALSE;	
	}

	function getUsers()
	{
		$users = array();
		$query = $this->db->get('users');
		if ($query->num_rows() > 0){
			foreach($query->result() as $row)
			{
				if($row->id != $this->session->userdata('user_id')){
					$name = $row->fname . ' '. $row->lname;
					$users[$row->id] = $name;
				}
			}
		}
		return $users;
	}

	function isManager($username)
	{
		$this->db->select('manager');
		$this->db->where('username',$username);
		$query = $this->db->get('users',1);
		
	}

	function updateBalance($recipient,$amount){
		
		// Update User balance
		$newAmount = ($this->getBalance()-$amount);
		$updatedBalance = array(
			'balance' => $newAmount
		);

		$id = $this->session->userdata('user_id');
		$this->db->where('id', $id);
		$this->db->update('users',$updatedBalance);
		
		// get recipient balance
		$this->db->select('balance');
		$this->db->where('id', $recipient);
		$query = $this->db->get('users',1);
		if ($query->num_rows() == 1){
			$row = $query->row();
			$recipientBalance = $row->balance;
		}else{
			return FALSE;
		}
		// update recipient balance
		$newRecipientAmount = $recipientBalance + $amount;
		$updatedBalance = array (
			'balance' => $newRecipientAmount
		);
		$this->db->where('id',$recipient);
		$this->db->update('users',$updatedBalance);
		$this->_addTransaction($id,$newAmount,$recipient,$newRecipientAmount,$amount);		
		return TRUE;
	}
	
	function idToName($id){
		$this->db->where('id',$id);
                $query = $this->db->get('users');
                foreach($query->result() as $row){
                        return $row->fname . ' ' . $row->lname;
                }
	}
	function _addTransaction($id,$newAmount,$recipient,$newRecipientAmount,$amount)
	{
		date_default_timezone_set('America/New_York');
		// Transactions

		$description = "Transferred money to " . $this->idToName($recipient);
		$transaction = array(
			'user_id' => mysql_real_escape_string($id),
			'date' => mysql_real_escape_string(date('Y-M-d')),
			'time' => mysql_real_escape_string(time()),
			'description' => $description,
			'withdrawl' => mysql_real_escape_string($amount),
			'balance' => mysql_real_escape_string($newAmount)
		);
		$this->db->insert('transactions', $transaction);
		// Reverse Transaction
	//	$description = $this->idToName($id) . " transferred money to you";
		$description = $this->idToName($id) . " deposited money";
		$r_transaction = array(
			'user_id' => mysql_real_escape_string($recipient),
			'date' => mysql_real_escape_string(date('Y-M-d')),
			'time' => mysql_real_escape_string(time()),
			'description' => $description,
			'deposit' => mysql_real_escape_string($amount),
			'balance' => mysql_real_escape_string($newRecipientAmount)
		);
		$this->db->insert('transactions', $r_transaction);
	}

	function getTransactions()
	{

		$return_data = array(
			array('Date','Description','Amount','Balance')
		);
		date_default_timezone_set('America/New_York');
		$id = $this->session->userdata('user_id');
		$this->db->where('user_id', $id);
		$this->db->order_by('time','desc');
		$query = $this->db->get('transactions');
		if ($query->num_rows() > 0){
			foreach($query->result() as $row)
			{
				$foundArray = array(date('D jS M Y h:i:s A',$row->time),$row->description,($row->withdrawl != NULL ? $row->withdrawl:$row->deposit), $row->balance);
				array_push($return_data,$foundArray);
			}
		}else{
			# If there is no data display as much
			$temp = array('','',"No data to display",'','');
		}
		return $return_data;
	}

	
	function getBalance()
	{	
		$username = $this->session->userdata('username');
		$this->db->select('balance');
		$this->db->from('users');
		$this->db->where('username', $username);
		$query = $this->db->get();
		if($query->num_rows() == 1){
			$row = $query->row();
			$balance = $row->balance;
		}
		return $balance;
	}
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
