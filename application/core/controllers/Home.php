<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
	{
	
			parent::__construct();
			// $this->load->helper("langs");
			// $this->load->helper("IsLogedin","Paymust");
			$this->load->helper(
				array('langs', 'IsLogedin', 'Paymust')
		);
			$this->load->model('comman_model');
			$this->load->model('Home_model');
			LoadLang();
			//LoadLang();
			// Your own constructor code
	}

	public function index()
	{
		loginRedirect(base_url()."Dashboard");
		$this->load->view('index');
	}


	// public function dashboard(){
	// 	///check login
		
	// 	Checklogin(base_url());
	// 	Pay_must();

	// 	$sid = $_SESSION['id'];
	// 	$boss_id = $_SESSION['boss_id'];
	// 	$shop_id = $_SESSION['shop_id'];
	// 	$uid = $_SESSION['id'];
	// 	$shopo =  $_SESSION['shop_id'];
	// 	$typo =  $_SESSION['type'];


	// 	$aSetupDB=$this->comman_model->get_data_where("signup","id",$uid);
	// 	$data["aSetupFromDb"] = $aSetupDB['aSetup'];

	// 	//$uid = $_SESSION['id'];
	// 	//capitalcount
	// 	$capital=$this->comman_model->get_data_where("capital","boss_id",$boss_id);
	// 	$capitalCount=$this->comman_model->get_dataCount_where("capital","boss_id",$boss_id);
	// 	$data["capitalCount"]=$capitalCount;
	// 	//bank count
	// 	$bankCount=$this->comman_model->get_dataCount_where("bank","boss_id",$boss_id);
	// 	$data["bankCount"] = $bankCount;
	// 	//transaction count
	// 	$TransCount=$this->comman_model->get_dataCount_where("transactions","user_id",$sid);
	// 	$data["TransCount"] = $TransCount;
	// 	//expenses count
	// 	$ExpensesCount=$this->comman_model->get_dataCount_where("expenses","boss_id",$boss_id);
	// 	$data["ExpensesCount"] = $ExpensesCount;


	// 	///get user data
	// 	$fetchUsers_data=null;
	// 	if($typo == "admin"){
	// 		$fetchUsers_data=$this->comman_model->get_data_where("signup","shop_id",$shopo);
	// 	}else{
	// 		$fetchUsers_data=$this->Home_model->get_id_fromSignup($sid);
	// 	}
	// 	//print_r($fetchUsers_data);exit;
	// 	$data["FetchUserID"] =$fetchUsers_data["id"];
	// 	$gfid=$fetchUsers_data["id"];

	// 	//$vpsql = "SELECT DISTINCT cos_id FROM cos_transactions WHERE user_id=:sid";
	// 	//get transaction cost id
	// 	$transData=$this->comman_model->get_data_where("cos_transactions","user_id",$gfid);
	// 	$data["cosid"]=$transData["cos_id"];
	// 	$cos_id=$transData["cos_id"];
		
	// 	///customer name 
	// 	$CustomQuery="SELECT name FROM costumers WHERE user_id= $gfid AND main_id= $cos_id AND name !='casher'";
		
	// 	$customerData=$this->Home_model->get_data_by_query($CustomQuery);
	// 	$data["name"]=$customerData["name"];
	// 	//treasury data
	// 	$treasuryData=$this->comman_model->get_data_where("treasury","user_id",$gfid);
	// 	$data["treasuryData"]=$treasuryData;		
	// 	/////ARCHIVE START
	// 	$ArchiveData=null;
	// 	if($typo == "admin"){
	// 		$ArchiveData=$this->comman_model->get_data_where("signup","shop_id",$shopo);
	// 	}else{
	// 		$ArchiveData=$this->Home_model->get_id_fromSignup($sid);
	// 	}
	// 	$data["Archive_id"]=$ArchiveData["id"];
	// 	$gfid = $ArchiveData["id"];
	// 	///////
	// 	$delo = "0";
	// 	$invest = lang('invest');
	// 	$yy = date('Y');
	// 	$ryt = "$yy-01-01";
	// 	$ryd = "$yy-12-31";
	// 	///data transactions
	// 	$vpsql = "SELECT * FROM transactions WHERE user_id= $gfid AND hide= '$delo' AND type!= '$invest' AND (datepost BETWEEN '".$ryt."' and '".$ryd."') ORDER BY datepost DESC";
	// 	$infoData=$this->Home_model->get_all_data_by_query($vpsql);
	// 	$data["info"]=$infoData;
		
	// 	/////////////////
	// 	$vpsql = "SELECT DISTINCT kind FROM treasury WHERE shop_id=$shop_id AND kind !='LYD' AND tyi='cash'";
	// 	$tresdata=$this->Home_model->get_all_data_by_query($vpsql);
	// 	$array=array();
	// 	foreach($tresdata as $item){
	// 		//$array[]= $item["kind"];
	// 		$numberya = $item["kind"];
	// 		$vpsql = "SELECT * FROM transactions WHERE user_id=$sid AND given_name = '$numberya' ";
	// 		$transdata=$this->Home_model->get_all_data_by_query($vpsql);
	// 		$array["kinds"]["kind"]=$item["kind"];
	// 		$array["kinds"][$item["kind"]] = $transdata;
	// 	}
	// 	$data["graph"]=$array;


	// 	///graph cash lyd data
	// 	$vpsql = "SELECT kind, number FROM treasury WHERE shop_id = $shop_id AND tyi='cash' AND kind='LYD'";
	// 	$Cashdata=$this->Home_model->get_all_data_by_query($vpsql);
	// 	$data["CashLYD"]=$Cashdata;

	// 	///graph cash EU data
	// 	$vpsql = "SELECT kind, number FROM treasury WHERE shop_id= $shop_id AND tyi='cash' AND kind='EU'";
	// 	$CashEu=$this->Home_model->get_all_data_by_query($vpsql);
	// 	$data["CashLEU"]=$CashEu;
	// 	//graph CASH USD
	// 	$vpsql = "SELECT kind, number FROM treasury WHERE shop_id=$shop_id AND tyi='cash' AND kind='USD'";
	// 	$CashUSD=$this->Home_model->get_all_data_by_query($vpsql);
	// 	$data["CashUSD"]=$CashUSD;
	// 	//graph CASH GB
	// 	$vpsql = "SELECT kind, number FROM treasury WHERE shop_id=$shop_id AND tyi='cash' AND kind='GBP'";
	// 	$CashGB=$this->Home_model->get_all_data_by_query($vpsql);
	// 	$data["CashGB"]=$CashGB;
	// 	$data["dircheckPath"]= base_url()."Asset/";
	// 	//load currencies
	// 	$this->load->helper("Currencynodes");
	// 	$data["currencies_b"]=Currencies_b();
	// 	$data["currencies_a"]=Currencies_a();

	// 	// foreach($array as  $postsfetch ) {
    //     //       print_r($postsfetch);          
	// 	//  	//$numberya = $postsfetch; //$postsfetch['kind'];
	// 	// }
	// 	// exit;
	// 	// echo $data["kind"]=$tresdata[0]["kind"];exit;

	// 	$this->load->view('home/home',$data);
	// }
	//pay screen
	public function pay()
	{

		Checklogin(base_url());
		$data["dircheckPath"]= base_url()."Asset/";    
		$this->load->view('home/pay',$data);
	}
}
