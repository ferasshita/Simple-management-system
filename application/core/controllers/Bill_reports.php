<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bill_reports extends CI_Controller {

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

        if ($_SESSION['chak'] == '1') {
            header("location: Dashboard");
        }
        
        $mode=LoadMode();
        $data["dircheckPath"]= base_url()."Asset/";
        $data["layoutmode"]  =   $mode;
        $data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
		////////////////////////////////////////////////////
        
        if (isset($_POST['for'])) {
            $data=$this->UpdateData($_POST);            
        }
        // if (isset($_POST['submi_up'])) 
        // {
        //     $this->UpdateSumbitUp();
        // }

        if(isset($_POST['fromDate']))
        { 
            $data=$this->FilterData($data);
        }
        
        $data=$this->BindData($data);
        //}	
        $data["active"] = "bill";
        
        $data["tc"] = filter_var(htmlentities($_GET['tc']),FILTER_SANITIZE_STRING);
       //$//data[]=$test;
		$this->load->view('Bill_reports/index', $data);
    }

	

    public function UpdateData($data){
            $_POST=$data;                  
            $sid=$_SESSION['id'];  
            $exchange = filter_var(htmlentities($_POST['exchange']),FILTER_SANITIZE_STRING);
            $amousel = filter_var(htmlentities($_POST['amousel']),FILTER_SANITIZE_STRING);
            $received = filter_var(htmlentities($_POST['received']),FILTER_SANITIZE_STRING);
            $amouse = filter_var(htmlentities($_POST['amouse']),FILTER_SANITIZE_STRING);
            $amountsd = filter_var(htmlentities($_POST['amountsd']),FILTER_SANITIZE_STRING);

            $bankname = filter_var(htmlentities($_POST['bankname']),FILTER_SANITIZE_STRING);
            $bankacc = filter_var(htmlentities($_POST['bankacc']),FILTER_SANITIZE_STRING);
            $email = filter_var(htmlentities($_POST['email']),FILTER_SANITIZE_STRING);
            $phone = filter_var(htmlentities($_POST['phone']),FILTER_SANITIZE_STRING);
            $address = filter_var(htmlentities($_POST['address']),FILTER_SANITIZE_STRING);
            $name = filter_var(htmlentities($_POST['name']),FILTER_SANITIZE_STRING);
            $c_id = filter_var(htmlentities($_POST['c_id']),FILTER_SANITIZE_STRING);
            $pid = filter_var(htmlentities($_POST['pid']),FILTER_SANITIZE_STRING);
            $selbu = lang('buy');

            $vpsql = "SELECT * FROM transactions WHERE post_id=$pid";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            $numvf = count($FetchedData);//$view_posts->rowCount();
            foreach($FetchedData as $postsfetch) 
            {
                $user_id = $postsfetch['user_id'];
                $receivedy = $postsfetch['received'];
                $giveny = $postsfetch['given'];
            }
            $numbj = $received-$receivedy;
            $numbg = $amountsd-$giveny;
            $vpsql = "SELECT * FROM signup WHERE id=$user_id";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            $numvf = count($FetchedData);///$view_posts->rowCount();
            foreach($FetchedData as $postsfetch) {
                $shop_id = $postsfetch['shop_id'];
                $boss_id = $postsfetch['boss_id'];
            }
            $vpsql = "SELECT * FROM treasury WHERE shop_id=$sid AND kind='$received_name'";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            $numvf = count($FetchedData);// $view_posts->rowCount();
            foreach($FetchedData as $postsfetch) 
            {
                $numberya = $postsfetch['number'];
            }
            $numberybt = $numberya+$numbj;
            $vpsql = "SELECT * FROM treasury WHERE shop_id=$sid AND kind='$given_name'";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            // $view_posts = $conn->prepare($vpsql);
            // $view_posts->bindParam(':p_user_id', $sid, PDO::PARAM_INT);
            // $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
            // $view_posts->execute();
            $numvh = count($FetchedData); // $view_posts->rowCount();
            foreach($FetchedData as $postsfetch) {
                $numberyb = $postsfetch['number'];
            }
            $numberd = $numberyb+$numbg;
            $edit_post_sql = "UPDATE cos_transactions SET name= '$name',address = '$address',phone='$phone',email = '$email',bankacc ='$bankacc',bankname='$bankname' WHERE post_id= $c_id";
            $IsUpdate=$this->comman_model->run_query($edit_post_sql);
            // $edit_post = $conn->prepare($edit_post_sql);
            // $edit_post->bindParam(':name',$name,PDO::PARAM_STR);
            // $edit_post->bindParam(':address',$address,PDO::PARAM_STR);
            // $edit_post->bindParam(':phone',$phone,PDO::PARAM_INT);
            // $edit_post->bindParam(':email',$email,PDO::PARAM_INT);
            // $edit_post->bindParam(':bankacc',$bankacc,PDO::PARAM_INT);
            // $edit_post->bindParam(':bankname',$bankname,PDO::PARAM_INT);
            // $edit_post->bindParam(':c_id',$c_id,PDO::PARAM_INT);
            // $edit_post->execute();
            $edit_post_sql = "UPDATE transactions SET exchange= '$exchange',received = '$received',given='$amountsd',received_name = '$amousel',given_name ='$amouse',kin='$selbu' WHERE post_id= $pid";
            $IsUpdate=$this->comman_model->run_query($edit_post_sql);
            // $edit_post = $conn->prepare($edit_post_sql);
            // $edit_post->bindParam(':exchange',$exchange,PDO::PARAM_STR);
            // $edit_post->bindParam(':amousel',$amousel,PDO::PARAM_STR);
            // $edit_post->bindParam(':received',$received,PDO::PARAM_INT);
            // $edit_post->bindParam(':amouse',$amouse,PDO::PARAM_INT);
            // $edit_post->bindParam(':amountsd',$amountsd,PDO::PARAM_INT);
            // $edit_post->bindParam(':selbu',$selbu,PDO::PARAM_INT);
            // $edit_post->bindParam(':pid',$pid,PDO::PARAM_INT);
            // $edit_post->execute();

            $iptdbsql = "UPDATE treasury SET number='$numberybt' WHERE shop_id = $sid AND kind='$received_name' ";
            $IsUpdate=$this->comman_model->run_query($iptdbsql);
            // $insert_post_toDB = $conn->prepare($iptdbsql);
            // $insert_post_toDB->bindParam(':p_user_id', $sid,PDO::PARAM_INT);
            // $insert_post_toDB->bindParam(':numbero', $numberybt,PDO::PARAM_INT);
            // $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
            // $insert_post_toDB->execute();

            //=====================insert the money of given==================================
            $iptdbsql = "UPDATE treasury SET number='$numberd' WHERE shop_id = $sid AND kind='$given_name' ";
            $IsUpdate=$this->comman_model->run_query($iptdbsql);
            // $insert_post_toDB = $conn->prepare($iptdbsql);
            // $insert_post_toDB->bindParam(':p_user_id', $sid,PDO::PARAM_INT);
            // $insert_post_toDB->bindParam(':numberb', $numberd,PDO::PARAM_INT);
            // $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
            // $insert_post_toDB->execute();
    }

    public function BindData($data)
    {
        $sid =  $_SESSION['id'];
        $delo = "0";
        $sid =  $_SESSION['id'];
        $shopo =  $_SESSION['shop_id'];
        $typo =  $_SESSION['type'];
        $shop_id =  $_SESSION['shop_id'];
        $boss_id =  $_SESSION['boss_id'];
        
        
        if($typo == "admin"){
            $fetchUsers_sql = "SELECT DISTINCT id FROM signup WHERE shop_id='$shopo'";
        }else{
            $fetchUsers_sql = "SELECT DISTINCT id FROM signup WHERE boss_id='$sid' OR id='$sid'";
        }
        $FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
        $data["singupids"]=$FetchedData;
        $bill_id = filter_var(htmlentities($_GET['pid']), FILTER_SANITIZE_NUMBER_INT);
        $array2=array();
        foreach($FetchedData as $item){
            $gfid = $item['id'];
            $vpsql = "SELECT * FROM transactions WHERE bill = '$bill_id' AND user_id=$gfid AND hide='0'";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $postsfetch){
                $id = $postsfetch['id'];
                $post_id = $postsfetch['post_id'];
                $chak_id = $postsfetch['chak_id'];
                $user_id = $postsfetch['user_id'];
                $chak_id = $postsfetch['chak_id'];
                $exchange = $postsfetch['exchange'];
                $received_name = $postsfetch['received_name'];
                $received = $postsfetch['received'];
                $given_name = $postsfetch['given_name'];
                $kind = $postsfetch['kin'];
                $amountsd = $postsfetch['given'];
                $time = $postsfetch['time'];
                $type = $postsfetch['type'];
                $media = $postsfetch['media'];
                $array2["transactions"][$gfid][]=$postsfetch;

                $vpsql = "SELECT * FROM cos_transactions WHERE post_id=$chak_id AND hide='$delo'";
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                foreach($FetchedData as $item){
                    $array2["costransactions"][$chak_id][]=$postsfetch;
                    $user_id = $item['user_id'];
                }
                
                $vpsql = "SELECT Username FROM signup WHERE id=$user_id";
            
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                $array2["usernames"][$user_id]=$FetchedData;
            }
        }
        $data["Pagedata2"]=$array2;



        if($typo == "admin"){
            $fetchUsers_sql = "SELECT id FROM signup WHERE shop_id='$shopo'";
        }else{
            $fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$sid' OR id='$sid'";
        }
        $FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
        $data["singups"]=$FetchedData;
        $array=array();
        foreach($FetchedData as $items)
        {
            $gfid = $items['id'];
            $vpsql = "SELECT DISTINCT bill FROM transactions WHERE user_id=$gfid AND hide='$delo' ORDER BY datepost DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $items)
            {
              $array["bills"][$gfid][]=$items;
            }
        }

        $data["Pagedata"]=$array;
   		$typem =  $_SESSION['type'];
          if($typem == "boss"){
              $gfid = $boss_id;
          }elseif($typem == "admin"){
              $gfid = $shop_id;
          }else{
              $gfid = $shop_id;
          }
          $delk = "0";
          $bgh = "cash";

		 $vpsql = "SELECT * FROM treasury WHERE (kind='LYD' OR kind='USD') AND (tyi='$bgh' AND shop_id=$gfid OR tyi='$bgh' AND boss_id=$gfid)";
       
        $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasury"]=$FetchedData;
        return $data;

    }

	public function FilterData($data){
        $type = "chak";
        $sid =  $_SESSION['id'];
        $shopo =  $_SESSION['shop_id'];
        $typo =  $_SESSION['type'];
       
        $shop_id =  $_SESSION['shop_id'];
        $boss_id = $_SESSION['boss_id'];    
           
		if($typo == "admin"){
			$fetchUsers_sql = "SELECT id FROM signup WHERE shop_id='$shopo'";
           
		}else{
			$fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$sid' OR id='$sid'";
		}
        $fromDate = $_POST['fromDate'];
        $endDate = $_POST['endDate'];
		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$data["signups"]=$FetchedData;
		foreach($FetchedData as $item)
		{
			$gfid = $item['id'];
            
            $emp_query = "SELECT * FROM transactions WHERE 1";

            // Date filter
            if(isset($_POST['but_search'])){
               $fromDate = $_POST['fromDate'];

               if(!empty($fromDate)){
                  $emp_query .= " and bill='".$fromDate."' and user_id = '".$gfid."' and hide ='0' ";
               }
             }

             // Sort
             $emp_query .= " ORDER BY datepost DESC";

             $FetchedData=$this->comman_model->get_all_data_by_query($emp_query);
             
             $data["FetchedResult"]=$FetchedData;
             foreach($FetchedData as $postsfetch)
             {
                $bill_id = $postsfetch['bill'];

                

                // $vpsql = "SELECT Username FROM signup WHERE id=$gfid";
                // $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                // $data["fetchedUsernames"][$gfid]=$FetchedData;
             }
             $vpsql = "SELECT * FROM transactions WHERE bill=$bill_id";
             $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
             $data["billtransactions"][$bill_id]=$FetchedData;
             foreach($FetchedData as $item)
             {
                    $id = $item['id'];
                    $post_id = $item['post_id'];
                    $chak_id = $item['chak_id'];
                    $user_id = $item['user_id'];
                    $chak_id = $item['chak_id'];
                    $exchange = $item['exchange'];
                    $received_name = $item['received_name'];
                    $received = $item['received'];
                    $given_name = $item['given_name'];
                    $kind = $item['kin'];
                    $amountsd = $item['given'];
                    $time = $item['time'];
                    $type = $item['type'];
                    $media = $item['media'];
                    $delo="0";
                    $vpsql = "SELECT * FROM cos_transactions WHERE post_id=$chak_id AND hide=$delo ";
                    $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                    $data["billcostransactions"][$chak_id]=$FetchedData;
                    foreach($FetchedData as $items)
                    {
                        $user_id = $items['user_id'];
                    }
                    $vpsql = "SELECT Username FROM signup WHERE id=$user_id";
                    $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                    $data["filterusernames"][$user_id]=$FetchedData;
             }
		}
        $sid =  $_SESSION['id'];
        $typey = "head";
        $ghy = "LYD";
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

        $vpsql = "SELECT * FROM expenses WHERE shop_id=$gfid AND datepost BETWEEN '$fromDate' AND '$endDate' OR boss_id=$gfid AND datepost BETWEEN '$fromDate' AND '$endDate' ORDER BY time DESC";
        $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
        $data["filterExpenses"] =$FetchedData;
        foreach ($FetchedData as $postsfetch) 
        {
            $id = $postsfetch['id'];
            $shop_id = $postsfetch['shop_id'];
            $user_id = $postsfetch['user_id'];
            $number = $postsfetch['number'];
            $kind = $postsfetch['type'];
            $ex = $postsfetch['ex'];
            $calc = $postsfetch['calc'];
            $note = $postsfetch['note'];
            $notey = $postsfetch['notey'];
            $time = $postsfetch['time'];
            $ytz = $postsfetch['yt'];

            $vpsql = "SELECT Username FROM signup WHERE id=$user_id";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            $data["filterusernames"][$user_id]=$FetchedData;

            if($ytz != "0")
            {
              $vpsql = "SELECT bank_name,country FROM my_bank WHERE id=$ytz";
              $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
              $data["FilterCountries"][$ytz]=$FetchedData;
              
            }

            $vpsql = "SELECT Username FROM signup WHERE id=$shop_id";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            $data["shopusernames"][$shop_id]=$FetchedData;

        }

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
        
        $typey = "head";
        $ghy = "LYD";


        $vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE type='$typey'  AND shop_id = $gfid OR type='$typey'  AND boss_id=$gfid";
        $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
        $data["tyuydata"]=$FetchedData;
        
        $vpsql = "SELECT SUM(number) AS ty_uy FROM capital WHERE kind='$ghy' AND type='$typey'  AND shop_id=$gfid OR kind='$ghy' AND type= '$typey'  AND boss_id=$gfid";
        $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
        $data["tyubdata"]=$FetchedData;


        $vpsql = "SELECT * FROM capital WHERE type='$typey'  AND shop_id=$gfid OR type='$typey'  AND boss_id=$gfid  ORDER BY time DESC";
        $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
        $data["filtercapitals"]=$FetchedData;
        //print_r($FetchedData);exit;
        foreach ($FetchedData as $postsfetch) {
            $wh = $postsfetch['wh'];
            
            
            if($wh != 0){
                //echo $wh;
                $vpsqlus = "SELECT * FROM my_bank WHERE id=$wh";
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsqlus);
                
                
                $data["mybanks"]=$FetchedData;

            }
            
            if($whb != 0){
                
                $vpsqlu = "SELECT * FROM my_bank WHERE id=$whb";
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsqlu);
                print_r($FetchedData);
                $data["mybwhbanks"]=$FetchedData;
            }
        }
        
       

   		$typem =  $_SESSION['type'];
          if($typem == "boss"){
              $gfid = $boss_id;
          }elseif($typem == "admin"){
              $gfid = $shop_id;
          }else{
              $gfid = $shop_id;
          }
          $delk = "0";
          $bgh = "cash";

		$vpsql = "SELECT * FROM treasury WHERE (kind='LYD' OR kind='USD') AND (tyi='$bgh' AND shop_id=$gfid OR tyi='$bgh' AND boss_id=$gfid)";
        $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasury"]=$FetchedData;

       
        //============total of the money=============================
        $vpsql = "SELECT * FROM treasury WHERE tyi='$bgh' AND shop_id=$gfid OR tyi='$bgh' AND boss_id=$gfid";
        $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasry"]=$FetchedData;


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

			$array["bank"] = $postsfetch;
			$array["bank"][$idn] = $transdata;
		}
		
		$data["bank"]=$array;





        return $data;



    }



}
