<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {

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
			$this->load->helper("langs");
			$this->load->helper("IsLogedin");
			$this->load->helper("Phonecodes");
			
			$this->load->helper(
				array('langs', 'IsLogedin', 'Phonecodes','Sendmail')
			);
			$this->load->model('account_model');
			$this->load->model('Comman_model');
		
			//LoadLang();
			// Your own constructor code
	}

	public function login()
	{
		$url=base_url()."Dashboard";
		loginRedirect($url);
		$this->load->view('account/login');
	}

	public function Forgot_password()
	{
		$url=base_url()."Dashboard";
		loginRedirect($url);

		LoadLang();	
		$message="";
if (isset($_POST['send_email'])) {
    $email_var = filter_var(htmlentities($_POST['login_email']),FILTER_SANITIZE_STRING);
	$data["email_var"]=$email_var;
    $emExist = "SELECT Email FROM signup WHERE Email ='$email_var'";
	$FetchData=$this->Comman_model->get_all_data_by_query($emExist);
    // $emExist->bindParam(':email_var',$email_var,PDO::PARAM_STR);
    // $emExist->execute();
    $emExistCount = count($FetchData);// $emExist->rowCount();
	
    foreach ($FetchData as $postsfetch ) {
        $emxv = $postsfetch['Email'];
    }
			if ($emExistCount > 0) {
				if ($email_var != $emxv) {
					$message = "<p class='alertRed'>".lang('invalid_email_address')."</p>";
					$stop = "1";
					$data["stop"] = "1";
				}

				if ($stop != "1") {
					$emExist = "SELECT Username FROM signup WHERE Email ='$email_var'";
					$FetchData=$this->Comman_model->get_all_data_by_query($emExist);
					// $emExist->bindParam(':email_var',$email_var,PDO::PARAM_STR);
					// $emExist->execute();
					foreach ($FetchData as $postsfetch) {
						$Username = $postsfetch['Username'];
					}
					$forg_id = rand(0,9999999)+time();
					$time = time();
					$data = array(
						'email'   => $email_var,
						'numi'      => $forg_id,
						'time'	=>	$time
					);
					$this->account_model->insert_entry("forg_pass",$data);

					// $signupsql = "INSERT INTO forg_pass (email,numi,time) VALUES( :email_var, :forg_id, :time)";
					// $query = $conn->prepare($signupsql);
					// $query->bindParam(':email_var', $email_var, PDO::PARAM_STR);
					// $query->bindParam(':forg_id', $forg_id, PDO::PARAM_INT);
					// $query->bindParam(':time', $time, PDO::PARAM_INT);
					// $query->execute();

					$to = $email_var;


					$base_url = base_url();  //change this baseurl value as per your file path
					$mail_body = "
					<p>Hi ".$Username.",</p>
					Your password is: <a href='".$base_url."Account/forgot_verifi?veri=$forg_id'><button>click to verify</button></a>
					if is not worked try to past this url in browser ".$base_url."forgot_verifi?veri=$forg_id'
					<p><br />Almaqar</p>
					";


					$this->load->library('email');

					$this->email->from('activate@bumedianbm.com', 'activate');
					$this->email->to($email_var);
					// $this->email->cc('another@another-example.com');
					// $this->email->bcc('them@their-example.com');
					
					$this->email->subject( 'Change your Almaqar currency exchange POS password');
					$this->email->message($mail_body);
					
					//$this->email->send();




					// require 'class/class.phpmailer.php';
					// $mail = new PHPMailer;
					// $mail->IsSMTP();								//Sets Mailer to send message using SMTP
					// $mail->Host = 'mail.bumedianbm.com';		//Sets the SMTP hosts of your Email hosting, this for Godaddy
					// $mail->Port = '25';								//Sets the default SMTP server port
					// $mail->SMTPAuth = true;							//Sets SMTP authentication. Utilizes the Username and Password variables
					// $mail->Username = 'activate@bumedianbm.com';					//Sets SMTP username
					// $mail->Password = 'AlmaqarPos112233!!';					//Sets SMTP password
					// $mail->SMTPSecure = '';							//Sets connection prefix. Options are "", "ssl" or "tls"
					// $mail->setFrom('activate@bumedianbm.com');			//Sets the From email address for the message
					// $mail->FromName = 'Almaqar';					//Sets the From name of the message
					// $mail->AddAddress($email_var);		//Adds a "To" address
					// $mail->WordWrap = 50;							//Sets word wrapping on the body of the message to a given number of characters
					// $mail->IsHTML(true);							//Sets message type to HTML
					// $mail->Subject = 'Change your Almaqar currency exchange POS password';			//Sets the Subject of the message
					// $mail->Body = $mail_body;							//An HTML or plain text message body
					// if($mail->Send())								//Send an Email. Return true on success or false on error
					
					if($this->email->send()) 
					{
						$message = "<p class='alertGreen'>The email has been sent</p>";
					}else{
						$message = "<p class='alertRed'>unexpected error email had not been sent</p>";
					}
				}else{
						
					$message = "<p class='alertRed'>".lang('un_email_not_exist')."</p>";
				}
			}else{
				
				$message = "<p class='alertRed'>".lang('un_email_not_exist')."</p>";
			}
		}
		$data["success"]=$message;
		$this->load->view('account/Forgot_password',$data);
	}
	
	public function logout()
	{
		//update signup online 
		$myid = $_SESSION['id'];
		$online_status = "0";
		
		$data = array(
			'online'   => $typef,
		);
		
		$where=array('id' => $myid);
		$this->Comman_model->update_entry("signup",$data,$where);
		
		///insert entry inandout
		$type= "out";
		$data = array(
			'user_id'   => $myid,
			'type'      => $type
		);
		$this->account_model->insert_entry("inandout",$data);

		
		// $iptdbsqli = "INSERT INTO inandout
		// (user_id,type)
		// VALUES
		// ( :p_user_id, :type)
		// ";
		// $insert_post_toDBi = $conn->prepare($iptdbsqli);
		// $insert_post_toDBi->bindParam(':type', $type,PDO::PARAM_STR);
		// $insert_post_toDBi->bindParam(':p_user_id', $myid,PDO::PARAM_INT);
		// $insert_post_toDBi->execute();
		// destroy session and redirect to main page
		session_destroy();
		session_unset();
		$baseurl=base_url();
		header("location:  $baseurl");

		//$this->load->view('account/login');
	}
	public function dologin()
	{	
		
			LoadLang();
			$username = htmlentities($_POST['un'], ENT_QUOTES);
			$password = htmlentities($_POST['pd'], ENT_QUOTES);
			$req=htmlentities($_POST['req'], ENT_QUOTES);
			
			if($username == null && $password == null){
				
			echo "<p class='alertRed'>".lang('enter_username_to_login')."</p>";
			}elseif ($username == null){
				
				echo "<p class='alertRed'>".lang('enter_username_to_login')."</p>";
			}elseif($password == null){
				echo "<p class='alertRed'>".lang('enter_password_to_login')."</p>";
			}else{
				
				$data=$this->account_model->get_account_by_username($username);
			
				// $chekPwd = $conn->prepare("SELECT * FROM signup WHERE Username = :username OR Email= :email");
				// $chekPwd->bindParam(':email', $username, PDO::PARAM_STR);
				// $chekPwd->bindParam(':username',$username,PDO::PARAM_STR);
				// $chekPwd->execute();

				
				//while ($row = $chekPwd->fetch(PDO::FETCH_ASSOC)) {
				$row = $data;
				//while ($row = $data) {
					$rUsername = $row['Username'];
					$rEmail = $row['Email'];
					
					$rPassword = $row['Password'];
					$rtype = $row['type'];
					$sus = $row['sus'];
				//}
				
				if (isset($_COOKIE['linAtt']) AND $_COOKIE['linAtt'] == $username) {
					echo "<p class='alertRed'>".lang('cannot_login_attempts').".</p>";
				}else{
				// check if user try to login in his username or email
				$email_pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
				if (preg_match($email_pattern, $username)) {
					$un_or_em = $rEmail;
				}else{
					$un_or_em = $rUsername;
				}
				// ========================
				$isverfied=password_verify($password,$rPassword);
				

				if ($un_or_em != $username) {
					echo "<p class='alertRed'>".lang('un_email_not_exist')."!</p>";

				}elseif($rtype == "shop"){
				echo "<p class='alertRed'>".lang('shop_no_access')."!</p>";
				}elseif (!$isverfied) {
					
					$checkAttempts = "SELECT login_attempts FROM signup WHERE Username = '$username'";
					$FetchData=$this->Comman_model->get_all_data_by_query($checkAttempts);
					
					// $checkAttempts->bindParam(':username',$username,PDO::PARAM_STR);
					// $checkAttempts->execute();
					foreach($FetchData as $attR) {
						$login_attempts = $attR['login_attempts'];
					}
					if ($login_attempts < 3) {
						$attempts = $login_attempts + 1;
						$addAttempts = "UPDATE signup SET login_attempts =$attempts WHERE Username='$username'";
						$FetchData=$this->Comman_model->run_query($addAttempts);

						// $addAttempts->bindParam(':username',$username,PDO::PARAM_STR);
						// $addAttempts->bindParam(':attempts',$attempts,PDO::PARAM_INT);
						// $addAttempts->execute();
					}elseif ($login_attempts >= 3) {
						$attempts = 0;
						$addAttempts = $conn->prepare("UPDATE signup SET login_attempts =$attempts WHERE Username='$username'");
						$FetchData=$this->Comman_model->run_query($addAttempts);
						// $addAttempts->bindParam(':username',$username,PDO::PARAM_STR);
						// $addAttempts->bindParam(':attempts',$attempts,PDO::PARAM_INT);
						// $addAttempts->execute();
						setcookie("linAtt", "$username", time() + (60 * 15), '/');
					}
					$LoginTry = 3 - $login_attempts;
					echo "<p class='alertRed'>".lang('password_incorrect_you_have')." $LoginTry ".lang('attempts_to_login')."</p>";

				}elseif($sus == "1"){
				echo "<p class='alertRed'>".lang('sus_msg')."!</p>";
				}else{

				// $loginsql = "SELECT * FROM signup WHERE (Username= :username OR Email= :email) AND Password= :rPassword";
				// $query = $conn->prepare($loginsql);
				// $query->bindParam(':username', $username, PDO::PARAM_STR);
				// $query->bindParam(':email', $username, PDO::PARAM_STR);
				// $query->bindParam(':rPassword', $rPassword, PDO::PARAM_STR);
				// $query->execute();
				$query=$this->account_model->check_login($username,$rPassword);
				
				$num = $query->num_rows();
				//$query->row_array
				if($num == 0){
					
					echo "<p class='alertRed'>".lang('un_and_pwd_incorrect')."!</p>";
				}else{
					$_SESSION['attempts'] = 0;
					//include ("GeT_login_WhileFetch.php");
					
				    
					$this->GetLoginWhileFetch($query,$req);
					echo "Welcome...";
				}
				}
				}
			}
			//$conn = null;
	}

	function GetLoginWhileFetch($query,$req){
		$row_fetch = $query->row_array();
	//	print_r($row_fetch);exit;
		// while($row_fetch = $query->row_array())
		// {
			//print_r($row_fetch);
			$row_id = $row_fetch['id'];
			$row_fullname = $row_fetch['phone'];
			$row_username = $row_fetch['Username'];
			$row_email = $row_fetch['Email'];
			$row_password = $row_fetch['Password'];
			
			$row_user_photo = $row_fetch['Userphoto'];
			
			$row_user_cover_photo = $row_fetch['user_cover_photo'];
			$row_school = $row_fetch['school'];
			$row_work = $row_fetch['work'];
			$row_work0 = $row_fetch['work0'];
			$row_country = $row_fetch['country'];
			
			$row_birthday = $row_fetch['birthday'];
			$row_verify = $row_fetch['verify'];
			$hide_trsh = $row_fetch['hide_trsh'];
			$row_website = $row_fetch['website'];
			$row_bio = $row_fetch['bio'];
			$row_admin = $row_fetch['admin'];
			$row_gender = $row_fetch['gender'];
			
			$row_profile_pic_border = $row_fetch['profile_pic_border'];
			$row_language = $row_fetch['language'];
			$row_online = $row_fetch['online'];
			$name_var = $row_fetch['name'];
			
			$phone_no = $row_fetch['phone_no'];
			$email_ad = $row_fetch['email_ad'];
			$website = $row_fetch['website'];
			$package_chose = $row_fetch['package_chose'];
			$type = $row_fetch['type'];
			
			$boss_id = $row_fetch['boss_id'];
			$shop_id = $row_fetch['shop_id'];
			$address_var = $row_fetch['address'];
			$nex_pay = $row_fetch['nex_pay'];
			$accou_stu = $row_fetch['accou_stu'];
			
			$cash = $row_fetch['cash'];
			$chak = $row_fetch['chak'];
			$cards = $row_fetch['cards'];
			
			$transfar = $row_fetch['transfar'];
			$invest = $row_fetch['invest'];
			$trash = $row_fetch['trash'];
			$Treasury = $row_fetch['Treasury'];
			$buythings = $row_fetch['buythings'];
			$Profit = $row_fetch['Profit'];
			
			$bank = $row_fetch['bank'];
			$trsh = $row_fetch['trsh'];
			$mode = $row_fetch['mode'];
			$title = $row_fetch['title'];
			$steps = $row_fetch['steps'];
			$user_email_status = $row_fetch['user_email_status'];
			
		//}
		
			// $vpsql = "SELECT package,Username FROM signup WHERE id=:sid";
			// $view_postsi = $conn->prepare($vpsql);
			// $view_postsi->bindParam(':sid', $boss_id, PDO::PARAM_INT);
			// $view_postsi->execute();
			$postsfetch=$this->account_model->get_user_package($boss_id);
			
			///while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
			  $package = $postsfetch['package'];
			  
			  
			//}

			$typef = "in";
			if($req == "login_code"){

				$this->user_id    = $_POST['title']; // please read the below note
                $this->type  = $_POST['content'];
                $this->date     = time();
 				$data = array(
                    'user_id'   => $typef,
                    'type'      => $row_id
                );
				$this->account_model->insert_entry("inandout",$data);

				//   $iptdbsqli = "INSERT INTO inandout
				//   (user_id,type)
				//   VALUES
				//   ( :p_user_id, :type)
				//   ";
				//   $insert_post_toDBi = $conn->prepare($iptdbsqli);
				//   $insert_post_toDBi->bindParam(':type', $typef,PDO::PARAM_STR);
				//   $insert_post_toDBi->bindParam(':p_user_id', $row_id,PDO::PARAM_INT);
				//   $insert_post_toDBi->execute();
			}
			$_SESSION['Email'] = $row_email;
			$_SESSION['id'] = $row_id;
			$_SESSION['phone'] = $row_fullname;
			$_SESSION['Username'] = $row_username;
			
			$_SESSION['Password'] = $row_password;
			$_SESSION['shop_id'] = $shop_id;
			$_SESSION['type'] = $type;
			$_SESSION['boss_id'] = $boss_id;
			$_SESSION['Userphoto'] = $row_user_photo;
			$_SESSION['uCoverPhoto'] = $row_user_cover_photo;
			$_SESSION['school'] = $row_school;
			$_SESSION['nex_pay'] = $nex_pay;
			$_SESSION['accou_stu'] = $accou_stu;
			$_SESSION['package_chose'] = $package_chose;
			$_SESSION['work'] = $row_work;
			$_SESSION['work0'] = $row_work0;
			$_SESSION['steps'] = $steps;
			$_SESSION['country'] = $row_country;
			$_SESSION['birthday'] = $row_birthday;
			$_SESSION['verify'] = $row_verify;
			$_SESSION['website'] = $row_website;
			$_SESSION['bio'] = $row_bio;
			$_SESSION['hide_trsh'] = $hide_trsh;
			$_SESSION['name'] = $name_var;
			$_SESSION['phone_no'] = $phone_no;
			$_SESSION['email_ad'] = $email_ad;
			$_SESSION['website'] = $website;
			$_SESSION['address'] = $address_var;
			$_SESSION['admin'] = $row_admin;
			$_SESSION['cash'] = $cash;
			$_SESSION['chak'] = $chak;
			$_SESSION['cards'] = $cards;
			$_SESSION['transfar'] = $transfar;
			$_SESSION['invest'] = $invest;
			$_SESSION['trash'] = $trash;
			$_SESSION['Treasury'] = $Treasury;
			$_SESSION['buythings'] = $buythings;
			$_SESSION['Profit'] = $Profit;
			$_SESSION['bank'] = $bank;
			$_SESSION['trsh'] = $trsh;
			$_SESSION['mode'] = $mode;
			$_SESSION['package'] = $package;
			$_SESSION['title_h'] = $title;
			$_SESSION['user_email_status'] = $user_email_status;
			if ($row_gender == "0" or $row_gender == "Male") {
			$_SESSION['gender'] = "Male";
			}elseif ($row_gender == "1" or $row_gender == "Female") {
			$_SESSION['gender'] = "Female";
			}
			$_SESSION['profile_pic_border'] = $row_profile_pic_border;
			$_SESSION['language'] = $row_language;
			$_SESSION['online'] = $row_online;
	}


	public function register()
	{
		$url=base_url()."Dashboard";
		loginRedirect($url);
		$phones=LoadPhoneCodes();
		$data["phones"]=$phones;
		$this->load->view('account/register',$data);
	}

	public function doregister(){
	// ============================= [ Signup code ] =============================		
			LoadLang();
			//////////////
			$user_activation_code = md5(rand());
			$signup_id = (rand(0,99999).time()) + time();
			$typ = filter_var(htmlentities($_POST['typ']),FILTER_SANITIZE_STRING);
			$req=htmlentities($_POST['req'], ENT_QUOTES);
			if($typ != "shop"){
			$signup_fullname = "+".filter_var(htmlentities($_POST['phone_code']),FILTER_SANITIZE_NUMBER_INT)."".filter_var(htmlentities($_POST['fn']),FILTER_SANITIZE_STRING);
			}
			
			$signup_username = filter_var(htmlentities($_POST['un']),FILTER_SANITIZE_STRING);
			$signup_email = filter_var(htmlentities($_POST['em']),FILTER_SANITIZE_STRING);
			// =========================== password hashinng ==================================
			$signup_password_var = filter_var(htmlentities($_POST['pd']),FILTER_SANITIZE_STRING);
			$options = array(
				'cost' => 12,
			);
			
			$signup_password = password_hash($signup_password_var, PASSWORD_BCRYPT, $options);
			// ================================================================================
			$signup_cpassword = filter_var(htmlentities($_POST['cpd']),FILTER_SANITIZE_STRING);
			$signup_genderVar = filter_var(htmlentities($_POST['gr']),FILTER_SANITIZE_STRING);
			$shop_id = filter_var(htmlentities($_POST['shop']),FILTER_SANITIZE_STRING);
			
			$signup_language = "العربية";
			
			/*  $url = " https://www.google.com/recaptcha/api/siteverify";
				$data = [
					//secret
				'secret' => "6LdPL-QUAAAAAKTj3nEJ1QTyy7nA5drQb1tD4UC4",
				'response' => $_POST['token'],
				//'remoteip' => $_SERVER['REMOTE_ADDR'],
				];
				$options = array(
				'http' => array(
				'header' => "content-type: application/x-www-form-urlencoded\r\n",
				'method' => 'POST',
				'content' => http_build_query($data)
				));
			
				$context = stream_context_create($options);
				$response = file_get_contents($url, false, $context);
			
				$res = json_decode($response, true);
				if($res['success'] == true){
					echo 'success.';
				}else{
					echo'try again';
				}*/
			
			$mode = "auto";
			$titl = "1";
			//chec usernames
			$exist_username=$this->Comman_model->get_dataCount_where("signup","Username",$signup_username);
			
			// $eunsql = "SELECT * FROM signup WHERE Username=:signup_username";
			// $exist_username = $conn->prepare($eunsql);
			// $exist_username->bindParam(':signup_username', $signup_username, PDO::PARAM_STR);
			// $exist_username->execute();

			$exist_email=$this->Comman_model->get_dataCount_where("signup","Email",$signup_email);
			
			// $eemsql = "SELECT * FROM signup WHERE Email=:signup_email";
			// $exist_email = $conn->prepare($eemsql);
			// $exist_email->bindParam(':signup_email', $signup_email, PDO::PARAM_STR);
			// $exist_email->execute();
			$num_un_ex = $exist_username;//$exist_username->rowCount();
			$num_em_ex = $exist_email;//$exist_email->rowCount();

			if(($signup_fullname == null || $signup_username == null || $signup_email == null || $signup_password == null || $signup_cpassword == null) && $typ != "shop" || ($signup_username == null) && $typ == "shop"){
				echo "<p class='alertRed'>".lang('please_fill_required_fields')."</p>";
			//	echo 'test0';exit;
			}elseif($num_un_ex == 1 && $typ != "shop"){
				
				echo "<p class='alertRed'>".lang('user_already_exist')."</p>";
			}elseif($num_em_ex == 1 && $typ != "shop"){
				//echo 'test8';exit;
					echo "<p class='alertRed'>".lang('email_already_exist')."</p>";
			}elseif((strlen($signup_password_var) < 6 || $signup_password_var == "qwe123()" || $signup_password_var == "Qwe123()") && $typ != "shop"){
				
			echo "
			
			<ul class='alertRed' style='list-style:none;'>
				<li><b>".lang('password_not_allowed')." :</b></li>
				<li><span class='fa fa-times'></span> ".lang('signup_password_should_be_1').".</li>
				<li><span class='fa fa-times'></span> ".lang('signup_password_should_be_2').".</li>
				<li><span class='fa fa-times'></span> ".lang('signup_password_should_be_3').".</li>
				<li><span class='fa fa-times'></span> ".lang('signup_password_should_be_4').".</li>
			</ul>";}elseif($signup_password_var != $signup_cpassword){
				echo "<p class='alertRed'>".lang('password_not_match_with_cpassword')."</p>";
			}elseif((strpos($signup_username, ' ') !== false || preg_match('/[\'^£$%&*()}{@#~?><>,.|=+¬-]/', $signup_username) || !preg_match('/[A-Za-z0-9]+/', $signup_username)) && $typ != "shop") {
				echo "
				<ul class='alertRed' style='list-style:none;'>
					<li><b>".lang('username_not_allowed')." :</b></li>
					<li><span class='fa fa-times'></span> ".lang('signup_username_should_be_1').".</li>
					<li><span class='fa fa-times'></span> ".lang('signup_username_should_be_2').".</li>
					<li><span class='fa fa-times'></span> ".lang('signup_username_should_be_3').".</li>
					<li><span class='fa fa-times'></span> ".lang('signup_username_should_be_4').".</li>
					<li><span class='fa fa-times'></span> ".lang('signup_username_should_be_5').".</li>
				</ul>";
			}elseif (!filter_var($signup_email, FILTER_VALIDATE_EMAIL) && $typ != "shop") {
				echo "<p class='alertRed'>".lang('invalid_email_address')."</p>";
			}elseif ((!preg_match('/[0-9]/', $signup_fullname) || strlen($signup_fullname) < 6) && $typ != "shop") {
				echo "<p class='alertRed'>".lang('invalid_phone_number')."</p>";
			}else
		{
			
			$yy = date('Y');
			$mm = date('m');
			$by = $mm+1;
			$dd = date('d');
			$yv = $yy+1;
			if($mm == "12"){
				$ergh = "$dd-01-$yv";
			}else{
				$ergh = "$dd-$by-$yy";
			}
			$ghys = "$dd-$mm-$yy";
			$fbm = $dd*$by/$yy;
			$verifi_now = "verified";
			$nex_pay = $ergh;
			$boss = "boss";
				// If who signup is the first user, make it [main admin]
				$recordCounts=$this->Comman_model->get_all_dataCounts_by_query("SELECT id FROM signup");
				// $cusers_q_sql = "SELECT id FROM signup";
				// $cusers_q = $conn->prepare($cusers_q_sql);
				// $cusers_q->execute();
				
				$cusers_q_num_rows =$recordCounts;
				
				if ($signup_genderVar == "sign") {
				$signup_admin = "0";


				$data = array(
					'boss_id'   => $signup_id,
					'shop_id'      => $signup_id,
					'id'      => $signup_id,
					'phone'      => $signup_fullname,
					'Username'      => $signup_username,
					'Email'      => $signup_email,
					'Password'      => $signup_password,
					'language'      => $signup_language,
					'admin'      => $signup_admin,
					'type'      => $boss,
					'mode'      => $mode,
					'nex_pay'      => $nex_pay,
					'accou_stu'      => $ghys,
					'expir'      => $fbm,
					'title'      => $titl,
					'user_activation_code'      => $user_activation_code

				);
				
				$this->account_model->insert_entry("signup",$data);
			
				// $signupsql = "INSERT INTO signup (boss_id,shop_id,id,phone,Username,Email,Password,language,admin,type,mode,nex_pay,accou_stu,expir,title,user_activation_code)
				// VALUES(:boss_id,:shop_id, :signup_id, :signup_fullname, :signup_username, :signup_email, :signup_password, :signup_language,:signup_admin, :boss,:mode,:nex_pay,:ghys,:fbm,:titl,:user_activation_code)";
				// $query = $conn->prepare($signupsql);
				// $query->bindParam(':signup_id', $signup_id, PDO::PARAM_INT);
				// $query->bindParam(':signup_fullname', $signup_fullname, PDO::PARAM_STR);
				// $query->bindParam(':ghys', $ghys, PDO::PARAM_STR);
				// $query->bindParam(':fbm', $fbm, PDO::PARAM_STR);
				// $query->bindParam(':mode', $mode, PDO::PARAM_STR);
				// $query->bindParam(':signup_username', $signup_username, PDO::PARAM_STR);
				// $query->bindParam(':signup_email', $signup_email, PDO::PARAM_STR);
				// $query->bindParam(':nex_pay', $nex_pay, PDO::PARAM_STR);
				// $query->bindParam(':user_activation_code', $user_activation_code, PDO::PARAM_STR);
				// $query->bindParam(':signup_password', $signup_password, PDO::PARAM_STR);
				// $query->bindParam(':signup_language', $signup_language, PDO::PARAM_STR);
				// $query->bindParam(':signup_admin', $signup_admin, PDO::PARAM_INT);
				// $query->bindParam(':boss', $boss, PDO::PARAM_INT);
				// $query->bindParam(':shop_id', $signup_id, PDO::PARAM_INT);
				// $query->bindParam(':boss_id', $signup_id, PDO::PARAM_INT);
				// $query->bindParam(':titl', $titl, PDO::PARAM_INT);
				// $query->execute();
				$une = "1";
				$bank_per = "2000";


			
				$bankdata =array(
				
					array(
						'boss_id'   => $signup_id,
						'bank_per'      => $bank_per,
						'usecar'      => $une,
						'usecha'      => $une,
						'bank_nam'      => 'مصرف الجمهورية'
					),
					array(
						'boss_id'   => $signup_id,
						'bank_per'      => $bank_per,
						'usecar'      => $une,
						'usecha'      => $une,
						'bank_nam'      => 'مصرف الوحدة'
					),
					array(
						'boss_id'   => $signup_id,
						'bank_per'      => $bank_per,
						'usecar'      => $une,
						'usecha'      => $une,
						'bank_nam'      => 'مصرف الصحاري'
					),
					array(
						'boss_id'   => $signup_id,
						'bank_per'      => $bank_per,
						'usecar'      => $une,
						'usecha'      => $une,
						'bank_nam'      => 'مصرف شمال أفريقيا'
					),
					array(
						'boss_id'   => $signup_id,
						'bank_per'      => $bank_per,
						'usecar'      => $une,
						'usecha'      => $une,
						'bank_nam'      => 'مصرف الأمان'
					),
					array(
						'boss_id'   => $signup_id,
						'bank_per'      => $bank_per,
						'usecar'      => $une,
						'usecha'      => $une,
						'bank_nam'      =>'مصرف التنميه'
					),
					array(
						'boss_id'   => $signup_id,
						'bank_per'      => $bank_per,
						'usecar'      => $une,
						'usecha'      => $une,
						'bank_nam'      => 'مصرف اليقين'
					),
					array(
						'boss_id'   => $signup_id,
						'bank_per'      => $bank_per,
						'usecar'      => $une,
						'usecha'      => $une,
						'bank_nam'      => 'مصرف الواحه'
					),
					array(
						'boss_id'   => $signup_id,
						'bank_per'      => $bank_per,
						'usecar'      => $une,
						'usecha'      => $une,
						'bank_nam'      => 'مصرف الوفاء'
					),
					array(
						'boss_id'   => $signup_id,
						'bank_per'      => $bank_per,
						'usecar'      => $une,
						'usecha'      => $une,
						'bank_nam'      => 'مصرف النوران'
					),
					array(
						'boss_id'   => $signup_id,
						'bank_per'      => $bank_per,
						'usecar'      => $une,
						'usecha'      => $une,
						'bank_nam'      => 'مصرف الخليج الأول الليبي'
					),
					array(
						'boss_id'   => $signup_id,
						'bank_per'      => $bank_per,
						'usecar'      => $une,
						'usecha'      => $une,
						'bank_nam'      => 'مصرف التجارة والتنميه'
					)
					
					
				);
			
				$this->account_model->insert_multiple_entries("bank",$bankdata);
			
				// $iptdbsqli = "INSERT INTO bank
				// (boss_id,bank_per,usecar,usecha,bank_nam)
				// VALUES
				// (:boss_id, :bank_per, :usecar, :usecha, 'مصرف الجمهورية'),
				// (:boss_id, :bank_per, :usecar, :usecha, 'مصرف الوحدة'),
				// (:boss_id, :bank_per, :usecar, :usecha, 'مصرف الصحاري'),
				// (:boss_id, :bank_per, :usecar, :usecha, 'مصرف شمال أفريقيا'),
				// (:boss_id, :bank_per, :usecar, :usecha, 'مصرف الأمان'),
				// (:boss_id, :bank_per, :usecar, :usecha, 'مصرف التنميه'),
				// (:boss_id, :bank_per, :usecar, :usecha, 'مصرف اليقين'),
				// (:boss_id, :bank_per, :usecar, :usecha, 'مصرف الواحه'),
				// (:boss_id, :bank_per, :usecar, :usecha, 'مصرف الوفاء'),
				// (:boss_id, :bank_per, :usecar, :usecha, 'مصرف النوران'),
				// (:boss_id, :bank_per, :usecar, :usecha, 'مصرف الخليج الأول الليبي'),
				// (:boss_id, :bank_per, :usecar, :usecha, 'مصرف التجارة والتنميه')
				// ";
				// $insert_post_toDBi = $conn->prepare($iptdbsqli);
				// $insert_post_toDBi->bindParam(':boss_id', $signup_id,PDO::PARAM_INT);
				// $insert_post_toDBi->bindParam(':usecar', $une,PDO::PARAM_INT);
				// $insert_post_toDBi->bindParam(':usecha', $une,PDO::PARAM_INT);
				// $insert_post_toDBi->bindParam(':bank_per', $bank_per,PDO::PARAM_INT);
				// $insert_post_toDBi->execute();
				}else{
				
			if($signup_genderVar == "user"){
			$boss = $typ;
			$boss_id = $_SESSION['boss_id'];
			if($typ == "user"){
			$qry="SELECT id FROM signup WHERE boss_id=$boss_id AND type='user'";
			$recordCounts=$this->Comman_model->get_all_dataCounts_by_query($qry);
			// $vpsql = "SELECT id FROM signup WHERE boss_id=:sid AND type='user'";
			// $view_postsi = $conn->prepare($vpsql);
			// $view_postsi->bindParam(':sid', $boss_id, PDO::PARAM_INT);
			// $view_postsi->execute();
			$numcosxz = $recordCounts;//$view_postsi->rowCount();
			if($_SESSION['package'] == "2" && $numcosxz <= "3" || $_SESSION['package'] == "3" && $numcosxz <= "10" || $_SESSION['package'] == "4" || $_SESSION['admin'] == "1")
			{
				$data = array(
					'boss_id'   => $boss_id,
					'shop_id'      => $shop_id,
					'id'      => $signup_id,
					'phone'      => $signup_fullname,
					'Username'      => $signup_username,
					'Email'      => $signup_email,
					'Password'      => $signup_password,
					'language'      => $signup_language,
					'admin'      => $signup_admin,
					'type'      => $boss,
					'mode'      => $mode,
					'title'      => $titl,
					'user_email_status'      => $verifi_now

				);
				$this->account_model->insert_entry("signup",$data);
				
			// $signupsql = "INSERT INTO signup (boss_id,shop_id,id,phone,Username,Email,Password,language,type,mode,title,user_email_status)
			// VALUES(:boss_id, :shop_id, :signup_id, :signup_fullname, :signup_username, :signup_email, :signup_password, :signup_language, :boss, :mode, :titl, :verifi_now)";
			// $query = $conn->prepare($signupsql);
			// $query->bindParam(':signup_id', $signup_id, PDO::PARAM_INT);
			// $query->bindParam(':signup_fullname', $signup_fullname, PDO::PARAM_STR);
			// $query->bindParam(':signup_username', $signup_username, PDO::PARAM_STR);
			// $query->bindParam(':signup_email', $signup_email, PDO::PARAM_STR);
			// $query->bindParam(':signup_password', $signup_password, PDO::PARAM_STR);
			// $query->bindParam(':signup_language', $signup_language, PDO::PARAM_STR);
			// $query->bindParam(':verifi_now', $verifi_now, PDO::PARAM_STR);
			// $query->bindParam(':boss', $boss, PDO::PARAM_INT);
			// $query->bindParam(':boss_id', $boss_id, PDO::PARAM_INT);
			// $query->bindParam(':shop_id', $shop_id, PDO::PARAM_INT);
			// $query->bindParam(':mode', $mode, PDO::PARAM_INT);
			// $query->bindParam(':titl', $titl, PDO::PARAM_INT);
			// $query->execute();
			}
			}elseif($typ == "shop"){
				//echo 'test243';exit;
			$qry="SELECT id FROM signup WHERE boss_id=$boss_id AND type='shop'";
			$recordCounts=$this->Comman_model->get_all_dataCounts_by_query($qry);
			// $vpsql = "SELECT id FROM signup WHERE boss_id=:sid AND type='shop'";
			// $view_postsi = $conn->prepare($vpsql);
			// $view_postsi->bindParam(':sid', $boss_id, PDO::PARAM_INT);
			// $view_postsi->execute();
			$numcosu = $recordCounts; /// $view_postsi->rowCount();
			if($_SESSION['package'] == "2" && $numcosu <= "1" || $_SESSION['package'] == "3" && $numcosu <= "3" || $_SESSION['package'] == "4" || $_SESSION['admin'] == "1")
			{
				$data = array(
					'boss_id'   => $boss_id,
					//'shop_id'      => $shop_id,
					'id'      => $signup_id,
					'phone'      => $signup_fullname,
					'Username'      => $signup_username,
					'Email'      => $signup_email,
					'Password'      => $signup_password,
					'language'      => $signup_language,
					'admin'      => $signup_admin,
					'type'      => $boss,
					'mode'      => $mode,
					'title'      => $titl,
					'user_email_status'      => $verifi_now

				);
				$this->account_model->insert_entry("signup",$data);


			// $signupsql = "INSERT INTO signup (boss_id, id,phone,Username,Email,Password,language,type,mode,title,user_email_status)
			// VALUES(:boss_id, :signup_id, :signup_fullname, :signup_username, :signup_email, :signup_password, :signup_language, :boss, :mode, :titl, :verifi_now)";
			// $query = $conn->prepare($signupsql);
			// $query->bindParam(':signup_id', $signup_id, PDO::PARAM_INT);
			// $query->bindParam(':signup_fullname', $signup_fullname, PDO::PARAM_STR);
			// $query->bindParam(':signup_username', $signup_username, PDO::PARAM_STR);
			// $query->bindParam(':signup_email', $signup_email, PDO::PARAM_STR);
			// $query->bindParam(':verifi_now', $verifi_now, PDO::PARAM_STR);
			// $query->bindParam(':signup_password', $signup_password, PDO::PARAM_STR);
			// $query->bindParam(':signup_language', $signup_language, PDO::PARAM_STR);
			// $query->bindParam(':boss', $boss, PDO::PARAM_INT);
			// $query->bindParam(':mode', $mode, PDO::PARAM_INT);
			// $query->bindParam(':boss_id', $boss_id, PDO::PARAM_INT);
			// $query->bindParam(':titl', $titl, PDO::PARAM_INT);
			// $query->execute();
			}else{
				
			$fsh = "<p class='alertRed'>".lang('allowed_banks');
			if($_SESSION['package'] == "1"){
			$fsj ="0";
			}elseif($_SESSION['package'] == "0"){
			$fsj ="0";
			}elseif($_SESSION['package'] == "2"){
			$fsj ="1";
			}elseif($_SESSION['package'] == "3"){
			$fsj = "3";
			}
			$fbh = lang('shop')."</p>";
				echo "$fsh $fsj $fbh";
			}
			}elseif($typ == "admin"){
			$qry="SELECT id FROM signup WHERE boss_id=$boss_id AND type='user'";
			$recordCounts = $this->Comman_model->get_all_dataCounts_by_query($qry);

			// $vpsql = "SELECT id FROM signup WHERE boss_id=:sid AND type='user'";
			// $view_postsi = $conn->prepare($vpsql);
			// $view_postsi->bindParam(':sid', $boss_id, PDO::PARAM_INT);
			// $view_postsi->execute();
			$numcoszh = $recordCounts;//$view_postsi->rowCount();
			
			if($_SESSION['package'] == "2" && $numcoszh <= "3" || $_SESSION['package'] == "3" && $numcoszh <= "10" || $_SESSION['package'] == "4" || $_SESSION['admin'] == "1")
			{
				$data = array(
					'boss_id'   => $boss_id,
					'shop_id'      => $shop_id,
					'id'      => $signup_id,
					'phone'      => $signup_fullname,
					'Username'      => $signup_username,
					'Email'      => $signup_email,
					'Password'      => $signup_password,
					'language'      => $signup_language,
					'admin'      => $signup_admin,
					'type'      => $boss,
					'mode'      => $mode,
					'title'      => $titl,
					'user_email_status'      => $verifi_now
	
				);
				$this->account_model->insert_entry("signup",$data);
	
			// $signupsql = "INSERT INTO signup (boss_id,shop_id,id,phone,Username,Email,Password,language,type,mode,title,user_email_status)
			// VALUES(:boss_id, :shop_id, :signup_id, :signup_fullname, :signup_username, :signup_email, :signup_password, :signup_language, :boss, :mode, :titl, :verifi_now)";
			// $query = $conn->prepare($signupsql);
			// $query->bindParam(':signup_id', $signup_id, PDO::PARAM_INT);
			// $query->bindParam(':signup_fullname', $signup_fullname, PDO::PARAM_STR);
			// $query->bindParam(':signup_username', $signup_username, PDO::PARAM_STR);
			// $query->bindParam(':signup_email', $signup_email, PDO::PARAM_STR);
			// $query->bindParam(':signup_password', $signup_password, PDO::PARAM_STR);
			// $query->bindParam(':signup_language', $signup_language, PDO::PARAM_STR);
			// $query->bindParam(':verifi_now', $verifi_now, PDO::PARAM_STR);
			// $query->bindParam(':shop_id', $shop_id, PDO::PARAM_INT);
			// $query->bindParam(':mode', $mode, PDO::PARAM_INT);
			// $query->bindParam(':boss', $boss, PDO::PARAM_INT);
			// $query->bindParam(':boss_id', $boss_id, PDO::PARAM_INT);
			// $query->bindParam(':titl', $titl, PDO::PARAM_INT);
			// $query->execute();
			}
			}else{
			
				$data = array(
					'boss_id'   => $signup_id,
					'shop_id'      => $signup_id,
					'id'      => $signup_id,
					'phone'      => $signup_fullname,
					'Username'      => $signup_username,
					'Email'      => $signup_email,
					'Password'      => $signup_password,
					'language'      => $signup_language,
					//'admin'      => $signup_admin,
					'type'      => $boss,
					'mode'      => $mode,
					'nex_pay' => $ergh,
					'accou_stu' => $ghys,
					'expir' => $fbm,
					'title'      => $titl,
					'user_email_status'      => $verifi_now
	
				);
				$this->account_model->insert_entry("signup",$data);

				
				// $signupsql = "INSERT INTO signup (boss_id,shop_id,id,phone,Username,Email,Password,language,type,mode,
				// nex_pay,accou_stu,expir,title,user_email_status)
				// VALUES(:boss_id, :shop_id, :signup_id, :signup_fullname, :signup_username, :signup_email, 
				// :signup_password, :signup_language, :boss, :mode, :nex_pay, :ghys, :fbm, :titl, :verifi_now)";
				// $query = $conn->prepare($signupsql);
				// $query->bindParam(':signup_id', $signup_id, PDO::PARAM_INT);
				// $query->bindParam(':signup_fullname', $signup_fullname, PDO::PARAM_STR);
				// $query->bindParam(':signup_username', $signup_username, PDO::PARAM_STR);
				// $query->bindParam(':ghys', $ghys, PDO::PARAM_STR);
				// $query->bindParam(':fbm', $fbm, PDO::PARAM_STR);
				// $query->bindParam(':signup_email', $signup_email, PDO::PARAM_STR);
				// $query->bindParam(':nex_pay', $ergh, PDO::PARAM_STR);
				// $query->bindParam(':mode', $mode, PDO::PARAM_STR);
				// $query->bindParam(':verifi_now', $verifi_now, PDO::PARAM_STR);
				// $query->bindParam(':signup_password', $signup_password, PDO::PARAM_STR);
				// $query->bindParam(':signup_language', $signup_language, PDO::PARAM_STR);
				// $query->bindParam(':boss', $boss, PDO::PARAM_INT);
				// $query->bindParam(':boss_id', $signup_id, PDO::PARAM_INT);
				// $query->bindParam(':shop_id', $signup_id, PDO::PARAM_INT);
				// $query->bindParam(':titl', $titl, PDO::PARAM_INT);
				// $query->execute();
			}
				}
			}
			//===========================================[email send]======================================================================
			if ($signup_genderVar == "sign") 
			{
				$base_url = base_url();/// "http://currency.bumedianbm.com/";  //change this baseurl value as per your file path
				$mail_body = "
				<p>Hi ".$_POST['user_name'].",</p>
				<p>Please Open this link to verified your email address - ".$base_url."Account/email_check?activation_code=".$user_activation_code."
				<p>Best Regards,<br />Almaqar</p>
				";


				////
				// $config = Array(
				// 	'protocol' => 'smtp',
				// 	'smtp_host' => 'mail.bumedianbm.com',
				// 	'smtp_port' => 25,
				// 	'smtp_user' => 'activate@bumedianbm.com',
				// 	'smtp_pass' => 'AlmaqarPos112233!!',
				// 	'mailtype'  => 'html', 
				// 	'charset'   => 'iso-8859-1'
				// );
				// $this->load->library('email', $config);
				// $this->email->set_newline("\r\n");
				
				// // Set to, from, message, etc.
				// $this->email->from('activate@bumedianbm.com', 'Almaqar');
				// $this->email->to($signup_email);
				// $this->email->cc('another@another-example.com');
				// $this->email->bcc('them@their-example.com');

				// $this->email->subject('Email Verification');	
				// $this->email->message($mail_body);
				// $result = $this->email->send();

				$result = SendEmail('Email Verification',$signup_email,$mail_body);
				
				
				if($result){
					$message = '<label class="text-success">Register Done, Please check your mail.</label>';
				}



				// require '../class/class.phpmailer.php';
				// $mail = new PHPMailer;
				// $mail->IsSMTP();								//Sets Mailer to send message using SMTP
				// $mail->Host = 'mail.bumedianbm.com';		//Sets the SMTP hosts of your Email hosting, this for Godaddy
				// $mail->Port = '25';								//Sets the default SMTP server port
				// $mail->SMTPAuth = true;							//Sets SMTP authentication. Utilizes the Username and Password variables
				// $mail->Username = 'activate@bumedianbm.com';					//Sets SMTP username
				// $mail->Password = 'AlmaqarPos112233!!';					//Sets SMTP password
				// $mail->SMTPSecure = '';							//Sets connection prefix. Options are "", "ssl" or "tls"
				// $mail->setFrom('activate@bumedianbm.com');			//Sets the From email address for the message
				// $mail->FromName = 'Almaqar';						//Sets the From name of the message
				// $mail->AddAddress($signup_email, $signup_username);		//Adds a "To" address
				// $mail->WordWrap = 50;							//Sets word wrapping on the body of the message to a given number of characters
				// $mail->IsHTML(true);							//Sets message type to HTML
				// $mail->Subject = 'Email Verification';			//Sets the Subject of the message
				// $mail->Body = $mail_body;							//An HTML or plain text message body
				// if($mail->Send())								//Send an Email. Return true on success or false on error
				// {
				// 	$message = '<label class="text-success">Register Done, Please check your mail.</label>';
				// }
			}
			//===================================================================================================================
				// ========================== login code after signup ============================
				// $qry="SELECT * FROM signup WHERE (Username= $signup_username OR Email= $signup_email) AND Password= $signup_password";
				// $recordCounts = $this->Comman_model->get_all_dataCounts_by_query($qry);

				// $loginsql = "SELECT * FROM signup WHERE (Username= :signup_username OR Email= :signup_email) AND Password= :signup_password";
				// $query = $conn->prepare($loginsql);
				// $query->bindParam(':signup_username', $signup_username, PDO::PARAM_STR);
				// $query->bindParam(':signup_email', $signup_email, PDO::PARAM_STR);
				// $query->bindParam(':signup_password', $signup_password, PDO::PARAM_STR);
				// $query->execute();
				$query=$this->account_model->check_login($signup_username,$signup_password);
				$num = $query->num_rows();
			//print_r($query);exit;
				//$num =  $recordCounts;//$query->rowCount();
			if($signup_genderVar != "user"){

				//include ("GeT_login_WhileFetch.php");
				$this->GetLoginWhileFetch($query,$req);
			}
				echo "Done..";
			}
			//$conn = null;
	}

	public function forgot_verifi(){
		$url=base_url()."Dashboard";
		loginRedirect($url);
		LoadLang();	
		$passco = filter_var(htmlentities($_POST['passco']),FILTER_SANITIZE_STRING);
		$pd = filter_var(htmlentities($_POST['pd']),FILTER_SANITIZE_STRING);
		$cpd = filter_var(htmlentities($_POST['cpd']),FILTER_SANITIZE_STRING);
		if (isset($_POST['send_email'])) {
				$emExist ="SELECT email FROM forg_pass WHERE numi ='$passco'";
				$FetchData=$this->Comman_model->get_all_data_by_query($emExist);
				// $emExist->bindParam(':passco',$passco,PDO::PARAM_STR);
				// $emExist->execute();
				foreach ($FetchData as $postsfetch) {
					$email = $postsfetch['email'];
				}
				if($pd == null || $cpd == null){
					$data["success"] = "<p class='alertRed'>".lang('please_fill_required_fields')."</p>";
				}elseif(strlen($pd) < 6){
					$data["success"] = "<p class='alertRed'>".lang('password_short').".</p>";
				}elseif($pd != $cpd){
					$data["success"] = "<p class='alertRed'>".lang('password_not_match_with_cpassword')."</p>";
				
				}else{
					$options = array(
						'cost' => 12,
					);
					$password_var = password_hash($pd, PASSWORD_BCRYPT, $options);
					
					$update_info_sql = "UPDATE signup SET Password= '$password_var' WHERE Email= '$email'";
					$update_info=$this->Comman_model->get_all_data_by_query($update_info_sql);
					// $update_info = $conn->prepare($update_info_sql);
					// $update_info->bindParam(':new_password_var',$password_var,PDO::PARAM_STR);
					// $update_info->bindParam(':email',$email,PDO::PARAM_STR);
					// $update_info->execute();
					$data["success"] = "<p class='alertGreen'>".lang('welcome')."...</p>";
					
					$loginsql = "SELECT * FROM signup WHERE Email= '$email' AND Password= '$password_var' ";

					$FetchData=$this->Comman_model->get_all_data_by_query($loginsql);
					// $query = $conn->prepare($loginsql);
					// $query->bindParam(':signup_email', $email, PDO::PARAM_STR);
					// $query->bindParam(':signup_password', $password_var, PDO::PARAM_STR);
					// $query->execute();
					$num = count($FetchData);// $query->rowCount();
					//include ("includes/GeT_login_WhileFetch.php");



					////////////////////////////////
					foreach($FetchData as $row_fetch){
						//print_r($row_fetch);exit;
					  $row_id = $row_fetch['id'];
					  $row_fullname = $row_fetch['phone'];
					  $row_username = $row_fetch['Username'];
					  $row_email = $row_fetch['Email'];
					  $row_password = $row_fetch['Password'];
					  $row_user_photo = $row_fetch['Userphoto'];
					  $row_user_cover_photo = $row_fetch['user_cover_photo'];
					  $row_school = $row_fetch['school'];
					  $row_work = $row_fetch['work'];
					  $row_work0 = $row_fetch['work0'];
					  $row_country = $row_fetch['country'];
					  $row_birthday = $row_fetch['birthday'];
					  $row_verify = $row_fetch['verify'];
					  $hide_trsh = $row_fetch['hide_trsh'];
					  $row_website = $row_fetch['website'];
					  $row_bio = $row_fetch['bio'];
					  $row_admin = $row_fetch['admin'];
					  $row_gender = $row_fetch['gender'];
					  $row_profile_pic_border = $row_fetch['profile_pic_border'];
					  $row_language = $row_fetch['language'];
					  $row_online = $row_fetch['online'];
					  $name_var = $row_fetch['name'];
					  $phone_no = $row_fetch['phone_no'];
					  $email_ad = $row_fetch['email_ad'];
					  $website = $row_fetch['website'];
					  $package_chose = $row_fetch['package_chose'];
					  $type = $row_fetch['type'];
					  $boss_id = $row_fetch['boss_id'];
					  $shop_id = $row_fetch['shop_id'];
					  $address_var = $row_fetch['address'];
					  $nex_pay = $row_fetch['nex_pay'];
					  $accou_stu = $row_fetch['accou_stu'];
					  $cash = $row_fetch['cash'];
					  $chak = $row_fetch['chak'];
					  $cards = $row_fetch['cards'];
					  $transfar = $row_fetch['transfar'];
					  $invest = $row_fetch['invest'];
					  $trash = $row_fetch['trash'];
					  $Treasury = $row_fetch['Treasury'];
					  $buythings = $row_fetch['buythings'];
					  $Profit = $row_fetch['Profit'];
					  $bank = $row_fetch['bank'];
					  $trsh = $row_fetch['trsh'];
					  $mode = $row_fetch['mode'];
					  $title = $row_fetch['title'];
					  $steps = $row_fetch['steps'];
					  $user_email_status = $row_fetch['user_email_status'];
					  }
					  $vpsql = "SELECT package,Username FROM signup WHERE id=:sid";
					  $view_postsi = $conn->prepare($vpsql);
					  $view_postsi->bindParam(':sid', $boss_id, PDO::PARAM_INT);
					  $view_postsi->execute();
					  while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
						$package = $postsfetch['package'];
					  }
					  $typef = "in";
					  if($req == "login_code"){
							$iptdbsqli = "INSERT INTO inandout
							(user_id,type)
							VALUES
							( :p_user_id, :type)
							";
							$insert_post_toDBi = $conn->prepare($iptdbsqli);
							$insert_post_toDBi->bindParam(':type', $typef,PDO::PARAM_STR);
							$insert_post_toDBi->bindParam(':p_user_id', $row_id,PDO::PARAM_INT);
							$insert_post_toDBi->execute();
					  }
					  $_SESSION['id'] = $row_id;
					  $_SESSION['phone'] = $row_fullname;
					  $_SESSION['Username'] = $row_username;
					  $_SESSION['Email'] = $row_email;
					  $_SESSION['Password'] = $row_password;
					  $_SESSION['shop_id'] = $shop_id;
					  $_SESSION['type'] = $type;
					  $_SESSION['boss_id'] = $boss_id;
					  $_SESSION['Userphoto'] = $row_user_photo;
					  $_SESSION['uCoverPhoto'] = $row_user_cover_photo;
					  $_SESSION['school'] = $row_school;
					  $_SESSION['nex_pay'] = $nex_pay;
					  $_SESSION['accou_stu'] = $accou_stu;
					  $_SESSION['package_chose'] = $package_chose;
					  $_SESSION['work'] = $row_work;
					  $_SESSION['work0'] = $row_work0;
					  $_SESSION['steps'] = $steps;
					  $_SESSION['country'] = $row_country;
					  $_SESSION['birthday'] = $row_birthday;
					  $_SESSION['verify'] = $row_verify;
					  $_SESSION['website'] = $row_website;
					  $_SESSION['bio'] = $row_bio;
					  $_SESSION['hide_trsh'] = $hide_trsh;
					  $_SESSION['name'] = $name_var;
					  $_SESSION['phone_no'] = $phone_no;
					  $_SESSION['email_ad'] = $email_ad;
					  $_SESSION['website'] = $website;
					  $_SESSION['address'] = $address_var;
					  $_SESSION['admin'] = $row_admin;
					  $_SESSION['cash'] = $cash;
					  $_SESSION['chak'] = $chak;
					  $_SESSION['cards'] = $cards;
					  $_SESSION['transfar'] = $transfar;
					  $_SESSION['invest'] = $invest;
					  $_SESSION['trash'] = $trash;
					  $_SESSION['Treasury'] = $Treasury;
					  $_SESSION['buythings'] = $buythings;
					  $_SESSION['Profit'] = $Profit;
					  $_SESSION['bank'] = $bank;
					  $_SESSION['trsh'] = $trsh;
					  $_SESSION['mode'] = $mode;
					  $_SESSION['package'] = $package;
					  $_SESSION['title_h'] = $title;
					  $_SESSION['user_email_status'] = $user_email_status;
					  if ($row_gender == "0" or $row_gender == "Male") {
					  $_SESSION['gender'] = "Male";
					  }elseif ($row_gender == "1" or $row_gender == "Female") {
					  $_SESSION['gender'] = "Female";
					  }
					  $_SESSION['profile_pic_border'] = $row_profile_pic_border;
					  $_SESSION['language'] = $row_language;
					  $_SESSION['online'] = $row_online;
					//////////////////////////////////





					$loginsql = "DELETE FROM forg_pass WHERE email= $email";
					$IsDelete = $this->Comman_model->run_query($loginsql);
					// $query = $conn->prepare($loginsql);
					// $query->bindParam(':signup_email', $email, PDO::PARAM_STR);
					// $query->execute();
					// $conn = null;
					$url = base_url()."Dashboard";
					header("location: $url");
					exit;
				}
		}
		///////////////////////////////////
		$story_id = filter_var(htmlentities($_GET['veri']), FILTER_SANITIZE_NUMBER_INT);
		$data["story_id"]=$story_id;
		$fPosts_sql_sql = "SELECT * FROM forg_pass WHERE numi = '$story_id'";
		$FetchData=$this->Comman_model->get_all_data_by_query($fPosts_sql_sql);
		// $view_posts = $conn->prepare($fPosts_sql_sql);
		// $view_posts->bindParam(':story_id',$story_id,PDO::PARAM_INT);
		// $view_posts->execute();
		$countSaved = count($FetchData);/// $view_posts->rowCount();
		$data["countSaved"]=$countSaved;


		$this->load->view('account/forgot_verifi',$data);
	}


	public  function email_check()
	{
		// $url=base_url()."Dashboard";
		// loginRedirect($url);

		LoadLang();	
		
		$message = '';
		$activation_code_url = $_GET['activation_code'];
		if(isset($_GET['activation_code']))
		{
			$activationCode=$_GET['activation_code'];
			$query = " SELECT * FROM signup WHERE user_activation_code = '$activationCode' ";
			$FetchData=$this->Comman_model->get_all_data_by_query($query);
			$no_of_row =count($FetchData);// $statement->rowCount();
			if($no_of_row > 0){
				$result = $FetchData;
				foreach($result as $row)
				{
					if($row['user_email_status'] == 'not verified'){
						$update_query = "
						UPDATE signup SET user_email_status = 'verified' WHERE user_activation_code = '$activation_code_url'";
						$statement=$this->Comman_model->run_query($update_query);
						// $statement = $conn->prepare($update_query);
						// $statement->execute();
						if(isset($statement)){
							$url=base_url()."Steps?tc=shop";
							header("location: $url");
							$_SESSION['user_email_status'] = "verified";
						}
					}else{
					$url=base_url()."Steps?tc=shop";
					header("location: $url");
					$_SESSION['user_email_status'] = "verified";

					}
				}
			}else{
				$data["message"] = '<label class="text-danger">Invalid Link</label>';
			}
		}
		$this->load->view('account/email_check',$data);
	}
}
