<?php
require "mandiri/Mandiribaru_lib.php";

class mandiriapi{
    private $output;
    private $mandiri;
	private $user;
    private $pass;
    private $norek;
  
    public function __construct(){
		$this->user = get_option('mandiri_username');
		$this->pass = get_option('mandiri_password');
		$this->norek = get_option('mandiri_norek');
		if(!empty($this->user) && !empty($this->pass) && !empty($this->norek)){
				$this->mandiri = new Mandiribaru_lib();
			}else{
				die();
			}
    }
    public function getMutasi($dari, $ke){
		$output = $this->mandiri->get_transactions($this->user, $this->pass, $this->norek, $dari, $ke);
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
