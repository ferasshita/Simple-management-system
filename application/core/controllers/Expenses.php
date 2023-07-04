<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenses extends CI_Controller {

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



    public function Index()
    {
        if($_SESSION['user_email_status'] == "not verified"){
            header("location:email_verification");
        }
        if($_SESSION['steps'] != "1"){
            header('location:steps?tc=shop');
        }

		if ($_SESSION['buythings'] == '1') {
			header("location: home");
		}
        $mode=LoadMode();
        $data["dircheckPath"]= base_url()."Asset/";
        $data["layoutmode"]  =   $mode;
        $data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
		////////////////////////////////////////////////////
		$sid =  $_SESSION['id'];
		$branch = "LYD";
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
		$boss_id = $_SESSION['boss_id'];
		
		$vpsqlu = " SELECT * FROM my_bank WHERE boss_id=$boss_id ";
		$result=$this->comman_model->get_all_data_by_query($vpsqlu);
		$data["mybank"]=$result;


		$vpsql = "SELECT DISTINCT kind FROM capital WHERE boss_id=$gfid AND kind!= '$branch' OR shop_id= $gfid AND kind!= '$branch' ";
		$result=$this->comman_model->get_all_data_by_query($vpsql);
		$ty_uy=0;
		$ty_ji=0;
		foreach($result as $postsfetch) {
			$ghj = $postsfetch['kind'];

			$vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE boss_id=$gfid AND kind= '$ghj' OR shop_id= $gfid AND kind='$ghj' ";
			$result=$this->comman_model->get_all_data_by_query($vpsql);
			$num = count($result);// $view_postsi->rowCount();
			foreach($result as $postsfetch) {
				$ty_uy = $postsfetch['ty_uy'];
			}
			$vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE boss_id=$gfid AND kind='$ghj' OR shop_id=$gfid AND kind= '$ghj'";
			$result=$this->comman_model->get_all_data_by_query($vpsql);
			$num = count($result);// $view_postsi->rowCount();
			foreach($result as  $postsfetch ) 
			{
				$ty_ji = $postsfetch['ty_ji'];
			}
			$media = $ty_uy/$ty_ji;
			$data["media"]=$media;
			$data["ghj"]=$ghj;
		
		}

		$vpsql = "SELECT DISTINCT note FROM expenses WHERE shop_id=$gfid OR boss_id=$gfid ORDER BY time DESC";
		$result=$this->comman_model->get_all_data_by_query($vpsql);
		$data["notes"]=$result;



		
		$typey = "head";
		$ghy = "LYD";
	
		$boss_id = $_SESSION['boss_id'];
		if($typem == "boss"){
			$gfid = $boss_id;
		}elseif($typem == "admin"){
			$gfid = $shop_id;
		}else{
			$gfid = $shop_id;
		}

		$vpsql = "SELECT * FROM expenses WHERE shop_id=$gfid OR boss_id=$gfid ORDER BY time DESC";
		$result=$this->comman_model->get_all_data_by_query($vpsql);

		$data["ExpensesData"]=$result;
		
		$array=array();
		$username="";
		foreach($result as $postsfetch){
			$user_id = $postsfetch['user_id'];
			$vpsql = "SELECT Username FROM signup WHERE id=$user_id ";
			$result=$this->comman_model->get_all_data_by_query($vpsql);
			foreach($result as  $postsfetchb) {
				
				$array["ExpensesData"][$user_id][] = $postsfetchb;
			}

			$ytz = $postsfetch['yt'];
			if($ytz != "0"){
				$vpsql = "SELECT bank_name,country FROM my_bank WHERE id=$ytz";
				$result=$this->comman_model->get_all_data_by_query($vpsql);
				foreach($result as  $postsfetchb) {
					$array["Expeses"][$ytz] = $postsfetchb;
				}
			}

			$vpsql = "SELECT Username FROM signup WHERE id= $shop_id ";
			$result=$this->comman_model->get_all_data_by_query($vpsql);
			
			foreach($result as $item){
				
				$array["UData"][$shop_id][]=$item;
			}
			

		}
		
		$data["Expeses"]=$array;
		

		$bgh = "cash";

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
		$data["active"] = "expenses";



		$this->load->view('Expenses/index', $data);


    }


	public function wexpenses(){
		$post_id = rand(0,9999999)+time();
		$p_user_id = $_SESSION['id'];
		$p_author = $_SESSION['Fullname'];
		$boss_id = $_SESSION['boss_id'];
		$shop_id = $_SESSION['shop_id'];
		$p_author_photo = $_SESSION['Userphoto'];
		$timec = time();
		//===================which boss====================
		$sid =  $_SESSION['id'];
		$shopo =  $_SESSION['shop_id'];
		$typo =  $_SESSION['type'];
		
		$fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$sid' OR shop_id='$shopo' AND type='admin' OR id='$sid'";
		$result=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		
		// $fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$sid' OR shop_id='$shopo' AND type='admin' OR id='$sid'";
		// $fetchUsers = $conn->prepare($fetchUsers_sql);
		// $fetchUsers->execute();
		foreach ($result as  $rows) {
			$gfid = $rows['id'];
		}
		$yy = date(Y);
		$mm = date('m');
		$dd = date('d');
		$jgj = "$yy-$mm-$dd";
		//========================== input and form ===============================
		$un = filter_var(htmlspecialchars($_POST['amou']),FILTER_SANITIZE_STRING);
		$received_name = filter_var(htmlspecialchars($_POST['received_name']),FILTER_SANITIZE_STRING);
		$ex = filter_var(htmlspecialchars($_POST['ex']),FILTER_SANITIZE_STRING);
		$note = filter_var(htmlspecialchars($_POST['note']),FILTER_SANITIZE_STRING);
		$notey = filter_var(htmlspecialchars($_POST['notey']),FILTER_SANITIZE_STRING);
		$ty = filter_var(htmlspecialchars($_POST['ty']),FILTER_SANITIZE_STRING);
		$idjd = filter_var(htmlspecialchars($_POST['idjd']),FILTER_SANITIZE_STRING);
		$time = filter_var(htmlspecialchars($_POST['time']),FILTER_SANITIZE_STRING);
		$yer = filter_var(htmlspecialchars($_POST['yer']),FILTER_SANITIZE_STRING);
		if($received_name == "LYD"){
		$calc = "";
		}else{
			if($ex > 1){
				$calc= $un*$ex;
			}else{
				$calc= $un/$ex;
			}
		}
		//===================check if there is money=====================================
		
		$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind= '$received_name' AND wh= '$idjd' ";

		$result=$this->comman_model->get_all_data_by_query($vpsql);

		// $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:ty AND wh=:idjd";
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':ty', $received_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':idjd', $idjd, PDO::PARAM_INT);
		// $view_postsi->execute();
		foreach ($result as  $postsfetch) {
			$numberaq = $postsfetch['number'];
		}
		if($numberaq >= $un){
		unset($_SESSION['myerrorb']);
		}else{
		if(!$numberaq){
		$_SESSION['myerrorb'] = "0 $received_name :".lang('youhave');
		return false;
		}else{
		$_SESSION['myerrorb'] = number_format("$numberaq",2, ".", "")." $received_name :".lang('youhave');
		return false;
		}
		}
		//=======================start insert or update profit=========================

		$data = array(
			'user_id'   => $p_user_id,
			'boss_id'      => $boss_id,
			'shop_id'      => $shop_id,
			'number'      => $un,
			'ex'      => $ex,
			'calc'      => $calc,
			'type'      => $received_name,
			'note'      => $note,
			'notey'      => $notey,
			'yt'      => $idjd,
			'time'      => $time,
			'datepost'      => $jgj,
			'yer'      => $yer,
			'toyer'	=>	$jgj,
			'fgyer'	=>	$yy,
			'timex'	=>	$timec
		);

		$this->comman_model->insert_entry("expenses",$data);


		// 	$iptdbsql = "INSERT INTO expenses
		// (user_id,boss_id,shop_id,number,ex,calc,type,note,notey,yt,time,datepost,yer,toyer,fgyer,timex)
		// VALUES
		// ( :p_user_id, :boss_id, :shop_id, :un, :ex, :calc, :received_name, :note, :notey, :idjd, :time, :jgj, :yer, :yy, :fgyer, :timec)
		// ";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':timec', $timec,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':fgyer', $yy,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':ex', $ex,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':yer', $yer,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':yy', $jgj,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':notey', $notey,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':calc', $calc,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':note', $note,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':idjd', $idjd,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':time', $time,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':jgj', $jgj,PDO::PARAM_STR);
		// $insert_post_toDB->execute();
		//=======================end of insert or update profit=========================
		//========================check if there is money==============================
		$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind= '$received_name' AND wh= '$idjd' ";
		$result=$this->comman_model->get_all_data_by_query($vpsql);

		// $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:received_name AND wh=:idjd";
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':received_name', $received_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':idjd', $idjd, PDO::PARAM_INT);
		// $view_postsi->execute();
		$numsh = count($result);// $view_postsi->rowCount();
		foreach($result as  $postsfetch) {
			$number = $postsfetch['number'];
		}
		$numbercalv = $number-$un;
		//==============================end of the checking================================
		//==============================insert money========================================

		$data = array(
			'number'   =>$numbercalv			
		);
		$where=array('shop_id' => $shop_id,'kind' => $received_name , 'wh' =>$idjd );
		$update_info=$this->comman_model->update_entry("treasury",$data,$where);

		// 	$iptdbsql = "UPDATE treasury SET number=:numbercalv WHERE shop_id = :p_user_id AND kind=:received_name AND wh=:idjd";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbercalv', $numbercalv,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
		// $insert_post_toDB->execute();

	}

	public function delete_expenses(){
				
		// include("../config/connect.php");
		// session_start();
		$sid = $_SESSION['id'];
		$shop_id = $_SESSION['shop_id'];
		//==================function delet expenses========================
		$c_id = htmlentities($_POST['cid'], ENT_QUOTES);
		$p_id = htmlentities($_POST['pid'], ENT_QUOTES);
		//===================check the user========================
		$check = "SELECT * FROM expenses WHERE id =$c_id";
		$result=$this->comman_model->get_all_data_by_query($check);
		// $check->bindParam(':c_id',$c_id,PDO::PARAM_INT);
		// $check->execute();
		foreach($result as $chR) 
		{
			$chR_aid = $chR['user_id'];
			$received = $chR['number'];
			$received_name = $chR['type'];
			$yt = $chR['yt'];
		}
		//=================== select the treasury==========================
		$check = "SELECT * FROM treasury WHERE kind='$received_name' AND wh='$yt'";
		$result=$this->comman_model->get_all_data_by_query($check);
		// $check->bindParam(':received_name',$received_name,PDO::PARAM_INT);
		// $check->bindParam(':yt',$yt,PDO::PARAM_INT);
		// $check->execute();
		foreach ($result as $chR) {
			$numbero = $chR['number'];
		}
		//===================calculate the treasury==========================
		$numbery = $numbero+$received;

		//==================delete the expense =================================
			$delete_comm_sql = "DELETE FROM expenses WHERE id = $c_id";
			$result=$this->comman_model->run_query($delete_comm_sql);
			// $delete_comm = $conn->prepare($delete_comm_sql);
			// $delete_comm->bindParam(':c_id',$c_id,PDO::PARAM_INT);
			// $delete_comm->execute();
		//===================update the treasury=========================
		$iptdbsql = "UPDATE treasury SET number='$numbery' WHERE kind='$received_name' AND wh='$yt' AND shop_id=$shop_id";
		$result=$this->comman_model->run_query($iptdbsql);

		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':numbery', $numbery,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':yt',$yt,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':shop_id',$shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
		//=============================================================
			echo "yes";
	}

}
