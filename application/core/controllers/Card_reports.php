<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Card_reports extends CI_Controller {

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

		if ($_SESSION['cards'] == '1') {
			header("location: home");
		}
		if($_SESSION['package'] == "2" || $_SESSION['package'] == "3" || $_SESSION['package'] == "4" || $_SESSION['admin'] == "1"){
		}else{
			header("location: home");
		}
      
        $mode=LoadMode();
        $data["dircheckPath"]= base_url()."Asset/";
        $data["layoutmode"]  =   $mode;
        $data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
		////////////////////////////////////////////////////
        if(isset($_POST['fromDate']))
        { 
            $data=$this->FilterData($data);
        }else{
            $data=$this->BindData($data);
        }	
        $data["active"] = "cardarc";
       //$//data[]=$test;
		$this->load->view('Card_reports/index', $data);
    }

	
    public function BindData($data){
		$sid = $_SESSION['id'];
		$shop_id = $_SESSION['shop_id'];
		$boss_id = $_SESSION['boss_id'];
		$delo = "0";
		$type = "cards";
		//$sid =  $_SESSION['id'];
		$shopo =  $_SESSION['shop_id'];
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
            $vpsql = "SELECT * FROM cos_transactions WHERE user_id=$gfid AND hide='$delo' AND type='$type' ORDER BY datepost DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $postsfetch){
                $array["allCosTransactions"][$gfid][]=$postsfetch;
				$post_idy = $postsfetch['post_id'];
				$cos_id = $postsfetch['cos_id'];
				$user_id = $postsfetch['user_id'];


				$vpsql = "SELECT * FROM transactions WHERE chak_id=$post_idy AND hide='$delo'";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				foreach($FetchedData as $item){
					$array["allTransactions"][$post_idy][]=$item;
				}

				$vpsql = "SELECT * FROM costumers WHERE main_id=$cos_id";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				foreach($FetchedData as $item){
					$array["allCostumers"][$cos_id][]=$item;
				}

				$vpsql = "SELECT Username FROM signup WHERE id=$user_id";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				foreach($FetchedData as $item){
					$array["usernames"][$user_id][]=$item;
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
		$sid = $_SESSION['id'];
		$shop_id = $_SESSION['shop_id'];
		$boss_id = $_SESSION['boss_id'];
		$delo = "0";
		$type = "cards";


	

		//$sid =  $_SESSION['id'];
		$shopo =  $_SESSION['shop_id'];
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

			$emp_query = "SELECT * FROM cos_transactions WHERE 1";

			// Date filter
			if(isset($_POST['but_search'])){
				$fromDate = $_POST['fromDate'];
				$endDate = $_POST['endDate'];

				if(!empty($fromDate) && !empty($endDate)){
					$emp_query .= " and datepost between '".$fromDate."' and '".$endDate."' and user_id = '".$gfid."' and type = '".$type."' and hide ='0' ";
				}
			}

			// Sort
			$emp_query .= " ORDER BY datepost DESC";
			$FetchedData=$this->comman_model->get_all_data_by_query($emp_query);
			foreach($FetchedData as $postsfetch){
				$array["searchdata"][$gfid][]=$postsfetch;
				
				$id = $postsfetch['id'];
				$cos_id = $postsfetch['cos_id'];
				$post_id = $postsfetch['post_id'];
				$user_id = $postsfetch['user_id'];
				$bankacc = $postsfetch['bankacc'];
				$bankname = $postsfetch['bankname'];
				$uprcen = $postsfetch['uprcen'];

				$vpsql = "SELECT * FROM transactions WHERE chak_id=$post_id";
				$FetchedData=$this->comman_model->get_all_data_by_query($emp_query);
				foreach($FetchedData as $item){
					$array["filtertransactions"][$post_id][]=$item;
					$id = $item['id'];
					$post_id = $item['post_id'];
					$user_id = $item['user_id'];
					$exchange = $item['exchange'];
					$received_name = $item['received_name'];
					$received = $item['received'];
					$given_name = $item['given_name'];
					$kind = $item['kin'];
					$amountsd = $item['given'];
					$time = $item['time'];
					$type = $item['type'];
					$media = $item['media'];
				}
				
				$vpsql = "SELECT Username FROM signup WHERE id=$user_id";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				foreach($FetchedData as $postsfetch){
					$array["usernames"][$user_id][]=$postsfetch;
				}      
				
				$vpsql = "SELECT * FROM costumers WHERE main_id=$cos_id";
				$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
				foreach($FetchedData as $postsfetch){
					$array["filterdcustomers"][$cos_id][]=$postsfetch;
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



}
