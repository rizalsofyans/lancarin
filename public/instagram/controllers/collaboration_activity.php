<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPHtmlParser\Dom;

class collaboration_activity extends MX_Controller {
	public function __construct(){
		parent::__construct();
		$this->module = get_class($this);
		$this->load->model(get_class($this).'_model', 'model');
	}
	
	public function index(){
		$this->createCollaboration();
		$data = array(
			'accounts' => $this->getAccounts(),
			'member'=> $this->getMember()
		);
		$this->template->build('collaboration_activity/index', $data);
	}
	
	private function getMember(){
		$q = $this->db->query("SELECT COUNT(*) AS jml FROM instagram_collaboration_activity a WHERE a.status=1");
		$p = $this->db->query("SELECT COUNT(*) AS jml FROM instagram_collaboration_activity a JOIN instagram_accounts b ON a.account_id=b.id WHERE a.status=1 AND b.status=1");
		return ['total'=>$q->row()->jml, 'real'=>$p->row()->jml];
	}

	private function createCollaboration(){
		$uid = session('uid');
		$q = $this->db->select('a.id, b.account_id')->from('instagram_accounts a')->join('instagram_collaboration_activity b', 'a.id=b.account_id','left')->where('a.uid',$uid)->get();
		if($q->num_rows()==0) return;
		$data = [];
		foreach($q->result() as $row){
			if(empty($row->account_id)){
				$now = NOW;
				$this->db->insert('instagram_collaboration_activity', ['uid'=>$uid, 'account_id'=>$row->id]);
			}
		}
	}
	
	private function getAccounts(){
		$uid = session('uid');
		$q = $this->db->select('a.id, b.username, a.status as start, b.status, a.point, a.blacklist, b.avatar, a.account_id, a.like_enabled, a.comment_enabled')->from('instagram_collaboration_activity a')->join('instagram_accounts b','a.account_id=b.id')->where('a.uid',$uid)->get();
		if($q->num_rows()==0) return;
		$data = [];
		foreach($q->result() as $row){
			$data[] = [
				'id'=>$row->id,
				'username'=>$row->username,
				'avatar'=>$row->avatar,
				'blacklist'=>$row->blacklist,
				'like_enabled' => $row->like_enabled,
				'comment_enabled'=>$row->comment_enabled,
				'start'=> $row->start,
				'status'=> $row->status,
				'point'=> $row->point
			];
		}

		return $data;
	}

	public function save_setting(){
		$uid = session('uid');
		$id = post('id');
		$like = (int) post('like');
		$comment = (int) post('comment');
		$blacklist = post('blacklist');
		$q = $this->db->get_where('instagram_collaboration_activity', ['id'=>$id, 'uid'=>$uid]);
		if($q->num_rows()==0) ms([
			'status'=>'error',
			'message'=>'Data tidak ditemukan'
		]);

		$this->db->update('instagram_collaboration_activity', ['like_enabled'=>$like, 'comment_enabled'=>$comment, 'blacklist'=>$blacklist, 'log_modified'=>NOW], ['id'=>$id, 'uid'=>$uid]);
		ms([
			'status'=>'success',
			'message'=>'Berhasil'
		]);
	}

	public function change_status(){
		$uid = session('uid');
		$id = post('id');
		$q = $this->db->get_where('instagram_collaboration_activity', ['id'=>$id, 'uid'=>$uid]);
		if($q->num_rows()==0) ms([
			'status'=>'error',
			'message'=>'Data tidak ditemukan'
		]);
		if($q->row()->like_enabled==0 && $q->row()->comment_enabled==0){
			ms([
				'status'=>'error',
				'message'=>'Harus mengaktifkan salah satu fitur terlebih dahulu'
			]);
		}
		$status = $q->row()->status;
		$this->db->update('instagram_collaboration_activity', ['status'=>!$status, 'log_modified'=>NOW], ['id'=>$id, 'uid'=>$uid]);
		$s = $status?'dihentikan':'dimulai';
		ms([
			'status'=>'success',
			'message'=>'Aktivitas berhasil '. $s
		]);
	}

	public function scan_post(){
		if(!is_cli() && session('uid') !=1) redirect();
		$now = NOW;
		$interval = get_option('collab_interval_scan_post',10) *60;
		$minPoint = get_option('collab_min_point_scan_post',10);
		$pointPerPost = get_option('collab_point_per_scan_post',10);
		$ago = date('Y-m-d H:i:s',time()-$interval);
		//cari yg pnya point lebih dari 10;
		$q = $this->db->select('a.id, b.username, b.password, b.proxy, a.point')->from('instagram_collaboration_activity a')->join('instagram_accounts b','a.account_id=b.id')
		->group_start()
                ->where('a.last_scan_post <=', $ago)
                ->or_where('a.last_scan_post', NULL)
        ->group_end()->where('b.status',1)->where('a.status',1)->where('a.point >=',$minPoint)->limit(10)->order_by('a.last_scan_post','asc')->get();

		if($q->num_rows()==0){
			//klo g ada cari tanpa poin
			$q = $this->db->select('a.id, b.username, b.password, b.proxy, a.point')->from('instagram_collaboration_activity a')->join('instagram_accounts b','a.account_id=b.id')->group_start()
                ->where('a.last_scan_post <=', $ago)
                ->or_where('a.last_scan_post', NULL)
        ->group_end()->where('b.status',1)->where('a.status',1)->limit(10)->order_by('a.last_scan_post','asc')->get();
			if($q->num_rows()==0){
				echo $this->db->last_query() .PHP_EOL;
				die('kosong'); //nanti di rubah
			}
		}
		
		//update dulu
		foreach($q->result() as $row){
			$this->db->update('instagram_collaboration_activity',['point'=>$row->point-$pointPerPost,'last_scan_post'=> NOW],['id'=>$row->id]);
		}

		$maxrow = $q->num_rows();
		foreach($q->result() as $i=>$row){
			$fp = fopen(sys_get_temp_dir().DIRECTORY_SEPARATOR."ca_".$row->id.".lock", "w+");
			if (flock($fp, LOCK_EX | LOCK_NB)) { // do an exclusive lock
				echo 'run id: '.$row->id.PHP_EOL;
				$result = $this->get_new_post($row->id, $row->username, $row->password, $row->proxy);
			    flock($fp, LOCK_UN); // release the lock
			}else{
				echo 'locked: '.$row->id.PHP_EOL;
			}
			fclose($fp);
			if($i+1 < $maxrow) sleep(5);
		}
	}

	private function get_new_post($collabId, $username, $password, $proxy){
		$maxpost = get_option('collab_max_scan_post',2);
		$ig = new InstagramAPI($username, $password, $proxy);
		$post = $ig->get_feed();
		if(empty($post)) return;
		$i = 0;
		foreach($post as $row){
			if($i>=$maxpost) break;
			$q = $this->db->get_where('instagram_collaboration_post', ['media_id'=>$row->id]);
			if($q->num_rows()==0){
				$this->db->insert('instagram_collaboration_post', ['collab_id'=> $collabId, 'media_id'=>$row->id,'media_url'=>'https://instagram.com/p/'.$row->code.'/']);
			}
			$i++;
		}
	}
	
	public function scheduled_like(){
		if(!is_cli() && session('uid') !=1) redirect();
		$maxPoint = get_option('collab_min_point_scan_post',10);
		$now = NOW;
		$maxLike = rand(get_option('collab_min_liked', 20), get_option('collab_max_liked', 50));
		$interval = get_option('collab_interval_like',10) *60;
		//$ago = date('Y-m-d H:i:s',time()-$interval);

		//jangan matikan jika status 0 cukup ignore, agar ketika udah kelar bs langsung ikut
		//hapus jika expired
		$this->db->query("DELETE a FROM instagram_collaboration_activity a JOIN general_users b ON a.uid=b.id WHERE b.status=0 OR b.expiration_date < ?",[NOW]);
		
		$q = $this->db->query("SELECT a.id, a.collab_id, b.account_id, a.media_id, c.username FROM instagram_collaboration_post a JOIN instagram_collaboration_activity b ON a.collab_id=b.id JOIN instagram_accounts c ON  b.account_id=c.id WHERE a.queued=0 AND c.status=1 AND b.status=1 AND b.point<=? ORDER BY a.log_created ASC LIMIT 10", [$maxPoint]); 
		if($q->num_rows()==0){
			die('kosong'); //nanti di rubah
		}
		$this->db->query('update `instagram_collaboration_activity` set last_do_like=null');
		$n = 0;
		foreach($q->result() as $row){
			$fp = fopen(sys_get_temp_dir().DIRECTORY_SEPARATOR."sl_".$row->id.".lock", "w+");
			if (flock($fp, LOCK_EX | LOCK_NB)) { // do an exclusive lock
				echo 'run id: '.$row->id.PHP_EOL;
				$u = $this->db->select('a.id as collab_id, a.uid, a.account_id, a.last_do_like, a.blacklist')->from('instagram_collaboration_activity a')->join('instagram_accounts b','a.account_id=b.id')->where('a.status',1)->where('b.status',1)->limit($maxLike)
				->order_by('a.last_do_like','asc')
				//->order_by('a.account_id','asc')
				->get();

				if($u->num_rows()>0){
					$a=0;
					$b=0;
					foreach($u->result() as $job){
						if($a>9){
							$a=0;
							$b++;
						}else{
							$a++;
						}
						$blacklist = str_replace(',',"\n", trim($job->blacklist));
						$blacklist = str_replace(' ',"\n", trim($job->blacklist));
						$exp = explode("\n",'blacklist');
						$blacklistArray=[];
						foreach($exp as $bl){
							if(trim($bl) !=''){
								$blacklistArray[]=$bl;
							}
						}
						if(in_array($row->username, $blacklistArray)) continue;
						$lastLike = (int) strtotime($job->last_do_like); 
						$ts = time();
						if(empty($lastLike)){
							$theTime = $ts +($b*60);
						}else{
							if($lastLike <$ts){
								$theTime = $ts; 
							}else{
								$theTime = $lastLike;
							}
							$theTime = $theTime + $interval;
						}
						$time = date('Y-m-d H:i:s', $theTime);

						echo $job->account_id .' : '. $time .PHP_EOL;
						$this->db->update('instagram_collaboration_activity a', ['a.last_do_like'=> $time], ['id'=>$job->collab_id]);
						$jobdata = [
							'uid' => $job->uid,
							'account_id' => $job->account_id,
							'job_name' => 'collab_post_'.$row->id,
							'type' =>'like',
							'target' => $row->media_id,
							'point' => 1,
							'collab_post_id' => $row->id,
							'post_time' => $time
						];
						$this->db->insert('instagram_manual_activity', $jobdata);
					}
					$this->db->update('instagram_collaboration_post',['queued'=> 1],['id'=>$row->id]);
					echo PHP_EOL;
					$n++;
				}
			    flock($fp, LOCK_UN); // release the lock
			}else{
				echo 'locked: '.$row->id.PHP_EOL;
			}
			fclose($fp);
		}
	}

}