<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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
				array('langs', 'IsLogedin', 'Paymust','timefunction','Mode','User','Currencynodes','numcount')
		);
			$this->load->model('comman_model');
			$this->load->model('Home_model');
			LoadLang();
			//LoadLang();
			// Your own constructor code
	}




	public function index()
	{
		///check login
		//header("location: account/login");
		//phpinfo(); exit;
		Checklogin(base_url());
		Pay_must();
		//echo $_SESSION['steps'];exit;
		 if($_SESSION['user_email_status'] == "not verified"){
				header("location:email_verification");}
		if($_SESSION['steps'] != 1){

			$url=base_url()."steps?tc=shop";
			header("location: $url");
		}
		$sid = $_SESSION['id'];
		$boss_id = $_SESSION['boss_id'];
		$shop_id = $_SESSION['shop_id'];
		$uid = $_SESSION['id'];
		$shopo =  $_SESSION['shop_id'];
		$typo =  $_SESSION['type'];


		$aSetupDB=$this->comman_model->get_data_where("signup","id",$uid);
		$data["aSetupFromDb"] = $aSetupDB['aSetup'];

		//$uid = $_SESSION['id'];
		//capitalcount
		$capital=$this->comman_model->get_data_where("capital","boss_id",$boss_id);
		$capitalCount=$this->comman_model->get_dataCount_where("capital","boss_id",$boss_id);
		$data["capitalCount"]=$capitalCount;
		//bank count
		$bankCount=$this->comman_model->get_dataCount_where("bank","boss_id",$boss_id);
		$data["bankCount"] = $bankCount;
		//transaction count
		$TransCount=$this->comman_model->get_dataCount_where("transactions","user_id",$sid);
		$data["TransCount"] = $TransCount;
		//expenses count
		$ExpensesCount=$this->comman_model->get_dataCount_where("expenses","boss_id",$boss_id);
		$data["ExpensesCount"] = $ExpensesCount;


		///get user data
		$fetchUsers_data=null;
		if($typo == "admin"){
			$fetchUsers_data=$this->comman_model->get_data_where("signup","shop_id",$shopo);
		}else{
			$fetchUsers_data=$this->Home_model->get_id_fromSignup($sid);
		}
		//print_r($fetchUsers_data);exit;
		$data["FetchUserID"] =$fetchUsers_data["id"];
		$gfid=$fetchUsers_data["id"];

		//$vpsql = "SELECT DISTINCT cos_id FROM cos_transactions WHERE user_id=:sid";
		//get transaction cost id
		$transData=$this->comman_model->get_data_where("transactions","user_id",$gfid);
		$data["cosid"]=$transData["cos_id"];
		$cos_id=$transData["cos_id"];

		///customer name
		if($cos_id>0){
			$CustomQuery="SELECT name FROM costumers WHERE user_id= $gfid AND main_id= $cos_id AND name !='casher'";
			$customerData=$this->Home_model->get_data_by_query($CustomQuery);
			$data["name"]=$customerData["name"];
		}



		//treasury data
		$treasuryData=$this->comman_model->get_data_where("treasury","user_id",$gfid);
		$data["treasuryData"]=$treasuryData;
		/////ARCHIVE START
		$ArchiveData=null;
		if($typo == "admin"){
			$ArchiveData=$this->comman_model->get_data_where("signup","shop_id",$shopo);
		}else{
			$ArchiveData=$this->Home_model->get_id_fromSignup($sid);
		}
		$data["Archive_id"]=$ArchiveData["id"];
		$gfid = $ArchiveData["id"];
		///////
		$delo = "0";
		$invest = "invest";
		$yy = date('Y');
		$ryt = "$yy-01-01";
		$ryd = "$yy-12-31";
		///data transactions
		$vpsql = "SELECT * FROM transactions WHERE user_id= $gfid AND hide= '$delo' AND type!= '$invest' AND (datepost BETWEEN '".$ryt."' and '".$ryd."') ORDER BY datepost DESC";
		$infoData=$this->Home_model->get_all_data_by_query($vpsql);
		$data["info"]=$infoData;

		/////////////////
		$vpsql = "SELECT DISTINCT kind FROM treasury WHERE shop_id=$shop_id AND kind !='LYD' AND tyi='cash'";
		$tresdata=$this->Home_model->get_all_data_by_query($vpsql);
		$array=array();
		foreach($tresdata as $item){
			//$array[]= $item["kind"];
			$numberya = $item["kind"];
			$vpsql = "SELECT * FROM transactions WHERE user_id=$sid AND given_name = '$numberya' ";
			$transdata=$this->Home_model->get_all_data_by_query($vpsql);
			$array["kinds"]["kind"]=$item["kind"];
			$array["kinds"][$item["kind"]] = $transdata;
		}
		$data["graph"]=$array;


		///graph cash lyd data
		$vpsql = "SELECT kind, number FROM treasury WHERE shop_id = $shop_id AND tyi='cash' AND kind='LYD'";
		$Cashdata=$this->Home_model->get_all_data_by_query($vpsql);
		$data["CashLYD"]=$Cashdata;

		///graph cash EU data
		$vpsql = "SELECT kind, number FROM treasury WHERE shop_id= $shop_id AND tyi='cash' AND kind='EU'";
		$CashEu=$this->Home_model->get_all_data_by_query($vpsql);
		$data["CashLEU"]=$CashEu;
		//graph CASH USD
		$vpsql = "SELECT kind, number FROM treasury WHERE shop_id=$shop_id AND tyi='cash' AND kind='USD'";
		$CashUSD=$this->Home_model->get_all_data_by_query($vpsql);
		$data["CashUSD"]=$CashUSD;
		//graph CASH GB
		$vpsql = "SELECT kind, number FROM treasury WHERE shop_id=$shop_id AND tyi='cash' AND kind='GBP'";
		$CashGB=$this->Home_model->get_all_data_by_query($vpsql);
		$data["CashGB"]=$CashGB;
		$data["dircheckPath"]= base_url()."Asset/";
		//load currencies
		$this->load->helper("Currencynodes");
		$data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();

		// foreach($array as  $postsfetch ) {
        //       print_r($postsfetch);
		//  	//$numberya = $postsfetch; //$postsfetch['kind'];
		// }
		// exit;
		// echo $data["kind"]=$tresdata[0]["kind"];exit;

		$this->load->view('dashboard/home',$data);
	}
	//pay screen
	public function pay()
	{
		Checklogin(base_url());
		$this->load->view('dashboard/pay');
	}



public function panel(){
		///check login

		Checklogin(base_url());
		Pay_must();
		$mode=LoadMode();

        $data["dircheckPath"]= base_url()."Asset/";
        $data["layoutmode"]  =   $mode;
        $data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
		////////////////////////////////////////////////////
		if(!isset($_SESSION['Username'])){
			header("location: ../index");
		}
		if($_SESSION['user_email_status'] == "not verified"){
		header("location:../email_verification");
		}
		// check if user is an admin or naot to access dashboard
		if ($_SESSION['admin'] != '1') {
			if ($_SESSION['admin'] != '2') {
				header("location: ../index");
			}
		}
		$cusers_q_sql = "SELECT id FROM signup";

		$FetchedData=$this->comman_model->get_all_data_by_query($cusers_q_sql);
		$data["cusers_q_num_rows"]=count($FetchedData);

		$cposts_q_sql = "SELECT post_id FROM transactions";
		$FetchedData=$this->comman_model->get_all_data_by_query($cposts_q_sql);
		$data["cposts_q_num_rows"]=count($FetchedData);

		$admins_q_sql = "SELECT admin FROM signup WHERE admin='1' OR admin='2'";
		$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
		$data["admins_q_num_rows"]=count($FetchedData);

		$admins_q_sql = "SELECT language FROM signup WHERE language='english'";
		$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
		$data["language_q_num_rows"]=count($FetchedData);

		$admins_q_sql = "SELECT language FROM signup WHERE language='العربية'";
		$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
		$data["languagear_q_num_rows"]=count($FetchedData);



		$admins_q_sql = "SELECT language FROM signup WHERE package='0'";
		$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
		$data["package_free"]=count($FetchedData);


		$admins_q_sql = "SELECT language FROM signup WHERE package='1'";
		$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
		$data["package_basic"]=count($FetchedData);

		$admins_q_sql = "SELECT language FROM signup WHERE package='2'";
		$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
		$data["package_medium"]=count($FetchedData);

		$admins_q_sql = "SELECT language FROM signup WHERE package='3'";
		$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
		$data["package_pro"]=count($FetchedData);

		$admins_q_sql = "SELECT sus FROM signup WHERE sus='1'";
		$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
		$data["sus_q_num_rows"]=count($FetchedData);


		$admins_q_sql = "SELECT type FROM signup WHERE type='boss'";
		$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
		$data["boss"]=count($FetchedData);

		$admins_q_sql = "SELECT type FROM signup WHERE type='admin' OR type='user'";
		$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
		$data["sub_user"]=count($FetchedData);


		$admins_q_sql = "SELECT type FROM signup WHERE type='shop'";
		$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
		$data["shop"]=count($FetchedData);

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


		$fetchUsers_sql = "SELECT id,Username,phone,name,email,nex_pay,admin,sus,online FROM signup Where type='boss'";
		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$data["signups"]=$FetchedData;

		$this->load->view('dashboard/panel',$data);
	}





	public function mypanel(){
		///check login

		Checklogin(base_url());
		Pay_must();
		$mode=LoadMode();
		$data["dircheckPath"]= base_url()."Asset/";
		$data["layoutmode"]  =   $mode;
		$data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
		////////////////////////////////////////////////////

		// if user not logged in go index page or login
		if(!isset($_SESSION['Username'])){
			header("location: ../index");
		}
		if($_SESSION['user_email_status'] == "not verified"){
		header("location:../email_verification");
		}
		if($_SESSION['package'] == "2" || $_SESSION['package'] == "3" || $_SESSION['package'] == "4" || $_SESSION['admin'] == "1"){
		}else{
		header("location: ../home");
		}
		// check if user is an admin or not to access dashboard
		if ($_SESSION['type'] == 'boss' || $_SESSION['type'] == 'admin') {
		}else{
			header("location: ../index");
		}


		$ido = $_SESSION['id'];
		$shop_id = $_SESSION['shop_id'];
		//get request 'urlP'
		$urlP = filter_var(htmlspecialchars($_GET['adb']),FILTER_SANITIZE_STRING);
		// ============= [ General Data ] ==============
		if($_SESSION['type'] == "admin"){
			$cusers_q_sql = "SELECT id FROM signup WHERE shop_id='$shop_id'";
			$FetchedData=$this->comman_model->get_all_data_by_query($cusers_q_sql);
			$data["cusers_q_num_rows"]=count($FetchedData);

		}else{
			$cusers_q_sql = "SELECT id FROM signup WHERE boss_id='$ido'";
			$FetchedData=$this->comman_model->get_all_data_by_query($cusers_q_sql);
			$data["cusers_q_num_rows"]=count($FetchedData);
		}
		if($_SESSION['type'] == "admin"){
			$admins_q_sql = "SELECT admin FROM signup WHERE shop_id='$shop_id' AND type='admin'";
			$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
			$data["admins_q_num_rows"]=count($FetchedData);
		}else{
			$admins_q_sql = "SELECT admin FROM signup WHERE boss_id='$ido' AND type='admin'";
			$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
			$data["admins_q_num_rows"]=count($FetchedData);
		}
		if($_SESSION['type'] == "admin"){
			$admins_q_sql = "SELECT language FROM signup WHERE shop_id='$shop_id' AND  language='english'";
			$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
			$data["language_q_num_rows"]=count($FetchedData);

		}else{
			$admins_q_sql = "SELECT language FROM signup WHERE boss_id='$ido' AND  language='english'";
			$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
			$data["language_q_num_rows"]=count($FetchedData);
		}
		if($_SESSION['type'] == "admin"){
			$admins_q_sql = "SELECT language FROM signup WHERE shop_id='$shop_id' AND  language='العربية'";
			$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
			$data["languagear_q_num_rows"]=count($FetchedData);

		}else{
			$admins_q_sql = "SELECT language FROM signup WHERE boss_id='$ido' AND  language='العربية'";
			$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
			$data["languagear_q_num_rows"]=count($FetchedData);
		}if($_SESSION['type'] == "admin"){
			$admins_q_sql = "SELECT sus FROM signup WHERE shop_id='$shop_id' AND sus='1'";
			$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
			$data["sus_q_num_rows"]=count($FetchedData);
		}else{
			$admins_q_sql = "SELECT sus FROM signup WHERE boss_id='$ido' AND sus='1'";
			$FetchedData=$this->comman_model->get_all_data_by_query($admins_q_sql);
			$data["sus_q_num_rows"]=count($FetchedData);
		}


		if($_SESSION['type'] == "admin"){
			$fetchUsers_sql = "SELECT Username,phone,name,email,nex_pay,admin,sus,shop_id,type,online FROM signup WHERE shop_id='$shop_id'";
		}else{
			$fetchUsers_sql = "SELECT Username,phone,name,email,nex_pay,admin,sus,shop_id,type,online FROM signup WHERE boss_id='$ido'";
		}
		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$array=array();
		foreach($FetchedData as $item){
			$array["info"][]=$item;
			$shopid = $item['shop_id'];
			$fetchUsers_sqli = "SELECT Username FROM signup WHERE id='$shopid'";
			$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sqli);
			foreach($FetchedData as $item)
			{
				$array["usernames"][$shopid][]=$item;
			}
		}
		$data["signups"]=$array;


		$sid = $_SESSION['id'];
		$fetchUsers_sql = "SELECT Username,id FROM signup WHERE type='shop' AND boss_id='$sid'";
		$FetchedData=$this->comman_model->get_all_data_by_query($fetchUsers_sql);
		$data["UData"]=$FetchedData;

		$typem =  $_SESSION['type'];

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

		$this->load->view('dashboard/mypanel',$data);
	}



	public function user(){
		///check login

		Checklogin(base_url());

		$url=base_url()."Dashboard";
		$emailverif=base_url()."email_verification";
		if($_SESSION['user_email_status'] == "not verified"){
		header("location:$emailverif");}
		if ($_SESSION['admin'] != '1') {
			if ($_SESSION['admin'] != '2') {
				header("location:$url");
			}
		}
		// if($_SESSION['package'] == "2" || $_SESSION['package'] == "3" || $_SESSION['package'] == "4" || $_SESSION['admin'] == "1"){
		// }else{
		// header("location:$url");
		// }

		// // check if user is an admin or not to access dashboard

		// // echo $_SESSION['type'];
		// // echo "test";exit;
		// if ($_SESSION['type'] == 'boss' || $_SESSION['type'] == 'admin') {
		// }else{
		// 	header("location:$url");
		// }


		$urlP = filter_var(htmlspecialchars($_GET['adb']),FILTER_SANITIZE_STRING);
		// get var's
		$nex_payi = trim(filter_var(htmlspecialchars($_POST['nex_pay']),FILTER_SANITIZE_STRING));
		$package = trim(filter_var(htmlspecialchars($_POST['package']),FILTER_SANITIZE_STRING));
		$ed = trim(filter_var(htmlspecialchars($_GET['ed']),FILTER_SANITIZE_STRING));
		$db_username = trim(filter_var(htmlentities($_POST['username']),FILTER_SANITIZE_STRING));
		$db_email = trim(filter_var(htmlentities($_POST['email']),FILTER_SANITIZE_STRING));
		$cash = trim(filter_var(htmlentities($_POST['cash']),FILTER_SANITIZE_STRING));
		$chak = trim(filter_var(htmlentities($_POST['chak']),FILTER_SANITIZE_STRING));
		$cards = trim(filter_var(htmlentities($_POST['cards']),FILTER_SANITIZE_STRING));
		$transfar = trim(filter_var(htmlentities($_POST['transfar']),FILTER_SANITIZE_STRING));
		$invest = trim(filter_var(htmlentities($_POST['invest']),FILTER_SANITIZE_STRING));
		$trash = trim(filter_var(htmlentities($_POST['trash']),FILTER_SANITIZE_STRING));
		$Treasury = trim(filter_var(htmlentities($_POST['Treasury']),FILTER_SANITIZE_STRING));
		$buythings = trim(filter_var(htmlentities($_POST['buythings']),FILTER_SANITIZE_STRING));
		$Profit = trim(filter_var(htmlentities($_POST['Profit']),FILTER_SANITIZE_STRING));
		$bank = trim(filter_var(htmlentities($_POST['bank']),FILTER_SANITIZE_STRING));
		$trsh = trim(filter_var(htmlentities($_POST['trsh']),FILTER_SANITIZE_STRING));
		$local = trim(filter_var(htmlentities($_POST['transfar_local']),FILTER_SANITIZE_STRING));
		$tree = trim(filter_var(htmlentities($_POST['tree']),FILTER_SANITIZE_STRING));
		// =========================== password hashinng ==================================
		$db_password_var = trim(filter_var(htmlentities($_POST['password']),FILTER_SANITIZE_STRING));
		$options = array(
			'cost' => 12,
		);
		$db_password = password_hash($db_password_var, PASSWORD_BCRYPT, $options);
		// ================================================================================
		$db_admin = trim(filter_var(htmlentities($_POST['admin']),FILTER_SANITIZE_STRING));
		switch ($db_admin) {
			case lang('yes'):
				$db_admin = "1";
				break;
			case lang('no'):
				$db_admin = "0";
				break;
		}


		if (isset($_POST['pay'])) {
			$yy = date('Y');
			$by = $yy+$nex_payi;
			$mm = date('m');
			$dd = date('d');
			$ergh = $nex_payi;
			$ghys = "$dd-$mm-$yy";
			$fbm = $dd*$mm/$by;
		$nex_pay = $hu*$nex_payi;
		$update = "UPDATE signup SET nex_pay = '$ergh', accou_stu = '$ghys',expir = '$fbm' WHERE id = '$ed'";
		$update=$this->comman_model->run_query($update);
		// $update->bindParam(':fbm',$fbm,PDO::PARAM_STR);
		// $update->bindParam(':nex_pay',$ergh,PDO::PARAM_STR);
		// $update->bindParam(':ghys',$ghys,PDO::PARAM_STR);
		// $update->bindParam(':ed',$ed,PDO::PARAM_STR);
		// $update->execute();
			  if ($update) {
					$update_result = "<p class='alertGreen'>".lang('changes_saved_seccessfully')."</p>";
			  }else{
				  $update_result = "<p class='alertRed'>".lang('errorSomthingWrong')."</p>";
				  $data["update_result"]=$update_result;
			  }
		  }
			if (isset($_POST['package_plus'])) {
$update = "UPDATE signup SET tree='$tree',local_transfar='$local' WHERE id = '$ed'";
$update=$this->comman_model->run_query($update);
// $update->bindParam(':fbm',$fbm,PDO::PARAM_STR);
			// $update->bindParam(':nex_pay',$ergh,PDO::PARAM_STR);
			// $update->bindParam(':ghys',$ghys,PDO::PARAM_STR);
			// $update->bindParam(':ed',$ed,PDO::PARAM_STR);
			// $update->execute();
				  if ($update) {
						$update_result = "<p class='alertGreen'>".lang('changes_saved_seccessfully')."</p>";
				  }else{
					  $update_result = "<p class='alertRed'>".lang('errorSomthingWrong')."</p>";
					  $data["update_result"]=$update_result;
				  }
			  }

		if (isset($_POST['submit_uInfo'])) {
			if (empty($db_username) or empty($db_email)) {
				$data["update_result"] = "<p class='alertRed'>".lang('please_fill_required_fields')."</p>";
				$data["stop"] = "1";
			}
			if(strpos($db_username, ' ') !== false || preg_match('/[\'^£$%&*()}{@#~?><>,.|=+¬-]/', $db_username) || !preg_match('/[A-Za-z0-9]+/', $db_username)) {
				$data["update_result"] = "
					<ul class='alertRed' style='list-style:none;'>
						<li><b>".lang('username_not_allowed')." :</b></li>
						<li><span class='fa fa-times'></span> ".lang('signup_username_should_be_1').".</li>
						<li><span class='fa fa-times'></span> ".lang('signup_username_should_be_2').".</li>
						<li><span class='fa fa-times'></span> ".lang('signup_username_should_be_3').".</li>
						<li><span class='fa fa-times'></span> ".lang('signup_username_should_be_4').".</li>
						<li><span class='fa fa-times'></span> ".lang('signup_username_should_be_5').".</li>
					</ul>";
				$stop = "1";
			}

			$unExist = "SELECT Username FROM signup WHERE Username ='$db_username'";
			$FetchedData=$this->comman_model->get_all_data_by_query($unExist);
			$data["unExistCount"]=count($FetchedData);

			$emExist = "SELECT Email FROM signup WHERE Email ='$db_email'";
			$FetchedData=$this->comman_model->get_all_data_by_query($emExist);
			$data["emExistCount"]=count($FetchedData);

			if($emExistCount > 0){
				if ($uInfo_em != $db_email) {
				$data["update_result"] = "<p class='alertRed'>".lang('email_already_exist')."</p>";
				$data["stop"] = "1";
				$stop="1";
				}
			}

			if (!filter_var($db_email, FILTER_VALIDATE_EMAIL)) {
				$data["update_result"] = "<p class='alertRed'>".lang('invalid_email_address')."</p>";
				$data["stop"] = "1";
				$stop="1";
			}


			if ($stop != "1") {
				if (empty($db_password_var)) {
				$update = "UPDATE signup SET Username = '$db_username',Email = '$db_email',package = '$package',admin = '$db_admin' WHERE id = '$ed'";
				}else{
				$update = "UPDATE signup SET Username = '$db_username',Email = '$db_email',package = '$package',Password = '$db_password',admin = '$db_admin' WHERE id = '$ed' ";
				}
				// $update->bindParam(':db_username',$db_username,PDO::PARAM_STR);
				// $update->bindParam(':db_email',$db_email,PDO::PARAM_STR);
				// $update->bindParam(':package',$package,PDO::PARAM_INT);
				// if (!empty($db_password_var)) {
				// $update->bindParam(':db_password',$db_password,PDO::PARAM_STR);
				// }
				// $update->bindParam(':db_admin',$db_admin,PDO::PARAM_INT);
				// $update->bindParam(':ed',$ed,PDO::PARAM_STR);
				// $update->execute();
				$update=$this->comman_model->run_query($update);
				if ($update) {
					$data["update_result"] = "<p class='alertGreen'>".lang('changes_saved_seccessfully')."</p>";
				}else{
					$data["update_result"] = "<p class='alertRed'>".lang('errorSomthingWrong')."</p>";
				}
			}

		}





		if (isset($_POST['susbend'])) {
			$uInfo = "SELECT sus FROM signup WHERE id = '$ed'";
			$FetchedData=$this->comman_model->get_all_data_by_query($uInfo);
			// $uInfo->bindParam(':ed',$ed,PDO::PARAM_STR);
			// $uInfo->execute();
			$uInfo_count = count($FetchedData);// $uInfo->rowCount();
			if ($uInfo_count > 0) {
			foreach ($FetchedData as $uInfoRow)
			{
				$uInfo_sus= $uInfoRow['sus'];
			}
			if($uInfo_sus == "1"){
			  $sus = "0";
			}else{
			  $sus = "1";
			}

			$query ="UPDATE signup SET sus = '$sus' WHERE id = '$ed' ";
			$update = $this->comman_model->run_query($query);
			// $update->bindParam(':db_username',$sus,PDO::PARAM_INT);
			// $update->bindParam(':ed',$ed,PDO::PARAM_STR);
			// $update->execute();
				if ($update) {
				}else{
					$data["update_result"] = "<p class='alertRed'>".lang('errorSomthingWrong')."</p>";
				}
			}
		}



		Pay_must();

		$mode=LoadMode();
		$data["dircheckPath"]= base_url()."Asset/";
		$data["layoutmode"]  =   $mode;
		$data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
		////////////////////////////////////////////////////
		$typem =  $_SESSION['type'];

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

		$ed = trim(filter_var(htmlspecialchars($_GET['ed']),FILTER_SANITIZE_STRING));
		 $uInfo = "SELECT * FROM signup WHERE id =  '$ed'";
		$FetchedData=$this->comman_model->get_all_data_by_query($uInfo);
		$data["uinfo"]=$FetchedData;
		//print_r($FetchedData);exit;
		$uInfo_count=count($FetchedData);
		// $unExist = $conn->prepare("SELECT Username FROM signup WHERE Username =:db_username");
		// $unExist->bindParam(':db_username',$db_username,PDO::PARAM_STR);
		// $unExist->execute();
		$un_not_found="";
		//$uInfo_id=0;
		if ($uInfo_count > 0) {
			foreach($FetchedData as $uInfoRow ) {
				$uInfo_id = $uInfoRow['id'];
				$shop_id_him = $uInfoRow['shop_id'];
				$uInfo_type = $uInfoRow['type'];
				$uInfo_boss = $uInfoRow['boss_id'];
				$uInfo_un = $uInfoRow['Username'];
				$online = $uInfoRow['online'];
				$pack_ad = $uInfoRow['package'];
				$uInfo_em = $uInfoRow['Email'];
				$uInfo_ph = $uInfoRow['phone'];
				$uInfo_pd = $uInfoRow['Password'];
				$uInfo_ad = $uInfoRow['admin'];
				$nex_payf = $uInfoRow['nex_pay'];
				$package_chose = $uInfoRow['package_chose'];
				$status = $uInfoRow['sus'];
				$accou_stu = $uInfoRow['accou_stu'];
				$tree = $uInfoRow['tree'];
				$local_transfar = $uInfoRow['local_transfar'];
			}
		}else{
				$un_not_found = "user not found";
		}
			// remove a user from all tables (forever from database)
			if (isset($_POST['zAccBtn'])) {

				$remeveAccount_sql = "DELETE FROM transactions WHERE user_id= $uInfo_id";
				$IsDeleted=$this->comman_model->run_query($remeveAccount_sql);

				$remeveAccount_sql = "DELETE FROM treasury WHERE boss_id= $uInfo_id";
				$IsDeleted=$this->comman_model->run_query($remeveAccount_sql);

				$remeveAccount_sql = "DELETE FROM capital WHERE boss_id= $uInfo_id";
				$IsDeleted=$this->comman_model->run_query($remeveAccount_sql);

				$uInfo = "SELECT * FROM signup WHERE boss_id =  '$uInfo_id'";
			 $FetchedData=$this->comman_model->get_all_data_by_query($uInfo);
	foreach($FetchedData as $uInfoRows ) {
$id_del = $uInfoRows['id'];


				$remeveAccount_sql = "DELETE FROM invest_treasury WHERE user_id= $id_del";
				$IDeleted=$this->Comman_model->run_query($remeveAccount_sql);

				$remeveAccount_sql = "DELETE FROM transactions WHERE user_id= $id_del";
				$IsDeleted=$this->comman_model->run_query($remeveAccount_sql);
}
				$remeveAccount_sql = "DELETE FROM treasury WHERE boss_id= $uInfo_id";
				$IDeleted=$this->Comman_model->run_query($remeveAccount_sql);

				$remeveAccount_sql = "DELETE FROM expenses WHERE boss_id= $uInfo_id";
				$IDeleted=$this->Comman_model->run_query($remeveAccount_sql);

				if($bank_del == "1"){

						$remeveAccount_sql = "DELETE FROM bank WHERE boss_id= $session_id";
						$IDeleted=$this->Comman_model->run_query($remeveAccount_sql);
						// $remeveAccount = $conn->prepare($remeveAccount_sql);
						// $remeveAccount->bindParam('$session_id',$boss_id,PDO::PARAM_STR);
						// $remeveAccount->execute();
						$remeveAccount_sql = "DELETE FROM my_bank WHERE boss_id= $session_id";
						$IDeleted=$this->Comman_model->run_query($remeveAccount_sql);
						// $remeveAccount = $conn->prepare($remeveAccount_sql);
						// $remeveAccount->bindParam('$session_id',$boss_id,PDO::PARAM_STR);
						// $remeveAccount->execute();
				}
				if($cos_del == "1"){
						$remeveAccount_sql = "DELETE FROM costumers WHERE boss_id= $session_id";
						$IDeleted=$this->Comman_model->run_query($remeveAccount_sql);

						// $remeveAccount = $conn->prepare($remeveAccount_sql);
						// $remeveAccount->bindParam('$session_id',$boss_id,PDO::PARAM_STR);
						// $remeveAccount->execute();
				}

					if ($IDeleted) {
						$url=base_url()."Dashboard/panel?adb=Users";
					}else
					{
						$data["update_result"] = "<p class='alertRed'>".lang('errorSomthingWrong')."</p>";
					}
				}

		// remove a user from all tables (forever from database)
		if (isset($_POST['rAccBtn'])) {
			$remeveAccount_sql = "DELETE FROM treasury WHERE boss_id= $uInfo_id";
			$remeveAccount=$this->comman_model->run_query($remeveAccount_sql);

			$remeveAccount_sql = "DELETE FROM capital WHERE boss_id= $uInfo_id";
			$remeveAccount=$this->comman_model->run_query($remeveAccount_sql);



			$remeveAccount_sql = "DELETE FROM treasury WHERE boss_id= $uInfo_id";
			$remeveAccount=$this->Comman_model->run_query($remeveAccount_sql);

			$uInfo = "SELECT id FROM signup WHERE boss_id = '$uInfo_id'";
		 $FetchedData=$this->comman_model->get_all_data_by_query($uInfo);
foreach($FetchedData as $uInfoRows ) {
$id_del = $uInfoRows['id'];


			$remeveAccount_sql = "DELETE FROM invest_treasury WHERE user_id= $id_del";
			$remeveAccount=$this->Comman_model->run_query($remeveAccount_sql);

			$remeveAccount_sql = "DELETE FROM transactions WHERE user_id= $id_del";
			$remeveAccount=$this->comman_model->run_query($remeveAccount_sql);
}

			$remeveAccount_sql = "DELETE FROM expenses WHERE boss_id= $uInfo_id";
			$remeveAccount=$this->Comman_model->run_query($remeveAccount_sql);


			if($uInfo_type == "boss"){
					$remeveAccount_sql = "DELETE FROM signup WHERE boss_id= $uInfo_id";
					$remeveAccount=$this->Comman_model->run_query($remeveAccount_sql);
			}

			$remeveAccount_sql = "DELETE FROM signup WHERE id= $uInfo_id";
			$remeveAccount = $this->comman_model->run_query($remeveAccount_sql);
			// $remeveAccount = $conn->prepare($remeveAccount_sql);
			// $remeveAccount->bindParam(':uInfo_id',$uInfo_id,PDO::PARAM_STR);
			// $remeveAccount->execute();

			if ($remeveAccount)
			{
				//echo "Test";exit;
				echo $url=base_url()."Dashboard/panel";
				header("location: $url");
				exit;
			}
			else
			{
				//echo "Test2";exit;
				$data["update_result"] = "<p class='alertRed'>".lang('errorSomthingWrong')."</p>";
			}
		}
		if ($un_not_found != "user not found")
		{
			$admin_int = "1";
			$chAdmin = "SELECT Username FROM signup WHERE id = '$ed' AND admin = '$admin_int' ";
			$FetchedData=$this->comman_model->get_all_data_by_query($chAdmin);
			$data["chAdminCount"]=count($FetchedData);
		}

		$this->load->view('dashboard/user',$data);
	}





	/////////////////////endjscodes called ajax methods

	public function exchange_rate(){
		session_start();
		$comment_content = filter_var(htmlentities($_POST['cContent']),FILTER_SANITIZE_STRING);
		$_SESSION['exchange_rate'] = "$comment_content";
		exit();
	}

	public function delete_my_bank(){
		// include("../config/connect.php");
		// session_start();
		//==================personal information========================
		$c_id = htmlentities($_POST['cid'], ENT_QUOTES);
		$sid =  $_SESSION['boss_id'];
		//================delete the company's bank=============================
			$delete_comm_sql = "DELETE FROM my_bank WHERE id = $c_id AND boss_id = $sid";
			$FetchedData=$this->comman_model->run_query($delete_comm_sql);
			// $delete_comm = $conn->prepare($delete_comm_sql);
			// $delete_comm->bindParam(':sid',$sid,PDO::PARAM_INT);
			// $delete_comm->bindParam(':c_id',$c_id,PDO::PARAM_INT);
			// $delete_comm->execute();
		//=============================================================
			echo "yes";

	}



	//////////////////////////////////////////////



}
