<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detailed_reports extends CI_Controller {

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


        if ($_SESSION['bonds'] == '1') {
            header("location: Dashboard");
        }

        $mode=LoadMode();
        $data["dircheckPath"]= base_url()."Asset/";
        $data["layoutmode"]  =   $mode;
        $data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
		////////////////////////////////////////////////////

        // if (isset($_POST['for'])) {
        //     $data=$this->UpdateData($_POST);            
        // }

        if(isset($_POST['but_search']))
        { 
            $data=$this->FilterData($data);
        }
            $data=$this->BindData($data);
        //}	
        $data["active"] = "Detailed_reports";
       //$//data[]=$test;
		$this->load->view('Detailed_reports/index', $data);
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

        // $view_posts = $conn->prepare($vpsql);
        // $view_posts->bindParam(':pid', $pid, PDO::PARAM_INT);
        // $view_posts->execute();
        // $numvf = $view_posts->rowCount();
        
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);	
        foreach($FetchedData as $postsfetch ) 
        {
            $user_id = $postsfetch['user_id'];
            $receivedy = $postsfetch['received'];
            $giveny = $postsfetch['given'];
        }
        $numbj = $received-$receivedy;
        $numbg = $amountsd-$giveny;
        $vpsql = "SELECT * FROM signup WHERE id=$user_id";
        $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
        // $view_posts = $conn->prepare($vpsql);
        // $view_posts->bindParam(':pid', $user_id, PDO::PARAM_INT);
        // $view_posts->execute();
        $numvf = count($FetchedData);//$view_posts->rowCount();
        foreach ($FetchedData as $postsfetch) 
        {
            $shop_id = $postsfetch['shop_id'];
            $boss_id = $postsfetch['boss_id'];
        }
        $vpsql = "SELECT * FROM treasury WHERE shop_id=$sid AND kind='$received_name'";
        $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
        // $view_posts = $conn->prepare($vpsql);
        // $view_posts->bindParam(':p_user_id', $sid, PDO::PARAM_INT);
        // $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
        // $view_posts->execute();
        $numvf = count($FetchedData);// $view_posts->rowCount();
        foreach($FetchedData as $postsfetch) {
            $numberya = $postsfetch['number'];
        }
        $numberybt = $numberya+$numbj;
        $vpsql = "SELECT * FROM treasury WHERE shop_id=$sid AND kind='$given_name'";
        // $view_posts = $conn->prepare($vpsql);
        // $view_posts->bindParam(':p_user_id', $sid, PDO::PARAM_INT);
        // $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
        // $view_posts->execute();
        $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
        $numvh = count($FetchedData); //  $view_posts->rowCount();
        foreach($FetchedData as $postsfetch) 
        {
            $numberyb = $postsfetch['number'];
        }
        $numberd = $numberyb+$numbg;
        $edit_post_sql = "UPDATE cos_transactions SET name= '$name',address = '$address',phone='$phone',email = '$email',bankacc ='$bankacc',bankname= '$bankname' WHERE post_id= $c_id";
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

        $iptdbsql = "UPDATE treasury SET number='$numberybt' WHERE shop_id = $sid AND kind='$received_name'";
        $IsUpdate=$this->comman_model->run_query($iptdbsql);

        // $insert_post_toDB = $conn->prepare($iptdbsql);
        // $insert_post_toDB->bindParam(':p_user_id', $sid,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':numbero', $numberybt,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
        // $insert_post_toDB->execute();

        //=====================insert the money of given==================================

        $iptdbsql = "UPDATE treasury SET number='$numberd' WHERE shop_id = $sid AND kind = '$given_name'";
        $IsUpdate=$this->comman_model->run_query($iptdbsql);

        // $insert_post_toDB = $conn->prepare($iptdbsql);
        // $insert_post_toDB->bindParam(':p_user_id', $sid,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':numberb', $numberd,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
        // $insert_post_toDB->execute();

    }


    public function BindData($data){
        $sid =  $_SESSION['id'];
        $shopo =  $_SESSION['shop_id'];
        $typo =  $_SESSION['type'];     

        $shop_id =  $_SESSION['shop_id'];
        $boss_id =  $_SESSION['boss_id'];
        
		if($typo == "admin"){
			$fetchUsers_sql = "SELECT id FROM signup WHERE shop_id='$shopo'";
		}else{
			$fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$sid' OR id='$sid'";
		}

		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$data["signups"]=$FetchedData;
		foreach($FetchedData as $item)
		{
			$gfid = $item['id'];
            $vpsql = "SELECT DISTINCT cos_id FROM cos_transactions WHERE user_id=$gfid";
            //$vpsql = "SELECT * FROM cos_transactions WHERE user_id=$gfid AND hide='$delo' AND type='$type' ORDER BY datepost DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $postsfetch){
                $array["allCosTransactions"][$gfid][]=$postsfetch;
				$post_idy = $postsfetch['post_id'];
				$cos_id = $postsfetch['cos_id'];
				$user_id = $postsfetch['user_id'];



			
                $vpsql = "SELECT name FROM costumers WHERE user_id=$gfid AND main_id=$cos_id AND name !='casher'";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				foreach($FetchedData as $item){
					$array["allCostumers"][$cos_id][]=$item;
				}
               

            }
		}
        //print_r($array["usernames"][312433213849836]);exit;
		$data["Pagedata"]=$array;

        if($_SESSION['type'] == "admin"){
            $fetchUsers_sql = "SELECT Username FROM signup WHERE shop_id='$shop_id'";
        }else{
            $fetchUsers_sql = "SELECT Username FROM signup WHERE boss_id='$ido'";
        }
        $FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
        $data["usernames"]=$FetchedData;

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

		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$data["signups2"]=$FetchedData;
		foreach($FetchedData as $item)
		{
			$gfid = $item['id'];

            $emp_query = "SELECT * FROM transactions WHERE 1";

            // Date filter
            if(isset($_POST['but_search'])){
               $fromDate = $_POST['fromDate'];
               $endDate = $_POST['endDate'];
               $user = $_POST['usersd'];
               $name = $_POST['name'];
               $kind = $_POST['kind'];
               $taken = $_POST['taken'];
               $given = $_POST['given'];
               $transaction_cancel = $_POST['transaction_cancel'];
               $invest = "invest";
               $vpsql = "SELECT id FROM signup WHERE Username='$user'";
               $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
               
               foreach($FetchedData as $postsfetchb) 
               {
                    $userid = $postsfetchb['id'];
               }
               if(!empty($user)){
                  $a = " and user_id = '".$userid."'";
               }else{
                 $a = " and user_id = '".$gfid."'";
                }
               if($kind == "all"){
                 $z = "";
               }elseif(!empty($kind)){
                 $z = " and type = '".$kind."'";
              }
               if($transaction_cancel != "1"){
                  $d = " and received_name = '".$taken."'";
               }else{
                  $d = "";
                }
               if($transaction_cancel != "1"){
                  $e = " and given_name = '".$given."'";
               }else{
                  $e = "";
                }
               if(!empty($fromDate) && !empty($endDate)){
                  $g = " and datepost
                               between '".$fromDate."' and '".$endDate."'";
               }
                  $emp_query .= "$g $a $f $d $e $z $b";
             }
             // Sort
             $emp_query .= " and hide ='0' and type!='".$invest."' and user_id='".$gfid."' ORDER BY datepost DESC";
             $FetchedData=$this->comman_model->get_all_data_by_query($emp_query);
             $data["FetchedResult"]=$FetchedData;
             foreach($FetchedData as $postsfetch)
             {
                $id = $postsfetch['id'];
                $chak_idc = $postsfetch['chak_id'];
                $post_id = $postsfetch['post_id'];
                $user_id = $postsfetch['user_id'];
                $exchange = $postsfetch['exchange'];
                $received_name = $postsfetch['received_name'];
                $received = $postsfetch['received'];
                $given_name = $postsfetch['given_name'];
                $kind = $postsfetch['type'];
                $amountsd = $postsfetch['given'];
                $time = $postsfetch['time'];
                $typec = $postsfetch['kin'];
                $media = $postsfetch['media'];

                $vpsql = "SELECT Username FROM signup WHERE id=$user_id";
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                $data["fetchedUsernames"][$user_id]=$FetchedData;
             }

             if($name != ""){
                $vpsql = "SELECT * FROM costumers WHERE name='$name'";
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                $data["fetchedcostumers"][$name]=$FetchedData;
                $cgadg=0;
                foreach($FetchedData as $item)
                {
                    $cos_id = $item['main_id'];
                    $vpsql = "SELECT post_id FROM cos_transactions WHERE cos_id=$cos_id";
                    $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                    $data["post_ids"][$cos_id]=$FetchedData;
                    
                    foreach($FetchedData as $postsfetchb) 
                    {
                        $chak_id = $postsfetchb['post_id'];
                        //echo $chak_idc;exit;
                        if($chak_id == $chak_idc){
                            $cgadg = $chak_id;
                        }
                    }
                }
                if($cgadg == $chak_idc){
                    $vpsql = "SELECT * FROM transactions WHERE chak_id=$cgadg";
                    $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                    $data["Fetchedtransactions"][$cgadg]=$FetchedData;
                }
             }
             $invest = "invest";
             $delo = "0";
             $vpsql = "SELECT * FROM transactions WHERE user_id=$gfid AND hide='$delo' AND type!='$invest'";
             $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
             $data["fetchedInvestTransactions"][$gfid]=$FetchedData;
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



}
