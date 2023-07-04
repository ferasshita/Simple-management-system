<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

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
			// $this->load->helper("Islogedin","Paymust");

			$this->load->helper(
				array('langs','timefunction','Mode','User','Currencynodes','countrynames')
		);

			$this->load->model('comman_model');
			LoadLang();
			//LoadLang();
			// Your own constructor code
	}

	public function index()
	{
	      $fetchUsers_sql = "SELECT DISTINCT groupco FROM transaction";

	        $FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
	        $data["groupsc"]=$FetchedData;


					$fetchUsers_sql = "SELECT DISTINCT nationality FROM transaction";

					$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
					$data["groupsn"]=$FetchedData;


					$fetchUsers_sql = "SELECT DISTINCT company FROM transaction";

					$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
					$data["groupsco"]=$FetchedData;

					$fetchUsers_sql = "SELECT DISTINCT job FROM transaction";

					$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
					$data["groupsj"]=$FetchedData;

					$fetchUsers_sql = "SELECT DISTINCT section FROM transaction";

					$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
					$data["groupss"]=$FetchedData;

					$fetchUsers_sql = "SELECT DISTINCT education FROM transaction";

					$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
					$data["groupsed"]=$FetchedData;

					$fetchUsers_sql = "SELECT DISTINCT name FROM transaction";

					$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
					$data["groupsname"]=$FetchedData;

					$fetchUsers_sql = "SELECT DISTINCT company_action FROM transaction";

					$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
					$data["groupscoac"]=$FetchedData;

					$fetchUsers_sql = "SELECT DISTINCT company_address FROM transaction";

					$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
					$data["groupscoadd"]=$FetchedData;

					$fetchUsers_sql = "SELECT DISTINCT company_do FROM transaction";

					$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
					$data["groupscodo"]=$FetchedData;
		//////////////////////////////

		$this->load->view('Transaction/index', $data);
	}
}
