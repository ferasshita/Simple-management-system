<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trash extends CI_Controller {

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
            header("location: $url");
        }
        if($_SESSION['steps'] != "1"){
            $url=base_url()."steps?tc=shop";
            header("location: $url");
        }

        if ($_SESSION['trsh'] == '1') {
            header("location: Dashboard");
        }
        $data["active"] = "trash";
        $mode=LoadMode();
        $data["dircheckPath"]= base_url()."Asset/";
        $data["layoutmode"]  =   $mode;
        $data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
		////////////////////////////////////////////////////
        $sid =  $_SESSION['id'];
        $delo = "1";
        $sid =  $_SESSION['id'];
        $shopo =  $_SESSION['shop_id'];
        $typo =  $_SESSION['type'];
        $fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$sid' OR shop_id='$shopo' AND type='admin' OR id='$sid'";
        $FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
        $array=array();
        foreach($FetchedData as $item){
            $array["Signup"][]=$item;
            $gfid = $item['id'];
            //=================start of the archive=======================
            $vpsql = "SELECT * FROM transactions WHERE user_id=$gfid AND hide='$delo' ORDER BY datepost DESC";
            $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
            foreach($FetchedData as $trans){
                $array["Transactions"][$gfid][]=$trans;
            }
        }

        $data["Id"]=$array;
        




		$data["bank"]=array();
		$this->load->view('Trash/index', $data);
    }


    public function delete_perment(){
        //include("../config/connect.php");
        //session_start();
        $sid =  $_SESSION['id'];
        //==================from the function========================
        $c_id = htmlentities($_POST['cid'], ENT_QUOTES);
        $p_id = htmlentities($_POST['pid'], ENT_QUOTES);
        $delo = "1";
        //===================check the user========================
        $check = "SELECT * FROM transactions WHERE post_id =$c_id ";
        $FetchedData=$this->comman_model->get_all_data_by_query($check);
        // $check->bindParam(':c_id',$c_id,PDO::PARAM_INT);
        // $check->execute();
        foreach ($FetchedData as $chR) {
            $chR_aid = $chR['user_id'];
            $received = $chR['received'];
            $given = $chR['given'];
            $received_name = $chR['received_name'];
            $given_name = $chR['given_name'];
        }
        //==================delete the transaction =================================
            $delete_comm_sql = "DELETE FROM transactions WHERE hide= '$delo' AND post_id = $c_id";
            $IsUpdate=$this->comman_model->run_query($delete_comm_sql);
            // $delete_comm = $conn->prepare($delete_comm_sql);
            // $delete_comm->bindParam(':numbery',$delo,PDO::PARAM_INT);
            // $delete_comm->bindParam(':c_id',$c_id,PDO::PARAM_INT);
            // $delete_comm->execute();
        //=============================================================
            echo "yes";

    }



}
