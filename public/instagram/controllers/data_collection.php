<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPHtmlParser\Dom;

class data_collection extends MX_Controller {
	public function __construct(){
		parent::__construct();
		$this->module = get_class($this);
		$this->load->model(get_class($this).'_model', 'model');
	}
	
	public function get_related_hashtag(){
		$account_id = post('account_id');
		$uid = session('uid');
		$hashtag = post('hashtag');
		$hashtag = str_replace('#','',$hashtag);
		$maxHashtag = 10;
		
		if(empty($hashtag)){
			ms([
				'status'=>'error',
				'message'=>'Hashtag tidak boleh kosong'
			]);
		}
		
		$q = $this->db->get_where(INSTAGRAM_ACCOUNTS, ['uid'=>$uid,'ids'=>$account_id, 'status'=>1]);
		if($q->num_rows()==0){
			ms([
				'status'=>'error',
				'message'=>'Akun tidak ditemukan'
			]);
		}
		$account = [
			'username'=>$q->row()->username,
			'password'=>$q->row()->password,
			'proxy'=>$q->row()->proxy,
		];
		
		try {
			$ig = new InstagramAPI($account['username'],$account['password'], $account['proxy'] );
			$listHashtag = $ig->search_tag($hashtag);
			if(empty($listHashtag)){
				ms([
					'status'=>'error',
					'message'=> 'related hashtag tidak ditemukan',
				]);
			}
			$data = '';
			foreach($listHashtag as $i=>$tag){
				if($i>=$maxHashtag) break;
				if($tag->name != $hashtag){
					$data .= '#'.$tag->name . ' ';
				}
			}
			ms([
				'status'=>'success',
				'data'=>$data,
			]);
			
			
		} catch (Exception $e) {
			ms([
				'status'=>'error',
				'message'=> $e->getMessage(),
			]);
		}
	}
	
	public function download_text($filename){
		$filename = basename($filename);
		$filepath = 'assets/data/user'.session('uid').'/'.$filename;
		if(file_exists($filepath)){
			$content = file_get_contents($filepath);
		}else{
			$content= 'file not found';
		}
		$this->load->helper('download');
		force_download($filename, $content);
	}

	public function get_data_comment(){
		$id = post('data-id');
		$account_id = post('account');
		$max_number = (int) post('max-number');
		if($max_number<1) $max_number=9999999;
		$keyword = post('keyword');
		$max_time = post('max-time');
		$mention_count = (int) post('mention-count');
		$duplicate_user = (int) post('duplicate-user');
		$duplicate_comment = (int) post('duplicate-comment');
		$myfollower = (int) post('my-follower');
		$more_hashtag = (int) post('more-hashtag');
		$winner_count = (int) post('winner-count');
		if($winner_count <1 || $winner_count >5){
			ms([
				'status'=>'error',
				'message'=>'Jumlah pemenang hanya boleh 1-5'
			]);
		}
		$blacklist_user = post('blacklist-user');
		$blacklist=[];
		$blacklist_user = str_replace(',',"\n", $blacklist_user);
		$blacklist_user = str_replace(' ',"\n", $blacklist_user);
		foreach(explode("\n", $blacklist_user) as $row){
			$row = trim($row);
			if(!empty($row)){
				$blacklist[] = $row; 
			}
			
		}
		
		$uid = session('uid');

		if($myfollower && empty($account_id)){
			ms([
				'status'=>'error',
				'message'=>'Akun tidak boleh kosong jika ingin melakukan pengecekan follower'
			]);
		}
		$q = $this->db->get_where(INSTAGRAM_ACCOUNTS, ['uid'=>$uid,'id'=>$account_id, 'status'=>1]);
		if($myfollower && $q->num_rows()==0){
			ms([
				'status'=>'error',
				'message'=>'Akun tidak ditemukan'
			]);
		}elseif($q->num_rows()>0){
			$account = [
				'username'=>$q->row()->username,
				'password'=>$q->row()->password,
				'proxy'=>$q->row()->proxy,
			];
		}else{
			$account = [
				'username'=>''
			];
		}

		$q = $this->db->get_where('general_data_collection', ['uid'=>$uid,'id'=>$id]);
		if($q->num_rows()==0){
			ms([
				'status'=>'error',
				'message'=>'data tidak ditemukan'
			]);
		}
		$filepath = 'assets/data/user'.$uid.'/'.$q->row()->filename;
		if(!file_exists($filepath)){
			ms([
				'status'=>'error',
				'message'=>'file tidak ditemukan'
			]);
			
		}
		
		$users = [];
		$comments = [];
		$ts_max = strtotime($max_time);
		$data = [];
		if (($handle = fopen($filepath, "r")) !== FALSE) {
			$row=1;
			while (($line = fgetcsv($handle)) !== FALSE) {
				if($row>1){
					$valid = true;

					if(!empty($keyword) && stripos($line[3], $keyword) === FALSE){
						$valid=false;
					}
					if($valid && $line[1]==$account['username']){
						$valid=false;
					}
					if($valid && in_array($line[1],$blacklist)){
						$valid=false;
					}

					if($valid && $duplicate_user && in_array($line[1],$users)){
						$valid=false;
					}else{
						$users[] = $line[1];
					}

					if($valid && $duplicate_comment && in_array($line[3],$comments)){
						$valid=false;
					}else{
						$comments[] = $line[3];
					}

					if($valid && strtotime($line[4]) > $ts_max){
						$valid=false;
					}

					if($valid && $more_hashtag && substr_count($line[3],'#') > 1){
						$valid=false;
					}

					if($valid && $mention_count>0 && substr_count($line[3],'@') < $mention_count){
						$valid=false;
					}

					if($valid){
						$data[]=$line;
					}
					if($row >= $max_number) break;
				}
				$row++;
			}
			fclose($handle);

			if(empty($data)){
				ms([
					'status'=>'error',
					'message'=>'Tidak menemukan data dengan kriteria yang ada',
				]);
			}
			function shuffle_assoc(&$array) {
		        $keys = array_keys($array);

		        shuffle($keys);

		        foreach($keys as $key) {
		            $new[$key] = $array[$key];
		        }

		        $array = $new;

		        return true;
		    }
			shuffle_assoc($data);
			
			if($myfollower){
				try {
					$ig = new InstagramAPI($account['username'],$account['password'], $account['proxy'] );
				} catch (Exception $e) {
					ms([
						'status'=>'error',
						'message'=> $e->getMessage(),
					]);
				}
				
			}
			$maxFailFollower = 20;
			$nFollower =0;
			$winner = [];
			$userIdWinner = [];
			foreach($data as $a=>$d){
				if($myfollower){
					if($this->isMyFollower($ig, $d[0])){
						if(!in_array($d[0], $userIdWinner)){
							$winner[] = $a;
							$userIdWinner[] = $d[0];
						}
					}else{
						$nFollower++;
					}
				}else{
					if(!in_array($d[0], $userIdWinner)){
						$winner[] = $a;
						$userIdWinner[] = $d[0];
					}
				}
				if(count($winner)>=$winner_count || $nFollower>=$maxFailFollower) break;
			}

			if(empty($winner)){
				ms([
					'status'=>'error',
					'message'=>'Tidak menemukan pemenang yang merupakan follower anda',
				]);
			}
			$html ='';
			foreach($data as $d){
				$html .= '<div class="text-center meee">'.$d[1].'</div>';
			}
			ms([
				'status'=>'success',
				'winner'=>$winner,
				'data'=>$data,
				'html'=>$html
			]);
		}else{
			ms([
				'status'=>'error',
				'message'=>'Cant open file',
			]);
		}

	}

	private function isMyFollower($ig, $userId){
		$resp = $ig->get_friendship($userId);
		return (!isset($resp->followed_by) || empty($resp->followed_by)) ? false:true;
	}
	
	public function ajax_download_excel(){
		$id = post('id');
		$q = $this->db->get_where('general_data_collection', ['id'=>$id, 'uid'=>session('uid')]); 
		if($q->num_rows() ==0){
			ms([
				'status'=>'error',
				'message'=>'data not found',
			]);
		}
		$result['status']='error';
		$file = 'assets/data/user'.session('uid').'/export.xlsx';
		@file_put_contents($file, '');
		if(is_writable($file)){
			$source = 'assets/data/user'.session('uid').'/'.$q->row()->filename;
			if(file_exists($source)){
				if (($handle = fopen($source, "r")) !== FALSE) {
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$row=1;
					
					$headerStyle = [
						'font' => [
							'bold' => true,
						],
						'borders' => [
							'allBorders' => [
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
							],
						],
						'fill' => [
							'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
							'color' => [
								'argb' => 'FFC5C5C5',
							]
						],
					];
					
					$bodyStyle = array(
						'borders' => array(
							'allBorders' => array(
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
								'color' => array('argb' => '00000000'),
							),
						),
					);
					$first_char = 65;
					while (($line = fgetcsv($handle)) !== FALSE) {
						$ascii = $first_char;
						if($row==1){
							array_unshift ($line, 'No');
							for ($c=0; $c < count($line); $c++) {
								$char = chr($ascii);
								$sheet->setCellValue($char.$row, $line[$c]);
								$sheet->getColumnDimension($char)->setAutoSize(true);
								
								$ascii++;
							}
						}else{
							array_unshift ($line, $row-1);
							for ($c=0; $c < count($line); $c++) {
								$sheet->setCellValue(chr($ascii).$row, $line[$c]);
								$ascii++;
							}
						}
						$row++;
					}
					
					fclose($handle);
					$last_char = $ascii-1;
					$sheet ->getStyle('A1:'.chr($last_char). 1)->applyFromArray($headerStyle);
					$sheet ->getStyle('A2:'.chr($last_char).($row-1))->applyFromArray($bodyStyle);
					
					$writer = new Xlsx($spreadsheet);
					$writer->save($file);	
					$result['status']='success';
					$result['url']=site_url("instagram/data_collection/download_text/export.xlsx");
					$result['message']='Berhasil';
				}else{
					ms([
						'status'=>'error',
						'message'=>'Cant open file',
					]);
				}
			}else{
				ms([
					'status'=>'error',
					'message'=>'File not found',
				]);
			}
			
		}else{
			$result['message'] = 'File: data.csv is not writeable for PHP. Please check file permission.';
		}
		ms($result);
	}
	
	public function preview_data(){
		$id = post('id');
		
		$q = $this->db->get_where('general_data_collection', ['id'=>$id, 'uid'=>session('uid')]);
		if($q->num_rows()> 0){
			$delimit = $q->row()->group_type=='instagram'? ", ": "\n";
			$filepath = 'assets/data/user'.session('uid').'/'.$q->row()->filename;
			if(file_exists($filepath)){
				$maxsize = 1024*1024; //1Mb
				$filesize = filesize($filepath);
				if($filesize <= $maxsize){
					$data = [];
					if (($handle = fopen($filepath, "r")) !== FALSE) {
						$row=1;
						while (($line = fgetcsv($handle)) !== FALSE) {
							if($row>1){
								$data[]=$line[1];
							}
							$row++;
						}
						fclose($handle);
					}else{
						ms([
							'status'=>'error',
							'message'=>'Cant open file',
						]);
					}
					
					ms([
						'status'=>'success',
						'url'=>'',
						'data'=>implode($delimit, $data),
					]);
				}else{
					ms([
						'status'=>'success',
						'url'=>'instagram/data_collection/preview_raw_data/'.$id,
						'data'=>implode(', ', $data),
					]);
				}
			}
		}
		ms([
			'status'=>'error',
			'message'=>'Data not found',
		]);
	}
	
	public function preview_raw_data($id){		
		$q = $this->db->get_where('general_data_collection', ['id'=>$id, 'uid'=>session('uid')]);
		if($q->num_rows()> 0){
			$filepath = 'assets/data/user'.session('uid').'/'.$q->row()->filename;
			$delimit = $q->row()->group_type=='instagram'? ", ": "<br>";

			if(file_exists($filepath)){
				if (($handle = fopen($filepath, "r")) !== FALSE) {
					$row=1;
					while (($line = fgetcsv($handle)) !== FALSE) {
						if($row>1){
							echo $line[1] . $delimit;
						}
						$row++;
					}
					fclose($handle);
				}else{
					ms([
						'status'=>'error',
						'message'=>'Cant open file',
					]);
				}
			}
		}else{
			die('Data not found');
		}
	}
	
	
	public function index(){
		$module_name = 'Data Collection';
		$module_icon = "fa fa-database";
		$columns = array(
			"id" => 'Id',
			"group_type" => 'Group',
			"type" => 'Type',
			"target" => 'Target',
			"filename" => 'Filename',
			"current_data" => 'Current.Data',
			"final_data" => 'Final.Data',
			'stat' => 'Status',
			'next_run' => 'Next.Run',
			'last_run' => 'Last.Run',
			'log_created' => 'Created',
			'log_modified' => 'Changed',
			'keterangan' => 'Keterangan',
		);
		
		$page        = (int)get("p");
		$limit       = 10;
		$result      = $this->model->getTableDataCollection($columns, $limit, $page);
		$total       = $this->model->getTableDataCollection($columns, -1, -1);
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
		$this->template->build('data_collection/index', $data);
	}

	public function random_comment(){
		$columns = array(
			"id" => 'Id',
			"target" => 'Target',
			'stat' => 'Status',
		);
		
		$page        = 0;
		$limit       = 50;
		$result      = $this->model->getTableCommentCollection($columns, $limit, $page);

		$data = array(
			"result"  => $result,
			"account" => $this->db->get_where(INSTAGRAM_ACCOUNTS, ['uid'=>session('uid'),'status'=>1])->result()
		);
		$this->template->build('data_collection/random_comment', $data);
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
	
	public function form(){
		$data = array(
			'accounts'     => $this->model->fetch("id, username, avatar, ids", INSTAGRAM_ACCOUNTS, "uid = ".session("uid")." AND status = 1"),
			'onlineshop' => $this->getListOnlineShop()
		);
		$this->template->build('data_collection/form', $data);
	}
	
	public function ajax_delete_item(){
		$listid = post('id');
		if(!empty($listid)){
			if(!is_array($listid)) $listid=[$listid];
			foreach($listid as $id){
				$q=$this->db->get_where('general_data_collection', ['id'=>$id , 'uid'=>session('uid')]);
				if($q->num_rows()>0){
					@unlink('assets/data/user'.$q->row()->uid.'/'.$q->row()->filename);
					$this->db->delete('general_data_collection', ['id'=>$id , 'uid'=>session('uid')]);
				}
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
	
	public function stop_collection(){
		$id = post('id');
		$q=$this->db->get_where('general_data_collection', ['id'=>$id , 'uid'=>session('uid')]);
		if($q->num_rows()>0){
			if($q->row()->status ==1){
				ms([
					'status'=>'error',
					'message'=>'Proses sudah dihentikan sebelumnya'
				]);
			}else{
				$this->db->update('general_data_collection', ['status'=>1, 'next_run'=> NULL, 'log_modified'=>NOW], ['id'=>$id , 'uid'=>session('uid')]);
				ms([
					'status'=>'success',
					'message'=>'Data berhasil dihentikan'
				]);
			}
		}else{
			ms([
				'status'=>'error',
				'message'=>'id tidak ditemukan'
			]);
		}
	}
	
	public function run_schedule($group_type='', $type=''){
		if(!is_cli() && session('uid') !=1) die('bye');
		//hentikan schedule utk userid yg d hapus atau expired
		$this->db->query('UPDATE general_data_collection a LEFT JOIN general_users b ON a.uid = b.id SET a.status=3, a.log_modified=? WHERE b.id IS NULL OR b.status = 0',[NOW]);
		if(!empty($group_type)) $this->db->where('group_type',$group_type);
		if(!empty($type)) $this->db->where('type',$type);
		$this->db->limit(10)->order_by('next_run', 'asc');
		$q = $this->db->get_where('general_data_collection', ['status'=>2, 'next_run <='=> NOW]);
		if($q->num_rows() == 0) die('tidak ada schedule');

		//update dulu biar nggak stack
		$now = NOW;
		foreach($q->result() as $row){
			$this->db->query('UPDATE general_data_collection SET next_run=DATE_ADD(next_run, INTERVAL ? SECOND), last_run=?, log_modified=? WHERE id=? ', [$row->run_interval*60, $now, $now, $row->id]);
		}


		foreach($q->result() as $row){
			$fp = fopen(sys_get_temp_dir().DIRECTORY_SEPARATOR."gdc_".$row->id.".lock", "w+");
			if (flock($fp, LOCK_EX | LOCK_NB)) { // do an exclusive lock
				echo 'run id: '.$row->id.PHP_EOL;
				$_POST['id'] = $row->id;
				$_POST['manual_uid'] = $row->uid;
			    $result = $this->run_now(true);
			    if(!isset($result['status']) || $result['status'] =='error'){var_dump($result);
			    	$this->db->update('general_data_collection', ['status'=>3, 'keterangan'=>json_encode($result), 'log_modified'=>NOW], ['id'=>$row->id]);
			    }else{
			    	$this->db->update('general_data_collection', ['keterangan'=>NULL], ['id'=>$row->id]);
			    }
			    flock($fp, LOCK_UN); // release the lock
			}else{
				$this->db->update('general_data_collection', ['next_run'=>$row->next_run + ($row->interval*60), 'last_run'=>NOW, 'keterangan'=>json_encode(['status'=>'error', 'message'=>'process locked']), 'log_modified'=>NOW], ['id'=>$row->id]);
				echo 'locked: '.$row->id.PHP_EOL;
			}
			fclose($fp);
		}
	}

	public function run_now($return=false){
		$id = post('id');
		$uid = !empty(post('manual_uid'))?post('manual_uid'):session('uid');
		$q=$this->db->get_where('general_data_collection', ['id'=>$id , 'uid'=>$uid]);
		if($q->num_rows()>0){
			if($q->row()->status ==1){
				$ms = [
					'status'=>'error',
					'message'=>'Proses sudah selesai'
				];
				if($return) return $ms;else ms($ms);
			}else{
				if($q->row()->group_type=='instagram'){
					$_POST['id'] = $q->row()->id;
					$_POST['account'] = $q->row()->account_id;
					$_POST['target'] = $q->row()->target;
					$_POST['group_type'] = $q->row()->group_type;
					$_POST['type'] = $q->row()->type;
					$_POST['manual_uid'] = $q->row()->uid;
					$_POST['interval'] = $q->row()->run_interval;
					$_POST['current_data'] = $q->row()->current_data;
					$_POST['final_data'] = $q->row()->final_data;
					$_POST['limit'] = $q->row()->final_data - $q->row()->current_data;
					$_POST['rank_token'] = $q->row()->rank_token;
					$_POST['next_max_id'] = $q->row()->max_id;
					if($return) return $this->ajax_collect(true);
					else $this->ajax_collect();
				}elseif($q->row()->group_type=='marketplace'){
					$_POST['id'] = $q->row()->id;
					$_POST['target'] = $q->row()->target;
					$_POST['group_type'] = $q->row()->group_type;
					$_POST['web'] = $q->row()->type;
					$_POST['manual_uid'] = $q->row()->uid;
					$_POST['interval'] = $q->row()->run_interval;
					$_POST['from'] = $q->row()->current_data + 1;
					$_POST['to'] = $q->row()->final_data;
					if($return) return $this->ajax_collect_marketplace(true);
					else $this->ajax_collect_marketplace();
				}elseif($q->row()->group_type=='onlineshop'){
					$_POST['id'] = $q->row()->id;
					$_POST['target'] = $q->row()->target;
					$_POST['group_type'] = $q->row()->group_type;
					$_POST['web'] = $q->row()->type;
					$_POST['target'] = $q->row()->target;
					$_POST['manual_uid'] = $q->row()->uid;
					$_POST['interval'] = $q->row()->run_interval;
					$_POST['from'] = $q->row()->current_data + 1;
					$_POST['to'] = $q->row()->final_data;
					if($return) return $this->ajax_collect_onlineshop(true);
					else $this->ajax_collect_onlineshop();
				}else{
					$ms = [
						'status'=>'error',
						'message'=>'Tipe grup tidak dikenal'
					];
					if($return) return $ms;else ms($ms);
				}
				
			}
		}else{
			$ms = [
				'status'=>'error',
				'message'=>'id tidak ditemukan'
			];
			if($return) return $ms;else ms($ms);
		}
	}
	
	
	function getUsernameFromUrl($url){
		preg_match('/(?:(?:http|https):\/\/)?(?:www.)?(?:instagram.com|instagr.am)\/p\/([A-Za-z0-9-_\.]+)/', $url, $output_array);
		return isset($output_array[1])?$output_array[1]:null;
	}
	
	public function get_category_onlineshop(){
		$web = post('web');
		$uid = session('uid');
		$q = $this->db->query("SELECT * FROM general_online_shop WHERE status=1 AND (is_private=0 OR uid=?) AND nama=? AND have_category=1", [$uid, $web]);
		if($q->num_rows() ==0){
			ms([
				'status'=>'error',
				'message'=>'Toko tidak ditemukan'
			]);
		}
		$namaToko = $q->row()->nama;
		
		$q = $this->db->get_where(INSTAGRAM_ACCOUNTS, ["uid"=> $uid, "status "=> 1]);
		if($q->num_rows() ==0){
			ms([
				"status"  => "error",
				"message" => 'You dont have any account',
			]);
		}
		$account_id = $q->row()->id;
		$proxy = $q->row()->proxy;
		
		$this->load->library('onlineshop', NULL, 'os');
		$this->os->setProxy($proxy);
		$categories = $this->os->getTokoCategory($namaToko);
		if(!empty($categories)){
			$data='';
			foreach($categories as $cat){
				$data .= '<option value="'.$this->os->slugify($cat['nama']).'">'.$cat['nama'].'</option>';
			}
			ms([
				'status'=>'success',
				'data'=>$data
			]);
		}
		ms([
			'status'=>'error',
			'message'=>'Not found any category'
		]);
	}
		
	function ajax_collect_onlineshop($return=false){
		$id = post('id');
		$web = post('web');
		$category = post('target');
		$group_type = 'onlineshop';
		$uid = !empty(post('manual_uid'))?post('manual_uid'):session('uid');
		$interval = (int) post("interval");
		if($interval < 1) $interval=1;
		
		$pageFrom = (int) post("from");
		if($pageFrom<1) $pageFrom = 1;
		$pageTo = (int) post("to");
		if($pageTo<$pageFrom) $pageTo = $pageFrom;
		$limit = get_option('max_scrape_onlineshop_page', 2); //max 2 page per url
		
		$q = $this->db->query("SELECT * FROM general_online_shop WHERE status=1 AND (is_private=0 OR uid=?) AND nama=?", [$uid, $web]);
		if($q->num_rows() ==0){
			$ms = [
				'status'=>'error',
				'message'=>'Toko tidak ditemukan'
			];
			if($return) return $ms;else ms($ms);
		}
		$namaToko = $q->row()->nama;
		
		$q = $this->db->get_where(INSTAGRAM_ACCOUNTS, ["uid"=> $uid, "status "=> 1]);
		if($q->num_rows() ==0){
			$ms = [
				"status"  => "error",
				"message" => 'You dont have any account',
			];
			if($return) return $ms;else ms($ms);
		}
		$account_id = $q->row()->id;
		$proxy = $q->row()->proxy;
		
		$this->load->library('onlineshop', NULL, 'os');
		$this->os->setProxy($proxy);
		$maxpage = $this->os->getMaxPage($namaToko, $category);
		if($pageTo>$maxpage) $pageTo = $maxpage;
		$newPageTo = ($pageTo-$pageFrom > $limit-1)?$pageFrom+$limit-1:$pageTo;
		$listLink = [];
		
		$data_collect=[];
		$existingFile = null;
		if(!empty($id)){
			$qProcess = $this->db->get_where('general_data_collection', ['id'=>$id, 'uid'=>$uid]);
			if($qProcess->num_rows()>0) $existingFile=$qProcess->row()->filename;
		}
		$this->prepare_file(['target'=>$namaToko], $uid, $existingFile);
		for($a=$pageFrom;$a<=$newPageTo;$a++){
			$tmp = $this->os->getProductList($namaToko, $a, $category);
			if(!empty($tmp)){
				$listLink = array_merge($listLink, $tmp);
			}
			$lastPage = $a;
		}
		
		if(!empty($listLink)){
			$datas = [];
			$data=[];
			if(empty($id)){
				$data[] =['Toko', 'Nama', 'Harga', 'Url'];
			}
			
			foreach($listLink as $d){
				$datas[] = $d['url'];
				$data[] = [
					$namaToko, $d['nama'], $d['harga'], $d['url']
				];
			}
			$this->createCSV($data);
			$more_page = $lastPage>=$maxpage?false:true; 
			if(!$more_page) $pageTo = $lastPage;
			
			$current_data=$lastPage;
			
			if($lastPage>=$pageTo){ 
				$status_collection=1;
				$final_data = $lastPage;
				$next_run = NULL;
			}else{
				$status_collection = 2;
				$final_data = $pageTo;
				$next_run = date('Y-m-d H:i:s', time()+($interval*60));
			}
		}else{
			$datas =[];
			$data_collect[$web] = ['count'=>0, 'list'=>''];
			$status_collection = 3; //error
			$current_data=$pageFrom;
			$final_data=$pageTo;
			$next_run=NULL;
			$maxpage=$maxpage;
		}
		
		$status = file_exists($this->filepath)?true:false;
		$q = $this->db->get_where('general_data_collection', ['filename'=> $this->filename, 'uid'=>$uid]);
		
		$now=NOW;
		if($q->num_rows()==0){
			$this->db->insert('general_data_collection', ['filename'=>$this->filename, 'uid'=>$uid, 'status'=>$status_collection, 'run_interval'=>$interval, 'target'=> $namaToko, 'group_type'=> $group_type, 'type'=> $namaToko, 'current_data'=> $current_data, 'final_data'=> $final_data, 'last_run'=>$now, 'next_run'=>$next_run, 'max_data'=> $maxpage, 'account_id'=>$account_id]);
		}else{
			if($status_collection==3) {
				$current_data = $q->row()->current_data;
				$final_data = $q->row()->final_data;
				$maxpage = $q->row()->max_data;
			}
			
			$this->db->update('general_data_collection', ['status'=>$status_collection, 'current_data'=> $current_data, 'final_data'=> $final_data, 'max_data'=>$maxpage, 'log_modified'=>$now, 'last_run'=>$now, 'account_id'=>$account_id], ['id'=>$q->row()->id]);
		}
		
		
		$data_collect[$namaToko] = ['count'=>count($datas), 'list'=>implode("\n", $datas)];
		$ms = [
			'status'=>'success',
			'message'=>'Berhasil',
			'data'=>$data_collect
		];
		if($return) return $ms;else ms($ms);
	}
	
	public function ajax_collect_marketplace($return=false){
		$id = post("id");
		$target = post("target");
		$group_type = 'marketplace';
		$web = post("web");
		$uid = !empty(post('manual_uid'))?post('manual_uid'):session('uid');
		
		$delay = (int) post("delay");
		if($delay < 3) $delay=3;
		
		$interval = (int) post("interval");
		if($interval < 10) $interval=10;
		
		$pageFrom = (int) post("from");
		if($pageFrom<1) $pageFrom = 1;
		$pageTo = (int) post("to");
		if($pageTo<$pageFrom) $pageTo = $pageFrom;
		
		$limit = get_option('max_scrape_marketplace_page', 2); //max 2 page per url
		
		$q = $this->db->get_where(INSTAGRAM_ACCOUNTS, ["uid"=> $uid, "status "=> 1]); //account mana g penting, yg penting proxy nya
		if($q->num_rows() >0){
			$account_id = $q->row()->id;
			$proxy = $q->row()->proxy;
			if(empty($proxy) || !filter_var($proxy, FILTER_VALIDATE_URL)){
				$ms = [
					"status"  => "error",
					"message" => 'Proxy not found',
				];
				if($return) return $ms;else ms($ms);
			}
			$targets =[];
			$target = str_replace(" ", ",", $target);
			$target = str_replace(",", "\n", $target);
			$expTarget=explode("\n", $target);
			$this->load->library('marketplace');
			
			foreach($expTarget as $tar){
				$tar = trim($tar);
				if(empty($tar)){
					continue;
				}elseif(filter_var($tar, FILTER_VALIDATE_URL)){
					$webTarget = $this->marketplace->getPluginByUrl($tar);
					if(!empty($webTarget) && $webTarget==$web){
						$targets[] = $tar;
					}
				}elseif(preg_match('/^[A-Za-z0-9_\.]+$/', $tar)){
					$urlTarget = $this->marketplace->getNamaTokoFromUsername($web, $tar);
					if(!empty($urlTarget)){
						$targets[] = $urlTarget;
					}
				}
			}
			
			if(empty($targets)){
				$ms = [
					"status"  => "error",
					"message" => "Target tidak boleh kosong",
				];
				if($return) return $ms;else ms($ms);
			}
			$this->marketplace->setProxy($proxy);
			
			//$this->tokoProducts($url, array $page=[], $limit=0);
			$data_collect=[];
			$existingFile = null;
			if(!empty($id)){
				$qProcess = $this->db->get_where('general_data_collection', ['id'=>$id, 'uid'=>$uid]);
				if($qProcess->num_rows()>0) $existingFile=$qProcess->row()->filename;
			}
			foreach($targets as $iTarget => $target){
				if($iTarget >0) sleep($delay);
				$newPageTo = ($pageTo-$pageFrom > $limit-1)?$pageFrom+$limit-1:$pageTo;
				$more_page =false;
				$targetUsername = $this->marketplace->getUsernameFromUrl($target);
				$this->prepare_file(['target'=>$targetUsername], $uid, $existingFile);
				$resp =[];
				for($a=0;$a<=$newPageTo-$pageFrom;$a++){
					$lastPage = $pageFrom+$a;
					$tmpResp = $this->marketplace->tokoProducts($target, [$lastPage, $lastPage]);
					if(empty($resp)) {$resp = $tmpResp;} else{
						$merge = array_merge($resp['data'], $tmpResp['data']);
						$resp['data'] = $merge;
					}
					if(!isset($tmpResp['ok']) || $tmpResp['ok']==0 || count($tmpResp['data'])==0 || $tmpResp['data'][0]['more_page']==false) break;
				}
				
				//$resp=$this->marketplace->tokoProducts($target, [$pageFrom, $newPageTo]);
				if(isset($resp['ok']) && $resp['ok']==1 && count($resp['data'])>0){
					$datas = [];
					$data=[];
					if(empty($id)){
						$data[] =['Nama Item', 'Url Item', 'Harga', 'Url gambar', 'Username Toko', 'Url Toko'];
					}
					foreach($resp['data'] as $d){
						$datas[] = $d['url'];
						$data[] = [
							$d['title'], $d['url'], $d['price']['post'], $d['image'], $targetUsername, $target
						];
					}
					$this->createCSV($data);
					$more_page = $d['more_page']; 
					if(!$more_page) $pageTo = $lastPage;
					
					$current_data=$lastPage;
					if(empty($more_page) || $lastPage >= $pageTo){ 
						$status_collection=1;
						$final_data = $lastPage;
						$next_run = NULL;
					}else{
						$status_collection = 2;
						$final_data = $pageTo;
						$next_run = date('Y-m-d H:i:s', time()+($interval*60));
					}
					
					$data_collect[$target] = ['count'=>count($datas), 'list'=>implode("\n", $datas)];
				}else{
					$data_collect[$target] = ['count'=>0, 'list'=>''];
					$status_collection = 3; //error
					$current_data=$pageFrom;
					$final_data=$pageTo;
					$next_run=NULL;
				}
				
				$status = file_exists($this->filepath)?true:false;
				$q = $this->db->get_where('general_data_collection', ['filename'=> $this->filename, 'uid'=>$uid]);
				
				$now=NOW;
				if($q->num_rows()==0){
					$this->db->insert('general_data_collection', ['filename'=>$this->filename, 'uid'=>$uid, 'status'=>$status_collection, 'run_interval'=>$interval, 'target'=> $target, 'group_type'=> $group_type, 'type'=> $web, 'current_data'=> $current_data, 'final_data'=> $final_data, 'last_run'=>$now, 'next_run'=>$next_run, 'account_id'=>$account_id]);
				}else{
					$this->db->update('general_data_collection', ['status'=>$status_collection, 'current_data'=> $current_data, 'final_data'=> $final_data, 'log_modified'=>$now, 'last_run'=>$now, 'account_id'=>$account_id], ['id'=>$q->row()->id]);
				}
				
			}
			$ms = [
				'status'=>'success',
				'data'=> $data_collect,
				'message'=> "Berhasil",
			];
			if($return) return $ms;else ms($ms);
		}else{
			//klo akun tidak ada, maka ganti semua status progress ke error dari user tsb.
			$this->db->update('general_data_collection', ['status'=>3, 'log_modified'=>NOW, 'next_run'=> NULL], ['uid'=>$uid, 'status !='=>1]);
			$ms = [
				"status"  => "error",
				"message" => 'Instagram account not exist',
			];
			if($return) return $ms;else ms($ms);
		}
		
	}
	
	private function createCSV($data){
		$fp = fopen($this->filepath, 'a');
		foreach ($data as $fields) {
			fputcsv($fp, $fields);
		}
		fclose($fp);
	}
	
	public function ajax_collect($return=false){
		$target = post("target");
		$group_type = 'instagram';
		$uid = !empty(post('manual_uid'))?post('manual_uid'):session('uid');
		$type = post("type");
		$rankToken = post("rank_token");
		$next_max_id = $this->input->post("next_max_id"); //nggak ikut grameasy biar g d filter addslash
		$current_data = (int) post('current_data');
		$final_data = (int) post('final_data');
		$delay = (int) post("delay");
		if($delay < 3) $delay=3;
		
		$interval = (int) post("interval");
		if($interval < 1) $interval=1;
		
		$originalLimit = $limit = (int) post("limit");
		//if($limit>2000) $limit =2000; //jaga22 klo lost

		$account_id = (int)post("account");
		$id = (int)post("id");
		$q = $this->db->get_where(INSTAGRAM_ACCOUNTS, ["uid"=> $uid, "status "=> 1, "id"=>$account_id]);

		if($q->num_rows() ==1){
			$account = $q->row();
			$targets =[];
			$target = str_replace(" ", ",", $target);
			$target = str_replace(",", "\n", $target);
			$expTarget=explode("\n", $target);
			$no = 0;
			foreach($expTarget as $tar){
				$tar = trim($tar);
				if(empty($tar)){
					continue;
				}elseif(filter_var($tar, FILTER_VALIDATE_URL)){
					$targets[$no]['mode'] = 'url';
					if(preg_match('/https:\/\/www.instagram.com\/p\/+[a-zA-Z\-0-9]+/', $tar)){
						$targets[$no]['type'] = 'media';
					}else{
						$targets[$no]['type'] = 'username';
					}
					$targets[$no]['target'] = $tar;
				}else{
					$targets[$no]['mode'] = 'string';
					if(preg_match('/^\d+$/', $target)){
						$targets[$no]['type'] = 'userid';
					}elseif(preg_match('/\d+_\d+/', $target)){
						$targets[$no]['type'] = 'mediaid';
					}else{
						$targets[$no]['type'] = 'username';
					}
					$targets[$no]['target'] = $tar;
				}
				$no++;
			}
			
			if(empty($targets)){
				$ms = [
					"status"  => "error",
					"message" => "Target tidak boleh kosong",
				];
				if($return) return $ms;else ms($ms);
			}else{
				$existingFile=NULL;
				if(!empty($id)){
					$qProcess = $this->db->get_where('general_data_collection', ['id'=>$id, 'uid'=>$uid]);
					if($qProcess->num_rows()>0) $existingFile=$qProcess->row()->filename;
				}
				$ig = new InstagramAPI($account->username, $account->password, $account->proxy);
				$data_collect=[];
				foreach($targets as $iTarget => $target){
					if($iTarget > 0) sleep($delay);
					if($type=='followers' && in_array($target['type'], ['username','userid'])){
						$maxLimit = get_option('instagram_max_scrape_follower', 2000);

						$maxCount =($limit > $maxLimit) ? $maxLimit: $limit;
						$originalTarget = $target['target'];
						if($target['mode']=='url') {
							$target['target'] = $this->getUsernameFromUrl($target['target']);
						}
						if($target['type']=='username'){
							$userId = $ig->get_userid_from_name($target['target']);
						}else{
							$userId = $target['target'];
						}
						
						$userinfo = $ig->get_userinfo($userId);
						$max_data = (int) $userinfo->follower_count;
						$is_private = $userinfo->is_private;
						$this->prepare_file($target, $uid, $existingFile);
						
						if($is_private){
							$friendship_allowed = false;
							$friendship = $ig->get_friendships($userId);
							if(isset($friendship->$userId->following) && $friendship->$userId->following==1){
								$friendship_allowed = true;
								$response = $ig->get_followers(true, $userId, $next_max_id, $maxCount, $rankToken, true);
							}else{
								$response=null;
							}
							
						}else{
							$friendship_allowed = true;
							$response = $ig->get_followers(true, $userId, $next_max_id, $maxCount, $rankToken, true);
						}
						
						if($friendship_allowed && !empty($response['users'])){
							$datas=[];
							$data=[];
							if(empty($id)){
								$data[] =['Userid', 'Username', 'Fullname'];
							}
							$i=0;
							foreach($response['users'] as $u){
								$data[] = [
									$u->pk, $u->username, $u->full_name
								];
								$datas[] = $u->username;
								$i++;
								if($limit <= $maxLimit && $i>=$limit) break;	
							}
							
							$this->createCSV($data);
							
							$status = file_exists($this->filepath)?true:false;
							
							$rankToken = $response['rank_token'];
							$next_max_id = $response['next_max_id'];
							$current_data=$i;
							
							if($limit > $max_data){
								$final_data=$max_data;
							}else{
								if(!empty($next_max_id)){
									if(empty($final_data)){
										$final_data = $originalLimit;
									}
								}else{
									$final_data = $current_data;
								}
							}
							
							if($current_data>=$limit || empty($next_max_id)){ 
								$status_collection=1;
								$next_run = NULL;
							}else {
								$status_collection = 2;
								$next_run = date('Y-m-d H:i:s', time()+($interval*60));
							}
							
						}else{
							$status_collection=3;
							$originalTarget=0;
							$current_data = 0;
							$final_data = 0;
							$max_data=0;
							$rankToken = NULL;
							$next_max_id = NULL;
							$next_run = NULL;
							$status = false;
						}
						
						$q = $this->db->get_where('general_data_collection', ['filename'=> $this->filename, 'uid'=>$uid]);
						$now = NOW;
						if($q->num_rows()==0){
							$this->db->insert('general_data_collection', ['filename'=>$this->filename, 'uid'=>$uid, 'status'=>$status_collection, 'target'=> $originalTarget, 'group_type'=> $group_type, 'type'=> $type, 'current_data'=> $current_data, 'final_data'=> $final_data, 'last_run'=>$now, 'next_run'=>$next_run, 'max_data'=>$max_data, 'max_id'=>$next_max_id, 'rank_token'=> $rankToken, 'account_id'=>$account_id]);
						}else{
							if($status_collection==3) {
								$current_data = $q->row()->current_data;
								$final_data = $q->row()->final_data;
								$max_data = $q->row()->max_data;
								$rankToken = $q->row()->rank_token;
								$next_max_id = $q->row()->max_id;
							}else{
								$current_data = $q->row()->current_data + $current_data;
							}
							$this->db->update('general_data_collection', ['status'=>$status_collection, 'current_data'=> $current_data, 'final_data'=> $final_data, 'log_modified'=>$now, 'last_run'=>$now, 'max_data'=>$max_data, 'max_id'=>$next_max_id, 'rank_token'=> $rankToken], ['id'=>$q->row()->id]);
						}
						
						if(!$friendship_allowed && $is_private){
							$ms = [
								"status"  => "error",
								"message" => "Anda belum berteman dengan user private tsb.",
							];
							if($return) return $ms;else ms($ms);
						}
						
						if(!$status){
							$ms = [
								"status"  => "error",
								"message" => "Gagal menyimpan data",
							];
							if($return) return $ms;else ms($ms);
						}else{
							$data_collect[$originalTarget] = ['count'=>count($datas), 'list'=>implode(', ', $datas)];
						}
						
						
					}elseif($type=='followings' && in_array($target['type'], ['username','userid'])){
						$maxLimit = get_option('instagram_max_scrape_following', 2000);
						$maxCount =($limit > $maxLimit) ? $maxLimit: $limit;
						$originalTarget = $target['target'];
						if($target['mode']=='url') {
							$target['target'] = $this->getUsernameFromUrl($target['target']);
						}
						if($target['type']=='username'){
							$userId = $ig->get_userid_from_name($target['target']);
						}else{
							$userId = $target['target'];
						}
						
						$userinfo = $ig->get_userinfo($userId);
						$max_data = (int) $userinfo->following_count;
						$is_private = $userinfo->is_private;
						$this->prepare_file($target, $uid, $existingFile);
						
						if($is_private){
							$friendship_allowed = false;
							$friendship = $ig->get_friendships($userId);
							if(isset($friendship->$userId->following) && $friendship->$userId->following==1){
								$friendship_allowed = true;
								$response = $ig->get_following(true, $userId, $next_max_id, $maxCount, $rankToken, true);
							}else{
								$response=null;
							}
							
						}else{
							$friendship_allowed = true;
							$response = $ig->get_following(true, $userId, $next_max_id, $maxCount, $rankToken, true);
						}
						
						if($friendship_allowed && !empty($response['users'])){
							$datas=[];
							$data=[];
							if(empty($id)){
								$data[] =['Userid', 'Username', 'Fullname'];
							}
							$i=0;
							foreach($response['users'] as $u){
								$data[] = [
									$u->pk, $u->username, $u->full_name
								];
								$datas[] = $u->username;
								$i++;
								if($limit <= $maxLimit && $i>=$limit) break;	
							}
							
							$this->createCSV($data);
							
							$status = file_exists($this->filepath)?true:false;
							
							$rankToken = $response['rank_token'];
							$next_max_id = $response['next_max_id'];
							$current_data=$i;
							
							if($limit > $max_data){
								$final_data=$max_data;
							}else{
								if(!empty($next_max_id)){
									if(empty($final_data)){
										$final_data = $originalLimit;
									}
								}else{
									$final_data = $current_data;
								}
							}
							
							if($current_data>=$limit || empty($next_max_id)){ 
								$status_collection=1;
								$next_run = NULL;
							}else {
								$status_collection = 2;
								$next_run = date('Y-m-d H:i:s', time()+($interval*60));
							}
							
						}else{
							$status_collection=3;
							$originalTarget=0;
							$current_data = 0;
							$final_data = 0;
							$max_data=0;
							$rankToken = NULL;
							$next_max_id = NULL;
							$next_run = NULL;
							$status = false;
						}
						
						$q = $this->db->get_where('general_data_collection', ['filename'=> $this->filename, 'uid'=>$uid]);
						$now = NOW;
						if($q->num_rows()==0){
							$this->db->insert('general_data_collection', ['filename'=>$this->filename, 'uid'=>$uid, 'status'=>$status_collection, 'target'=> $originalTarget, 'group_type'=> $group_type, 'type'=> $type, 'current_data'=> $current_data, 'final_data'=> $final_data, 'last_run'=>$now, 'next_run'=>$next_run, 'max_data'=>$max_data, 'max_id'=>$next_max_id, 'rank_token'=> $rankToken, 'account_id'=>$account_id]);
						}else{
							if($status_collection==3) {
								$current_data = $q->row()->current_data;
								$final_data = $q->row()->final_data;
								$max_data = $q->row()->max_data;
								$rankToken = $q->row()->rank_token;
								$next_max_id = $q->row()->max_id;
							}else{
								$current_data = $q->row()->current_data + $current_data;
							}
							$this->db->update('general_data_collection', ['status'=>$status_collection, 'current_data'=> $current_data, 'final_data'=> $final_data, 'log_modified'=>$now, 'last_run'=>$now, 'max_data'=>$max_data, 'max_id'=>$next_max_id, 'rank_token'=> $rankToken], ['id'=>$q->row()->id]);
						}
						
						if(!$friendship_allowed && $is_private){
							$ms = [
								"status"  => "error",
								"message" => "Anda belum berteman dengan user private tsb.",
							];
							if($return) return $ms;else ms($ms);
						}
						
						if(!$status){
							$ms = [
								"status"  => "error",
								"message" => "Gagal menyimpan data",
							];
							if($return) return $ms;else ms($ms);
						}else{
							$data_collect[$originalTarget] = ['count'=>count($datas), 'list'=>implode(', ', $datas)];
						}
					}elseif($type=='likers' && in_array($target['type'], ['media','mediaid'])){
						$originalTarget = $target['target'];
						if($target['mode']=='url') {
							$target['target'] = $this->getMediaIdByUrl($target['target']);
						}
						
						$response = $ig->get_likers($target['target'], false);
						
						if(!empty($response)){
							$this->prepare_file($target, $uid, $existingFile);
							$datas=[];
							$data=[];
							if(empty($id)){
								$data[] =['Userid', 'Username', 'Fullname'];
							}
							$i=0;
							foreach($response as $u){
								$data[] = [
									$u->pk, $u->username, $u->full_name
								];
								$datas[] = $u->username;
								$i++;
								if($i>=$limit) break;
							}
							$this->createCSV($data);
							
							$status = file_exists($this->filepath)?true:false;
							$q = $this->db->get_where('general_data_collection', ['filename'=> $this->filename, 'uid'=>$uid]);
							
							$current_data=$i;
							$status_collection=1;
							$final_data = $current_data;
							$next_run = NULL;
							
							$now=NOW;
							if($q->num_rows()==0){
								$this->db->insert('general_data_collection', ['filename'=>$this->filename, 'uid'=>$uid, 'status'=>$status_collection, 'target'=> $originalTarget, 'group_type'=> $group_type, 'type'=> $type, 'current_data'=> $current_data, 'final_data'=> $final_data, 'last_run'=>$now, 'next_run'=>$next_run, 'account_id'=>$account_id]);
							}else{
								//nggak ada update
								//$this->db->update('general_data_collection', ['status'=>$status_collection, 'current_data'=> $current_data, 'final_data'=> $final_data, 'log_modified'=>$now, 'last_run'=>$now], ['id'=>$q->row()->id]);
							}
							
							if(!$status){
								$ms = [
									"status"  => "error",
									"message" => "Gagal menyimpan data",
								];
								if($return) return $ms;else ms($ms);
							}else{
								$data_collect[$originalTarget] = ['count'=>count($datas), 'list'=>implode(', ', $datas)];
							}
							
						}
					}elseif($type=='commenters' && in_array($target['type'], ['media','mediaid'])){
						$originalTarget = $target['target'];
						if($target['mode']=='url') {
							$target['target'] = $this->getMediaIdByUrl($target['target']);
						}
						
						$mediainfo = $ig->get_mediainfo($target['target']);
						$max_data = !empty($mediainfo)? $mediainfo->comment_count:0;
						
						$maxLimit = get_option('instagram_max_scrape_comment', 200);
						$maxCount =($limit > $maxLimit) ? $maxLimit: $limit;
						$response = $ig->get_comments($target['target'], $next_max_id,  false, $maxCount, true);
						$this->prepare_file($target, $uid, $existingFile);
						
						if(!empty($response)){
							function sortByOrder($a, $b) {
								return $a->created_at - $b->created_at;
							}
							$theComment = $response['comments'];
							usort($theComment, 'sortByOrder');
							$response['comments'] = $theComment;
							
							$datas=[];
							$data=[];
							$listUser =[];
							if(empty($id)){
								$data[] =['Userid', 'Username', 'Fullname'];
							}
							$i=0;
							foreach($response['comments'] as $u){
								if(!in_array($u->user->username, $listUser)){
									$listUser[]=$u->user->username;
									$data[] = [
										$u->user->pk, $u->user->username, $u->user->full_name
									];
									$datas[] = $u->user->username;
									$i++;
									if($i>=$limit) break;
								}
							}
							$this->createCSV($data);
							$next_max_id = $response['next_min_id'];
							$status = file_exists($this->filepath)?true:false;
							$q = $this->db->get_where('general_data_collection', ['filename'=> $this->filename, 'uid'=>$uid]);
							
							$current_data=$current_data+$i;
							if($limit > $max_data){
								$final_data=$max_data;
							}else{
								if(!empty($next_max_id)){
									if(empty($final_data)){
										$final_data = $originalLimit;
									}
								}else{
									$final_data = $current_data;
								}
							}
							
							if($current_data>=$limit || empty($next_max_id)){ 
								$status_collection=1;
								$next_run = NULL;
							}else {
								$status_collection = 2;
								$next_run = date('Y-m-d H:i:s', time()+($interval*60));
							}
							
							
						}else{
							$status_collection=3;
							$originalTarget=0;
							$current_data = 0;
							$final_data = 0;
							$max_data=0;
							$next_max_id = NULL;
							$next_run = NULL;
							$status = false;
						}
						
						$q = $this->db->get_where('general_data_collection', ['filename'=> $this->filename, 'uid'=>$uid]);
						$now = NOW;
						if($q->num_rows()==0){
							$this->db->insert('general_data_collection', ['filename'=>$this->filename, 'uid'=>$uid, 'status'=>$status_collection, 'target'=> $originalTarget, 'group_type'=> $group_type, 'type'=> $type, 'current_data'=> $current_data, 'final_data'=> $final_data, 'last_run'=>$now, 'next_run'=>$next_run, 'max_data'=>$max_data, 'max_id'=>$next_max_id,  'account_id'=>$account_id]);
						}else{
							if($status_collection==3) {
								$current_data = $q->row()->current_data;
								$final_data = $q->row()->final_data;
								$max_data = $q->row()->max_data;
								$rankToken = $q->row()->rank_token;
								$next_max_id = $q->row()->max_id;
							}
							
							$this->db->update('general_data_collection', ['status'=>$status_collection, 'current_data'=> $current_data, 'final_data'=> $final_data, 'log_modified'=>$now, 'last_run'=>$now, 'max_data'=>$max_data, 'max_id'=>$next_max_id], ['id'=>$q->row()->id]);
						}
						
						if(!$status){
							$ms = [
								"status"  => "error",
								"message" => "Gagal menyimpan data",
							];
							if($return) return $ms;else ms($ms);
						}else{
							$data_collect[$originalTarget] = ['count'=>count($datas), 'list'=>implode(', ', $datas)];
						}
					}elseif($type=='comments' && in_array($target['type'], ['media','mediaid'])){
						$originalTarget = $target['target'];
						if($target['mode']=='url') {
							$target['target'] = $this->getMediaIdByUrl($target['target']);
						}
						
						$mediainfo = $ig->get_mediainfo($target['target']);
						$max_data = !empty($mediainfo)? $mediainfo->comment_count:0;
						
						$maxLimit = get_option('instagram_max_scrape_comment', 200);
						$maxCount =($limit > $maxLimit) ? $maxLimit: $limit;
						$response = $ig->get_comments($target['target'], $next_max_id,  false, $maxCount, true);
						$this->prepare_file($target, $uid, $existingFile);
						
						
						if(!empty($response)){
							function sortByOrder($a, $b) {
								return $a->created_at - $b->created_at;
							}
							$theComment = $response['comments'];
							usort($theComment, 'sortByOrder');
							$response['comments'] = $theComment;
							
							$datas=[];
							$data=[];
							//$listUser =[];
							if(empty($id)){
								$data[] =['Userid', 'Username', 'Fullname', 'Comment', 'Tanggal'];
							}
							$i=0;
							foreach($response['comments'] as $u){
								//if(!in_array($u->user->username, $listUser)){
									$listUser[]=$u->user->username;
									$data[] = [
										$u->user->pk, $u->user->username, $u->user->full_name, $u->text, date('Y-m-d H:i:s',$u->created_at)
									];
									$datas[] = $u->user->username .' : '.$u->text;
									$i++;
									if($i>=$limit) break;
								//}
							}

							$this->createCSV($data);
							$next_max_id = $response['next_min_id'];
							$status = file_exists($this->filepath)?true:false;
							$q = $this->db->get_where('general_data_collection', ['filename'=> $this->filename, 'uid'=>$uid]);
							
							$current_data=$current_data+$i;
							if($limit > $max_data){
								$final_data=$max_data;
							}else{
								if(!empty($next_max_id)){
									if(empty($final_data)){
										$final_data = $originalLimit;
									}
								}else{
									$final_data = $current_data;
								}
							}
							
							if($current_data>=$limit || empty($next_max_id)){ 
								$status_collection=1;
								$next_run = NULL;
							}else {
								$status_collection = 2;
								$next_run = date('Y-m-d H:i:s', time()+($interval*60));
							}
							
						}else{
							$status_collection=3;
							$originalTarget=0;
							$current_data = 0;
							$final_data = 0;
							$max_data=0;
							$next_max_id = NULL;
							$next_run = NULL;
							$status = false;
						}
						
						$q = $this->db->get_where('general_data_collection', ['filename'=> $this->filename, 'uid'=>$uid]);
						$now = NOW;
						if($q->num_rows()==0){
							$this->db->insert('general_data_collection', ['filename'=>$this->filename, 'uid'=>$uid, 'status'=>$status_collection, 'target'=> $originalTarget, 'group_type'=> $group_type, 'type'=> $type, 'current_data'=> $current_data, 'final_data'=> $final_data, 'last_run'=>$now, 'next_run'=>$next_run, 'max_data'=>$max_data, 'max_id'=>$next_max_id,  'account_id'=>$account_id]);
						}else{
							if($status_collection==3) {
								$current_data = $q->row()->current_data;
								$final_data = $q->row()->final_data;
								$max_data = $q->row()->max_data;
								$rankToken = $q->row()->rank_token;
								$next_max_id = $q->row()->max_id;
							}
							
							$this->db->update('general_data_collection', ['status'=>$status_collection, 'current_data'=> $current_data, 'final_data'=> $final_data, 'log_modified'=>$now, 'last_run'=>$now, 'max_data'=>$max_data, 'max_id'=>$next_max_id], ['id'=>$q->row()->id]);
						}
						
						if(!$status){
							$ms = [
								"status"  => "error",
								"message" => "Gagal menyimpan data",
							];
							if($return) return $ms;else ms($ms);
						}else{
							$data_collect[$originalTarget] = ['count'=>count($datas), 'list'=>implode("\n", $datas)];
						}
					
					}elseif($type=='userpost' && in_array($target['type'], ['username','userid'])){
						$maxLimit = get_option('instagram_max_scrape_userpost', 200);
						$maxCount =($limit > $maxLimit) ? $maxLimit: $limit;
						$originalTarget = $target['target'];
						if($target['mode']=='url') {
							$target['target'] = $this->getUsernameFromUrl($target['target']);
						}
						if($target['type']=='username'){
							$userId = $ig->get_userid_from_name($target['target']);
						}else{
							$userId = $target['target'];
						}
						
						$userinfo = $ig->get_userinfo($userId);
						$max_data = (int) $userinfo->media_count;
						$is_private = $userinfo->is_private;
						$this->prepare_file($target, $uid, $existingFile);
						
						$response = $is_private?null:$ig->get_feed($userId, $next_max_id, false, $maxCount, true);
						if($is_private){
							$friendship_allowed = false;
							$friendship = $ig->get_friendships($userId);
							if(isset($friendship->$userId->following) && $friendship->$userId->following==1){
								$friendship_allowed = true;
								$response = $ig->get_feed($userId, $next_max_id, false, $maxCount, true);
							}else{
								$response=null;
							}
							
						}else{
							$friendship_allowed = true;
							$response = $ig->get_feed($userId, $next_max_id, false, $maxCount, true);
						}
						
						if($friendship_allowed && !empty($response['feed'])){
							$datas=[];
							$data=[];
							if(empty($id)){
								$data[] =['Id', 'Url', 'Caption'];
							}
							$i=0;
							foreach($response['feed'] as $u){
								$caption = isset($u->caption->text)?$u->caption->text:'';
								$urlpost = 'https://www.instagram.com/p/'.$u->code;
								$data[] = [
									$u->id, $urlpost.'/', $caption
								];
								$datas[] = $urlpost;
								$i++;
								if($limit <= $maxLimit && $i>=$limit) break;	
							}
							
							$this->createCSV($data);
							
							$status = file_exists($this->filepath)?true:false;
							
							$rankToken = NULL;
							$next_max_id = $response['next_max_id'];
							$current_data=$i;
							
							if($limit > $max_data){
								$final_data=$max_data;
							}else{
								if(!empty($next_max_id)){
									if(empty($final_data)){
										$final_data = $originalLimit;
									}
								}else{
									$final_data = $current_data;
								}
							}
							
							if($current_data>=$limit || empty($next_max_id)){ 
								$status_collection=1;
								$next_run = NULL;
							}else {
								$status_collection = 2;
								$next_run = date('Y-m-d H:i:s', time()+($interval*60));
							}
							
						}else{
							$status_collection=3;
							$originalTarget=0;
							$current_data = 0;
							$final_data = 0;
							$max_data=0;
							$rankToken = NULL;
							$next_max_id = NULL;
							$next_run = NULL;
							$status = false;
						}
						
						$q = $this->db->get_where('general_data_collection', ['filename'=> $this->filename, 'uid'=>$uid]);
						$now = NOW;
						if($q->num_rows()==0){
							$this->db->insert('general_data_collection', ['filename'=>$this->filename, 'uid'=>$uid, 'status'=>$status_collection, 'target'=> $originalTarget, 'group_type'=> $group_type, 'type'=> $type, 'current_data'=> $current_data, 'final_data'=> $final_data, 'last_run'=>$now, 'next_run'=>$next_run, 'max_data'=>$max_data, 'max_id'=>$next_max_id, 'rank_token'=> $rankToken, 'account_id'=>$account_id]);
						}else{
							if($status_collection==3) {
								$current_data = $q->row()->current_data;
								$final_data = $q->row()->final_data;
								$max_data = $q->row()->max_data;
								$rankToken = $q->row()->rank_token;
								$next_max_id = $q->row()->max_id;
							}else{
								$current_data = $q->row()->current_data + $current_data;
							}
							$this->db->update('general_data_collection', ['status'=>$status_collection, 'current_data'=> $current_data, 'final_data'=> $final_data, 'log_modified'=>$now, 'last_run'=>$now, 'max_data'=>$max_data, 'max_id'=>$next_max_id, 'rank_token'=> $rankToken], ['id'=>$q->row()->id]);
						}
						
						if(!$friendship_allowed && $is_private){
							$ms = [
								"status"  => "error",
								"message" => "Anda belum berteman dengan user private tsb.",
							];
							if($return) return $ms;else ms($ms);
						}
						
						if(!$status){
							$ms = [
								"status"  => "error",
								"message" => "Gagal menyimpan data",
							];
							if($return) return $ms;else ms($ms);
						}else{
							$data_collect[$originalTarget] = ['count'=>count($datas), 'list'=>implode("\n", $datas)];
						}
					
					}else{
						$ms = [
							"status"  => "error",
							"message" => "Invalid options",
						];
						if($return) return $ms;else ms($ms);
					}
				}
				$ms = [
					"status"  => "success",
					"data"  => $data_collect,
					"message" => "Berhasil menyimpan data",
				];
				if($return) return $ms;else ms($ms);
			}
			

		}else{
			$ms = [
				"status"  => "error",
				"message" => 'Instagram account not exist',
			];
			if($return) return $ms;else ms($ms);
		}
	}
	
	private function prepare_file($target, $uid, $existingFile=null){
		$this->folder = "assets/data/user".$uid;
		if(!is_dir($this->folder)) mkdir($this->folder, 0755, true);
		$this->filename = !empty($existingFile)?$existingFile:date("Ymd_His")."_".$target['target'].".txt";
		$this->filepath = $this->folder."/".$this->filename;
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
	
	public function engagement($userIG){
		$instagram_account = $this->model->get("*", 'instagram_accounts', "uid = '".session("uid")."' AND status = 1", "rand()");
		$ig   = new InstagramAPI($instagram_account->username, $instagram_account->password, $instagram_account->proxy);
		$userinfo = $ig->get_userinfo($userIG);
		$follower = $userinfo->follower_count;
		$resp = $ig->search_media($userIG, 'username');
		$o=[];
		$en=[];
		if(!empty($resp->items)){
			foreach($resp->items as $items){
				$comment_count=isset($items->comment_count)?$items->comment_count:0;
				$like_count=isset($items->like_count)?$items->like_count:0;
				$engagement = ($comment_count+$like_count)/$follower*100;
				$o[] = [
					'comment'=> $comment_count,
					'like'=> $like_count,
					'follower'=> $follower,
					'engagement'=> round($engagement,2).'%'
				];
				$en[] = $engagement;
			}
		}
		print_r($o);
		echo 'avg engagement: '. round(array_sum($en)/count($en),2).'%';
	}

}