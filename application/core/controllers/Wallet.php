<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet extends CI_Controller {

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
			$url=base_url()."email_verification";
            header("location:email_verification");
        }
        if($_SESSION['steps'] != "1"){
			$url=base_url()."steps?tc=shop";
            header("location: $url");
        }

        $mode=LoadMode();
        $data["dircheckPath"]= base_url()."Asset/";
        $data["layoutmode"]  =   $mode;
        $data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();

        $sid =  $_SESSION['id'];
        $branch = "LYD";
        $typem =  $_SESSION['type'];
     
        $shop_id = $_SESSION['shop_id'];
        $boss_id = $_SESSION['boss_id'];
        if($typem == "boss"){
            $gfid = $boss_id;
        }elseif($typem == "admin"){
            $gfid = $shop_id;
        }else{
            $gfid = $shop_id;
        }

        $vpsql = "SELECT DISTINCT kind FROM capital WHERE boss_id=$gfid AND kind!= '$branch' OR shop_id= $gfid AND kind!= '$branch'";		
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		//$numcoss = $view_posts->rowCount();
		$array=array();
		$mediaResult=0;
		$ghj="";
        $data["kinds"]=$FetchedData;
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

        $vpsql = "SELECT * FROM my_bank WHERE boss_id=$boss_id ";
        $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
        $data["mybank"]=$FetchedData;


        $typey = "head";
        $ghy = "LYD";
        $vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE type= '$typey'  AND shop_id= $gfid OR type= '$typey'  AND boss_id= $gfid";
        $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
        //$data["tys"]=$FetchedData;
        $ty_uy = $FetchedData[0]['ty_uy'];


        $vpsql = "SELECT SUM(number) AS ty_uy FROM capital WHERE kind='$ghy' AND type= '$typey'  AND shop_id= $gfid OR kind='$ghy' AND type= '$typey'  AND boss_id = $gfid ";
        $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
        $ty_ub = $FetchedData[0]['ty_uy'];
        $data["tyji"]  =  $ty_ub+$ty_uy;

	//	$vpsql = "SELECT * FROM capital WHERE type=:typey  AND shop_id=:sid OR type=:typey  AND boss_id=:sid  ORDER BY time DESC";
		                    // $view_posts = $conn->prepare($vpsql);
                    // $view_posts->bindParam(':sid', $gfid, PDO::PARAM_INT);
                    // $view_posts->bindParam(':typey', $typey, PDO::PARAM_INT);
                    // $view_posts->execute();

        $vpsql = "SELECT * FROM capital WHERE type= '$typey'  AND shop_id=  $gfid OR type= '$typey'  AND boss_id=  $gfid  ORDER BY time DESC";
        $FetchCapitalData=$this->comman_model->get_all_data_by_query($vpsql);   
        $data["capitalsData"]=$FetchCapitalData;
        foreach($FetchCapitalData as $item){
           
           $wh = $item['wh'];
           if($wh != ""){
                if($wh != "0")
                {
                  
                    $vpsqlu = "SELECT * FROM my_bank WHERE id= $wh ";
                    $FetchedData=$this->comman_model->get_all_data_by_query($vpsqlu); 
                    $data["capitals"][$wh]=$FetchedData;
                }
           }
        }
        foreach($FetchCapitalData as $item){         
            $whb = $item['whb'];
            if($whb != "")
            {
                if($whb != "0"){
                    $vpsqlu = "SELECT * FROM my_bank WHERE id=$whb ";
                    $FetchedData=$this->comman_model->get_all_data_by_query($vpsqlu); 
                    $data["capitals"][$whb]=$FetchedData;
                }
            }
        }
      	//============total of the money=============================
        $bgh = "cash";
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
		$data["active"] = "wallet";
		$this->load->view('Wallet/index', $data);


    }


	public function Wwallet(){
		error_reporting(0);
		
		//include "../config/connect.php";
		$post_id = rand(0,9999999)+time();
		$p_user_id = $_SESSION['id'];
		$p_author = $_SESSION['Fullname'];
		$p_author_photo = $_SESSION['Userphoto'];
		$shop_id = $_SESSION['shop_id'];
		$boss_id = $_SESSION['boss_id'];
		$timec = time();
		$un = filter_var(htmlspecialchars($_POST['amou']),FILTER_SANITIZE_STRING);
		$received_name = filter_var(htmlspecialchars($_POST['received_name']),FILTER_SANITIZE_STRING);
		$ex = filter_var(htmlspecialchars($_POST['ex']),FILTER_SANITIZE_STRING);
		$time = filter_var(htmlspecialchars($_POST['time']),FILTER_SANITIZE_STRING);
		$note = filter_var(htmlspecialchars($_POST['note']),FILTER_SANITIZE_STRING);
		$ty = filter_var(htmlspecialchars($_POST['ty']),FILTER_SANITIZE_STRING);
		$cda = filter_var(htmlspecialchars($_POST['cda']),FILTER_SANITIZE_STRING);
		$headed = filter_var(htmlspecialchars($_POST['headed']),FILTER_SANITIZE_STRING);
		$id = filter_var(htmlspecialchars($_POST['id']),FILTER_SANITIZE_STRING);

		$submit_name = filter_var(htmlspecialchars($_POST['submit_name']),FILTER_SANITIZE_STRING);
		$amoun_submit = filter_var(htmlspecialchars($_POST['amoun_submit']),FILTER_SANITIZE_STRING);
		$from_name = filter_var(htmlspecialchars($_POST['from_name']),FILTER_SANITIZE_STRING);
		$to_name = filter_var(htmlspecialchars($_POST['to_name']),FILTER_SANITIZE_STRING);

		if($received_name == "LYD"){
		$calc = "";
		}else{
			$calc= $un*$ex;
		}
		
		if($cda == "bank")
		{
		$bgh = "bank";
		//===================check if there is money in wallet=====================================
		
		$vpsql = "SELECT * FROM capital WHERE shop_id= $shop_id AND kind= '$received_name' AND type= '$ty' AND wh= $id AND tyi= '$bg' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);

		
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
		// $view_posts->bindParam(':id', $id, PDO::PARAM_INT);
		// $view_posts->bindParam(':ty', $ty, PDO::PARAM_INT);
		// $view_posts->bindParam(':bgh', $bgh, PDO::PARAM_STR);
		// $view_posts->execute();
		$numvg = count($FetchedData);//$view_posts->rowCount();
		foreach($FetchedData as $postsfetch) {
			$numberg = $postsfetch['number'];
		}
		$type = "head";
		$vpsql = "SELECT * FROM capital WHERE shop_id= $shop_id AND type= '$gth' AND wh= '$id' AND tyi= '$bgh'";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);

		
		// $vpsql = "SELECT * FROM capital WHERE shop_id=:p_user_id AND type=:ty AND wh=:id AND tyi=:bgh";
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':ty', $gth, PDO::PARAM_INT);
		// $view_postsi->bindParam(':id', $id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':bgh', $bgh, PDO::PARAM_STR);
		// $view_postsi->execute();
		$numfdy = count($FetchedData);// $view_postsi->rowCount();
		foreach($FetchedData as  $postsfetch) {
			$kindaq = $postsfetch['kind'];
		}

		$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind= '$kindaq' AND wh= '$id' AND tyi= '$bgh' ";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);

		// $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:ty AND wh=:id AND tyi=:bgh";
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':ty', $kindaq, PDO::PARAM_INT);
		// $view_postsi->bindParam(':id', $id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':bgh', $bgh, PDO::PARAM_STR);
		// $view_postsi->execute();
		foreach($FetchedData as  $postsfetch ) {
			$numberaq = $postsfetch['number'];
		}
		//=======================end of the checking==============================
		//=======================start insert or update wallet=========================
		$data = array(
			'user_id'   => $p_user_id,
			'shop_id'      => $shop_id,
			'boss_id'      => $boss_id,
			'number'      => $un,
			'exchange'      => $ex,
			'kind'      => $received_name,
			'calc'      => $calc,
			'type'      => $type,
			'note'		=> $note,
			'wh'		=> $id,
			'tyi'		=> $bgh,
			'headed'		=> $headed,
			'time'		=> $time,
			'timex'		=> $timec,
		

		);

		$this->comman_model->insert_entry("capital",$data);		


		// 	$iptdbsql = "INSERT INTO capital
		// (user_id,shop_id,boss_id,number,exchange,kind,calc,type,note,wh,tyi,headed,time,timex)
		// VALUES
		// ( :p_user_id, :shop_id, :boss_id, :un, :ex, :received_name, :calc, :type, :note, :id, :bgh, :headed, :time, :timec)
		// ";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':timec', $timec,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':ex', $ex,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':headed', $headed,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':calc', $calc,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':note', $note,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':id', $id, PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':type', $type,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':bgh', $bgh, PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':time', $time, PDO::PARAM_STR);
		// $insert_post_toDB->execute();
		$gskl = $numberaq-$calc;
			
		$data = array(
			'number'   =>$gskl			
		);
		$where=array('shop_id' => $shop_id,'kind' => $kindaq , 'wh' => $id , 'tyi' => $bgh );
		$update_info=$this->comman_model->update_entry("treasury",$data,$where);

		// $iptdbsql = "UPDATE treasury SET number=:numbero WHERE shop_id = :p_user_id AND kind=:gth AND wh=:id AND tyi=:bgh";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbero', $gskl,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':gth', $kindaq,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':id', $id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':bgh', $bgh, PDO::PARAM_STR);
		// $insert_post_toDB->execute();
		//=======================end of insert or update profit=========================
		//========================check if there is money==============================
		$vpsql = "SELECT * FROM treasury WHERE shop_id= $shop_id AND kind= '$received_name' AND wh= '$id,' AND tyi= '$bgh' ";
		$result=$this->comman_model->get_all_data_by_query($vpsql);

		// $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:received_name AND wh=:id AND tyi=:bgh";
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':received_name', $received_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':id', $id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':bgh', $bgh, PDO::PARAM_STR);
		// $view_postsi->execute();
		$numsh = count($result); //$view_postsi->rowCount();
		foreach($result as $postsfetch) {
			$number = $postsfetch['number'];
		}
		$numbercalv = $number+$un;
		//==============================end of the checking================================
		//==============================insert money========================================
		if(1 > $numsh){

			
			$data = array(
				'user_id'   => $p_user_id,
				'shop_id'      => $shop_id,
				'boss_id'      => $boss_id,
				'kind'      => $received_name,
				'number'	=> $un,
				'wh'		=> $id,
				'tyi'		=>	$bgh
			);

			$this->comman_model->insert_entry("treasury",$data);



		// 		$iptdbsql = "INSERT INTO treasury
		// (user_id,shop_id,boss_id,kind,number,wh,tyi)
		// VALUES
		// ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
		// ";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':id', $id, PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':bgh', $bgh, PDO::PARAM_STR);
		// $insert_post_toDB->execute();
		}else{
				
			$data = array(
				'number'   =>$numbercalv			
			);
			$where=array('shop_id' => $shop_id,'kind' => $received_name , 'wh' =>$id , 'tyi' => $bgh );
			$update_info=$this->comman_model->update_entry("treasury",$data,$where);
			


		// 	$iptdbsql = "UPDATE treasury SET number=:numbercalv WHERE shop_id = :p_user_id AND kind=:received_name
		// 	 AND wh=:id AND tyi=:bgh";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbercalv', $numbercalv,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':id', $id, PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':bgh', $bgh, PDO::PARAM_STR);
		// $insert_post_toDB->execute();
		}
		}elseif($cda == "transfar"){
			
		$bgha = "cash";
		$bghb = "bank";
		$bgh = "transfar";
		if($from_name == "0"){
		$ty_kin = "cash";
		}else{
		$ty_kin = "bank";
		}
		$type = "head";
	
		$vpsql = "SELECT * FROM capital WHERE shop_id= $shop_id AND kind= '$from_name' AND tyi= '$ty_kin' ";
		$result=$this->comman_model->get_all_data_by_query($vpsql);
		
		// $vpsql = "SELECT * FROM capital WHERE shop_id=:sid AND kind=:received_name AND tyi=:tyi";
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':received_name', $from_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':tyi', $ty_kin, PDO::PARAM_INT);
		// $view_postsi->execute();
		$num = count($result); //$view_postsi->rowCount();
		foreach ($result as $postsfetch) {
			$numberhea = $postsfetch['number'];
			$exchangehea = $postsfetch['exchange'];
			$tyhea = $postsfetch['kind'];
			$ty_gt = $postsfetch['type'];
		}
		
		//=============calculate the average of the exchange rate=======================
		$vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE shop_id= $shop_id AND kind= '$given_name' AND tyi= '$ty_kin' ";
		$result=$this->comman_model->get_all_data_by_query($vpsql);

		// $vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE shop_id=:sid AND kind=:from_name AND tyi=:tyi";
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':from_name', $given_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':tyi', $ty_kin, PDO::PARAM_INT);
		// $view_postsi->execute();
		foreach($result as  $postsfetch) {
			$ty_uy = $postsfetch['ty_uy'];
		}

		$vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE shop_id= $shop_id AND kind= '$given_name' AND tyi='$ty_kin'";
		$result=$this->comman_model->get_all_data_by_query($vpsql);

		// $vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE shop_id=:sid AND kind=:from_name AND tyi=:tyi";
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':from_name', $given_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':tyi', $ty_kin, PDO::PARAM_INT);
		// $view_postsi->execute();
		foreach ($result as $postsfetch) {
			$ty_ji = $postsfetch['ty_ji'];
		}
		$medid= $ty_uy/$ty_ji;
		$media = number_format("$medid",2, ".", "");

		if($submit_name == "LYD"){
			$calcx = "";
		}else{
			$calcx= $amoun_submit*$media;
		}
		
		if($from_name == "0"){
		$vpsql = "SELECT * FROM treasury WHERE shop_id= $shop_id AND kind='$submit_name' AND wh='$from_name' AND tyi='$bgha'";
		$result=$this->comman_model->get_all_data_by_query($vpsql);

		// $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:ty AND wh=:id AND tyi=:bgh";
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':ty', $submit_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':id', $from_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':bgh', $bgha, PDO::PARAM_STR);
		// $view_postsi->execute();
		$numva = count($result);//$view_postsi->rowCount();
		foreach($result as  $postsfetch ) {
			$numbera = $postsfetch['number'];
		}
		}else{
		
		$vpsql = "SELECT * FROM treasury WHERE shop_id= $shop_id AND kind= '$submit_name' AND wh= '$from_name' AND tyi='$bghb' ";
		$result=$this->comman_model->get_all_data_by_query($vpsql);


		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':ty', $submit_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':id', $from_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':bgh', $bghb, PDO::PARAM_STR);
		// $view_postsi->execute();
		$numva = count($result); // $view_postsi->rowCount();
		foreach ($result as $postsfetch) {
			$numbera = $postsfetch['number'];
		}
		}
		if($numbera >= $amoun_submit){
		unset($_SESSION['myerrortrban']);

		if($to_name == "0"){
		$vpsql = "SELECT * FROM treasury WHERE shop_id= $shop_id AND kind= '$submit_name' AND wh='$to_name' AND tyi= '$bgha'";
		$result=$this->comman_model->get_all_data_by_query($vpsql);

		// $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:ty AND wh=:id AND tyi=:bgh";
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':ty', $submit_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':id', $to_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':bgh', $bgha, PDO::PARAM_STR);
		// $view_postsi->execute();

		$numvb = count($result); //  $view_postsi->rowCount();
		foreach($result as  $postsfetch) {
			$numberb = $postsfetch['number'];
		}
		}else{
		$vpsql = "SELECT * FROM treasury WHERE shop_id= $shop_id AND kind='$submit_name' AND wh= '$to_name' AND tyi='$bghb'";

		$result=$this->comman_model->get_all_data_by_query($vpsql);

		// $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:ty AND wh=:id AND tyi=:bgh";
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':ty', $submit_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':id', $to_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':bgh', $bghb, PDO::PARAM_STR);
		// $view_postsi->execute();
		$numvb = count($result);// $view_postsi->rowCount();
		foreach($result as $postsfetch) {
		$numberb = $postsfetch['number'];
		}
		}
		$numberca = $numbera-$amoun_submit;
		$numbercb = $numberb+$amoun_submit;
		//=======================end of the checking==============================
		//=======================start insert or update wallet=========================
		$data = array(
			'user_id'   => $p_user_id,
			'shop_id'      => $shop_id,
			'boss_id'	=>	$boss_id,
			'number'      => $amoun_submit,
			'exchange'      => $media,			
			'kind'      => $submit_name,
			'calc'      => $calcx,
			'type'      => $type,
			'note'      => $note,
			'wh'      => $to_name,
			'whb'      => $from_name,
			'tyi'      => $bgh,
			'headed'      => $headed,
			'time'      => $time,
			'timex'      => $timec,
		);

		$this->comman_model->insert_entry("capital",$data);

		// 	$iptdbsql = "INSERT INTO capital
		// (user_id,shop_id,boss_id,number,exchange,kind,calc,type,note,wh,whb,tyi,headed,time,timex)
		// VALUES
		// ( :p_user_id, :shop_id, :boss_id, :un, :ex, :from_name, :calc, :type, :note, :id, :whb, :bgh, :headed, :time, :timec)
		// ";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':timec', $timec,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':un', $amoun_submit,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':ex', $media,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':headed', $headed,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':from_name', $submit_name,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':calc', $calcx,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':note', $note,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':id', $to_name, PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':whb', $from_name, PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':type', $type,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':bgh', $bgh, PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':time', $time, PDO::PARAM_STR);
		// $insert_post_toDB->execute();

		//==============================end of the checking================================
		//==============================insert money========================================
		$data = array(
			'number'   =>$numberca			
		);
		$where=array('shop_id' => $shop_id,'kind' => $submit_name , 'wh' =>$from_name );
		$update_info=$this->comman_model->update_entry("treasury",$data,$where);

		// $iptdbsql = "UPDATE treasury SET number=:numbercalv WHERE shop_id = :p_user_id AND kind=:received_name AND wh=:id";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbercalv', $numberca,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':id', $from_name, PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $submit_name,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
	

		if(1 > $numvb){
			
			
			$bghcustom="";
			if($to_name == "0"){
				$bghcustom= $bgha;
			}else{
				$bghcustom= $bghb;
				//$insert_post_toDB->bindParam(':bgh', $bghb, PDO::PARAM_STR);
			}

			$data = array(
				'user_id'   => $p_user_id,
				'shop_id'      => $shop_id,
				'boss_id'	=>	$boss_id,
				'kind'      => $submit_name,
				'number'      => $amoun_submit,			
				'wh'      => $to_name,
				'tyi'	=> $bghcustom
			);
	
			$this->comman_model->insert_entry("treasury",$data);


		// 		$iptdbsql = "INSERT INTO treasury
		// (user_id,shop_id,boss_id,kind,number,wh,tyi)
		// VALUES
		// ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
		// ";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':un', $amoun_submit,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':id', $to_name, PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $submit_name,PDO::PARAM_STR);
		// if($to_name == "0"){
		// $insert_post_toDB->bindParam(':bgh', $bgha, PDO::PARAM_STR);
		// }else{
		// $insert_post_toDB->bindParam(':bgh', $bghb, PDO::PARAM_STR);
		// }
		// $insert_post_toDB->execute();
		}else{
			$data = array(
				'number'   =>$numbercb			
			);
			$where=array('shop_id' => $shop_id,'kind' => $submit_name , 'wh' =>$to_name );
			$update_info=$this->comman_model->update_entry("treasury",$data,$where);


		// 	$iptdbsql = "UPDATE treasury SET number=:numbercalv WHERE shop_id = :p_user_id AND kind=:received_name AND wh=:id";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbercalv', $numbercb,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':id', $to_name, PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $submit_name,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
		}
		}else{
		$_SESSION['myerrortrban'] = number_format("$numbera",2, ".", "")." $submit_name :".lang('youhave');
		}
		}else{
		$fgdx = "cash";
		//===================check if there is wallet=====================================
		
		$vpsql = "SELECT * FROM capital WHERE shop_id= $shop_id  AND kind= '$received_name' AND type= '$ty' AND tyi='$fgdx' ";
		$result=$this->comman_model->get_all_data_by_query($vpsql);

		//$vpsql = "SELECT * FROM capital WHERE shop_id=:p_user_id AND kind=:received_name AND type=:ty AND tyi=:fgdx";
		// $view_posts = $conn->prepare($vpsql);
		// $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
		// $view_posts->bindParam(':ty', $ty, PDO::PARAM_INT);
		// $view_posts->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
		// $view_posts->execute();
		$numvg = count($result);// $view_posts->rowCount();
		foreach($result as $postsfetch) 
		{
			$numberg = $postsfetch['number'];
		}
		$type = "head";
		$vpsql = "SELECT * FROM capital WHERE shop_id= $shop_id  AND type= '$gth' AND tyi='$fgdx' ";
		$result=$this->comman_model->get_all_data_by_query($vpsql);


		// $vpsql = "SELECT * FROM capital WHERE shop_id=:p_user_id AND type=:ty AND tyi=:fgdx";
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':ty', $gth, PDO::PARAM_INT);
		// $view_postsi->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
		// $view_postsi->execute();
		$numfdy = count($result); /// $view_postsi->rowCount();
		foreach($result as  $postsfetch) {
			$kindaq = $postsfetch['kind'];
		}

		$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$kindaq' AND tyi='$fgdx'";
		$result=$this->comman_model->get_all_data_by_query($vpsql);

		// $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:ty AND tyi=:fgdx";
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':ty', $kindaq, PDO::PARAM_INT);
		// $view_postsi->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
		// $view_postsi->execute();
		foreach($result as  $postsfetch) {
			$numberaq = $postsfetch['number'];
		}
		//=======================end of the checking==============================
		//=======================start insert or update profit=========================
		$data = array(
			'user_id'   => $p_user_id,
			'shop_id'      => $shop_id,
			'boss_id'      => $boss_id,
			'number'      => $un,				
			'exchange'      => $ex,
			'kind'      => $received_name,
			'calc'      => $calc,
			'type'      => $type,
			'note'      => $note,
			'tyi'      => $fgdx,
			'headed'      => $headed,
			'time'      => $time,
			'timex'      => $timec
		);

		$this->comman_model->insert_entry("capital",$data);


		// 	$iptdbsql = "INSERT INTO capital
		// (user_id,shop_id,boss_id,number,exchange,kind,calc,type,note,tyi,headed,time,timex)
		// VALUES
		// ( :p_user_id, :shop_id, :boss_id, :un, :ex, :received_name, :calc, :type, :note, :fgdx, :headed, :time,:timec)
		// ";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':timec', $timec,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':headed', $headed,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':ex', $ex,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':calc', $calc,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':note', $note,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':type', $type,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':time', $time, PDO::PARAM_STR);
		// $insert_post_toDB->execute();
		$gskl = $numberaq-$calc;
		
			
		$data = array(
			'number'   =>$gskl			
		);
		$where=array('shop_id' => $shop_id,'kind' => $kindaq , 'tyi' =>$fgdx );
		$update_info=$this->comman_model->update_entry("treasury",$data,$where);

		// $iptdbsql = "UPDATE treasury SET number=:numbero WHERE shop_id = :p_user_id AND kind=:gth AND tyi=:fgdx";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbero', $gskl,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':gth', $kindaq,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
		// $insert_post_toDB->execute();
		//=======================end of insert or update profit=========================
		//========================check if there is money==============================
		$vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind= '$received_name' AND tyi='$fgdx' ";
		$result=$this->comman_model->get_all_data_by_query($vpsql);

		// $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:received_name AND tyi=:fgdx";
		// $view_postsi = $conn->prepare($vpsql);
		// $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		// $view_postsi->bindParam(':received_name', $received_name, PDO::PARAM_INT);
		// $view_postsi->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
		// $view_postsi->execute();
		$numsh = count($result);// $view_postsi->rowCount();
		foreach($result as  $postsfetch) {
			$number = $postsfetch['number'];
		}
		$numbercalv = $number+$un;
		//==============================end of the checking================================
		//==============================insert money========================================
		if(1 > $numsh){
			$data = array(
				'user_id'   => $p_user_id,
				'shop_id'      => $shop_id,
				'boss_id'      => $boss_id,
				'kind'      => $received_name,				
				'number'      => $un,
				'tyi'      => $fgdx,
			);

			$this->comman_model->insert_entry("treasury",$data);

		// 		$iptdbsql = "INSERT INTO treasury
		// (user_id,shop_id,boss_id,kind,number,tyi)
		// VALUES
		// ( :p_user_id, :shop_id, :boss_id, :received_name, :un, :fgdx)
		// ";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
		// $insert_post_toDB->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
		// $insert_post_toDB->execute();
		}else{

			$data = array(
				'number'   =>$numbercalv			
			);
			$where=array('shop_id' => $shop_id,'kind' => $received_name , 'tyi' =>$fgdx );
			$update_info=$this->comman_model->update_entry("treasury",$data,$where);
			

		// 	$iptdbsql = "UPDATE treasury SET number=:numbercalv WHERE shop_id = :p_user_id AND kind=:received_name AND tyi=:fgdx";
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbercalv', $numbercalv,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
		// $insert_post_toDB->execute();
		}
		}
	}


	public function delete_wallet(){

		//include("../config/connect.php");
		//session_start();
		$shopo =  $_SESSION['shop_id'];
		$boss_id =  $_SESSION['boss_id'];
		//==================from the function========================
		$c_id = htmlentities($_POST['cid'], ENT_QUOTES);
		$p_id = htmlentities($_POST['pid'], ENT_QUOTES);
		$delo = "0";
		//===================check the user========================
		$check = "SELECT * FROM capital WHERE id =$c_id";
		$result=$this->comman_model->get_all_data_by_query($check);
		// $check->bindParam(':c_id',$c_id,PDO::PARAM_INT);
		// $check->execute();
		foreach($result as $chR) 
		{
			$chR_aid = $chR['user_id'];
			$shop_id = $chR['shop_id'];
			$received = $chR['number'];
			$type = $chR['type'];
			$tyi = $chR['tyi'];
			$received_name = $chR['kind'];
			$wh = $chR['wh'];
			$whb = $chR['whb'];
		}

		if($tyi == "transfar"){
			$bgha = "cash";
		  $bghb = "bank";
		  $bgh = "transfar";
		if($wh == "0"){
		  $vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$received_name' AND wh='$wh' AND tyi='$bgha'";
		  $result=$this->comman_model->get_all_data_by_query($check);
		//   $view_postsi = $conn->prepare($vpsql);
		//   $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		//   $view_postsi->bindParam(':ty', $received_name, PDO::PARAM_INT);
		//   $view_postsi->bindParam(':id', $wh, PDO::PARAM_INT);
		//   $view_postsi->bindParam(':bgh', $bgha, PDO::PARAM_STR);
		//   $view_postsi->execute();
		  $numva = count($result);// $view_postsi->rowCount();
		  foreach($result as $postsfetch) 
		  {
		  	$numbera = $postsfetch['number'];
		  }
		}else{
		  $vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$received_name' AND wh='$wh' AND tyi='$bghb'";
		  $result=$this->comman_model->get_all_data_by_query($vpsql);

		//   $view_postsi = $conn->prepare($vpsql);
		//   $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		//   $view_postsi->bindParam(':ty', $received_name, PDO::PARAM_INT);
		//   $view_postsi->bindParam(':id', $wh, PDO::PARAM_INT);
		//   $view_postsi->bindParam(':bgh', $bghb, PDO::PARAM_STR);
		//   $view_postsi->execute();
		  $numva = count($result);// $view_postsi->rowCount();
		  foreach($result as $postsfetch) {
		  $numbera = $postsfetch['number'];
		  }
		}
		
		if($whb == "0"){
		  $vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$received_name' AND wh='$whb' AND tyi='$bgha'";
		  $result=$this->comman_model->get_all_data_by_query($vpsql);
			//   $view_postsi = $conn->prepare($vpsql);
			//   $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
			//   $view_postsi->bindParam(':ty', $received_name, PDO::PARAM_INT);
			//   $view_postsi->bindParam(':id', $whb, PDO::PARAM_INT);
			//   $view_postsi->bindParam(':bgh', $bgha, PDO::PARAM_STR);
			//$view_postsi->execute();
		  $numvb = count($result);// $view_postsi->rowCount();
		  foreach ($result as $postsfetch) {
		  $numberb = $postsfetch['number'];
		  }
		}else{
		  $vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$received_name' AND wh='$whb' AND tyi= '$bghb' ";
		  $result=$this->comman_model->get_all_data_by_query($vpsql);
		//   $view_postsi = $conn->prepare($vpsql);
		//   $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
		//   $view_postsi->bindParam(':ty', $received_name, PDO::PARAM_INT);
		//   $view_postsi->bindParam(':id', $whb, PDO::PARAM_INT);
		//   $view_postsi->bindParam(':bgh', $bghb, PDO::PARAM_STR);
		//   $view_postsi->execute();
		  $numvb = count($result);/// $view_postsi->rowCount();
		  foreach ($result as $postsfetch) 
		  {
		  	$numberb = $postsfetch['number'];
		  }
		}
		$numberca = $numbera-$received;
		$numbercb = $numberb+$received;
		}else{
		//===================select from treasury==========================
		$check = "SELECT * FROM treasury WHERE shop_id =$shop_id AND kind='$received_name' AND wh='$wh'";
		$result=$this->comman_model->get_all_data_by_query($check);
		// $check->bindParam(':chR_aid',$shop_id,PDO::PARAM_INT);
		// $check->bindParam(':received_name',$received_name,PDO::PARAM_INT);
		// $check->bindParam(':wh',$wh,PDO::PARAM_INT);
		// $check->execute();
		foreach ($result as $chR) {
			$numbero = $chR['number'];
		}
		
		//===================calculate the treasury==========================
		$numbery = $numbero-$received;
		}
		//==================delete the wallet =================================
			 $delete_comm_sql = "DELETE FROM capital WHERE id = $c_id ";
			
			 $IsDeleted=$this->comman_model->run_query($delete_comm_sql);
			
			// $delete_comm = $conn->prepare($delete_comm_sql);
			// $delete_comm->bindParam(':c_id',$c_id,PDO::PARAM_INT);
			// $delete_comm->execute();
		if($tyi == "transfar"){
			$iptdbsql = "UPDATE treasury SET number= '$numberca' WHERE shop_id = $shop_id AND kind='$received_name' AND wh='$wh'";
			$IsUpdate=$this->comman_model->run_query($iptdbsql);
			// $insert_post_toDB = $conn->prepare($iptdbsql);
			// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':numbercalv', $numberca,PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':id', $wh, PDO::PARAM_INT);
			// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
			// $insert_post_toDB->execute();
		
			$iptdbsql = "UPDATE treasury SET number='$numbercb' WHERE shop_id = $shop_id AND kind='$received_name' AND wh='$whb'";
			$IsUpdate=$this->comman_model->run_query($iptdbsql);
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbercalv', $numbercb,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':id', $whb, PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
		
		}else{
		//===================update your treasury=========================
			$iptdbsql = "UPDATE treasury SET number='$numbery' WHERE shop_id = $shop_id AND kind='$received_name' AND wh='$wh'";
			$IsUpdate=$this->comman_model->run_query($iptdbsql);
		// $insert_post_toDB = $conn->prepare($iptdbsql);
		// $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':numbery', $numbery,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
		// $insert_post_toDB->bindParam(':wh', $wh,PDO::PARAM_INT);
		// $insert_post_toDB->execute();
		}
		echo "yes";
		
	}
}
