<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class custom_link extends MX_Controller {
	public $table;
	public $columns;
	public $module_name;
	public $module_icon;

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		
		$data = array(
			"list_link"  => $this->getListLink(),
			"max_user_link"  => $this->getMaxUserLink(),
		);
		if($this->isPaidUser()){
			$this->template->build('index', $data);
		}else{
			$this->load->view('index_standalone', $data);
		}
	}
	
	private function isPaidUser(){
		$q = $this->db->get_where('general_users', ['id'=>session('uid')]);
		if($q->num_rows()==0) return false;
		elseif($q->row()->package<=1) return false;
		else return true;
	}
	
	private function getMaxUserLink(){
		$uid = session('uid');
		return (int) $this->db->query('SELECT b.number_accounts FROM general_users a JOIN general_packages b ON a.package=b.id WHERE a.id=?', [$uid])->row()->number_accounts;
	}
	
	private function getListLink(){
		$uid = session('uid');
		return $this->db->get_where('general_custom_link', ['uid'=>$uid]);
	}

	private function isSlugAvailable($slug){
		return $this->db->get_where('general_custom_link', ['slug'=>$slug])->num_rows()==0?true:false;
	}
	
	public function ajax_update(){
		$id      = post("id");
		$uid      = session("uid");
		if(empty($uid)) redirect();
		$profile_type = post('profile-type');
		$profile_username = post('profile-username');
		$slug = post('slug');
		$slug = str_ireplace(['admin','administrator'],'',trim($slug));
		if(empty($slug)){
			ms([
				"status"  => "error",
				"message" => 'Custom link tidak boleh kosong.'
			]);
		}
		$max_link = get_option('max_custom_link', 10);
		$url = post('url');
		$description = post('description');
    $fb_pixel = post('fb_pixel');
		$icon = post('icon');
		$title = post('title');
		$body_color = post('body_color');
		$bgcolor = post('bgcolor');
		$textcolor = post('textcolor');
		if(empty($title)){
			ms([
				"status"  => "error",
				"message" => 'You need to defined at least 1 title.'
			]);
		}
		if(count($title)>$max_link){
			ms([
				"status"  => "error",
				"message" => 'Maximum title per link is '.$max_link
			]);
		}
		$mydata =[];
		for($a=0;$a<count($title);$a++){
			if((empty($url[$a]) || filter_var($url[$a], FILTER_VALIDATE_URL)) && !empty($title)){
				$mydata[] = [
					'icon'=>$icon[$a],
					'title'=>$title[$a],
					'url'=>$url[$a],
					'bgcolor'=>$bgcolor[$a],
					'textcolor'=>$textcolor[$a],
				];
			}
		}
		if(empty($mydata)){
			ms([
				"status"  => "error",
				"message" => 'You need to defined at least 1 valid data or Check your url.'
			]);
		}
		
		if(!empty($id)){
			$q = $this->db->get_where('general_custom_link', ['uid'=>$uid, 'id'=>$id]);
			if($q->num_rows()==0){
				ms([
					"status"  => "error",
					"message" => 'Data tidak ditemukan.'
				]);
			}
			if($q->row()->slug != $slug && !$this->isSlugAvailable($slug)){
				ms([
					"status"  => "error",
					"message" => 'Custom link is not available please change it.'
				]);
			}
			$now = NOW;
			$data = [
				'data'=>json_encode($mydata),
				'description'=>trim($description),
        'fb_pixel'=>$fb_pixel,
				'slug'=>$slug,
				'body_color'=>$body_color,
				'profile_type'=>$profile_type,
				'profile_username'=>$profile_username,
				'log_modified'=>$now,
			];
			$this->db->update('general_custom_link', $data, ['id'=>$id, 'uid'=>$uid]);
			ms([
				"status"  => "success",
				"message" => 'Berhasil.',
				"data" => ['log_modified'=>$now, 'slug'=>$slug],
			]);
		}else{
			$userLink = $this->getListLink()->num_rows();
			if($uid != 1 && $this->getMaxUserLink() <= $userLink){
				ms([
					"status"  => "error",
					"message" => 'You have reach your maximum link. Please upgrade your account to get more link.'
				]);
			}
			if(!$this->isSlugAvailable($slug)){
				ms([
					"status"  => "error",
					"message" => 'Custom link is not available please change it.'
				]);
			}
			
			$data = [
				'uid'=>$uid,
				'data'=>json_encode($mydata),
				'description'=>trim($description),
        'fb_pixel'=>$fb_pixel,
				'body_color'=>$body_color,
				'slug'=>$slug,
				'profile_type'=>$profile_type,
				'profile_username'=>$profile_username,
			];
			$this->db->insert('general_custom_link', $data);
			$id = $this->db->insert_id();
			ms([
				"status"  => "success",
				"message" => 'Berhasil.',
				"data" => ['log_created'=>NOW, 'slug'=>$slug, 'id'=>$id],
			]);
		}
	}
	
	public function page($slug){
		$q = $this->db->get_where('general_custom_link', ['slug'=>$slug]);
		if($q->num_rows() ==0){
			redirect();
		}
		$viewCount = $q->row()->view_count +1;
		$this->db->update('general_custom_link', ['view_count'=>$viewCount],['slug'=>$slug]);
		$data =[
			'info'=>$q->row(),
			'viewCount'=>$viewCount
		];
		$this->load->view('page',$data);
	}

	public function get_data(){
		$id = post("id");
		$uid = session('uid');
		$q = $this->db->get_where('general_custom_link', ['uid'=>$uid, 'id'=>$id]);
		if($q->num_rows()==0){
			ms([
				"status"  => "error",
				"message" => 'Data tidak ditemukan.'
			]);
		}
		$data = [
			'id'=>$q->row()->id,
			'profile_type'=>$q->row()->profile_type,
			'profile_username'=>$q->row()->profile_username,
			'slug'=>$q->row()->slug,
			'body_color'=>$q->row()->body_color,
			'description'=>$q->row()->description,
      'fb_pixel'=>$q->row()->fb_pixel,
			'item'=>json_decode($q->row()->data),
		];
		ms([
			"status"  => "success",
			"data" => $data
		]);
	}
	
	public function ajax_delete_item(){
		$id = post("id");
		$uid = session('uid');
		$q = $this->db->get_where('general_custom_link', ['uid'=>$uid, 'id'=>$id]);
		if($q->num_rows()==0){
			ms([
				"status"  => "error",
				"message" => 'Data tidak ditemukan.'
			]);
		}
		
		$this->db->delete('general_custom_link', ['uid'=>$uid, 'id'=>$id]);
		ms([
			"status"  => "success",
			"message" => 'Berhasil.'
		]);
	}
}