<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Help extends CI_Controller {

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
                    array('langs', 'IsLogedin', 'Paymust','timefunction','Mode','User','Currencynodes'));
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
	
        Checklogin(base_url());
        CheckMailVerification();
        Pay_must();
        if ($_SESSION['trsh'] == '1') {
            header("location: Dashboard");
        }

        $mode=LoadMode();
        $data["dircheckPath"]= base_url()."Asset/";
        $data["layoutmode"]  =   $mode;
        $data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
        $data["active"] = "trash";
		////////////////////////////////////////////////////

		//////////////////////////////////////
		
		$data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
		$this->load->view('Help/index', $data);
	}

  
}
