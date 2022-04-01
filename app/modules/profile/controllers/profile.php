<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class profile extends MX_Controller {

	private  $newUser=true;
	
	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		Modules::run('dashboard/checkPaymentStatus');
		$data = array(
			"account" => $this->model->get_profile(),
			'paymentHistory'=> $this->getPaymentHistory()
		);

		$this->template->build('index', $data);
	}
	
	public function login_as_admin(){
		if(session('tmp_uid') != 1) redirect();
		$tmp_uid = session('tmp_uid');
		
		//login user
		set_session('uid', $tmp_uid);
		
		//save user admin
		unset_session('tmp_uid');
		
		redirect('users');
	}

	
	public function clean_apps(){
		$q = $this->db->select('id, fullname, expiration_date')->get_where(USERS, ['expiration_date <'=>NOW]);
		if($q->num_rows()==0) die('kosong');
		$deleteDay =  time() - (86400 * get_option('delete_day_after_expired', 365));
		
		$recalculate = false;
		foreach($q->result() as $row){
			$uid = $row->id;
			if(strtotime($row->expiration_date)<$deleteDay){
				$this->db->delete(USERS, ['id'=>$id]);
			}
			if($this->db->limit(1)->get_where('instagram_accounts', ['uid'=>$uid])->num_rows()>0){
				$this->db->delete('instagram_accounts', ['uid'=>$uid]);
				$recalculate=true;
			}
			if($this->db->limit(1)->get_where(FILE_MANAGER, ['uid'=>$uid])->num_rows()>0){
				$this->db->delete(FILE_MANAGER, ['uid'=>$uid]);
				exec("rm ".FCPATH ."assets/uploads/user".$uid."/*");
				file_put_contents('assets/uploads/user'.$uid.'/index.html', '');
			}
		}
		
		if($recalculate){
			Modules::run('instagram/activity/cron/autoAssignProxy');
		}
	}

	
	public function ajax_update_account(){
		$fullname = post("fullname");
		$email    = post("email");
		$whatsapp    = post("whatsapp");
		$email_error    = (int) post("email_error");
		$timezone = TIMEZONE;

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
		
		if(!preg_match('/08+[0-9]{8,10}/',$whatsapp)){
			ms(array(
				"status"  => "error",
				"message" => "please enter valid whatsapp number"
			));
		}

		if(!filter_var(post("email"), FILTER_VALIDATE_EMAIL)){
		  	ms(array(
				"status"  => "error",
				"message" => lang("email_address_in_invalid_format")
			));
		}

		//
		$user_check = $this->model->get("id", USERS, "email = '{$email}' AND id != '".session("uid")."'");
		if(!empty($user_check)){
			ms(array(
				"status"  => "error",
				"message" => lang("this_email_already_exists")
			));
		}
/*
		$check_timezone = 0;
		foreach (tz_list() as $key => $value) {
			if($timezone == $value['zone']){
				$check_timezone = 1;
			}
		}

		if(!$check_timezone){
			ms(array(
				"status"  => "error",
				"message" => "Timezone is required"
			));
		}
*/
		$this->db->update(USERS, array(
			"fullname" => $fullname,
			"email" => $email,
			"timezone" => $timezone,
			"whatsapp"=>$whatsapp,
			"email_error"=>$email_error
		), array("id" => session("uid")));

		Modules::run('users/update_user_kirim_email', session('uid'));
		
		ms(array(
			"status"  => "success",
			"message" => lang("successfully")
		));
	}

	public function ajax_change_password(){
		$password = post("password");
		$confirm_password = post("confirm_password");

		if(strlen($password) < 6){
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

		$this->db->update(USERS, array(
			"password" => md5($password)
		), array("id" => session("uid")));

		ms(array(
			"status"  => "success",
			"message" => lang("successfully")
		));
 	}
	
	private function getPaymentHistory(){
    $tbl='';
    $q= $this->db->limit(10)->order_by('id', 'desc')->get_where(PAYMENT_HISTORY, ['uid'=>session('uid')]);
    
    if($q->num_rows() >0){
		foreach($q->result() as $row){
			if($row->status==1) $this->newUser=false;
			$status =($row->status==1) ? '<span class="text-success"><b>Paid</b></span>' : '<span class="text-danger"><b>Unpaid</b></span>';
			$tbl .= '<tr><td><a class="" href="'.site_url('profile/invoice/'. $row->transaction_id).'" data-id="'.$row->id.'">'.$row->transaction_id.'</a></td><td>'.$status.'</td></tr>';
      }
    }else{
      $tbl .= '<tr><td colspan="2"></td></tr>';
    }
    return $tbl;
  }
	
	public function checkHaveFirstPayment(){
		$this->getPaymentHistory();
		$result['ok']= ($this->newUser) ? 0:1;
		ms($result);
	}
	
	public function confirmPayment(){
    $result['ok']=0;
    $nama = post('nama');
    $norek = post('norek');
    $bank = post('bank');
    $nilai = post('nilai');
    $invoice = post('invoice');
    $bukti = post('bukti');
    $keterangan = post('keterangan');
    if(!empty('nama') ||!empty('bank') ||!empty('norek') ||!empty('invoice')){
      $q = $this->db->get_where(PAYMENT_HISTORY, ['transaction_id'=>$invoice]);
      if($q->num_rows()==1){
        $profile = $this->model->get_profile();
        $subject = 'Konfirmasi Pembayaran '.$invoice;
        $nilai = empty($nilai)?'sama':$nilai;
        $content = $profile->fullname .' ingin melakukan konfirmasi pembayaran, sebagai berikut:<br><br>
        <b><u>Informasi Akun :</u></b>
        <ul>
        <li><b>Id akun : </b>'.$profile->id.'</li>
        <li><b>Nama akun : </b>'.$profile->fullname.'</li>
        <li><b>Email akun : </b>'.$profile->email.'</li>
        </ul>
        <b><u>Informasi Tagihan :</u></b>
        <ul>
        <li><b>Invoice : </b><a href="'.site_url('profile/invoice/'.$invoice).'">'.$invoice.'</a></li>
        <li><b>Tagihan Invoice : </b>'.$q->row()->amount.'</li>
        <li><b>Bank Tujuan: </b>'.strtoupper($q->row()->type).'</li>
        <li><b>Rekening Tujuan : </b>'.get_option($q->row()->type .'_norek').'</li>
        <li><b>Nama Tujuan : </b>'.get_option($q->row()->type .'_nama').'</li>
        </ul>
        <b><u>Klaim Pembayaran :</u></b>
        <ul>
        <li><b>Bank Asal: </b>'.strtoupper($bank).'</li>
        <li><b>Rekening Asal : </b>'.$norek.'</li>
        <li><b>Nama Pemilik Asal : </b>'.$nama.'</li>
        <li><b>Jumlah : </b>'.$nilai.'</li>
        <li><b>Keterangan : </b>'.$keterangan.'</li>
        </ul><br>
        Terima kasih.
        ';
        
        $attachment =[];
        if(isset($_FILES['bukti']['tmp_name']) && file_exists($_FILES['bukti']['tmp_name']) && strpos($_FILES['bukti']['type'], 'image/') !== false){
          $newpath = 'assets/tmp/'. $_FILES['bukti']['name'];
          move_uploaded_file($_FILES['bukti']['tmp_name'], $newpath);
          $attachment[] = $newpath;
        }
        $this->model->send_email($subject, $content, 1, $attachment);
        @unlink($newpath);
        $result['ok']=1; 
      }else{
        $result['msg'] = 'Nomor invoice / transaksi tidak ditemukan';
      }
    }else{
      $result['msg']='Mohon isi input yang diperlukan';
    }
    ms($result);
  }
  
  public function invoice($invoiceId=null){
		$q = $this->db->select('a.type, b.id, a.transaction_id, b.fullname, b.email, b.expiration_date, a.created, c.name, c.description, a.plan, c.price_monthly, c.price_annually, a.valid_until, a.status AS payment_status, a.amount')->from(PAYMENT_HISTORY .' a')->join(USERS .' b','a.uid=b.id')->join(PACKAGES.' c','c.id=a.package')->where('transaction_id', $invoiceId)->get();
		if($q->num_rows()==0) redirect('profile');
		if(session('uid') != 1 || session('uid') != $q->row()->id);
		//$price = $q->row()->plan==1 ? $q->row()->price_monthly : 12* $q->row()->price_annually;
		$price = $q->row()->amount;
		$price = get_option('payment_currency') .' '. number_format($price, 0, ',','.');
		
		$data = array(
		  "transaction_id"=> $q->row()->transaction_id,
		  "fullname"=> $q->row()->fullname,
		  "user_id"=> $q->row()->id,
		  "email"=> $q->row()->email,
		  "package_description"=> $q->row()->description,
		  "package_name"=> $q->row()->name,
		  "plan" => $q->row()->plan==1 ? 'Bulanan':'Tahunan',
		  "price"=> $price,
		  'bank'=> $q->row()->type,
		  "created"=> $this->convertDateId($q->row()->created, true),
		  "invoice_expired"=> $this->convertDateId($q->row()->valid_until, true),
		  "expired"=> $this->convertDateId($q->row()->expiration_date),
		  'status'=> $q->row()->payment_status
		);
		
		$q= $this->db->get_where(USERS, ['id'=>session('uid'), 'package'=>1]);
		if($q->num_rows() ==0)
		$this->template->build('invoice', $data);
		else $this->load->view('invoice_standalone',$data);
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
  public function user_payment_history(){
		$data = array(
			"account" => $this->model->get_profile(),
			'paymentHistory'=> $this->getPaymentHistory()
		);
		if($this->newUser)
		$this->load->view('user_payment_history', $data);
		else redirect('profile');
	}

}