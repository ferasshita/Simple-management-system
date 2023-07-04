<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {

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
				array('langs', 'IsLogedin', 'Paymust','timefunction','Mode','User','Currencynodes')
		);
			$this->load->model('comman_model');
			$this->load->model('Home_model');
			LoadLang();
			//LoadLang();
			// Your own constructor code
			Checklogin(base_url());
			Pay_must();
			
	}




	public function cash()
	{
	
		$mode=LoadMode();
        
        $data["layoutmode"]  =   $mode;
		//treasury
		$typem =  $_SESSION['type'];
        $sid =  $_SESSION['id'];
        $shop_id = $_SESSION['shop_id'];
        $boss_id = $_SESSION['boss_id'];
		$shopo =  $_SESSION['shop_id'];
		$typo =  $_SESSION['type'];

        if($typem == "boss"){
            $gfid = $boss_id;
        }elseif($typem == "admin"){
            $gfid = $shop_id;
        }else{
            $gfid = $shop_id;
        }
        $delk = "0";
        $bgh = "cash";

		$vpsql = "SELECT * FROM treasury WHERE (kind='LYD' OR kind='USD') AND (tyi= '$bgh' AND shop_id=$gfid OR tyi= '$bgh' AND boss_id= $gfid)";

		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//print_r($FetchedData);exit;
		$data["treasury"]=$FetchedData;

		//////////////////////////////////////
		if($typo == "admin")
		{
				$fetchUsers_sql = "SELECT id FROM signup WHERE shop_id='$shopo'";
		}else
		{
				$fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$sid' OR id='$sid'";		
		}
		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$data["signups"]=$FetchedData;
		$costumerarray=array();
		foreach($FetchedData as $item){
			$gfid = $item['id'];
			$vpsql = "SELECT DISTINCT cos_id FROM cos_transactions WHERE user_id= $gfid";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			foreach($FetchedData as $items){
				
				$cos_id=$items["cos_id"];
				$costumerarray["costrans"][$gfid][]=$items;

				$vpsql = "SELECT name FROM costumers WHERE user_id=$gfid AND main_id = $cos_id AND name !='casher'";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				foreach($FetchedData as $rows){
					$costumerarray["costumers"][$cos_id][]=$rows;
				}
			}

		}
		
		$data["pagedata"]=$costumerarray;
		//////////////////////////////////////
		///update if sumbmited
		if (isset($_POST['submi_up'])) 
		{
			$billclo = filter_var(htmlspecialchars($_POST['billclo']),FILTER_SANITIZE_STRING);
			//$sid = $_SESSION['id'];
			//$shop_id = $_SESSION['shop_id'];
			//$boss_id = $_SESSION['boss_id'];
			$type= "cash";
			$update_info_sql = "UPDATE transactions SET bill= '$billclo' WHERE user_id= $sid AND type='$type'";
			$IsUpDate=$this->comman_model->run_query($vpsql);
		}


		$sid=$_SESSION['id'];
		if (isset($_POST['for'])) {			
			$this->UpdateCashData($_POST);
		}



	


		$data["dircheckPath"]= base_url()."Asset/";
		
		
	
		
		$gfid=0;
		if($typem == "boss"){
        	$gfid = $boss_id;
        }elseif($typem == "admin"){
        	$gfid = $shop_id;
        }else{
            $gfid = $shop_id;
		}
        $cash = "cash";
		$branch = "LYD";
		
        $vpsql = "SELECT DISTINCT kind FROM capital WHERE boss_id= $gfid AND kind!= '$branch' AND tyi= '$cash' OR shop_id=$gfid AND kind!='$branch' AND tyi = '$cash' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//$numcoss = $view_posts->rowCount();
		$array=array();
		$mediaResult=0;
		$ghj="";
		foreach($FetchedData as $item){
			$ty_uy=0;
			$ty_ji=0;
			$ghj = $item['kind'];
			$vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE boss_id=$gfid AND kind= '$ghj' AND tyi= '$cash' OR shop_id= $gfid AND kind=  '$ghj' AND tyi= '$cash'";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			//$num = $view_postsi->rowCount();
			$ty_uy=$FetchedData[0]['ty_uy'];
			//print_r($FetchedData);
			$vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE boss_id= $gfid AND kind='$ghj' AND tyi= '$cash'
			 OR shop_id=$gfid AND kind= '$ghj' AND tyi= '$cash' ";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			//$num = $view_postsi->rowCount();
			//print_r($FetchedData);
			$ty_ji=$FetchedData[0]["ty_ji"];

			$mediaResult = $ty_uy/$ty_ji;
			
		}
		$data["ghj"]=$ghj;
		$data["media"]=$mediaResult;



		//$sid= $_SESSION['id'];
		$vpsql ="SELECT * FROM treasury WHERE user_id =$sid";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasuredata"]=$FetchedData;
		////get all transactions
		$typey = "0";
		$typeu = "cash";
        $vpsql = "SELECT * FROM transactions WHERE user_id=  $sid AND bill =  '$typey' AND hide= '$typey' AND type= '$typeu' ORDER BY datepost DESC";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["transactions"]=$FetchedData;
        
		//
		$transArray=array();
		foreach($FetchedData as $item){
			$chak_id = $item['chak_id'];
			$vpsql = "SELECT * FROM cos_transactions WHERE post_id=$chak_id";
			$CosData=$this->comman_model->get_all_data_by_query($vpsql);
			$transArray["transactions"]=$item;
			foreach($CosData as $cositem){
				$cos_id = $cositem['cos_id'];
				$transArray["transactions"][$chak_id]=$cositem;
				$vpsql = "SELECT * FROM costumers WHERE main_id=$cos_id";
				$CustData=$this->comman_model->get_all_data_by_query($vpsql);
				foreach($CustData as $cusitem){
					$transArray["transactions"][$chak_id][$cos_id]=$cusitem;
				}
				
			}
		}

		$data["printtransactinos"]=$transArray;


		///get treasury
		
		if($typem == "boss"){
			$gfid = $boss_id;
		}elseif($typem == "admin"){
			$gfid = $shop_id;
		}else{
			$gfid = $shop_id;
		}
		$delk = "0";
		$bgh = "cash";

		//============total of the money=============================
		$vpsql = "SELECT * FROM treasury WHERE tyi='$bgh' AND shop_id=  $gfid OR tyi= '$bgh' AND boss_id= $gfid";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasry"]=$FetchedData;
		//$data["treasury"]=$FetchedData;


		/////////////////
		$boss_id = $_SESSION['boss_id'];
		$vpsql = "SELECT * FROM my_bank WHERE boss_id= $boss_id";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//$data["mybank"]=$FetchedData;

		$array=array();
		
		foreach($FetchedData as $postsfetch){
			$idn = $postsfetch['id'];
			$bank_name = $postsfetch['bank_name'];
			//============total of the money=============================
			$vpsql = "SELECT * FROM treasury WHERE wh=$idn";
			$transdata=$this->comman_model->get_all_data_by_query($vpsql);
			$array["bank"][] = $postsfetch;
			//$array["bank"][$idn] = $transdata;
			foreach($transdata as $item){
				$array["bank"][$idn][] = $item;
			}
			
			
		}
		
		$data["bank"]=$array;
		//////////////////////////////
		$data["active"] = "cash";

		$data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
		$this->load->view('Transaction/Cash', $data);
	}

	public function updatetrans(){
		echo $billclo = filter_var(htmlspecialchars($_POST['billclo']),FILTER_SANITIZE_STRING);
	
		$sid = $_SESSION['id'];
		$shop_id = $_SESSION['shop_id'];
		$boss_id = $_SESSION['boss_id'];
		$type= "cash";

		$data = array(
			'bill'   => $billclo			
		);
		$where=array('user_id' => $sid);
		$update_info=$this->comman_model->update_entry("transactions",$data,$where);
		$url=base_url()."Transaction/cash";
		header("Location: $url");
        exit;
	}

    public function Bonds(){
        $typem =  $_SESSION['type'];
        $sid =  $_SESSION['id'];
        $shop_id = $_SESSION['shop_id'];
        $boss_id = $_SESSION['boss_id'];
        if($typem == "boss"){
            $gfid = $boss_id;
        }elseif($typem == "admin"){
            $gfid = $shop_id;
        }else{
            $gfid = $shop_id;
        }
        $delk = "0";
        $bgh = "cash";

		$getCount=0;

		$vpsql = "SELECT * FROM treasury WHERE (kind='LYD' OR kind='USD') AND (tyi= '$bgh' AND shop_id=$gfid OR tyi= '$bgh' AND boss_id= $gfid)";

		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//print_r($FetchedData);exit;
		$data["treasury"]=$FetchedData;
		
		

        $mode=LoadMode();
        $data["dircheckPath"]= base_url()."Asset/";
        $data["layoutmode"]  =   $mode;

		//$this->load->helper("Currencynodes");
		$data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
		//////////////////////////

		
		if (isset($_POST['for'])) {
			$this->UpdateBondData($_POST);
		}


		// $Udata=GetIdofUser();
		// $gfid = $Udata[0]['id'];
		
		// $vpsql = "SELECT DISTINCT cos_id FROM cos_transactions WHERE user_id= $gfid";
		// $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		
		// $cos_id=$FetchedData[0]["cos_id"];

		// $vpsql = "SELECT name FROM costumers WHERE user_id=$gfid AND main_id = $cos_id AND name !='casher'";		
		// $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		
		// $data["costumers"]= $FetchedData;



		//////////////////////////////////////
		if($typo == "admin")
		{
				$fetchUsers_sql = "SELECT id FROM signup WHERE shop_id='$shopo'";
		}else
		{
				$fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$sid' OR id='$sid'";		
		}
		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$data["signups"]=$FetchedData;
		$costumerarray=array();
		foreach($FetchedData as $item){
			$gfid = $item['id'];
			$vpsql = "SELECT DISTINCT cos_id FROM cos_transactions WHERE user_id= $gfid";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			foreach($FetchedData as $items){
				
				$cos_id=$items["cos_id"];
				$costumerarray["costrans"][$gfid][]=$items;

				$vpsql = "SELECT name FROM costumers WHERE user_id=$gfid AND main_id = $cos_id AND name !='casher'";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				foreach($FetchedData as $rows){
					$costumerarray["costumers"][$cos_id][]=$rows;
				}
			}

		}
		
		$data["pagedata"]=$costumerarray;
		//////////////////////////////////////
		


		
		$data["active"] = "chak";

		$usecar =  "1";
		$vpsqlu = "SELECT * FROM bank WHERE boss_id= $boss_id AND usecha='$usecar' ";		
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsqlu);
		$data["banks"]=$FetchedData;

		$branch = "LYD";
		$shopo =  $_SESSION['shop_id'];
		$typo =  $_SESSION['type'];
		$fetchUsers_sql = "SELECT DISTINCT id FROM signup WHERE boss_id='$sid' OR shop_id='$shopo' AND type='admin' OR id='$sid'";
		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$array=array();
		$ty_uy = 0;
		$ty_ji=0;
		$mediaResult=0; 
		$kind="";
		
		foreach($FetchedData as $item ){
			$gfid = $item['id'];
			$array["udata"]=$item;
			$vpsql = "SELECT DISTINCT kind FROM capital WHERE user_id= $gfid AND kind!='$branch'";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			$array["udata"]["KindCount"]=count($FetchedData);
			//$data["datacount"]=count($FetchedData);
			$getCount+=count($FetchedData);
			foreach($FetchedData as $kinditem){
				$array["udata"]["kinds"][$gfid]	=	$kinditem;
				$ghj = $kinditem['kind'];
				$kind=$kinditem['kind'];
				$vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE user_id=$gfid AND kind='$ghj' ";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				$array["udata"]["tycounts"]	=	count($FetchedData);
				foreach($FetchedData as $tyitem){
					$ty_uy = $tyitem['ty_uy'];
				}
				

				$vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE user_id=$gfid AND kind='$ghj'";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				foreach ($FetchedData as $postsfetch) 
				{
					$ty_ji = $postsfetch['ty_ji'];
				}
				$mediaResult = $ty_uy/$ty_ji;
			}
			$data["media"]=$mediaResult;
			
			//$data["dataCount"] = $array["udata"]["KindCount"];
			
			//echo $getCount= $array["udata"]["KindCount"];
			
			$data["ghjikind"]=$kind;
			//$numcoss = $view_posts->rowCount();
		}

		$vpsqlu = "SELECT * FROM my_bank WHERE boss_id=$boss_id AND usechav= '$usecar' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsqlu);
		$data["mybank"]=$FetchedData;

		$check ="SELECT * FROM treasury WHERE user_id =$sid";
		$FetchedData=$this->comman_model->get_all_data_by_query($check);
		$data["treasurydata"]=$FetchedData;

		$sid =  $_SESSION['id'];
		$typeu = "chak";
		$typey = "0";
		$vpsql = "SELECT * FROM transactions WHERE user_id=$sid AND bill= '$typey' AND hide = '$typey' AND type= '$typeu' ORDER BY datepost DESC";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["transdata"]=$FetchedData;

		/////////////////
		$boss_id = $_SESSION['boss_id'];
		$vpsql = "SELECT * FROM my_bank WHERE boss_id= $boss_id";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//$data["mybank"]=$FetchedData;

		$array=array();
		
		foreach($FetchedData as $postsfetch){
			$idn = $postsfetch['id'];
			$bank_name = $postsfetch['bank_name'];
			//============total of the money=============================
			$vpsql = "SELECT * FROM treasury WHERE wh=$idn";
			$transdata=$this->comman_model->get_all_data_by_query($vpsql);
			$array["bank"][] = $postsfetch;
			//$array["bank"][$idn] = $transdata;
			foreach($transdata as $item){
				$array["bank"][$idn][] = $item;
			}
			
			
		}
		
		$data["bank"]=$array;
		//////////////////////////////
		if($typem == "boss"){
			$gfid = $boss_id;
		}elseif($typem == "admin"){
			$gfid = $shop_id;
		}else{
			$gfid = $shop_id;
		}
		$delk = "0";
		$bgh = "cash";

		//============total of the money=============================
		$vpsql = "SELECT * FROM treasury WHERE tyi='$bgh' AND shop_id=  $gfid OR tyi= '$bgh' AND boss_id= $gfid";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasry"]=$FetchedData;




		////get all transactions
		$typey = "0";
		$typeu = "cash";
		
        $vpsql = "SELECT * FROM transactions WHERE user_id=  $sid AND bill =  '$typey' AND hide= '$typey' AND type= '$typeu' ORDER BY datepost DESC";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["transactions"]=$FetchedData;
		//
		$transArray=array();
		foreach($FetchedData as $item){
			$chak_id = $item['chak_id'];
			$vpsql = "SELECT * FROM cos_transactions WHERE post_id=$chak_id";
			$CosData=$this->comman_model->get_all_data_by_query($vpsql);
			$transArray["transactions"]=$item;
			foreach($CosData as $cositem){
				$cos_id = $cositem['cos_id'];
				$transArray["transactions"][$chak_id]=$cositem;
				$vpsql = "SELECT * FROM costumers WHERE main_id=$cos_id";
				$CustData=$this->comman_model->get_all_data_by_query($vpsql);
				foreach($CustData as $cusitem){
					$transArray["transactions"][$chak_id][$cos_id]=$cusitem;
				}
				
			}
		}


			/////////////////
			$boss_id = $_SESSION['boss_id'];
			$vpsql = "SELECT * FROM my_bank WHERE boss_id= $boss_id";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			//$data["mybank"]=$FetchedData;
	
			$array=array();
			
			foreach($FetchedData as $postsfetch){
				$idn = $postsfetch['id'];
				$bank_name = $postsfetch['bank_name'];
				//============total of the money=============================
				$vpsql = "SELECT * FROM treasury WHERE wh=$idn";
				$transdata=$this->comman_model->get_all_data_by_query($vpsql);
				$array["bank"][] = $postsfetch;
				//$array["bank"][$idn] = $transdata;
				foreach($transdata as $item){
					$array["bank"][$idn][] = $item;
				}
				
				
			}
			
			$data["bank"]=$array;
			//////////////////////////////
		
		$data["printtransactinos"]=$transArray;
		$data["dataCount"]=$getCount;
		
		
		//$data["dataCount"] = $array["udata"]["KindCount"];
			
		
		$this->load->view('Transaction/Bond', $data);
	}

	public function UpdateBondData($data){
		$_POST=$data;
		$sid = $_SESSION['id'];
		$shop_id = $_SESSION['shop_id'];
		$boss_id = $_SESSION['boss_id'];
		$exchange = filter_var(htmlentities($_POST['exchange']),FILTER_SANITIZE_STRING);
		$received_name = filter_var(htmlentities($_POST['amousel']),FILTER_SANITIZE_STRING);
		$received = filter_var(htmlentities($_POST['received']),FILTER_SANITIZE_STRING);
		$given_name = filter_var(htmlentities($_POST['amouse']),FILTER_SANITIZE_STRING);
		$amountsd = filter_var(htmlentities($_POST['amountsd']),FILTER_SANITIZE_STRING);
		$selbu = filter_var(htmlentities($_POST['selbu']),FILTER_SANITIZE_STRING);
		$pid = filter_var(htmlentities($_POST['pid']),FILTER_SANITIZE_STRING);

		$vpsql = "SELECT * FROM transactions WHERE post_id=$pid ";
		$CustData = $this->comman_model->get_all_data_by_query($vpsql);
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':pid', $pid, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvf = count($CustData);// $view_posts->rowCount();
		foreach($CustData as $postsfetch) 
		{
			$receivedy = $postsfetch['received'];
			$giveny = $postsfetch['given'];
		}
		$numbj = $received-$receivedy;
		$numbg = $amountsd-$giveny;
		$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$received_name'";
		$CustData = $this->comman_model->get_all_data_by_query($vpsql);

		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvf = count($CustData); // $view_posts->rowCount();
		foreach($CustData as $postsfetch) 
		{
			$numberya = $postsfetch['number'];
		}

		$numberybt = $numberya+$numbj;

		$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$given_name'";
		$CustData = $this->comman_model->get_all_data_by_query($vpsql);		
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvh = count();// $view_posts->rowCount();
		foreach($CustData as $postsfetch) 
		{
			$numberyb = $postsfetch['number'];
		}

		$numberd = $numberyb+$numbg;

		$edit_post_sql = "UPDATE transactions SET exchange= '$exchange',received = '$received',given='$amountsd',received_name = '$received_name',given_name = '$given_name',kin='$selbu' WHERE post_id= $pid ";
		$IsUpdate = $this->comman_model->get_all_data_by_query($edit_post_sql);		

		// $edit_post = $conn->prepare($edit_post_sql);
		// $edit_post->bindParam(':exchange',$exchange,PDO::PARAM_STR);
		// $edit_post->bindParam(':amousel',$received_name,PDO::PARAM_STR);
		// $edit_post->bindParam(':received',$received,PDO::PARAM_INT);
		// $edit_post->bindParam(':amouse',$given_name,PDO::PARAM_INT);
		// $edit_post->bindParam(':amountsd',$amountsd,PDO::PARAM_INT);
		// $edit_post->bindParam(':selbu',$selbu,PDO::PARAM_INT);
		// $edit_post->bindParam(':pid',$pid,PDO::PARAM_INT);
		// $edit_post->execute();

		$iptdbsql = "UPDATE treasury SET number='$numberybt' WHERE shop_id = $shop_id AND kind='$received_name'";
		$IsUpdate = $this->comman_model->get_all_data_by_query($iptdbsql);		
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbero', $numberybt,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
		// $insert_post_toDB->execute();

		//=====================insert the money of given==================================

		$iptdbsql = "UPDATE treasury SET number='$numberd' WHERE shop_id = $shop_id AND kind='$given_name'";
		$IsUpdate = $this->comman_model->get_all_data_by_query($iptdbsql);		
		
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numberb', $numberd,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
		return true;
	}


	public function UpdateCashData($data){
		$sid=$_SESSION['id'];
		$_POST=$data;
		$exchange = filter_var(htmlentities($_POST['exchange']),FILTER_SANITIZE_STRING);
		$received_name = filter_var(htmlentities($_POST['amousel']),FILTER_SANITIZE_STRING);
		$received = filter_var(htmlentities($_POST['received']),FILTER_SANITIZE_STRING);
		$given_name = filter_var(htmlentities($_POST['amouse']),FILTER_SANITIZE_STRING);
		$amountsd = filter_var(htmlentities($_POST['amountsd']),FILTER_SANITIZE_STRING);
		$selbu = filter_var(htmlentities($_POST['selbu']),FILTER_SANITIZE_STRING);
		$pid = filter_var(htmlentities($_POST['pid']),FILTER_SANITIZE_STRING);
	
		$vpsql = "SELECT * FROM transactions WHERE post_id=$pid";
		$FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':pid', $pid, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvf = count($FetchedData);// $view_posts->rowCount();
		foreach($FetchedData as $postsfetch) 
		{
			$receivedy = $postsfetch['received'];
			$giveny = $postsfetch['given'];
		}
		$numbj = $received-$receivedy;
		$numbg = $amountsd-$giveny;
		$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$received_name'";
		$FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvf = count($FetchedData);// $view_posts->rowCount();
		foreach ($FetchedData as $postsfetch) {
			$numberya = $postsfetch['number'];
		}
		$numberybt = $numberya+$numbj;
		$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$given_name'";
		$FetchedData = $this->comman_model->get_all_data_by_query($vpsql);

		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvh = count($FetchedData);// $view_posts->rowCount();
		foreach ($FetchedData as $postsfetch) 
		{
			$numberyb = $postsfetch['number'];
		}
		$numberd = $numberyb+$numbg;
	
		$edit_post_sql = "UPDATE transactions SET exchange= '$exchange',received = '$received',given='$amountsd',received_name = '$received_name', given_name ='$given_name',kin='$selbu' WHERE post_id= $pid";
		$IsUpDate = $this->comman_model->run_query($edit_post_sql);

		// $edit_post = $conn->prepare($edit_post_sql);
		// $edit_post->bindParam(':exchange',$exchange,PDO::PARAM_STR);
		// $edit_post->bindParam(':amousel',$received_name,PDO::PARAM_STR);
		// $edit_post->bindParam(':received',$received,PDO::PARAM_INT);
		// $edit_post->bindParam(':amouse',$given_name,PDO::PARAM_INT);
		// $edit_post->bindParam(':amountsd',$amountsd,PDO::PARAM_INT);
		// $edit_post->bindParam(':selbu',$selbu,PDO::PARAM_INT);
		// $edit_post->bindParam(':pid',$pid,PDO::PARAM_INT);
		// $edit_post->execute();
	
		$iptdbsql = "UPDATE treasury SET number='$numberybt' WHERE shop_id = $shop_id AND kind='$received_name'";
		$IsUpDate = $this->comman_model->run_query($edit_post_sql);
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbero', $numberybt,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
	
		//=====================insert the money of given==================================
	
		$iptdbsql = "UPDATE treasury SET number='$numberd' WHERE shop_id = $shop_id AND kind='$given_name'";
		$IsUpDate = $this->comman_model->run_query($edit_post_sql);

		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numberb', $numberd,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
	}

	public function updatebonds(){
		$billclo = filter_var(htmlspecialchars($_POST['billclo']),FILTER_SANITIZE_STRING);
	
		$sid = $_SESSION['id'];
		$shop_id = $_SESSION['shop_id'];
		$boss_id = $_SESSION['boss_id'];
		$type= "cash";

		$data = array(
			'bill'   => $billclo			
		);
		$where=array('user_id' => $sid);
		$update_info=$this->comman_model->update_entry("transactions",$data,$where);
		$url=base_url()."Transaction/Bonds";
		header("Location: $url");
        exit;
	}

	public function Transfar(){


		$url=base_url()."Dashboard";
		if ($_SESSION['transfar'] == '1') {
			header("location: $url");
		}
		if($_SESSION['package'] == "2" || $_SESSION['package'] == "3" || $_SESSION['package'] == "4" || $_SESSION['admin'] == "1"){
		}else{
			header("location: $url");
		}

		$mode=LoadMode();
		$data["dircheckPath"]= base_url()."Asset/";
        $data["layoutmode"]  =   $mode;
		$data["active"] = "transfar";


		$typem =  $_SESSION['type'];
        $sid =  $_SESSION['id'];
        $shop_id = $_SESSION['shop_id'];
        $boss_id = $_SESSION['boss_id'];
        if($typem == "boss"){
            $gfid = $boss_id;
        }elseif($typem == "admin"){
            $gfid = $shop_id;
        }else{
            $gfid = $shop_id;
        }
        $delk = "0";
        $bgh = "cash";

		$vpsql = "SELECT * FROM treasury WHERE (kind='LYD' OR kind='USD') AND (tyi= '$bgh' AND shop_id=$gfid OR tyi= '$bgh' AND boss_id= $gfid)";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasury"]=$FetchedData;

		$vpsql = "SELECT * FROM treasury WHERE tyi='$bgh' AND shop_id=  $gfid OR tyi= '$bgh' AND boss_id= $gfid";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasry"]=$FetchedData;

		if (isset($_POST['submi_up'])) {
			//include "config/connect.php";
			$billclo = filter_var(htmlspecialchars($_POST['billclo']),FILTER_SANITIZE_STRING);
			$sid = $_SESSION['id'];
			$type= "transfar";
			$update_info_sql = "UPDATE transactions SET bill = '$billclo' WHERE user_id= $sid AND type='$type'";
			$FetchedData=$this->comman_model->run_query($vpsql);
		}


		
		if (isset($_POST['for'])) {
			$this->UpdateTransferData($_POST);
		}

		$shopo =  $_SESSION['shop_id'];
		$typo =  $_SESSION['type'];
		$fetchUsers_sql="";

		//////////////////////////////////////
		if($typo == "admin")
		{
				$fetchUsers_sql = "SELECT id FROM signup WHERE shop_id='$shopo'";
		}else
		{
				$fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$sid' OR id='$sid'";		
		}
		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$data["signups"]=$FetchedData;
		$costumerarray=array();
		foreach($FetchedData as $item){
			$gfid = $item['id'];
			$vpsql = "SELECT DISTINCT cos_id FROM cos_transactions WHERE user_id= $gfid";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			foreach($FetchedData as $items){
				
				$cos_id=$items["cos_id"];
				$costumerarray["costrans"][$gfid][]=$items;

				$vpsql = "SELECT name FROM costumers WHERE user_id=$gfid AND main_id = $cos_id AND name !='casher'";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				foreach($FetchedData as $rows){
					$costumerarray["costumers"][$cos_id][]=$rows;
				}
			}

		}

		$data["pagedata"]=$costumerarray;
		//////////////////////////////////////


		$usecar =  "1";
		$vpsqlu = "SELECT * FROM bank WHERE boss_id= $boss_id AND usecha='$usecar' ";		
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsqlu);
		$data["banks"]=$FetchedData;

		$vpsqlu = "SELECT * FROM my_bank WHERE boss_id=$boss_id AND usechav= '$usecar' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsqlu);
		$data["mybank"]=$FetchedData;



		$branch = "LYD";
		$shopo =  $_SESSION['shop_id'];
		$typo =  $_SESSION['type'];
		$fetchUsers_sql = "SELECT DISTINCT id FROM signup WHERE boss_id='$sid' OR shop_id='$shopo' AND type='admin' OR id='$sid'";
		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$array=array();
		$ty_uy = 0;
		$ty_ji=0;
		$mediaResult=0; 
		$kind="";
		foreach($FetchedData as $item ){
			$gfid = $item['id'];
			$array["udata"]=$item;
			$vpsql = "SELECT DISTINCT kind FROM capital WHERE user_id= $gfid AND kind!='$branch'";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			$array["udata"]["KindCount"]=count($FetchedData);
			foreach($FetchedData as $kinditem){
				$array["udata"]["kinds"][$gfid]	=	$kinditem;
				$ghj = $kinditem['kind'];
				$kind=$kinditem['kind'];
				
				$vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE user_id=$gfid AND kind='$ghj' ";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				$array["udata"]["tycounts"]	=	count($FetchedData);
				foreach($FetchedData as $tyitem){
					$ty_uy = $tyitem['ty_uy'];
				}
				
				$vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE user_id=$gfid AND kind='$ghj'";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				foreach ($FetchedData as $postsfetch) 
				{
					$ty_ji = $postsfetch['ty_ji'];
				}
				$mediaResult = $ty_uy/$ty_ji;
			}
			$data["media"]=$mediaResult;
			$data["dataCount"] = $array["udata"]["KindCount"];
			$data["ghjikind"]=$kind;
			//$numcoss = $view_posts->rowCount();
		}

		//$this->load->helper("Currencynodes");
		$data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
		$check ="SELECT * FROM treasury WHERE user_id =$sid";
		$FetchedData=$this->comman_model->get_all_data_by_query($check);
		$data["treasurydata"]=$FetchedData;

		$typey = "0";
		$typeu = "transfar";
        $vpsql = "SELECT * FROM transactions WHERE user_id=  $sid AND bill =  '$typey' AND hide= '$typey' AND type= '$typeu' ORDER BY datepost DESC";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["transactions"]=$FetchedData;
        $transArray=array();
		foreach($FetchedData as $item){
			$chak_id = $item['chak_id'];
			$vpsql = "SELECT * FROM cos_transactions WHERE post_id=$chak_id";
			$CosData=$this->comman_model->get_all_data_by_query($vpsql);
			$transArray["transactions"]=$item;
			foreach($CosData as $cositem){
				$cos_id = $cositem['cos_id'];
		
				$transArray["transactions"][$chak_id]=$cositem;
				$vpsql = "SELECT * FROM costumers WHERE main_id=$cos_id";
				$CustData=$this->comman_model->get_all_data_by_query($vpsql);
				foreach($CustData as $cusitem){
					$transArray["transactions"][$chak_id][$cos_id]=$cusitem;
				}
				
			}
		}
		
		$data["printtransactinos"]=$transArray;
		
		

		/////////////////
		$boss_id = $_SESSION['boss_id'];
		$vpsql = "SELECT * FROM my_bank WHERE boss_id= $boss_id";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//$data["mybank"]=$FetchedData;

		$array=array();
		
		foreach($FetchedData as $postsfetch){
			$idn = $postsfetch['id'];
			$bank_name = $postsfetch['bank_name'];
			//============total of the money=============================
			$vpsql = "SELECT * FROM treasury WHERE wh=$idn";
			$transdata=$this->comman_model->get_all_data_by_query($vpsql);
			$array["bank"][] = $postsfetch;
			//$array["bank"][$idn] = $transdata;
			foreach($transdata as $item){
				$array["bank"][$idn][] = $item;
			}
			
			
		}
		
		$data["bank"]=$array;
		//////////////////////////////
	
		$this->load->view('Transaction/Transfar', $data);
	}


	public function UpdateTransferData($data){
		$_POST=$data;
		$sid = $_SESSION['id'];
		$shop_id = $_SESSION['shop_id'];
		$boss_id = $_SESSION['boss_id'];
		$exchange = filter_var(htmlentities($_POST['exchange']),FILTER_SANITIZE_STRING);
		$received_name = filter_var(htmlentities($_POST['amousel']),FILTER_SANITIZE_STRING);
		$received = filter_var(htmlentities($_POST['received']),FILTER_SANITIZE_STRING);
		$given_name = filter_var(htmlentities($_POST['amouse']),FILTER_SANITIZE_STRING);
		$amountsd = filter_var(htmlentities($_POST['amountsd']),FILTER_SANITIZE_STRING);
		$selbu = filter_var(htmlentities($_POST['selbu']),FILTER_SANITIZE_STRING);
		$pid = filter_var(htmlentities($_POST['pid']),FILTER_SANITIZE_STRING);

		$vpsql = "SELECT * FROM transactions WHERE post_id=$pid";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':pid', $pid, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvf = count($FetchedData);////$view_posts->rowCount();
		foreach ($FetchedData as $postsfetch) 
		{
			$receivedy = $postsfetch['received'];
			$giveny = $postsfetch['given'];
		}
		$numbj = $received-$receivedy;
		$numbg = $amountsd-$giveny;
		$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$received_name'";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvf = count($FetchedData);// $view_posts->rowCount();
		foreach($FetchedData as $postsfetch) {
			$numberya = $postsfetch['number'];
		}
		$numberybt = $numberya+$numbj;
		$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$given_name'";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);

		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvh = count($FetchedData);// $view_posts->rowCount();
		foreach($FetchedData as $postsfetch) {
			$numberyb = $postsfetch['number'];
		}
		$numberd = $numberyb+$numbg;

		$edit_post_sql = "UPDATE cash SET exchange= $exchange,received = $received,given='$amountsd',received_name = '$received_name',given_name ='$given_name',kin='$selbu' WHERE post_id= $pid";
		$IsUpDate=$this->comman_model->run_query($vpsql);
		// $edit_post = $conn->prepare($edit_post_sql);
		// $edit_post->bindParam(':exchange',$exchange,PDO::PARAM_STR);
		// $edit_post->bindParam(':amousel',$received_name,PDO::PARAM_STR);
		// $edit_post->bindParam(':received',$received,PDO::PARAM_INT);
		// $edit_post->bindParam(':amouse',$given_name,PDO::PARAM_INT);
		// $edit_post->bindParam(':amountsd',$amountsd,PDO::PARAM_INT);
		// $edit_post->bindParam(':selbu',$selbu,PDO::PARAM_INT);
		// $edit_post->bindParam(':pid',$pid,PDO::PARAM_INT);
		// $edit_post->execute();

		$iptdbsql = "UPDATE treasury SET number='$numberybt' WHERE shop_id = $shop_id AND kind='$received_name'";
		$IsUpDate=$this->comman_model->run_query($vpsql);
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbero', $numberybt,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
		// $insert_post_toDB->execute();

		//=====================insert the money of given==================================

		$iptdbsql = "UPDATE treasury SET number='$numberd' WHERE shop_id = $shop_id AND kind='$given_name'";
		$IsUpDate=$this->comman_model->run_query($vpsql);
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numberb', $numberd,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
	}



	public function Cards(){
		$mode=LoadMode();        
        $data["layoutmode"]  =   $mode;
		
		if($_SESSION['user_email_status'] == "not verified"){
			header("location:email_verification");
		}
		if($_SESSION['steps'] != "1"){
			header('location:steps?tc=shop');
		}

		if ($_SESSION['cards'] == '1') {
			header("location: Dashboard");
		}
		if($_SESSION['package'] == "2" || $_SESSION['package'] == "3" || $_SESSION['package'] == "4" || $_SESSION['admin'] == "1"){
			
		}else{
			header("location: Dashboard");
		}
		$data["active"] = "cards";
		$typem =  $_SESSION['type'];
        $sid =  $_SESSION['id'];
        $shop_id = $_SESSION['shop_id'];
        $boss_id = $_SESSION['boss_id'];
        if($typem == "boss"){
            $gfid = $boss_id;
        }elseif($typem == "admin"){
            $gfid = $shop_id;
        }else{
            $gfid = $shop_id;
        }
        $delk = "0";
        $bgh = "cash";

		$vpsql = "SELECT * FROM treasury WHERE (kind='LYD' OR kind='USD') AND (tyi= '$bgh' AND shop_id=$gfid OR tyi= '$bgh' AND boss_id= $gfid)";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasury"]=$FetchedData;

		//============total of the money=============================
		$vpsql = "SELECT * FROM treasury WHERE tyi='$bgh' AND shop_id=  $gfid OR tyi= '$bgh' AND boss_id= $gfid";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasry"]=$FetchedData;


		$data["dircheckPath"]= base_url()."Asset/";
		/////////
		if (isset($_POST['submi_up'])) {
		///	include "config/connect.php";
			$billclo = filter_var(htmlspecialchars($_POST['billclo']),FILTER_SANITIZE_STRING);
			$sid = $_SESSION['id'];
			$shop_id = $_SESSION['shop_id'];
			$boss_id = $_SESSION['boss_id'];
			$type= "cards";
			$update_info_sql = "UPDATE transactions SET bill= '$billclo' WHERE user_id= $sid AND type='$type'";
			$IsUpdate=$this->comman_model->run_query($vpsql);
		}
		//////////////////////////////////////
		//////////////////
		
		if (isset($_POST['for'])) {
			$this->UpdateCardData($_POST);
		}
		/////////////////




		if($typo == "admin")
		{
				$fetchUsers_sql = "SELECT id FROM signup WHERE shop_id='$shopo'";
		}else
		{
				$fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$sid' OR id='$sid'";		
		}
		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$data["signups"]=$FetchedData;
		$costumerarray=array();
		foreach($FetchedData as $item){
			$gfid = $item['id'];
			$vpsql = "SELECT DISTINCT cos_id FROM cos_transactions WHERE user_id= $gfid";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			foreach($FetchedData as $items){
				
				$cos_id=$items["cos_id"];
				$costumerarray["costrans"][$gfid][]=$items;

				$vpsql = "SELECT name FROM costumers WHERE user_id=$gfid AND main_id = $cos_id AND name !='casher'";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				foreach($FetchedData as $rows){
					$costumerarray["costumers"][$cos_id][]=$rows;
				}
			}

		}

		$data["pagedata"]=$costumerarray;
		//////////////////////////////////////
		// $Udata=GetIdofUser();
		// $gfid = $Udata[0]['id'];
		// $vpsql = "SELECT DISTINCT cos_id FROM cos_transactions WHERE user_id= $gfid";
		// $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);		
		// $cos_id=$FetchedData[0]["cos_id"];
		// $vpsql = "SELECT name FROM costumers WHERE user_id=$gfid AND main_id = $cos_id AND name !='casher'";		
		// $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);		
		// $data["costumers"]= $FetchedData;

		$usecar =  "1";
		$vpsqlu = "SELECT * FROM bank WHERE boss_id= $boss_id AND usecha='$usecar' ";		
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsqlu);
		$data["banks"]=$FetchedData;

		$cash = "cash";
		$branch = "LYD";
        $vpsql = "SELECT DISTINCT kind FROM capital WHERE boss_id= $gfid AND kind!= '$branch' AND tyi= '$cash' OR shop_id=$gfid AND kind!='$branch' AND tyi = '$cash' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//$numcoss = $view_posts->rowCount();
		$array=array();
		$mediaResult=0;
		$ghj="";
		foreach($FetchedData as $item){
			$ty_uy=0;
			$ty_ji=0;
			$ghj = $item['kind'];
			$vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE boss_id=$gfid AND kind= '$ghj' AND tyi= '$cash' OR shop_id= $gfid AND kind=  '$ghj' AND tyi= '$cash'";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			//$num = $view_postsi->rowCount();
			$ty_uy=$FetchedData[0]['ty_uy'];
			//print_r($FetchedData);
			$vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE boss_id= $gfid AND kind='$ghj' AND tyi= '$cash'
			 OR shop_id=$gfid AND kind= '$ghj' AND tyi= '$cash' ";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			//$num = $view_postsi->rowCount();
			//print_r($FetchedData);
			$ty_ji=$FetchedData[0]["ty_ji"];

			$mediaResult = $ty_uy/$ty_ji;
			
		}

		$data["ghj"]=$ghj;
		$data["media"]=$mediaResult;
		//$data["dataCount"] = $array["udata"]["KindCount"];
		$usecar =  "1";
		$vpsql = "SELECT * FROM my_bank WHERE boss_id= $boss_id AND usecarv= '$usecar' ";
	   	$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["mybank"]	=	$FetchedData;
		
		$vpsql ="SELECT * FROM treasury WHERE user_id =$sid";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasuredata"]=$FetchedData;

		$typey = "0";
		$typeu = "cards";
		$vpsql = "SELECT * FROM transactions WHERE user_id= $sid AND bill= '$typey' AND hide= '$typey' AND type= '$typeu' ORDER BY datepost DESC ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["transactions"]=$FetchedData;
		//$data["transactions"]=$FetchedData;
        $transArray=array();
		foreach($FetchedData as $item){
			$chak_id = $item['chak_id'];
			$vpsql = "SELECT * FROM cos_transactions WHERE post_id=$chak_id";
			$CosData=$this->comman_model->get_all_data_by_query($vpsql);
			$transArray["transactions"]=$item;
			foreach($CosData as $cositem){
				$cos_id = $cositem['cos_id'];
		
				$transArray["transactions"][$chak_id]=$cositem;
				$vpsql = "SELECT * FROM costumers WHERE main_id=$cos_id";
				$CustData=$this->comman_model->get_all_data_by_query($vpsql);
				foreach($CustData as $cusitem){
					$transArray["transactions"][$chak_id][$cos_id]=$cusitem;
				}
				
			}
		}
		
		$data["printtransactinos"]=$transArray;
		


		
		////////////////////////////////////////////////////////



		/////////////////
		$boss_id = $_SESSION['boss_id'];
		$vpsql = "SELECT * FROM my_bank WHERE boss_id= $boss_id";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//$data["mybank"]=$FetchedData;

		$array=array();
		
		foreach($FetchedData as $postsfetch){
			$idn = $postsfetch['id'];
			$bank_name = $postsfetch['bank_name'];
			//============total of the money=============================
			$vpsql = "SELECT * FROM treasury WHERE wh=$idn";
			$transdata=$this->comman_model->get_all_data_by_query($vpsql);
			$array["bank"][] = $postsfetch;
			//$array["bank"][$idn] = $transdata;
			foreach($transdata as $item){
				$array["bank"][$idn][] = $item;
			}
			
			
		}
		
		$data["bank"]=$array;
		//////////////////////////////



		$this->load->view('Transaction/Cards', $data);
	}


	public function UpdateCardData($data){
		$_POST=$data;
		$sid=$_SESSION['id'];
		$exchange = filter_var(htmlentities($_POST['exchange']),FILTER_SANITIZE_STRING);
		$received_name = filter_var(htmlentities($_POST['amousel']),FILTER_SANITIZE_STRING);
		$received = filter_var(htmlentities($_POST['received']),FILTER_SANITIZE_STRING);
		$given_name = filter_var(htmlentities($_POST['amouse']),FILTER_SANITIZE_STRING);
		$amountsd = filter_var(htmlentities($_POST['amountsd']),FILTER_SANITIZE_STRING);
		$selbu = filter_var(htmlentities($_POST['selbu']),FILTER_SANITIZE_STRING);
		$pid = filter_var(htmlentities($_POST['pid']),FILTER_SANITIZE_STRING);

		$vpsql = "SELECT * FROM transactions WHERE post_id=$pid";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':pid', $pid, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvf = count($FetchedData);// $view_posts->rowCount();
		foreach($FetchedData as $postsfetch) 
		{
			$receivedy = $postsfetch['received'];
			$giveny = $postsfetch['given'];
		}

		$numbj = $received-$receivedy;
		$numbg = $amountsd-$giveny;
		$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$received_name'";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvf = count($FetchedData);// $view_posts->rowCount();
		foreach ($FetchedData as $postsfetch) {
			$numberya = $postsfetch['number'];
		}
		$numberybt = $numberya+$numbj;
		$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$given_name'";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvh = count($FetchedData);/// $view_posts->rowCount();
		foreach ($FetchedData as $postsfetch) 
		{
			$numberyb = $postsfetch['number'];
		}
		$numberd = $numberyb+$numbg;

		$edit_post_sql = "UPDATE transactions SET exchange= '$exchange',received = '$received',given='$amountsd',received_name = '$received_name',given_name =$given_name,kin='$selbu' WHERE post_id= $pid";
		$IsUpdate=$this->comman_model->run_query($edit_post_sql);
		// $edit_post = $conn->prepare($edit_post_sql);
		// $edit_post->bindParam(':exchange',$exchange,PDO::PARAM_STR);
		// $edit_post->bindParam(':amousel',$received_name,PDO::PARAM_STR);
		// $edit_post->bindParam(':received',$received,PDO::PARAM_INT);
		// $edit_post->bindParam(':amouse',$given_name,PDO::PARAM_INT);
		// $edit_post->bindParam(':amountsd',$amountsd,PDO::PARAM_INT);
		// $edit_post->bindParam(':selbu',$selbu,PDO::PARAM_INT);
		// $edit_post->bindParam(':pid',$pid,PDO::PARAM_INT);
		// $edit_post->execute();

		$iptdbsql = "UPDATE treasury SET number=$numberybt WHERE shop_id = $shop_id AND kind='$received_name'";
		$IsUpdate=$this->comman_model->run_query($iptdbsql);

		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbero', $numberybt,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
		// $insert_post_toDB->execute();

		//=====================insert the money of given==================================

		$iptdbsql = "UPDATE treasury SET number='$numberd' WHERE shop_id = $shop_id AND kind=$given_name";
		$IsUpdate=$this->comman_model->run_query($iptdbsql);

		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numberb', $numberd,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
	}
	
	public function updateCards(){
		$billclo = filter_var(htmlspecialchars($_POST['billclo']),FILTER_SANITIZE_STRING);
	
		$sid = $_SESSION['id'];
		$shop_id = $_SESSION['shop_id'];
		$boss_id = $_SESSION['boss_id'];
		$type= "cards";

		$data = array(
			'bill'   => $billclo			
		);
		$where=array('user_id' => $sid, 'type' => $type);
		$update_info=$this->comman_model->update_entry("transactions",$data,$where);


		$url=base_url()."Transaction/Cards";
		header("Location: $url");
        exit;
	}



	public function Investment()
	{
		if($_SESSION['user_email_status'] == "not verified"){
			header("location:email_verification");
		}
		if($_SESSION['steps'] != "1"){
			header('location:steps?tc=shop');
		}

		if ($_SESSION['invest'] == '1') {
			header("location: home");
		}
		/////////////////////////
		if(isset($_POST['search_name'])){ 
			$data=$this->SearchInvester($_POST);
		}
		$data["active"] = "investment";

		error_reporting(0);
		$branch = "LYD";
		$tyo = lang('invest');
		$sid =  $_SESSION['id'];
		$shopo =  $_SESSION['shop_id'];
		$typo =  $_SESSION['type'];
		$Udata=GetIdofUser();
		$boss_id = $_SESSION['boss_id'];
		$shop_id = $_SESSION['shop_id'];
		$typem =  $_SESSION['type'];

		$gfid = $Udata[0]['id'];
		$vpsql = "SELECT DISTINCT cos_id FROM cos_transactions WHERE user_id= $gfid AND type= '$tyo' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//print_r($FetchedData);
		$cos_id=$FetchedData[0]["cos_id"];
		if($cos_id>0)
		{
			$vpsql = "SELECT name FROM costumers WHERE user_id=$gfid AND main_id = $cos_id ";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			$data["costumers"]= $FetchedData;
		}
		else
		{
			$data["costumers"]= array();
		}
		

		
		$data["tc"] = 'buysell' ; // filter_var(htmlentities($_GET['tc']),FILTER_SANITIZE_STRING);

		if (isset($_POST['submi_up'])) {
			$billclo = filter_var(htmlspecialchars($_POST['billclo']),FILTER_SANITIZE_STRING);
			$sid = $_SESSION['id'];
			$type= lang('Cash');
			$update_info_sql = "UPDATE transactions SET bill= '$billclo' WHERE user_id= $sid AND type='$type'";
			$IsUpdate = $this->comman_model->run_query($vpsql);
		}

		///////////////////
	
		if (isset($_POST['for'])) 
		{
			$this->UpdateInvestmentData($_POST);
		}


		$gfid=0;
		if($typem == "boss"){
        	$gfid = $boss_id;
        }elseif($typem == "admin"){
        	$gfid = $shop_id;
        }else{
            $gfid = $shop_id;
		}
        $cash = "cash";
		$branch = "LYD";
		
        $vpsql = "SELECT DISTINCT kind FROM capital WHERE boss_id= $gfid AND kind!= '$branch' AND tyi= '$cash' OR shop_id=$gfid AND kind!='$branch' AND tyi = '$cash' ";
		
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//$numcoss = $view_posts->rowCount();
		$array=array();
		$mediaResult=0;
		$ghj="";
		foreach($FetchedData as $item){
			$ty_uy=0;
			$ty_ji=0;
			$ghj = $item['kind'];
			
			$vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE boss_id=$gfid AND kind= '$ghj' AND tyi= '$cash' OR shop_id= $gfid AND kind=  '$ghj' AND tyi= '$cash'";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			//$num = $view_postsi->rowCount();
			$ty_uy=$FetchedData[0]['ty_uy'];
			//print_r($FetchedData);
			
			$vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE boss_id= $gfid AND kind='$ghj' AND tyi= '$cash'
			 OR shop_id=$gfid AND kind= '$ghj' AND tyi= '$cash' ";
			 
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			//$num = $view_postsi->rowCount();
			//print_r($FetchedData);
			$array["count"] =  count($FetchedData);
			$ty_ji=$FetchedData[0]["ty_ji"];

			$mediaResult = $ty_uy/$ty_ji;
			
		}
		$data["ghj"]=$ghj;
		$data["media"]=$mediaResult;

		$data["dataCount"] = $array["count"];



		

		$branch = lang('sell');
		$vhj = lang('invest');
		$delei = "";
		$delo = "0";
		$search = $_POST['search_name'];
		$Arraycostumers = array();

		$fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$boss_id'";
		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$gfid = $FetchedData[0]['id'];


		$delo = "0";
		$type = lang('invest');
		$sid =  $_SESSION['id'];
		$shopo =  $_SESSION['shop_id'];
		$typo =  $_SESSION['type'];
		$boss_id =  $_SESSION['boss_id'];
		$ql = "SELECT * FROM cos_transactions WHERE user_id=$gfid AND hide= '$delo' AND type='$type' ORDER BY datepost DESC";
		$cosData=$this->comman_model->get_all_data_by_query($ql);
		$cosArray= array();
		foreach($cosData as $item){
			$post_idy = $item['post_id'];
			$cos_id = $item['cos_id'];
			$cosArray["cos"][]=$item;
			$q = "SELECT * FROM transactions WHERE chak_id= $post_idy  AND hide= '$delo' ";
			$getData = $this->comman_model->get_all_data_by_query($q);
			$user_id=0;
			foreach($getData as $titem){
				$user_id = $titem['user_id'];
				$cosArray["cos"]["transactions"][$post_idy]=$titem;
			}

			$costquery = "SELECT * FROM costumers WHERE main_id=$cos_id ";
			$getcosData = $this->comman_model->get_all_data_by_query($costquery);
			foreach($getcosData as $cositem){
				$cosArray["cos"]["costumers"][$cos_id]=$titem;
			}

			$uquery = "SELECT Username FROM signup WHERE id=$user_id ";
			$getudata = $this->comman_model->get_all_data_by_query($uquery);
			foreach($getcosData as $uitem){
				$cosArray["cos"]["signup"][$user_id]=$uitem;
			}
			
		}
		$data["cos"]=$cosArray;
		$query = "SELECT * FROM treasury WHERE user_id = $gfid ";
		$FetchedData = $this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$data["treasrydata"]=$FetchedData;

		$vpsql = "SELECT main_id FROM costumers WHERE user_id= $gfid AND name= '$search' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		foreach($FetchedData as $postsfetch){
			$main_id = $postsfetch['main_id'];
			$Arraycostumers['costumers'][] = $postsfetch;
			$vpsql = "SELECT * FROM cos_transactions WHERE user_id=$gfid AND cos_id= $main_id AND hide= '$delo' ORDER BY datepost DESC";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);




			foreach($FetchedData as $postsfetch)
			{
				$headvk = $postsfetch['post_id'];
				$og = $postsfetch['og'];
				$cutr = $postsfetch['cutr'];
				$Arraycostumers['costumers']["Costransactions"][$main_id] = $postsfetch;

				$vpsql = "SELECT * FROM transactions WHERE chak_id= $headvk ";
				$FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
				$Arraycostumers['costumers']["transCount"]=count($FetchedData);
				foreach($FetchedData as $item){
					$Arraycostumers['costumers']["transactions"][$headvk]=count($FetchedData);
				}
				
				
			}
			
		}
		$data["transCount"]	= $Arraycostumers['costumers']["transCount"];
		$data["transdata"] = $Arraycostumers;
		
		$data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();

		$data["dircheckPath"]= base_url()."Asset/";
		$mode=LoadMode();
		$data["layoutmode"]  =   $mode;
		$data["bank"]="";


		
		$vpsql = "SELECT * FROM invest_treasury WHERE user_id=$boss_id AND name='$name' ";
		$FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
		$data["investtreasury"]=$FetchedData;
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':given_name', $name, PDO::PARAM_INT);
		// $view_posts->execute();
		//$numvh = $view_posts->rowCount();
		if($typem == "boss"){
			$gfid = $boss_id;
		}elseif($typem == "admin"){
			$gfid = $shop_id;
		}else{
			$gfid = $shop_id;
		}
		$delk = "0";
		$bgh = "cash";
		
		$vpsql = "SELECT * FROM treasury WHERE (kind='LYD' OR kind='USD') AND (tyi= '$bgh' AND shop_id=$gfid OR tyi= '$bgh' AND boss_id= $gfid)";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasury"]=$FetchedData;


		

		//============total of the money=============================
		$vpsql = "SELECT * FROM treasury WHERE tyi='$bgh' AND shop_id=  $gfid OR tyi= '$bgh' AND boss_id= $gfid";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasry"]=$FetchedData;



		/////////////////
		$boss_id = $_SESSION['boss_id'];
		$vpsql = "SELECT * FROM my_bank WHERE boss_id= $boss_id";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//$data["mybank"]=$FetchedData;

		$array=array();
		
		foreach($FetchedData as $postsfetch){
			$idn = $postsfetch['id'];
			$bank_name = $postsfetch['bank_name'];
			//============total of the money=============================
			$vpsql = "SELECT * FROM treasury WHERE wh=$idn";
			$transdata=$this->comman_model->get_all_data_by_query($vpsql);
			$array["bank"][] = $postsfetch;
			//$array["bank"][$idn] = $transdata;
			foreach($transdata as $item){
				$array["bank"][$idn][] = $item;
			}
			
			
		}
		
		$data["bank"]=$array;
		//////////////////////////////


		$this->load->view('Transaction/Investment', $data);
	}



	public function UpdateInvestmentData($data){
		$_POST=$data;
		
		$sid = $_SESSION['id'];
		$shop_id = $_SESSION['shop_id'];
		$boss_id = $_SESSION['boss_id'];
		$exchange = filter_var(htmlentities($_POST['exchange']),FILTER_SANITIZE_STRING);
		$received_name = filter_var(htmlentities($_POST['amousel']),FILTER_SANITIZE_STRING);
		$received = filter_var(htmlentities($_POST['received']),FILTER_SANITIZE_STRING);
		$given_name = filter_var(htmlentities($_POST['amouse']),FILTER_SANITIZE_STRING);
		$amountsd = filter_var(htmlentities($_POST['amountsd']),FILTER_SANITIZE_STRING);
		$selbu = filter_var(htmlentities($_POST['selbu']),FILTER_SANITIZE_STRING);
		$pid = filter_var(htmlentities($_POST['pid']),FILTER_SANITIZE_STRING);
	  
		$vpsql = "SELECT * FROM transactions WHERE post_id=$pid";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':pid', $pid, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvf = count($FetchedData);// $view_posts->rowCount();
		foreach($FetchedData as $postsfetch) 
		{
			$receivedy = $postsfetch['received'];
			$giveny = $postsfetch['given'];
		}

		$numbj = $received-$receivedy;
		$numbg = $amountsd-$giveny;
		$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$received_name'";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvf = count($FetchedData);// $view_posts->rowCount();
		foreach($FetchedData as $postsfetch) 
		{
			$numberya = $postsfetch['number'];
	 	}
		$numberybt = $numberya+$numbj;
		$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$given_name'";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvh = count($FetchedData);// $view_posts->rowCount();
		foreach ($FetchedData as $postsfetch) 
		{
			$numberyb = $postsfetch['number'];
		}
		$numberd = $numberyb+$numbg;
	  
		$edit_post_sql = "UPDATE transactions SET exchange= '$exchange',received = '$received',given='$amountsd',received_name = '$received_name',given_name ='$given_name',kin='$selbu' WHERE post_id= $pid";
		$IsUpdate = $this->comman_model->run_query($edit_post_sql);
		// $edit_post = $conn->prepare($edit_post_sql);
		// $edit_post->bindParam(':exchange',$exchange,PDO::PARAM_STR);
		// $edit_post->bindParam(':amousel',$received_name,PDO::PARAM_STR);
		// $edit_post->bindParam(':received',$received,PDO::PARAM_INT);
		// $edit_post->bindParam(':amouse',$given_name,PDO::PARAM_INT);
		// $edit_post->bindParam(':amountsd',$amountsd,PDO::PARAM_INT);
		// $edit_post->bindParam(':selbu',$selbu,PDO::PARAM_INT);
		// $edit_post->bindParam(':pid',$pid,PDO::PARAM_INT);
		// $edit_post->execute();
	  
		$iptdbsql = "UPDATE treasury SET number='$numberybt' WHERE shop_id = $shop_id AND kind='$received_name'";
		$IsUpdate = $this->comman_model->run_query($iptdbsql);

		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbero', $numberybt,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
	  
		//=====================insert the money of given==================================
	  
		$iptdbsql = "UPDATE treasury SET number=$numberd WHERE shop_id = $shop_id AND kind='$given_name'";
		$IsUpdate = $this->comman_model->run_query($iptdbsql);
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numberb', $numberd,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
		// $insert_post_toDB->execute();

	}




	
	public function BuyInvestment(){

		if($_SESSION['user_email_status'] == "not verified"){
			header("location:email_verification");
		}
		if($_SESSION['steps'] != "1"){
			header('location:steps?tc=shop');
		}

		if ($_SESSION['invest'] == '1') {
			header("location: home");
		}

		if(isset($_POST['search_name'])){ 
			$data=$this->SearchInvester($_POST);
		}

		//error_reporting(0);

		
		$data["tc"] = 'buy_inv' ; // filter_var(htmlentities($_GET['tc']),FILTER_SANITIZE_STRING);

		if (isset($_POST['submi_up'])) {
			$billclo = filter_var(htmlspecialchars($_POST['billclo']),FILTER_SANITIZE_STRING);
			$sid = $_SESSION['id'];
			$type= lang('Cash');
			$update_info_sql = "UPDATE transactions SET bill= '$billclo' WHERE user_id= $sid AND type='$type'";
			$IsUpdate = $this->comman_model->run_query($vpsql);
		}

		///////////////////
	
		if (isset($_POST['for'])) 
		{
			$this->UpdateInvestmentData($_POST);
		}


		
		$branch = "LYD";
		$tyo = lang('invest');
		$sid =  $_SESSION['id'];
		$shopo =  $_SESSION['shop_id'];
		$typo =  $_SESSION['type'];
		$Udata=GetIdofUser();
		$boss_id = $_SESSION['boss_id'];
		$shop_id = $_SESSION['shop_id'];
		$typem =  $_SESSION['type'];

		$gfid = $Udata[0]['id'];
		$vpsql = "SELECT DISTINCT cos_id FROM cos_transactions WHERE user_id= $gfid AND type= '$tyo' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//print_r($FetchedData);
		$cos_id=$FetchedData[0]["cos_id"];
		if($cos_id>0)
		{
			$vpsql = "SELECT name FROM costumers WHERE user_id=$gfid AND main_id = $cos_id ";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			$data["costumers"]= $FetchedData;
		}
		else
		{
			$data["costumers"]= array();
		}
		

		




		$gfid=0;
		if($typem == "boss"){
        	$gfid = $boss_id;
        }elseif($typem == "admin"){
        	$gfid = $shop_id;
        }else{
            $gfid = $shop_id;
		}
        $cash = "cash";
		$branch = "LYD";
		
        $vpsql = "SELECT DISTINCT kind FROM capital WHERE boss_id= $gfid AND kind!= '$branch' AND tyi= '$cash' OR shop_id=$gfid AND kind!='$branch' AND tyi = '$cash' ";
		
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//$numcoss = $view_posts->rowCount();
		$array=array();
		$mediaResult=0;
		$ghj="";
		foreach($FetchedData as $item){
			$ty_uy=0;
			$ty_ji=0;
			$ghj = $item['kind'];
			
			$vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE boss_id=$gfid AND kind= '$ghj' AND tyi= '$cash' OR shop_id= $gfid AND kind=  '$ghj' AND tyi= '$cash'";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			//$num = $view_postsi->rowCount();
			$ty_uy=$FetchedData[0]['ty_uy'];
			//print_r($FetchedData);
			
			$vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE boss_id= $gfid AND kind='$ghj' AND tyi= '$cash'
			 OR shop_id=$gfid AND kind= '$ghj' AND tyi= '$cash' ";
			 
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			//$num = $view_postsi->rowCount();
			//print_r($FetchedData);
			$array["count"] =  count($FetchedData);
			$ty_ji=$FetchedData[0]["ty_ji"];

			$mediaResult = $ty_uy/$ty_ji;
			
		}
		$data["ghj"]=$ghj;
		$data["media"]=$mediaResult;

		$data["dataCount"] = $array["count"];



		

		$branch = lang('sell');
		$vhj = lang('invest');
		$delei = "";
		$delo = "0";
		$search = $_POST['search_name'];
		$Arraycostumers = array();

		$fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$boss_id'";
		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$gfid = $FetchedData[0]['id'];


		$delo = "0";
		$type = lang('invest');
		$sid =  $_SESSION['id'];
		$shopo =  $_SESSION['shop_id'];
		$typo =  $_SESSION['type'];
		$boss_id =  $_SESSION['boss_id'];
		$ql = "SELECT * FROM cos_transactions WHERE user_id=$gfid AND hide= '$delo' AND type='$type' ORDER BY datepost DESC";
		$cosData=$this->comman_model->get_all_data_by_query($ql);
		$cosArray= array();
		foreach($cosData as $item){
			$post_idy = $item['post_id'];
			$cos_id = $item['cos_id'];
			$cosArray["cos"][]=$item;
			$q = "SELECT * FROM transactions WHERE chak_id= $post_idy  AND hide= '$delo' ";
			$getData = $this->comman_model->get_all_data_by_query($q);
			$user_id=0;
			foreach($getData as $titem){
				$user_id = $titem['user_id'];
				$cosArray["cos"]["transactions"][$post_idy]=$titem;
			}

			$costquery = "SELECT * FROM costumers WHERE main_id=$cos_id ";
			$getcosData = $this->comman_model->get_all_data_by_query($costquery);
			foreach($getcosData as $cositem){
				$cosArray["cos"]["costumers"][$cos_id]=$titem;
			}

			$uquery = "SELECT Username FROM signup WHERE id=$user_id ";
			$getudata = $this->comman_model->get_all_data_by_query($uquery);
			foreach($getcosData as $uitem){
				$cosArray["cos"]["signup"][$user_id]=$uitem;
			}
			
		}
		$data["cos"]=$cosArray;
		$query = "SELECT * FROM treasury WHERE user_id = $gfid ";
		$FetchedData = $this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$data["treasrydata"]=$FetchedData;

		$vpsql = "SELECT main_id FROM costumers WHERE user_id= $gfid AND name= '$search' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		foreach($FetchedData as $postsfetch){
			$main_id = $postsfetch['main_id'];
			$Arraycostumers['costumers'][] = $postsfetch;
			$vpsql = "SELECT * FROM cos_transactions WHERE user_id=$gfid AND cos_id= $main_id AND hide= '$delo' ORDER BY datepost DESC";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);




			foreach($FetchedData as $postsfetch)
			{
				$headvk = $postsfetch['post_id'];
				$og = $postsfetch['og'];
				$cutr = $postsfetch['cutr'];
				$Arraycostumers['costumers']["Costransactions"][$main_id] = $postsfetch;

				$vpsql = "SELECT * FROM transactions WHERE chak_id= $headvk ";
				$FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
				$Arraycostumers['costumers']["transCount"]=count($FetchedData);
				foreach($FetchedData as $item){
					$Arraycostumers['costumers']["transactions"][$headvk]=count($FetchedData);
				}
				
				
			}
			
		}
		$data["transCount"]	= $Arraycostumers['costumers']["transCount"];
		$data["transdata"] = $Arraycostumers;
		
		$data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();

		$data["dircheckPath"]= base_url()."Asset/";
		$mode=LoadMode();
		$data["layoutmode"]  =   $mode;
		$data["bank"]="";


		
		$vpsql = "SELECT * FROM invest_treasury WHERE user_id=$boss_id AND name='$name' ";
		$FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
		$data["investtreasury"]=$FetchedData;
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':given_name', $name, PDO::PARAM_INT);
		// $view_posts->execute();
		//$numvh = $view_posts->rowCount();
		if($typem == "boss"){
			$gfid = $boss_id;
		}elseif($typem == "admin"){
			$gfid = $shop_id;
		}else{
			$gfid = $shop_id;
		}
		$delk = "0";
		$bgh = "cash";

		$vpsql = "SELECT * FROM treasury WHERE (kind='LYD' OR kind='USD') AND (tyi= '$bgh' AND shop_id=$gfid OR tyi= '$bgh' AND boss_id= $gfid)";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasury"]=$FetchedData;

	

		//============total of the money=============================
		$vpsql = "SELECT * FROM treasury WHERE tyi='$bgh' AND shop_id=  $gfid OR tyi= '$bgh' AND boss_id= $gfid";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasry"]=$FetchedData;


		/////////////////
		$boss_id = $_SESSION['boss_id'];
		$vpsql = "SELECT * FROM my_bank WHERE boss_id= $boss_id";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//$data["mybank"]=$FetchedData;

		$array=array();
		
		foreach($FetchedData as $postsfetch){
			$idn = $postsfetch['id'];
			$bank_name = $postsfetch['bank_name'];
			//============total of the money=============================
			$vpsql = "SELECT * FROM treasury WHERE wh=$idn";
			$transdata=$this->comman_model->get_all_data_by_query($vpsql);
			$array["bank"][] = $postsfetch;
			//$array["bank"][$idn] = $transdata;
			foreach($transdata as $item){
				$array["bank"][$idn][] = $item;
			}
			
			
		}
		//echo "Test";
		$data["bank"]=$array;
		//////////////////////////////
		//echo "Test2";
		$emptyarray=array();
		$data["signpupsids2"]=$emptyarray;
		$sarray["costumers2"][0][]=$emptyarray;
	
		$this->load->view('Transaction/BuyInvestment', $data);
	}

	
	public function SearchInvester($array){
		$_POST=$array;
		$search = $_POST['search_name'];
			//include "config/connc.php";
			$sid =  $_SESSION['id'];
			$shopo =  $_SESSION['shop_id'];
			$typo =  $_SESSION['type'];
			$boss_id =  $_SESSION['boss_id'];

			$search_name = $_POST['search_name'];
			$fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$boss_id'";
			$fdata=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
			$data["signpupsids2"]=$fdata;
			$sarray=array();
			foreach($fdata as $item){
				$gfid = $item['id'];
				
				$vpsql = "SELECT main_id FROM costumers WHERE user_id=$gfid AND name='$search_name'";
				
				$fdata=$this->comman_model->get_all_data_by_query($vpsql);
		
				if(count($fdata)>0){
				foreach($fdata as $item){
					
							$sarray["costumers2"][$gfid][]=$item;
							$main_id = $item['main_id'];
							
							$emp_query = "SELECT * FROM cos_transactions WHERE 1";
							// Date filter
							
							if(isset($_POST['postsea_now'])){
								$search_name = $_POST['search_name'];
								if(!empty($search_name))
								{
									$emp_query .= " and cos_id ='".$main_id."' and user_id = '".$gfid."' and hide ='0' ";
								}
							}
							
							$emp_query .= " ORDER BY datepost DESC";
							$fdata=$this->comman_model->get_all_data_by_query($emp_query);
							foreach($fdata as $item){
								$sarray["filterdata2"][$main_id][]=$item;
								$post_id = $item['post_id'];
								$vpsql = "SELECT * FROM transactions WHERE chak_id=$post_id";
								$fdata=$this->comman_model->get_all_data_by_query($vpsql);
								foreach($fdata as $item){
									$sarray["transactions2"][$post_id][]=$item;
									$cos_id = $item['cos_id'];
									$user_id = $item['user_id'];
								}
								$vpsql = "SELECT * FROM costumers WHERE main_id=$cos_id";
								$fdata=$this->comman_model->get_all_data_by_query($vpsql);
								foreach($fdata as $item){
									$sarray["costumerswithmain"][$cos_id][]=$item;
									
								}

								$vpsql = "SELECT Username FROM signup WHERE id=$user_id";
								$fdata=$this->comman_model->get_all_data_by_query($vpsql);
								foreach($fdata as $item){
									$sarray["filterusernames"][$user_id][]=$item;
									
								}
												//   $view_postsi = $conn->prepare($vpsql);
												//   $view_postsi->bindParam(':tyo', $cos_id, PDO::PARAM_INT);
												//   $view_postsi->execute();
							}
						}
				}
				

				$vpsql = "SELECT * FROM costumers WHERE user_id=$gfid AND name='$search_name'";
				$fdata=$this->comman_model->get_all_data_by_query($vpsql);
			
				if(count($fdata)>0)
				{
					foreach($fdata as $postsfetch)
					{
						$sarray["costumers"][$gfid][]=$postsfetch;
						$main_id = $postsfetch['main_id'];
						$name = $postsfetch['name'];
						$email = $postsfetch['email'];
						$phone = $postsfetch['phone'];
						$address = $postsfetch['address'];

						$emp_query = "SELECT DISTINCT cos_id FROM cos_transactions WHERE 1";
						// Date filter
						if(isset($_POST['postsea_now']))
						{
							$search_name = $_POST['search_name'];
							if(!empty($search_name)){
								$emp_query .= " and cos_id ='".$main_id."' and user_id = '".$gfid."' and hide ='0' ";
							}
						}

						// Sort
						$emp_query .= " ORDER BY datepost DESC";
						$fdata=$this->comman_model->get_all_data_by_query($emp_query);
						foreach($fdata as $item){
							$sarray["filterdata"][$gfid][]=$item;
						}

					}
				}
			}
			$data["SearchInfo"]=$sarray;

			$vpsql = "SELECT * FROM invest_treasury WHERE user_id=$boss_id AND name= '$search' ";
			
			$fdata=$this->comman_model->get_all_data_by_query($vpsql);
			$data["investTreasury"]=$fdata;
		    return $data;
	}

	public function ExternalInvestment()
	{

		if($_SESSION['user_email_status'] == "not verified"){
			header("location:email_verification");
		}
		if($_SESSION['steps'] != "1"){
			header('location:steps?tc=shop');
		}

		if ($_SESSION['invest'] == '1') {
			header("location: home");
		}
		//////////////////////////////
		if(isset($_POST['search_name'])){ 
			$data=$this->SearchInvester($_POST);
		}


		error_reporting(0);
		$data["tc"] = 'ext_inv' ; // filter_var(htmlentities($_GET['tc']),FILTER_SANITIZE_STRING);

		if (isset($_POST['submi_up'])) {
			$billclo = filter_var(htmlspecialchars($_POST['billclo']),FILTER_SANITIZE_STRING);
			$sid = $_SESSION['id'];
			$type= lang('Cash');
			$update_info_sql = "UPDATE transactions SET bill= '$billclo' WHERE user_id= $sid AND type='$type'";
			$IsUpdate = $this->comman_model->run_query($vpsql);
		}

		///////////////////
	
		if (isset($_POST['for'])) 
		{
			$this->UpdateInvestmentData($_POST);
		}



		$branch = "LYD";
		$tyo = lang('invest');
		$sid =  $_SESSION['id'];
		$shopo =  $_SESSION['shop_id'];
		$typo =  $_SESSION['type'];
		$Udata=GetIdofUser();
		$boss_id = $_SESSION['boss_id'];
		$shop_id = $_SESSION['shop_id'];
		$typem =  $_SESSION['type'];

		$gfid = $Udata[0]['id'];
		$vpsql = "SELECT DISTINCT cos_id FROM cos_transactions WHERE user_id= $gfid AND type= '$tyo' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//print_r($FetchedData);
		$cos_id=$FetchedData[0]["cos_id"];
		if($cos_id>0)
		{
			$vpsql = "SELECT name FROM costumers WHERE user_id=$gfid AND main_id = $cos_id ";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			$data["costumers"]= $FetchedData;
		}
		else
		{
			$data["costumers"]= array();
		}
		

		




		$gfid=0;
		if($typem == "boss"){
        	$gfid = $boss_id;
        }elseif($typem == "admin"){
        	$gfid = $shop_id;
        }else{
            $gfid = $shop_id;
		}
        $cash = "cash";
		$branch = "LYD";
		
        $vpsql = "SELECT DISTINCT kind FROM capital WHERE boss_id= $gfid AND kind!= '$branch' AND tyi= '$cash' OR shop_id=$gfid AND kind!='$branch' AND tyi = '$cash' ";
		
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//$numcoss = $view_posts->rowCount();
		$array=array();
		$mediaResult=0;
		$ghj="";
		foreach($FetchedData as $item){
			$ty_uy=0;
			$ty_ji=0;
			$ghj = $item['kind'];
			
			$vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE boss_id=$gfid AND kind= '$ghj' AND tyi= '$cash' OR shop_id= $gfid AND kind=  '$ghj' AND tyi= '$cash'";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			//$num = $view_postsi->rowCount();
			$ty_uy=$FetchedData[0]['ty_uy'];
			//print_r($FetchedData);
			
			$vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE boss_id= $gfid AND kind='$ghj' AND tyi= '$cash'
			 OR shop_id=$gfid AND kind= '$ghj' AND tyi= '$cash' ";
			 
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			//$num = $view_postsi->rowCount();
			//print_r($FetchedData);
			$array["count"] =  count($FetchedData);
			$ty_ji=$FetchedData[0]["ty_ji"];

			$mediaResult = $ty_uy/$ty_ji;
			
		}
		$data["ghj"]=$ghj;
		$data["media"]=$mediaResult;

		$data["dataCount"] = $array["count"];



		

		$branch = lang('sell');
		$vhj = lang('invest');
		$delei = "";
		$delo = "0";
		$search = $_POST['search_name'];
		$Arraycostumers = array();

		$fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$boss_id'";
		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$gfid = $FetchedData[0]['id'];


		$delo = "0";
		$type = lang('invest');
		$sid =  $_SESSION['id'];
		$shopo =  $_SESSION['shop_id'];
		$typo =  $_SESSION['type'];
		$boss_id =  $_SESSION['boss_id'];
		$ql = "SELECT * FROM cos_transactions WHERE user_id=$gfid AND hide= '$delo' AND type='$type' ORDER BY datepost DESC";
		$cosData=$this->comman_model->get_all_data_by_query($ql);
		$cosArray= array();
		foreach($cosData as $item){
			$post_idy = $item['post_id'];
			$cos_id = $item['cos_id'];
			$cosArray["cos"][]=$item;
			$q = "SELECT * FROM transactions WHERE chak_id= $post_idy  AND hide= '$delo' ";
			$getData = $this->comman_model->get_all_data_by_query($q);
			$user_id=0;
			foreach($getData as $titem){
				$user_id = $titem['user_id'];
				$cosArray["cos"]["transactions"][$post_idy]=$titem;
			}

			$costquery = "SELECT * FROM costumers WHERE main_id=$cos_id ";
			$getcosData = $this->comman_model->get_all_data_by_query($costquery);
			foreach($getcosData as $cositem){
				$cosArray["cos"]["costumers"][$cos_id]=$titem;
			}

			$uquery = "SELECT Username FROM signup WHERE id=$user_id ";
			$getudata = $this->comman_model->get_all_data_by_query($uquery);
			foreach($getcosData as $uitem){
				$cosArray["cos"]["signup"][$user_id]=$uitem;
			}
			
		}
		$data["cos"]=$cosArray;
		$query = "SELECT * FROM treasury WHERE user_id = $gfid ";
		$FetchedData = $this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$data["treasrydata"]=$FetchedData;

		$vpsql = "SELECT main_id FROM costumers WHERE user_id= $gfid AND name= '$search' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		foreach($FetchedData as $postsfetch){
			$main_id = $postsfetch['main_id'];
			$Arraycostumers['costumers'][] = $postsfetch;
			$vpsql = "SELECT * FROM cos_transactions WHERE user_id=$gfid AND cos_id= $main_id AND hide= '$delo' ORDER BY datepost DESC";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);




			foreach($FetchedData as $postsfetch)
			{
				$headvk = $postsfetch['post_id'];
				$og = $postsfetch['og'];
				$cutr = $postsfetch['cutr'];
				$Arraycostumers['costumers']["Costransactions"][$main_id] = $postsfetch;

				$vpsql = "SELECT * FROM transactions WHERE chak_id= $headvk ";
				$FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
				$Arraycostumers['costumers']["transCount"]=count($FetchedData);
				foreach($FetchedData as $item){
					$Arraycostumers['costumers']["transactions"][$headvk]=count($FetchedData);
				}
				
				
			}
			
		}
		$data["transCount"]	= $Arraycostumers['costumers']["transCount"];
		$data["transdata"] = $Arraycostumers;
		
		$data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();

		$data["dircheckPath"]= base_url()."Asset/";
		$mode=LoadMode();
		$data["layoutmode"]  =   $mode;
		$data["bank"]="";


		
		$vpsql = "SELECT * FROM invest_treasury WHERE user_id=$boss_id AND name='$name' ";
		$FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
		$data["investtreasury"]=$FetchedData;
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':given_name', $name, PDO::PARAM_INT);
		// $view_posts->execute();
		//$numvh = $view_posts->rowCount();
		if($typem == "boss"){
			$gfid = $boss_id;
		}elseif($typem == "admin"){
			$gfid = $shop_id;
		}else{
			$gfid = $shop_id;
		}
		$delk = "0";
		$bgh = "cash";

		$vpsql = "SELECT * FROM treasury WHERE (kind='LYD' OR kind='USD') AND (tyi= '$bgh' AND shop_id=$gfid OR tyi= '$bgh' AND boss_id= $gfid)";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasury"]=$FetchedData;

	

		//============total of the money=============================
		$vpsql = "SELECT * FROM treasury WHERE tyi='$bgh' AND shop_id=  $gfid OR tyi= '$bgh' AND boss_id= $gfid";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasry"]=$FetchedData;


		/////////////////
		$boss_id = $_SESSION['boss_id'];
		$vpsql = "SELECT * FROM my_bank WHERE boss_id= $boss_id";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//$data["mybank"]=$FetchedData;

		$array=array();
		
		foreach($FetchedData as $postsfetch){
			$idn = $postsfetch['id'];
			$bank_name = $postsfetch['bank_name'];
			//============total of the money=============================
			$vpsql = "SELECT * FROM treasury WHERE wh=$idn";
			$transdata=$this->comman_model->get_all_data_by_query($vpsql);
			$array["bank"][] = $postsfetch;
			//$array["bank"][$idn] = $transdata;
			foreach($transdata as $item){
				$array["bank"][$idn][] = $item;
			}
			
			
		}
		
		$data["bank"]=$array;
		
		$this->load->view('Transaction/ExternalInvestment', $data);
	}



	public function FastInvestment()
	{

		if($_SESSION['user_email_status'] == "not verified"){
			header("location:email_verification");
		}
		if($_SESSION['steps'] != "1"){
			header('location:steps?tc=shop');
		}

		if ($_SESSION['invest'] == '1') {
			header("location: home");
		}
		/////////////////////////
		if(isset($_POST['search_name'])){ 
			$data=$this->SearchInvester($_POST);
		}

		error_reporting(0);

		$data["tc"] = 'fast_inv' ; // filter_var(htmlentities($_GET['tc']),FILTER_SANITIZE_STRING);

		if (isset($_POST['submi_up'])) {
			$billclo = filter_var(htmlspecialchars($_POST['billclo']),FILTER_SANITIZE_STRING);
			$sid = $_SESSION['id'];
			$type= lang('Cash');
			$update_info_sql = "UPDATE transactions SET bill= '$billclo' WHERE user_id= $sid AND type='$type'";
			$IsUpdate = $this->comman_model->run_query($vpsql);
		}

		///////////////////
	
		if (isset($_POST['for'])) 
		{
			$this->UpdateInvestmentData($_POST);
		}

		$branch = "LYD";
		$tyo = lang('invest');
		$sid =  $_SESSION['id'];
		$shopo =  $_SESSION['shop_id'];
		$typo =  $_SESSION['type'];
		$Udata=GetIdofUser();
		$boss_id = $_SESSION['boss_id'];
		$shop_id = $_SESSION['shop_id'];
		$typem =  $_SESSION['type'];

		$gfid = $Udata[0]['id'];
		$vpsql = "SELECT DISTINCT cos_id FROM cos_transactions WHERE user_id= $gfid AND type= '$tyo' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//print_r($FetchedData);
		$cos_id=$FetchedData[0]["cos_id"];
		if($cos_id>0)
		{
			$vpsql = "SELECT name FROM costumers WHERE user_id=$gfid AND main_id = $cos_id ";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			$data["costumers"]= $FetchedData;
		}
		else
		{
			$data["costumers"]= array();
		}
		

		




		$gfid=0;
		if($typem == "boss"){
        	$gfid = $boss_id;
        }elseif($typem == "admin"){
        	$gfid = $shop_id;
        }else{
            $gfid = $shop_id;
		}
        $cash = "cash";
		$branch = "LYD";
		$vpsql = "SELECT DISTINCT kind FROM capital WHERE boss_id=$gfid AND kind!= '$branch' OR shop_id= $gfid AND kind!= '$branch'";
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':sid', $gfid, PDO::PARAM_INT);
		// $view_posts->bindParam(':branch', $branch, PDO::PARAM_INT);
		// $view_posts->execute();

        //$vpsql = "SELECT DISTINCT kind FROM capital WHERE boss_id= $gfid AND kind!= '$branch' AND tyi= '$cash' OR shop_id=$gfid AND kind!='$branch' AND tyi = '$cash' ";
		
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//$numcoss = $view_posts->rowCount();
		$array=array();
		$mediaResult=0;
		$ghj="";
		
		foreach($FetchedData as $item){
			$ty_uy=0;
			$ty_ji=0;
			$ghj = $item['kind'];
			
			 

			 
			$vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE boss_id= $gfid AND kind= '$ghj' OR shop_id= $gfid AND kind= '$ghj' ";
			
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			//$num = $view_postsi->rowCount();
			$ty_uy=$FetchedData[0]['ty_uy'];
			//print_r($FetchedData);
			$vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE boss_id=$gfid AND kind='$ghj' OR shop_id=$gfid AND kind='$ghj'";                                                 
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			//$num = $view_postsi->rowCount();
			//print_r($FetchedData);
			$array["count"] =  count($FetchedData);
			$ty_ji=$FetchedData[0]["ty_ji"];

			$mediaResult = $ty_uy/$ty_ji;
			
		}
		$data["ghj"]=$ghj;
		$data["media"]=$mediaResult;

		$data["dataCount"] = $array["count"];



		

		$branch = lang('sell');
		$vhj = lang('invest');
		$delei = "";
		$delo = "0";
		$search = $_POST['search_name'];
		$Arraycostumers = array();

		$fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$boss_id'";
		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$gfid = $FetchedData[0]['id'];


		$delo = "0";
		$type = lang('invest');
		$sid =  $_SESSION['id'];
		$shopo =  $_SESSION['shop_id'];
		$typo =  $_SESSION['type'];
		$boss_id =  $_SESSION['boss_id'];
		$ql = "SELECT * FROM cos_transactions WHERE user_id=$gfid AND hide= '$delo' AND type='$type' ORDER BY datepost DESC";
		$cosData=$this->comman_model->get_all_data_by_query($ql);
		$cosArray= array();
		foreach($cosData as $item){
			$post_idy = $item['post_id'];
			$cos_id = $item['cos_id'];
			$cosArray["cos"][]=$item;
			$q = "SELECT * FROM transactions WHERE chak_id= $post_idy  AND hide= '$delo' ";
			$getData = $this->comman_model->get_all_data_by_query($q);
			$user_id=0;
			foreach($getData as $titem){
				$user_id = $titem['user_id'];
				$cosArray["cos"]["transactions"][$post_idy]=$titem;
			}

			$costquery = "SELECT * FROM costumers WHERE main_id=$cos_id ";
			$getcosData = $this->comman_model->get_all_data_by_query($costquery);
			foreach($getcosData as $cositem){
				$cosArray["cos"]["costumers"][$cos_id]=$titem;
			}

			$uquery = "SELECT Username FROM signup WHERE id=$user_id ";
			$getudata = $this->comman_model->get_all_data_by_query($uquery);
			foreach($getcosData as $uitem){
				$cosArray["cos"]["signup"][$user_id]=$uitem;
			}
			
		}
		$data["cos"]=$cosArray;
		$query = "SELECT * FROM treasury WHERE user_id = $gfid ";
		$FetchedData = $this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$data["treasrydata"]=$FetchedData;

		$vpsql = "SELECT main_id FROM costumers WHERE user_id= $gfid AND name= '$search' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		foreach($FetchedData as $postsfetch){
			$main_id = $postsfetch['main_id'];
			$Arraycostumers['costumers'][] = $postsfetch;
			$vpsql = "SELECT * FROM cos_transactions WHERE user_id=$gfid AND cos_id= $main_id AND hide= '$delo' ORDER BY datepost DESC";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);




			foreach($FetchedData as $postsfetch)
			{
				$headvk = $postsfetch['post_id'];
				$og = $postsfetch['og'];
				$cutr = $postsfetch['cutr'];
				$Arraycostumers['costumers']["Costransactions"][$main_id] = $postsfetch;

				$vpsql = "SELECT * FROM transactions WHERE chak_id= $headvk ";
				$FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
				$Arraycostumers['costumers']["transCount"]=count($FetchedData);
				foreach($FetchedData as $item){
					$Arraycostumers['costumers']["transactions"][$headvk]=count($FetchedData);
				}
				
				
			}
			
		}
		$data["transCount"]	= $Arraycostumers['costumers']["transCount"];
		$data["transdata"] = $Arraycostumers;
		
		$data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();

		$data["dircheckPath"]= base_url()."Asset/";
		$mode=LoadMode();
		$data["layoutmode"]  =   $mode;
		$data["bank"]="";


		
		$vpsql = "SELECT * FROM invest_treasury WHERE user_id=$boss_id AND name='$name' ";
		$FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
		$data["investtreasury"]=$FetchedData;
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':given_name', $name, PDO::PARAM_INT);
		// $view_posts->execute();
		//$numvh = $view_posts->rowCount();
		if($typem == "boss"){
			$gfid = $boss_id;
		}elseif($typem == "admin"){
			$gfid = $shop_id;
		}else{
			$gfid = $shop_id;
		}
		$delk = "0";
		$bgh = "cash";

		$vpsql = "SELECT * FROM treasury WHERE (kind='LYD' OR kind='USD') AND (tyi= '$bgh' AND shop_id=$gfid OR tyi= '$bgh' AND boss_id= $gfid)";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasury"]=$FetchedData;

	

		//============total of the money=============================
		$vpsql = "SELECT * FROM treasury WHERE tyi='$bgh' AND shop_id=  $gfid OR tyi= '$bgh' AND boss_id= $gfid";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasry"]=$FetchedData;


		/////////////////
		$boss_id = $_SESSION['boss_id'];
		$vpsql = "SELECT * FROM my_bank WHERE boss_id= $boss_id";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//$data["mybank"]=$FetchedData;

		$array=array();
		
		foreach($FetchedData as $postsfetch){
			$idn = $postsfetch['id'];
			$bank_name = $postsfetch['bank_name'];
			//============total of the money=============================
			$vpsql = "SELECT * FROM treasury WHERE wh=$idn";
			$transdata=$this->comman_model->get_all_data_by_query($vpsql);
			$array["bank"][] = $postsfetch;
			//$array["bank"][$idn] = $transdata;
			foreach($transdata as $item){
				$array["bank"][$idn][] = $item;
			}
			
			
		}
		
		$data["bank"]=$array;
		//////////////////////////////

		$this->load->view('Transaction/FastInvestment', $data);
	}




	public function wcos_transaction(){
			//include "../config/connect.php";
			//error_reporting(0);
			$post_id = rand(0,9999999)+time();
			$post_ido = rand(0,9999999)+time();
			$p_user_id = $_SESSION['id'];
			$p_author = $_SESSION['Fullname'];
			$p_author_photo = $_SESSION['Userphoto'];
			$boss_id =  $_SESSION['boss_id'];
			$shop_id =  $_SESSION['shop_id'];
			$timec = time();
			//==================from the form==============================
			$un = filter_var(htmlspecialchars($_POST['amou']),FILTER_SANITIZE_STRING);
			$p_time = filter_var(htmlspecialchars($_POST['time']),FILTER_SANITIZE_STRING);
			$pd = filter_var(htmlspecialchars($_POST['lyamou']),FILTER_SANITIZE_STRING);
			$usd = filter_var(htmlspecialchars($_POST['price']),FILTER_SANITIZE_STRING);
			$date = filter_var(htmlspecialchars($_POST['date']),FILTER_SANITIZE_STRING);
			$received_name = filter_var(htmlspecialchars($_POST['received_name']),FILTER_SANITIZE_STRING);
			$given_name = filter_var(htmlspecialchars($_POST['given_name']),FILTER_SANITIZE_STRING);
			$cosnam = filter_var(htmlspecialchars($_POST['cosnam']),FILTER_SANITIZE_STRING);
			$bankacco = filter_var(htmlspecialchars($_POST['bankacco']),FILTER_SANITIZE_STRING);
			$banknam = filter_var(htmlspecialchars($_POST['banknam']),FILTER_SANITIZE_STRING);
			$address = filter_var(htmlspecialchars($_POST['address']),FILTER_SANITIZE_STRING);
			$phone = filter_var(htmlspecialchars($_POST['phno']),FILTER_SANITIZE_STRING);
			$email = filter_var(htmlspecialchars($_POST['email']),FILTER_SANITIZE_STRING);
			$uprce = filter_var(htmlspecialchars($_POST['uprce']),FILTER_SANITIZE_STRING);
			$kighy = filter_var(htmlspecialchars($_POST['kighy']),FILTER_SANITIZE_STRING);
			$bprce = filter_var(htmlspecialchars($_POST['bprce']),FILTER_SANITIZE_STRING);
			$resnam = filter_var(htmlspecialchars($_POST['resnam']),FILTER_SANITIZE_STRING);
			$resbankacco = filter_var(htmlspecialchars($_POST['resbankacco']),FILTER_SANITIZE_STRING);
			$resbanknam = filter_var(htmlspecialchars($_POST['resbanknam']),FILTER_SANITIZE_STRING);
			$resaddress = filter_var(htmlspecialchars($_POST['resaddress']),FILTER_SANITIZE_STRING);
			$resphone = filter_var(htmlspecialchars($_POST['resphno']),FILTER_SANITIZE_STRING);
			$resemail = filter_var(htmlspecialchars($_POST['resemail']),FILTER_SANITIZE_STRING);
			$idjd = filter_var(htmlspecialchars($_POST['idjd']),FILTER_SANITIZE_STRING);
			$idvb = filter_var(htmlspecialchars($_POST['idvb']),FILTER_SANITIZE_STRING);
			$rescountry = filter_var(htmlspecialchars($_POST['rescountry']),FILTER_SANITIZE_STRING);
			$phno=$phone;
			$bank = "bank";
			$cash = "cash";
			if($cosnam == ""){
			$name = "casher";
			}else{
			$name = $cosnam;
			}
			$fas = "0";
			if($given_name == "LYD"){
			$kin = lang('sell');
			}else{
			$kin = lang('buy');
			}
			
			if($kighy == "chak"){
				
				$vpsql = "SELECT * FROM capital WHERE shop_id=$shop_id AND kind= '$received_name' AND tyi=  '$cash' ";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				$num = count($FetchedData); // $view_postsi->rowCount();
				foreach($FetchedData as $postsfetch) 
				{
					$numberhea = $postsfetch['number'];
					$exchangehea = $postsfetch['exchange'];
					$tyhea = $postsfetch['kind'];
					$ty_gt = $postsfetch['type'];
				}
				$vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE shop_id=$shop_id AND kind= '$given_name' AND tyi= '$cash' ";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				
				foreach($FetchedData as $postsfetch) {
					$ty_uy = $postsfetch['ty_uy'];
				}
				$vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE shop_id=$shop_id AND kind= '$given_name' AND tyi= '$cash' ";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				
				foreach ($FetchedData as $postsfetch) 
				{
					$ty_ji = $postsfetch['ty_ji'];
				}
				$medid= $ty_uy/$ty_ji;
				$media = number_format("$medid",2, ".", "");
			}else{
				
				$vpsql = "SELECT * FROM capital WHERE shop_id=$shop_id AND kind= '$received_name' AND tyi= '$bank' AND wh= '$idjd'";
				
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				
				$num = count($FetchedData); //$view_postsi->rowCount();
				foreach($FetchedData as $postsfetch)
				{
					$numberhea = $postsfetch['number'];
					$exchangehea = $postsfetch['exchange'];
					$tyhea = $postsfetch['kind'];
					$ty_gt = $postsfetch['type'];
				}
				
				$vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE shop_id=$shop_id AND kind= '$given_name' AND tyi= '$bank' AND wh= '$idjd' ";
				
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				$ty_uy=0;
				$ty_ji=0;
				
				foreach ($FetchedData  as  $postsfetch) {
					$ty_uy = $postsfetch['ty_uy'];
				}
				
				$vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE shop_id= $shop_id AND kind=  '$given_name' AND tyi= '$bank' AND wh= '$idjd' ";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				
				foreach($FetchedData as $postsfetch) {
					$ty_ji = $postsfetch['ty_ji'];
				}
				if($ty_ji=="" && $ty_uy==""){
					$ty_uy=1;
					$ty_ji=1;
				}
				$medid= $ty_uy/$ty_ji;

				
				$media = number_format("$medid",2, ".", "");
			}
			$vpsql = "SELECT * FROM costumers WHERE shop_id=$shop_id AND name='$name'";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			
			$num_name = count($FetchedData); // $view_postsi->rowCount();
			foreach( $FetchedData as $post_viewi)
			{
				$main_id = $post_viewi['main_id'];
			}
			
			if($num_name < 1)
			{
				$main_id = rand(0,9999999)+time();
				
					
				$data = array(
					'main_id'   => $main_id,
					'boss_id'      => $boss_id,
					'shop_id'      => $shop_id,
					'user_id'      => $p_user_id,
					'name'      => $name,
					'email'      => $email,
					'phone'      => $phno,
					'address'      => $address,

				);

				$this->comman_model->insert_entry("costumers",$data);			
			// $iptdbsqli = "INSERT INTO costumers
			// (main_id,boss_id,shop_id,user_id,name,email,phone,address)
			// VALUES
			// ( :main_id, :boss_id, :shop_id, :user_id, :name, :email, :phno, :address)
			// ";
			// $insert_post_toDBi = $conn->prepare($iptdbsqli);
			// $insert_post_toDBi->bindParam(':main_id', $main_id,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':user_id', $p_user_id,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':name', $name,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':address', $address,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':phno', $phno,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':email', $email,PDO::PARAM_STR);
			// $insert_post_toDBi->execute();
			}
			else{
				
			if($address != ""){
				$data = array(
                    'address'   => $address   
                );
				
            	$where=array('shop_id' => $shop_id,'name' => $name);
                $update_info=$this->comman_model->update_entry("costumers",$data,$where);
				
				// $iptdbsql = "UPDATE costumers SET address=:address WHERE shop_id=:sid AND name=:name";
				// $insert_post_toDB = $conn->prepare($iptdbsql);
				// $insert_post_toDB->bindParam(':address', $address,PDO::PARAM_STR);
				// $insert_post_toDB->bindParam(':name', $name,PDO::PARAM_STR);
				// $insert_post_toDB->bindParam(':sid', $shop_id,PDO::PARAM_INT);
				// $insert_post_toDB->execute();
			}if($phno != ""){
				
				$data = array(
                    'phone'   => $phno   
                );               
				
            	$where=array('shop_id' => $shop_id,'name' => $name);
                $update_info=$this->comman_model->update_entry("costumers",$data,$where);
				
				// $iptdbsql = "UPDATE costumers SET phone=:phno WHERE shop_id=:sid AND name=:name";
				// $insert_post_toDB = $conn->prepare($iptdbsql);
				// $insert_post_toDB->bindParam(':phno', $phno,PDO::PARAM_STR);
				// $insert_post_toDB->bindParam(':name', $name,PDO::PARAM_STR);
				// $insert_post_toDB->bindParam(':sid', $shop_id,PDO::PARAM_INT);
				// $insert_post_toDB->execute();
				}if($email != ""){
					
					$data = array(
						'email'   => $email   
					);               
					$where=array('shop_id' => $shop_id,'name' => $name);
					$update_info=$this->comman_model->update_entry("costumers",$data,$where);
					
					// $iptdbsql = "UPDATE costumers SET email=:email WHERE shop_id=:sid AND name=:name";
					// $insert_post_toDB = $conn->prepare($iptdbsql);
					// $insert_post_toDB->bindParam(':email', $email,PDO::PARAM_STR);
					// $insert_post_toDB->bindParam(':name', $name,PDO::PARAM_STR);
					// $insert_post_toDB->bindParam(':sid', $shop_id,PDO::PARAM_INT);
					// $insert_post_toDB->execute();
				}
			}
			
			if($kighy == "chak"){
			if($kin == lang('sell')){
				//======================check the cad====================
			//======================taken cash usd===========
				$vpsql = "SELECT * FROM treasury WHERE shop_id= $shop_id AND kind= '$received_name' AND tyi= '$cash' ";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				// $view_posts = $conn->prepare($vpsql);
				// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
				// $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
				// $view_posts->bindParam(':cash', $cash, PDO::PARAM_INT);
				// $view_posts->execute();
				$numvf = count($FetchedData); //$view_posts->rowCount();
				foreach ($FetchedData as $postsfetch) {
					$numberya = $postsfetch['number'];
				}
			//==================given lyd bank==================
				$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind= '$given_name' AND wh= $idjd AND tyi='$bank' ";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				// $view_posts = $conn->prepare($vpsql);
				// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
				// $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
				// $view_posts->bindParam(':bank', $bank, PDO::PARAM_INT);
				// $view_posts->bindParam(':idjd', $idjd, PDO::PARAM_INT);
				// $view_posts->execute();
				$numvh = count($FetchedData); //$view_posts->rowCount();
				foreach ($FetchedData as $postsfetch) 
				{
					$numberyb = $postsfetch['number'];
				}
				//a taken
				$numbero = $numberya+$un;
				//b given
				$numberb = $numberyb-$pd;
				if($numberyb >= $pd){
					unset($_SESSION['myerrorch']);
				}else{
					$_SESSION['myerrorch'] = number_format("$numberyb",2, ".", "")." $given_name :".lang('youhave');
					return false;
				}
				//a
				if($numvf < 1){


					$data = array(
						'user_id'   => $p_user_id,
						'shop_id'      => $shop_id,
						'boss_id'      => $boss_id,
						'kind'      => $received_name,
						'number'      => $numbero,
						'wh'      => $fas,
						'tyi'      => $cash	
					);
	
					$this->comman_model->insert_entry("treasury",$data);			

					// $iptdbsql = "INSERT INTO treasury
					// (user_id,shop_id,boss_id,kind,number,wh,tyi)
					// VALUES
					// ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
					// ";
					// $insert_post_toDB = $conn->prepare($iptdbsql);
					// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':un', $numbero,PDO::PARAM_STR);
					// $insert_post_toDB->bindParam(':id', $fas, PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
					// $insert_post_toDB->bindParam(':bgh', $cash, PDO::PARAM_STR);
					// $insert_post_toDB->execute();
				}else{
					$data = array(
						'number'   => $numbero   
					);               
					$where=array('shop_id' => $shop_id,'kind' => $received_name, 'tyi' => $cash );
					$update_info=$this->comman_model->update_entry("treasury",$data,$where);

					// $iptdbsql = "UPDATE treasury SET number=:numbero WHERE shop_id = :p_user_id AND kind=:received_name AND tyi=:cash";
					// $insert_post_toDB = $conn->prepare($iptdbsql);
					// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':cash', $cash, PDO::PARAM_INT);
					// $insert_post_toDB->execute();
				}
				// b
				if($numvh < 1){
					
					$data = array(
						'user_id'   => $p_user_id,
						'shop_id'      => $shop_id,
						'boss_id'      => $boss_id,
						'kind'      => $received_name,
						'number'      => $numberb,
						'wh'      => $idjd,
						'tyi'      => $bank	
					);
	
					$this->comman_model->insert_entry("treasury",$data);	
					// $iptdbsql = "INSERT INTO treasury
					// (user_id,shop_id,boss_id,kind,number,wh,tyi)
					// VALUES
					// ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
					// ";
					// $insert_post_toDB = $conn->prepare($iptdbsql);
					// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':un', $numberb,PDO::PARAM_STR);
					// $insert_post_toDB->bindParam(':id', $idjd, PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':received_name', $given_name,PDO::PARAM_STR);
					// $insert_post_toDB->bindParam(':bgh', $bank, PDO::PARAM_STR);
					// $insert_post_toDB->execute();
				}else{
					$data = array(
						'number'   => $numberb   
					);               
					$where=array('shop_id' => $shop_id,'kind' => $given_name, 'wh' => $idjd , 'tyi' => $bank );
					// $update_info=$this->comman_model->update_entry("treasury",$data,$where);
					// $iptdbsql = "UPDATE treasury SET number=:numberb WHERE shop_id = :p_user_id AND kind=:given_name AND wh=:idjd AND tyi=:bank";
					// $insert_post_toDB = $conn->prepare($iptdbsql);
					// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':numberb', $numberb,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':bank', $bank, PDO::PARAM_INT);
					// $insert_post_toDB->execute();
			}
			}elseif($kin == lang('buy')){
				//======================check the cad====================
				//======================taken bank===========
				
					$vpsql = "SELECT * FROM treasury WHERE shop_id= $shop_id AND kind= '$received_name' AND wh= '$idjd' AND tyi= '$bank' ";
					$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);

					// $view_posts = $conn->prepare($vpsql);
					// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
					// $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
					// $view_posts->bindParam(':bank', $bank, PDO::PARAM_INT);
					// $view_posts->bindParam(':idjd', $idjd, PDO::PARAM_INT);
					// $view_posts->execute();
					$numvf =  count($FetchedData); //$view_posts->rowCount();
					foreach($FetchedData as  $postsfetch)
					{
						$numberya = $postsfetch['number'];
					}
				//==================given cash==================
					$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind= '$given_name' AND tyi= '$cash' ";
					$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
					// $view_posts = $conn->prepare($vpsql);
					// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
					// $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
					// $view_posts->bindParam(':cash', $cash, PDO::PARAM_INT);
					// $view_posts->execute();
					$numvh = count($FetchedData); // $view_posts->rowCount();
					foreach($FetchedData as $postsfetch) 
					{
						$numberyb = $postsfetch['number'];
					}
					//a taken
					$numbero = $numberya+$un;
					//b given
					$numberb = $numberyb-$pd;
					if($numberyb >= $pd){
						unset($_SESSION['myerrorch']);
					}else{
						$_SESSION['myerrorch'] = number_format("$numberyb",2, ".", "")."  $given_name :".lang('youhave');
						return false;
					}
					//a
					if($numvf < 1){

						$data = array(
							'user_id'   => $p_user_id,
							'shop_id'      => $shop_id,
							'boss_id'      => $boss_id,
							'kind'      => $received_name,
							'number'      => $numbero,
							'wh'      => $idjd,
							'tyi'      => $bank	
						);
		
						$this->comman_model->insert_entry("treasury",$data);
						// $iptdbsql = "INSERT INTO treasury
						// (user_id,shop_id,boss_id,kind,number,wh,tyi)
						// VALUES
						// ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
						// ";
						// $insert_post_toDB = $conn->prepare($iptdbsql);
						// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':un', $numbero,PDO::PARAM_STR);
						// $insert_post_toDB->bindParam(':id', $idjd, PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
						// $insert_post_toDB->bindParam(':bgh', $bank, PDO::PARAM_STR);
						// $insert_post_toDB->execute();
					}else{
						$data = array(
							'number'   => $numbero   
						);               
						$where=array('shop_id' => $shop_id,'kind' => $received_name,'wh'=> $idjd , 'tyi' => $bank );
						$update_info=$this->comman_model->update_entry("treasury",$data,$where);

						// $iptdbsql = "UPDATE treasury SET number=:numbero WHERE shop_id = :p_user_id AND kind=:received_name AND wh=:idjd AND tyi=:bank";
						// $insert_post_toDB = $conn->prepare($iptdbsql);
						// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':bank', $bank, PDO::PARAM_INT);
						// $insert_post_toDB->execute();
			}
					// b
			if($numvh < 1){
				$data = array(
					'user_id'   => $p_user_id,
					'shop_id'      => $shop_id,
					'boss_id'      => $boss_id,
					'kind'      => $given_name,
					'number'      => $numberb,
					'wh'      => $fas,
					'tyi'      => $cash	
				);

				$this->comman_model->insert_entry("treasury",$data);

				// 	$iptdbsql = "INSERT INTO treasury
				// (user_id,shop_id,boss_id,kind,number,wh,tyi)
				// VALUES
				// ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
				// ";
				// $insert_post_toDB = $conn->prepare($iptdbsql);
				// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':un', $numberb,PDO::PARAM_STR);
				// $insert_post_toDB->bindParam(':id', $fas, PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':received_name', $given_name,PDO::PARAM_STR);
				// $insert_post_toDB->bindParam(':bgh', $cash, PDO::PARAM_STR);
				// $insert_post_toDB->execute();
			}else{
				$data = array(
					'number'   => $numberb   
				);               
				$where=array('shop_id' => $shop_id,'kind' => $given_name,'wh'=> $idjd , 'tyi' => $cash );
				$update_info=$this->comman_model->update_entry("treasury",$data,$where);

					// 	$iptdbsql = "UPDATE treasury SET number=:numberb WHERE shop_id = :p_user_id AND kind=:given_name AND tyi=:cash";
					// $insert_post_toDB = $conn->prepare($iptdbsql);
					// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':numberb', $numberb,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':cash', $cash, PDO::PARAM_INT);
					// $insert_post_toDB->execute();
			}
			}

			$ty_kin = "chak";
			//=====================insert into cash=====================
			$data = array(
				'post_id' => $post_ido,
				'user_id'   => $p_user_id,
				'cos_id'      => $main_id,
				'bankacc'      => $bankacco,
				'bankname'      => $banknam,
				'uprcen'      => $uprce,
				'type'      => $ty_kin,
				'idjd'      => $idjd	
			);

			$this->comman_model->insert_entry("cos_transactions",$data);

			// $iptdbsqli = "INSERT INTO cos_transactions
			// (post_id,user_id,cos_id,bankacc,bankname,uprcen,type,idjd)
			// VALUES
			// ( :post_ido, :p_user_id, :cosnam, :bankacco, :banknam, :uprce, :type, :idjd)
			// ";
			// $insert_post_toDBi = $conn->prepare($iptdbsqli);
			// $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':cosnam', $main_id,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':idjd', $idjd,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':bankacco', $bankacco,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':banknam', $banknam,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':uprce', $uprce,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':type', $ty_kin,PDO::PARAM_STR);
			// $insert_post_toDBi->execute();
			}elseif($kighy == "cards"){
				
			if($kin == lang('sell')){
				
				//======================check the cad====================
			//======================taken bank===========
				$vpsql = "SELECT * FROM treasury WHERE shop_id= $shop_id AND kind='$received_name' AND wh='$idjd' AND tyi='$bank'";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);

				// $view_posts = $conn->prepare($vpsql);
				// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
				// $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_STR);
				// $view_posts->bindParam(':idjd', $idjd, PDO::PARAM_INT);
				// $view_posts->bindParam(':bank', $bank, PDO::PARAM_STR);
				// $view_posts->execute();
				$numvf = count($FetchedData);// $view_posts->rowCount();
				foreach ($FetchedData as $postsfetch) 
				{
					$numberya = $postsfetch['number'];
				}
			//==================given cash==================
				$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$given_name' AND tyi='$cash'";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				// $view_posts = $conn->prepare($vpsql);
				// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
				// $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_STR);
				// $view_posts->bindParam(':cash', $cash, PDO::PARAM_STR);
				// $view_posts->execute();
				$numvh = count($FetchedData);// $view_posts->rowCount();
				foreach ($FetchedData as $postsfetch) {
					$numberyb = $postsfetch['number'];
				}
				//a taken
				$numbero = $numberya+$un;
				//b given
				$numberb = $numberyb-$pd;
				
				if($numberyb >= $pd){
							unset($_SESSION['myerrorca']);
						}else{
							if($numberyb==""){
								$numberyb=0;
							}
							$_SESSION['myerrorca'] = number_format("$numberyb",2, ".", "")." $given_name :".lang('youhave');
							return false;
						}
				//a
				if($numvf < 1){
					$data = array(
						'user_id' => $p_user_id,
						'shop_id'   => $shop_id,
						'boss_id'      => $boss_id,
						'kind'      => $received_name,
						'number'      => $numbero,
						'wh'      => $idjd,
						'tyi'      => $bank
					);
		
					$this->comman_model->insert_entry("treasury",$data);

					// 	$iptdbsql = "INSERT INTO treasury
					// (user_id,shop_id,boss_id,kind,number,wh,tyi)
					// VALUES
					// ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
					// ";
					// $insert_post_toDB = $conn->prepare($iptdbsql);
					// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':un', $numbero,PDO::PARAM_STR);
					// $insert_post_toDB->bindParam(':id', $idjd, PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
					// $insert_post_toDB->bindParam(':bgh', $bank, PDO::PARAM_STR);
					// $insert_post_toDB->execute();
				}else{
				$iptdbsql = "UPDATE treasury SET number='$numbero' WHERE shop_id = $shop_id AND kind='$received_name' AND wh='$idjd' AND tyi='$bank'";
				$this->comman_model->run_query($iptdbsql);

				// $insert_post_toDB = $conn->prepare($iptdbsql);
				// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':bank', $bank, PDO::PARAM_INT);
				// $insert_post_toDB->execute();
			}
			if($numvh < 1){
				$data = array(
					'user_id' => $p_user_id,
					'shop_id'   => $shop_id,
					'boss_id'      => $boss_id,
					'kind'      => $given_name,
					'number'      => $numberb,
					'wh'      => $fas,
					'tyi'      => $cash
				);
	
				$this->comman_model->insert_entry("treasury",$data);


			// $iptdbsql = "INSERT INTO treasury
			// (user_id,shop_id,boss_id,kind,number,wh,tyi)
			// VALUES
			// ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
			// ";
			// $insert_post_toDB = $conn->prepare($iptdbsql);
			// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':un', $numberb,PDO::PARAM_STR);
			// $insert_post_toDB->bindParam(':id', $fas, PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':received_name', $given_name,PDO::PARAM_STR);
			// $insert_post_toDB->bindParam(':bgh', $cash, PDO::PARAM_STR);
			// $insert_post_toDB->execute();
			}else{
				// b
				$iptdbsql = "UPDATE treasury SET number='$numberb' WHERE shop_id = $shop_id AND kind='$given_name' AND tyi='$cash'";
				$this->comman_model->run_query($iptdbsql);
				// $insert_post_toDB = $conn->prepare($iptdbsql);
				// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':numberb', $numberb,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':cash', $cash, PDO::PARAM_INT);
				// $insert_post_toDB->execute();
			}
			}elseif($kin == lang('buy')){
				//======================check the cad====================
				//======================a taken bank===========
				
					$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$received_name' AND wh='$idjd' AND tyi='$bank'";
					$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);

					// $view_posts = $conn->prepare($vpsql);
					// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
					// $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
					// $view_posts->bindParam(':idjd', $idjd, PDO::PARAM_INT);
					// $view_posts->bindParam(':bank', $bank, PDO::PARAM_INT);
					// $view_posts->execute();
					$numvf = count($FetchedData);// $view_posts->rowCount();
					foreach ($FetchedData as $postsfetch) 
					{
						$numberya = $postsfetch['number'];
					}
				//==================b given cash==================
					$vpsql = "SELECT * FROM treasury WHERE shop_id= $shop_id AND kind='$given_name' AND tyi='$cash'";
					$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
					// $view_posts = $conn->prepare($vpsql);
					// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
					// $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
					// $view_posts->bindParam(':cash', $cash, PDO::PARAM_INT);
					// $view_posts->execute();
					$numvh = count($FetchedData);// $view_posts->rowCount();
					foreach ($FetchedData as $postsfetch) 
					{
						$numberyb = $postsfetch['number'];
					}
					//a taken
					$numbero = $numberya+$un;
					//b given
					$numberb = $numberyb-$pd;
					if($numberyb >= $pd){
								unset($_SESSION['myerrorca']);
							}else{
								$_SESSION['myerrorca'] = number_format("$numberyb",2, ".", "")." $given_name :".lang('youhave');
								return false;
							}
					//a
					if($numvf < 1){
						$data = array(
							'user_id' => $p_user_id,
							'shop_id'   => $shop_id,
							'boss_id'      => $boss_id,
							'kind'      => $received_name,
							'number'      => $numbero,
							'wh'      => $idjd,
							'tyi'      => $bank
						);
			
						$this->comman_model->insert_entry("treasury",$data);

				// 	$iptdbsql = "INSERT INTO treasury
				// (user_id,shop_id,boss_id,kind,number,wh,tyi)
				// VALUES
				// ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
				// ";
				// $insert_post_toDB = $conn->prepare($iptdbsql);
				// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':un', $numbero,PDO::PARAM_STR);
				// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
				// $insert_post_toDB->bindParam(':id', $idjd, PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':bgh', $bank, PDO::PARAM_STR);
				// $insert_post_toDB->execute();
					}else{
						$iptdbsql = "UPDATE treasury SET number='$numbero' WHERE user_id = $shop_id AND kind='$received_name' AND wh='$idjd' AND tyi='$bank'";
						$this->comman_model->run_query($iptdbsql);
					// $insert_post_toDB = $conn->prepare($iptdbsql);
					// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':bank', $bank, PDO::PARAM_INT);
					// $insert_post_toDB->execute();
			}
			if($numvh < 1){
				$data = array(
					'user_id' => $p_user_id,
					'shop_id'   => $shop_id,
					'boss_id'      => $boss_id,
					'kind'      => $given_name,
					'number'      => $numberb,
					'wh'      => $fas,
					'tyi'      => $cash
				);
	
				$this->comman_model->insert_entry("treasury",$data);


			// $iptdbsql = "INSERT INTO treasury
			// (user_id,shop_id,boss_id,kind,number,wh,tyi)
			// VALUES
			// ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
			// ";
			// $insert_post_toDB = $conn->prepare($iptdbsql);
			// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':un', $numberb,PDO::PARAM_STR);
			// $insert_post_toDB->bindParam(':id', $fas, PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':received_name', $given_name,PDO::PARAM_STR);
			// $insert_post_toDB->bindParam(':bgh', $cash, PDO::PARAM_STR);
			// $insert_post_toDB->execute();
			}else{
					// b
						$iptdbsql = "UPDATE treasury SET number='$numberb' WHERE shop_id = $shop_id AND kind='$given_name' AND tyi='$cash'";
						$this->comman_model->run_query($iptdbsql);
					// $insert_post_toDB = $conn->prepare($iptdbsql);
					// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':numberb', $numberb,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':cash', $cash, PDO::PARAM_INT);
					// $insert_post_toDB->execute();
			}
			}

			//===============end of the checking=================================
			$ty_kin = "cards";
			$data = array(
				'post_id' => $post_ido,
				'user_id'   => $p_user_id,
				'cos_id'      => $main_id,
				'bankname'      => $banknam,
				'uprcen'      => $uprce,
				'bankprcen'      => $bprce,
				'type'      => $ty_kin,
				'idjd'		=>	$idjd

			);

			$this->comman_model->insert_entry("cos_transactions",$data);

				// $iptdbsqli = "INSERT INTO cos_transactions
				// (post_id,user_id,cos_id,bankname,uprcen,bankprcen,type,idjd)
				// VALUES
				// ( :post_ido, :p_user_id, :cosnam, :banknam, :uprce, :bprce, :type, :idjd)
				// ";
				// $insert_post_toDBi = $conn->prepare($iptdbsqli);
				// $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
				// $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
				// $insert_post_toDBi->bindParam(':cosnam', $main_id,PDO::PARAM_INT);
				// $insert_post_toDBi->bindParam(':idjd', $idjd,PDO::PARAM_STR);
				// $insert_post_toDBi->bindParam(':banknam', $banknam,PDO::PARAM_STR);
				// $insert_post_toDBi->bindParam(':uprce', $uprce,PDO::PARAM_STR);
				// $insert_post_toDBi->bindParam(':bprce', $bprce,PDO::PARAM_STR);
				// $insert_post_toDBi->bindParam(':type', $ty_kin,PDO::PARAM_STR);
				// $insert_post_toDBi->execute();
			}elseif($kighy == "transfar"){
			if($kin == lang('sell')){
				//======================check the cad====================
			//======================taken bank===========
				$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$received_name' AND wh='$idjd' AND tyi='$bank'";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				// $view_posts = $conn->prepare($vpsql);
				// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
				// $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
				// $view_posts->bindParam(':idjd', $idjd, PDO::PARAM_INT);
				// $view_posts->bindParam(':bank', $bank, PDO::PARAM_INT);
				// $view_posts->execute();
				$numvf = count($FetchedData);//$view_posts->rowCount();
				foreach ($FetchedData as $postsfetch) {
					$numberya = $postsfetch['number'];
				}
			if($idvb == ""){
			//==================given cash==================
				$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$given_name' AND tyi=$cash";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);

				// $view_posts = $conn->prepare($vpsql);
				// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
				// $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
				// $view_posts->bindParam(':cash', $cash, PDO::PARAM_INT);
				// $view_posts->execute();
				$numvh = count($FetchedData);///$view_posts->rowCount();
				foreach ($FetchedData as $postsfetch) {
					$numberyb = $postsfetch['number'];
				}
			}else{
			$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$given_name' AND wh='$idjd' AND tyi='$bank'";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			// $view_posts = $conn->prepare($vpsql);
			// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
			// $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
			// $view_posts->bindParam(':bank', $bank, PDO::PARAM_INT);
			// $view_posts->bindParam(':idjd', $idjd, PDO::PARAM_INT);
			// $view_posts->execute();
			$numvf = count();// $view_posts->rowCount();
			foreach ($FetchedData as $postsfetch) {
				$numberya = $postsfetch['number'];
			}
			}
				//a taken
				$numbero = $numberya+$un;
				//b given
				$numberb = $numberyb-$pd;
				if($numberyb >= $pd){
							unset($_SESSION['myerrortr']);
						}else{
							$_SESSION['myerrortr'] = number_format("$numberyb",2, ".", "")." $given_name :".lang('youhave');
							return false;
						}
				//a
				if($numvf < 1){
					$data = array(
						'user_id' => $p_user_id,
						'shop_id'   => $shop_id,
						'boss_id'      => $boss_id,
						'kind'      => $received_name,
						'number'      => $numbero,
						'wh'      => $idjd,
						'tyi'      => $bank
		
					);
		
					$this->comman_model->insert_entry("treasury",$data);

			// 	$iptdbsql = "INSERT INTO treasury
			// (user_id,shop_id,boss_id,kind,number,wh,tyi)
			// VALUES
			// ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
			// ";
			// $insert_post_toDB = $conn->prepare($iptdbsql);
			// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':un', $numbero,PDO::PARAM_STR);
			// $insert_post_toDB->bindParam(':id', $idjd, PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
			// $insert_post_toDB->bindParam(':bgh', $bank, PDO::PARAM_STR);
			// $insert_post_toDB->execute();
				}else{
					$iptdbsql = "UPDATE treasury SET number='$numbero' WHERE shop_id = $shop_id AND kind='$received_name' AND wh='$idjd' AND tyi='$bank'";
					$this->comman_model->run_query($iptdbsql);
				// $insert_post_toDB = $conn->prepare($iptdbsql);
				// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':bank', $bank, PDO::PARAM_INT);
				// $insert_post_toDB->execute();
			}
			if($idvb == ""){
			if($numvf < 1){
				$data = array(
					'user_id' => $p_user_id,
					'shop_id'   => $shop_id,
					'boss_id'      => $boss_id,
					'kind'      => $given_name,
					'number'      => $numberb,
					'wh'      => $fas,
					'tyi'      => $cash
	
				);
	
				$this->comman_model->insert_entry("treasury",$data);


			// $iptdbsql = "INSERT INTO treasury
			// (user_id,shop_id,boss_id,kind,number,wh,tyi)
			// VALUES
			// ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
			// ";
			// $insert_post_toDB = $conn->prepare($iptdbsql);
			// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':un', $numberb,PDO::PARAM_STR);
			// $insert_post_toDB->bindParam(':id', $fas, PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':received_name', $given_name,PDO::PARAM_STR);
			// $insert_post_toDB->bindParam(':bgh', $cash, PDO::PARAM_STR);
			// $insert_post_toDB->execute();
			}else{
				// b
					$iptdbsql = "UPDATE treasury SET number='$numberb' WHERE shop_id = $shop_id AND kind='$given_name' AND tyi='$cash'";
					$this->comman_model->run_query($iptdbsql);
				// $insert_post_toDB = $conn->prepare($iptdbsql);
				// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':numberb', $numberb,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':cash', $cash, PDO::PARAM_INT);
				// $insert_post_toDB->execute();
			}
			}else{
			if($numvf < 1){
				$data = array(
					'user_id' => $p_user_id,
					'shop_id'   => $shop_id,
					'boss_id'      => $boss_id,
					'kind'      => $given_name,
					'number'      => $numbero,
					'wh'      => $idjd,
					'tyi'      => $bank
	
				);
	
				$this->comman_model->insert_entry("treasury",$data);



			// 	$iptdbsql = "INSERT INTO treasury
			// (user_id,shop_id,boss_id,kind,number,wh,tyi)
			// VALUES
			// ( :p_user_id, :shop_id, :boss_id, :given_name, :un , :id, :bgh)
			// ";
			// $insert_post_toDB = $conn->prepare($iptdbsql);
			// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':un', $numbero,PDO::PARAM_STR);
			// $insert_post_toDB->bindParam(':id', $idjd, PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_STR);
			// $insert_post_toDB->bindParam(':bgh', $bank, PDO::PARAM_STR);
			// $insert_post_toDB->execute();
			}else{
					$iptdbsql = "UPDATE treasury SET number='$numbero' WHERE shop_id = $shop_id AND kind='$given_name' AND wh='$idjd' AND tyi='$bank'";
					$this->comman_model->run_query($iptdbsql);
				// $insert_post_toDB = $conn->prepare($iptdbsql);
				// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
				// $insert_post_toDB->bindParam(':bank', $bank, PDO::PARAM_INT);
				// $insert_post_toDB->execute();
			}
			}
			}elseif($kin == lang('buy')){
				
				//======================check the cad====================
				//======================taken cash===========
				if($idvb == ""){
					$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind= '$received_name' AND tyi= '$cash' ";
					$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
					// $view_posts = $conn->prepare($vpsql);
					// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
					// $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
					// $view_posts->bindParam(':cash', $cash, PDO::PARAM_INT);
					// $view_posts->execute();
					$numvf = count($FetchedData); ///$view_posts->rowCount();
					foreach($FetchedData as $postsfetch) 
					{
						$numberya = $postsfetch['number'];
					}
			}else{
			$vpsql = "SELECT * FROM treasury WHERE shop_id= $shop_id AND kind= '$received_name' AND tyi= '$bank' AND wh= '$idjd' ";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			// $view_posts = $conn->prepare($vpsql);
			// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
			// $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
			// $view_posts->bindParam(':bank', $bank, PDO::PARAM_INT);
			// $view_posts->bindParam(':idjd', $idjd, PDO::PARAM_INT);
			// $view_posts->execute();
			$numvh = count($FetchedData); //  $view_posts->rowCount();
			foreach($FetchedData as $postsfetch) {
				$numberyb = $postsfetch['number'];
			}
			}
				//==================given bank==================
					$vpsql = "SELECT * FROM treasury WHERE shop_id = $shop_id AND kind= '$$given_name' AND tyi= '$bank' AND wh= $idjd ";
					$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
					// $view_posts = $conn->prepare($vpsql);
					// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
					// $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
					// $view_posts->bindParam(':bank', $bank, PDO::PARAM_INT);
					// $view_posts->bindParam(':idjd', $idjd, PDO::PARAM_INT);
					// $view_posts->execute();
					$numvh = count($FetchedData);  //$view_posts->rowCount();
					foreach($FetchedData as  $postsfetch) {
						$numberyb = $postsfetch['number'];
					}
					//a taken
					$numbero = $numberya+$un;
					//b given
					$numberb = $numberyb-$pd;
					if($numberyb >= $pd){
								unset($_SESSION['myerrortr']);
							}else{
								$_SESSION['myerrortr'] = number_format("$numberyb",2, ".", "")." $given_name :".lang('youhave');
								return false;
							}
					//a
					if($idvb == ""){
					if($numvf < 1){
						$data = array(
							'user_id'   => $p_user_id,
							'shop_id'      => $shop_id,
							'boss_id'      => $boss_id,
							'kind'      => $received_name,
							'number'	=> $numbero,
							'wh'      => $fas,
							'tyi'      => $cash
						);
						$this->comman_model->insert_entry("treasury",$data);

						// $iptdbsql = "INSERT INTO treasury
						// (user_id,shop_id,boss_id,kind,number,wh,tyi)
						// VALUES
						// ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
						// ";
						// $insert_post_toDB = $conn->prepare($iptdbsql);
						// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':un', $numbero,PDO::PARAM_STR);
						// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':id', $fas, PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
						// $insert_post_toDB->bindParam(':bgh', $cash, PDO::PARAM_STR);
						// $insert_post_toDB->execute();
					}else{
						$data = array(
                            'number'   => $numbero                         
                        );
                        
                        $where=array('shop_id' => $shop_id, 'kind' => $received_name, 'tyi' =>  $cash);
                        $update_info=$this->comman_model->update_entry("treasury",$data,$where);
                    

						// $iptdbsql = "UPDATE treasury SET number=:numbero WHERE shop_id = :p_user_id AND kind=:received_name AND tyi=:cash";
						// $insert_post_toDB = $conn->prepare($iptdbsql);
						// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':cash', $cash, PDO::PARAM_INT);
						// $insert_post_toDB->execute();
					}
			}else{
			if($numvf < 1){
						$data = array(
							'user_id'   => $p_user_id,
							'shop_id'      => $shop_id,
							'boss_id'      => $boss_id,
							'kind'      => $received_name,
							'number'	=> $numbero,
							'wh'      => $idjd,
							'tyi'      => $bank
						);
						$this->comman_model->insert_entry("treasury",$data);
						// $iptdbsql = "INSERT INTO treasury
						// (user_id,shop_id,boss_id,kind,number,wh,tyi)
						// VALUES
						// ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
						// ";
						// $insert_post_toDB = $conn->prepare($iptdbsql);
						// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':un', $numbero,PDO::PARAM_STR);
						// $insert_post_toDB->bindParam(':id', $idjd, PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
						// $insert_post_toDB->bindParam(':bgh', $bank, PDO::PARAM_STR);
						// $insert_post_toDB->execute();
					}else{
					// b
						$data = array(
							'number'   => $numbero                         
						);
						
						$where=array('shop_id' => $shop_id, 'kind' => $received_name,'wh' => $idjd , 'tyi' =>  $bank);
						$update_info=$this->comman_model->update_entry("treasury",$data,$where);

						// $iptdbsql = "UPDATE treasury SET number=:numberb WHERE shop_id = :p_user_id AND kind=:received_name AND wh=:idjd AND tyi=:bank";
						// $insert_post_toDB = $conn->prepare($iptdbsql);
						// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':numberb', $numbero,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
						// $insert_post_toDB->bindParam(':bank', $bank, PDO::PARAM_INT);
						// $insert_post_toDB->execute();
			}
			}
			//================================== X ============================
			if($numvh < 1){
					$data = array(
						'user_id'   => $p_user_id,
						'shop_id'      => $shop_id,
						'boss_id'      => $boss_id,
						'kind'      => $given_name,
						'number'	=> $numberb,
						'wh'      => $idjd,
						'tyi'      => $bank
					);
					$this->comman_model->insert_entry("treasury",$data);
					// $iptdbsql = "INSERT INTO treasury
					// (user_id,shop_id,boss_id,kind,number,wh,tyi)
					// VALUES
					// ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
					// ";
					// $insert_post_toDB = $conn->prepare($iptdbsql);
					// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':un', $numberb,PDO::PARAM_STR);
					// $insert_post_toDB->bindParam(':id', $idjd, PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':received_name', $given_name,PDO::PARAM_STR);
					// $insert_post_toDB->bindParam(':bgh', $bank, PDO::PARAM_STR);
					// $insert_post_toDB->execute();
					}else{
					// b
					$data = array(
						'number'   => $numberb                         
					);
					
					$where=array('shop_id' => $shop_id, 'kind' => $given_name,'wh' => $idjd , 'tyi' =>  $bank);
					$update_info=$this->comman_model->update_entry("treasury",$data,$where);
					
					// $iptdbsql = "UPDATE treasury SET number=:numberb WHERE shop_id = :p_user_id AND kind=:given_name AND wh=:idjd AND tyi=:bank";
					// $insert_post_toDB = $conn->prepare($iptdbsql);
					// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':numberb', $numberb,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':bank', $bank, PDO::PARAM_INT);
					// $insert_post_toDB->execute();
			}
			}
			$ty_kin = "transfar";
			
			$data = array(
				'post_id' 	=> $post_ido,
				'user_id'   => $p_user_id,
				'cos_id'      => $main_id,
				'bankacc'      => $bankacco,
				'bankname' 	=> $banknam,
				'uprcen'      => $uprce,
				'bankprcen'	=> $bprce,
				'type'      => $ty_kin,
				'resnam'      => $resnam,
				'resbankacco'      => $resbankacco,
				'resbanknam'	=> $resbanknam,
				'resaddress'      => $resaddress,
				'resphone'      => $resphone,
				'resemail'      => $resemail,
				'rescountry'      => $rescountry,
				'idjd'      => $idjd,
			);
			$this->comman_model->insert_entry("cos_transactions",$data);

			// $iptdbsqli = "INSERT INTO cos_transactions
			// (post_id,user_id,cos_id,bankacc,bankname,uprcen,bankprcen,type,resnam,resbankacco,resbanknam,resaddress,resphone
			// ,resemail,rescountry,idjd)
			// VALUES
			// ( :post_ido, :p_user_id, :cosnam, :bankacco, :banknam, :uprce, :bprce, :type, :resnam, :resbankacco, 
			// :resbanknam, :resaddress, :resphone, :resemail, :rescountry,:idjd)
			// ";
			// $insert_post_toDBi = $conn->prepare($iptdbsqli);
			// $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':cosnam', $main_id,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':bankacco', $bankacco,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':banknam', $banknam,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':uprce', $uprce,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':idjd', $idjd,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':bprce', $bprce,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':type', $ty_kin,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':resnam', $resnam,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':resbankacco', $resbankacco,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':resbanknam', $resbanknam,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':resaddress', $resaddress,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':resphone', $resphone,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':resemail', $resemail,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':rescountry', $rescountry,PDO::PARAM_STR);
			// $insert_post_toDBi->execute();
			}
			//========================================================================
			$data = array(
				'post_id' 	=> $post_id,
				'user_id'   => $p_user_id,
				'chak_id'	=> $post_ido,
				'exchange'      => $usd,
				'received'      => $un,
				'given' 	=> $pd,
				'received_name'      => $received_name,
				'given_name'	=> $given_name,
				'kin'      => $kin,
				'type'      => $ty_kin,
				'media'      => $media,
				'time'	=> $p_time,
				'date'      => $date,
				'timex'      => $timec
			);
			$this->comman_model->insert_entry("transactions",$data);

				// $iptdbsqli = "INSERT INTO transactions
				// (post_id,user_id,chak_id,exchange,received,given,received_name,given_name,kin,type,media,time,date,timex)
				// VALUES
				// ( :post_id, :p_user_id,:post_ido, :usd, :un, :pd, :received_name, :given_name, :kin, :ty_kin, :media, :p_time, :date,:timec)
				// ";
				// $insert_post_toDBi = $conn->prepare($iptdbsqli);
				// $insert_post_toDBi->bindParam(':post_id', $post_id,PDO::PARAM_STR);
				// $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
				// $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
				// $insert_post_toDBi->bindParam(':p_time', $p_time,PDO::PARAM_INT);
				// $insert_post_toDBi->bindParam(':media', $media,PDO::PARAM_INT);
				// $insert_post_toDBi->bindParam(':un', $un,PDO::PARAM_STR);
				// $insert_post_toDBi->bindParam(':timec', $timec,PDO::PARAM_STR);
				// $insert_post_toDBi->bindParam(':date', $date,PDO::PARAM_STR);
				// $insert_post_toDBi->bindParam(':received_name', $received_name,PDO::PARAM_STR);
				// $insert_post_toDBi->bindParam(':kin', $kin,PDO::PARAM_STR);
				// $insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_STR);
				// $insert_post_toDBi->bindParam(':pd', $pd,PDO::PARAM_STR);
				// $insert_post_toDBi->bindParam(':ty_kin', $ty_kin,PDO::PARAM_STR);
				// $insert_post_toDBi->bindParam(':usd', $usd,PDO::PARAM_STR);
				// $insert_post_toDBi->execute();
			//=======================fetching the information in the table============================
			//include("fetch_users_info.php");
			$s_id = $_SESSION['id'];
			$s_username = $_SESSION['username'];
			
			$un = filter_var(htmlspecialchars($_GET['u']),FILTER_SANITIZE_STRING);
			
			$uisql = "SELECT * FROM signup WHERE Username='$un'";
			$fetchdata = $this->comman_model->get_all_data_by_query($uisql);
			// $que = $conn->prepare($uisql);
			// $que->bindParam(':un', $un, PDO::PARAM_STR);
			// $que->execute();
			foreach($fetchdata as $row){			
				$row_username = $row['username'];
				$row_email = $row['email'];
				$row_password = $row['password'];
				$row_user_cover_photo = $row['user_cover_photo'];
				$row_school = $row['school'];
				$row_work = $row['work'];
				$row_work0 = $row['work0'];
				$row_country = $row['country'];
				$row_birthday = $row['birthday'];
				$row_verify = $row['verify'];
				$row_website = $row['website'];
				$row_bio = $row['bio'];
				$row_admin = $row['admin'];
				$row_gender = $row['gender'];
				$row_profile_pic_border = $row['profile_pic_border'];
				$row_language = $row['language'];
				$row_online = $row['online'];
			}


			//include("time_function.php");
			//include("num_k_m_count.php");
			$vpsql = "SELECT * FROM transactions WHERE post_id = $post_id";
			$fetchdata = $this->comman_model->get_all_data_by_query($vpsql);
			// $view_posts = $conn->prepare($vpsql);
			// $view_posts->bindParam(':post_id', $post_id, PDO::PARAM_INT);
			// $view_posts->execute();
			//include "fetch_posts.php";
			echo "Test";exit;
	}




	public function winvest(){
		$post_id = rand(0,9999999)+time();
		$post_ido = rand(0,9999999)+time();
		$post_idv = rand(0,9999999)+time();
		$p_user_id = $_SESSION['id'];
		$boss_id = $_SESSION['boss_id'];
		$shop_id = $_SESSION['shop_id'];
		$p_author = $_SESSION['Fullname'];
		$timec = time();
		$p_author_photo = $_SESSION['Userphoto'];
		//==================from the form==============================
		$un = filter_var(htmlspecialchars($_POST['amou']),FILTER_SANITIZE_STRING);
		$p_time = filter_var(htmlspecialchars($_POST['time']),FILTER_SANITIZE_STRING);
		$pd = filter_var(htmlspecialchars($_POST['lyamou']),FILTER_SANITIZE_STRING);
		$usdy = filter_var(htmlspecialchars($_POST['price']),FILTER_SANITIZE_STRING);
		$date = filter_var(htmlspecialchars($_POST['date']),FILTER_SANITIZE_STRING);
		$received_name = filter_var(htmlspecialchars($_POST['received_name']),FILTER_SANITIZE_STRING);
		$precy = filter_var(htmlspecialchars($_POST['prec']),FILTER_SANITIZE_STRING);
		$given_name = filter_var(htmlspecialchars($_POST['given_name']),FILTER_SANITIZE_STRING);
		//investor
		$infh_st = filter_var(htmlspecialchars($_POST['infh_st']),FILTER_SANITIZE_STRING);
		$prec_st = filter_var(htmlspecialchars($_POST['prec_st']),FILTER_SANITIZE_STRING);
		$price_st = filter_var(htmlspecialchars($_POST['price_st']),FILTER_SANITIZE_STRING);
		$received_name_st = filter_var(htmlspecialchars($_POST['received_name_st']),FILTER_SANITIZE_STRING);
		$amou_st = filter_var(htmlspecialchars($_POST['amou_st']),FILTER_SANITIZE_STRING);
		$given_name_st = filter_var(htmlspecialchars($_POST['given_name_st']),FILTER_SANITIZE_STRING);
		$lyamou_st = filter_var(htmlspecialchars($_POST['lyamou_st']),FILTER_SANITIZE_STRING);
		//costom
		$price_sd = filter_var(htmlspecialchars($_POST['price_sd']),FILTER_SANITIZE_STRING);
		$received_name_sd = filter_var(htmlspecialchars($_POST['received_name_sd']),FILTER_SANITIZE_STRING);
		$amou_sd = filter_var(htmlspecialchars($_POST['amou_sd']),FILTER_SANITIZE_STRING);
		$given_name_sd = filter_var(htmlspecialchars($_POST['given_name_sd']),FILTER_SANITIZE_STRING);
		$lyamou_sd = filter_var(htmlspecialchars($_POST['lyamou_sd']),FILTER_SANITIZE_STRING);

		$howgia = filter_var(htmlspecialchars($_POST['howgia']),FILTER_SANITIZE_STRING);
		$howgi = filter_var(htmlspecialchars($_POST['howgi']),FILTER_SANITIZE_STRING);

		$giv = filter_var(htmlspecialchars($_POST['giv']),FILTER_SANITIZE_STRING);
		$giva = filter_var(htmlspecialchars($_POST['giva']),FILTER_SANITIZE_STRING);
		$pero = filter_var(htmlspecialchars($_POST['pero']),FILTER_SANITIZE_STRING);
		$exc = filter_var(htmlspecialchars($_POST['exc']),FILTER_SANITIZE_STRING);

		$postsea_now = filter_var(htmlspecialchars($_POST['infh']),FILTER_SANITIZE_STRING);
		$cosnamf = filter_var(htmlspecialchars($_POST['name']),FILTER_SANITIZE_STRING);
		$addressf = filter_var(htmlspecialchars($_POST['address']),FILTER_SANITIZE_STRING);
		$phonef = filter_var(htmlspecialchars($_POST['phone']),FILTER_SANITIZE_STRING);
		$emailf = filter_var(htmlspecialchars($_POST['email']),FILTER_SANITIZE_STRING);
		$whhead = filter_var(htmlspecialchars($_POST['whichead']),FILTER_SANITIZE_STRING);
		

		if($cosnamf != ""){
		$name = $cosnamf;
		}elseif($postsea_now != ""){
		$name = $postsea_now;
		}elseif($cosnamf == ""){
		$name = "casher";
		}elseif($postsea_now == ""){
		$name = "casher";
		}
		//============================if condition buy or sell===============================
		if($howgi){
		$kin = lang('given');
		}elseif($giv){
		$kin = lang('given');
		}else{
		if($given_name == "LYD"){
		$kin = lang('sell');
		}else{
		$kin = lang('buy');
		}
		}
		$buy = lang('buy');
		$sell = lang('sell');
		if($kin == "buy" || $kin == "بيع"){
		$whheadt = $whhead;
		}else {
		$whheadt = "";
		}
		$vpsql = "SELECT * FROM costumers WHERE shop_id=$shop_id AND name='$name' ";
		$fetchdata=$this->comman_model->get_all_data_by_query($vpsql);
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':name', $name, PDO::PARAM_INT);
		// $view_postsi->execute();
		$num_name = count($fetchdata); // $view_postsi->rowCount();
		foreach($fetchdata as $post_viewi) {
			$main_id = $post_viewi['main_id'];
		}

		if($num_name < 1){
		$main_id = rand(0,9999999)+time();


		$data = array(
			'main_id'   => $main_id,
			'boss_id'      => $boss_id,
			'shop_id'      => $shop_id,
			'user_id'	=> $p_user_id,
			'name'      => $name,
			'email'      => $emailf,
			'phone'      => $phonef,
			'address'      => $addressf,
		);
		
		$this->comman_model->insert_entry("costumers",$data);

		// $iptdbsqli = "INSERT INTO costumers
		// (main_id,boss_id,shop_id,user_id,name,email,phone,address)
		// VALUES
		// ( $main_id , $boss_id, $shop_id, $p_user_id , $name , $emailf ,$phonef , $addressf )
		// ";
		// $insert_post_toDBi = $conn->prepare($iptdbsqli);
		// $insert_post_toDBi->bindParam(':main_id', $main_id,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':name', $name,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':address', $addressf,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':phone', $phonef,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':email', $emailf,PDO::PARAM_STR);
		// //$insert_post_toDBi->execute();

		}else{
		if($address != ""){
			$data = array(
				'address'   => $addressf
			);
			
			$where=array('shop_id' => $shop_id , 'name' => $name);
			$this->comman_model->update_entry("costumers",$data,$where);
			

		// $iptdbsql = "UPDATE costumers SET address=:address WHERE shop_id=:sid AND name=:name";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':address', $addressf,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':name', $name,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':sid', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
		}if($phno != ""){
			$data = array(
				'phone'   => $phonef,
			);
			
			$where=array('shop_id' => $shop_id , 'name' => $name);
			$this->comman_model->update_entry("costumers",$data,$where);


		// $iptdbsql = "UPDATE costumers SET phone=:phno WHERE shop_id=:sid AND name=:name";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':phno', $phonef,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':name', $name,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':sid', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
		}if($email != ""){
			$data = array(
				'email'   => $emailf,
			);			
			$where=array('shop_id' => $shop_id , 'name' => $name);
			$this->comman_model->update_entry("costumers",$data,$where);

		// $iptdbsql = "UPDATE costumers SET email=:email WHERE shop_id=:sid AND name=:name";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':email', $emailf,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':name', $name,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':sid', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
		}
		}
		if($whheadt != ""){

			$vpsql = "SELECT * FROM transactions WHERE post_id=  $whheadt ";
			
			$fetchdata = $this->comman_model->get_all_data_by_query($vpsql);

			// $vpsql = "SELECT * FROM transactions WHERE post_id=:whheadt";
			// $view_postsi = $conn->prepare($vpsql);
			// $view_postsi->bindParam(':whheadt', $whheadt, PDO::PARAM_INT);
			// $view_postsi->execute();
			$num = count($fetchdata);// $view_postsi->rowCount();
			foreach($fetchdata as  $postsfetch) 
			{
			$chak_idgj = $postsfetch['chak_id'];
			$exchangegj = $postsfetch['exchange'];

			$vpsql = "SELECT * FROM cos_transactions WHERE post_id=$chak_idgj ";			
			$fetchdata = $this->comman_model->get_all_data_by_query($vpsql);

			// $view_posts = $conn->prepare($vpsql);
			// $view_posts->bindParam(':chak_idgj', $chak_idgj, PDO::PARAM_INT);
			// $view_posts->execute();
			$num = count($fetchdata); //$view_posts->rowCount();
				foreach($fetchdata  as $postsfetch) {
					$pergj = $postsfetch['invest_per'];
				}
			}
		}
		if($whheadt != ""){
		$prec = $pergj;
		}else{
		$prec = $precy;
		}
		$usd = $usdy;
		if($postsea_now != ""){
			$vpsql = "SELECT * FROM costumers WHERE boss_id=$boss_id AND name= '$postsea_now' ";
			$fetchdata = $this->comman_model->get_all_data_by_query($vpsql);
			
			// $vpsql = "SELECT * FROM costumers WHERE boss_id=:sid AND name=:postsea_now";
			// $view_postsi = $conn->prepare($vpsql);
			// $view_postsi->bindParam(':sid', $boss_id, PDO::PARAM_INT);
			// $view_postsi->bindParam(':postsea_now', $postsea_now, PDO::PARAM_INT);
			// $view_postsi->execute();
			foreach($fetchdata as  $postsfetch) {
				$address = $postsfetch['address'];
				$phone = $postsfetch['phone'];
				$email = $postsfetch['email'];
				$cosnam = $postsfetch['name'];
			}
		}else{
			$cosnam = "$cosnamf";
			$address = "$addressf";
			$phone = "$phonef";
			$email = "$emailf";
		}

		$calcr = $usd*$un;
		$sid =  $_SESSION['id'];
		$branch = "LYD";
		$vpsql = "SELECT * FROM transactions WHERE user_id= $sid";
		$fetchdata = $this->comman_model->get_all_data_by_query($vpsql);

		// $vpsql = "SELECT * FROM transactions WHERE user_id=:sid";
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':sid', $sid, PDO::PARAM_INT);
		// $view_posts->execute();
		foreach($fetchdata as $postsfetch)
		{
			$ghj = $postsfetch['received'];
			$kind = $postsfetch['type'];
			$received_nameu = $postsfetch['received_name'];
			$media = $exchangegj;
		}
		
		echo $vpsqlo = "SELECT * FROM transactions WHERE post_id= $whhead ";
		exit;
		$fetchdata = $this->comman_model->get_all_data_by_query($vpsqlo);
		
		// $vpsqlo = "SELECT * FROM transactions WHERE post_id=:whhead";
		// $view_postso = $conn->prepare($vpsqlo);
		// $view_postso->bindParam(':whhead', $whhead, PDO::PARAM_INT);
		// $view_postso->execute();
		foreach($fetchdata as $postsfetcho) 
		{
		$chak_idfsf = $postsfetcho['chak_id'];

		$vpsqli = "SELECT * FROM cos_transactions WHERE post_id=$chak_idfsf";
		$fetchdata = $this->comman_model->get_all_data_by_query($vpsqli);
		
		foreach($fetchdata as $postsfetchi ) {
			$cutro = $postsfetchi['cutr'];
		}
		}
		if($howgi){
		$vpsql = "SELECT * FROM invest_treasury WHERE user_id=$boss_id AND kind= '$howgia' AND name= '$cosnam' ";
		$fetchdata = $this->comman_model->get_all_data_by_query($vpsql);
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':howgia', $howgia, PDO::PARAM_INT);
		// $view_posts->bindParam(':name', $cosnam, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvf = count($fetchdata); ///$view_posts->rowCount();
		foreach($fetchdata as  $postsfetch) {

		$bghh = $postsfetch['number'];
		}
		$ddf = $bghh-$howgi;
		}else{
		//======================check the cad====================
		$vpsql = "SELECT * FROM invest_treasury WHERE user_id=$boss_id AND kind= '$received_name'  AND name= '$cosnam' ";
		$fetchdata = $this->comman_model->get_all_data_by_query($vpsql);

		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
		// $view_posts->bindParam(':name', $cosnam, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvf = count($fetchdata); /// $view_posts->rowCount();
		foreach($fetchdata as $postsfetch) 
		{
			$numberya = $postsfetch['number'];
		}
		$vpsql = "SELECT * FROM invest_treasury WHERE user_id=$boss_id AND kind=$given_name AND name= '$cosnam' ";
		$fetchdata = $this->comman_model->get_all_data_by_query($vpsql);

		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
		// $view_posts->bindParam(':name', $cosnam, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvh = count($fetchdata); //$view_posts->rowCount();
		foreach($fetchdata  as $postsfetch ) {
		$numberyb = $postsfetch['number'];
		}
		}
		//===============end of the checking=================================
		//================calculate the values========================
		if($kin == lang('sell')){
		//taken invest_treasury value
		$numbero = $numberya+$un;
		//given invest_treasury value
		$numberb = $numberya-$pd;
		}elseif($kin == lang('buy')){
		//taken invest_treasury value
		$numbero = $numberya+$un;
		//given invest_treasury value
		$numberb = $numberyb-$pd;
		}
		$ty_kin = lang('invest');
		//=====================insert into cash=====================
		if($prec_st != ""){


		$dfs = "0";


		
		$data = array(
			'post_id'   => $post_ido,
			'user_id'      => $p_user_id,
			'cos_id'      => $main_id,
			'type'      => $ty_kin,
			'invest_per'      => $prec_st,
			'calcr'      => $dfs,
			'head'      => $whhead,
			'cutr'      => $un
		);
		$this->comman_model->insert_entry("cos_transactions",$data);


		// $iptdbsqli = "INSERT INTO cos_transactions
		// (post_id,user_id,cos_id,type,invest_per,calcr,head,cutr)
		// VALUES
		// ( :post_ido, :p_user_id, :cosnam, :type, :prec, :calcr, :whhead, :cutr)
		// ";
		// $insert_post_toDBi = $conn->prepare($iptdbsqli);
		// $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':cosnam', $main_id,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':whhead', $whhead,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':cutr', $un,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':prec', $prec_st,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':type', $ty_kin,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':calcr', $dfs,PDO::PARAM_STR);
		// $insert_post_toDBi->execute();
		$post_idt = rand(0,9999999)+time();

		
		$data = array(
			'post_id'   => $post_idt,
			'user_id'      => $p_user_id,
			'cos_id'      => $main_id,
			'type'      => $ty_kin,
			'invest_per'      => $prec_st,
			'calcr'      => $dfs,
			'head'      => $post_idv,
			
		);
		$this->comman_model->insert_entry("cos_transactions",$data);
		
		
		// $iptdbsqli = "INSERT INTO cos_transactions
		// (post_id,user_id,cos_id,type,invest_per,calcr,head)
		// VALUES
		// ( :post_ido, :p_user_id, :cosnam, :type, :prec, :calcr, :whhead)
		// ";
		// $insert_post_toDBi = $conn->prepare($iptdbsqli);
		// $insert_post_toDBi->bindParam(':post_ido', $post_idt,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':cosnam', $main_id,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':whhead', $post_idv,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':prec', $prec_st,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':type', $ty_kin,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':calcr', $dfs,PDO::PARAM_STR);
		// $insert_post_toDBi->execute();
		}elseif($kin == "buy" || $kin == "بيع"){
			$data = array(
				'post_id'   => $post_ido,
				'user_id'      => $p_user_id,
				'cos_id'      => $main_id,
				'type'      => $ty_kin,
				'invest_per'      => $prec,
				'calcr'      => $calcr,
				'head'      => $whhead,
				
			);
			$this->comman_model->insert_entry("cos_transactions",$data);
			
		
			
		// $iptdbsqli = "INSERT INTO cos_transactions
		// (post_id,user_id,cos_id,type,invest_per,calcr,head)
		// VALUES
		// ( :post_ido, :p_user_id, :cosnam, :type, :prec, :calcr, :whhead)
		// ";
		// $insert_post_toDBi = $conn->prepare($iptdbsqli);
		// $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':cosnam', $main_id,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':whhead', $whhead,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':prec', $prec,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':type', $ty_kin,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':calcr', $calcr,PDO::PARAM_STR);
		// $insert_post_toDBi->execute();
		// $cutr = $cutro-$pd;
		// $iptdbsql = "UPDATE cos_transactions SET cutr=:cutr WHERE post_id=:whhead";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':cutr', $cutr,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':whhead', $chak_idfsf,PDO::PARAM_STR);
		// $insert_post_toDB->execute();
		}else{

			$data = array(
				'post_id'   => $post_ido,
				'user_id'      => $p_user_id,
				'cos_id'      => $main_id,
				'type'      => $ty_kin,
				'invest_per'      => $prec,
				'calcr'      => $calcr,
				'head'      => $whhead,
				'cutr'	=> $un
				
			);
			$this->comman_model->insert_entry("cos_transactions",$data);
			
		


		// $iptdbsqli = "INSERT INTO cos_transactions
		// (post_id,user_id,cos_id,type,invest_per,calcr,head,cutr)
		// VALUES
		// ( :post_ido, :p_user_id, :cosnam, :type, :prec, :calcr, :whhead, :cutr)
		// ";
		// $insert_post_toDBi = $conn->prepare($iptdbsqli);
		// $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':cosnam', $main_id,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':whhead', $whhead,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':cutr', $un,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':prec', $prec,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':type', $ty_kin,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':calcr', $calcr,PDO::PARAM_STR);
		// $insert_post_toDBi->execute();
		}
		//========================================================================
		if($giv != "")
		{
			$data = array(
				'post_id'   => $post_id,
				'user_id'      => $p_user_id,
				'chak_id'      => $post_ido,
				'exchange'      => $usd,
				'received'      => $un,
				'given'      => $pd,
				'received_name' => $received_name,
				'given_name'	=> $given_name ,
				'kin'	=> $kin,
				'type'	=> $ty_kin,
				'media'	=> $media,
				'time'	=> $p_time,
				'date'	=> $date,
				'giv'	=> $giv,
				'giva'	=> $giva,
				'pero'	=> $pero,
				'exc'	=> $exc,
				'timex'	=> $timec			
			);

			$this->comman_model->insert_entry("transactions",$data);
			
		


			// $iptdbsqli = "INSERT INTO transactions
			// (post_id,user_id,chak_id,exchange,received,given,received_name,given_name,kin,type,media,time,date,giv,giva,pero
			// ,exc,timex)
			// VALUES
			// ( :post_id, :p_user_id,:post_ido, :usd, :un, :pd, :received_name, :given_name, :kin, :ty_kin, :media, :p_time, :date, :giv, :giva, :pero, :exc,:timec)
			// ";
			// $insert_post_toDBi = $conn->prepare($iptdbsqli);
			// $insert_post_toDBi->bindParam(':post_id', $post_id,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':p_time', $p_time,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':giv', $giv,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':giva', $giva,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':timec', $timec,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':exc', $exc,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':pero', $pero,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':media', $media,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':un', $un,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':date', $date,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':received_name', $received_name,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':kin', $kin,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':pd', $pd,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':ty_kin', $ty_kin,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':usd', $usd,PDO::PARAM_STR);
			// $insert_post_toDBi->execute();

		}elseif($howgi != ""){
			$data = array(
				'post_id'   => $post_id,
				'user_id'      => $p_user_id,
				'chak_id'      => $post_ido,
				'exchange'      => $usd,
				'received'      => $un,
				'given'      => $pd,
				'received_name' => $received_name,
				'given_name'	=> $given_name ,
				'kin'	=> $kin,
				'type'	=> $ty_kin,
				'media'	=> $media,
				'time'	=> $p_time,
				'date'	=> $date,
				'howgi' => $howgi,
				'howgia' => $howgia,				
				'timex'	=> $timec			
			);

			$this->comman_model->insert_entry("transactions",$data);
			
		

		// $iptdbsqli = "INSERT INTO transactions
		// (post_id,user_id,chak_id,exchange,received,given,received_name,given_name,kin,type,media,time,date,howgi,howgia,timex)
		// VALUES
		// ( :post_id, :p_user_id,:post_ido, :usd, :un, :pd, :received_name, :given_name, :kin, :ty_kin, :media, :p_time, :date, :howgi, :howgia, :timec)
		// ";
		// $insert_post_toDBi = $conn->prepare($iptdbsqli);
		// $insert_post_toDBi->bindParam(':post_id', $post_id,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':p_time', $p_time,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':howgi', $howgi,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':timec', $timec,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':howgia', $howgia,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':media', $media,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':un', $un,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':date', $date,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':received_name', $received_name,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':kin', $kin,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':pd', $pd,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':ty_kin', $ty_kin,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':usd', $usd,PDO::PARAM_STR);
		// $insert_post_toDBi->execute();
		}elseif($prec_st != ""){
			$kino = lang('sell');
			$kinc = lang('buy');
			$data = array(
				'post_id'   => $post_idv,
				'user_id'      => $p_user_id,
				'chak_id'      => $post_ido,
				'exchange'      => $price_st,
				'received'      => $amou_st,
				'given'      => $lyamou_st,
				'received_name' => $received_name_st,
				'given_name'	=> $given_name_st ,
				'kin'	=> $kino,
				'type'	=> $ty_kin,
				'media'	=> $media,
				'time'	=> $p_time,
				'date'	=> $date,
				'timex' => $timec,
				
			);
			$this->comman_model->insert_entry("transactions",$data);	
			// $iptdbsqli = "INSERT INTO transactions
			// (post_id,user_id,chak_id,exchange,received,given,received_name,given_name,kin,type,media,time,date,timex)
			// VALUES
			// ( :post_id, :p_user_id,:post_ido, :usd, :un, :pd, :received_name, :given_name, :kin, :ty_kin, :media, :p_time, :date, :timec)
			// ";
			// $insert_post_toDBi = $conn->prepare($iptdbsqli);
			// $insert_post_toDBi->bindParam(':post_id', $post_idv,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':p_time', $p_time,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':timec', $timec,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':media', $media,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':un', $amou_st,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':date', $date,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':received_name', $received_name_st,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':kin', $kino,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':given_name', $given_name_st,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':pd', $lyamou_st,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':ty_kin', $ty_kin,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':usd', $price_st,PDO::PARAM_STR);
			// $insert_post_toDBi->execute();

			$data = array(
				'post_id'   => $post_id,
				'user_id'      => $p_user_id,
				'chak_id'      => $post_idt,
				'exchange'      => $price_sd,
				'received'      => $amou_sd,
				'given'      => $lyamou_sd,
				'received_name' => $received_name_sd,
				'given_name'	=> $given_name_sd ,
				'kin'	=> $kinc,
				'type'	=> $ty_kin,
				'media'	=> $price_st,
				'time'	=> $p_time,
				'date'	=> $date,
				'timex' => $timec,
				
			);
			$this->comman_model->insert_entry("transactions",$data);

			// $iptdbsqli = "INSERT INTO transactions
			// (post_id,user_id,chak_id,exchange,received,given,received_name,given_name,kin,type,media,time,date,timex)
			// VALUES
			// ( :post_id, :p_user_id,:post_ido, :usd, :un, :pd, :received_name, :given_name, :kin, :ty_kin, :media,
			//  :p_time, :date, :timec)
			// ";
			// $insert_post_toDBi = $conn->prepare($iptdbsqli);
			// $insert_post_toDBi->bindParam(':post_id', $post_id,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':post_ido', $post_idt,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':p_time', $p_time,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':timec', $timec,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':media', $price_st,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':un', $amou_sd,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':date', $date,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':received_name', $received_name_sd,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':kin', $kinc,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':given_name', $given_name_sd,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':pd', $lyamou_sd,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':ty_kin', $ty_kin,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':usd', $price_sd,PDO::PARAM_STR);
			// $insert_post_toDBi->execute();
		}else{
			
			$data = array(
				'post_id'   => $post_id,
				'user_id'      => $p_user_id,
				'chak_id'      => $post_ido,
				'exchange'      => $usd,
				'received'      => $un,
				'given'      => $pd,
				'received_name' => $received_name,
				'given_name'	=> $given_name ,
				'kin'	=> $kin,
				'type'	=> $ty_kin,
				'media'	=> $media,
				'time'	=> $p_time,
				'date'	=> $date,
				'timex' => $timec,
				
			);
			$this->comman_model->insert_entry("transactions",$data);

		// 	$iptdbsqli = "INSERT INTO transactions
		// (post_id,user_id,chak_id,exchange,received,given,received_name,given_name,kin,type,media,time,date, timex)
		// VALUES
		// ( :post_id, :p_user_id,:post_ido, :usd, :un, :pd, :received_name, :given_name, :kin, :ty_kin, :media, :p_time, :date, :timec)
		// ";
		// $insert_post_toDBi = $conn->prepare($iptdbsqli);
		// $insert_post_toDBi->bindParam(':post_id', $post_id,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':timec', $timec,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':p_time', $p_time,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':media', $media,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':un', $un,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':date', $date,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':received_name', $received_name,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':kin', $kin,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':pd', $pd,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':ty_kin', $ty_kin,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':usd', $usd,PDO::PARAM_STR);
		// $insert_post_toDBi->execute();
		}
		//===================insert the money of taken X================================
		if($howgi){

			$data = array(
				'number'   => $ddf			
			);
			$where=array('user_id' => $boss_id,'kind' => $howgia , 'name' =>$cosnam );
			$update_info=$this->comman_model->update_entry("invest_treasury",$data,$where);


		// $iptdbsql = "UPDATE invest_treasury SET number=:numbero WHERE user_id = :p_user_id AND kind=:received_name
		//  AND name=:name";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbero', $ddf,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $howgia,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':name', $cosnam, PDO::PARAM_INT);
		// $insert_post_toDB->execute();
		}elseif($giv){
			$vpsql = "SELECT * FROM treasury WHERE user_id=$shop_id AND kind='$giva' ";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			
			
		
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':given_name', $giva, PDO::PARAM_INT);
		// $view_posts->execute();
		foreach($FetchedData as $postsfetch) {
			$gjjbv = $postsfetch['number'];
		}
		$ghh = $gjjbv-$giv;
		
		$data = array(
			'number'   =>$ghh			
		);
		$where=array('user_id' => $shop_id,'kind' => $giva , 'name' =>$cosnam );
		$update_info=$this->comman_model->update_entry("treasury",$data,$where);


		// $delete_comm_sqli = "UPDATE treasury SET number=:numbery WHERE user_id = :p_user_id AND kind=:received_name";
		// $delete_commi = $conn->prepare($delete_comm_sqli);
		// $delete_commi->bindParam(':numbery',$ghh,PDO::PARAM_INT);
		// $delete_commi->bindParam(':received_name', $giva,PDO::PARAM_INT);
		// $delete_commi->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $delete_commi->execute();
		}elseif($prec_st != ""){
		$vpsql = "SELECT * FROM invest_treasury WHERE user_id= $boss_id AND kind= '$received_name_st' AND name= '$cosnam' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			

		
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':given_name', $received_name_st, PDO::PARAM_INT);
		// $view_posts->bindParam(':name', $cosnam, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvz = count($FetchedData);// $view_posts->rowCount();
		foreach($FetchedData as $postsfetch) {
			$gjjbvcv = $postsfetch['number'];
		}
		$ghcv = $gjjbvcv+$amou_st;
		
		$vpsql = "SELECT * FROM invest_treasury WHERE user_id=$boss_id AND kind= '$given_name_st' AND name='$cosnam' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			

		
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':given_name', $given_name_st, PDO::PARAM_INT);
		// $view_posts->bindParam(':name', $cosnam, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvw = count($FetchedData); //$view_posts->rowCount();
		foreach($FetchedData as $postsfetch) {
		$gjjbvxv = $postsfetch['number'];
		}
		$ghxv = $gjjbvxv-$lyamou_st;

		/*    $iptdbsql = "UPDATE invest_treasury SET number=:numbero WHERE user_id = :p_user_id AND kind=:received_name AND name=:name";
		$insert_post_toDB = $conn->prepare($iptdbsql);
		$insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
		$insert_post_toDB->bindParam(':numbero', $ghcv,PDO::PARAM_INT);
		$insert_post_toDB->bindParam(':received_name', $received_name_st,PDO::PARAM_INT);
		$insert_post_toDB->bindParam(':name', $cosnam, PDO::PARAM_INT);
		$insert_post_toDB->execute();

		//=====================insert the money of given==================================

			$iptdbsql = "UPDATE invest_treasury SET number=:numberb WHERE user_id = :p_user_id AND kind=:given_name AND name=:name";
		$insert_post_toDB = $conn->prepare($iptdbsql);
		$insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
		$insert_post_toDB->bindParam(':numberb', $ghxv,PDO::PARAM_INT);
		$insert_post_toDB->bindParam(':given_name', $given_name_st,PDO::PARAM_INT);
		$insert_post_toDB->bindParam(':name', $cosnam, PDO::PARAM_INT);
		$insert_post_toDB->execute(); */

		//==============================================================================================================================================
		$vpsql = "SELECT * FROM invest_treasury WHERE user_id=$boss_id AND kind= '$received_name_sd' AND name= '$cosnam' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
			

		// $vpsql = "SELECT * FROM invest_treasury WHERE user_id=:p_user_id AND kind=:given_name AND name=:name";
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':given_name', $received_name_sd, PDO::PARAM_INT);
		// $view_posts->bindParam(':name', $cosnam, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvzt = count($FetchedData);// $view_posts->rowCount();
		foreach($FetchedData as $postsfetch ) {
		$gjjbvc = $postsfetch['number'];
		}
		$ghc = $gjjbvc+$amou_sd;
		$vpsql = "SELECT * FROM invest_treasury WHERE user_id=$boss_id AND kind= '$given_name_sd' AND name= '$cosnam' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);

		// $vpsql = "SELECT * FROM invest_treasury WHERE user_id=:p_user_id AND kind=:given_name AND name=:name";
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':given_name', $given_name_sd, PDO::PARAM_INT);
		// $view_posts->bindParam(':name', $cosnam, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvwt = count($FetchedData);// $view_posts->rowCount();
		foreach($FetchedData as  $postsfetch ) {
		$gjjbvx = $postsfetch['number'];
		}
		$ghx = $gjjbvx-$lyamou_sd;
		if(1 > $numvzt){

		
			$data = array(
				'user_id'   => $boss_id,
				'number'      => $amou_sd,
				'kind'      => $received_name_sd,
				'name'      => $cosnam				
			);

			$this->comman_model->insert_entry("invest_treasury",$data);


		// 	$iptdbsqli = "INSERT INTO invest_treasury
		// (user_id,number,kind,name)
		// VALUES
		// ( :p_user_id, :numbero,:received_name, :name)
		// ";
		// $insert_post_toDB = $conn->prepare($iptdbsqli);
		// $insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbero', $amou_sd,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $received_name_sd,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':name', $cosnam,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
		}else{
			
			$data = array(
				'number'   =>$ghc			
			);
			$where=array('user_id' => $boss_id,'kind' => $received_name_sd , 'name' =>$cosnam );
			$update_info=$this->comman_model->update_entry("invest_treasury",$data,$where);



		// 	$iptdbsql = "UPDATE invest_treasury SET number=:numbero WHERE user_id = :p_user_id AND kind=:received_name AND name=:name";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbero', $ghc,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $received_name_sd,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':name', $cosnam, PDO::PARAM_INT);
		// $insert_post_toDB->execute();
		}
		if($kin == "buy" || $kin == "بيع"){
		//=====================insert the money of given==================================
		if(1 > $numvwt){

			$data = array(
				'user_id'   => $boss_id,
				'number'      => $lyamou_sd,
				'kind'      => $given_name_sd,
				'name'      => $cosnam				
			);

			$this->comman_model->insert_entry("invest_treasury",$data);



		// 	$iptdbsqli = "INSERT INTO invest_treasury
		// (user_id,number,kind,name)
		// VALUES
		// ( :p_user_id, :numbero,:received_name, :name)
		// ";
		// $insert_post_toDB = $conn->prepare($iptdbsqli);
		// $insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbero', $lyamou_sd,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $given_name_sd,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':name', $cosnam,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
		}else{
			
			$data = array(
				'number'   =>$ghx			
			);
			$where=array('user_id' => $boss_id,'kind' => $given_name_sd , 'name' =>$cosnam);
			
			$update_info=$this->comman_model->update_entry("invest_treasury",$data,$where);



		// 	$iptdbsql = "UPDATE invest_treasury SET number=:numberb WHERE user_id = :p_user_id AND kind=:given_name AND name=:name";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numberb', $ghx,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':given_name', $given_name_sd,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':name', $cosnam, PDO::PARAM_INT);
		// $insert_post_toDB->execute();
		}
		}
		}else{
		if(1 > $numvf){

			$data = array(
				'user_id'   => $boss_id,
				'number'      => $numbero,
				'kind'      => $received_name,
				'name'      => $cosnam				
			);

			$this->comman_model->insert_entry("invest_treasury",$data);


		// $iptdbsqli = "INSERT INTO invest_treasury
		// (user_id,number,kind,name)
		// VALUES
		// ( :p_user_id, :numbero,:received_name, :name)
		// ";
		// $insert_post_toDB = $conn->prepare($iptdbsqli);
		// $insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':name', $cosnam,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
		}else{
			$data = array(
				'number'   =>$numbero			
			);
			$where=array('user_id' => $boss_id,'kind' => $received_name , 'name' =>$cosnam );
			$update_info=$this->comman_model->update_entry("invest_treasury",$data,$where);



		// 	$iptdbsql = "UPDATE invest_treasury SET number=:numbero WHERE user_id = :p_user_id AND kind=:received_name AND name=:name";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':name', $cosnam, PDO::PARAM_INT);
		// $insert_post_toDB->execute();
		}
		if($kin == "buy" || $kin == "بيع"){
		//=====================insert the money of given==================================
		if(1 > $numvh){
			$data = array(
				'user_id'   => $boss_id,
				'number'      => $numberb,
				'kind'      => $given_name,
				'name'      => $cosnam				
			);

			$this->comman_model->insert_entry("invest_treasury",$data);


		// $iptdbsqli = "INSERT INTO invest_treasury
		// (user_id,number,kind,name)
		// VALUES
		// ( :p_user_id, :numbero,:received_name, :name)
		// ";
		// $insert_post_toDB = $conn->prepare($iptdbsqli);
		// $insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbero', $numberb,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $given_name,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':name', $cosnam,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
		}else{
			$data = array(
				'number'   =>$numberb			
			);
			$where=array('user_id' => $boss_id,'kind' => $given_name , 'name' =>$cosnam );
			$update_info=$this->comman_model->update_entry("invest_treasury",$data,$where);

		// 	$iptdbsql = "UPDATE invest_treasury SET number=:numberb WHERE user_id = :p_user_id AND kind=:given_name AND name=:name";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numberb', $numberb,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':name', $cosnam, PDO::PARAM_INT);
		// $insert_post_toDB->execute();
		}
		}
		}
		//=======================fetching the information in the table============================
		//include("fetch_users_info.php");
		////fetch user info
		$s_id = $_SESSION['id'];
		$s_username = $_SESSION['username'];

		$un = filter_var(htmlspecialchars($_GET['u']),FILTER_SANITIZE_STRING);

		$query = "SELECT * FROM signup WHERE Username= '$un' ";
		$result=$this->comman_model->get_all_data_by_query($query);

		// $que = $conn->prepare($uisql);
		// $que->bindParam(':un', $un, PDO::PARAM_STR);
		// $que->execute();
		foreach($result as $row){

			$row_username = $row['username'];
			$row_email = $row['email'];
			$row_password = $row['password'];
			$row_user_cover_photo = $row['user_cover_photo'];
			$row_school = $row['school'];
			$row_work = $row['work'];
			$row_work0 = $row['work0'];
			$row_country = $row['country'];
			$row_birthday = $row['birthday'];
			$row_verify = $row['verify'];
			$row_website = $row['website'];
			$row_bio = $row['bio'];
			$row_admin = $row['admin'];
			$row_gender = $row['gender'];
			$row_profile_pic_border = $row['profile_pic_border'];
			$row_language = $row['language'];
			$row_online = $row['online'];
		}




		//include ("time_function.php");

		//include ("num_k_m_count.php");
		
		$query = "SELECT * FROM transactions WHERE post_id = $post_id";
		$result=$this->comman_model->get_all_data_by_query($query);
		
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':post_id', $post_id, PDO::PARAM_INT);
		// $view_posts->execute();
		include "fetch_posts.php";
	}



	public function delete_transaction(){
		// include("../config/connect.php");
		// session_start();
		$sid = $_SESSION['id'];
		//==================from the function========================
		$c_id = htmlentities($_POST['cid'], ENT_QUOTES);
		$p_id = htmlentities($_POST['pid'], ENT_QUOTES);
		$delo = "1";
		$deloz = "0";
		$cash = "cash";
		$bank = "bank";
		$inves = "أستثمار";
		//===================check the user========================
		$check = "SELECT * FROM transactions WHERE post_id =$c_id";
		
		$result=$this->comman_model->get_all_data_by_query($check);
		// $check->bindParam(':c_id',$c_id,PDO::PARAM_INT);
		// $check->execute();
		foreach($result as $chR) 
		{
			$chR_aid = $chR['user_id'];
			$received = $chR['received'];
			$given = $chR['given'];
			$received_name = $chR['received_name'];
			$given_name = $chR['given_name'];
			$type = $chR['type'];
			$kin = $chR['kin'];
			$chak_id = $chR['chak_id'];
			$giv = $chR['giv'];
			$giva = $chR['giva'];
		}

		 $fetchUsers_sql = "SELECT id,boss_id,shop_id FROM signup WHERE id=$chR_aid";
		
		$result=$this->comman_model->get_all_data_by_query($fetchUsers_sql);

		// $fetchUsers = $conn->prepare($fetchUsers_sql);
		// $fetchUsers->execute();
		foreach($result as $rows) 
		{
			$gfid = $rows['id'];
			$gfshop = $rows['shop_id'];
			$gfboss = $rows['boss_id'];
		}
		
		
		$check = "SELECT * FROM cos_transactions WHERE post_id =$chak_id";
		$result=$this->comman_model->get_all_data_by_query($check);

		// $check->bindParam(':chR_aid',$chak_id,PDO::PARAM_INT);
		// $check->execute();
		foreach ($result as $chR) {
			$idji = $chR['idjd'];
		}
		if($type == $inves){
			$check ="SELECT name FROM cos_transactions WHERE post_id =$chak_id";
			$result=$this->comman_model->get_all_data_by_query($check);
			// $check->bindParam(':chR_aid',$chak_id,PDO::PARAM_INT);
			// $check->execute();
			foreach($result as $chR) {
				$name = $chR['name'];
			}
			//===================first select from treasury==========================
			$check = "SELECT * FROM invest_treasury WHERE kind='$received_name' AND name='$name'";
			$result=$this->comman_model->get_all_data_by_query($check);
			// $check->bindParam(':received_name',$received_name,PDO::PARAM_INT);
			// $check->bindParam(':name',$name,PDO::PARAM_INT);
			// $check->execute();
			foreach ($result as $chR) {
				$numbero = $chR['number'];
			}
			//===================second select from treasury==========================
			$check = "SELECT * FROM invest_treasury WHERE kind='$given_name' AND name='$name'";
			$result=$this->comman_model->get_all_data_by_query($check);
			// $check->bindParam(':given_name',$given_name,PDO::PARAM_INT);
			// $check->bindParam(':name',$name,PDO::PARAM_INT);
			// $check->execute();
			foreach($result as $chR) {
				$numberi = $chR['number'];
			}
		}else{
			if($type == "transfar")
			{
			//===================first select from treasury==========================
			$check = "SELECT * FROM treasury WHERE boss_id = $gfboss AND kind= '$received_name' AND tyi= '$cash' ";
			$result=$this->comman_model->get_all_data_by_query($check);

			// $check->bindParam(':chR_aid',$gfboss,PDO::PARAM_INT);
			// $check->bindParam(':received_name',$received_name,PDO::PARAM_INT);
			// $check->bindParam(':cash',$cash,PDO::PARAM_INT);
			// $check->execute();
			foreach($result as $chR) {
				$numbero = $chR['number'];
			}
			//===================second select from treasury==========================
			$check = "SELECT * FROM treasury WHERE shop_id = $gfshop AND kind='$given_name' AND wh='$idji' AND tyi='$bank'";
			$result=$this->comman_model->get_all_data_by_query($check);

			// $check->bindParam(':chR_aid',$gfshop,PDO::PARAM_INT);
			// $check->bindParam(':given_name',$given_name,PDO::PARAM_INT);
			// $check->bindParam(':idji',$idji,PDO::PARAM_INT);
			// $check->bindParam(':bank',$bank,PDO::PARAM_INT);
			// $check->execute();
			foreach($result as $chR) {
				$numberi = $chR['number'];
			}

		}elseif($type == "cards"){
			//=================== select from treasury==========================
			$check = "SELECT * FROM treasury WHERE boss_id =$gfboss AND kind='$received_name' AND wh='$idji' AND tyi='$bank'";
			$result=$this->comman_model->get_all_data_by_query($check);

			// $check->bindParam(':chR_aid',$gfboss,PDO::PARAM_INT);
			// $check->bindParam(':received_name',$received_name,PDO::PARAM_INT);
			// $check->bindParam(':idji',$idji,PDO::PARAM_INT);
			// $check->bindParam(':bank',$bank,PDO::PARAM_INT);
			// $check->execute();
			foreach ($result as $chR) {
				$numbero = $chR['number'];
			}
			//=================== select from treasury==========================
			$check = "SELECT * FROM treasury WHERE shop_id =$gfshop AND kind='$given_name' AND tyi='$cash'";
			$result=$this->comman_model->get_all_data_by_query($check);
			// $check->bindParam(':chR_aid',$gfshop,PDO::PARAM_INT);
			// $check->bindParam(':given_name',$given_name,PDO::PARAM_INT);
			// $check->bindParam(':cash',$cash,PDO::PARAM_INT);
			// $check->execute();
			foreach($result as $chR) {
				$numberi = $chR['number'];
			}

		}elseif($type == "chak"){
			//=================== select from treasury==========================
			$check = "SELECT * FROM treasury WHERE shop_id =$gfshop AND kind='$received_name' AND wh='$idji' AND tyi='$bank'";
			$result=$this->comman_model->get_all_data_by_query($check);
			// $check->bindParam(':chR_aid',$gfshop,PDO::PARAM_INT);
			// $check->bindParam(':received_name',$received_name,PDO::PARAM_INT);
			// $check->bindParam(':idji',$idji,PDO::PARAM_INT);
			// $check->bindParam(':bank',$bank,PDO::PARAM_INT);
			// $check->execute();
			foreach ($result as $chR) {
				$numbero = $chR['number'];
			}
			//=================== select from treasury==========================
			$check = "SELECT * FROM treasury WHERE boss_id = $gfboss AND kind= '$given_name' AND tyi='$cash' ";
			$result=$this->comman_model->get_all_data_by_query($check);
			// $check->bindParam(':chR_aid',$gfboss,PDO::PARAM_INT);
			// $check->bindParam(':given_name',$given_name,PDO::PARAM_INT);
			// $check->bindParam(':cash',$cash,PDO::PARAM_INT);
			// $check->execute();
			foreach ($result as $chR) {
				$numberi = $chR['number'];
			}

		}elseif($type == "cash"){
			//=================== select from treasury==========================
			$check = "SELECT * FROM treasury WHERE shop_id = $gfshop AND kind='$received_name' AND tyi='$cash' ";
			
			$result=$this->comman_model->get_all_data_by_query($check);
			// $check->bindParam(':chR_aid',$gfshop,PDO::PARAM_INT);
			// $check->bindParam(':received_name',$received_name,PDO::PARAM_INT);
			// $check->bindParam(':cash',$cash,PDO::PARAM_INT);
			// $check->execute();
			foreach($result as $chR) {
				$numbero = $chR['number'];
			}
			//=================== select from treasury==========================
			$check ="SELECT * FROM treasury WHERE shop_id =$gfshop AND kind='$given_name' AND tyi='$cash'";
			$result=$this->comman_model->get_all_data_by_query($check);
			// $check->bindParam(':chR_aid',$gfshop,PDO::PARAM_INT);
			// $check->bindParam(':given_name',$given_name,PDO::PARAM_INT);
			// $check->bindParam(':cash',$cash,PDO::PARAM_INT);
			// $check->execute();
			foreach ($result as $chR) {
				$numberi = $chR['number'];
			}
		}
		}
			$numbery = $numbero-$received;
			$numberb = $numberi+$given;

		//==================delete the transaction =================================
			$delete_comm_sql = "UPDATE transactions SET hide='$delo' WHERE post_id = $c_id";
			$isupdate=$this->comman_model->run_query($delete_comm_sql);
			// $delete_comm = $conn->prepare($delete_comm_sql);
			// $delete_comm->bindParam(':numbery',$delo,PDO::PARAM_INT);
			// $delete_comm->bindParam(':c_id',$c_id,PDO::PARAM_INT);
			// $delete_comm->execute();
				$delete_comm_sqli = "UPDATE cos_transactions SET hide= '$delo' WHERE post_id = $chak_id ";
				$isupdate=$this->comman_model->run_query($delete_comm_sqli);
				// $delete_commi = $conn->prepare($delete_comm_sqli);
				// $delete_commi->bindParam(':numbery',$delo,PDO::PARAM_INT);
				// $delete_commi->bindParam(':c_id',$chak_id,PDO::PARAM_INT);
				// $delete_commi->execute();
				if($type == $inves){
					//===================update your treasury=========================
					$iptdbsql = "UPDATE invest_treasury SET number='$numbery' WHERE kind='$received_name' AND name='$name'";
					$isupdate=$this->comman_model->run_query($iptdbsql);
					// $insert_post_toDB = $conn->prepare($iptdbsql);
					// $insert_post_toDB->bindParam(':numbery', $numbery,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':name',$name,PDO::PARAM_INT);
					// $insert_post_toDB->execute();
					//==================================invest treasury========================
					$iptdbsqli = "UPDATE invest_treasury SET number='$numberb' WHERE kind='$given_name' AND name='$name'";
					$isupdate=$this->comman_model->run_query($iptdbsqli);

					// $insert_post_toDBi = $conn->prepare($iptdbsqli);
					// $insert_post_toDBi->bindParam(':numberb', $numberb,PDO::PARAM_INT);
					// $insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_INT);
					// $insert_post_toDBi->bindParam(':name',$name,PDO::PARAM_INT);
					// $insert_post_toDBi->execute();
					if($giv != ""){
						$check ="SELECT * FROM treasury WHERE shop_id = $gfshop AND kind='$giva' ";
						$result=$this->comman_model->get_all_data_by_query($check);
						// $check->bindParam(':chR_aid',$gfshop,PDO::PARAM_INT);
						// $check->bindParam(':received_name',$giva,PDO::PARAM_INT);
						// $check->execute();
						foreach($result as $chR) 
						{
							$numgiv = $chR['number'];
						}
						$numgivcalc = $numgiv+$giv;
						$iptdbsql = "UPDATE treasury SET number='$numgivcalc' WHERE shop_id = $gfshop AND kind='$giva'";
						$isupdate=$this->comman_model->run_query($iptdbsql);
					// $insert_post_toDB = $conn->prepare($iptdbsql);
					// $insert_post_toDB->bindParam(':chR_aid', $gfshop,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':numbery', $numgivcalc,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':received_name', $giva,PDO::PARAM_INT);
					// $insert_post_toDB->execute();
					}
				}else{
					if($type == "transfar"){
					//===================update your treasury=========================
						$iptdbsql = "UPDATE treasury SET number='$numbery' WHERE boss_id = $gfboss AND kind='$received_name' AND tyi='$cash' ";
						$isupdate=$this->comman_model->run_query($iptdbsql);
					// $insert_post_toDB = $conn->prepare($iptdbsql);
					// $insert_post_toDB->bindParam(':chR_aid', $gfboss,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':numbery', $numbery,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':cash', $cash,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
					// $insert_post_toDB->execute();
					//==================================LYD X========================
					$iptdbsqli = "UPDATE treasury SET number='$numberb' WHERE shop_id = $gfshop AND kind='$given_name' AND wh='$idji' AND tyi='$bank'";
					$isupdate=$this->comman_model->run_query($iptdbsqli);

					// $insert_post_toDBi = $conn->prepare($iptdbsqli);
					// $insert_post_toDBi->bindParam(':chR_aid', $gfshop,PDO::PARAM_INT);
					// $insert_post_toDBi->bindParam(':numberb', $numberb,PDO::PARAM_INT);
					// $insert_post_toDBi->bindParam(':idji',$idji,PDO::PARAM_INT);
					// $insert_post_toDBi->bindParam(':bank',$bank,PDO::PARAM_INT);
					// $insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_INT);
					// $insert_post_toDBi->execute();
				}elseif($type == "cards"){
					//===================update your treasury=========================
						$iptdbsql = "UPDATE treasury SET number='$numbery' WHERE boss_id = $gfboss AND kind='$received_name' AND wh='$idji' AND tyi='$bank'";
						$isupdate=$this->comman_model->run_query($iptdbsql);
					// $insert_post_toDB = $conn->prepare($iptdbsql);
					// $insert_post_toDB->bindParam(':chR_aid', $gfboss,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':numbery', $numbery,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':idji',$idji,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':bank',$bank,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
					// $insert_post_toDB->execute();
					$iptdbsqli = "UPDATE treasury SET number='$numberb' WHERE shop_id = $gfshop AND kind='$given_name' AND tyi='$cash'";
					$isupdate=$this->comman_model->run_query($iptdbsqli);
					// $insert_post_toDBi = $conn->prepare($iptdbsqli);
					// $insert_post_toDBi->bindParam(':chR_aid', $gfshop,PDO::PARAM_INT);
					// $insert_post_toDBi->bindParam(':cash', $cash,PDO::PARAM_INT);
					// $insert_post_toDBi->bindParam(':numberb', $numberb,PDO::PARAM_INT);
					// $insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_INT);
					// $insert_post_toDBi->execute();
		}elseif($type == "chak"){
					//===================update your treasury=========================
						$iptdbsql = "UPDATE treasury SET number='$numbery' WHERE shop_id = $gfshop AND kind= $received_name AND wh=$idji AND tyi=$bank";
						$isupdate=$this->comman_model->run_query($iptdbsql);
					// $insert_post_toDB = $conn->prepare($iptdbsql);
					// $insert_post_toDB->bindParam(':chR_aid', $gfshop,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':numbery', $numbery,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':idji', $idji,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':bank', $bank,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
					// $insert_post_toDB->execute();
					$iptdbsqli = "UPDATE treasury SET number='$numberb' WHERE boss_id =$gfboss AND kind='$given_name' AND tyi='$cash'";
					$isupdate=$this->comman_model->run_query($iptdbsqli);
					// $insert_post_toDBi = $conn->prepare($iptdbsqli);
					// $insert_post_toDBi->bindParam(':chR_aid', $gfboss,PDO::PARAM_INT);
					// $insert_post_toDBi->bindParam(':numberb', $numberb,PDO::PARAM_INT);
					// $insert_post_toDBi->bindParam(':cash',$cash,PDO::PARAM_INT);
					// $insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_INT);
					// $insert_post_toDBi->execute();
				}elseif($type == "cash"){
					//===================update your treasury=========================
						$iptdbsql = "UPDATE treasury SET number='$numbery' WHERE shop_id = $gfshop AND kind= '$received_name' AND tyi='$cash'";
						$isupdate=$this->comman_model->run_query($iptdbsql);
					// $insert_post_toDB = $conn->prepare($iptdbsql);
					// $insert_post_toDB->bindParam(':chR_aid', $gfshop,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':numbery', $numbery,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
					// $insert_post_toDB->bindParam(':cash',$cash,PDO::PARAM_INT);
					// $insert_post_toDB->execute();
					$iptdbsqli = "UPDATE treasury SET number='$numberb' WHERE shop_id = $gfshop AND kind='$given_name' AND tyi='$cash'";
					$isupdate=$this->comman_model->run_query($iptdbsqli);
					// $insert_post_toDBi = $conn->prepare($iptdbsqli);
					// $insert_post_toDBi->bindParam(':chR_aid', $gfshop,PDO::PARAM_INT);
					// $insert_post_toDBi->bindParam(':numberb', $numberb,PDO::PARAM_INT);
					// $insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_INT);
					// $insert_post_toDBi->bindParam(':cash',$cash,PDO::PARAM_INT);
					// $insert_post_toDBi->execute();
				}
				}
			echo"yes";

	}




	public function wtransaction(){
		session_start();
		//error_reporting(0);
		//include "../config/connect.php";
		$post_id = rand(0,9999999)+time();
		$post_ido = rand(0,9999999)+time();
		$timec = time();
		$p_user_id = $_SESSION['id'];
		$shop_id = $_SESSION['shop_id'];
		$boss_id = $_SESSION['boss_id'];
		$p_author = $_SESSION['Fullname'];
		$p_author_photo = $_SESSION['Userphoto'];
		//==================from the form of transaction==============================
		$un = filter_var(htmlspecialchars($_POST['amou']),FILTER_SANITIZE_STRING);
		$p_time = filter_var(htmlspecialchars($_POST['time']),FILTER_SANITIZE_STRING);
		$pd = filter_var(htmlspecialchars($_POST['lyamou']),FILTER_SANITIZE_STRING);
		$usd = filter_var(htmlspecialchars($_POST['price']),FILTER_SANITIZE_STRING);
		$date = filter_var(htmlspecialchars($_POST['date']),FILTER_SANITIZE_STRING);
		$received_name = filter_var(htmlspecialchars($_POST['received_name']),FILTER_SANITIZE_STRING);
		$given_name = filter_var(htmlspecialchars($_POST['given_name']),FILTER_SANITIZE_STRING);
		$name = filter_var(htmlspecialchars($_POST['name']),FILTER_SANITIZE_STRING);
		if($name == NULL){
		$name = "casher";
		}
		if($given_name == "LYD"){
		$kin = lang('sell');
		}else{
		$kin = lang('buy');
		}
		$ty_kin = "cash";
		//=================select the capital =========================
		$vpsql = "SELECT * FROM capital WHERE shop_id=$shop_id AND kind='$received_name' AND tyi='$tyi_kin'";
		$result=$this->comman_model->get_all_data_by_query($vpsql);
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':received_name', $received_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':tyi', $ty_kin, PDO::PARAM_INT);
		// $view_postsi->execute();
		$num = count($result);//$view_postsi->rowCount();
		foreach ($result as $postsfetch) {
			$numberhea = $postsfetch['number'];
			$exchangehea = $postsfetch['exchange'];
			$tyhea = $postsfetch['kind'];
			$ty_gt = $postsfetch['type'];
		}
		//=============calculate the average of the exchange rate=======================
		$vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE shop_id=$shop_id AND kind= '$given_name' AND tyi='$ty_kin'";
		$result=$this->comman_model->get_all_data_by_query($vpsql);
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':received_name', $given_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':tyi', $ty_kin, PDO::PARAM_INT);
		// $view_postsi->execute();
		foreach( $result as $postsfetch) {
			$ty_uy = $postsfetch['ty_uy'];
		}
	 	$vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE shop_id=$shop_id AND kind='$given_name' AND tyi='$ty_kin'";
		$result=$this->comman_model->get_all_data_by_query($vpsql);
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':received_name', $given_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':tyi', $ty_kin, PDO::PARAM_INT);
		// $view_postsi->execute();
		
		foreach($result as $postsfetch) 
		{
			$ty_ji = $postsfetch['ty_ji'];
		}
		if($ty_ji=="" && $ty_uy == ""){
			$ty_ji=1;
			$ty_uy=1;
		}
		$medid= $ty_uy/$ty_ji;
		
		$media = number_format("$medid",2, ".", "");
		//======================check the treasury====================
		
		$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$received_name' AND tyi='cash'";
		$result=$this->comman_model->get_all_data_by_query($vpsql);
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvf = count($result);// $view_posts->rowCount();
		foreach($result as $postsfetch) {
			$numberya = $postsfetch['number'];
		}
		
		$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$given_name' AND tyi='cash'";
		$result=$this->comman_model->get_all_data_by_query($vpsql);
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
		// $view_posts->execute();
		$numvh = count($result);// $view_posts->rowCount();
		foreach ($result as $postsfetch) {
			$numberyb = $postsfetch['number'];
		}
		
		if($numberyb >= $pd){
		unset($_SESSION['myerror']);
		if($received_name == "LYD"){
		$calc = "";
		}else{
			if($usd > 1){
				$calc= $un*$usd;
			}else{
				$calc= $un/$usd;
			}
		}
		//===============end of the checking=================================
		//================calculate the values (buy & sell)========================
		
		if($kin == lang('sell')){
			
		//taken treasury value
		$numbero = $numberya+$un;
		//given treasury value
		$numberb = $numberyb-$pd;
		}elseif($kin == lang('buy')){
		//taken treasury value
		$numbero = $numberya+$un;
		//given treasury value
		$numberb = $numberyb-$pd;
		}
		//=====================insert the value into tabel transaction=====================
		$data = array(
			'post_id'   => $post_id,
			'user_id'      => $p_user_id,
			'chak_id'      => $post_ido,
			'exchange'      => $usd,
			'received'      => $un,
			'given'      => $pd,
			'received_name'      => $received_name,
			'given_name'      => $given_name,
			'kin'      => $kin,
			'type'      => $ty_kin,
			'media'      => $media,
			'time'      => $p_time,
			'date'		=>	$date,
			'timex'      => $timec
		);
		
		$this->comman_model->insert_entry("transactions",$data);	

		// 	$iptdbsqli = "INSERT INTO transactions
		// (post_id,user_id,chak_id,exchange,received,given,received_name,given_name,kin,type,media,time,date,timex)
		// VALUES
		// ( :post_id, :p_user_id, :post_ido, :usd, :un, :pd, :received_name, :given_name, :kin, :ty_kin, :media, :p_time, :date, :timec)
		// ";
		// $insert_post_toDBi = $conn->prepare($iptdbsqli);
		// $insert_post_toDBi->bindParam(':post_id', $post_id,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':p_time', $p_time,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':media', $media,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':un', $un,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':timec', $timec,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':date', $date,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':received_name', $received_name,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':kin', $kin,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':pd', $pd,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':ty_kin', $ty_kin,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':usd', $usd,PDO::PARAM_STR);
		// $insert_post_toDBi->execute();
		
		$vpsql = "SELECT * FROM costumers WHERE shop_id=$shop_id AND name='$name'";
		$result=$this->comman_model->get_all_data_by_query($vpsql);
		
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':name', $name, PDO::PARAM_INT);
		// $view_postsi->execute();
		$num_name = count($result);// $view_postsi->rowCount();
		foreach($result as $post_viewi) {
			$main_id = $post_viewi['main_id'];
		}
	
		if($num_name < 1){
		$main_id = rand(0,9999999)+time();
		
			$data = array(
				'main_id'   => $main_id,
				'boss_id'      => $boss_id,
				'shop_id'      => $shop_id,
				'user_id'      => $p_user_id,
				'name'      => $name
			
			);
			
			$this->comman_model->insert_entry("costumers",$data);	

			// $iptdbsqli = "INSERT INTO costumers
			// (main_id,boss_id,shop_id,user_id,name)
			// VALUES
			// ( :main_id, :boss_id, :shop_id, :user_id, :name)
			// ";
			// $insert_post_toDBi = $conn->prepare($iptdbsqli);
			// $insert_post_toDBi->bindParam(':main_id', $main_id,PDO::PARAM_STR);
			// $insert_post_toDBi->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':user_id', $p_user_id,PDO::PARAM_INT);
			// $insert_post_toDBi->bindParam(':name', $name,PDO::PARAM_STR);
			// $insert_post_toDBi->execute();
		}

		
		$data = array(
			'post_id'   => $post_ido,
			'user_id'      => $p_user_id,
			'cos_id'      => $main_id		
		);
		$this->comman_model->insert_entry("cos_transactions",$data);	
		

		// $iptdbsqli = "INSERT INTO cos_transactions
		// (post_id,user_id,cos_id)
		// VALUES
		// ( :post_ido, :p_user_id, :cosnam)
		// ";
		// $insert_post_toDBi = $conn->prepare($iptdbsqli);
		// $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDBi->bindParam(':cosnam', $main_id,PDO::PARAM_INT);
		// $insert_post_toDBi->execute();
		//===================insert the value into table treasury================================
		$cash = "cash";
		
		if(1 > $numvf){
			$data = array(
				'user_id'   => $p_user_id,
				'shop_id'      => $shop_id,
				'boss_id'      => $boss_id,
				'kind'		=>  $received_name,
				'number'	=>	$un,
				'tyi'		=>	$cash
			);
			$this->comman_model->insert_entry("treasury",$data);


		// $iptdbsql = "INSERT INTO treasury
		// (user_id, shop_id, boss_id,kind,number,tyi)
		// VALUES
		// ( :p_user_id, :shop_id, :boss_id, :received_name, :un, :cash)
		// ";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':cash', $cash,PDO::PARAM_STR);
		// $insert_post_toDB->execute();
		}else{
			$iptdbsql = "UPDATE treasury SET number='$numbero' WHERE shop_id = $shop_id AND kind='$received_name' AND tyi='cash'";			
			$this->comman_model->run_query($iptdbsql);
			
			// $insert_post_toDB = $conn->prepare($iptdbsql);
			// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
			// $insert_post_toDB->execute();
		}
		
			
		if(1 > $numvh){
			
			$data = array(
				'user_id'   => $p_user_id,
				'shop_id'      => $shop_id,
				'boss_id'      => $boss_id,
				'kind'		=>  $given_name,
				'number'	=>	$un,
				'tyi'		=>	$cash
			);
			$this->comman_model->insert_entry("treasury",$data);


		// $iptdbsql = "INSERT INTO treasury
		// (user_id, shop_id, boss_id,kind,number,tyi)
		// VALUES
		// ( :p_user_id, :shop_id, :boss_id, :given_name, :un, :cash)
		// ";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':cash', $cash,PDO::PARAM_STR);
		// $insert_post_toDB->execute();
		}else{
			
			$iptdbsql = "UPDATE treasury SET number='$numberb' WHERE shop_id = $shop_id AND kind='$given_name' AND tyi='cash'";
			
			$this->comman_model->run_query($iptdbsql);
			
			// $insert_post_toDB = $conn->prepare($iptdbsql);
			// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':numberb', $numberb,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
			// $insert_post_toDB->execute();
		}
		//============================insert into tabel capital==========================================
		if($kin == lang('sell')){
			$data = array(
				'user_id'   => $p_user_id,
				'shop_id'      => $shop_id,
				'boss_id'      => $boss_id,
				'number'		=>  $un,
				'exchange'	=>	$usd,
				'kind'		=>	$received_name,
				'calc'		=>	$calc,
				'tyi'		=>	$cash,
				'timex'		=>	$timec
			);
			$this->comman_model->insert_entry("capital",$data);


		// $iptdbsql = "INSERT INTO capital
		// (user_id, shop_id, boss_id,number,exchange,kind,calc,tyi,timex)
		// VALUES
		// ( :p_user_id, :shop_id, :boss_id, :un, :usd, :received_name, :calc, :cash,:timec)
		// ";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':timec', $timec,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':usd', $usd,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':calc', $calc,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':cash', $cash,PDO::PARAM_STR);
		// $insert_post_toDB->execute();
		}
		//=============================end insert into table capital ===================================
		//=============================error: there is no money in treasury ===================================
		}else{
			$_SESSION['myerror'] = number_format("$numberyb",2, ".", "")." $given_name :".lang('youhave');
		}

		//=======================fetching the information in the table============================
		//include("fetch_users_info.php");
		$s_id = $_SESSION['id'];
		$s_username = $_SESSION['username'];
		
		$un = filter_var(htmlspecialchars($_GET['u']),FILTER_SANITIZE_STRING);
		
		$uisql = "SELECT * FROM signup WHERE Username='$un'";
		$result=$this->comman_model->get_all_data_by_query($uisql);
		// $que = $conn->prepare($uisql);
		// $que->bindParam(':un', $un, PDO::PARAM_STR);
		// $que->execute();
		foreach($result as $row){		
			$row_username = $row['username'];
			$row_email = $row['email'];
			$row_password = $row['password'];
			$row_user_cover_photo = $row['user_cover_photo'];
			$row_school = $row['school'];
			$row_work = $row['work'];
			$row_work0 = $row['work0'];
			$row_country = $row['country'];
			$row_birthday = $row['birthday'];
			$row_verify = $row['verify'];
			$row_website = $row['website'];
			$row_bio = $row['bio'];
			$row_admin = $row['admin'];
			$row_gender = $row['gender'];
			$row_profile_pic_border = $row['profile_pic_border'];
			$row_language = $row['language'];
			$row_online = $row['online'];
		}



		//include ("time_function.php");
		//include ("num_k_m_count.php");
		$vpsql = "SELECT * FROM transactions WHERE post_id =  $post_id ";
		$result=$this->comman_model->get_all_data_by_query($vpsql);

		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':post_id', $post_id, PDO::PARAM_INT);
		// $view_posts->execute();
		//include "fetch_posts.php";
	}
}
