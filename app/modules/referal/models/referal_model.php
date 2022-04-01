<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class referal_model extends MY_Model {
	public function __construct(){
		parent::__construct();
	}

	function getTableUserReferal($columns, $limit=-1, $page=-1){
		
		$c = (int)get_secure('c'); //Column key
		$t = get_secure('t'); //Sort type
		$k = get_secure('k'); //Search keywork

		if($limit == -1){
			$this->db->select('count(a.id) as sum');
		}else{
			$this->db->select("a.fullname, a.whatsapp, a.email, b.name, b.price_monthly, a.expiration_date");
		}
		
		$this->db->from(USERS." as a");
		$this->db->join(PACKAGES." as b", "a.package = b.id");
		$this->db->where('a.ref_uid', session('uid'));
		
		if($limit != -1) {
			$this->db->limit($limit, $page);
		}

		if($k){
			$i = 0;
			$this->db->group_start();
			foreach ($columns as $column_name => $column_title) {
				switch ($column_name) {
					case 'name':
						$column_name = "b.name";
						break;

					case 'price_monthly':
						$column_name = "b.price_monthly";
						break;
					
					default:
						$column_name = "a.".$column_name;
						break;
				}
				if($i == 0){
					$this->db->like($column_name, $k);
				}else{
					$this->db->or_like($column_name, $k);
				}
				$i++;
			}
			$this->db->group_end();
		}

		if($c){
			$i = 0;
			$s = ($t && ($t == "asc" || $t == "desc"))?$t:"desc";
			foreach ($columns as $column_name => $column_title) {
				if($i == $c){
					switch ($column_name) {
						case 'name':
							$column_name = "b.name";
							break;

						case 'price_monthly':
							$column_name = "b.price_monthly";
							break;
						
						default:
							$column_name = "a.".$column_name;
							break;
					}
					$this->db->order_by($column_name , $s);
				}
				$i++;
			}
		}else{
			$this->db->order_by('a.expiration_date', 'asc');
		}
				
		$query = $this->db->get();
		
		if($query->result()){
			if($limit == -1){
				return $query->row()->sum;
			}else{
				$result =  $query->result();
				return $result;
			}

		}else{
			return false;
		}
	}

	function getTableReferalHistory($columns, $limit=-1, $page=-1){
		
		$c = (int)get_secure('c'); //Column key
		$t = get_secure('t'); //Sort type
		$k = get_secure('k'); //Search keywork

		if($limit == -1){
			$this->db->select('count(a.id) as sum');
		}else{
			$this->db->select("a.fullname, b.name, c.amount as nilai, c.transaction_id, c.created");
		}
		
		$this->db->from(PAYMENT_HISTORY." as c");
		$this->db->join(USERS." as a", "a.id = c.uid");
		$this->db->join(PACKAGES." as b", "a.package = b.id");
		$this->db->where('c.ref_uid', session('uid'));
		$this->db->where('c.ref_claim', 1);
		
		if($limit != -1) {
			$this->db->limit($limit, $page);
		}

		if($k){
			$i = 0;
			$this->db->group_start();
			foreach ($columns as $column_name => $column_title) {
				switch ($column_name) {
					case 'name':
						$column_name = "b.name";
						break;
					case 'nilai':
						$column_name = "c.amount";
						break;
					case 'fullname':
						$column_name = "a.fullname";
						break;
					
					default:
						$column_name = "c.".$column_name;
						break;
				}
				if($i == 0){
					$this->db->like($column_name, $k);
				}else{
					$this->db->or_like($column_name, $k);
				}
				$i++;
			}
			$this->db->group_end();
		}

		if($c){
			$i = 0;
			$s = ($t && ($t == "asc" || $t == "desc"))?$t:"desc";
			foreach ($columns as $column_name => $column_title) {
				if($i == $c){
					switch ($column_name) {
						case 'name':
							$column_name = "b.name";
							break;
						case 'nilai':
							$column_name = "c.amount";
							break;
						case 'fullname':
							$column_name = "a.fullname";
							break;
						
						default:
							$column_name = "c.".$column_name;
							break;
					}
					$this->db->order_by($column_name , $s);
				}
				$i++;
			}
		}else{
			$this->db->order_by('c.created', 'desc');
		}

		$query = $this->db->get();

		if($query->result()){
			if($limit == -1){
				return $query->row()->sum;
			}else{
				$result =  $query->result();
				return $result;
			}

		}else{
			return false;
		}
	}
	
	function getTableUserWithdraw($columns, $limit=-1, $page=-1){
		
		$c = (int)get_secure('c'); //Column key
		$t = get_secure('t'); //Sort type
		$k = get_secure('k'); //Search keywork

		if($limit == -1){
			$this->db->select('count(a.id) as sum');
		}else{
			$this->db->select("a.id, a.code, a.amount, CASE a.status 
WHEN 1 THEN 'Paid'
ELSE 'Unpaid'
END as stat, a.log_created");
		}
		
		$this->db->from("general_withdraw_history as a");
		$this->db->where('a.uid', session('uid'));
		
		if($limit != -1) {
			$this->db->limit($limit, $page);
		}

		if($k){
			$i = 0;
			$this->db->group_start();
			foreach ($columns as $column_name => $column_title) {
				switch ($column_name) {
					case 'balance':
						$column_name = "b.balance";
						break;
					case 'stat':
						$column_name = "a.status";
						break;
					default:
						$column_name = "a.".$column_name;
						break;
				}
				
				if($i == 0){
					$this->db->like($column_name, $k);
				}else{
					$this->db->or_like($column_name, $k);
				}
				$i++;
			}
			$this->db->group_end();
		}

		if($c){
			$i = 0;
			$s = ($t && ($t == "asc" || $t == "desc"))?$t:"desc";
			foreach ($columns as $column_name => $column_title) {
				if($i == $c){
					switch ($column_name) {
						case 'balance':
							$column_name = "b.balance";
							break;
						case 'stat':
							$column_name = "a.status";
							break;
						default:
							$column_name = "a.".$column_name;
							break;
					}
					$this->db->order_by($column_name , $s);
				}
				$i++;
			}
		}else{
			$this->db->order_by('a.id', 'desc');
		}
				
		$query = $this->db->get();
		
		if($query->result()){
			if($limit == -1){
				return $query->row()->sum;
			}else{
				$result =  $query->result();
				return $result;
			}

		}else{
			return false;
		}
	}
	
	function getTableWithdrawClaim($columns, $limit=-1, $page=-1){
		if(session('uid')!=1) redirect();
		$c = (int)get_secure('c'); //Column key
		$t = get_secure('t'); //Sort type
		$k = get_secure('k'); //Search keywork

		if($limit == -1){
			$this->db->select('count(a.id) as sum');
		}else{
			$this->db->select("a.id, a.code, a.amount as nilai, b.balance, a.status, a.log_created, a.log_modified");
		}
		
		$this->db->from("general_withdraw_history as a");
		$this->db->join("general_referal as b",'a.uid=b.uid','left');
		//$this->db->where('a.uid', session('uid'));
		
		if($limit != -1) {
			$this->db->limit($limit, $page);
		}

		if($k){
			$i = 0;
			$this->db->group_start();
			foreach ($columns as $column_name => $column_title) {
				switch ($column_name) {
					case 'balance':
						$column_name = "b.balance";
						break;
					case 'nilai':
						$column_name = "a.amount";
						break;
					default:
						$column_name = "a.".$column_name;
						break;
				}
				if($i == 0){
					$this->db->like($column_name, $k);
				}else{
					$this->db->or_like($column_name, $k);
				}
				$i++;
			}
			$this->db->group_end();
		}

		if($c){
			$i = 0;
			$s = ($t && ($t == "asc" || $t == "desc"))?$t:"desc";
			foreach ($columns as $column_name => $column_title) {
				if($i == $c){
					switch ($column_name) {
						case 'balance':
							$column_name = "b.balance";
							break;
						case 'nilai':
							$column_name = "a.amount";
							break;
						default:
							$column_name = "a.".$column_name;
							break;
					}
					$this->db->order_by($column_name , $s);
				}
				$i++;
			}
		}else{
			$this->db->order_by('a.id', 'desc');
		}
				
		$query = $this->db->get();
		
		if($query->result()){
			if($limit == -1){
				return $query->row()->sum;
			}else{
				$result =  $query->result();
				return $result;
			}

		}else{
			return false;
		}
	}

}
