<?php
session_start();
include("../config/connect.php");
$req = filter_var(htmlentities($_POST['req']),FILTER_SANITIZE_STRING);
switch ($req) {
// ============================= [ Login code ] =============================
case 'login_code':
$username = htmlentities($_POST['un'], ENT_QUOTES);
$password = htmlentities($_POST['pd'], ENT_QUOTES);
if($username == null && $password == null){
echo "<p class='alertRed'>".lang('enter_username_to_login')."</p>";
}elseif ($username == null){
    echo "<p class='alertRed'>".lang('enter_username_to_login')."</p>";
}elseif($password == null){
    echo "<p class='alertRed'>".lang('enter_password_to_login')."</p>";
}else{
    $chekPwd = $conn->prepare("SELECT * FROM signup WHERE Username = :username OR Email= :email");
    $chekPwd->bindParam(':email', $username, PDO::PARAM_STR);
    $chekPwd->bindParam(':username',$username,PDO::PARAM_STR);
    $chekPwd->execute();
    while ($row = $chekPwd->fetch(PDO::FETCH_ASSOC)) {
        $rUsername = $row['Username'];
        $rEmail = $row['Email'];
        $rPassword = $row['Password'];
        $rtype = $row['type'];
        $sus = $row['sus'];
    }

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
    if ($un_or_em != $username) {
        echo "<p class='alertRed'>".lang('un_email_not_exist')."!</p>";

    }elseif($rtype == "shop"){
    echo "<p class='alertRed'>".lang('shop_no_access')."!</p>";
    }elseif (!password_verify($password,$rPassword)) {
        $checkAttempts = $conn->prepare("SELECT login_attempts FROM signup WHERE Username = :username");
        $checkAttempts->bindParam(':username',$username,PDO::PARAM_STR);
        $checkAttempts->execute();
        while ($attR = $checkAttempts->fetch(PDO::FETCH_ASSOC)) {
            $login_attempts = $attR['login_attempts'];
        }
        if ($login_attempts < 3) {
            $attempts = $login_attempts + 1;
            $addAttempts = $conn->prepare("UPDATE signup SET login_attempts =:attempts WHERE Username=:username");
            $addAttempts->bindParam(':username',$username,PDO::PARAM_STR);
            $addAttempts->bindParam(':attempts',$attempts,PDO::PARAM_INT);
            $addAttempts->execute();
        }elseif ($login_attempts >= 3) {
            $attempts = 0;
            $addAttempts = $conn->prepare("UPDATE signup SET login_attempts =:attempts WHERE Username=:username");
            $addAttempts->bindParam(':username',$username,PDO::PARAM_STR);
            $addAttempts->bindParam(':attempts',$attempts,PDO::PARAM_INT);
            $addAttempts->execute();
            setcookie("linAtt", "$username", time() + (60 * 15), '/');
        }
        $LoginTry = 3 - $login_attempts;
        echo "<p class='alertRed'>".lang('password_incorrect_you_have')." $LoginTry ".lang('attempts_to_login')."</p>";

    }elseif($sus == "1"){
      echo "<p class='alertRed'>".lang('sus_msg')."!</p>";
    }else{
    $loginsql = "SELECT * FROM signup WHERE (Username= :username OR Email= :email) AND Password= :rPassword";
    $query = $conn->prepare($loginsql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':email', $username, PDO::PARAM_STR);
    $query->bindParam(':rPassword', $rPassword, PDO::PARAM_STR);
    $query->execute();
    $num = $query->rowCount();
    if($num == 0){
        echo "<p class='alertRed'>".lang('un_and_pwd_incorrect')."!</p>";
    }else{
        $_SESSION['attempts'] = 0;
        include ("GeT_login_WhileFetch.php");
        echo "Welcome...";
    }
    }
    }
}
$conn = null;
break;
// ============================= [ Signup code ] =============================
case 'signup_code':

$user_activation_code = md5(rand());
$signup_id = (rand(0,99999).time()) + time();
$typ = filter_var(htmlentities($_POST['typ']),FILTER_SANITIZE_STRING);
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
$eunsql = "SELECT * FROM signup WHERE Username=:signup_username";
$exist_username = $conn->prepare($eunsql);
$exist_username->bindParam(':signup_username', $signup_username, PDO::PARAM_STR);
$exist_username->execute();
$eemsql = "SELECT * FROM signup WHERE Email=:signup_email";
$exist_email = $conn->prepare($eemsql);
$exist_email->bindParam(':signup_email', $signup_email, PDO::PARAM_STR);
$exist_email->execute();
$num_un_ex = $exist_username->rowCount();
$num_em_ex = $exist_email->rowCount();
if(($signup_fullname == null || $signup_username == null || $signup_email == null || $signup_password == null || $signup_cpassword == null) && $typ != "shop" || ($signup_username == null) && $typ == "shop"){
       echo "<p class='alertRed'>".lang('please_fill_required_fields')."</p>";
}elseif($num_un_ex == 1 && $typ != "shop"){
       echo "<p class='alertRed'>".lang('user_already_exist')."</p>";
}elseif($num_em_ex == 1 && $typ != "shop"){
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
}else{
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
    $cusers_q_sql = "SELECT id FROM signup";
    $cusers_q = $conn->prepare($cusers_q_sql);
    $cusers_q->execute();
    $cusers_q_num_rows = $cusers_q->rowCount();
    if ($signup_genderVar == "sign") {
    $signup_admin = "0";
    $signupsql = "INSERT INTO signup (boss_id,shop_id,id,phone,Username,Email,Password,language,admin,type,mode,nex_pay,accou_stu,expir,title,user_activation_code)
    VALUES(:boss_id,:shop_id, :signup_id, :signup_fullname, :signup_username, :signup_email, :signup_password, :signup_language,:signup_admin, :boss,:mode,:nex_pay,:ghys,:fbm,:titl,:user_activation_code)";
    $query = $conn->prepare($signupsql);
    $query->bindParam(':signup_id', $signup_id, PDO::PARAM_INT);
    $query->bindParam(':signup_fullname', $signup_fullname, PDO::PARAM_STR);
    $query->bindParam(':ghys', $ghys, PDO::PARAM_STR);
    $query->bindParam(':fbm', $fbm, PDO::PARAM_STR);
    $query->bindParam(':mode', $mode, PDO::PARAM_STR);
    $query->bindParam(':signup_username', $signup_username, PDO::PARAM_STR);
    $query->bindParam(':signup_email', $signup_email, PDO::PARAM_STR);
    $query->bindParam(':nex_pay', $nex_pay, PDO::PARAM_STR);
    $query->bindParam(':user_activation_code', $user_activation_code, PDO::PARAM_STR);
    $query->bindParam(':signup_password', $signup_password, PDO::PARAM_STR);
    $query->bindParam(':signup_language', $signup_language, PDO::PARAM_STR);
    $query->bindParam(':signup_admin', $signup_admin, PDO::PARAM_INT);
    $query->bindParam(':boss', $boss, PDO::PARAM_INT);
    $query->bindParam(':shop_id', $signup_id, PDO::PARAM_INT);
    $query->bindParam(':boss_id', $signup_id, PDO::PARAM_INT);
    $query->bindParam(':titl', $titl, PDO::PARAM_INT);
    $query->execute();
    $une = "1";
  $bank_per = "2000";
    $iptdbsqli = "INSERT INTO bank
    (boss_id,bank_per,usecar,usecha,bank_nam)
    VALUES
    (:boss_id, :bank_per, :usecar, :usecha, 'مصرف الجمهورية'),
  (:boss_id, :bank_per, :usecar, :usecha, 'مصرف الوحدة'),
  (:boss_id, :bank_per, :usecar, :usecha, 'مصرف الصحاري'),
  (:boss_id, :bank_per, :usecar, :usecha, 'مصرف شمال أفريقيا'),
  (:boss_id, :bank_per, :usecar, :usecha, 'مصرف الأمان'),
  (:boss_id, :bank_per, :usecar, :usecha, 'مصرف التنميه'),
  (:boss_id, :bank_per, :usecar, :usecha, 'مصرف اليقين'),
  (:boss_id, :bank_per, :usecar, :usecha, 'مصرف الواحه'),
  (:boss_id, :bank_per, :usecar, :usecha, 'مصرف الوفاء'),
  (:boss_id, :bank_per, :usecar, :usecha, 'مصرف النوران'),
  (:boss_id, :bank_per, :usecar, :usecha, 'مصرف الخليج الأول الليبي'),
  (:boss_id, :bank_per, :usecar, :usecha, 'مصرف التجارة والتنميه')
    ";
    $insert_post_toDBi = $conn->prepare($iptdbsqli);
    $insert_post_toDBi->bindParam(':boss_id', $signup_id,PDO::PARAM_INT);
    $insert_post_toDBi->bindParam(':usecar', $une,PDO::PARAM_INT);
    $insert_post_toDBi->bindParam(':usecha', $une,PDO::PARAM_INT);
    $insert_post_toDBi->bindParam(':bank_per', $bank_per,PDO::PARAM_INT);
    $insert_post_toDBi->execute();
    }else{
if($signup_genderVar == "user"){
$boss = $typ;
$boss_id = $_SESSION['boss_id'];
if($typ == "user"){
  $vpsql = "SELECT id FROM signup WHERE boss_id=:sid AND type='user'";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':sid', $boss_id, PDO::PARAM_INT);
  $view_postsi->execute();
$numcosxz = $view_postsi->rowCount();
if($_SESSION['package'] == "2" && $numcosxz <= "3" || $_SESSION['package'] == "3" && $numcosxz <= "10" || $_SESSION['package'] == "4" || $_SESSION['admin'] == "1"){
  $signupsql = "INSERT INTO signup (boss_id,shop_id,id,phone,Username,Email,Password,language,type,mode,title,user_email_status)
  VALUES(:boss_id, :shop_id, :signup_id, :signup_fullname, :signup_username, :signup_email, :signup_password, :signup_language, :boss, :mode, :titl, :verifi_now)";
  $query = $conn->prepare($signupsql);
  $query->bindParam(':signup_id', $signup_id, PDO::PARAM_INT);
  $query->bindParam(':signup_fullname', $signup_fullname, PDO::PARAM_STR);
  $query->bindParam(':signup_username', $signup_username, PDO::PARAM_STR);
  $query->bindParam(':signup_email', $signup_email, PDO::PARAM_STR);
  $query->bindParam(':signup_password', $signup_password, PDO::PARAM_STR);
  $query->bindParam(':signup_language', $signup_language, PDO::PARAM_STR);
  $query->bindParam(':verifi_now', $verifi_now, PDO::PARAM_STR);
  $query->bindParam(':boss', $boss, PDO::PARAM_INT);
  $query->bindParam(':boss_id', $boss_id, PDO::PARAM_INT);
  $query->bindParam(':shop_id', $shop_id, PDO::PARAM_INT);
  $query->bindParam(':mode', $mode, PDO::PARAM_INT);
  $query->bindParam(':titl', $titl, PDO::PARAM_INT);
  $query->execute();
}
}elseif($typ == "shop"){
  $vpsql = "SELECT id FROM signup WHERE boss_id=:sid AND type='shop'";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':sid', $boss_id, PDO::PARAM_INT);
  $view_postsi->execute();
$numcosu = $view_postsi->rowCount();
  if($_SESSION['package'] == "2" && $numcosu <= "1" || $_SESSION['package'] == "0" && $numcosu <= "1" || $_SESSION['package'] == "3" && $numcosu <= "3" || $_SESSION['package'] == "4" || $_SESSION['admin'] == "1"){
  $signupsql = "INSERT INTO signup (boss_id, id,phone,Username,Email,Password,language,type,mode,title,user_email_status)
  VALUES(:boss_id, :signup_id, :signup_fullname, :signup_username, :signup_email, :signup_password, :signup_language, :boss, :mode, :titl, :verifi_now)";
  $query = $conn->prepare($signupsql);
  $query->bindParam(':signup_id', $signup_id, PDO::PARAM_INT);
  $query->bindParam(':signup_fullname', $signup_fullname, PDO::PARAM_STR);
  $query->bindParam(':signup_username', $signup_username, PDO::PARAM_STR);
  $query->bindParam(':signup_email', $signup_email, PDO::PARAM_STR);
  $query->bindParam(':verifi_now', $verifi_now, PDO::PARAM_STR);
  $query->bindParam(':signup_password', $signup_password, PDO::PARAM_STR);
  $query->bindParam(':signup_language', $signup_language, PDO::PARAM_STR);
  $query->bindParam(':boss', $boss, PDO::PARAM_INT);
  $query->bindParam(':mode', $mode, PDO::PARAM_INT);
  $query->bindParam(':boss_id', $boss_id, PDO::PARAM_INT);
  $query->bindParam(':titl', $titl, PDO::PARAM_INT);
  $query->execute();
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
  $vpsql = "SELECT id FROM signup WHERE boss_id=:sid AND type='user'";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':sid', $boss_id, PDO::PARAM_INT);
  $view_postsi->execute();
$numcoszh = $view_postsi->rowCount();
if($_SESSION['package'] == "2" && $numcoszh <= "3" || $_SESSION['package'] == "3" && $numcoszh <= "10" || $_SESSION['package'] == "4" || $_SESSION['admin'] == "1"){
  $signupsql = "INSERT INTO signup (boss_id,shop_id,id,phone,Username,Email,Password,language,type,mode,title,user_email_status)
  VALUES(:boss_id, :shop_id, :signup_id, :signup_fullname, :signup_username, :signup_email, :signup_password, :signup_language, :boss, :mode, :titl, :verifi_now)";
  $query = $conn->prepare($signupsql);
  $query->bindParam(':signup_id', $signup_id, PDO::PARAM_INT);
  $query->bindParam(':signup_fullname', $signup_fullname, PDO::PARAM_STR);
  $query->bindParam(':signup_username', $signup_username, PDO::PARAM_STR);
  $query->bindParam(':signup_email', $signup_email, PDO::PARAM_STR);
  $query->bindParam(':signup_password', $signup_password, PDO::PARAM_STR);
  $query->bindParam(':signup_language', $signup_language, PDO::PARAM_STR);
  $query->bindParam(':verifi_now', $verifi_now, PDO::PARAM_STR);
  $query->bindParam(':shop_id', $shop_id, PDO::PARAM_INT);
  $query->bindParam(':mode', $mode, PDO::PARAM_INT);
  $query->bindParam(':boss', $boss, PDO::PARAM_INT);
  $query->bindParam(':boss_id', $boss_id, PDO::PARAM_INT);
  $query->bindParam(':titl', $titl, PDO::PARAM_INT);
  $query->execute();
}
}else{
    $signupsql = "INSERT INTO signup (boss_id,shop_id,id,phone,Username,Email,Password,language,type,mode,nex_pay,accou_stu,expir,title,user_email_status)
    VALUES(:boss_id, :shop_id, :signup_id, :signup_fullname, :signup_username, :signup_email, :signup_password, :signup_language, :boss, :mode, :nex_pay, :ghys, :fbm, :titl, :verifi_now)";
    $query = $conn->prepare($signupsql);
    $query->bindParam(':signup_id', $signup_id, PDO::PARAM_INT);
    $query->bindParam(':signup_fullname', $signup_fullname, PDO::PARAM_STR);
    $query->bindParam(':signup_username', $signup_username, PDO::PARAM_STR);
    $query->bindParam(':ghys', $ghys, PDO::PARAM_STR);
    $query->bindParam(':fbm', $fbm, PDO::PARAM_STR);
    $query->bindParam(':signup_email', $signup_email, PDO::PARAM_STR);
    $query->bindParam(':nex_pay', $ergh, PDO::PARAM_STR);
    $query->bindParam(':mode', $mode, PDO::PARAM_STR);
    $query->bindParam(':verifi_now', $verifi_now, PDO::PARAM_STR);
    $query->bindParam(':signup_password', $signup_password, PDO::PARAM_STR);
    $query->bindParam(':signup_language', $signup_language, PDO::PARAM_STR);
    $query->bindParam(':boss', $boss, PDO::PARAM_INT);
    $query->bindParam(':boss_id', $signup_id, PDO::PARAM_INT);
    $query->bindParam(':shop_id', $signup_id, PDO::PARAM_INT);
    $query->bindParam(':titl', $titl, PDO::PARAM_INT);
    $query->execute();
}
    }
}
//===========================================[email send]======================================================================
if ($signup_genderVar == "sign") {
  $base_url = "http://currency.bumedianbm.com/";  //change this baseurl value as per your file path
  $mail_body = "
  <p>Hi ".$_POST['user_name'].",</p>
  <p>Please Open this link to verified your email address - ".$base_url."email_check.php?activation_code=".$user_activation_code."
  <p>Best Regards,<br />Almaqar</p>
  ";
  require '../class/class.phpmailer.php';
  $mail = new PHPMailer;
  $mail->IsSMTP();								//Sets Mailer to send message using SMTP
  $mail->Host = 'mail.bumedianbm.com';		//Sets the SMTP hosts of your Email hosting, this for Godaddy
  $mail->Port = '25';								//Sets the default SMTP server port
  $mail->SMTPAuth = true;							//Sets SMTP authentication. Utilizes the Username and Password variables
  $mail->Username = 'activate@bumedianbm.com';					//Sets SMTP username
  $mail->Password = 'AlmaqarPos112233!!';					//Sets SMTP password
  $mail->SMTPSecure = '';							//Sets connection prefix. Options are "", "ssl" or "tls"
  $mail->setFrom('activate@bumedianbm.com');			//Sets the From email address for the message
  $mail->FromName = 'Almaqar';						//Sets the From name of the message
  $mail->AddAddress($signup_email, $signup_username);		//Adds a "To" address
  $mail->WordWrap = 50;							//Sets word wrapping on the body of the message to a given number of characters
  $mail->IsHTML(true);							//Sets message type to HTML
  $mail->Subject = 'Email Verification';			//Sets the Subject of the message
  $mail->Body = $mail_body;							//An HTML or plain text message body
  if($mail->Send())								//Send an Email. Return true on success or false on error
  {
    $message = '<label class="text-success">Register Done, Please check your mail.</label>';
  }
}
//===================================================================================================================
    // ========================== login code after signup ============================
    $loginsql = "SELECT * FROM signup WHERE (Username= :signup_username OR Email= :signup_email) AND Password= :signup_password";
    $query = $conn->prepare($loginsql);
    $query->bindParam(':signup_username', $signup_username, PDO::PARAM_STR);
    $query->bindParam(':signup_email', $signup_email, PDO::PARAM_STR);
    $query->bindParam(':signup_password', $signup_password, PDO::PARAM_STR);
    $query->execute();
    $num = $query->rowCount();
if($signup_genderVar != "user"){
    include ("GeT_login_WhileFetch.php");
}
    echo "Done..";
}
$conn = null;
break;
}
?>
