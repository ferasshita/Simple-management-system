<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

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
        if (isset($_POST['for'])) {
            $data=$this->UpdateData($_POST);            
        }
        $mode=LoadMode();
        $data["dircheckPath"]= base_url()."Asset/";
        $data["layoutmode"]  =   $mode;
        $data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
		////////////////////////////////////////////////////
       
        $data["active"] = "reports";

        if(isset($_POST['fromDate']))
        { 
            $data=$this->FilterData($data);
        }else{
            $data=$this->WithoutFilter($data);
        }	
        
       //$//data[]=$test;

        $data["active"]="reports";
		$this->load->view('Reports/index', $data);
    }

    public function UpdateData($data){
            $_POST=$data;
        
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
            foreach ($FetchedData as $postsfetch){
                $user_id = $postsfetch['user_id'];
                $receivedy = $postsfetch['received'];
                $giveny = $postsfetch['given'];
            }
            $numbj = $received-$receivedy;
            $numbg = $giveny-$amountsd;

            $vpsql = "SELECT * FROM signup WHERE id=$user_id";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            // $view_posts = $conn->prepare($vpsql);
            // $view_posts->bindParam(':pid', $user_id, PDO::PARAM_INT);
            // $view_posts->execute();
            $numvf = count($FetchedData); // $view_posts->rowCount();
            foreach($FetchedData as $postsfetch) 
            {
                $shop_id = $postsfetch['shop_id'];
                $boss_id = $postsfetch['boss_id'];
            }
            $vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$received_name'";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            // $view_posts = $conn->prepare($vpsql);
            // $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
            // $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
            // $view_posts->execute();
            $numvf = count($FetchedData); // $view_posts->rowCount();
            foreach ($FetchedData as $postsfetch ){
                $numberya = $postsfetch['number'];
            }
            $numberybt = $numberya+$numbj;
            $vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$given_name'";
            // $view_posts = $conn->prepare($vpsql);
            // $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
            // $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
            // $view_posts->execute();
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);

            $numvh = count($FetchedData); //$view_posts->rowCount();
            foreach($FetchedData as $postsfetch) {
                $numberyb = $postsfetch['number'];
            }

            $numberd = $numberyb-$numbg;

            $edit_post_sql = "UPDATE transactions SET exchange= '$exchange',received = '$received',given='$amountsd',received_name = '$received_name',given_name ='$given_name',kin='$selbu' WHERE post_id= $pid";
            $IsUpdated=$this->comman_model->run_query($edit_post_sql);
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
            $IsUpdated=$this->comman_model->run_query($edit_post_sql);
            // $insert_post_toDB = $conn->prepare($iptdbsql);
            // $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
            // $insert_post_toDB->bindParam(':numbero', $numberybt,PDO::PARAM_INT);
            // $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
            // $insert_post_toDB->execute();

            //=====================insert the money of given==================================

            $iptdbsql = "UPDATE treasury SET number='$numberd' WHERE shop_id = $shop_id AND kind='$given_name'";
            $insert_post_toDB = $conn->prepare($iptdbsql);
            // $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
            // $insert_post_toDB->bindParam(':numberb', $numberd,PDO::PARAM_INT);
            // $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
            // $insert_post_toDB->execute();
    }

    public function FilterData($data){
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
    
        $bgh = "cash";

		$vpsql = "SELECT * FROM treasury WHERE (kind='LYD' OR kind='USD') AND (tyi= '$bgh' AND shop_id=$gfid OR tyi= '$bgh' AND boss_id= $gfid)";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasury"]=$FetchedData;



        $logh = $_SESSION['language'];
        $kindoi2 = "";
		if($logh == "english"){
			$kindoi = "sell";
            $kindoi2 = "buy";
		}else{
			$kindoi = "شراء";
            $kindoi2 = "بيع";
		}
        $delo="0";
		$invest = lang('invest');
		//=================start of the archive=======================
		$sid =  $_SESSION['id'];
		$shopo =  $_SESSION['shop_id'];
		$typo =  $_SESSION['type'];
		$fetchUsers_sql="";
		if($typo == "admin"){
			$fetchUsers_sql = "SELECT id FROM signup WHERE shop_id='$shopo'";	
            
		}else{
			$fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$sid' OR id='$sid'";			
		}       
        

		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$data["signups"]=$FetchedData;
        
        $yy = date('Y');
        $ryt = "$yy-01-01";
        $ryd = "$yy-12-31";
        $array=array();
        foreach($FetchedData as $item){
            $gfid = $item['id'];
            $lyd = "lyd";
            $vpsql = "SELECT DISTINCT kind FROM treasury WHERE user_id=$gfid AND kind!='$lyd'";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item){
                $array["FilterKindTreasury"][$gfid][]=$item;
            }                                 

            $emp_query = "SELECT * FROM transactions WHERE 1";

            // Date filter
            if(isset($_POST['but_search'])){
                $fromDate = $_POST['fromDate'];
                $endDate = $_POST['endDate'];
                $invest = lang('invest');
                if(!empty($fromDate) && !empty($endDate)){
                    $emp_query .= " and datepost
                         between '".$fromDate."' and '".$endDate."' and hide ='0' and type != '".$invest."' and user_id='".$gfid."'";
                }
            }

            $emp_query .= " ORDER BY datepost DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($emp_query);
            foreach($FetchedData as $item){
                $array["FilterdTransactions"][$gfid][]=$item;
                $user_id = $item['user_id'];
                $vpsql = "SELECT Username FROM signup WHERE id= $user_id ";
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                foreach($FetchedData as $item){
                    $array["TransactionUsernames"][$user_id][]=$item;
                }
            }



             $vpsql = "SELECT * FROM transactions WHERE user_id=$gfid AND hide='$delo' AND (type='$invest' AND (kin='بيع' OR kin='buy' OR kin='المدفوع' OR kin='given') OR type='cash' OR type='cards' OR type='transfar') AND (type!='chak') AND (datepost BETWEEN '".$fromDate."' AND '".$endDate."') ORDER BY datepost DESC";
       

            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item){
                $array["AllTransactions"][$gfid][]=$item;
            }

			
            $xfg = "0";
            $vpsql = "SELECT * FROM expenses WHERE user_id=$gfid AND yt='$xfg' AND ((datepost BETWEEN '".$fromDate."' and '".$endDate."') OR (toyer BETWEEN '".$fromDate."' and '".$endDate."')) ORDER BY time DESC";            
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item){
                $array["Allexpenses"][$gfid][]=$item;
            }



            $tyip = "transfar";
            $head = "head";
            $vpsql = "SELECT * FROM capital WHERE user_id=$gfid AND tyi='$tyip' AND whb!='0' AND type='$head' ORDER BY time DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item){
                $array["Allcapitals"][$gfid][]=$item;
            }


            $tyip = "transfar";
            $head = "head";
            $vpsql = "SELECT * FROM capital WHERE user_id=$gfid AND tyi='$tyip' AND wh!='0' AND type='$head' ORDER BY time DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item){
                $array["Allcapitalswh"][$gfid][]=$item;
            }
            $xfg = "0";
            $vpsql = "SELECT * FROM expenses WHERE user_id=$gfid AND yt='$xfg' AND ((datepost BETWEEN '".$ryt."' and '".$ryd."') OR (toyer BETWEEN '".$ryt."' and '".$ryd."')) ORDER BY time DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item){
                $array["Allexpenses2"][$gfid][]=$item;
            }        


            $zsdf = "0";
            $delo = "0";
            $vpsql = "SELECT * FROM cos_transactions WHERE user_id= $gfid AND hide='$delo' AND idjd!='zsdf' AND (type='chak') AND datepost BETWEEN '".$fromDate."' and '".$endDate."' ORDER BY datepost DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item){
                $array["allcostransactions"][$gfid][]=$item;

                $postz_id = $item['post_id'];
                $vpsql = "SELECT * FROM transactions WHERE chak_id=$postz_id";
            
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                foreach($FetchedData as $item){
                    $array["Allfiltertrans"][$postz_id][]=$item;
                }        
            }         



            $tyi = "bank";
            $head = "head";
            // $tyi = "bank";
    // $head = "head";
    // $vpsql = "SELECT * FROM capital WHERE user_id=:sid AND tyi=:tyi AND type=:head ORDER BY time DESC";
    // $view_posts = $conn->prepare($vpsql);
    // $view_posts->bindParam(':sid', $gfid, PDO::PARAM_INT);
    // $view_posts->bindParam(':tyi', $tyi, PDO::PARAM_INT);
    // $view_posts->bindParam(':head', $head, PDO::PARAM_INT);
    
            $vpsql = "SELECT * FROM capital WHERE user_id=$gfid AND tyi='$tyi' AND type='$head' ORDER BY time DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item){
                $array["FilteredCapital"][$gfid][]=$item;
            }        

            $tyip = "transfar";
         
            $vpsql = "SELECT * FROM capital WHERE user_id=$gfid AND tyi='$tyi' AND whb='0' AND type='$head' ORDER BY time DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item){
                $array["FilteredTransfarCapital"][$gfid][]=$item;
            }        
            
            $vpsql = "SELECT * FROM capital WHERE user_id=$gfid AND tyi='$tyip' AND wh='0' AND type= '$head' ORDER BY time DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item){
                $array["FilteredTranswhCapital"][$gfid][]=$item;
            }        

            $xfg = "0";
            $vpsql = "SELECT * FROM expenses WHERE user_id=$gfid AND yt!='$xfg' AND ((datepost BETWEEN '".$fromDate."' and '".$endDate."') OR (toyer BETWEEN '".$fromDate."' and '".$endDate."')) ORDER BY time DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item)
            {
                $array["Filteredexpenses"][$gfid][]=$item;
            }  
            
            
            
            $vpsql = "SELECT DISTINCT note FROM expenses WHERE user_id=$gfid AND ((datepost BETWEEN '".$fromDate."' and '".$endDate."') OR (toyer BETWEEN '".$fromDate."' and '".$endDate."')) ORDER BY time DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item)
            {
                $noteid = $item['note'];
                $array["FilteredNotesexpenses"][$gfid][]=$item;
                $vpsql = "SELECT * FROM expenses WHERE note='$noteid' ORDER BY time DESC";
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                foreach($FetchedData as $item)
                {
                    $array["FilteredByNote"][$noteid][]=$item;
                }    
            }  
		}

        
        $data["transdata"]=$array;

        $array2=array();
        $lyd = "lyd";
        
        if($typo == "admin"){
             $fetchUsers_sql = "SELECT DISTINCT shop_id FROM signup WHERE shop_id='$shopo'";            
        }else{
             $fetchUsers_sql = "SELECT DISTINCT shop_id FROM signup WHERE boss_id='$sid' OR id='$sid'";            
        }        
        $FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
     //   print_r($FetchedData);exit;

        $data["distsignups"]=$FetchedData;
        foreach($FetchedData as $item)
        {         

            $gfidshop = $item['shop_id'];
            $vpsql = "SELECT DISTINCT kind FROM treasury WHERE shop_id=$gfidshop AND kind!='$lyd'";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item)
            {
                $array2["kinds"][$gfidshop][]=$item;
                $noteid = $item['kind'];

                $fetchUsers_sql="";
                if($typo == "admin"){
                    $fetchUsers_sql = "SELECT DISTINCT id FROM signup WHERE shop_id='$shopo'";
                    
                }
                else
                {
                    $fetchUsers_sql = "SELECT DISTINCT id FROM signup WHERE boss_id='$sid' OR id='$sid'";
                }
                $FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
                $data["signupids"]=$FetchedData;
                foreach($FetchedData as $item){
                    
                    $gfid = $item['id'];

                    $buya = "sell";
                    $buyb = "شراء";
                    //$gfid = $rows['id'];
                    $vpsql = "SELECT * FROM transactions WHERE user_id=$gfid AND (received_name='$noteid' OR given_name='$noteid') AND ((type='$invest' AND (kin='بيع' OR kin='buy' OR kin='المدفوع' OR kin='given')) OR ((type='cash' OR type='cards' OR type='transfar' OR type='chak') AND (kin='buy' OR kin='بيع'))) AND hide='0' AND datepost BETWEEN '".$fromDate."' and '".$endDate."' ORDER BY time DESC";
                    
                    $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                    foreach($FetchedData as $item)
                    {
                        $array2["filterselltransactions"][$noteid][]=$item;
                    }   




                    $vpsql = "SELECT * FROM transactions WHERE user_id=$gfid AND (received_name='$noteid' OR given_name='$noteid') AND (kin='$buya' OR kin='$buyb') AND (type='cash' OR type='cards' OR type='transfar' OR type='chak') AND hide='0' AND datepost BETWEEN '".$fromDate."' and '".$endDate."' ORDER BY time DESC";
                    $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                    foreach($FetchedData as $item)
                    {
                        $array2["filselltransications2"][$gfid][]=$item;
                    }   
                }
            }            
        }
        
        

        $data["traeasrydata"]=$array2;

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
		//$data["bank"]=$array;
        $bgh = "cash";
          if($typem == "boss"){
                $gfid = $boss_id;
            }elseif($typem == "admin"){
                $gfid = $shop_id;
            }else{
                $gfid = $shop_id;
            }
		//============total of the money=============================
		$vpsql = "SELECT * FROM treasury WHERE tyi='$bgh' AND shop_id=  $gfid OR tyi= '$bgh' AND boss_id= $gfid";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasry"]=$FetchedData;
        return $data;

    }








    public function WithoutFilter2($data){
        $yy = date(Y);
        $ryt = "$yy-01-01";
        $ryd = "$yy-12-31";
        $logh = $_SESSION['language'];
        if($logh == "english"){
            $kindoi = "sell";
        }else{
            $kindoi = "شراء";
        }$delo="0";
        $invest = lang('invest');
        //=================start of the archive=======================
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
        $arrays=array();
        foreach($FetchedData as $item){
            $gfid = $item['id'];
            $vpsql = "SELECT * FROM transactions WHERE user_id=$gfid AND hide='$delo' AND kin='$kindoi' AND type!='$invest' AND (datepost BETWEEN '".$ryt."' and '".$ryd."') ORDER BY datepost DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item){
                $arrays["transactionslist"][$gfid][]=$item;
                $user_id = $item['user_id'];
                $vpsql = "SELECT Username FROM signup WHERE id=$user_id";
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                foreach($FetchedData as $item){
                    $arrays["usernames"][$user_id][]=$item;
                }
                
            }
                                  
        }
        $data["PageData"]=$arrays;
        return $data;
    }


    public function WithoutFilter($data){

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
    
        $bgh = "cash";

		$vpsql = "SELECT * FROM treasury WHERE (kind='LYD' OR kind='USD') AND (tyi= '$bgh' AND shop_id=$gfid OR tyi= '$bgh' AND boss_id= $gfid)";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasury"]=$FetchedData;



        $logh = $_SESSION['language'];
        $kindoi2 = "";
		if($logh == "english"){
			$kindoi = "sell";
            $kindoi2 = "buy";
		}else{
			$kindoi = "شراء";
            $kindoi2 = "بيع";
		}
        $delo="0";
		$invest = lang('invest');
		//=================start of the archive=======================
		$sid =  $_SESSION['id'];
		$shopo =  $_SESSION['shop_id'];
		$typo =  $_SESSION['type'];
		$fetchUsers_sql="";
		if($typo == "admin"){
			$fetchUsers_sql = "SELECT id FROM signup WHERE shop_id='$shopo'";			
		}else{
			$fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$sid' OR id='$sid'";			
		}       
        

		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$data["signups"]=$FetchedData;
        
        $yy = date('Y');
        $ryt = "$yy-01-01";
        $ryd = "$yy-12-31";
        $array=array();
        foreach($FetchedData as $item){

			$gfid = $item['id'];
            $lyd = "lyd";
            $vpsql = "SELECT DISTINCT kind FROM treasury WHERE user_id=$gfid AND kind!= '$lyd' ";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item){
                $array["treaserykinds"][$gfid][]=$item;
            }
             
            $vpsql = "SELECT DISTINCT note FROM expenses WHERE user_id=$gfid AND ((datepost BETWEEN '".$ryt."' and '".$ryd."') OR (toyer BETWEEN '".$ryt."' and '".$ryd."')) ORDER BY time DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item){
                $array["notesexpenses2"][$gfid][]=$item;
            }


            $vpsql = "SELECT * FROM transactions WHERE user_id=$gfid AND hide='$delo' AND type!='invest' AND (datepost BETWEEN '".$ryt."' and '".$ryd."') ORDER BY datepost DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item){
                $array["transactions2"][$gfid][]=$item;
                
                $user_id = $item['user_id'];
                $vpsql = "SELECT Username FROM signup WHERE id=$user_id ";
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                foreach($FetchedData as $item){
                    $array["usernames2"][$user_id][]=$item;
                }
            }
         




            $vpsql = "SELECT * FROM transactions WHERE user_id=$gfid AND hide= '$delo' AND  kin= '$kindoi2' AND type!= '$invest' AND (datepost BETWEEN '".$ryt."' and '".$ryd."')  ORDER BY datepost DESC";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item){
                $array["transactions3"][$gfid][]=$item;
                
                $user_id = $item['user_id'];
                $vpsql = "SELECT Username FROM signup WHERE id=$user_id ";
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                foreach($FetchedData as $item){
                    $array["usernames3"][$user_id][]=$item;
                }
            }


			$vpsql = "SELECT * FROM transactions WHERE user_id=$gfid AND hide= '$delo' AND  kin= '$kindoi' AND type!= '$invest' AND (datepost BETWEEN '".$ryt."' and '".$ryd."')  ORDER BY datepost DESC";
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item){
                $array["transactions"][$gfid][]=$item;
                
                $user_id = $item['user_id'];
                $vpsql = "SELECT Username FROM signup WHERE id=$user_id ";
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                foreach($FetchedData as $item){
                    $array["usernames"][$user_id][]=$item;
                }
            }

            $bill;
            
           $vpsql = "SELECT * FROM transactions WHERE user_id= $gfid AND hide= '$delo' AND (datepost BETWEEN '".$ryt."' and '".$ryd."')";
           
			$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
           $transCounts=count($FetchedData);
            foreach($FetchedData as $item){
                $array["transactions4"][$gfid][]=$item;                
                $user_id = $item['user_id'];
                $bill = $item['bill'];
            }
            if($transCounts>0)
            {
                //$bill;
                $vpsql = "SELECT DISTINCT bill FROM transactions WHERE bill=  $bill";
                //exit;
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                
                foreach($FetchedData as $item){
                    $bill = $item['bill'];
                    $array["bills"][$bill][]=$item;
                }
            }else{
                $array["bills"][0][]=array();
            }


            $vpsql = "SELECT * FROM transactions WHERE user_id= $gfid AND hide= '$delo' AND (type= '$invest' 
            AND (kin='بيع' OR kin='buy' OR kin='المدفوع' OR kin='given') OR type='cash' OR type='cards' 
            OR type='transfar') AND (type!='chak') AND (datepost BETWEEN '".$ryt."' and '".$ryd."') 
            ORDER BY datepost DESC";
           $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
           foreach($FetchedData as $item)
           {
                $array["transactions5"][$gfid][]=$item;
                $chak_id = $item['chak_id'];

                $vpsql = "SELECT * FROM cos_transactions WHERE post_id=$chak_id";
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                foreach($FetchedData as $itemb){
                    $array["cos_transactions"][$chak_id][]=$itemb;
                }
            }

            $tyi = "cash";
            $head = "head";
            $vpsql = "SELECT * FROM capital WHERE user_id=$gfid AND tyi='$tyi' AND type= '$head' ORDER BY time DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item)
            {
                $array["CashCapital"][$gfid][]=$item;
            }
            
            $tyip = "transfar";
            $head = "head";
            $vpsql = "SELECT * FROM capital WHERE user_id=$gfid AND tyi='$tyip' AND whb!='0' AND type= '$head' ORDER BY time DESC";             
    //   $view_posts = $conn->prepare($vpsql);
                                                //   $view_posts->bindParam(':sid', $gfid, PDO::PARAM_INT);
                                                //   $view_posts->bindParam(':tyi', $tyip, PDO::PARAM_INT);
                                                //   $view_posts->bindParam(':head', $head, PDO::PARAM_INT);
                                                //   $view_posts->execute();             

            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
          
            
            foreach($FetchedData as $item)
            {                
                $array["TransferCapital"][$gfid][]=$item;
            }


            

            $vpsql = "SELECT * FROM capital WHERE user_id=$gfid AND tyi='$tyip' AND wh!='0' AND type='$head' ORDER BY time DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item)
            {
                $array["TransferWHCapital"][$gfid][]=$item;
            }


            $xfg = "0";
            $vpsql = "SELECT * FROM expenses WHERE user_id=$gfid AND yt='$xfg' AND ((datepost BETWEEN '".$ryt."' and '".$ryd."') OR (toyer BETWEEN '".$ryt."' and '".$ryd."')) ORDER BY time DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item)
            {
                $array["expenses"][$gfid][]=$item;
            }


            $zsdf = "0";
            $delo = "0";
            $vpsql = "SELECT * FROM cos_transactions WHERE user_id=$gfid AND hide='$delo' AND idjd!='$zsdf' AND (type='chak' OR (type='transfar' AND idjd!='')) AND datepost BETWEEN '".$ryt."' and '".$ryd."' ORDER BY datepost DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item){
                $array["cos_transactions2"][$gfid][]=$item;
                $postz_id = $item['post_id'];
                $vpsql = "SELECT * FROM transactions WHERE chak_id=$postz_id";
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                foreach($FetchedData as $item)
                {
                    $array["costransactionspostsid"][$postz_id][]=$item;
                }
            }


            $tyi = "bank";
            $head = "head";
            $vpsql = "SELECT * FROM capital WHERE user_id=$gfid AND tyi='$tyi' AND type='$head' ORDER BY time DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item)
            {
                $array["bankcapital"][$gfid][]=$item;
            }

            $tyip = "transfar";
            $head = "head";
            $vpsql = "SELECT * FROM capital WHERE user_id=$gfid AND tyi='$tyip' AND whb='0' AND type='$head' ORDER BY time DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item)
            {
                $array["transfercapitalwhbs"][$gfid][]=$item;
            }

            $tyip = "transfar";
            $head = "head";
            $vpsql = "SELECT * FROM capital WHERE user_id=$gfid AND tyi='$tyip' AND wh='0' AND type='$head' ORDER BY time DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            
            foreach($FetchedData as $item)
            {
                $array["transfercapitalwh2"][$gfid][]=$item;
            }

            $xfg = "0";
            $vpsql = "SELECT * FROM expenses WHERE user_id=$gfid AND yt!='$xfg' AND ((datepost BETWEEN '".$ryt."' and '".$ryd."') OR (toyer BETWEEN '".$ryt."' and '".$ryd."')) ORDER BY time DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item)
            {
                $array["expenses2"][$gfid][]=$item;
            }

            $vpsql = "SELECT DISTINCT note FROM expenses WHERE user_id=$gfid ORDER BY time DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item)
            {
                $array["expensesnotes"][$gfid][]=$item;
                $noteid = $item['note'];

                $vpsql = "SELECT * FROM expenses WHERE note='$noteid' AND ((datepost BETWEEN '".$ryt."' and '".$ryd."') OR (toyer BETWEEN '".$ryt."' and '".$ryd."')) ORDER BY time DESC";
                $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                foreach($FetchedData as $item)
                {
                    $array["notesdata"][$noteid][]=$item;
                }
            }
            


		}
        // echo "<pre>";
        // print_r($array["notesexpenses2"]);exit;
        $data["transdata"]=$array;

        $array2=array();
        $lyd = "lyd";
        if($typo == "admin"){
            $fetchUsers_sql = "SELECT DISTINCT shop_id FROM signup WHERE shop_id='$shopo'";            
        }else{
            $fetchUsers_sql = "SELECT DISTINCT shop_id FROM signup WHERE boss_id='$sid' OR id='$sid'";            
        }        
        $FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
        $data["distsignups"]=$FetchedData;
        foreach($FetchedData as $item)
        {         

            $gfidshop = $item['shop_id'];
            $vpsql = "SELECT DISTINCT kind FROM treasury WHERE shop_id=$gfidshop AND kind!='$lyd'";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $item)
            {
                $array2["kinds"][$gfidshop][]=$item;
                $noteid = $item['kind'];

                $fetchUsers_sql="";
                if($typo == "admin"){
                    $fetchUsers_sql = "SELECT DISTINCT id FROM signup WHERE shop_id='$shopo'";
                    
                }
                else
                {
                    $fetchUsers_sql = "SELECT DISTINCT id FROM signup WHERE boss_id='$sid' OR id='$sid'";
                }
                $FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
                $data["signupids"]=$FetchedData;
                foreach($FetchedData as $item){
                    $gfid = $item['id'];

                    $buya = "sell";
                    $buyb = "شراء";
                    $vpsql = "SELECT * FROM transactions WHERE user_id=$gfid AND (received_name='$noteid' OR given_name= '$noteid' ) AND (kin='$buya' OR kin='$buyb') AND (type='cash' OR type='cards' OR type='transfar' OR type='chak') AND hide='0' AND datepost BETWEEN '".$ryt."' and '".$ryd."' ORDER BY time DESC";
                    $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                    foreach($FetchedData as $item)
                    {
                        $array2["selltransactions"][$noteid][]=$item;
                    }   




                    $vpsql = "SELECT * FROM transactions WHERE user_id=$gfid AND (received_name='$noteid' OR given_name= '$noteid') AND  (type='$invest' AND (kin='بيع' OR kin='buy' OR kin='المدفوع' OR kin='given') OR ((type='cash' OR type='cards' OR type='transfar' OR type='chak') AND (kin='بيع' OR kin='buy'))) AND hide='0' AND datepost BETWEEN '".$ryt."' and '".$ryd."' ORDER BY time DESC";
                    $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                    foreach($FetchedData as $item)
                    {
                        $array2["transicationsdistIds"][$noteid][]=$item;
                    }   
                }
            }            
        }
        
        

        $data["traeasrydata"]=$array2;

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
        $bgh = "cash";
          if($typem == "boss"){
                $gfid = $boss_id;
            }elseif($typem == "admin"){
                $gfid = $shop_id;
            }else{
                $gfid = $shop_id;
            }
		//============total of the money=============================
		$vpsql = "SELECT * FROM treasury WHERE tyi='$bgh' AND shop_id=  $gfid OR tyi= '$bgh' AND boss_id= $gfid";
		$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
		$data["treasry"]=$FetchedData;
        return $data;

    }



}
