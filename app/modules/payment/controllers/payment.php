<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class payment extends MX_Controller {

	public $tb_packages;

	public function __construct(){
		parent::__construct();
		$this->tb_packages = PACKAGES;
		$this->load->model(get_class($this).'_model', 'model');
	}
	
	public function check_discount(){
		$code=post('code');
		$uid = session('uid');
		$result['ok']=0;
		if($this->can_use_discount($code, $uid)){
			$this->session->set_userdata('discount', $code);
			$result['ok']=1;
		}else{
			$result['msg']='Invalid discount code';
		}
		ms($result);
	}
	
	public function can_use_discount($code, $uid){
		$now = date('Y-m-d');
		$q = $this->db->get_where('general_discount', ['code'=>$code, 'status'=>1, 'tgl_start <='=> $now, 'tgl_end >='=>$now]);
		if($q->num_rows()==1){
			$p = $this->db->get_where('general_discount_history', ['id_discount'=>$q->row()->id, 'uid'=>$uid]);
			if($p->num_rows()==0){
				return true;
			}
		}
		return false;
	}
	
	public function index(){
		$package = $this->model->get("*", $this->tb_packages, "ids = '".segment(2)."'  AND status = 1 AND type = 2", "sort", "asc");
		if(empty($package)){
			redirect(cn('pricing'));
		}


		$type = 2;
		if((int)get("type") == 1){
			$type = 1;
		}

		$data = array(
			'package' => $package,
			'type'    => $type
		);
		//$this->template->set_layout('pricing_page');
		//$this->template->build('index', $data);
		
		$this->load->view('index2', $data);
	}

	public function pricing(){
		$data = array(
			"package" => $this->model->fetch("*", $this->tb_packages, "status = 1 AND type = 2", "sort", "asc")
		);

		//$this->template->set_layout('blank_page');
		//$this->template->build('../../../themes/'.get_theme().'/views/pricing', $data);
		$this->load->view('pricing',$data);
	}

	public function block_pricing(){
		$data = array(
			"package" => $this->model->fetch("*", $this->tb_packages, "status = 1 AND type = 2", "sort", "asc")
		);

		$this->load->view('../../../themes/'.get_theme().'/views/block_pricing', $data);
	}

	public function thank_you(){
		if(get_option("email_payment_enable", "")){
			$this->model->send_email(get_option("email_payment_subject", ""), get_option("email_payment_content", ""), session("uid"));
		}

		$data = array();
		$this->template->set_layout('thank_you_page');
		$this->template->build('thank_you', $data);
	}

	public function payment_unsuccessfully(){
		$data = array();
		$this->template->set_layout('thank_you_page');
		$this->template->build('payment_unsuccessfully', $data);
	}

	public function checkRenewalReminder($diff=1){
   	/* $this->db->query('truncate table general_reminder');
    $this->db->query('DELETE FROM general_payment_history where uid != 1');
    exit;*/
    if($diff !=0 && $diff !=1) exit;
	//H-3 d ganti H-1 ,H-1 dganti H-0
	$invoice_expire = 1; //1hari
    $ts = time();
    $t = date('Y-m-d', $ts+($diff*86400));
    $now = date('Y-m-d', $ts);
    $q= $this->db->query("SELECT DISTINCT b.id, b.package FROM general_reminder a RIGHT JOIN general_users b ON a.uid=b.id WHERE b.expiration_date=? AND (a.log_date != ? OR a.log_date IS NULL) AND (a.type IS NULL OR a.type != ?)", [$t, $now, 'H-'. $diff]);
    if($q->num_rows()){
      foreach($q->result() as $row){
        $paymentId = null;
        if($diff==1){
          $q1 =$this->db->order_by('id','desc')->get_where(PAYMENT_HISTORY, ['uid'=>$row->id]);
          $ada = $q1->num_rows()>0 ? true: false;
          $created = date('Y-m-d H:i:s', $ts);
          $valid = date('Y-m-d H:i:s', $ts+($invoice_expire * 86400));

          if($ada){
            $q2= $this->db->get_where(PACKAGES,['id'=>$q1->row()->package]);
            $plan = $q1->row()->plan;
            $bank=$q1->row()->type;
            $amount = $plan==1 ? $q2->row()->price_monthly : 12*$q2->row()->price_annually;
            $package = $row->package==1 ? $q2->row()->id : $row->package;
          }else{
            $q2= $this->db->order_by('id','asc')->get_where(PACKAGES,['type'=>2]);//personal
            $plan=1;
            $bank='bca';
            $amount = $q2->row()->price_monthly;
            $package = $row->package==1 ? $q2->row()->id : $row->package;
          }
          $amount = $this->getUniqNumber($bank, $amount);
          $exp = explode('-', $created);
          $code = substr($exp[0], 2,2).$exp[1]. substr($exp[2], 0,2);
          $transID = implode('-', [strtoupper($bank), $row->id, $code ]);
          $q4 = $this->db->like('transaction_id', $transID)->get(PAYMENT_HISTORY);
          $transID = $transID .'-'. sprintf("%03d", $q4->num_rows() + 1);

          $this->db->insert(PAYMENT_HISTORY, [
            'ids'=> ids(),
            'uid'=> $row->id,
            'type'=> $bank,
            'package'=>$package,
            'plan'=> $plan,
            'amount'=>$amount,
            'status'=>0,
            'transaction_id'=> $transID,
            'created'=>$created,
            'valid_until'=>$valid
          ]);
          $paymentId = $this->db->insert_id();
        }
		
		$q = $this->db->get_where('general_reminder', ['uid'=>$row->id, 'type'=>'H-'.$diff, 'log_sent'=>null]);
		if($q->num_rows()==0){
			$this->db->insert('general_reminder', ['payment_id'=>$paymentId, 'uid'=> $row->id, 'log_date'=>$now, 'type'=> 'H-'.$diff]);
			//echo $this->db->insert_id().'-';
		}else{
			//echo 'udah ada';
		}
      }
    }
  }
  
  public function invoiceEmailTemplate($reminderId){
    if(empty($reminderId)) return null;
    $q= $this->db->query("SELECT b.uid, b.transaction_id, b.created, b.type, b.valid_until, c.description, b.amount, b.plan, b.status FROM general_reminder a JOIN ".PAYMENT_HISTORY." b ON a.payment_id =b.id JOIN ".PACKAGES." c ON b.package=c.id WHERE a.id=? ORDER BY b.id DESC", [$reminderId]);
//    '.$q->row()->description.'
    
    if($q->num_rows() ==0) return null;
    
    $profile = $this->db->get_where(USERS, ['id'=>$q->row()->uid])->row();
    $planId = $q->row()->plan;
    $price = $q->row()->amount;
    $plan =$planId==1 ?'Bulanan':'Tahunan';
    $status = $q->row()->status?"Sudah Dibayar":"Belum Dibayar";
    $statusColor= $q->row()->status?"#0CC27E":"#FF8A9A";
    
    $content ='<br><br>
    <div>
      <table cellspacing="0" cellpadding="0">
        <tbody>
          <tr >
            <td align="left" style="font-weight:bold;font-size:18px; border-bottom: 1px solid #555;">INVOICE</td>
            <td align="right" style="font-weight:bold;font-size:18px;border-bottom: 1px solid #555;">'.$q->row()->transaction_id.'</td>
          </tr>
          <tr>
             <td align="left" style="">
              <b>Dari:</b><br>
              '.$profile->fullname.',<br>
              '.$profile->email.'
             </td>
            <td align="right" style="">
              <b>Kepada:</b><br>
              '.get_option($q->row()->type .'_nama').'<br>
'.strtoupper($q->row()->type).': <b>'.get_option($q->row()->type .'_norek').'</b>
            </td>
          </tr>
          <tr>
             <td align="left" style="">
              <b>Tanggal Invoice:</b><br>
'.$this->convertDateId($q->row()->created, true).'<br><br>
              <b>Tanggal Invoice Kadaluarsa:</b><br>
'.$this->convertDateId($q->row()->valid_until, true).'
             </td>
            <td align="right" style="">
              <b>Tanggal Akun Kadaluarsa:</b><br>
'.$this->convertDateId($profile->expiration_date).'
            </td>
          </tr>
          <tr>
        </tbody>
      </table>
      <table style="">
        <thead>
          <tr><th align="left" colspan="4" style="padding:5px; background-color: #f5f5f5; border: 1px solid #ddd; ">Order Summary</th></tr>
           <tr>
             <td style="border-bottom: 1px solid #ddd;"><strong>Paket</strong></td>
              <td align="center" style="border-bottom: 1px solid #ddd;"><strong>Deskripsi</strong></td>
              <td align="center" style="border-bottom: 1px solid #ddd;"><strong>Tipe</strong></td>
              <td align="right" style="border-bottom: 1px solid #ddd;"><strong>Harga</strong></td>
           </tr>
        </thead>
        <tbody>
          <tr>
            <td style="border-bottom: 1px solid #ddd;">Personal</td>
            <td align="center" style="border-bottom: 1px solid #ddd;">'.$q->row()->description.'</td>
            <td align="center" style="border-bottom: 1px solid #ddd;">'.$plan.'</td>
            <td align="right" style="border-bottom: 1px solid #ddd;">'.$price.'</td>
          </tr>
          <tr>
            <td colspan="2" style="border-bottom: 1px solid #ddd;"></td>
            <td align="center" style="border-bottom: 1px solid #ddd;"><strong>Total</strong></td>
            <td align="right" style="border-bottom: 1px solid #ddd;">'.$price.'</td>
          </tr>
          <tr >
            <td colspan="2" style="border-bottom: 1px solid #ddd;"></td>
            <td align="center" style="border-bottom: 1px solid #ddd;"><strong>Status</strong></td>
            <td align="right" style="border-bottom: 1px solid #ddd; padding:10px;"><span style="font-weight:bold; font-size:16px; padding:5px; background-color: '.$statusColor.'; color: #fff;">'.$status.'</span></td>
          </tr>
        </tbody>
      </table>
    </div>
    ';
    return $content;
  }
  
  private function getUniqNumber($bank, $amount){
    while(true){
      $a = rand(1,200);
      $a = $amount+$a;
      $q = $this->db->get_where(PAYMENT_HISTORY, ['type'=>$bank, 'amount'=>$a, 'valid_until >=', date('Y-m-d H:i:s')]);
      if($q->num_rows() ==0)
      return $a; 
    }
    
  }
  
  private function convertDateId($date, $includeHari=false){
    $ts = strtotime($date);
    $bulan = array (
      1 =>   'Januari',
      'Februari',
      'Maret',
      'April',
      'Mei',
      'Juni',
      'Juli',
      'Agustus',
      'September',
      'Oktober',
      'November',
      'Desember'
    );
    $hari = array (
      'Minggu',
      'Senin',
      'Selasa',
      'Rabu',
      'Kamis',
      'Jum\'at',
      'Sabtu'
    );
    $o = $hari[date('w', $ts)] .', '. date('j', $ts) . ' ' . $bulan[ date('n', $ts) ] . ' ' . date('Y', $ts);
    if($includeHari) $o .= ', '. date('H:i', $ts).' WIB'; 
      return $o;
  }
  
  
  public function sendReminder(){
	set_time_limit(200);
	$q = $this->db->get_where('general_reminder', ['log_sent'=>NULL]);
	if($q->num_rows() > 0){
	  foreach($q->result() as $row){
		if($row->type=="H-1"){
			$subject = get_option("email_renewal_reminders_subject_h1", "");
			$message = get_option("email_renewal_reminders_content_h1", "");
		}else{
			$subject = get_option("email_renewal_reminders_subject", "");
			$message = get_option("email_renewal_reminders_content", "");
		}
		$invoiceDetail = (strpos($message, '{invoice_detail}') !==false)? $this->invoiceEmailTemplate($row->id):'';
		$message = str_replace("{invoice_detail}", $invoiceDetail, $message);
		$this->model->send_email($subject, $message, $row->uid);
		$this->db->update('general_reminder', ['log_sent'=> date('Y-m-d H:i:s')], ['uid'=>$row->uid]);
	  }
	}
  }
  
	public function checkPayment(){
		set_time_limit(100);
		$maxDay=1;
		$result['ok']=0;
		$found=0;
		if(session('uid')!=1 && !is_cli()){
			//$result['msg'] ='access denied';
			//ms($result);
		}
		
		$listbank = ['bca', 'bni', 'mandiri', 'bri'];
		for($i=0;$i<count($listbank);$i++){
			if(get_option($listbank[$i].'_enable') !=1) unset($listbank[$i]);
		}
		
		if(empty($listbank)){
			$result['msg'] ='Tidak ada bank yang aktif';
			ms($result);
		}
		$list=[];
		$q= $this->db->from(PAYMENT_HISTORY)->where('status', 0)->where('valid_until >', date('Y-m-d H:i:s'))->where_in('type',$listbank)->get();
		if($q->num_rows() >0){
			foreach($q->result() as $row){
				if(in_array($row->type, $listbank)){
					$list[$row->type][] = $row;
				}
			}
			
			if(!empty($list)){
				$t = time();
				$result['msg'] ='';
				foreach($list as $bank=>$item){
					$this->load->library($bank.'api', NULL, 'mybank');
					$mutasi = $this->mybank->getMutasi(date('Y-m-d', $t-($maxDay*86400)), date('Y-m-d', $t))->getOutput();
					if(!empty($mutasi)){
						foreach($item as $l){
							foreach($mutasi as $m){
								if($m['type']=='kredit'){
									if($m['jumlah']==$l->amount){
										if(!empty($l->ref_uid) && $l->ref_claim==0 ){
											$this->send_withdraw($l->ref_uid, $l->amount);
										}
										$this->db->update(PAYMENT_HISTORY, ['status'=>1, 'ref_claim'=>1],['ids'=>$l->ids]);
										$package = $this->model->get("*", PACKAGES, "id = '".$l->package."'  AND status = 1");
										$user = $this->db->get_where(USERS, ['id'=>$l->uid])->row();
										if(!empty($package)){
											Modules::run('payment/bank/update_package', $package, $l->plan, $l->uid);
											$qUser = $this->db->get_where(USERS, ['id'=>$l->uid]);
											if($qUser->num_rows()==1){
												$this->load->library('Kirim_email_api', null, 'ek');
												$this->ek->create_subscriber($qUser->row()->email, $qUser->row()->fullname, 'paid');
												$this->ek->delete_subscriber($qUser->row()->email, 'register');
												$this->ek->delete_subscriber($qUser->row()->email, 'expired');
											}
											
											//$this->update_package($package, $l->plan, $l->uid);
											if(get_option("email_payment_enable", "")){
												$this->model->send_email(get_option("email_payment_subject", ""), get_option("email_payment_content", ""), $l->uid);
											}
										}
										$found++;
									}
								}
							}
						}
						$result['ok']=1;
						$result['found']=$found;
					}else{
						$result['msg'] .= "mutasi $bank kosong. ".PHP_EOL;
					}
				}
			}
		}else{
			$result['msg']='no pending payment';
		}
		ms($result);
	}

	public function send_withdraw($uid, $amount){
		$q = $this->db->get_where('general_referal', ['uid'=>$uid]);
		if($q->num_rows()==1){
			$amount = 1000 * floor($amount/1000);
			$money = $amount * $q->row()->percent /100;
			$this->db->update('general_referal', ['balance'=> 'balance+'.$money], ['uid'=>$uid]);
			$this->db->query('UPDATE general_referal SET balance= balance+? WHERE uid=?',[$money,$uid]);
		}
	}
}