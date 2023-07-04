<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finel_reports extends CI_Controller {

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

        // if (isset($_POST['for'])) {
        //     $data=$this->UpdateData($_POST);            
        // }
        if (isset($_POST['submi_up'])) 
        {
            $this->UpdateSumbitUp();
        }

        if(isset($_POST['but_search']))
        { 
            $data=$this->FilterData($data);
        }
        
        $data=$this->BindData($data);
        //}	
        $data["active"] = "finel_reports";
       //$//data[]=$test;
		$this->load->view('Finel_reports/index', $data);
    }

	

    public function UpdateSumbitUp(){
            $sid =  $_SESSION['id'];
            $shopo =  $_SESSION['shop_id'];
            $typo =  $_SESSION['type'];
            if($typo == "admin"){
                $fetchUsers_sql = "SELECT id FROM signup WHERE shop_id='$shopo'";
            }else{
                $fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$sid' OR id='$sid'";        
            }
            while ($rows = $fetchUsers->fetch(PDO::FETCH_ASSOC)) {
            $gfid = $rows['id'];
            $billclo = filter_var(htmlspecialchars($_POST['billclo']),FILTER_SANITIZE_STRING);
            $sid = $_SESSION['id'];
            $shop_id = $_SESSION['shop_id'];
            $boss_id = $_SESSION['boss_id'];
            $type= "cash";
        $update_info_sql = "UPDATE transactions SET bill= :billclo WHERE user_id= :sid AND type=:type";
        $update_info = $conn->prepare($update_info_sql);
        $update_info->bindParam(':billclo',$billclo,PDO::PARAM_STR);
        $update_info->bindParam(':type',$type,PDO::PARAM_STR);
        $update_info->bindParam(':sid',$gfid,PDO::PARAM_STR);
        $update_info->execute();
        }
    }


    public function BindData($data){
        $sid =  $_SESSION['id'];
        $shopo =  $_SESSION['shop_id'];
        $typo =  $_SESSION['type'];     

        $shop_id =  $_SESSION['shop_id'];
        $boss_id =  $_SESSION['boss_id'];
        
		
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
               
                $invest = "invest";
               if(!empty($fromDate) && !empty($endDate)){
                  $g = " and datepost
                               between '".$fromDate."' and '".$endDate."'";
               }
                  $emp_query .= "$g";
             }
             // Sort
             $emp_query .= " and hide ='0' and (type!='invest' or type!='أستثمار') and user_id='".$gfid."' ORDER BY datepost DESC";
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

                $vpsql = "SELECT Username FROM signup WHERE id=$gfid";
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                $data["fetchedUsernames"][$gfid]=$FetchedData;
             }
             $cgadg=0;
             if($name != ""){
                $vpsql = "SELECT post_id FROM cos_transactions WHERE name=$name";
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                $data["FetchCos_trans"][$name]=$FetchedData;
                //$rowcount = $view_postsb->rowCount();
                foreach($FetchedData as $postsfetchb) 
                {
                    $chak_id = $postsfetchb['post_id'];
                    if($chak_id == $chak_idc)
                    {
                        $cgadg = $chak_id;
                    }
                }
            }
            if($cgadg == $chak_idc){
                $vpsql = "SELECT * FROM transactions WHERE chak_id=$cgadg";
                $data["Fetchtransactions"][$cgadg]=$FetchedData;
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
		

      





        return $data;



    }



}
