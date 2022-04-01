<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class proxies extends MX_Controller {
	public $table;
	public $columns;
	public $module_name;
	public $module_icon;

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');

		//Config Module
		$this->table       = PROXIES;
		$this->module_name = lang("proxies");
		$this->module_icon = "ft-shield";
		

		$this->columns = array(
			"address"              => lang("address"),
			"user"                 => 'Users',
			"location"             => lang("location"),
			"status"               => lang("status")
		);
	}

	public function index(){
		$page        = (int)get("p");
		$limit       = 50;
		$result      = $this->model->getList($this->table, $this->columns, $limit, $page);
		$total       = $this->model->getList($this->table, $this->columns, -1, -1);
		$total_final = $total;

		$query = array();
		$query_string = "";
		if(get("c")) $query["c"] = get("c");
		if(get("t")) $query["t"] = get("t");
		if(get("k")) $query["k"] = get("k");

		if(!empty($query)){
			$query_string = "?".http_build_query($query);
		}

		$configs = array(
			"base_url"   => cn(get_class($this).$query_string), 
			"total_rows" => $total_final, 
			"per_page"   => $limit
		);

		$this->pagination->initialize($configs);

		$data = array(
			"columns" => $this->columns,
			"result"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon
		);

		$this->template->build('index', $data);
	}

	public function update(){
		$data = array(
			"result"      => $this->model->get("*", $this->table, "ids = '".segment(3)."'"),       
			"module"      => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon
		);
		$this->template->build('update', $data);
	}

	public function ajax_update(){
		$ids         = post("ids");
		$address     = post("address");
		$location   = post("location");

		if($address == ""){
			ms(array(
				"status"  => "error",
				"message" => "Address is required"
			));
		}

		$data = array(
			'address'   => $address,
			'location'  => $location,
			'changed'   => NOW
		);

		$proxies = $this->model->get("id", $this->table, "ids = '{$ids}'");
		if(empty($proxies)){
			$data['ids'] = ids();
			$data['status'] = 1;
			$data['created'] = NOW;
			$this->db->insert($this->table, $data);
		}else{
			$this->db->update('instagram_accounts', ['proxy'=>$address], ['ids'=> $ids]);
			$this->db->update($this->table, $data, array("id" => $proxies->id));
			$this->autoAssignProxy();
		}
		
		ms(array(
			"status"  => "success",
			"message" => lang("successfully")
		));
	}

	public function export(){
		export_csv($this->table);
	}

	public function ajax_update_status(){
		$this->model->update_status($this->table, post("id"), false);
	}
	
	public function ajax_delete_item(){
		
		$listid =post("id");
		if(!is_array($listid)){
			$listid = [$listid];
		}
		if(!empty($listid)){
			foreach($listid as $id){
				$q = $this->db->get_where($this->table, ['ids'=>$id]);
				if($q->num_rows()>0){
					$this->db->update('instagram_accounts', ['proxy'=>''], ['proxy'=> $q->row()->address]);
				}
				$this->db->delete($this->table, ['ids'=>$id]);
			}
			
			$this->autoAssignProxy();
			$result['status']='success';
			$result['tag']='tag-success';
			$result['message']='Delete data successfully';
		}else{
			$result=[
			  'status'=> 'error',
			  'tag'=> 'tag-danger',
			  'message'=> 'Id tidak boleh kosong'
			];
			
		}
		
	
		ms($result);
	}
	
	  public function getAvailableProxy(){
    $max = get_option('proxy_max_users', '');
    $q= $this->db->limit(1)->get_where('general_proxies', ['user <', $max]);
    if($q->num_rows() > 0){
      return $q->row()->address;
    }else{
      $q= $this->db->limit(1)->order_by('id','desc')->get_where('general_proxies');
      return $q->num_rows() ==0 ? '' : $q->row()->address;
    }
  }
  
  public function getProxyCount(){
    $proxy = post('proxy');
    $result['ok']=1;
    $result['data']='';
    $q = $this->db->select('a.username, b.fullname')->from('instagram_accounts a')->join('general_users b','a.uid=b.id')->where('a.proxy', $proxy)->order_by('b.id')->get();
    $no =1;
    if($q->num_rows()>0){
      foreach($q->result() as $row){
        $result['data'] .= '<tr>';
        $result['data'] .= '<td>'.$no.'</td>';
        $result['data'] .= '<td>'.$row->fullname.'</td>';
        $result['data'] .= '<td>'.$row->username.'</td>';
        $result['data'] .= '</tr>';
        $no++;
      }
      
    }else{
      $result['data'] .= '<tr><td colspan="3"></td></tr>';
    }
    ms($result);
  }

    public function recalculateProxy(){
    //hitung proxy yang di pakai
    $usedProxy=[];
    $q = $this->db->select('proxy, count(*) AS jml')->from('instagram_accounts') ->where('proxy !=','')->group_by('proxy')->get();
    $q = $this->db->query("SELECT proxy, count(*) AS jml FROM instagram_accounts WHERE proxy !='' GROUP BY proxy");
    if($q->num_rows() >0){
      foreach($q->result() as $row){
        $cek = $this->db->get_where('general_proxies', ['address'=> $row->proxy]);
        if($cek->num_rows() >0){
          $usedProxy[] = $row->proxy;
          $this->db->update('general_proxies', ['user'=>$row->jml], ['address'=> $row->proxy]); 
        }
      }
    }else{
      $this->db->update('general_proxies', ['user'=> 0]); 
    }
    
    //hitung ulang proxy yg tdk terpakai
    if(!empty($usedProxy)){
      $this->db->set(['user'=>0])->where_not_in('address', $usedProxy)->update('general_proxies');
    }
    
  }
  
  public function autoAssignProxy(){
    //hapus proxy utk akun yg tidak aktif
    $q = $this->db->get_where('instagram_accounts', ['status'=>0, 'proxy !='=>'']);
    if($q->num_rows() >0){
      foreach($q->result() as $row){
        $this->db->update('instagram_accounts', ['proxy'=>''], ['id'=>$row->id]);
      }
    }
    $this->recalculateProxy();
    
    //cari available proxy
    $proxies = [];
    $max = get_option('proxy_max_users', 5);
    $q = $this->db->select('address, user AS jml')->from('general_proxies')->where('user <', $max)->group_by('id')->order_by('id','asc')->order_by('jml', 'desc')->get();
    if($q->num_rows()>0){
      foreach($q->result() as $row){
        $proxies[$row->address] = $row->jml;
      }
    }else{
      $this->sendAlertProxy('No proxy available : max '.$max.' user per proxy');
    }
    
    //cari user yang tidak pnya proxy
    //bila ada auto assign dgn yg tersedia
    if(!empty($proxies)){
      $q = $this->db->from('instagram_accounts')->where("proxy", '')->where('status',1)->get();
      if($q->num_rows() >0){
        foreach($q->result() as $row){
          $jmlProxy = count($proxies);
          $firstProxyJml = reset($proxies);
          $firstProxy = key($proxies);

          $this->db->update('instagram_accounts', ['proxy'=>$firstProxy], ['id'=>$row->id]); 
          $this->counterUpProxy($firstProxy);
            
          if($jmlProxy>1){
            $proxies[$firstProxy]++;
            if($proxies[$firstProxy] >= $max){
              array_shift($proxies);
            }
          }
        }
      }
    }
    
    //cari proxy yang melebihi batas
    $q = $this->db->select('address, user as jml')->from('general_proxies')->where('user >',$max)->get();
    if($q->num_rows() > 0 && count($proxies) > 1){
      foreach($q->result() as $row){
       
        $selisih = $row->jml -$max;
        
        $q1 = $this->db->limit($selisih)->order_by('id','desc')->get_where('instagram_accounts', ['proxy'=>$row->address]);
        if($q1->num_rows() >0){
          foreach($q1->result() as $row1){
            $jmlProxy = count($proxies);
            $firstProxyJml = reset($proxies);
            $firstProxy = key($proxies);
            $this->counterDownProxy($row->address);
            $this->db->update('instagram_accounts',['proxy'=>$firstProxy], ['id'=>$row1->id]);
            $this->counterUpProxy($firstProxy);
            if($jmlProxy>1){
              $proxies[$firstProxy]++;
              if($proxies[$firstProxy] >= $max){
                array_shift($proxies);
              }
            }
          }
        }
      }
    }
    $this->recalculateProxy();
  }
  
  
  private function counterDownProxy($proxy){
    $this->db->query("UPDATE general_proxies SET user=user-1 WHERE address='$proxy'");
  }
  
  private function counterUpProxy($proxy){
    $this->db->query("UPDATE general_proxies SET user=user+1 WHERE address='$proxy'");
  }
  
  private function sendAlertProxy($msg){
    $q= $this->db->get_where('general_proxies_report', ['log_send'=>date('Y-m-d')]);
    if($q->num_rows() ==0)
    $this->model->send_email('Alert: Less of proxy', $msg, 1);
  }

}