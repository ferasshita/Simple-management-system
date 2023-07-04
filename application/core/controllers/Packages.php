<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Packages extends CI_Controller {

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
				array('langs', 'IsLogedin', 'Paymust','timefunction','Mode')
		);
			$this->load->model('comman_model');
			$this->load->model('Home_model');
			LoadLang();
			//LoadLang();
			// Your own constructor code
	}

	// public function index()
	// {
	// 	$this->load->view('index');
	// }


	public function index(){
        Checklogin(base_url());
		Pay_must();
        $mode=LoadMode();
        $data["dircheckPath"]= base_url()."Asset/";
        $data["layoutmode"]  =   $mode;
		$this->load->view('packages/upgrade',$data);
	}

    public function Updatepackage(){
        $c_id = htmlentities($_POST['cid'], ENT_QUOTES);
        $sid =  $_SESSION['boss_id'];
        //================delete the company's bank=============================
            $data = array(
                'package_chose'   => $c_id,                               
            );
            $where=array('boss_id' => $c_id,'id' => $sid);
            $update_info=$this->comman_model->update_entry("signup",$data,$where);
        //     $delete_comm_sql = "UPDATE signup SET package_chose=:c_id WHERE boss_id = :sid AND id = :sid";
        //     $delete_comm = $conn->prepare($delete_comm_sql);
        //   $delete_comm->bindParam(':c_id',$c_id,PDO::PARAM_INT);
        //   $delete_comm->bindParam(':sid',$sid,PDO::PARAM_INT);
        //     $delete_comm->execute();
        //=============================================================
            echo "yes";
        
        $_SESSION['package_chose'] = $c_id;
    }
	
}
