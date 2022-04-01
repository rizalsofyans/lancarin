<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPHtmlParser\Dom;

class manual_activity extends MX_Controller {
	public $max_size = 5*1024;
	public function __construct(){
		parent::__construct();
		$this->module = get_class($this);
		$this->load->model(get_class($this).'_model', 'model');
	}
	
	public function index(){
		$module_name = 'Manual Activity';
		$module_icon = "fa fa-paper-plane";
		$columns = array(
			"id" => 'Id',
			"username" => 'Username',
			"job_name" => 'Job Name',
			"type" => 'Type',
			"target" => 'Target',
			'stat' => 'Status',
			'post_time' => 'Action Time',
			'note' => 'Note',
			"log_created" => 'Created',
		);
		
		$page        = (int)get("p");
		$limit       = 50;
		$result      = $this->model->getTableManualActivity($columns, $limit, $page);
		$total       = $this->model->getTableManualActivity($columns, -1, -1);
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
			"base_url"   => cn('instagram/'.get_class($this).$query_string), 
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
			"module"  => 'instagram/'. get_class($this),
			"module_class"  => 'instagram/'.get_class($this),
			"module_name" => $module_name,
			"module_icon" => $module_icon
		);
		$this->template->build('manual_activity/index', $data);
	}

	public function form(){
		$data = array(
			'accounts'     => $this->model->fetch("id, username, avatar, ids", INSTAGRAM_ACCOUNTS, "uid = ".session("uid")." AND status = 1"),
			'jobname' => $this->getjobname()
		);
		$this->template->build('manual_activity/form', $data);
	}
	
	public function form_post(){
		$data = array(
			'accounts'     => $this->model->fetch("id, username, avatar, ids", INSTAGRAM_ACCOUNTS, "uid = ".session("uid")." AND status = 1"),
			//'jobname' => $this->getjobname()
		);
		$this->template->build('manual_activity/form_post', $data);
	}
	public function test(){
		$this->template->build('manual_activity/test');
	}
	
	public function form_repost(){
		$data = array(
			'accounts'     => $this->model->fetch("id, username, avatar, ids", INSTAGRAM_ACCOUNTS, "uid = ".session("uid")." AND status = 1"),
			'jobname' => $this->getjobname()
		);
		$this->template->build('manual_activity/form_repost', $data);
	}

	private function getListOnlineShop(){
		$uid = !empty(post('manual_uid'))?post('manual_uid'):session('uid');
		$this->db->from('general_online_shop')->where('status',1);
		if(!empty($category)){
			$this->db->where('category');
		}
		$q = $this->db->query("SELECT * FROM general_online_shop WHERE status=1 AND (is_private=0 OR uid=?)", [$uid]);
		return $q->result();
	}
	
	public function getjobname(){
		$search = post('search');
		$page = post('page');
		$q = $this->db->select('job_name')->distinct()->from('instagram_manual_activity')->like('job_name', $search)->limit(50)->get();
		$data = [];
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row->job_name;
			}
		}
		return $data;
	}
	
	private $start_ts=[];
	public function save(){
		$uid = session('uid');
		$accounts = post('account');
		$type = post('type');
		$target = post('target');
		$force = (int) post('force'); //0 give warning, 1 = force , 2 = sesuaikan
		$start = post('start');
		if(empty($start)) $start = date("Y-m-d H:i:s");
		$start2 = post('start-time');
		if(!empty($start2) && strtotime($start) < strtotime($start2)) $start = $start2;
		$delay = (int) post('delay');
		if($delay<1) $delay = 1;
		$interval = (int) post('interval');
		if($interval<1) $interval = 1;
		$jobname = post('jobname');
		$force = post('force');
		$adjust = post('adjust');
		$config = post('config');
		$additional_config = json_encode($config);
		$hours = post('hours');
		$days = post('days');
		
		if(empty($accounts)){
			ms([
				'status'=>'error',
				'message'=>'Akun tidak boleh kosong',
			]);
		}else{
			$start_per_account =[];
			foreach($accounts as $account_id){
				$q = $this->db->get_where(INSTAGRAM_ACCOUNTS, ['uid'=>$uid, 'id'=>$account_id, 'status'=>1]);
				if($q->num_rows()==0){
					ms([
						'status'=>'error',
						'message'=>'Akun tidak ditemukan',
					]);
				}else{
					$start_per_account[$account_id] = $start;
				}
			}
		}
		
		if(empty($type)){
			ms([
				'status'=>'error',
				'message'=>'Type tidak boleh kosong',
			]);
		}
		
		if(empty($target)){
			ms([
				'status'=>'error',
				'message'=>'Target tidak boleh kosong',
			]);
		}
		
		if(empty($jobname)){
			$jobname = date("Ymd_His")."_".$type;
		}
		
		$warning_start_time = [];
		foreach($accounts as $account_id){
			$q = $this->db->select('a.id, b.id as account_id, b.username, a.post_time')->from('instagram_manual_activity a')->join(INSTAGRAM_ACCOUNTS.' b', 'a.account_id=b.id')->where('a.status',0)->where('a.account_id', $account_id)->where('a.uid', $uid)->order_by('a.post_time', 'desc')->limit(1)->get();
			if($q->num_rows() >0){
				$start_ts = strtotime($start_per_account[$account_id]);
				$last_ts = strtotime($q->row()->post_time);
				
				if($start_ts <= $last_ts){
					$warning_start_time[$account_id] = ['username'=>$q->row()->username, 'start_ts'=>$start_ts, 'post_time'=>$last_ts, 'adjust_ts'=>$last_ts+60];
				}
			}
		}
		
		if(!empty($warning_start_time)){
			if(empty($force) && empty($adjust)){
				$aktifitas = ucwords(str_replace('_', ' ', $type));
				$message ='<div>Kami mendeteksi adanya aktifitas <b>'. $aktifitas .'</b> yang belum terselesaikan pada akun berikut:</div>';
				$message .='<table class="table table-striped table-bordered table-hover"><tr><th>Akun</th><th>Schedule terakhir</th></tr>';
				foreach($warning_start_time as $id=>$row){
					$message .='<tr><td>'.$row['username'].'</td><td>'.date('Y-m-d H:i:s', $row['post_time']).'</td></tr>';
				}
				$message .= '</table>';
				$message .= '<div><b>Option:</b><br>
					<ul>
						<li><u><i>Batal</i></u> : Untuk membatalkan pembuatan schedule baru</li>
						<li><u><i class="text-primary">Adjust</i></u> : Untuk menyesuaikan pembuatan schedule 1 menit setelah semua aktifitas <b>'.$aktifitas.'</b> selesai</li>
						<li><u><i class="text-danger">Force</i></u> : Untuk tetap memaksakan settingan schedule baru</li>
					</ul>
				</div>';
				ms([
					'status'=>'warning',
					'message'=>$message,
					'confirmation'=>'warning time'
				]);
			}
		}
		
		$target = str_replace(',',"\n", $target);
		$target = str_replace(' ',"\n", $target);
		$expTarget = explode("\n", $target);
		$i =0;
		
		foreach($expTarget as $row){
			$row = trim($row);
			if(!empty($row)) {
				if(filter_var($row, FILTER_VALIDATE_URL)){
					$eRow = explode('?', $row);
					$row=$eRow[0];
				}
				
				foreach($accounts as $iAccount=>$account_id){
					if(!isset($this->start_ts[$account_id])) $this->start_ts[$account_id] = strtotime($start_per_account[$account_id]);
					$data = [
						'uid'=>$uid,
						'account_id'=>$account_id,
						'job_name'=>$jobname,
						'type'=>$type,
						'target'=>$row,
						'additional_config'=> $additional_config,
						//'post_time'=>date('Y-m-d H:i:s', $start_ts + ($interval * 60 * $i) + ($delay*60*$iAccount)),
						'post_time'=>$this->calculatNextRun($account_id, $interval, ($delay*$iAccount), $days, $hours),
					];
					$this->db->insert('instagram_manual_activity', $data);
				}
				$i++;
			}
		}
		ms([
			'status'=>'success',
			'message'=>'Berhasil',
		]);
		
	}
	
	function calculatNextRun($account_id, $interval, $delay, array $days, array $hours){
		$start_ts = $this->start_ts[$account_id];
		$next = $start_ts + ($interval*60)+ ($delay*60);

		$d = date('w', $next);
		$h = date('G', $next);
		$ts = 0;
		$tmp = [];
		while(!in_array($d, $days) || !in_array($h, $hours)){
			if(!in_array($d, $days)){
				$ts = mktime(0,0,0, date('n', $next), date('j', $next), date('Y', $next));
				$next = $next + 86400;
				$d =date('w', $ts);
				$h =date('G', $ts);
			}
			
			if(!in_array($h,$hours)){
				$ts = mktime(date('H', $next),0,0, date('n', $next), date('j', $next), date('Y', $next));
				$next = $next + 3600 ;
				$d =date('w', $ts);
				$h =date('G', $ts);
			}
		}
		$this->start_ts[$account_id] = empty($ts)?$next:$ts;
		return date('Y-m-d H:i:s', $this->start_ts[$account_id]);
	}
	
	public function ajax_delete_item(){
		$listid = post('id');
		if(!empty($listid)){
			if(!is_array($listid)) $listid=[$listid];
			foreach($listid as $id){
				$this->db->delete('instagram_manual_activity', ['id'=>$id , 'uid'=>session('uid')]);
			}
			ms([
				'status'=>'success',
				'message'=>'Data berhasil dihapus'
			]);
		}else{
			ms([
				'status'=>'error',
				'message'=>'id cannot empty'
			]);
		}
		
	}
	
	public function run_schedule($action){
		if(!is_cli() && session('uid') !=1) redirect();
		$now = NOW;
		$q = $this->db->select('a.id, a.uid')->from('instagram_manual_activity a')->where('a.post_time <=',$now)->where('a.status', 0)->where('a.type', $action)->limit(10)->order_by('a.post_time','asc')->get();
		if($q->num_rows() ==0) die('kosong');

		foreach($q->result() as $row){//make it on progress
			$this->db->update('instagram_manual_activity', ['status'=>2], ['id'=>$row->id]);
		}
		
		foreach($q->result() as $row){
			$_POST['id']=$row->id;
			$_POST['manual_uid']=$row->uid;
			$a = $this->run_now(true);
			print_r($a);
		}
	}
	
	
	public function run_now($return=false){
		$id = post('id');
		$uid = !empty(post('manual_uid'))?post('manual_uid'):session('uid');
		$q=$this->db->get_where('instagram_manual_activity', ['id'=>$id , 'uid'=>$uid]);
		if($q->num_rows()>0){
			if($q->row()->status ==1){
				$ms = [
					'status'=>'error',
					'message'=>'Proses sudah selesai'
				];
				if($return) return $ms;else ms($ms);
			}else{
				if($q->row()->type=='post'){
					//g jadii, masuk ke calendar aja
					//$this->ajax_collect();
				}else{
					$resp = $this->do_action($id);
					if($resp['message']=='success' && !empty($q->row()->collab_post_id)){
						$this->db->update('instagram_manual_activity', ['point'=>0], ['id'=>$q->row()->id]);
						$this->db->query('UPDATE instagram_collaboration_activity a JOIN instagram_collaboration_post b ON a.id=b.collab_id SET a.point=a.point+? WHERE b.id=?',[$q->row()->point, $q->row()->collab_post_id]);
					}
					$ms = $resp;
				}
				if($return) return $ms;else ms($ms);
			}
		}else{
			ms([
				'status'=>'error',
				'message'=>'id tidak ditemukan'
			]);
		}
	}
	
	public function test_engine(){
		$account_id = post('account');
		$target = post('target');
		$text = trim(post('text'));
		$uid = session('uid');
		if(empty($target) ||empty($text) ){
			ms([
				"status"  => "error",
				"message" => 'text dan target tidak boleh kosong.'
			]);
		}
		$instagram_account = $this->db->get_where('instagram_accounts', ['status'=>1, 'id'=>$account_id, 'uid'=>session('uid')])->row();
		if(empty($instagram_account)){
			ms([
				"status"  => "error",
				"message" => 'account tidak boleh kosong.'
			]);
		}
		try {
			$ig   = new InstagramAPI($instagram_account->username, $instagram_account->password, $instagram_account->proxy);

			$target = $this->parseTarget($target);
			if(in_array($target['type'], ['username', 'userid'])){
				if($target['mode']=='url') {
					$target['target'] = $this->getUsernameFromUrl($target['target']);
				}
				if($target['type']=='username'){
					$userId = $ig->get_userid_from_name($target['target']);
				}else{
					$userId = $target['target'];
				}
				
				if(empty($userId)){
					$msg = 'User target not exists';
					$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
					return [
						'status'=>'error',
						'message'=>$msg
					];
				}
				$message = $this->parseMessage($text, $uid, $ig, $userId);
				ms([
					"status"  => "success",
					"data" => $message
				]);
			}
			
		} catch (Exception $e) {
			ms([
				"status"  => "error",
				"message" => $e->getMessage()
			]);
		}
	}

	private function parseTarget($target){
		$targets=[];
		if(filter_var($target, FILTER_VALIDATE_URL)){
			$targets['mode'] = 'url';
			if(preg_match('/https:\/\/www.instagram.com\/p\/+[a-zA-Z\-0-9]+/', $target)){
				$targets['type'] = 'media';
			}else{
				$targets['type'] = 'username';
			}
			$targets['target'] = $target;
		}else{
			$targets['mode'] = 'string';
			if(preg_match('/^\d+$/', $target)){
				$targets['type'] = 'userid';
			}elseif(preg_match('/\d+_\d+/', $target)){
				$targets['type'] = 'mediaid';
			}else{
				$targets['type'] = 'username';
			}
			$targets['target'] = $target;
		}
		return $targets;
	}
	
	
	public function save_multi_post(){
		$uid = session('uid');
		$accounts = post('account');
		$watermark = post('watermark');
		$masterCaption = post('master-caption');
		$caption = post('caption');
		$delay = (int) post('delay');
		$delay = $delay *60;
		$start = post('start');
		$images = $this->input->post('images');
		$media = json_decode($images, true);
		if(empty($accounts)){
			ms(['status'=>'error',
			'message'=>'account cannot empty'
			]);
		}
		if(empty($media)){
			ms(['status'=>'error',
			'message'=>'media cannot empty'
			]);
		}
		
		foreach($accounts as $iAccount=>$account){
			foreach($start as $b=>$begin){
				$mymedia = [];
				foreach($media[$b] as $m){
					$mymedia[] = strtok($m, '?');
				}
				$_POST['media'] = $mymedia;
				if(count($media[$b])>1){
					$type='carousel';
				}else{
					$ext = explode('.',$media[$b][0]);
					if(end($ext)=='mp4') $type='video';
					else $type='photo';
				}
				$_POST['type'] = $type;
				$ts_post_time = strtotime($begin) + ($iAccount * $delay);
				$_POST['time_post'] = date('Y-m-d H:i:s', $ts_post_time);
				$_POST['caption'] = str_replace('{default_caption}', $masterCaption, $caption[$b]);
				$_POST['account'] = [$account];
				$_POST['watermark'] = (bool) $watermark[$b];
				$_POST['is_schedule'] = true;
				$this->post_media($uid);
			}
		}
		
		ms([
			'status'=>'success',
			'message'=>'Berhasil'
		]);
	}
	
	
	private function post_media($uid){
		$accounts  = $this->input->post("account");
		$media     = $this->input->post("media");
		$type      = post("type");
		$time_post = post("time_post");
		$caption   = post("caption");
		$comment   = post("comment");
		$watermark = (bool) post("watermark");

		if(!$accounts){
			return array(
	        	"status"  => "error",
	        	"stop"    => true,
	        	"message" => lang('please_select_an_account')
	        );
		}

		if(!$media && empty($media)){
			return array(
	        	"status"  => "error",
	        	"stop"    => true,
	        	"message" => lang('please_select_a_media')
	        );
		}

		if(!Instagram_Post_Type($type)){
			return array(
	        	"status"  => "error",
	        	"stop"    => true,
	        	"message" => lang('please_select_a_post_type')
	        );
		}
		
		
		if($type=='carousel' && count($media) <2){
			return array(
	        	"status"  => "error",
	        	"stop"    => true,
	        	"message" => 'You need post more than 1 media to use album/carousel type'
	        );
		}

		if(!post("advance")){
			$comment   = "";
		}
		
		if(!empty($accounts)){
			if(!is_array($accounts)) $accounts = [$accounts];
			if(!post('is_schedule')){
			foreach ($accounts as $account_id) {
				$instagram_account = $this->model->get("id, username, password, proxy, default_proxy", INSTAGRAM_ACCOUNTS, "id = '".$account_id."' AND uid = '".$uid."' AND status = 1");
				if(!empty($instagram_account)){
					$data = array(
						"ids" => ids(),
						"uid" => $uid,
						"account" => $instagram_account->id,
						"type" => post("type"),
						"data" => json_encode(array(
									"media"   => $media,
									"caption" => $caption,
									"comment" => $comment,
									"watermark" => $watermark
								)),
						"time_post" => NOW,
						"delay" => 0,
						"time_delete" => 0,
						"changed" => NOW,
						"created" => NOW
					);
					
					$proxy_data = get_proxy(INSTAGRAM_ACCOUNTS, $instagram_account->proxy, $instagram_account);	
					try {
						$ig = new InstagramAPI($instagram_account->username, $instagram_account->password, $proxy_data->use);
						$result = $ig->post($data);
					} catch (Exception $e) {
						return array(
							"status" => "error",
							"message" => $e->getMessage()
						);
					}
					
					if(is_array($result)){
						$data['status'] = 3;
						$data['result'] = $result['message'];

						//Save report
						update_setting("ig_post_error_count", get_setting("ig_post_error_count", 0) + 1);
						update_setting("ig_post_count", get_setting("ig_post_count", 0) + 1);

						$this->db->insert(INSTAGRAM_POSTS, $data);

						return $result;
					}else{
						$data['status'] = 2;
						$data['result'] = json_encode(array("message" => "successfully", "id" => $result->id, "url" => "https://www.instagram.com/p/".$result->code));

						//Save report
						update_setting("ig_post_success_count", get_setting("ig_post_success_count", 0) + 1);
						update_setting("ig_post_count", get_setting("ig_post_count", 0) + 1);
						update_setting("ig_post_{$type}_count", get_setting("ig_post_{$type}_count", 0) + 1);

						$this->db->insert(INSTAGRAM_POSTS, $data);

						return array(
							"status"  => "success",
							"message" => lang('post_successfully')
						);
					}

				}else{
					return array(
						"status"  => "error",
						"message" => lang("instagram_account_not_exists")
					);
				}
			}
			}else{
				foreach ($accounts as $account_id) {
					$instagram_account = $this->model->get("id, username, password", INSTAGRAM_ACCOUNTS, "id = '".$account_id."' AND uid = '".$uid."'");
					if(!empty($instagram_account)){
						$data = array(
							"ids" => ids(),
							"uid" => $uid,
							"account" => $instagram_account->id,
							"type" => post("type"),
							"data" => json_encode(array(
								"media"   => $media,
								"caption" => $caption,
								"comment" => $comment,
								"watermark" => $watermark
							)),
							"time_post" => get_timezone_system($time_post),
							"delay" => 0,
							"time_delete" => 0,
							"status" => 1,
							"changed" => NOW,
							"created" => NOW
						);
						
						$this->db->insert(INSTAGRAM_POSTS, $data);
					}
				}
				return array(
					"status"  => "success",
					"message" => lang('post_successfully')
				);
			}
		}

		return array(
			"status"  => "error",
			"message" => lang("processing_is_error_please_try_again")
		);
	
	}

	
	private function save_image($image, $uid){
		get_upload_folder();

		$return_type = "json";
		$media_link  = $image;
		$media_ext  = post("ext"); //g usah kyknya
		$exp = explode('?', $media_link);
		$media_link2 = $exp[0];
		$fileParts   = pathinfo($media_link2);
		if(!empty($media_ext)) $fileParts['extension']=$media_ext;
		//else $media_ext=$fileParts['extension'];
		$file_name   = ids().".".$fileParts['extension'];
		$file_type   = get_file_type($file_name);
		$newfilename = md5(encrypt_encode($file_name)).".".$file_type;
	

		if(!check_media($media_link2, $media_ext)){
			return array(
				"status"  => "error",
				"message" => "The filetype you are attempting to upload is not allowed!.",
			);
		}

		$file_size = curl_get_file_size($media_link);
		if($file_size > $this->max_size){
			ms(array(
				"status"  => "error",
				"message" => lang("you_have_exceeded_the_file_limit"),
			));
		}

		get_file_via_curl($media_link, $newfilename);

		$mime = mime_content_type(get_path_upload($newfilename));
		if(!strstr($mime, "video/") && !strstr($mime, "image/")){
			return array(
				"status"  => "error",
				"message" => "The filetype you are attempting to upload is not allowed.",
			);
			unlink(get_path_upload($newfilename));
		}

		$file_size = @filesize(get_path_upload($newfilename));
		if(is_int($file_size) && $file_size/1024 > $this->max_size){
			return array(
				"status"  => "error",
				"message" => lang("you_have_exceeded_the_file_limit"),
			);
			unlink(get_path_upload($newfilename));

		}

		$this->model->get_storage("file", $file_size/1024);

		$image_width = 0;
		$image_height = 0;
		$fileinfo = @getimagesize(get_path_upload($newfilename));
		if(!empty($fileinfo)){
			$image_width = $fileinfo[0];
			$image_height = $fileinfo[1];
		}
		
		$data = array(
			"ids"       => ids(),
			"file_name" => $newfilename,
			"image_type"=> get_mime_type(get_file_type($newfilename)),
			"file_ext"  => get_file_type($newfilename),
			"is_image"  => check_image($newfilename),
			"file_size" => round($file_size/1024,2),
			"image_width" => $image_width,
			"image_height" => $image_height,
			"created"   => NOW
		);

		$media = $this->db->select('ids')->get_where(FILE_MANAGER, ["file_name"=>$newfilename, 'uid'=> $uid]); 

		if($media->num_rows()==0){
			$data['uid']      = $uid;
			$this->db->insert(FILE_MANAGER, $data);
		}else{
			$this->db->update(FILE_MANAGER, $data, "ids = '".$media->ids."'");
		}
		
		
		return array(
			"status"  => "success",
			"message" => "Upload successfully",
			"link"    => get_link_file($newfilename)
		);
		
	}
	
	private function replacePattern($text, $patternHelper){
		if(strpos($text, '{money}') !== false){
			//$text = str_replace('{money}', '[0-9\.,]+(?:jt|k)?[^\S]', $text);
			//$text = str_replace('{money}', '([1-9][0-9]{1,2})+[\.,]+[0-9\.,]{3,15}', $text);
			$text = str_replace('{money}', '([0-9]{1,3}[\.,]([0-9]{3}[\.,])*[0-9]{3}|[0-9]+)([\.,][0-9][0-9])?$', $text);
		}
		
		$text = preg_replace('/{after_\[(.*)\]}/', '$1(.+)', $text);
		$text = preg_replace('/{before_\[(.*)\]}/', '(.+)$1', $text);
		$text = preg_replace('/{between_\[(.*)\]_\[(.*)\]}/', '$1(.+)$2', $text);
		$text = str_replace('{handphone}', '(\+62\s?|0)(\d{3,4}-?){2}\d{3,4}', $text);
		$text = str_replace('{url}', '[-a-zA-Z0-9@:%_\+.~#?&\/\/=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)?', $text);
		$text = str_replace('{domain}', '(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}', $text);
		
		$text = str_replace('{target_fullname}', $patternHelper['target_fullname'], $text);
		$text = str_replace('{target_username}', $patternHelper['target_username'], $text);
		
		//$text = preg_quote($text, '/');
		return $text;
	}
	
	private function isOnlineShop($host, $uid){
		$q = $this->db->query("SELECT * FROM general_online_shop WHERE nama=? AND status=1 AND (is_private=0 OR uid=?)", [$host, $uid]);
		return ($q->num_rows()>0)?true:false;
	}
	
	private function do_action($id){
		//$id= post('id');
		$q = $this->db->select('a.*, b.id AS user_id, b.status as user_status, b.expiration_date')->from('instagram_manual_activity a')->join(USERS.' b','a.uid=b.id','LEFT')->where('a.id',$id)->get();
		if($q->num_rows()==0){
			return [
				'status'=>'error',
				'message'=>'schedule not found'
			];
		}else{
			if(empty($q->row()->user_id)){
				$msg = 'User apps not found';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['uid'=>$q->row()->uid, 'status'=>0]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}else{
				if($q->row()->user_status != 1){
					$msg = 'User apps not active';
					$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['uid'=>$q->row()->uid, 'status'=>0]);
					return [
						'status'=>'error',
						'message'=>$msg
					];
				}elseif(strtotime($q->row()->expiration_date) < time()){
					$msg = 'User apps expired';
					$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['uid'=>$q->row()->uid, 'status'=>0]);
					return [
						'status'=>'error',
						'message'=>$msg
					];
				}
				
				$p = $this->db->get_where(INSTAGRAM_ACCOUNTS, ['id'=>$q->row()->account_id]);
				if($p->num_rows()==0){
					$msg = 'User account ig not found';
					$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['uid'=>$q->row()->uid, 'status'=>0]);
					return [
						'status'=>'error',
						'message'=>$msg
					];
				}else{
					if($p->row()->status != 1){
						$msg = 'User account ig not active or have problem';
						$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
						return [
							'status'=>'error',
							'message'=>$msg
						];
					}elseif(empty($p->row()->proxy)){
						$msg = 'User account dont have proxy';
						$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
						return [
							'status'=>'error',
							'message'=>$msg
						];
					}
					
					$userIg = $p->row()->username;
					$passIg = $p->row()->password;
					$proxy = $p->row()->proxy;
				}
			}
			
		}
		
		$type = $q->row()->type;
		$target = $q->row()->target;
		$uid = $q->row()->uid;
		$account_id = $q->row()->account_id;
		$config = json_decode($q->row()->additional_config, true);
		$ig = new InstagramAPI($userIg, $passIg, $proxy);

		$target = $this->parseTarget($target);
		if($type=="repost"){
			if($target['mode'] != 'url'){
				$msg = 'Target must be url';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			$medias=[];
			$original_caption='';
			
			$target['target'] = str_replace(['://www.m.', '://m.'],['://www.','://'],$target['target']); //force mobile to use desktop site
			$host = parse_url($target['target'], PHP_URL_HOST);
			if($host == 'instagram.com' || $host == 'www.instagram.com'){
				//media ig
				$target['target'] = $this->getMediaIdByUrl($target['target']);
				$mediaId = $target['target'];
				$mediainfo = $ig->get_mediainfo($mediaId);
				if(empty($mediainfo)){
					$msg = 'Media tidak ditemukan';
					$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
					return [
						'status'=>'error',
						'message'=>$msg
					];
				}
				$original_caption=isset($mediainfo->caption->text)?$mediainfo->caption->text:'';
				if($mediainfo->media_type==1){
					$media_type = 'photo';
					$medias[] = $mediainfo->image_versions2->candidates[0]->url;
				}elseif($mediainfo->media_type==2){
					$media_type = 'video';
					$medias[] = $mediainfo->video_versions[0]->url;
				}elseif($mediainfo->media_type==8){
					$media_type = 'carousel';
					foreach ($mediainfo->carousel_media as $value) {
						$medias[] = $value->image_versions2->candidates[0]->url;
					}
				}
				
				$patternHelper['target_username'] = $mediainfo->user->username;
				$patternHelper['target_fullname'] = $mediainfo->user->full_name;
			}elseif(preg_match('/(www\.)?+(bukalapak.com|shopee.co.id|tokopedia.com)$/i', $host)){
				$this->load->library('marketplace', null, 'market');
				$this->market->setProxy($proxy);
				$resp = $this->market->productInfo($target['target']);
				if(!isset($resp['data'][0]) || empty($resp['data'][0])){
					$msg = 'Produk tidak ditemukan';
					$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
					return [
						'status'=>'error',
						'message'=>$msg
					];
				}else{
					$_POST['ext']='jpg';
					$product = $resp['data'][0];
					$markup =0;
					if(isset($product['price']['post_max']) && $product['price']['original'] != $product['price']['post_max']){
						$price = number_format($product['price']['original'] * ((100+$markup)/100), 0, ',','.') ." - ". number_format($product['price']['post_max'] * ((100+$markup)/100), 0,',','.');
					}else{
						$price = number_format($product['price']['original'] * ((100+$markup)/100),0,',','.');
					}
					$desc = preg_replace('#<br\s*/?>#i', "\n", strip_tags($product['description'], '<br>'));
					$desc = trim($desc);
					$original_caption= $product['title'] ."\n\n".
					"Harga Rp ". $price."\n".
					$desc
					;
					$original_caption = preg_replace('#<br\s*/?>#i', "\n", $original_caption);
					$exp = explode("\n", $original_caption);
					foreach($exp as $i=>$e){
						$exp[$i] = trim($e);
					}
					$original_caption = implode("\n", $exp);
					
					$medias=$product['images'];
					$media_type = count($medias)>1?'carousel':'photo';
					if(count($medias)>10){
						array_splice($medias, 10);
					}
					$patternHelper['target_username'] = ''; //belum tau
					$patternHelper['target_fullname'] = '';
				}
			}elseif($this->isOnlineShop($host, $uid)){
				$this->load->library('onlineshop', NULL, 'os');
				$this->os->setProxy($proxy);
				$product = $this->os->getProductInfo($host, $target['target']);
				$desc = preg_replace('#<br\s*/?>#i', "\n", strip_tags($product['description'], '<br>'));
				$desc = trim($desc);
				$original_caption= $product['nama'] ."\n\n".
				"Harga Rp ". $product['harga']."\n".
				$desc
				;
				$medias=$product['images'];
				$media_type = count($medias)>1?'carousel':'photo';
				if(count($medias)>10){
					array_splice($medias, 10);
				}
				$patternHelper['target_username'] = ''; //belum tau
				$patternHelper['target_fullname'] = '';
				
			}else{
				$msg = 'Host not recognize';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			$configPost = $config['message'];
			$hasOriginalPost = strpos($configPost, '{original_post}')===false?false:true;
			$message = $original_caption;
			
			$patternFrom = isset($config['pattern_from'])?$config['pattern_from']:[];
			$patternTo = isset($config['pattern_to'])?$config['pattern_to']:[];
			$myinfo =[];
			$delimiter = "~`|@!\"#$%&'*+,./:;=?^_-";
			$delimiters = str_split ($delimiter);
			
			if(!empty($patternFrom)){
				for($n=0; $n<count($patternFrom);$n++){
					$from = $this->replacePattern($patternFrom[$n], $patternHelper);
					$to = $patternTo[$n];
					if(strpos($to, '{my_fullname}') !== false || strpos($to, '{my_fullname}') !== false){
						if(empty($myinfo)){
							$u = $ig->userinfo();
							$myinfo['username']=$u->username;
							$myinfo['fullname']=$u->full_name;
						}
						$to = str_replace('{my_username}', $myinfo['username'], $to);
						$to = str_replace('{my_fullname}', $myinfo['full_name'], $to);
					}
					
					if(preg_match('/\{(\+|\-)(\d+)\%\}/', $to, $match)){
						$find = preg_match_all("/".$from."/m", $message, $mfind);
						if(isset($mfind[0]) && !empty($mfind)){
							foreach($mfind[0] as $f){
								$s = preg_replace('/[^0-9\.,]/','',$f);
								$s = str_replace(',','.', $s);
								$s = preg_replace('/\.+[0-9]{1,2}$/','',$s);
								$s = str_replace('.','', $s);
								if($s>9999){//hanya d atas 10k yg d itung
									if($match[1]=='-'){
										$p = - ($s*$match[2]/100);
									}else{
										$p = $s*$match[2]/100;
									}
									$s = $s + round($p);
									$s = number_format($s,0,',','.');
									$message = str_replace($f, $s, $message);
								}
							}
						}
					}elseif(preg_match('/\{(\+|\-)(\d+)\}/', $to, $match)){
						$find = preg_match_all("/".$from."/", $message, $mfind);
						if(isset($mfind[0]) && !empty($mfind)){
							foreach($mfind[0] as $f){
								$s = preg_replace('/[^0-9\.,]/','',$f);
								$s = str_replace(',','.', $s);
								$s = preg_replace('/\.+[0-9]{1,2}$/','',$s);
								$s = str_replace('.','', $s);
								if($match[1]=='-'){
									$p = - $match[2];
								}else{
									$p = $match[2];
								}
								$s = $s + round($p);
								$s = number_format($s,0,',','.');
								$message = str_replace($f, $s, $message);
							}
						}
					}elseif(preg_match('/\{(below_line_)(\d+)\}/', $from, $match)){
						$exp = explode("\n",$message);
						array_splice($exp, $match[2]);
						$message = implode("\n", $exp);
					}else{
						$delimiter_char = '';
						foreach($delimiters as $del){
							if(strpos($from, $del) ===false) {
								$delimiter_char = $del;
								break;
							}
						}
						if(empty($delimiter_char)){
						$delimiter_char = '~';
							$from = str_replace($delimiter_char,' ', $from);
						}

						$message = preg_replace($delimiter_char.$from.$delimiter_char."is", $to, $message);
						//$message = str_replace($from, $to, $message);
					}
				}
			}
			
			if($hasOriginalPost){
				$message = str_replace('{original_post}', $message, $configPost);
			}else{
				$message = $configPost;
			}
			
			$message = $this->parseMessage($message, $uid, $ig);
			if(strlen($message)>2200){
				$msg = 'Caption terlalu panjang: '.strlen($message).' , max: 2200';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			$watermark = isset($config['watermark'])?$config['watermark']:null;
			
			if(empty($medias)){
				$msg = 'Media kosong';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}else{
				$tmp = [];
				foreach($medias as $media){
					$resp = $this->save_image($media, $uid);
					if($resp['status'] != 'success'){
						$msg = $resp['message'];
						$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
						return [
							'status'=>'error',
							'message'=>$msg
						];
					}else{
						$tmp[] = $resp['link'];
					}
				}
				$medias = $tmp;
				
			}
			
			
			$_POST['account'] = $account_id;
			$_POST['media'] = $medias;
			$_POST['type'] = $media_type;
			$_POST['time_post'] = NOW;
			$_POST['caption'] = $message;
			$_POST['watermark'] = $watermark;
			$resp = $this->post_media($uid);
			
			if($resp['status']=='success'){
				$msg = 'Success';
				$this->db->update('instagram_manual_activity', ['status'=>1, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'success',
					'message'=>$msg
				];
			}else{
				$msg = $resp['message'];
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
		}elseif($type=="follow" && in_array($target['type'], ['username', 'userid'])){
			if($target['mode']=='url') {
				$target['target'] = $this->getUsernameFromUrl($target['target']);
			}
			if($target['type']=='username'){
				$userId = $ig->get_userid_from_name($target['target']);
			}else{
				$userId = $target['target'];
			}
			
			if(empty($userId)){
				$msg = 'User target not exists';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			$userinfo = $ig->get_userinfo($userId);
			if(empty($userinfo)){
				$msg = 'User target not exists.';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			
			$friendship = $ig->get_friendships($userId);
			if(empty($friendship) || !isset($friendship->$userId)){
				$msg = 'Cannot get friendship status.';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($friendship->$userId->following==1){
				$msg = 'Already following';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($friendship->$userId->outgoing_request==1){
				$msg = 'Already following but still didnt accepted yet';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			
			$response = $ig->follow($userId);
			if(!isset($response->status)){
				$msg = 'Unknown error';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($response->status !='ok'){
				$msg = 'Error from IG';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			$msg = 'Success';
			$this->db->update('instagram_manual_activity', ['status'=>1, 'note'=>$msg], ['id'=>$id]);
			return [
				'status'=>'success',
				'message'=>$msg
			];
			
		}elseif($type=="unfollow" && in_array($target['type'], ['username', 'userid'])){
			if($target['mode']=='url') {
				$target['target'] = $this->getUsernameFromUrl($target['target']);
			}
			if($target['type']=='username'){
				$userId = $ig->get_userid_from_name($target['target']);
			}else{
				$userId = $target['target'];
			}
			
			if(empty($userId)){
				$msg = 'User target not exists';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			$userinfo = $ig->get_userinfo($userId);
			if(empty($userinfo)){
				$msg = 'User target not exists.';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			
			$friendship = $ig->get_friendships($userId);
			if(empty($friendship) || !isset($friendship->$userId)){
				$msg = 'Cannot get friendship status.';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($friendship->$userId->following==0 && $friendship->$userId->outgoing_request==0){
				$msg = 'Already not follow';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			
			$response = $ig->unfollow($userId);
			if(!isset($response->status)){
				$msg = 'Unknown error';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($response->status !='ok'){
				$msg = 'Error from IG';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			$msg = 'Success';
			$this->db->update('instagram_manual_activity', ['status'=>1, 'note'=>$msg], ['id'=>$id]);
			return [
				'status'=>'success',
				'message'=>$msg
			];
			
		}elseif($type=="like" && in_array($target['type'], ['media','mediaid'])){
			if($target['mode']=='url') {
				$target['target'] = $this->getMediaIdByUrl($target['target']);
			}
			
			
			$mediaId = $target['target'];
			$mediainfo = $ig->get_mediainfo($mediaId);
			if(empty($mediainfo)){
				$msg = 'Media tidak ditemukan';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($mediainfo->comment_likes_enabled==0){
				$msg = 'Media like disabled';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($mediainfo->has_liked==1){
				$msg = 'Media already liked';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			
			$response = $ig->like($mediaId, 'profile', ['username'=>$mediainfo->user->username, 'user_id'=>$mediainfo->user->pk]);
			if(!isset($response->status)){
				$msg = 'Unknown error';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($response->status !='ok'){
				$msg = 'Error from IG';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			$msg = 'Success';
			$this->db->update('instagram_manual_activity', ['status'=>1, 'note'=>$msg], ['id'=>$id]);
			return [
				'status'=>'success',
				'message'=>$msg
			];
		}elseif($type=="unlike" && in_array($target['type'], ['media','mediaid'])){
			if($target['mode']=='url') {
				$target['target'] = $this->getMediaIdByUrl($target['target']);
			}
			
			$mediaId = $target['target'];
			$mediainfo = $ig->get_mediainfo($mediaId);
			if(empty($mediainfo)){
				$msg = 'Media tidak ditemukan';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($mediainfo->comment_likes_enabled==0){
				$msg = 'Media like disabled';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($mediainfo->has_liked==0){
				$msg = 'Media not liked yet';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			
			$response = $ig->unlike($mediaId, 'profile', ['username'=>$mediainfo->user->username, 'user_id'=>$mediainfo->user->pk]);
			if(!isset($response->status)){
				$msg = 'Unknown error';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($response->status !='ok'){
				$msg = 'Error from IG';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			$msg = 'Success';
			$this->db->update('instagram_manual_activity', ['status'=>1, 'note'=>$msg], ['id'=>$id]);
			return [
				'status'=>'success',
				'message'=>$msg
			];
		}elseif($type=="delete_media" && in_array($target['type'], ['media','mediaid'])){
			if($target['mode']=='url') {
				$target['target'] = $this->getMediaIdByUrl($target['target']);
			}

			$mediaId = $target['target']; 
			$mediainfo = $ig->get_mediainfo($mediaId);
			if(!isset($config['min_like'])) $config['min_like']=0;
			if(!isset($config['min_comment'])) $config['min_comment']=0;
			if(!isset($config['min_date'])) $config['min_date']=0; 
			
			if(empty($mediainfo)){
				$msg = 'Media is not found';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($mediainfo->user->username != $userIg){
				$msg = 'Media is not own post';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($config['min_like'] != 0 && $mediainfo->like_count < $config['min_like']){
				$msg = 'Media like under '. $config['min_like'];
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($config['min_comment'] !=0 && $mediainfo->comment_count < $config['min_comment']){
				$msg = 'Media comment under '. $config['min_comment'];
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($config['min_date'] !=0 && $mediainfo->taken_at < strtotime($config['min_date'])){
				$msg = 'Media post date under '. $config['min_date'];
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
				
			}
			
			$response = $ig->delete_media($mediaId, $mediainfo->media_type);
			if(!isset($response->status)){
				$msg = 'Unknown error';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($response->status !='ok'){
				$msg = 'Error from IG';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			$msg = 'Success';
			$this->db->update('instagram_manual_activity', ['status'=>1, 'note'=>$msg], ['id'=>$id]);
			return [
				'status'=>'success',
				'message'=>$msg
			];
		}elseif($type=="comment" && in_array($target['type'], ['media','mediaid'])){
			if($target['mode']=='url') {
				$target['target'] = $this->getMediaIdByUrl($target['target']);
			}
			

			$mediaId = $target['target'];
			$mediainfo = $ig->get_mediainfo($mediaId);
			if(!isset($config['message'])) $config['message']='';

			if(empty($mediainfo)){
				$msg = 'Media is not found';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($mediainfo->comment_likes_enabled == 0){
				$msg = 'Media comment disabled';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif(empty($config['message'])){
				$msg = 'Message cant empty';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			$message = $this->parseMessage($config['message'], $uid, $ig, $mediainfo->user->pk);

			$response = $ig->comment($mediaId, $message);
			if(!isset($response->status)){
				$msg = 'Unknown error';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($response->status !='ok'){
				$msg = 'Error from IG';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			$msg = 'Success';
			$this->db->update('instagram_manual_activity', ['status'=>1, 'note'=>$msg], ['id'=>$id]);
			return [
				'status'=>'success',
				'message'=>$msg
			];
		}elseif($type=="direct_message" && in_array($target['type'], ['username', 'userid'])){
			if($target['mode']=='url') {
				$target['target'] = $this->getUsernameFromUrl($target['target']);
			}
			if($target['type']=='username'){
				$userId = $ig->get_userid_from_name($target['target']);
			}else{
				$userId = $target['target'];
			}
			
			if(empty($userId)){
				$msg = 'User target not exists';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			$userinfo = $ig->get_userinfo($userId);
			if(empty($userinfo)){
				$msg = 'User target not exists.';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			$message = $config['message'];
			$friendship = $ig->get_friendships($userId);
			if(empty($friendship) || !isset($friendship->$userId)){
				$msg = 'Cannot get friendship status.';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($friendship->$userId->is_private==1 && $friendship->$userId->following==0){
				$msg = 'You cant send message to private user who is not your friend yet';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif(empty($message)){
				$msg = 'Message cannot empty';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			$message = $this->parseMessage($config['message'], $uid, $ig, $userId);
			$response = $ig->direct_message($userId, $message);
			if(!isset($response->status)){
				$msg = 'Unknown error';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}elseif($response->status !='ok'){
				$msg = 'Error from IG';
				$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
				return [
					'status'=>'error',
					'message'=>$msg
				];
			}
			
			$msg = 'Success';
			$this->db->update('instagram_manual_activity', ['status'=>1, 'note'=>$msg], ['id'=>$id]);
			return [
				'status'=>'success',
				'message'=>$msg
			];
			
		}else{
			$msg = 'Action not found';
			$this->db->update('instagram_manual_activity', ['status'=>3, 'note'=>$msg], ['id'=>$id]);
			return [
				'status'=>'error',
				'message'=>$msg
			];
		}
		
	}

	public function parseMessage($message, $uid, $ig=null, $targetUserId=null, array $param=[]){
		$myinfo = null;
		$targetinfo = null;
		if(!empty($ig)){
			if (strpos($message, '{my_fullname}') !== false) {
			    if(!isset($param['my_fullname'])){
			    	$myinfo = !empty($myinfo)?$myinfo:$ig->get_userinfo();
			    	$param['my_fullname'] = isset($myinfo->full_name)?$myinfo->full_name:'';
			    }
			    $message = str_replace('{my_fullname}', $param['my_fullname'], $message);
			}
			if (strpos($message, '{my_username}') !== false) {
			    if(!isset($param['my_username'])){
			    	$myinfo = !empty($myinfo)?$myinfo:$ig->get_userinfo();
			    	$param['my_username'] = isset($myinfo->username)?$myinfo->username:'';
			    }
			    $message = str_replace('{my_username}', $param['my_username'], $message);
			}
			if (strpos($message, '{my_post_count}') !== false) {
			    if(!isset($param['my_post_count'])){
			    	$myinfo = !empty($myinfo)?$myinfo:$ig->get_userinfo();
			    	$param['my_post_count'] = isset($myinfo->media_count)?$myinfo->media_count:'';
			    }
			    $message = str_replace('{my_post_count}', $param['my_post_count'], $message);
			}
			if (strpos($message, '{my_follower_count}') !== false) {
			    if(!isset($param['my_follower_count'])){
			    	$myinfo = !empty($myinfo)?$myinfo:$ig->get_userinfo();
			    	$param['my_follower_count'] = isset($myinfo->follower_count)?$myinfo->follower_count:'';
			    }
			    $message = str_replace('{my_follower_count}', $param['my_follower_count'], $message);
			}
			if (strpos($message, '{my_following_count}') !== false) {
			    if(!isset($param['my_following_count'])){
			    	$myinfo = !empty($myinfo)?$myinfo:$ig->get_userinfo();
			    	$param['my_following_count'] = isset($myinfo->following_count)?$myinfo->following_count:'';
			    }
			    $message = str_replace('{my_following_count}', $param['my_following_count'], $message);
			}

			if (strpos($message, '{target_fullname}') !== false) {
			    if(!isset($param['target_fullname'])){
			    	$targetinfo = !empty($targetinfo)?$targetinfo:$ig->get_userinfo($targetUserId);
			    	$param['target_fullname'] = isset($targetinfo->full_name)?$targetinfo->full_name:'';
			    }
			    $message = str_replace('{target_fullname}', $param['target_fullname'], $message);
			}
			if (strpos($message, '{target_username}') !== false) {
			    if(!isset($param['target_username'])){
			    	$targetinfo = !empty($targetinfo)?$targetinfo:$ig->get_userinfo($targetUserId);
			    	$param['target_username'] = isset($targetinfo->username)?$targetinfo->username:'';
			    }
			    $message = str_replace('{target_username}', $param['target_username'], $message);
			}
			if (strpos($message, '{target_post_count}') !== false) {
			    if(!isset($param['target_post_count'])){
			    	$targetinfo = !empty($targetinfo)?$targetinfo:$ig->get_userinfo($targetUserId);
			    	$param['target_post_count'] = isset($targetinfo->media_count)?$targetinfo->media_count:'';
			    }
			    $message = str_replace('{target_post_count}', $param['target_post_count'], $message);
			}
			if (strpos($message, '{target_follower_count}') !== false) {
			    if(!isset($param['target_follower_count'])){
			    	$targetinfo = !empty($targetinfo)?$targetinfo:$ig->get_userinfo($targetUserId);
			    	$param['target_follower_count'] = isset($targetinfo->follower_count)?$targetinfo->follower_count:'';
			    }
			    $message = str_replace('{target_follower_count}', $param['target_follower_count'], $message);
			}
			if (strpos($message, '{target_following_count}') !== false) {
			    if(!isset($param['target_following_count'])){
			    	$targetinfo = !empty($targetinfo)?$targetinfo:$ig->get_userinfo($targetUserId);
			    	$param['target_following_count'] = isset($targetinfo->following_count)?$targetinfo->following_count:'';
			    }
			    $message = str_replace('{target_following_count}', $param['target_following_count'], $message);
			}
			

		}

		if(strpos($message, '{') !== false && strpos($message, '}') !== false){
			$q = $this->db->get_where('general_template_message', ['uid'=>$uid]);
			if($q->num_rows()>0){
				foreach($q->result() as $row){
				    $message = str_replace('{'.$row->text.'}', $row->replace_text, $message);
				    if(!strpos($message, '{') !== false || !strpos($message, '}') !== false) break;
				}
			}
		}

		$spintax = new Spintax();
		$message = $spintax->process($message);
		
		return $message;
	}
	
	function time_elapsed_string($datetime, $full = false) {
	    $now = new DateTime;
	    $ago = new DateTime($datetime);
	    $diff = $now->diff($ago);

	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;

	    $string = array(
	        'y' => 'tahun',
	        'm' => 'bulan',
	        'w' => 'minggu',
	        'd' => 'hari',
	        'h' => 'jam',
	        'i' => 'menit',
	        's' => 'detik',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
	        } else {
	            unset($string[$k]);
	        }
	    }

	    if (!$full) $string = array_slice($string, 0, 1);
	    return $string ? implode(', ', $string) : '';
	}

	private function getUserInfoByName($i, $name){
		try{
			$info = $i->people->getInfoByName($name);
			$user = $info->getUser();
			$result = [
				'pk' => $user->getPk(),
				'media'=>$user->getMedia_count(),
				'followers'=>$user->getFollower_count(),
				'followings'=>$user->getFollowing_count(),
			];
			return $result;
		} catch (Exception $e){
				return false;
		}
	}
	
	private function getMediaIdByUrl($url){
		$api = file_get_contents("http://api.instagram.com/oembed?url=". $url);
		$apiObj = json_decode($api,true);
		if(!empty($apiObj)){
			return $apiObj['media_id'];
		}else{
			return false;
		}
			
    }
	
}