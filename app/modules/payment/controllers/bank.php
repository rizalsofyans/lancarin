<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class bank extends MX_Controller {

	public $tb_packages;
	public $tb_payment_history;
	public $tb_users;
	private $user;
	private $pass;
	private $norek;
	private $bank;

	public function __construct(){
		parent::__construct();
		$this->tb_packages = PACKAGES;
		$this->tb_payment_history = PAYMENT_HISTORY;
		$this->tb_users = USERS;
		$this->load->model(get_class($this).'_model', 'model');
	}
	
	public function index(){
		if(ajax_page()){
			$package = $this->model->get("*", PACKAGES, "ids = '".get_secure("pid")."'  AND status = 1");
			if(empty($package)){
				echo  "Cannot processing this getway, Please try again later.";
				exit(0);
			}
			$data = array(
				'package' => $package
			);
			$this->load->view('bank', $data);
		}
	}

	public function process($bank, $ids, $plan){
		$bank = strtolower($bank);
		$this->bank = $bank;
		$this->user = get_option($bank.'_username');
		$this->pass =get_option($bank.'_password');
		$this->norek=get_option($bank.'_norek');
		$discountCode = session('discount');
		//$ids  = segment(4);
		//$plan  = segment(5);
		$package = $this->model->get("*", $this->tb_packages, "ids = '".$ids."'  AND status = 1");
		$bank = $this->bank;
		if(!empty($package)){
			$amount = $package->price_monthly;
			if($plan == 2){
				$amount = $package->price_annually*12;
			}else{
				$plan = 1;
			}

			
			
			set_session('payment_package', $ids);
			set_session('payment_plan', $plan);
			set_session('payment_bank', $bank);

			$now = date('Y-m-d H:i:s');
			$exp = explode('-', $now);
			$code = substr($exp[0], 2,2).$exp[1]. substr($exp[2], 0,2);
			$transID = implode('-', [strtoupper($bank), session('uid'), $code ]);
			$q = $this->db->like('transaction_id', $transID)->get($this->tb_payment_history);
			$transID = $transID .'-'. sprintf("%03d", $q->num_rows() + 1);
			set_session('payment_transid', $transID);
			set_session('payment_validuntil', date('Y-m-d H:i:s', time() + 86400));
			
			$discountPercent = 0;
			if(!empty($discountCode) && Modules::run('payment/can_use_discount', $discountCode, session('uid'))){
				$qDiscount = $this->db->get_where('general_discount', ['code'=>$discountCode]);
				$this->db->insert('general_discount_history',['id_discount'=>$qDiscount->row()->id, 'uid'=>session('uid'), 'invoice'=>$transID]);
				$discountPercent=$qDiscount->row()->percent;
				$amount = $amount-($discountPercent * $amount / 100);
			}
			
			$amount = $this->getUniqNumber($bank, $amount);
			set_session('payment_amount', $amount);

			$data = array(
			'bank'=> $bank,
			'bank_nama'=> get_option($bank.'_nama'),
			'norek'=> get_option($bank.'_norek'),
			'akun'=> $this->getAccount(),
			'package' => $package,
			'amount'=> $amount,
			'discount_code'=> $discountCode,
			'discount_percent'=> $discountPercent,
			'plan'=>$plan==2 ? 'tahunan' :'bulanan'
			);
			$this->load->view('create_payment', $data);
		}
	}
  
  private function getAccount(){
    $u= get_field(USERS, session("uid"), "fullname");
    return !empty($u) ? $u : '<span class="text-danger text-bold">user not found</span>';
  }
  
  private function getUniqNumber($bank, $amount){
    while(true){
		$a = rand(1,999);
		$a = $amount+$a;
		//$q = $this->db->get_where($this->tb_payment_history, ['type'=>$bank, 'amount'=>$a, 'valid_until >='=> date('Y-m-d H:i:s', time()-86400)]);
		$q = $this->db->get_where('general_uniq_code', ['kode_unik'=>$a, 'tanggal >='=> date('Y-m-d', time()-86400)]);//echo $this->db->last_query();
		if($q->num_rows() ==0){
			$this->db->insert('general_uniq_code', ['kode_unik'=>$a, 'tanggal'=> date('Y-m-d')]);
			$q = $this->db->get_where($this->tb_payment_history, ['type'=>$bank, 'amount'=>$a, 'valid_until >=', date('Y-m-d H:i:s', time()-86400)]);
			if($q->num_rows() ==0){
				return $a; 
			}
		}
      
    }
    
  }

	public function complete(){
		$ids = session('payment_package');
		$plan = session('payment_plan');
		$uid = session('uid');
		$bank = session('payment_bank');
		$transID = session('payment_transid');
		$amount = session('payment_amount');
    if(empty($ids) || empty($plan) || empty($uid) || empty($bank) || empty($transID)) redirect('pricing');
    
    $q = $this->db->get_where($this->tb_payment_history, ['transaction_id'=> $transID]);
    if($q->num_rows() > 0) redirect('profile');
    
    
		$package = $this->model->get("*", $this->tb_packages, "ids = '".$ids."'  AND status = 1");
		$ref_uid = $this->db->get_where(USERS, ['id'=>$uid])->row()->ref_uid;
		
		if(!empty($package)){
			$data = array(
				'ids' => ids(),
				'uid' => session("uid"),
				'package' => $package->id,
				'type' => session('payment_bank'),
				'transaction_id' => $transID,
				'amount' => $amount,
				'plan' => $plan,
				'status' => 0,
				'created' => NOW,
				'ref_uid' => $ref_uid,
				'valid_until'=> session('payment_validuntil')
			);
			
      
			$this->db->insert($this->tb_payment_history, $data);
			$paymentId = $this->db->insert_id();
				//$this->update_package($package, $plan);
			$this->db->insert('general_reminder', ['payment_id'=>$paymentId, 'uid'=> session('uid'), 'log_date'=>date('Y-m-d'), 'type'=> 'H-0', 'log_sent'=>NOW]);
			$reminderId = $this->db->insert_id();
			$invoiceTemplate = Modules::run('payment/controllers/payment/invoiceEmailTemplate',$reminderId);
			$this->model->send_email(get_option("email_renewal_reminders_subject", ""), get_option("email_renewal_reminders_content", ""). $invoiceTemplate, session('uid'));
		  
			$this->load->view('complete', array(
				'bank'=> $bank,
				'bank_nama'=> get_option($bank.'_nama'),
				'norek'=> get_option($bank.'_norek'),
				'amount'=> $amount
			));
		}else{
			//echo 'gagal';
			redirect("pricing");
		}
	}

	public function cancel(){
		
	}
	
	public function update_package($package_new, $plan, $uid=null){
		if(empty($uid)) $uid=session('uid');
		$user = $this->model->get("*", $this->tb_users, "id = '".$uid."'");
		$new_days = $plan==2?365:30;
		if(!empty($user)){
			$package_old = $this->model->get("*", $this->tb_packages, "id = '".$user->package."'");
			$package_id = $package_new->id;
			if(!empty($package_old)){
				if(strtotime(NOW) < strtotime($user->expiration_date)){
					$date_now = date("Y-m-d", strtotime(NOW));
					$date_expiration = date("Y-m-d", strtotime($user->expiration_date));
					$diff = abs(strtotime($date_expiration) - strtotime($date_now));
					$left_days = floor($diff/86400);

					if($plan == 2){
						$day_added = round(($package_old->price_annually/$package_new->price_annually)*$left_days);
					}else{
						$day_added = round(($package_old->price_monthly/$package_new->price_monthly)*$left_days);
					}

					$total_day = $new_days + $day_added;
					$expiration_date = date('Y-m-d', strtotime(NOW." +".$total_day." days"));
				}else{
					$expiration_date = date('Y-m-d', strtotime(NOW." +".$new_days." days"));
				}
			}else{
				$expiration_date = date('Y-m-d', strtotime(NOW." +".$new_days." days"));
			}

			$data = array(
				"package"      => $package_id,
				"expiration_date" => $expiration_date,
				"permission"=>$package_new->permission
			);

			$this->db->update($this->tb_users, $data, "id = '".$uid."'");
		}
	}
}