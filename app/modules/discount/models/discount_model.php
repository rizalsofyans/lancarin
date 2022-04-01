<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class discount_model extends MY_Model {
	public function __construct(){
		parent::__construct();
	}

	function getTableDiscount($columns, $limit=-1, $page=-1){
		$c = (int)get_secure('c'); //Column key
		$t = get_secure('t'); //Sort type
		$k = get_secure('k'); //Search keywork

		if($limit == -1){
			$this->db->select('count(a.id) as sum');
		}else{
			$this->db->select("a.id, a.nama, a.code, a.percent, a.status, a.tgl_start, a.tgl_end, a.quota, a.log_modified, a.log_created");
		}
		
		$this->db->from("general_discount as a");
		
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
