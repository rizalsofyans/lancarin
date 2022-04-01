<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class caption extends MX_Controller {
	public $table;
	public $columns;
	public $module_name;
	public $module_icon;

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');

		//Config Module
		$this->table       = CAPTION;
		$this->module_name = lang("caption");
		$this->module_icon = "ft-command";
		

		$this->columns = array(
			"content"              => lang("content"),
			"status"               => lang("status"),
			"signature" => 'signature'
		);
	}

	public function index(){
		$page        = (int)get("p");
		$limit       = 20;
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
			"module_icon" => $this->module_icon,
			"accounts"=> $this->getAccounts(),
			"template_message"=> $this->getTemplateMessage()
		);

		$this->template->build('index', $data);
	}

	private function getAccounts(){
		return $this->db->get_where('instagram_accounts', ['status'=>1, 'uid'=>session('uid')])->result();
	}

	public function popup_documentation(){
		$this->load->view("popup_documentation");
	}
	
	public function popup_findreplace(){
		$this->load->view("popup_findreplace");
	}

	private function getTemplateMessage(){
		return $this->db->get_where('general_template_message', ['uid'=>session('uid')])->result();
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

	public function get_signature(){
		$q = $this->db->get_where(CAPTION, ['uid'=>session('uid'), 'signature'=>1]);
		if($q->num_rows()>0){
			ms([
				"status"  => "success",
				"data" => $q->row()->content
			]);
		}else{
			ms([
				"status"  => "error",
				"message" => 'Anda belum memiliki signature, mohon mengeset nya pada halaman caption terlebih dahulu.'
			]);
		}
	}

	public function set_signature(){
		$id = post('id');
		$q = $this->db->get_where(CAPTION, ['uid'=>session('uid'), 'ids'=>$id]);
		if($q->num_rows()>0){
			$this->db->update(CAPTION, ['signature'=>0, 'changed'=>NOW], ['uid'=>session('uid')]);
			$this->db->update(CAPTION, ['signature'=>1, 'changed'=>NOW], ['uid'=>session('uid'), 'ids'=>$id]);
			ms([
				"status"  => "success",
				"message" => lang("successfully")
			]);
		}else{
			ms([
				"status"  => "error",
				"message" => 'id not found'
			]);
		}
	}

	public function add_template_message(){
		$format = post('format');
		$replace = post('replace');
		if(empty($format) || empty($replace)){
			ms([
				"status"  => "error",
				"message" => 'Format / replace cant empty'
			]);
		}
		$q = $this->db->get_where('general_template_message', ['uid'=>session('uid'), 'text'=>$format]);
		if($q->num_rows()>0){
			$this->db->update('general_template_message', ['text'=>$format, 'replace_text'=>$replace], ['uid'=>session('uid'), 'text'=>$format]);
		}else{
			$this->db->insert('general_template_message', ['text'=>$format, 'replace_text'=>$replace, 'uid'=>session('uid')]);
		}
		ms([
			"status"  => "success",
			"message" => lang("successfully")
		]);
	}

	public function delete_template_message(){
		$id = post('id');
		$this->db->delete('general_template_message', ['uid'=>session('uid'), 'id'=>$id]);
		ms([
			"status"  => "success",
			"message" => lang("successfully")
		]);
		
	}

	public function ajax_update(){
		$ids         = post("ids");
		$caption     = post("caption");

		if($caption == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("caption_is_required")
			));
		}

		$data = array(
			'uid'       => session("uid"),
			'content'   => $caption,
			'changed'   => NOW
		);

		$item = $this->model->get("id", $this->table, "ids = '{$ids}'");
		if(empty($item)){
			$data['ids'] = ids();
			$data['status'] = 1;
			$data['created'] = NOW;
			$this->db->insert($this->table, $data);
		}else{
			$this->db->update($this->table, $data, array("id" => $item->id));
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
		$this->model->delete($this->table, post("id"), false);
	}

	public function popup(){
		$this->load->view('popup_caption');
	}

	public function save_caption(){
		$caption = post("caption");

		$item = $this->model->get("*", $this->table, "content = '{$caption}' AND uid = '".session("uid")."'");
		if(empty($item)){
			$data = array(
				"ids" => ids(),
				"uid" => session("uid"),
				"content" => $caption,
				"status" => 1,
				"changed" => NOW,
				"created" => NOW
			);

			$this->db->insert($this->table, $data);

			ms(array(
			"status"  => "success",
			"message" => lang("add_caption_successfully")
		));
		}else{
			ms(array(
				"status"  => "error",
				"message" => lang("this_caption_already_exists")
			));
		}
	}

	public function get_caption($page = 0){
		$limit = 10;
		$start = $page * $limit;
		$next_start = ($page+1) * $limit;
		$captions = $this->model->fetch("*", $this->table, "status = 1 AND uid = '".session("uid")."'", "id", "desc", $start, $limit);
		$next_captions = $this->model->fetch("*", $this->table, "status = 1 AND uid = '".session("uid")."'", "id", "desc", $next_start, $limit);
		$data = array(
			"limit" => $limit,
			"page" => $page,
			"captions" => $captions,
			"next_captions" => $next_captions
		);

		$this->load->view('get_caption', $data);
	}
}