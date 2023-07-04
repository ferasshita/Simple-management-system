<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Embloyees_reports extends CI_Controller {

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
				array('langs', 'IsLogedin', 'Paymust','timefunction','Mode','User','Currencynodes','timefunction','numkmcount')
		);
			$this->load->model('comman_model');
			$this->load->model('Home_model');
			LoadLang();
			//LoadLang();
			// Your own constructor code
            
			Checklogin(base_url());
			Pay_must();
            error_reporting(0);
			
	}



    public function Index()
    {
      if($_SESSION['user_email_status'] == "not verified"){
            header("location:email_verification");
        }
        if($_SESSION['steps'] != "1"){
            header('location:steps?tc=shop');
        }


        if ($_SESSION['cards'] == '1') {
            header("location: home");
        }

        $mode=LoadMode();
        $data["dircheckPath"]= base_url()."Asset/";
        $data["layoutmode"]  =   $mode;
        $data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
		////////////////////////////////////////////////////

       // if (isset($_POST['for'])) {
         //   $data=$this->UpdateData($_POST);            
        //}

        if(isset($_POST['fromDate']))
        { 
            //$data=$this->FilterData($data);
            $arrays=array();
            $sid =  $_SESSION['id'];
            $shopo =  $_SESSION['shop_id'];
            $typo =  $_SESSION['type'];
            if($typo == "admin"){
            $fetchUsers_sql = "SELECT id FROM signup WHERE shop_id='$shopo'";
            }else{
              $fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$sid' OR id='$sid'";
            }
            $FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
            $data["signups"]=$FetchedData;

            foreach($FetchedData as $item){
                $gfid = $item['id'];
            }
            $emp_query = "SELECT * FROM inandout WHERE 1";
            // Date filter
            if(isset($_POST['but_search'])){
               $fromDate = $_POST['fromDate'];
               $vpsql = "SELECT id FROM signup WHERE Username='$fromDate'";
               $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);  
               foreach($FetchedData as $postsfetchb) 
               {
                    $dt = $postsfetchb['id'];
               }
               if(!empty($fromDate)){
                  $emp_query .= " and user_id ='".$dt."'";
               }
             }

             // Sort $arrays
             $emp_query .= " ORDER BY date DESC";
             
             $FetchedData=$this->comman_model->get_all_data_by_query($emp_query);
             $data["records"]=$FetchedData;
             foreach($FetchedData as $item){
                $user_idy = $item['user_id'];
                $vpsql = "SELECT Username FROM signup WHERE id=$user_idy";
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                $data["usernames"][$user_idy]=$FetchedData;
             }

             //$employeesRecords = mysqli_query($con,$emp_query);

        }else{
            $data=$this->BindData($data);
        }	
        $data["active"] = "embarchive";
       //$//data[]=$test;
		$this->load->view('embloyees_reports/index', $data);
    }

	

    // public function UpdateData($data){
    //     $_POST=$data;
    //     $sid=$_SESSION['id'];
    //     $exchange = filter_var(htmlentities($_POST['exchange']),FILTER_SANITIZE_STRING);
    //     $amousel = filter_var(htmlentities($_POST['amousel']),FILTER_SANITIZE_STRING);
    //     $received = filter_var(htmlentities($_POST['received']),FILTER_SANITIZE_STRING);
    //     $amouse = filter_var(htmlentities($_POST['amouse']),FILTER_SANITIZE_STRING);
    //     $amountsd = filter_var(htmlentities($_POST['amountsd']),FILTER_SANITIZE_STRING);
    
    //     $bankname = filter_var(htmlentities($_POST['bankname']),FILTER_SANITIZE_STRING);
    //     $bankacc = filter_var(htmlentities($_POST['bankacc']),FILTER_SANITIZE_STRING);
    //     $email = filter_var(htmlentities($_POST['email']),FILTER_SANITIZE_STRING);
    //     $phone = filter_var(htmlentities($_POST['phone']),FILTER_SANITIZE_STRING);
    //     $address = filter_var(htmlentities($_POST['address']),FILTER_SANITIZE_STRING);
    //     $name = filter_var(htmlentities($_POST['name']),FILTER_SANITIZE_STRING);
    //     $c_id = filter_var(htmlentities($_POST['c_id']),FILTER_SANITIZE_STRING);
    //     $pid = filter_var(htmlentities($_POST['pid']),FILTER_SANITIZE_STRING);
    //     $selbu = lang('buy');
    
    //     $vpsql = "SELECT * FROM transactions WHERE post_id=$pid";
    //     $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
	
	
    //     $numvf = count($FetchedData); // $view_posts->rowCount();
    //     foreach($FetchedData as $postsfetch) {
    //         $user_id = $postsfetch['user_id'];
    //         $receivedy = $postsfetch['received'];
    //         $giveny = $postsfetch['given'];
    //     }

    //     $numbj = $received-$receivedy;
    //     $numbg = $amountsd-$giveny;
    //     $vpsql = "SELECT * FROM signup WHERE id=$user_id";
    //     $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);

    //     $numvf = count($FetchedData);//$view_posts->rowCount();
    //     foreach($FetchedData as $postsfetch) {
    //         $shop_id = $postsfetch['shop_id'];
    //         $boss_id = $postsfetch['boss_id'];
    //     }

    //     $vpsql = "SELECT * FROM treasury WHERE shop_id=$sid AND kind='$received_name'";
    //     $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);

    //     $numvf = count($FetchedData); // $view_posts->rowCount();
    //     foreach($FetchedData as $postsfetch) 
    //     {
    //         $numberya = $postsfetch['number'];
    //     }
    //     $numberybt = $numberya+$numbj;
    //     $vpsql = "SELECT * FROM treasury WHERE shop_id=$sid AND kind='$given_name'";
    //     $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);

    //     $numvh = count($FetchedData);// $view_posts->rowCount();
    //     foreach($FetchedData as $postsfetch) 
    //     {
    //         $numberyb = $postsfetch['number'];
    //     }
    //     $numberd = $numberyb+$numbg;
    //     $edit_post_sql = "UPDATE cos_transactions SET name= '$name',address = '$address',phone='$phone',email = '$email',bankacc ='$bankacc',bankname='$bankname' WHERE post_id= $c_id";
    //     $IsUpdated=$this->comman_model->run_query($edit_post_sql);

    //     $edit_post_sql = "UPDATE transactions SET exchange= '$exchange',received = '$received',given='$amountsd',received_name = '$amousel',given_name ='$amouse',kin='$selbu' WHERE post_id= $pid";
    //     $IsUpdated=$this->comman_model->run_query($edit_post_sql);

    //     // $edit_post = $conn->prepare($edit_post_sql);
    //     // $edit_post->bindParam(':exchange',$exchange,PDO::PARAM_STR);
    //     // $edit_post->bindParam(':amousel',$amousel,PDO::PARAM_STR);
    //     // $edit_post->bindParam(':received',$received,PDO::PARAM_INT);
    //     // $edit_post->bindParam(':amouse',$amouse,PDO::PARAM_INT);
    //     // $edit_post->bindParam(':amountsd',$amountsd,PDO::PARAM_INT);
    //     // $edit_post->bindParam(':selbu',$selbu,PDO::PARAM_INT);
    //     // $edit_post->bindParam(':pid',$pid,PDO::PARAM_INT);
    //     // $edit_post->execute();
    
    //     $iptdbsql = "UPDATE treasury SET number='$numberybt' WHERE shop_id = $sid AND kind= '$received_name'";
    //     $IsUpdated=$this->comman_model->run_query($iptdbsql);
    //     // $insert_post_toDB = $conn->prepare($iptdbsql);
    //     // $insert_post_toDB->bindParam(':p_user_id', $sid,PDO::PARAM_INT);
    //     // $insert_post_toDB->bindParam(':numbero', $numberybt,PDO::PARAM_INT);
    //     // $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
    //     // $insert_post_toDB->execute();
    
    //     //=====================insert the money of given==================================
    
    //     $iptdbsql = "UPDATE treasury SET number='$numberd' WHERE shop_id = $sid AND kind= '$given_name'";
    //     $IsUpdated=$this->comman_model->run_query($iptdbsql);
    //     // $insert_post_toDB = $conn->prepare($iptdbsql);
    //     // $insert_post_toDB->bindParam(':p_user_id', $sid,PDO::PARAM_INT);
    //     // $insert_post_toDB->bindParam(':numberb', $numberd,PDO::PARAM_INT);
    //     // $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
    //     // $insert_post_toDB->execute();

    // }


    public function BindData($data){
        $sid =  $_SESSION['id'];
        $delo = "0";
        $type = "transfar";
        $sid =  $_SESSION['id'];
        $shopo =  $_SESSION['shop_id'];
        $shop_id =  $_SESSION['shop_id'];
        $boss_id = $_SESSION['boss_id'];
        $typo =  $_SESSION['type'];

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
            $vpsql = "SELECT * FROM inandout WHERE user_id=$gfid ORDER BY date DESC";          
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);            
            foreach($FetchedData as $postsfetch){
                $array["allCosTransactions"][$gfid][]=$postsfetch;
				$id = $postsfetch['id'];
                $date = $postsfetch['date'];
                $type = $postsfetch['type'];
                $user_idy = $postsfetch['user_id'];

				$vpsql = "SELECT Username FROM signup WHERE id='$user_idy'";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				foreach($FetchedData as $item){
					$array["usernames"][$user_idy][]=$item;
                    $usernamef = $item['Username'];
				}
                
				

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
		$type = "transfar";
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
        $array=array();
        echo $fetchUsers_sql;
		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$data["signups"]=$FetchedData;
        echo count($FetchedData);exit;
		foreach($FetchedData as $item)
		{
			$gfid = $item['id'];

            $emp_query = "SELECT * FROM inandout WHERE 1";

             // // Date filter
            if(isset($_POST['but_search'])){
                
              $fromDate = $_POST['fromDate'];
              
              $vpsql = "SELECT id FROM signup WHERE Username='$fromDate'";
              
              $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
              //count($FetchedData);
              
              foreach($FetchedData as $postsfetch){				
                $dt = $postsfetch['id'];
              }
              
              if(!empty($fromDate)){
                  //echo $dt; 
                 $emp_query .= " and user_id ='".$dt."'";
                 //echo $emp_query;exit;
              }
            }

            // Sort
            $emp_query .= " ORDER BY date DESC";
           // echo $emp_query;exit;
			// Sort
            // if($dt == null)
            // {
            //     $FetchedData=0;
            // }
            // else{
                $FetchedData=$this->comman_model->get_all_data_by_query($emp_query);
            //}
			
            
            //echo count($FetchedData);exit;
                $i=1;
			foreach($FetchedData as $postsfetch){
				$array["searchdata2"][]=$postsfetch;
                //echo "1";
                 echo "<pre>";
                 echo $i;
                 //print_r($postsfetch);
                 $i++;
                $id = $postsfetch['id'];
                $date = $postsfetch['date'];
                $type = $postsfetch['type'];
                $user_idy = $postsfetch['user_id'];
				$vpsql = "SELECT Username FROM signup WHERE id=$user_idy";
				$FetchedData2=$this->comman_model->get_all_data_by_query($vpsql);
                //print_r($FetchedData2);exit;


				foreach($FetchedData2 as $item){
					$array["filtertransactions"][$user_idy][]=$item;
				//	$usernamef = $item['Username'];
					
				}
				
                

				
			}

           // exit;


           
		}
        // echo "<pre>";
         print_r($array["searchdata2"]);exit;
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
