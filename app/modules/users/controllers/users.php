<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class users extends MX_Controller {
	public $table;
	public $columns;
	public $module_name;
	public $module_icon;

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
		$this->load->helper('string');
		//Config Module
		$this->table       = USERS;
		$this->module_name = lang("user_manager");
		$this->module_icon = "fa ft-users";
		$this->columns = array(
			"email"            => lang("email"),
			"fullname"         => lang("fullname"),
			"whatsapp"         => "Whatsapp",
			"percent"         => "Percentage",
			"balance"         => "Balance WD",
			"package"          => lang("package"),
			"expiration_date"  => lang("expiration_date"),
			"login_type"       => lang("login_type"),
			"history_ip"       => lang("history_ip"),
			"status"           => lang("status"),
			"changed"          => lang("changed"),
			"created"          => lang("created")
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

	public function login_as_user($ids){
		if(session('uid') != 1) redirect();
		$q = $this->db->get_where(USERS, ['ids'=>$ids, 'id !='=>1]);
		if($q->num_rows()==1){
			$tmp_uid = session('uid');
			
			//login user
			set_session('uid', $q->row()->id);
			
			//save user admin
			set_session('tmp_uid', $tmp_uid);
			
			redirect('dashboard');
			
		}
		redirect('users');
	}
	
	public function update_subscriber(){
		set_time_limit(30);
		$q = $this->db->limit(2)->order_by('log_created', 'asc')->get_where('general_subscriber', ['update_time'=>null]);
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$this->load->library('Kirim_email_api', null, 'ek');
				if($row->type =='register'){
					$this->ek->create_subscriber($row->email, $row->fullname, 'register');
					$this->ek->delete_subscriber($row->email, 'paid');
					$this->ek->delete_subscriber($row->email, 'expired');
				}elseif($row->type =='paid'){
					$this->ek->create_subscriber($row->email, $row->fullname, 'paid');
					$this->ek->delete_subscriber($row->email, 'register');
					$this->ek->delete_subscriber($row->email, 'expired');
				}elseif($row->type =='expired'){
					$this->ek->create_subscriber($row->email, $row->fullname, 'expired');
					$this->ek->delete_subscriber($row->email, 'register');
					$this->ek->delete_subscriber($row->email, 'paid');
				}
				$this->db->update('general_subscriber', ['update_time'=>NOW], ['id'=>$row->id]);
			}
		}
	}
	
	public function update(){
		$data = array(
			"packages"    => $this->model->fetch("name, id, ids", PACKAGES),
			//"result"      => $this->model->get("*", $this->table, "ids = '".segment(3)."'"),
			"result"      => $this->db->select("a.*, b.percent")->from(USERS .' as a')->join('general_referal as b','a.id=b.uid','left')->where("a.ids = '".segment(3)."'")->get()->row(),
			"module"      => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon
		);
		$this->template->build('update', $data);
	}
	
	private function generate_code(){
		while(true){
			$refCode = random_string('alnum', 5);
			$q = $this->db->get_where('general_referal', ['code'=>$refCode]);
			if($q->num_rows()==0) return $refCode;
		}
	}
	
	private function create_referal($userId){
		$refCode = $this->generate_code();
		$refcodeurl = site_url('ref/'.$refCode);
		$balance=0;
		$percent = get_option('default_referal_percent', 20);
		$bitly ='';
		$this->load->helper('bitly');
		$params['access_token'] = get_option('api_bitlink');
		$params['longUrl'] = $refcodeurl;
		$resp = bitly_get('shorten', $params);
		if($resp['status_code']==200){
			$bitly=$resp['data']['url'];
		}
		$this->db->insert('general_referal', ['code'=>$refCode, 'uid'=>$userId, 'bitly'=>$bitly, 'percent'=>$percent]);
	}

	public function ajax_update(){
		$ids      = post("ids");
		$fullname = post("fullname");
		$email    = post("email");
		$whatsapp = post("whatsapp");
		$percent = intval(post("percent"));
		$password = post("password");
		$package_ids  = post("package");
		$confirm_password = post("confirm_password");
		$expiration_date  = post("expiration_date");
		$timezone  = post("timezone");

		if($fullname == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_fullname")
			));
		}

		if($email == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_email")
			));
		}
		
		if(!preg_match('/08+[0-9]{8,11}/',$whatsapp)){
			ms(array(
				"status"  => "error",
				"message" => "please enter valid phone number"
			));
		}

		if(!filter_var(post("email"), FILTER_VALIDATE_EMAIL)){
		  	ms(array(
				"status"  => "error",
				"message" => lang("email_address_in_invalid_format")
			));
		}

		if($package_ids == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("please_select_a_package")
			));
		}

		if($expiration_date == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_expiration_date")
			));
		}

		$package = $this->model->get('*', PACKAGES, "ids = '$package_ids'");

		if(empty($package)){
			ms(array(
				"status"  => "error",
				"message" => lang('package_does_not_exist')
			));
		}

		$check_timezone = 0;
		foreach (tz_list() as $key => $value) {
			if($timezone == $value['zone']){
				$check_timezone = 1;
			}
		}

		if(!$check_timezone){
			ms(array(
				"status"  => "error",
				"message" => lang('timezone_is_required')
			));
		}

		//
		$data = array(
			"fullname"        => $fullname,
			"email"           => $email,
			"whatsapp"        => $whatsapp,
			"package"         => $package->id,
			"permission"      => $package->permission,
			"timezone"        => $timezone,
			"expiration_date" => date("Y-m-d", strtotime($expiration_date)),
			"status"          => 1,
			"changed"         => NOW
		);

		$user = $this->model->get("*", $this->table, "ids = '{$ids}'");
		if(empty($user)){
			if($password == ""){
				ms(array(
					"status"  => "error",
					"message" => lang("please_enter_password")
				));
			}

			if(strlen($password) <= 5){
				ms(array(
					"status"  => "error",
					"message" => lang("password_must_be_greater_than_5_characters")
				));
			}

			if($password != $confirm_password){
				ms(array(
					"status"  => "error",
					"message" => lang("password_does_not_match_the_confirm_password")
				));
			}

			//
			$user_check = $this->model->get("id", $this->table, "email = '{$email}'");
			if(!empty($user_check)){
				ms(array(
					"status"  => "error",
					"message" => lang("this_email_already_exists")
				));
			}

			$data["ids"]        = ids();
			$data["login_type"] = "direct";
			$data["password"]   = md5($password);
			$data["activation_key"] = ids();
			$data["reset_key"]      = ids();
			$data["created"]    = NOW;

			$this->db->insert($this->table, $data);
			$uid = $this->db->insert_id();
			$this->create_referal($uid);
			$this->update_user_kirim_email($uid);
		}else{
			if($password != ""){
				if(strlen($password) <= 5){
					ms(array(
						"status"  => "error",
						"message" => lang("password_must_be_greater_than_5_characters")
					));
				}

				if($password != $confirm_password){
					ms(array(
						"status"  => "error",
						"message" => lang("password_does_not_match_the_confirm_password")
					));
				}

				$data["password"] = md5($password);
			}

			//
			$user_check = $this->model->get("id", $this->table, "email = '{$email}' AND email != '{$user->email}'");
			if(!empty($user_check)){
				ms(array(
					"status"  => "error",
					"message" => lang("this_email_already_exists")
				));
			}
			
			$this->db->update($this->table, $data, array("ids" => $user->ids));
			$this->update_user_kirim_email($user->id);
			$q= $this->db->get_where('general_referal', ['uid'=>$user->id]);
			if($q->num_rows()==0){
				$this->create_referal($user->id);
			}else{
				$this->db->update('general_referal', ['percent'=>$percent], array("uid" => $user->id));
			}
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
			$this->load->library('Kirim_email_api', null, 'ek');
			foreach($listid as $id){
				$m = $this->db->get_where(USERS, ['id'=>$id]);
				if($m->num_rows()==1){
					$this->ek->delete_subscriber($m->row()->email, 'register');
					$this->ek->delete_subscriber($m->row()->email, 'paid');
					$this->ek->delete_subscriber($m->row()->email, 'expired');
				}
				$this->model->delete($this->table, $id, false);
			}
			
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
	
	public function update_user_kirim_email($uid){
		
		$this->load->library('Kirim_email_api', null, 'ek');
		$q = $this->db->select('a.email, a.fullname, a.expiration_date, a.package, a.status as status_user, b.status as status_payment')->from(USERS.' a')->join(PAYMENT_HISTORY.' b', 'a.id=b.uid', 'left')->where('a.id', $uid)->where('b.status', 1)->get();
		
		if($q->num_rows()==1){
			if($q->row()->status=0 || $q->row()->package==1 || ($q->row()->status=1 && empty($q->row()->status_payment))) $type='register';
			elseif($q->row()->status=1 && !empty($q->row()->status_payment) && strtotime($q->row()->expiration_date)>= NOW) $type='paid';
			else $type='expired';
			
			//add subscriber
			$this->db->insert('general_subscriber', ['type'=>$type, 'email'=>$q->row()->email, 'fullname'=>$q->row()->fullname]);
		
		}
	}
	
	public function send_email_error_reporting(){
		if(!is_cli() && session('uid')!=1) die('hmm');
		if(get_option('email_problem_reporting', 1)!=1) die('disabled');
		
		$q = $this->db->query("SELECT a.id, a.uid, b.username, c.email_error, a.description FROM general_email_error a JOIN instagram_accounts b ON a.account_id=b.id JOIN general_users c ON a.uid=c.id WHERE a.log_sent IS NULL ORDER BY a.log_created ASC LIMIT 10");
		if($q->num_rows()==0) die('kosong');
		$tmp = [];
		foreach($q->result() as $row){
			$this->db->update('general_email_error', ['log_sent'=>NOW], ['id'=>$row->id]);
			if($row->email_error==0) continue;
			$tmp[$row->uid][] = $row;
		}
		if(empty($tmp)) die('kosong');
		foreach($tmp as $uid=>$row){
			$subject = 'Akun anda bermasalah';
			$content = 'Berikut kami sampaikan bahwa akun anda mempunyai masalah:<br><ul>';
			foreach($row as $data){
				$content .= '<li><b>'.$data->username.'</b> : '.$data->description.'</li>';
			}
			$content .= "</ul><br>Jika Anda tidak ingin menerima pesan ini, mohon untuk merubahnya pada menu profile. Terima kasih";
			$this->model->send_email($subject, $content, $uid);
		}
	}
}