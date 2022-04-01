<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class users_model extends MY_Model {
	public function __construct(){
		parent::__construct();
	}

	function getList($table, $columns, $limit=-1, $page=-1){
		$c = (int)get_secure('c'); //Column key
		$t = get_secure('t'); //Sort type
		$k = get_secure('k'); //Search keywork

		if($limit == -1){
			$this->db->select('count(*) as sum');
		}else{
			$this->db->select("users.email, users.fullname, users.whatsapp, users.package, users.expiration_date, users.login_type, users.history_ip, users.status, users.changed, users.created, users.ids, package.name as package, referal.percent, referal.balance");
		}
		
		$this->db->from($table." as users");
		$this->db->join(PACKAGES." as package", 'package.id = users.package', 'left');
		$this->db->join("general_referal as referal", 'referal.uid = users.id', 'left');
		
		if($limit != -1) {
			$this->db->limit($limit, $page);
		}
/*
		if($k){
			$i = 0;
			foreach ($columns as $column_name => $column_title) {
				if($i == 0){
					$this->db->like("users.".$column_name, $k);
				}else{
					$this->db->or_like("users.".$column_name, $k);
				}
				$i++;
			}
		}*/
		if($k){
			$i = 0;
			$this->db->group_start();
			foreach ($columns as $column_name => $column_title) {
				switch ($column_name) {
					case 'percent':
						$column_name = "referal.percent";
						break;
					case 'balance':
						$column_name = "referal.balance";
						break;
					default:
						$column_name = "users.".$column_name;
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

/*
		if($c){
			$i = 0;
			$s = ($t && ($t == "asc" || $t == "desc"))?$t:"desc";
			foreach ($columns as $column_name => $column_title) {
				if($i == $c){
					$this->db->order_by("users.".$column_name , $s);
				}
				$i++;
			}
		}else{
			$this->db->order_by('users.created', 'desc');
		}
	*/	
		if($c){
			$i = 0;
			$s = ($t && ($t == "asc" || $t == "desc"))?$t:"desc";
			foreach ($columns as $column_name => $column_title) {
				if($i == $c){
					switch ($column_name) {
						case 'percent':
							$column_name = "referal.percent";
							break;
						case 'balance':
							$column_name = "referal.balance";
							break;
						default:
							$column_name = "users.".$column_name;
							break;
					}
					$this->db->order_by($column_name , $s);
				}
				$i++;
			}
		}else{
			$this->db->order_by('users.created', 'desc');
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

	/*
	function getList($table, $columns, $limit=-1, $page=-1){
		$c = (int)get_secure('c'); //Column key
		$t = get_secure('t'); //Sort type
		$k = get_secure('k'); //Search keywork

		if($limit == -1){
			$this->db->select('count(*) as sum');
		}else{
			$this->db->select(implode(", ", array_keys($columns)).", ids");
		}
		
		$this->db->from($table);

		if($limit != -1) {
			$this->db->limit($limit, $page);
		}

		if($k){
			$i = 0;
			foreach ($columns as $column_name => $column_title) {
				if($i == 0){
					$this->db->like($column_name, $k);
				}else{
					$this->db->or_like($column_name, $k);
				}
				$i++;
			}
		}

		if($c){
			$i = 0;
			$s = ($t && ($t == "asc" || $t == "desc"))?$t:"desc";
			foreach ($columns as $column_name => $column_title) {
				if($i == $c){
					$this->db->order_by($column_name , $s);
				}
				$i++;
			}
		}else{
			$this->db->order_by('created', 'desc');
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
	*/
}
