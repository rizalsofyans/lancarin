<?php
require "bca/BCAParser.php";

class bcaapi{
    private $output;
    private $bca;
    private $user;
    private $pass;
    private $norek;
  
    public function __construct(){
      $this->user = get_option('bca_username');
      $this->pass = get_option('bca_password');
      $this->norek = get_option('bca_norek');
      if(!empty($this->user) && !empty($this->pass))
        $this->bca = new BCAParser($this->user, $this->pass);
      else
        die();
    }
    
    public function getMutasi($dari, $ke){
		$output = $this->bca->getListTransaksi($dari, $ke);
		$this->bca->logout();
		if(!empty($output)){
			$current_month = date('m'); 
			foreach($output as $transaksi){
			$expDate = explode('/', $transaksi['date']);
				if(count($expDate) <2) {
					$expDate = [date('d'), date('m')];
				}
				$tgl = $expDate[0];
				$bln = $expDate[1];
				$thn = ($current_month=='01' && $bln=='12')?date('Y', strtotime('-1 year')):date('Y');
				
				$exp = explode('.', end($transaksi['description']));
				$jumlah = str_replace(',','',$exp[0]);
				
				$this->output[] =[
					'tanggal'=> implode('-', [$thn, $bln, $tgl]),
					'jumlah'=> $jumlah,
					'type'=> $transaksi['flows']=='CR'?'kredit':'debet'
				];
			}
		}
		return $this;
    }
  
    public function getOutput(){
		return $this->output;
    }
}
