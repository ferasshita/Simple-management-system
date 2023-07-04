<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_verification extends CI_Controller {

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
				array('langs', 'IsLogedin', 'Paymust','timefunction','Mode','User','Currencynodes','countrynames',"Sendmail")
		);
			$this->load->model('comman_model');
			$this->load->model('Home_model');
			LoadLang();
			//LoadLang();
			// Your own constructor code
            
			Checklogin(base_url());
			//Pay_must();
			
	}



    public function Index()
    {

        $mode=LoadMode();
        $data["dircheckPath"]= base_url()."Asset/";        
        $data["layoutmode"]  =   $mode;
        $data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
		////////////////////////////////////////////////////
        $data['countries']=Countries();        
        


        ///////////////////////////////////////////////////
        $email_var = filter_var(htmlentities($_POST['edit_email']),FILTER_SANITIZE_STRING);
        $data["email_var"]=$email_var;
        if (isset($_POST['general_save_changes'])) {
            if (empty($email_var)) {
                $data["general_save_result"] = "<p id='error_msg'>".lang('please_fill_required_fields')."</p>";
                $stop = "1";
            }
            if (!filter_var($email_var, FILTER_VALIDATE_EMAIL)) {
                $data["general_save_result"] = "<p id='error_msg'>".lang('invalid_email_address')."</p>";
                $stop = "1";
            }
            $session_un = $_SESSION['Username'];
            $emExist = "SELECT Email FROM signup WHERE Email ='$email_var'";
            $FetchedData = $this->comman_model->get_all_data_by_query($emExist);
            // $emExist->bindParam(':email_var',$email_var,PDO::PARAM_STR);
            // $emExist->execute();
            $emExistCount = count($FetchedData); //$emExist->rowCount();
            if ($emExistCount > 0) {
            if ($email_var != $_SESSION['Email']) {
            $data["general_save_result"] = "<p id='error_msg'>".lang('email_already_exist')."</p>";
            $stop = "1";
            }
            }
            if ($stop != "1") {
                $update_info_sql = "UPDATE signup SET Email= '$email_var' WHERE username= '$session_un'";
                $update_info = $this->comman_model->run_query($update_info_sql);
                // $update_info = $conn->prepare($update_info_sql);
                // $update_info->bindParam(':email_var',$email_var,PDO::PARAM_STR);
                // $update_info->bindParam(':session_un',$session_un,PDO::PARAM_STR);
                // $update_info->execute();
                if (isset($update_info)) {
                    $_SESSION['Email'] = $email_var;
                    $user_activation_code = $_SESSION['user_activation_code'];
                    $base_url = base_url(); //"http://currency.bumedianbm.com/";  //change this baseurl value as per your file path
                    $mail_body = "
                    <p>Hi ".$_POST['user_name'].",</p>
                    <p>Please Open this link to verified your email address - ".$base_url."Account/email_check?activation_code=".$user_activation_code."
                    <p>Best Regards,<br />Almaqar</p>
                    ";

                    $result = SendEmail('Email Verification',$signup_email,$mail_body);

                    // $this->load->library('email');

					// $this->email->from('activate@bumedianbm.com', 'activate');
					// $this->email->to($signup_email);
					// // $this->email->cc('another@another-example.com');
					// // $this->email->bcc('them@their-example.com');
					
					// $this->email->subject('Email Verification');
					// $this->email->message($mail_body);
                    // $this->email->send();
                    // if($this->email->send()) 
					// {
					// 	$message = "<p class='alertGreen'>The email has been sent</p>";
					// }else{
					// 	$message = "<p class='alertRed'>unexpected error email had not been sent</p>";
					// }

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
                    // $mail->FromName = 'Almaqar';						//Sets the From name of the message
                    // $mail->AddAddress($signup_email, $signup_username);		//Adds a "To" address
                    // $mail->WordWrap = 50;							//Sets word wrapping on the body of the message to a given number of characters
                    // $mail->IsHTML(true);							//Sets message type to HTML
                    // $mail->Subject = 'Email Verification';			//Sets the Subject of the message
                    // $mail->Body = $mail_body;							//An HTML or plain text message body
                    $data["general_save_result"] = "<p class='success_msg'>".lang('changes_email_seccessfully')."</p>";
                } else {
                    $general_save_result = "<p id='error_msg'>".lang('errorSomthingWrong')."</p>";
                }
            }
         }




		$this->load->view('Email_verification/index', $data);
    }

    

    public function Wwallet(){
        // session_start();
        // include "../config/connect.php";
        $post_id = rand(0,9999999)+time();
        $p_user_id = $_SESSION['id'];
        $p_author = $_SESSION['Fullname'];
        $p_author_photo = $_SESSION['Userphoto'];
        $shop_id = $_SESSION['shop_id'];
        $boss_id = $_SESSION['boss_id'];
        $timec = time();
        $un = filter_var(htmlspecialchars($_POST['amou']),FILTER_SANITIZE_STRING);
        $received_name = filter_var(htmlspecialchars($_POST['received_name']),FILTER_SANITIZE_STRING);
        $ex = filter_var(htmlspecialchars($_POST['ex']),FILTER_SANITIZE_STRING);
        $time = filter_var(htmlspecialchars($_POST['time']),FILTER_SANITIZE_STRING);
        $note = filter_var(htmlspecialchars($_POST['note']),FILTER_SANITIZE_STRING);
        $ty = filter_var(htmlspecialchars($_POST['ty']),FILTER_SANITIZE_STRING);
        $cda = filter_var(htmlspecialchars($_POST['cda']),FILTER_SANITIZE_STRING);
        $headed = filter_var(htmlspecialchars($_POST['headed']),FILTER_SANITIZE_STRING);
        $id = filter_var(htmlspecialchars($_POST['id']),FILTER_SANITIZE_STRING);

        $submit_name = filter_var(htmlspecialchars($_POST['submit_name']),FILTER_SANITIZE_STRING);
        $amoun_submit = filter_var(htmlspecialchars($_POST['amoun_submit']),FILTER_SANITIZE_STRING);
        $from_name = filter_var(htmlspecialchars($_POST['from_name']),FILTER_SANITIZE_STRING);
        $to_name = filter_var(htmlspecialchars($_POST['to_name']),FILTER_SANITIZE_STRING);

        if($received_name == "LYD"){
        $calc = "";
        }else{
            $calc= $un*$ex;
        }
        if($cda == "bank"){
        $bgh = "bank";
        //===================check if there is money in wallet=====================================
        $vpsql = "SELECT * FROM capital WHERE shop_id=$shop_id AND kind='$received_name' AND type='$ty' AND wh='$id' AND tyi='$bgh'";
        $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
        // $view_posts = $conn->prepare($vpsql);
        // $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
        // $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
        // $view_posts->bindParam(':id', $id, PDO::PARAM_INT);
        // $view_posts->bindParam(':ty', $ty, PDO::PARAM_INT);
        // $view_posts->bindParam(':bgh', $bgh, PDO::PARAM_STR);
        // $view_posts->execute();
        $numvg = count($FetchedData);// $view_posts->rowCount();
        foreach($FetchedData as $postsfetch) 
        {
            $numberg = $postsfetch['number'];
        }
        $type = "head";
        $vpsql = "SELECT * FROM capital WHERE shop_id=$shop_id AND type='$gth' AND wh='$id' AND tyi='$bgh'";
        $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);

        // $view_postsi = $conn->prepare($vpsql);
        // $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
        // $view_postsi->bindParam(':ty', $gth, PDO::PARAM_INT);
        // $view_postsi->bindParam(':id', $id, PDO::PARAM_INT);
        // $view_postsi->bindParam(':bgh', $bgh, PDO::PARAM_STR);
        // $view_postsi->execute();
        $numfdy = count($FetchedData);// $view_postsi->rowCount();
        foreach($FetchedData as $postsfetch) 
        {
            $kindaq = $postsfetch['kind'];
        }

        $vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$kindaq' AND wh='$id' AND tyi='$bgh'";
        $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
        // $view_postsi = $conn->prepare($vpsql);
        // $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
        // $view_postsi->bindParam(':ty', $kindaq, PDO::PARAM_INT);
        // $view_postsi->bindParam(':id', $id, PDO::PARAM_INT);
        // $view_postsi->bindParam(':bgh', $bgh, PDO::PARAM_STR);
        // $view_postsi->execute();
        foreach ($FetchedData as $postsfetch) 
        {
            $numberaq = $postsfetch['number'];
        }
        //=======================end of the checking==============================
        //=======================start insert or update wallet=========================
        $data = array(
            'user_id'   => $p_user_id,
            'shop_id'      => $shop_id,
            'boss_id'      => $boss_id,
            'number'      => $un,
            'exchange'      => $ex,
            'kind'      => $received_name,
            'calc'      => $calc,
            'type'      => $type,
            'note'      => $note,
            'wh'      => $id,
            'tyi'      => $bgh,
            'headed'   => $headed,
            'time'      => $time,
            'timex'      => $timec
        );
        $insert_post_toDB=$this->comman_model->insert_entry("capital",$data);
        // $iptdbsql = "INSERT INTO capital
        // (user_id,shop_id,boss_id,number,exchange,kind,calc,type,note,wh,tyi,headed,time,timex)
        // VALUES
        // ( :p_user_id, :shop_id, :boss_id, :un, :ex, :received_name, :calc, :type, :note, :id, :bgh, :headed, :time, :timec)
        // ";
        // $insert_post_toDB = $conn->prepare($iptdbsql);
        // $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':timec', $timec,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':ex', $ex,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':headed', $headed,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':calc', $calc,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':note', $note,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':id', $id, PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':type', $type,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':bgh', $bgh, PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':time', $time, PDO::PARAM_STR);
        // $insert_post_toDB->execute();
        $gskl = $numberaq-$calc;
        $iptdbsql = "UPDATE treasury SET number='$gskl' WHERE shop_id = $shop_id AND kind='$kindaq' AND wh='$id' AND tyi='$bgh'";
        $insert_post_toDB=$this->comman_model->run_query($iptdbsql);
        // $insert_post_toDB = $conn->prepare($iptdbsql);
        // $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':numbero', $gskl,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':gth', $kindaq,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':id', $id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':bgh', $bgh, PDO::PARAM_STR);
        // $insert_post_toDB->execute();
        //=======================end of insert or update profit=========================
        //========================check if there is money==============================
        $vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$received_name' AND wh='$id' AND tyi='$bgh'";
        $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
        // $view_postsi = $conn->prepare($vpsql);
        // $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
        // $view_postsi->bindParam(':received_name', $received_name, PDO::PARAM_INT);
        // $view_postsi->bindParam(':id', $id, PDO::PARAM_INT);
        // $view_postsi->bindParam(':bgh', $bgh, PDO::PARAM_STR);
        // $view_postsi->execute();
        $numsh = count($FetchedData);//$view_postsi->rowCount();
        foreach ($FetchedData as $postsfetch) {
            $number = $postsfetch['number'];
        }
        $numbercalv = $number+$un;
        //==============================end of the checking================================
        //==============================insert money========================================
        if(1 > $numsh){
            $data = array(
                'user_id'   => $p_user_id,
                'shop_id'      => $shop_id,
                'boss_id'      => $boss_id,               
                'kind'      => $received_name,
                'number'      => $un,               
                'wh'      => $id,
                'tyi'      => $bgh
              
            );
        $insert_post_toDB=$this->comman_model->insert_entry("treasury",$data);

        //         $iptdbsql = "INSERT INTO treasury
        // (user_id,shop_id,boss_id,kind,number,wh,tyi)
        // VALUES
        // ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
        // ";
        // $insert_post_toDB = $conn->prepare($iptdbsql);
        // $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':id', $id, PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':bgh', $bgh, PDO::PARAM_STR);
        // $insert_post_toDB->execute();
        }else{
            $iptdbsql = "UPDATE treasury SET number='$numbercalv' WHERE shop_id = $shop_id AND kind='$received_name' AND wh='$id' AND tyi='$bgh'";
            $insert_post_toDB=$this->comman_model->run_query($iptdbsql);
        // $insert_post_toDB = $conn->prepare($iptdbsql);
        // $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':numbercalv', $numbercalv,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':id', $id, PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':bgh', $bgh, PDO::PARAM_STR);
        // $insert_post_toDB->execute();
        }
        }elseif($cda == "transfar"){
        $bgha = "cash";
        $bghb = "bank";
        $bgh = "transfar";
        if($from_name == "0"){
        $ty_kin = "cash";
        }else{
        $ty_kin = "bank";
        }
        $type = "head";
        $vpsql = "SELECT * FROM capital WHERE shop_id=$shop_id AND kind='$from_name' AND tyi='$ty_kin'";
        $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
        // $view_postsi = $conn->prepare($vpsql);
        // $view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
        // $view_postsi->bindParam(':received_name', $from_name, PDO::PARAM_INT);
        // $view_postsi->bindParam(':tyi', $ty_kin, PDO::PARAM_INT);
        // $view_postsi->execute();
        $num = count($FetchedData);// $view_postsi->rowCount();
        foreach ($FetchedData as $postsfetch) 
        {
            $numberhea = $postsfetch['number'];
            $exchangehea = $postsfetch['exchange'];
            $tyhea = $postsfetch['kind'];
            $ty_gt = $postsfetch['type'];
        }
        //=============calculate the average of the exchange rate=======================
        $vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE shop_id=$shop_id AND kind='$given_name' AND tyi='$ty_kin'";
        $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
        // $view_postsi = $conn->prepare($vpsql);
        // $view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
        // $view_postsi->bindParam(':from_name', $given_name, PDO::PARAM_INT);
        // $view_postsi->bindParam(':tyi', $ty_kin, PDO::PARAM_INT);
        // $view_postsi->execute();
        foreach($FetchedData as $postsfetch) {
            $ty_uy = $postsfetch['ty_uy'];
        }
        $vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE shop_id=$shop_id AND kind='$given_name' AND tyi='$ty_kin'";
        $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
        // $view_postsi = $conn->prepare($vpsql);
        // $view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
        // $view_postsi->bindParam(':from_name', $given_name, PDO::PARAM_INT);
        // $view_postsi->bindParam(':tyi', $ty_kin, PDO::PARAM_INT);
        // $view_postsi->execute();
        foreach ($FetchedData as $postsfetch) {
            $ty_ji = $postsfetch['ty_ji'];
        }
        $medid= $ty_uy/$ty_ji;
        $media = number_format("$medid",2, ".", "");

        if($submit_name == "LYD"){
            $calcx = "";
        }else{
            $calcx= $amoun_submit*$media;
        }
        if($from_name == "0"){
        $vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$submit_name' AND wh='$from_name' AND tyi=$bgha";
        $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
        // $view_postsi = $conn->prepare($vpsql);
        // $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
        // $view_postsi->bindParam(':ty', $submit_name, PDO::PARAM_INT);
        // $view_postsi->bindParam(':id', $from_name, PDO::PARAM_INT);
        // $view_postsi->bindParam(':bgh', $bgha, PDO::PARAM_STR);
        // $view_postsi->execute();
        $numva = count($FetchedData);// $view_postsi->rowCount();
        foreach ($FetchedData as $postsfetch) {
            $numbera = $postsfetch['number'];
        }
        }else{
            $vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$submit_name' AND wh='$from_name' AND tyi='$bghb'";
            $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
            // $view_postsi = $conn->prepare($vpsql);
            // $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
            // $view_postsi->bindParam(':ty', $submit_name, PDO::PARAM_INT);
            // $view_postsi->bindParam(':id', $from_name, PDO::PARAM_INT);
            // $view_postsi->bindParam(':bgh', $bghb, PDO::PARAM_STR);
            // $view_postsi->execute();
            $numva = count($FetchedData);// $view_postsi->rowCount();
        foreach($FetchedData as $postsfetch) {
            $numbera = $postsfetch['number'];
        }
        }
        if($numbera >= $amoun_submit){
        unset($_SESSION['myerrortrban']);

        if($to_name == "0"){
        $vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$submit_name' AND wh='$to_name' AND tyi='$bgha'";
        $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
        // $view_postsi = $conn->prepare($vpsql);
        // $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
        // $view_postsi->bindParam(':ty', $submit_name, PDO::PARAM_INT);
        // $view_postsi->bindParam(':id', $to_name, PDO::PARAM_INT);
        // $view_postsi->bindParam(':bgh', $bgha, PDO::PARAM_STR);
        // $view_postsi->execute();
        $numvb = count($FetchedData) ;// $view_postsi->rowCount();
        foreach($FetchedData as $postsfetch) {
        $numberb = $postsfetch['number'];
        }
        }else{
            $vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$submit_name' AND wh='$to_name' AND tyi='$bghb'";
            $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
            // $view_postsi = $conn->prepare($vpsql);
            // $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
            // $view_postsi->bindParam(':ty', $submit_name, PDO::PARAM_INT);
            // $view_postsi->bindParam(':id', $to_name, PDO::PARAM_INT);
            // $view_postsi->bindParam(':bgh', $bghb, PDO::PARAM_STR);
            // $view_postsi->execute();
            $numvb = count($FetchedData);// $view_postsi->rowCount();
        foreach($FetchedData as $postsfetch) {
            $numberb = $postsfetch['number'];
        }
        }
        $numberca = $numbera-$amoun_submit;
        $numbercb = $numberb+$amoun_submit;
        //=======================end of the checking==============================
        //=======================start insert or update wallet=========================
        $data = array(
            'user_id'   => $p_user_id,
            'shop_id'      => $shop_id,
            'boss_id'      => $boss_id,               
            'number'      => $amoun_submit,
            'exchange'      => $media,               
            'kind'      => $submit_name,
            'calc'      => $calcx,
            'type'      => $type,
            'note'      => $note,
            'wh'      => $to_name,
            'whb'      => $from_name,
            'tyi'      => $bgh,
            'headed'      => $headed,
            'time'      => $time,
            'timex'      => $timec
          
        );
        $insert_post_toDB=$this->comman_model->insert_entry("capital",$data);
        //     $iptdbsql = "INSERT INTO capital
        // (user_id,shop_id,boss_id,number,exchange,kind,calc,type,note,wh,whb,tyi,headed,time,timex)
        // VALUES
        // ( :p_user_id, :shop_id, :boss_id, :un, :ex, :from_name, :calc, :type, :note, :id, :whb, :bgh, :headed, :time, :timec)
        // ";
        // $insert_post_toDB = $conn->prepare($iptdbsql);
        // $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':timec', $timec,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':un', $amoun_submit,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':ex', $media,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':headed', $headed,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':from_name', $submit_name,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':calc', $calcx,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':note', $note,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':id', $to_name, PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':whb', $from_name, PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':type', $type,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':bgh', $bgh, PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':time', $time, PDO::PARAM_STR);
        // $insert_post_toDB->execute();

        //==============================end of the checking================================
        //==============================insert money========================================
            $iptdbsql = "UPDATE treasury SET number='$numberca' WHERE shop_id = $shop_id AND kind='$submit_name' AND wh='$from_name'";
            $insert_post_toDB=$this->comman_model->run_query($iptdbsql);

        // $insert_post_toDB = $conn->prepare($iptdbsql);
        // $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':numbercalv', $numberca,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':id', $from_name, PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':received_name', $submit_name,PDO::PARAM_INT);
        // $insert_post_toDB->execute();
        $getbha="";
        if($to_name == "0"){
            $getbha=$bgha;
            //$insert_post_toDB->bindParam(':bgh', $bgha, PDO::PARAM_STR);
            }else{
                $getbha=$bghb;

            //$insert_post_toDB->bindParam(':bgh', $bghb, PDO::PARAM_STR);
            }

        if(1 > $numvb){
            $data = array(
                'user_id'   => $p_user_id,
                'shop_id'      => $shop_id,
                'boss_id'      => $boss_id,               
                'kind'      => $submit_name,
                'number'      => $amoun_submit,               
                'wh'      => $to_name,
                'tyi'      => $getbha
                
              
            );
            $insert_post_toDB=$this->comman_model->insert_entry("treasury",$data);



        //         $iptdbsql = "INSERT INTO treasury
        // (user_id,shop_id,boss_id,kind,number,wh,tyi)
        // VALUES
        // ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
        // ";
        // $insert_post_toDB = $conn->prepare($iptdbsql);
        // $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':un', $amoun_submit,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':id', $to_name, PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':received_name', $submit_name,PDO::PARAM_STR);
        // if($to_name == "0"){
        // $insert_post_toDB->bindParam(':bgh', $bgha, PDO::PARAM_STR);
        // }else{
        // $insert_post_toDB->bindParam(':bgh', $bghb, PDO::PARAM_STR);
        // }
        // $insert_post_toDB->execute();
        }else{
            $iptdbsql = "UPDATE treasury SET number='$numbercb' WHERE shop_id = $shop_id AND kind='$submit_name' AND wh= '$to_name' ";
            $insert_post_toDB=$this->comman_model->run_query($iptdbsql);
        
            // $insert_post_toDB = $conn->prepare($iptdbsql);
            // $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
            // $insert_post_toDB->bindParam(':numbercalv', $numbercb,PDO::PARAM_INT);
            // $insert_post_toDB->bindParam(':id', $to_name, PDO::PARAM_INT);
            // $insert_post_toDB->bindParam(':received_name', $submit_name,PDO::PARAM_INT);
            // $insert_post_toDB->execute();
        }
        }else{
        $_SESSION['myerrortrban'] = number_format("$numbera",2, ".", "")." $submit_name :".lang('youhave');
        }
        }else{
        $fgdx = "cash";
        //===================check if there is wallet=====================================
        $vpsql = "SELECT * FROM capital WHERE shop_id=$shop_id AND kind='$received_name' AND type='$ty' AND tyi='$fgdx'";
        $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
        // $view_posts = $conn->prepare($vpsql);
        // $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
        // $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
        // $view_posts->bindParam(':ty', $ty, PDO::PARAM_INT);
        // $view_posts->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
        // $view_posts->execute();
        $numvg = count($FetchedData);// $view_posts->rowCount();
        foreach($FetchedData as $postsfetch) 
        {
            $numberg = $postsfetch['number'];
        }
        $type = "head";
        $vpsql = "SELECT * FROM capital WHERE shop_id=$shop_id AND type='$gth' AND tyi='$fgdx'";
        $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
        // $view_postsi = $conn->prepare($vpsql);
        // $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
        // $view_postsi->bindParam(':ty', $gth, PDO::PARAM_INT);
        // $view_postsi->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
        // $view_postsi->execute();
        $numfdy = count($FetchedData);// $view_postsi->rowCount();
        foreach($FetchedData as $postsfetch) {
            $kindaq = $postsfetch['kind'];
        }

        $vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$kindaq' AND tyi='$fgdx'";
        $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
        // $view_postsi = $conn->prepare($vpsql);
        // $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
        // $view_postsi->bindParam(':ty', $kindaq, PDO::PARAM_INT);
        // $view_postsi->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
        // $view_postsi->execute();
        foreach ($FetchedData as $postsfetch) {
            $numberaq = $postsfetch['number'];
        }
        //=======================end of the checking==============================
        //=======================start insert or update profit=========================
        $data = array(
            'user_id'   => $p_user_id,
            'shop_id'      => $shop_id,
            'boss_id'      => $boss_id,               
            'number'      => $un,
            'exchange'      => $ex,               
            'kind'      => $received_name,
            'calc'      => $calc,
            'type'      => $type,
            'note'      => $note,
            'tyi'      => $fgdx,
            'headed'      => $headed,
            'time'  =>$time,
            'timex' =>$timec

            
          
        );
        $insert_post_toDB=$this->comman_model->insert_entry("capital",$data);

        //     $iptdbsql = "INSERT INTO capital
        // (user_id,shop_id,boss_id,number,exchange,kind,calc,type,note,tyi,headed,time,timex)
        // VALUES
        // ( :p_user_id, :shop_id, :boss_id, :un, :ex, :received_name, :calc, :type, :note, :fgdx, :headed, :time,:timec)
        // ";
        // $insert_post_toDB = $conn->prepare($iptdbsql);
        // $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':timec', $timec,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':headed', $headed,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':ex', $ex,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':calc', $calc,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':note', $note,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':type', $type,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':time', $time, PDO::PARAM_STR);
        // $insert_post_toDB->execute();
        $gskl = $numberaq-$calc;
        $iptdbsql = "UPDATE treasury SET number='$gskl' WHERE shop_id = $shop_id AND kind='$kindaq' AND tyi='$fgdx'";
        $insert_post_toDB=$this->comman_model->run_query($iptdbsql);
        // $insert_post_toDB = $conn->prepare($iptdbsql);
        // $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':numbero', $gskl,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':gth', $kindaq,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
        // $insert_post_toDB->execute();
        //=======================end of insert or update profit=========================
        //========================check if there is money==============================
        $vpsql = "SELECT * FROM treasury WHERE shop_id=$shop_id AND kind='$received_name' AND tyi='$fgdx'";
        $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
        // $view_postsi = $conn->prepare($vpsql);
        // $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
        // $view_postsi->bindParam(':received_name', $received_name, PDO::PARAM_INT);
        // $view_postsi->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
        // $view_postsi->execute();
        $numsh =  count($FetchedData);//$view_postsi->rowCount();
        foreach ($FetchedData as $postsfetch) {
            $number = $postsfetch['number'];
        }
        $numbercalv = $number+$un;
        //==============================end of the checking================================
        //==============================insert money========================================
        if(1 > $numsh){
            $data = array(
                'user_id'   => $p_user_id,
                'shop_id'      => $shop_id,
                'boss_id'      => $boss_id,               
                'kind'      => $received_name,
                'number'      => $un,               
                'tyi'      => $fgdx      
            );
            $insert_post_toDB=$this->comman_model->insert_entry("treasury",$data);

        //         $iptdbsql = "INSERT INTO treasury
        // (user_id,shop_id,boss_id,kind,number,tyi)
        // VALUES
        // ( :p_user_id, :shop_id, :boss_id, :received_name, :un, :fgdx)
        // ";
        // $insert_post_toDB = $conn->prepare($iptdbsql);
        // $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
        // $insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
        // $insert_post_toDB->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
        // $insert_post_toDB->execute();
        }else{
            $iptdbsql = "UPDATE treasury SET number='$numbercalv' WHERE shop_id = $shop_id AND kind='$received_name' AND tyi='$fgdx'";
            $insert_post_toDB=$this->comman_model->run_query($iptdbsql);

            // $insert_post_toDB = $conn->prepare($iptdbsql);
            // $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
            // $insert_post_toDB->bindParam(':numbercalv', $numbercalv,PDO::PARAM_INT);
            // $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
            // $insert_post_toDB->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
            // $insert_post_toDB->execute();
        }
        }
        //================================end of insertation===================================
    }
	

    public function UpdateBankSaveData($array){
    }


    public function BindData($data){
       


        
        return $data;

    }

	public function FilterData($data){
		$type = "transfar";
        $sid =  $_SESSION['id'];
        $shopo =  $_SESSION['shop_id'];
        $typo =  $_SESSION['type'];
        $shop_id =  $_SESSION['shop_id'];
        $boss_id = $_SESSION['boss_id'];
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

			// Sort
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
