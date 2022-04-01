<?php
require "bni/Bni_lib.php";

class bniapi{
    private $output;
    private $bni;
    private $user;
    private $pass;
    private $norek;
  
    public function __construct(){
		$this->user = get_option('bni_username');
		$this->pass = get_option('bni_password');
		$this->norek = get_option('bni_norek');

		if(!empty($this->user) && !empty($this->pass) && !empty($this->norek)){
			$this->bni = new Bni_lib();
		}else{
			die('input empty');
		}
    }
    
    public function getMutasi($dari, $ke){
		$this->bni->login($this->user, $this->pass);
		$output = $this->bni->get_transactions($this->norek, date('d-m-Y', strtotime($dari)), date('d-m-Y', strtotime($ke)));
		$this->bni->logout();
		if(!empty($output)){
			foreach($output as $transaksi){
				$this->output[] =[
					'tanggal'=> $transaksi['tanggal'],
					'jumlah'=> !empty($transaksi['debet'])?$transaksi['debet']:$transaksi['kredit'],
					'type'=> !empty($transaksi['debet'])?'debet':'kredit'
				];
			}
		}
		
		return $this;
    }
  
    public function getOutput(){
		return $this->output;
    }
}
