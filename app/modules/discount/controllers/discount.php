<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class discount extends MX_Controller {

	
	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
		$this->load->helper('string');
	}
	
	public function ajax_update_status(){
		if(session('uid')!=1) redirect();
		$result=[
      'status'=> 'error',
      'tag'=> 'tag-danger',
      'text'=> 'Error',
      'message'=> 'Error'
    ];
    $id = post('id');
	$q = $this->db->get_where('general_discount', ['id'=>$id]);
    if($q->num_rows()>0){
      if($q->row()->status==1){
        $status=0;
        $this->db->update('general_discount', ['status'=>$status, 'log_modified'=>NOW],['id'=>$id]);
        $result['text']='Disable';
      }else{
        $status=1;
        $this->db->update('general_discount', ['status'=>$status, 'log_modified'=>NOW],['id'=>$id]);
        $result['text']='Enable';
        
      }
	  $result['status']='success';
      $result['tag']='tag-success';
      $result['message']='Update status successfully';
    }else{
		$result['message']='Id not found';
    }
		ms($result);
	}
	
	public function ajax_delete_item(){
		if(session('uid')!=1) redirect();
		$listid = session('uid');
		if(!is_array($listid)){
			$listid=[$listid];
		}
		foreach($listid as $id){
			$this->db->delete('general_discount', ['id'=>$id]);
		}
		
		$result['status']='success';
		$result['tag']='tag-success';
		//$result['text']='Enable';
		$result['message']='Delete data successfully';
	
		ms($result);
	}
	
	
	public function index(){
		if(session('uid')!=1) redirect();
		$module_name = 'Discount Management';
		$module_icon = "fa fa-percent";
		$columns = array(
			"id" => 'id',
			"nama" => 'Nama',
			"code" => 'Code',
			"percent" => 'Percent',
			"quota" => 'Quota',
			"status" => 'Status',
			"tgl_start" => 'Start',
			"tgl_end" => 'End',
			"log_modified" => 'Changed',
			"log_created" => 'Created',
		);
		
		$page        = (int)get("p");
		$limit       = 50;
		$result      = $this->model->getTableDiscount($columns, $limit, $page);
		$total       = $this->model->getTableDiscount($columns, -1, -1);
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
			"columns" => $columns,
			"result"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this),
			"module_name" => $module_name,
			"module_icon" => $module_icon
		);

		$this->template->build('index', $data);
	}
	
	public function get_discount_history(){
		if(session('uid')!=1) redirect();
		$id= post('id');
		$result['ok']=1;
		$q=$this->db->select('a.*, b.fullname')->from('general_discount_history a')->join(USERS.' b','a.uid=b.id','left')->where('a.id_discount',$id)->get();
		$table = '<table class="table table-hover table-striped table-bordered">
		<thead><tr>
		<th>No</th>
		<th>UserId</th>
		<th>Nama</th>
		<th>Invoice</th>
		<th>Tanggal</th>
		</tr></thead><tbody>
		';
		$no=1;
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$table .= '<tr>';
				$table .= '<td>'.$no.'</td>';
				$table .= '<td>'.$row->uid.'</td>';
				$table .= '<td>'.$row->fullname.'</td>';
				$table .= '<td>'.$row->invoice.'</td>';
				$table .= '<td>'.$row->created.'</td>';
				$table .= '</tr>';
				$no++;
			}
		}else{
			$table .='<tr><td colspan="5">Belum ada yang mengklaim</td></tr>';
		}
		$table .='</tbody></table>';
		$result['table']=$table;
		ms($result);
	}
	
	public function update(){
		if(session('uid')!=1) redirect();
		$data = array(
			"result"      => $this->db->select("a.*")->from('general_discount as a')->where("a.id = '".segment(3)."'")->get()->row(),
			"module"      => get_class($this),
			"module_name" => 'Discount',
			"module_icon" => 'fa fa-percent'
		);
		
		$this->template->build('update', $data);
	}
	
	public function ajax_update(){
		if(session('uid')!=1) redirect();
		$id      = post("ids");
		$nama = post("nama");
		$code    = trim(post("code"));
		$percent = (int)post("percent");
		$status = intval(post("status"));
		$tgl_start = post("tgl_start");
		$tgl_end = post("tgl_end");
		$quota  = (int)post("quota");

		
		
		if($nama == ""){
			ms(array(
				"status"  => "error",
				"message" => 'Please enter name'
			));
		}
		
		
		if($quota == ""){
			ms(array(
				"status"  => "error",
				"message" => 'Please enter quota'
			));
		}
		
		if($percent == ""){
			ms(array(
				"status"  => "error",
				"message" => 'Please enter discount percentage'
			));
		}
		
		
		if($tgl_start == "" || $tgl_end==""){
			ms(array(
				"status"  => "error",
				"message" => 'Please enter date'
			));
		}
		
		if(strtotime($tgl_start) > strtotime($tgl_end)){
			ms(array(
				"status"  => "error",
				"message" => 'Start date must be same or greater than end date'
			));
		}

		if($code == ""){
			ms(array(
				"status"  => "error",
				"message" => 'Please enter uri'
			));
		}
		
		$q=$this->db->get_where('general_discount', ['code'=>$code]);
		if($q->num_rows()>0 && $q->row()->id != $id){
			ms(array(
				"status"  => "error",
				"message" => 'Kode diskon tidak boleh sama'
			));
		}
		
		
		$data = array(
			"id" => $id,
			"nama" => $nama,
			"code" => $code,
			"percent" => $percent,
			"status" => $status,
			"tgl_start" => date("Y-m-d", strtotime($tgl_start)),
			"tgl_end" => date("Y-m-d", strtotime($tgl_end)),
			"quota" => $quota,
			"log_modified" => NOW,
		);
		
		$q = $this->db->get_where('general_discount ', ['id'=>$id]);
		if($q->num_rows()==0){
			$this->db->insert('general_discount ', $data);
		}else{
			$this->db->update('general_discount ', $data, ['id'=>$id]);
		}
		ms(array(
			"status"  => "success",
			"message" => lang("successfully")
		));
	}	

}