<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class manual_activity_model extends MY_Model {

	public function __construct(){
		parent::__construct();
	}
	
	function getTableManualActivity($columns, $limit=-1, $page=-1){
		
		$c = (int)get_secure('c'); //Column key
		$t = get_secure('t'); //Sort type
		$k = get_secure('k'); //Search keywork

		if($limit == -1){
			$this->db->select('count(a.id) as sum');
		}else{
			$this->db->select("a.id, b.username, a.job_name, a.type, a.target, a.status as stat, a.post_time, a.note, a.log_created");
		}
		$this->db->from("instagram_manual_activity as a");
		//$this->db->join(USERS." as c", "a.uid=c.id");
		$this->db->join(INSTAGRAM_ACCOUNTS." as b", "a.account_id=b.id");
		$this->db->where('a.uid', session('uid'));
		if(session('uid') !=1){
			$this->db->where('a.collab_post_id', NULL);
		}
		
		if($limit != -1) {
			$this->db->limit($limit, $page);
		}

		if($k){
			$i = 0;
			$this->db->group_start();
			foreach ($columns as $column_name => $column_title) {
				switch ($column_name) {
					case 'stat':
						$column_name = "a.status";
						break;
					case 'username':
						$column_name = "b.username";
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
						case 'stat':
							$column_name = "a.status";
							break;
						case 'username':
							$column_name = "b.username";
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
