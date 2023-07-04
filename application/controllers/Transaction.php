<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {

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
				array('langs','timefunction','Mode','User','Currencynodes','countrynames')
		);
			$this->load->model('comman_model');
			$this->load->model('Home_model');
			LoadLang();
			//LoadLang();
			// Your own constructor code
$_SESSION['Username'] == "user";

	}


	public function index(){


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



	public function wtransaction(){
		//error_reporting(0);
		//include "../config/connect.php";

		//==================from the form of transaction==============================
		$name = filter_var(htmlspecialchars($_POST['name']),FILTER_SANITIZE_STRING);
		$gender = filter_var(htmlspecialchars($_POST['gender']),FILTER_SANITIZE_STRING);
		$birthdate = filter_var(htmlspecialchars($_POST['birthdate']),FILTER_SANITIZE_STRING);
		$passport = filter_var(htmlspecialchars($_POST['passport']),FILTER_SANITIZE_STRING);
		$education = filter_var(htmlspecialchars($_POST['education']),FILTER_SANITIZE_STRING);
		$section = filter_var(htmlspecialchars($_POST['section']),FILTER_SANITIZE_STRING);
		$qualification_years = filter_var(htmlspecialchars($_POST['qualification_years']),FILTER_SANITIZE_STRING);
		$job = filter_var(htmlspecialchars($_POST['job']),FILTER_SANITIZE_STRING);
		$paid = filter_var(htmlspecialchars($_POST['paid']),FILTER_SANITIZE_STRING);
		$starting = filter_var(htmlspecialchars($_POST['starting']),FILTER_SANITIZE_STRING);
		$expiring = filter_var(htmlspecialchars($_POST['expiring']),FILTER_SANITIZE_STRING);
		$visa = filter_var(htmlspecialchars($_POST['visa']),FILTER_SANITIZE_STRING);
		$enter_date = filter_var(htmlspecialchars($_POST['enter_date']),FILTER_SANITIZE_STRING);
		$company = filter_var(htmlspecialchars($_POST['company']),FILTER_SANITIZE_STRING);
		$company_address = filter_var(htmlspecialchars($_POST['company_address']),FILTER_SANITIZE_STRING);
		$company_action = filter_var(htmlspecialchars($_POST['company_action']),FILTER_SANITIZE_STRING);
		$company_do = filter_var(htmlspecialchars($_POST['company_do']),FILTER_SANITIZE_STRING);
		$nationality = filter_var(htmlspecialchars($_POST['nationality']),FILTER_SANITIZE_STRING);
		$group = filter_var(htmlspecialchars($_POST['group']),FILTER_SANITIZE_STRING);

		$fetchUsers_sql = "SELECT passport FROM transaction WHERE passport='$passport'";

		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		foreach ($FetchedData as $rows) {
			$passport_repeat = $rows['passport'];
		}
if($passport_repeat == ""){
$_SESSION['error'] = "";
		$data = array(
			'name'   => $name,
			'gender'      => $gender,
			'birthdate'      => $birthdate,
			'passport'      => $passport,
			'education'      => $education,
			'section'      => $section,
			'qualification_years'      => $qualification_years,
			'job'      => $job,
			'paid'      => $paid,
			'starting	'      => $starting,
			'expiring'      => $expiring,
			'visa'      => $visa,
			'enter_date'		=>	$enter_date,
			'company'      => $company,
			'company_address'      => $company_address,
			'company_action'      => $company_action,
			'company_do'      => $company_do,
			'nationality'      => $nationality,
			'groupco'      => $group
		);

		$this->comman_model->insert_entry("transaction",$data);
}else{
$_SESSION['error'] = "1";
}
	}
	public function utransaction(){
		//error_reporting(0);
		//include "../config/connect.php";

		//==================from the form of transaction==============================
		$name = filter_var(htmlspecialchars($_POST['names']),FILTER_SANITIZE_STRING);
		$gender = filter_var(htmlspecialchars($_POST['genders']),FILTER_SANITIZE_STRING);
		$birthdate = filter_var(htmlspecialchars($_POST['birthdates']),FILTER_SANITIZE_STRING);
		$passport = filter_var(htmlspecialchars($_POST['passports']),FILTER_SANITIZE_STRING);
		$education = filter_var(htmlspecialchars($_POST['educations']),FILTER_SANITIZE_STRING);
		$section = filter_var(htmlspecialchars($_POST['sections']),FILTER_SANITIZE_STRING);
		$qualification_years = filter_var(htmlspecialchars($_POST['qualification_yearss']),FILTER_SANITIZE_STRING);
		$job = filter_var(htmlspecialchars($_POST['jobs']),FILTER_SANITIZE_STRING);
		$paid = filter_var(htmlspecialchars($_POST['paids']),FILTER_SANITIZE_STRING);
		$starting = filter_var(htmlspecialchars($_POST['startings']),FILTER_SANITIZE_STRING);
		$expiring = filter_var(htmlspecialchars($_POST['expirings']),FILTER_SANITIZE_STRING);
		$visa = filter_var(htmlspecialchars($_POST['visas']),FILTER_SANITIZE_STRING);
		$enter_date = filter_var(htmlspecialchars($_POST['enter_dates']),FILTER_SANITIZE_STRING);
		$company = filter_var(htmlspecialchars($_POST['companys']),FILTER_SANITIZE_STRING);
		$company_address = filter_var(htmlspecialchars($_POST['company_addresss']),FILTER_SANITIZE_STRING);
		$company_action = filter_var(htmlspecialchars($_POST['company_actions']),FILTER_SANITIZE_STRING);
		$company_do = filter_var(htmlspecialchars($_POST['company_dos']),FILTER_SANITIZE_STRING);
		$nationality = filter_var(htmlspecialchars($_POST['nationalitys']),FILTER_SANITIZE_STRING);
		$group = filter_var(htmlspecialchars($_POST['groups']),FILTER_SANITIZE_STRING);

		$fetchUsers_sql = "SELECT passport FROM transaction WHERE passport='$passport' AND name != '$name'";

		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		foreach ($FetchedData as $rows) {
			$passport_repeat = $rows['passport'];
		}
		if($passport_repeat == ""){
		$_SESSION['error_u'] = "";
		$data = array(
			'gender'      => $gender,
			'birthdate'      => $birthdate,
			'passport'      => $passport,
			'education'      => $education,
			'section'      => $section,
			'qualification_years'      => $qualification_years,
			'job'      => $job,
			'paid'      => $paid,
			'starting	'      => $starting,
			'expiring'      => $expiring,
			'visa'      => $visa,
			'enter_date'		=>	$enter_date,
			'company'      => $company,
			'company_address'      => $company_address,
			'company_action'      => $company_action,
			'company_do'      => $company_do,
			'nationality'      => $nationality,
			'groupco'      => $group
		);
		$where=array('name' => $name);
		$update_info=$this->comman_model->update_entry("transaction",$data,$where);
	}else{
	$_SESSION['error_u'] = "1";
	}
	}
//=======================================================================================================
public function delete_transaction(){

	//==================from the function========================
	$c_id = htmlentities($_POST['cid'], ENT_QUOTES);


	$delete_comm_sql = "DELETE FROM transaction WHERE id ='$c_id' ";
	            $IsUpdate=$this->comman_model->run_query($delete_comm_sql);
echo"yes";
}

}
