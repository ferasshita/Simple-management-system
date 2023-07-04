<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {

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
				array('langs', 'IsLogedin', 'Paymust','timefunction','Mode','countrynames','Currencynodes')
		);
			
			$this->load->model('Home_model');
            $this->load->model('Comman_model');
			LoadLang();
            
			//LoadLang();
			// Your own constructor code
	}

	// public function index()
	// {
	// 	$this->load->view('index');
	// }


	public function index(){
		///check login
		Checklogin(base_url());
        CheckMailVerification();
        Pay_must();
        $data["dircheckPath"]= base_url()."Asset/";
        $mode=LoadMode();
        $data["tc"] = 'edit_profile';
        $data["layoutmode"]  =   $mode;
        $s_id = $_SESSION['id'];
        $s_username = $_SESSION['username'];

        $un = filter_var(htmlspecialchars($_GET['u']),FILTER_SANITIZE_STRING);
        $uisql = "SELECT * FROM signup WHERE Username= '$un' ";
        $udata=$this->Home_model->get_all_data_by_query($uisql);
        foreach($udata as $row){
            $data['row_id'] = $row['id'];
            $data['row_username'] = $row['username'];
            $data['row_email']  = $row['email'];
            $data['row_password']  = $row['password'];
            $data['row_user_cover_photo']   = $row['user_cover_photo'];
            $data['row_school']  = $row['school'];
            $data['row_work']  = $row['work'];
            $data['row_work0']  = $row['work0'];
            $data['row_country']  = $row['country'];
            $data['row_birthday']  = $row['birthday'];
            $data['row_verify']  = $row['verify'];
            $data['row_website']  = $row['website'];
            $data['row_bio']  = $row['bio'];
            $data['row_admin']  = $row['admin'];
            $data['row_gender']  = $row['gender'];
            $data['row_profile_pic_border']  = $row['profile_pic_border'];
            $data['row_language']  = $row['language'];
            $data['row_online']  = $row['online'];
        }

        $sid = $_SESSION['id'];
        $fetchUsers_sql = "SELECT Username,id FROM signup WHERE type='shop' AND boss_id='$sid'";
        $udata=$this->Home_model->get_all_data_by_query($fetchUsers_sql);
        $data["FetchedUser"]=$udata;


        
        if($_SESSION['EditProfile_save_result']!=null){
            $data["EditProfile_save_result"]=$_SESSION['EditProfile_save_result'];
            $_SESSION['EditProfile_save_result']=null;
        }

		$this->load->view('setting/profile',$data);
		
	}

    public function general(){
		///check login
		Checklogin(base_url());
        CheckMailVerification();
        Pay_must();
        $data["dircheckPath"]= base_url()."Asset/";
        $mode=LoadMode();
        $data["tc"] = 'general';
        $data["layoutmode"]  =   $mode;
        $s_id = $_SESSION['id'];
        $s_username = $_SESSION['username'];

        $un = filter_var(htmlspecialchars($_GET['u']),FILTER_SANITIZE_STRING);
        $uisql = "SELECT * FROM signup WHERE Username= '$un' ";
        $udata=$this->Home_model->get_all_data_by_query($uisql);
        foreach($udata as $row){
            $data['row_id'] = $row['id'];
            $data['row_username'] = $row['username'];
            $data['row_email']  = $row['email'];
            $data['row_password']  = $row['password'];
            $data['row_user_cover_photo']   = $row['user_cover_photo'];
            $data['row_school']  = $row['school'];
            $data['row_work']  = $row['work'];
            $data['row_work0']  = $row['work0'];
            $data['row_country']  = $row['country'];
            $data['row_birthday']  = $row['birthday'];
            $data['row_verify']  = $row['verify'];
            $data['row_website']  = $row['website'];
            $data['row_bio']  = $row['bio'];
            $data['row_admin']  = $row['admin'];
            $data['row_gender']  = $row['gender'];
            $data['row_profile_pic_border']  = $row['profile_pic_border'];
            $data['row_language']  = $row['language'];
            $data['row_online']  = $row['online'];
        }

        $sid = $_SESSION['id'];
        $fetchUsers_sql = "SELECT Username,id FROM signup WHERE type='shop' AND boss_id='$sid'";
        $udata=$this->Home_model->get_all_data_by_query($fetchUsers_sql);
        $data["FetchedUser"]=$udata;


        
        if($_SESSION['general_save_result']!=null){
            $data["general_save_result"]=$_SESSION['general_save_result'];
            $_SESSION['general_save_result']=null;
        }

		$this->load->view('setting/general',$data);
		
	}

    public function Saveprofile(){
            LoadLang();
            $fullname_var = filter_var(htmlentities($_POST['edit_fullname']),FILTER_SANITIZE_STRING);
            $username_var = filter_var(htmlentities($_POST['edit_username']),FILTER_SANITIZE_STRING);
            $email_var = filter_var(htmlentities($_POST['edit_email']),FILTER_SANITIZE_STRING);
            
            // =========================== password hashinng ==================================
            $new_password_var_field = filter_var(htmlentities($_POST['new_pass']),FILTER_SANITIZE_STRING);
            $options = array(
                'cost' => 12,
            );
            $new_password_var = password_hash($new_password_var_field, PASSWORD_BCRYPT, $options);
            // ================================================================================
            $rewrite_new_password_var = filter_var(htmlentities($_POST['rewrite_new_pass']),FILTER_SANITIZE_STRING);

            // filter gender as prefered language
            $gender_var = filter_var(htmlentities($_POST['gender']),FILTER_SANITIZE_STRING);
            if ($gender_var == lang('male')) {
            $gender_var = "Male";
            }elseif ($gender_var == lang('female')) {
                $gender_var = "Female";
            }
            $name_var = filter_var(htmlentities($_POST['name']),FILTER_SANITIZE_STRING);
            $address_var = filter_var(htmlentities($_POST['address']),FILTER_SANITIZE_STRING);
            $phone_no = filter_var(htmlentities($_POST['phone_no']),FILTER_SANITIZE_STRING);
            $email_ad = filter_var(htmlentities($_POST['email_ad']),FILTER_SANITIZE_STRING);
            $bankacc = filter_var(htmlentities($_POST['bankacc']),FILTER_SANITIZE_NUMBER_INT);
            $banknam = filter_var(htmlentities($_POST['banknam']),FILTER_SANITIZE_STRING);
            $website = filter_var(htmlentities($_POST['website']),FILTER_SANITIZE_STRING);
            $bank_per = filter_var(htmlentities($_POST['bank_per']),FILTER_SANITIZE_STRING);
            $bank_nam = filter_var(htmlentities($_POST['bank_nam']),FILTER_SANITIZE_STRING);
            $usecar = filter_var(htmlentities($_POST['usecar']),FILTER_SANITIZE_STRING);
            $usecha = filter_var(htmlentities($_POST['usecha']),FILTER_SANITIZE_STRING);
            $mode = filter_var(htmlentities($_POST['mode']),FILTER_SANITIZE_STRING);
            $currency_changes = filter_var(htmlentities($_POST['currency_pass']),FILTER_SANITIZE_STRING);

            $language_var = filter_var(htmlspecialchars($_POST['edit_language']),FILTER_SANITIZE_STRING);
            $trans_del = filter_var(htmlspecialchars($_POST['trans_del']),FILTER_SANITIZE_STRING);
            $bank_del = filter_var(htmlspecialchars($_POST['bank_del']),FILTER_SANITIZE_STRING);

            $general_current_pass_var = filter_var(htmlentities($_POST['general_current_pass']),FILTER_SANITIZE_STRING);
            $titl = filter_var(htmlentities($_POST['titl']),FILTER_SANITIZE_STRING);
            $EditProfile_current_pass_var = filter_var(htmlentities($_POST['EditProfile_current_pass']),FILTER_SANITIZE_STRING);
            $remeveA_current_pass_var = filter_var(htmlentities($_POST['removeA_current_pass']),FILTER_SANITIZE_STRING);
            $remevexj_current_pass_var = filter_var(htmlentities($_POST['removexj_current_pass']),FILTER_SANITIZE_STRING);
            $hide_trsh = filter_var(htmlentities($_POST['hide_trsh']),FILTER_SANITIZE_STRING);

            $bank_namv = filter_var(htmlentities($_POST['bank_namv']),FILTER_SANITIZE_STRING);
            $namev = filter_var(htmlentities($_POST['namev']),FILTER_SANITIZE_STRING);
            $banaccv = filter_var(htmlentities($_POST['banaccv']),FILTER_SANITIZE_STRING);
            $cityv = filter_var(htmlentities($_POST['cityv']),FILTER_SANITIZE_STRING);
            $streetv = filter_var(htmlentities($_POST['streetv']),FILTER_SANITIZE_STRING);
            $phonev = filter_var(htmlentities($_POST['phonev']),FILTER_SANITIZE_STRING);
            $emailv = filter_var(htmlentities($_POST['emailv']),FILTER_SANITIZE_STRING);
            $usecarv = filter_var(htmlentities($_POST['usecarv']),FILTER_SANITIZE_STRING);
            $usechav = filter_var(htmlentities($_POST['usechav']),FILTER_SANITIZE_STRING);
            $usetrav = filter_var(htmlentities($_POST['usetrav']),FILTER_SANITIZE_STRING);
            $cuuv = filter_var(htmlentities($_POST['cuuv']),FILTER_SANITIZE_STRING);
            $usecarv = filter_var(htmlentities($_POST['usecarv']),FILTER_SANITIZE_STRING);
            $usechavi = filter_var(htmlentities($_POST['usechavi']),FILTER_SANITIZE_STRING);
            $usetrav = filter_var(htmlentities($_POST['usetrav']),FILTER_SANITIZE_STRING);
            $currency = filter_var(htmlentities($_POST['addcur']),FILTER_SANITIZE_STRING);
            $note = filter_var(htmlentities($_POST['note']),FILTER_SANITIZE_STRING);
            $countryv = filter_var(htmlentities($_POST['countryv']),FILTER_SANITIZE_STRING);
            $mode = filter_var(htmlentities($_POST['mode']),FILTER_SANITIZE_STRING);
            $shop = filter_var(htmlentities($_POST['shop']),FILTER_SANITIZE_STRING);
            $cos_del = filter_var(htmlentities($_POST['cos_del']),FILTER_SANITIZE_STRING);
            $mode_save_changes = filter_var(htmlentities($_POST['mode_save_changes']),FILTER_SANITIZE_STRING);

            // =============================[ Save Edit profile settings ]==============================
            
            if (isset($_POST['EditProfile_save_changes'])) {
                
            if (!password_verify($EditProfile_current_pass_var,$_SESSION['Password'])) {
                
                $EditProfile_save_result = "<p id='error_msg'>".lang('current_password_is_incorrect')."</p>";
                

            }else{
                	
                $data = array(
                    'shop_id'   => $shop,
                    'name'   => $name_var,
                    'address'   => $address_var,
                    'phone_no'   => $phone_no,
                    'email_ad'   => $email_ad,
                    'website'   => $website,
                    'title'   => $titl,
                    'hide_trsh'   => $hide_trsh
                    
                );
                $session_un = $_SESSION['Username'];
                $where=array('username' => $session_un);
                $update_info=$this->Comman_model->update_entry("signup",$data,$where);
                if ($update_info) {
                    $_SESSION['name'] = $name_var;
                    $_SESSION['phone_no'] = $phone_no;
                    $_SESSION['email_ad'] = $email_ad;
                    $_SESSION['shop_id'] = $shop;
                    $_SESSION['website'] = $website;
                    $_SESSION['address'] = $address_var;
                    $_SESSION['title_h'] = $titl;
                    $_SESSION['hide_trsh'] = $hide_trsh;
                    $EditProfile_save_result = "<p class='success_msg'>".lang('changes_saved_seccessfully')."</p>";
                } else {
                    $EditProfile_save_result = "<p id='error_msg'>".lang('errorSomthingWrong')."</p>";
                }
            }
            
            $_SESSION['EditProfile_save_result']= $EditProfile_save_result;
        }
        $url=base_url()."Setting/";
        header("location: $url ");
        exit;

    }



    public function Savegeneral(){
        LoadLang();
        $fullname_var = filter_var(htmlentities($_POST['edit_fullname']),FILTER_SANITIZE_STRING);
        $username_var = filter_var(htmlentities($_POST['edit_username']),FILTER_SANITIZE_STRING);
        $email_var = filter_var(htmlentities($_POST['edit_email']),FILTER_SANITIZE_STRING);
        
        // =========================== password hashinng ==================================
        $new_password_var_field = filter_var(htmlentities($_POST['new_pass']),FILTER_SANITIZE_STRING);
        $options = array(
            'cost' => 12,
        );
        $new_password_var = password_hash($new_password_var_field, PASSWORD_BCRYPT, $options);
        // ================================================================================
        $rewrite_new_password_var = filter_var(htmlentities($_POST['rewrite_new_pass']),FILTER_SANITIZE_STRING);

        // filter gender as prefered language
        $gender_var = filter_var(htmlentities($_POST['gender']),FILTER_SANITIZE_STRING);
        if ($gender_var == lang('male')) {
        $gender_var = "Male";
        }elseif ($gender_var == lang('female')) {
            $gender_var = "Female";
        }
        $name_var = filter_var(htmlentities($_POST['name']),FILTER_SANITIZE_STRING);
        $address_var = filter_var(htmlentities($_POST['address']),FILTER_SANITIZE_STRING);
        $phone_no = filter_var(htmlentities($_POST['phone_no']),FILTER_SANITIZE_STRING);
        $email_ad = filter_var(htmlentities($_POST['email_ad']),FILTER_SANITIZE_STRING);
        $bankacc = filter_var(htmlentities($_POST['bankacc']),FILTER_SANITIZE_NUMBER_INT);
        $banknam = filter_var(htmlentities($_POST['banknam']),FILTER_SANITIZE_STRING);
        $website = filter_var(htmlentities($_POST['website']),FILTER_SANITIZE_STRING);
        $bank_per = filter_var(htmlentities($_POST['bank_per']),FILTER_SANITIZE_STRING);
        $bank_nam = filter_var(htmlentities($_POST['bank_nam']),FILTER_SANITIZE_STRING);
        $usecar = filter_var(htmlentities($_POST['usecar']),FILTER_SANITIZE_STRING);
        $usecha = filter_var(htmlentities($_POST['usecha']),FILTER_SANITIZE_STRING);
        $mode = filter_var(htmlentities($_POST['mode']),FILTER_SANITIZE_STRING);
        $currency_changes = filter_var(htmlentities($_POST['currency_pass']),FILTER_SANITIZE_STRING);

        $language_var = filter_var(htmlspecialchars($_POST['edit_language']),FILTER_SANITIZE_STRING);
        $trans_del = filter_var(htmlspecialchars($_POST['trans_del']),FILTER_SANITIZE_STRING);
        $bank_del = filter_var(htmlspecialchars($_POST['bank_del']),FILTER_SANITIZE_STRING);

        $general_current_pass_var = filter_var(htmlentities($_POST['general_current_pass']),FILTER_SANITIZE_STRING);
        $titl = filter_var(htmlentities($_POST['titl']),FILTER_SANITIZE_STRING);
        $EditProfile_current_pass_var = filter_var(htmlentities($_POST['EditProfile_current_pass']),FILTER_SANITIZE_STRING);
        $remeveA_current_pass_var = filter_var(htmlentities($_POST['removeA_current_pass']),FILTER_SANITIZE_STRING);
        $remevexj_current_pass_var = filter_var(htmlentities($_POST['removexj_current_pass']),FILTER_SANITIZE_STRING);
        $hide_trsh = filter_var(htmlentities($_POST['hide_trsh']),FILTER_SANITIZE_STRING);

        $bank_namv = filter_var(htmlentities($_POST['bank_namv']),FILTER_SANITIZE_STRING);
        $namev = filter_var(htmlentities($_POST['namev']),FILTER_SANITIZE_STRING);
        $banaccv = filter_var(htmlentities($_POST['banaccv']),FILTER_SANITIZE_STRING);
        $cityv = filter_var(htmlentities($_POST['cityv']),FILTER_SANITIZE_STRING);
        $streetv = filter_var(htmlentities($_POST['streetv']),FILTER_SANITIZE_STRING);
        $phonev = filter_var(htmlentities($_POST['phonev']),FILTER_SANITIZE_STRING);
        $emailv = filter_var(htmlentities($_POST['emailv']),FILTER_SANITIZE_STRING);
        $usecarv = filter_var(htmlentities($_POST['usecarv']),FILTER_SANITIZE_STRING);
        $usechav = filter_var(htmlentities($_POST['usechav']),FILTER_SANITIZE_STRING);
        $usetrav = filter_var(htmlentities($_POST['usetrav']),FILTER_SANITIZE_STRING);
        $cuuv = filter_var(htmlentities($_POST['cuuv']),FILTER_SANITIZE_STRING);
        $usecarv = filter_var(htmlentities($_POST['usecarv']),FILTER_SANITIZE_STRING);
        $usechavi = filter_var(htmlentities($_POST['usechavi']),FILTER_SANITIZE_STRING);
        $usetrav = filter_var(htmlentities($_POST['usetrav']),FILTER_SANITIZE_STRING);
        $currency = filter_var(htmlentities($_POST['addcur']),FILTER_SANITIZE_STRING);
        $note = filter_var(htmlentities($_POST['note']),FILTER_SANITIZE_STRING);
        $countryv = filter_var(htmlentities($_POST['countryv']),FILTER_SANITIZE_STRING);
        $mode = filter_var(htmlentities($_POST['mode']),FILTER_SANITIZE_STRING);
        $shop = filter_var(htmlentities($_POST['shop']),FILTER_SANITIZE_STRING);
        $cos_del = filter_var(htmlentities($_POST['cos_del']),FILTER_SANITIZE_STRING);
        $mode_save_changes = filter_var(htmlentities($_POST['mode_save_changes']),FILTER_SANITIZE_STRING);

        // =============================[ Save Edit profile settings ]==============================
        
        if (isset($_POST['general_save_changes'])) {
            if (!password_verify($general_current_pass_var,$_SESSION['Password'])) {
                $general_save_result = "<p id='error_msg'>".lang('current_password_is_incorrect')."</p>";
            }else{
                if (empty($fullname_var) or empty($username_var) or empty($email_var)) {
                    $general_save_result = "<p id='error_msg'>".lang('please_fill_required_fields')."</p>";
                } else {
                     if (empty($new_password_var) AND empty($rewrite_new_password_var)) {
                        $new_password_var = $_SESSION['Password'];
                     }elseif ($new_password_var_field != $rewrite_new_password_var) {
                        $general_save_result = "<p id='error_msg'>".lang('new_password_doesnt_match_the_confirm_field')."</p>";
                        $stop = "1";
                    }
                    if(strpos($username_var, ' ') !== false || preg_match('/[\'^£$%&*()}{@#~?><>,.|=+¬-]/', $username_var) || !preg_match('/[A-Za-z0-9]+/', $username_var)) {
                        $general_save_result =  "
                        <ul id='error_msg' style='list-style:none;'>
                            <li><b>".lang('username_not_allowed')." :</b></li>
                            <li><span class='fa fa-times'></span> ".lang('signup_username_should_be_1').".</li>
                            <li><span class='fa fa-times'></span> ".lang('signup_username_should_be_2').".</li>
                            <li><span class='fa fa-times'></span> ".lang('signup_username_should_be_3').".</li>
                            <li><span class='fa fa-times'></span> ".lang('signup_username_should_be_4').".</li>
                            <li><span class='fa fa-times'></span> ".lang('signup_username_should_be_5').".</li>
                        </ul>";
                        $stop = "1";
                    }
                    $unExist = "SELECT Username FROM signup WHERE Username = '$username_var' ";
		            $udata=$this->Home_model->get_all_data_by_query($unExist);

                    $unExistCount = count($udata);
                    if ($unExistCount > 0) {
                       if ($username_var != $_SESSION['Username']) {
                        $general_save_result = "<p id='error_msg'>".lang('user_already_exist')."</p>";
                        $stop = "1";
                       }
                    }

                    $unExist = "SELECT Email FROM signup WHERE Email = '$email_var' ";
		            $udata=$this->Home_model->get_all_data_by_query($unExist);

                    $emExistCount = count($udata);
                    if ($emExistCount > 0) {
                       if ($email_var != $_SESSION['Email']) {
                       $general_save_result = "<p id='error_msg'>".lang('email_already_exist')."</p>";
                       $stop = "1";
                       }
                    }
                    if (!filter_var($email_var, FILTER_VALIDATE_EMAIL)) {
                        $general_save_result = "<p id='error_msg'>".lang('invalid_email_address')."</p>";
                        $stop = "1";
                    }
                     if ($stop != "1") {
                    
                        $data = array(
                            'phone'   => $fullname_var,
                            'Username'   => $username_var,
                            'Email'   => $email_var,
                            'Password'   => $new_password_var,
                            'email_ad'   => $email_ad,
                            'website'   => $website,
                            'title'   => $titl,
                            'hide_trsh'   => $hide_trsh
                            
                        );
                        $session_un = $_SESSION['Username'];
                        $where=array('username' => $session_un);
                        $update_info=$this->Comman_model->update_entry("signup",$data,$where);
                    
                    if (isset($update_info)) {
                        $_SESSION['phone'] = $fullname_var;
                        $_SESSION['Username'] = $username_var;
                        $_SESSION['Email'] = $email_var;
                        $_SESSION['Password'] = $new_password_var;
                        $_SESSION['gender'] = $gender_var;
                        $general_save_result = "<p class='success_msg'>".lang('changes_saved_seccessfully')."</p>";
                    } else {
                        $general_save_result = "<p id='error_msg'>".lang('errorSomthingWrong')."</p>";
                    }
                    }
                }
            }

                 $_SESSION['general_save_result']= $general_save_result;

            }
        $url=base_url()."Setting/general";
        header("location: $url ");
        exit;

    }


    public function language(){
		///check login
		Checklogin(base_url());
        CheckMailVerification();
        Pay_must();
        $data["dircheckPath"]= base_url()."Asset/";
        $mode=LoadMode();
        $data["tc"] = 'language';
        $data["layoutmode"]  =   $mode;
        $s_id = $_SESSION['id'];
        $s_username = $_SESSION['username'];

        $un = filter_var(htmlspecialchars($_GET['u']),FILTER_SANITIZE_STRING);
        $uisql = "SELECT * FROM signup WHERE Username= '$un' ";
        $udata=$this->Home_model->get_all_data_by_query($uisql);
        foreach($udata as $row){
            $data['row_id'] = $row['id'];
            $data['row_username'] = $row['username'];
            $data['row_email']  = $row['email'];
            $data['row_password']  = $row['password'];
            $data['row_user_cover_photo']   = $row['user_cover_photo'];
            $data['row_school']  = $row['school'];
            $data['row_work']  = $row['work'];
            $data['row_work0']  = $row['work0'];
            $data['row_country']  = $row['country'];
            $data['row_birthday']  = $row['birthday'];
            $data['row_verify']  = $row['verify'];
            $data['row_website']  = $row['website'];
            $data['row_bio']  = $row['bio'];
            $data['row_admin']  = $row['admin'];
            $data['row_gender']  = $row['gender'];
            $data['row_profile_pic_border']  = $row['profile_pic_border'];
            $data['row_language']  = $row['language'];
            $data['row_online']  = $row['online'];
        }

        $sid = $_SESSION['id'];
        $fetchUsers_sql = "SELECT Username,id FROM signup WHERE type='shop' AND boss_id='$sid'";
        $udata=$this->Home_model->get_all_data_by_query($fetchUsers_sql);
        $data["FetchedUser"]=$udata;


        
        if($_SESSION['lang_save_result']!=null){
            $data["lang_save_result"]=$_SESSION['lang_save_result'];
            $_SESSION['lang_save_result']=null;
        }

		$this->load->view('setting/language',$data);
		
	}


    public function Savelanguage(){
        LoadLang();
        $fullname_var = filter_var(htmlentities($_POST['edit_fullname']),FILTER_SANITIZE_STRING);
        $username_var = filter_var(htmlentities($_POST['edit_username']),FILTER_SANITIZE_STRING);
        $email_var = filter_var(htmlentities($_POST['edit_email']),FILTER_SANITIZE_STRING);
        
        // =========================== password hashinng ==================================
        $new_password_var_field = filter_var(htmlentities($_POST['new_pass']),FILTER_SANITIZE_STRING);
        $options = array(
            'cost' => 12,
        );
        $new_password_var = password_hash($new_password_var_field, PASSWORD_BCRYPT, $options);
        // ================================================================================
        $rewrite_new_password_var = filter_var(htmlentities($_POST['rewrite_new_pass']),FILTER_SANITIZE_STRING);

        // filter gender as prefered language
        $gender_var = filter_var(htmlentities($_POST['gender']),FILTER_SANITIZE_STRING);
        if ($gender_var == lang('male')) {
        $gender_var = "Male";
        }elseif ($gender_var == lang('female')) {
            $gender_var = "Female";
        }
      
        $language_var = filter_var(htmlspecialchars($_POST['edit_language']),FILTER_SANITIZE_STRING);
    
        // =============================[ Save Edit profile settings ]==============================
        if (isset($_POST['lang_save_changes'])) {
            $data = array(
                'language'   => $language_var                
            );
            $session_un = $_SESSION['Username'];
            $where=array('username' => $session_un);
            $update_info=$this->Comman_model->update_entry("signup",$data,$where);

           if (isset($update_info)) {
               $_SESSION['language'] = $language_var;
               $lang_save_result = "<p class='success_msg'>".lang('changes_saved_seccessfully')."</p>";
                //header("location:?tc=language");
           } else {
               $lang_save_result = "<p id='error_msg'>".lang('errorSomthingWrong')."</p>";
           }
           $_SESSION['lang_save_result']= $lang_save_result;
       }
       
        $url=base_url()."Setting/language";
        header("location: $url ");
        exit;

    }

    public function mybank()
    {
        //////////////////////////////////
        Checklogin(base_url());
        CheckMailVerification();
        Pay_must();
        $data["dircheckPath"]= base_url()."Asset/";
        $mode=LoadMode();
        $data["tc"] = 'mybank';
        $data["layoutmode"]  =   $mode;
        $s_id = $_SESSION['id'];
        $s_username = $_SESSION['username'];

        $un = filter_var(htmlspecialchars($_GET['u']),FILTER_SANITIZE_STRING);
        $uisql = "SELECT * FROM signup WHERE Username= '$un' ";
        $udata=$this->Home_model->get_all_data_by_query($uisql);
        foreach($udata as $row){
            $data['row_id'] = $row['id'];
            $data['row_username'] = $row['username'];
            $data['row_email']  = $row['email'];
            $data['row_password']  = $row['password'];
            $data['row_user_cover_photo']   = $row['user_cover_photo'];
            $data['row_school']  = $row['school'];
            $data['row_work']  = $row['work'];
            $data['row_work0']  = $row['work0'];
            $data['row_country']  = $row['country'];
            $data['row_birthday']  = $row['birthday'];
            $data['row_verify']  = $row['verify'];
            $data['row_website']  = $row['website'];
            $data['row_bio']  = $row['bio'];
            $data['row_admin']  = $row['admin'];
            $data['row_gender']  = $row['gender'];
            $data['row_profile_pic_border']  = $row['profile_pic_border'];
            $data['row_language']  = $row['language'];
            $data['row_online']  = $row['online'];
        }

        $sid = $_SESSION['id'];
        $fetchUsers_sql = "SELECT Username,id FROM signup WHERE type='shop' AND boss_id='$sid'";
        $udata=$this->Home_model->get_all_data_by_query($fetchUsers_sql);
        $data["FetchedUser"]=$udata;


        
        if($_SESSION['general_save_result']!=null){
            $data["general_save_result"]=$_SESSION['general_save_result'];
            $_SESSION['general_save_result']=null;
        }
        ////////////////////BIND DATA TO MY BANK PAGE//////////////////////////////////////////
        $boss_id =  $_SESSION['boss_id'];
        $vpsql = "SELECT * FROM my_bank WHERE boss_id=$boss_id";
        $FetchedData=$this->Home_model->get_all_data_by_query($vpsql);
        foreach($FetchedData as $bankdata)
        {
            $array["searchbankdata"][$boss_id][]=$bankdata;
            $id = $bankdata['id'];
            $bank_namv = $bankdata['bank_name'];
            $namev = $bankdata['name'];
            $banaccv = $bankdata['bank_acc'];
            $cityv = $bankdata['city'];
            $streetv = $bankdata['street'];
            $phonev = $bankdata['phone'];
            $emailv = $bankdata['email'];
            $usecarv = $bankdata['usecar'];
            $usechav = $bankdata['usecha'];
            $usetrav = $bankdata['usetra'];
            $countryv = $bankdata['country'];
            $note = $bankdata['note'];


            $vpsqlc = "SELECT * FROM capital WHERE wh=$id";
            $FetchedData=$this->Home_model->get_all_data_by_query($vpsql);
            
            foreach($FetchedData as $capitaldata)
            {
                $array["searchcapitaldata"][$id][]=$capitaldata;
            }
        }
     
        
        $data["Pagedata"]=$array;
        ///////////////////////////////////////////////////////////////////////////////////////
        $this->load->view('setting/mybank',$data);
    }
    public function mybanknew()
    {
        //////////////////////////////////
        $data['countries']=Countries();
        Checklogin(base_url());
        CheckMailVerification();
        Pay_must();
        $data["dircheckPath"]= base_url()."Asset/";
        $mode=LoadMode();
        
        $data["layoutmode"]  =   $mode;
        $s_id = $_SESSION['id'];
        $s_username = $_SESSION['username'];

        $un = filter_var(htmlspecialchars($_GET['u']),FILTER_SANITIZE_STRING);
        $uisql = "SELECT * FROM signup WHERE Username= '$un' ";
        $udata=$this->Home_model->get_all_data_by_query($uisql);
        foreach($udata as $row){
            $data['row_id'] = $row['id'];
            $data['row_username'] = $row['username'];
            $data['row_email']  = $row['email'];
            $data['row_password']  = $row['password'];
            $data['row_user_cover_photo']   = $row['user_cover_photo'];
            $data['row_school']  = $row['school'];
            $data['row_work']  = $row['work'];
            $data['row_work0']  = $row['work0'];
            $data['row_country']  = $row['country'];
            $data['row_birthday']  = $row['birthday'];
            $data['row_verify']  = $row['verify'];
            $data['row_website']  = $row['website'];
            $data['row_bio']  = $row['bio'];
            $data['row_admin']  = $row['admin'];
            $data['row_gender']  = $row['gender'];
            $data['row_profile_pic_border']  = $row['profile_pic_border'];
            $data['row_language']  = $row['language'];
            $data['row_online']  = $row['online'];
        }

        $sid = $_SESSION['id'];
        $fetchUsers_sql = "SELECT Username,id FROM signup WHERE type='shop' AND boss_id='$sid'";
        $udata=$this->Home_model->get_all_data_by_query($fetchUsers_sql);
        $data["FetchedUser"]=$udata;


        
        if($_SESSION['general_save_result']!=null){
            $data["general_save_result"]=$_SESSION['general_save_result'];
            $_SESSION['general_save_result']=null;
        }
        ///////////////////////////////////////////////////////////////////////////////////////
        LoadLang();
        $fullname_var = filter_var(htmlentities($_POST['edit_fullname']),FILTER_SANITIZE_STRING);
        $username_var = filter_var(htmlentities($_POST['edit_username']),FILTER_SANITIZE_STRING);
        $email_var = filter_var(htmlentities($_POST['edit_email']),FILTER_SANITIZE_STRING);
        
        // =========================== password hashinng ==================================
        $new_password_var_field = filter_var(htmlentities($_POST['new_pass']),FILTER_SANITIZE_STRING);
        $options = array(
            'cost' => 12,
        );
        $new_password_var = password_hash($new_password_var_field, PASSWORD_BCRYPT, $options);
        // ================================================================================
        $rewrite_new_password_var = filter_var(htmlentities($_POST['rewrite_new_pass']),FILTER_SANITIZE_STRING);

        // filter gender as prefered language
        $gender_var = filter_var(htmlentities($_POST['gender']),FILTER_SANITIZE_STRING);
        if ($gender_var == lang('male')) {
        $gender_var = "Male";
        }elseif ($gender_var == lang('female')) {
            $gender_var = "Female";
        }
        $name_var = filter_var(htmlentities($_POST['name']),FILTER_SANITIZE_STRING);
        $address_var = filter_var(htmlentities($_POST['address']),FILTER_SANITIZE_STRING);
        $phone_no = filter_var(htmlentities($_POST['phone_no']),FILTER_SANITIZE_STRING);
        $email_ad = filter_var(htmlentities($_POST['email_ad']),FILTER_SANITIZE_STRING);
        $bankacc = filter_var(htmlentities($_POST['bankacc']),FILTER_SANITIZE_NUMBER_INT);
        $banknam = filter_var(htmlentities($_POST['banknam']),FILTER_SANITIZE_STRING);
        $website = filter_var(htmlentities($_POST['website']),FILTER_SANITIZE_STRING);
        $bank_per = filter_var(htmlentities($_POST['bank_per']),FILTER_SANITIZE_STRING);
        $bank_nam = filter_var(htmlentities($_POST['bank_nam']),FILTER_SANITIZE_STRING);
        $usecar = filter_var(htmlentities($_POST['usecar']),FILTER_SANITIZE_STRING);
        $usecha = filter_var(htmlentities($_POST['usecha']),FILTER_SANITIZE_STRING);
        $mode = filter_var(htmlentities($_POST['mode']),FILTER_SANITIZE_STRING);
        $currency_changes = filter_var(htmlentities($_POST['currency_pass']),FILTER_SANITIZE_STRING);

        $language_var = filter_var(htmlspecialchars($_POST['edit_language']),FILTER_SANITIZE_STRING);
        $trans_del = filter_var(htmlspecialchars($_POST['trans_del']),FILTER_SANITIZE_STRING);
        $bank_del = filter_var(htmlspecialchars($_POST['bank_del']),FILTER_SANITIZE_STRING);

        $general_current_pass_var = filter_var(htmlentities($_POST['general_current_pass']),FILTER_SANITIZE_STRING);
        $titl = filter_var(htmlentities($_POST['titl']),FILTER_SANITIZE_STRING);
        $EditProfile_current_pass_var = filter_var(htmlentities($_POST['EditProfile_current_pass']),FILTER_SANITIZE_STRING);
        $remeveA_current_pass_var = filter_var(htmlentities($_POST['removeA_current_pass']),FILTER_SANITIZE_STRING);
        $remevexj_current_pass_var = filter_var(htmlentities($_POST['removexj_current_pass']),FILTER_SANITIZE_STRING);
        $hide_trsh = filter_var(htmlentities($_POST['hide_trsh']),FILTER_SANITIZE_STRING);

        $bank_namv = filter_var(htmlentities($_POST['bank_namv']),FILTER_SANITIZE_STRING);
        $namev = filter_var(htmlentities($_POST['namev']),FILTER_SANITIZE_STRING);
        $banaccv = filter_var(htmlentities($_POST['banaccv']),FILTER_SANITIZE_STRING);
        $cityv = filter_var(htmlentities($_POST['city']),FILTER_SANITIZE_STRING);
        $streetv = filter_var(htmlentities($_POST['streetv']),FILTER_SANITIZE_STRING);
        $phonev = filter_var(htmlentities($_POST['phonev']),FILTER_SANITIZE_STRING);
        $emailv = filter_var(htmlentities($_POST['emailv']),FILTER_SANITIZE_STRING);
        $usecarv = filter_var(htmlentities($_POST['usecarv']),FILTER_SANITIZE_STRING);
        $usechav = filter_var(htmlentities($_POST['usechav']),FILTER_SANITIZE_STRING);
        $usetrav = filter_var(htmlentities($_POST['usetrav']),FILTER_SANITIZE_STRING);
        $cuuv = filter_var(htmlentities($_POST['cuuv']),FILTER_SANITIZE_STRING);
        $usecarv = filter_var(htmlentities($_POST['usecarv']),FILTER_SANITIZE_STRING);
        $usechavi = filter_var(htmlentities($_POST['usechavi']),FILTER_SANITIZE_STRING);
        $usetrav = filter_var(htmlentities($_POST['usetrav']),FILTER_SANITIZE_STRING);
        $currency = filter_var(htmlentities($_POST['addcur']),FILTER_SANITIZE_STRING);
        $note = filter_var(htmlentities($_POST['note']),FILTER_SANITIZE_STRING);
        $countryv = filter_var(htmlentities($_POST['countryv']),FILTER_SANITIZE_STRING);
        $mode = filter_var(htmlentities($_POST['mode']),FILTER_SANITIZE_STRING);
        $shop = filter_var(htmlentities($_POST['shop']),FILTER_SANITIZE_STRING);
        $cos_del = filter_var(htmlentities($_POST['cos_del']),FILTER_SANITIZE_STRING);
        $mode_save_changes = filter_var(htmlentities($_POST['mode_save_changes']),FILTER_SANITIZE_STRING);

        ////////////INSERT DATA TO SAVE NEW BANK///////////////////////////////////////////////////
        // =============================[ Save Edit bank settings ]==============================
        if (isset($_POST['bank_save_changesv'])) {  
        if (!password_verify($EditProfile_current_pass_var,$_SESSION['Password'])) {
            $EditProfile_save_resultddd = "<p id='error_msg'>".lang('current_password_is_incorrect')."</p>";
        }else{
        $boss_id =  $_SESSION['boss_id'];
        $vpsql = "SELECT id FROM my_bank WHERE boss_id=$boss_id";
        $FetchedData=$this->Home_model->get_all_data_by_query($vpsql);
        $numcosxz = count($FetchedData);
        if($_SESSION['package'] == "1" && $numcosxz <= "2" || $_SESSION['package'] == "0" && $numcosxz <= "2" || $_SESSION['package'] == "2" && $numcosxz <= "6" || $_SESSION['package'] == "3" && $numcosxz <= "10" || $_SESSION['package'] == "4" || $_SESSION['admin'] == "1"){
        
            $iptdbsqli = "INSERT INTO my_bank
            (boss_id,name,bank_name,country,city,street,phone,email,bank_acc,usecarv,usechav,usetrav,note)
            VALUES
            ( $boss_id, '$namev', '$bank_namv', '$countryv', '$cityv', '$streetv', '$phonev', '$emailv', '$banaccv', $usecarv, $usechav, $usetrav, '$note')
            ";
            //echo $iptdbsqli; exit;
            // $insert_post_toDBi = $conn->prepare($iptdbsqli);
            // $insert_post_toDBi->bindParam(':bank_nam', $bank_namv,PDO::PARAM_STR);
            // $insert_post_toDBi->bindParam(':note', $note,PDO::PARAM_STR);
            // $insert_post_toDBi->bindParam('$session_id', $boss_id,PDO::PARAM_INT);
            // $insert_post_toDBi->bindParam(':namev', $namev,PDO::PARAM_INT);
            // $insert_post_toDBi->bindParam(':banaccv', $banaccv,PDO::PARAM_INT);
            // $insert_post_toDBi->bindParam(':cityv', $cityv,PDO::PARAM_INT);
            // $insert_post_toDBi->bindParam(':countryv', $countryv,PDO::PARAM_INT);
            // $insert_post_toDBi->bindParam(':streetv', $streetv,PDO::PARAM_INT);
            // $insert_post_toDBi->bindParam(':usecarv', $usecarv,PDO::PARAM_INT);
            // $insert_post_toDBi->bindParam(':usechav', $usechav,PDO::PARAM_INT);
            // $insert_post_toDBi->bindParam(':usetrav', $usetrav,PDO::PARAM_INT);
            // $insert_post_toDBi->bindParam(':phonev', $phonev,PDO::PARAM_INT);
            // $insert_post_toDBi->bindParam(':emailv', $emailv,PDO::PARAM_INT);
            // $insert_post_toDBi->execute();
            // $insertdata = array(
                
            //     'boss_id'      => $boss_id,
            //     'name'         => $namev,
            //     'bank_nam'     => $bank_nam,
            //     'country'      => $countryv,
            //     'city'         => $cityv,
            //     'street'       => $streetv,
            //     'phone'        => $phonev,
            //     'email'        => $emailv,
            //     'bank_acc'     => $banaccv,
            //     'usecarv'      => $usecarv,
            //     'usechav'      => $usechav,
            //     'usetrav'      => $usetrav, 
            //     'note'         => $note,               
            // );
            $insert_post_toDBi = $this->Comman_model->run_query($iptdbsqli);		
            if (isset($insert_post_toDBi)) {
                $EditProfile_save_resultddd = "<p class='success_msg'>".lang('changes_saved_seccessfully')."</p>";
                //echo $EditProfile_save_resultddd;exit;
            } else {
            $EditProfile_save_resultddd = "<p id='error_msg'>".lang('errorSomthingWrong')."</p>";
        }
        }else{
        $fsh = "<p id='error_msg'>".lang('allowed_banks');
        if($_SESSION['package'] == "1"){
        $fsj ="2";
        }elseif($_SESSION['package'] == "0"){
        $fsj ="2";
        }elseif($_SESSION['package'] == "2"){
        $fsj ="6";
        }elseif($_SESSION['package'] == "3"){
        $fsj = "10";
        }
        $fbh = lang('banks')."</p>";
        $EditProfile_save_resultddd = "$fsh $fsj $fbh";
        }
        }
        }
        ///////////////////////////////////////////////////////////////////////////////////////////
        $data["EditProfile_save_resultddd"] = $EditProfile_save_resultddd;
        //echo $EditProfile_save_resultddd;exit;
        $this->load->view('setting/mybanknew',$data);
    }
	


 
    public function DeleteDatabase()
    {
          ///check login
          Checklogin(base_url());
          CheckMailVerification();
          Pay_must();
          $mode=LoadMode();
          $data["dircheckPath"]= base_url()."Asset/";
          $data["layoutmode"]  =   $mode;
          $data["currencies_b"]=Currencies_b();
          $data["currencies_a"]=Currencies_a();
          ////////////////////////////////////////////////////

          if (isset($_POST['removexj_save_changes'])) {
            $remevexj_current_pass_var = filter_var(htmlentities($_POST['removexj_current_pass']),FILTER_SANITIZE_STRING);
                if (!password_verify($remevexj_current_pass_var,$_SESSION['Password'])) {
                    
                    $data["removexj_save_result"] = "<p id='error_msg'>".lang('current_password_is_incorrect')."</p>";
                    
                }else{
                    $boss_id = $_SESSION['boss_id'];
                    $session_id = $_SESSION['id'];
                    $remeveAccount_sql = "DELETE FROM transactions WHERE user_id= $session_id";
                    $IsRun=$this->Comman_model->run_query($remeveAccount_sql);
                    $remeveAccount_sql = "DELETE FROM treasury WHERE user_id= $session_id";
                    $IsRun=$this->Comman_model->run_query($remeveAccount_sql);
                    // $remeveAccount = $conn->prepare($remeveAccount_sql);
                    // $remeveAccount->bindParam('$session_id',$session_id,PDO::PARAM_STR);
                    // $remeveAccount->execute();
                    $remeveAccount_sql = "DELETE FROM capital WHERE user_id= $session_id";
                    // $remeveAccount = $conn->prepare($remeveAccount_sql);
                    // $remeveAccount->bindParam('$session_id',$session_id,PDO::PARAM_STR);
                    // $remeveAccount->execute();
                    $IsRun=$this->Comman_model->run_query($remeveAccount_sql);
                    $remeveAccount_sql = "DELETE FROM cos_transactions WHERE user_id= $session_id";
                    $IsRun=$this->Comman_model->run_query($remeveAccount_sql);
                    // $remeveAccount = $conn->prepare($remeveAccount_sql);
                    // $remeveAccount->bindParam('$session_id',$session_id,PDO::PARAM_STR);
                    // $remeveAccount->execute();
                    $remeveAccount_sql = "DELETE FROM invest_treasury WHERE user_id= $session_id";
                    $IsRun=$this->Comman_model->run_query($remeveAccount_sql);

                    // $remeveAccount = $conn->prepare($remeveAccount_sql);
                    // $remeveAccount->bindParam('$session_id',$session_id,PDO::PARAM_STR);
                    // $remeveAccount->execute();
                    $remeveAccount_sql = "DELETE FROM expenses WHERE user_id= $session_id";
                    $IsRun=$this->Comman_model->run_query($remeveAccount_sql);
                    // $remeveAccount = $conn->prepare($remeveAccount_sql);
                    // $remeveAccount->bindParam('$session_id',$session_id,PDO::PARAM_STR);
                    // $remeveAccount->execute();
                
                if($bank_del != "1"){

                    $remeveAccount_sql = "DELETE FROM bank WHERE boss_id= $session_id";
                    $IsRun=$this->Comman_model->run_query($remeveAccount_sql);
                    // $remeveAccount = $conn->prepare($remeveAccount_sql);
                    // $remeveAccount->bindParam('$session_id',$boss_id,PDO::PARAM_STR);
                    // $remeveAccount->execute();
                    $remeveAccount_sql = "DELETE FROM my_bank WHERE boss_id= $session_id";
                    $IsRun=$this->Comman_model->run_query($remeveAccount_sql);
                    // $remeveAccount = $conn->prepare($remeveAccount_sql);
                    // $remeveAccount->bindParam('$session_id',$boss_id,PDO::PARAM_STR);
                    // $remeveAccount->execute();
                }
                if($cos_del != "1"){
                    $remeveAccount_sql = "DELETE FROM costumers WHERE boss_id= $session_id";
                    $IsRun=$this->Comman_model->run_query($remeveAccount_sql);

                    // $remeveAccount = $conn->prepare($remeveAccount_sql);
                    // $remeveAccount->bindParam('$session_id',$boss_id,PDO::PARAM_STR);
                    // $remeveAccount->execute();
                }
                    $data["removexj_save_result"] = "<p class='success_msg'>".lang('changes_saved_seccessfully')."</p>";
                }
            }


          $data["tc"] = 'remove_accountxj';
  
        
          $data=$this->BindData($data);
          $this->load->view('setting/Deletedatabase',$data);
          
     }

     
    public function DeleteAccount()
    {
          ///check login
          Checklogin(base_url());
          CheckMailVerification();
          Pay_must();
          $mode=LoadMode();
          $data["dircheckPath"]= base_url()."Asset/";
          $data["layoutmode"]  =   $mode;
          $data["currencies_b"]=Currencies_b();
          $data["currencies_a"]=Currencies_a();
          ////////////////////////////////////////////////////

        // =============================[ Remove account ]=================================
        $myid = $_SESSION['id'];
        if (isset($_POST['removeA_save_changes'])) {
            $remeveA_current_pass_var = filter_var(htmlentities($_POST['removeA_current_pass']),FILTER_SANITIZE_STRING);

        if (!password_verify($remeveA_current_pass_var,$_SESSION['Password'])) {
            $data["removeA_save_result"] = "<p id='error_msg'>".lang('current_password_is_incorrect')."</p>";
        }else{
                $remeveAccount_sql = "DELETE FROM signup WHERE Username= $session_id";
                $IDeleted=$this->Comman_model->run_query($remeveAccount_sql);
                // $remeveAccount = $conn->prepare($remeveAccount_sql);
                // $remeveAccount->bindParam('$session_id',$session_un,PDO::PARAM_STR);
                // $remeveAccount->execute();
                $remeveAccount_sql = "DELETE FROM transactions WHERE user_id= $session_id";
                $IDeleted=$this->Comman_model->run_query($remeveAccount_sql);
                // $remeveAccount = $conn->prepare($remeveAccount_sql);
                // $remeveAccount->bindParam('$session_id',$session_id,PDO::PARAM_STR);
                // $remeveAccount->execute();
                $remeveAccount_sql = "DELETE FROM treasury WHERE user_id= $session_id";
                $IDeleted=$this->Comman_model->run_query($remeveAccount_sql);

                // $remeveAccount = $conn->prepare($remeveAccount_sql);
                // $remeveAccount->bindParam('$session_id',$session_id,PDO::PARAM_STR);
                // $remeveAccount->execute();
                $remeveAccount_sql = "DELETE FROM capital WHERE user_id= $session_id";
                $IDeleted=$this->Comman_model->run_query($remeveAccount_sql);
                // $remeveAccount = $conn->prepare($remeveAccount_sql);
                // $remeveAccount->bindParam('$session_id',$session_id,PDO::PARAM_STR);
                // $remeveAccount->execute();
                $remeveAccount_sql = "DELETE FROM cos_transactions WHERE user_id= $session_id";
                $IDeleted=$this->Comman_model->run_query($remeveAccount_sql);
                // $remeveAccount = $conn->prepare($remeveAccount_sql);
                // $remeveAccount->bindParam('$session_id',$session_id,PDO::PARAM_STR);
                // $remeveAccount->execute();
                $remeveAccount_sql = "DELETE FROM invest_treasury WHERE user_id= $session_id";
                $IDeleted=$this->Comman_model->run_query($remeveAccount_sql);
                // $remeveAccount = $conn->prepare($remeveAccount_sql);
                // $remeveAccount->bindParam('$session_id',$session_id,PDO::PARAM_STR);
                // $remeveAccount->execute();
                $remeveAccount_sql = "DELETE FROM expenses WHERE user_id= $session_id";
                $IDeleted=$this->Comman_model->run_query($remeveAccount_sql);
                // $remeveAccount = $conn->prepare($remeveAccount_sql);
                // $remeveAccount->bindParam('$session_id',$session_id,PDO::PARAM_STR);
                // $remeveAccount->execute();
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
                $remeveAccount_sql = "DELETE FROM costumers WHERE boss_id= $session_id";
                $IDeleted=$this->Comman_model->run_query($remeveAccount_sql);
                // $remeveAccount = $conn->prepare($remeveAccount_sql);
                // $remeveAccount->bindParam('$session_id',$boss_id,PDO::PARAM_STR);
                // $remeveAccount->execute();
                if($_SESSION['type'] == "boss"){
                    $remeveAccount_sql = "DELETE FROM signup WHERE boss_id= $boss_id";
                    $IDeleted=$this->Comman_model->run_query($remeveAccount_sql);
                    // $remeveAccount = $conn->prepare($remeveAccount_sql);
                    // $remeveAccount->bindParam(':session_boss',$boss_id,PDO::PARAM_STR);
                    // $remeveAccount->execute();
                }
                header("location: login");
            }
        }


          $data["tc"] = 'remove_account';
  
        
          $data=$this->BindData($data);
          $this->load->view('setting/DeleteAccount',$data);
          
     }

  ///////////
  public function bank()
  {
        ///check login
        Checklogin(base_url());
        CheckMailVerification();
        Pay_must();
        $mode=LoadMode();
        $data["dircheckPath"]= base_url()."Asset/";
        $data["layoutmode"]  =   $mode;
        $data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
		////////////////////////////////////////////////////
        $data["tc"] = 'bank';

        if (isset($_POST['bank_save_changes'])) {
            
               $message=$this->UpdateData($_POST);
               $data["EditProfile_save_resultfdgd"]=$message;
        }
        $data=$this->BindData($data);
        $this->load->view('setting/bank',$data);
        
   }
   
   public function UpdateData($data){
    
       $_POST=$data;
      
        $boss_id =  $_SESSION['boss_id'];
        
        $name_var = filter_var(htmlentities($_POST['name']),FILTER_SANITIZE_STRING);
        $address_var = filter_var(htmlentities($_POST['address']),FILTER_SANITIZE_STRING);
        $phone_no = filter_var(htmlentities($_POST['phone_no']),FILTER_SANITIZE_STRING);
        $email_ad = filter_var(htmlentities($_POST['email_ad']),FILTER_SANITIZE_STRING);
        $bankacc = filter_var(htmlentities($_POST['bankacc']),FILTER_SANITIZE_NUMBER_INT);
        $banknam = filter_var(htmlentities($_POST['banknam']),FILTER_SANITIZE_STRING);
        $website = filter_var(htmlentities($_POST['website']),FILTER_SANITIZE_STRING);
        $bank_per = filter_var(htmlentities($_POST['bank_per']),FILTER_SANITIZE_STRING);
        $bank_nam = filter_var(htmlentities($_POST['bank_nam']),FILTER_SANITIZE_STRING);
        $usecar = filter_var(htmlentities($_POST['usecar']),FILTER_SANITIZE_STRING);
        $usecha = filter_var(htmlentities($_POST['usecha']),FILTER_SANITIZE_STRING);
        $mode = filter_var(htmlentities($_POST['mode']),FILTER_SANITIZE_STRING);
        $currency_changes = filter_var(htmlentities($_POST['currency_pass']),FILTER_SANITIZE_STRING);
        $EditProfile_current_pass_var = filter_var(htmlentities($_POST['EditProfile_current_pass']),FILTER_SANITIZE_STRING);

        $usechavi = filter_var(htmlentities($_POST['usechavi']),FILTER_SANITIZE_STRING);
        if (!password_verify($EditProfile_current_pass_var,$_SESSION['Password'])) {
            $EditProfile_save_resultfdgd = "<p id='error_msg'>".lang('current_password_is_incorrect')."</p>";
        }else{
            $session_id = $_SESSION['id'];
            $vpsql = "SELECT * FROM bank WHERE boss_id=$boss_id AND bank_nam='$bank_nam'";
            $FetchedData=$this->Comman_model->get_all_data_by_query($vpsql);
            // $view_postsi = $conn->prepare($vpsql);
            // $view_postsi->bindParam('$session_id', $boss_id, PDO::PARAM_INT);
            // $view_postsi->bindParam(':bank_nam', $bank_nam, PDO::PARAM_INT);
            // $view_postsi->execute();
            $num = count($FetchedData);//$view_postsi->rowCount();
            if($num < 1){
                $data = array(
                    'boss_id'   => $boss_id,
                    'bank_nam'   => $bank_nam,
                    'bank_per'   => $bank_per,
                    'usecar'   => $usecar,
                    'usecha'   => $usechavi,
                    
                );               
                print_r($data);exit;
                $id=$this->Comman_model->insert_entry("bank",$data);


            // $iptdbsqli = "INSERT INTO bank
            // (boss_id,bank_nam,bank_per,usecar,usecha)
            // VALUES
            // ( $session_id, :bank_nam, :bank_per, :usecar, :usecha)
            // ";
            // $insert_post_toDBi = $conn->prepare($iptdbsqli);
            // $insert_post_toDBi->bindParam(':bank_nam', $bank_nam,PDO::PARAM_STR);
            // $insert_post_toDBi->bindParam('$session_id', $boss_id,PDO::PARAM_INT);
            // $insert_post_toDBi->bindParam(':bank_per', $bank_per,PDO::PARAM_INT);
            // $insert_post_toDBi->bindParam(':usecar', $usecar,PDO::PARAM_INT);
            // $insert_post_toDBi->bindParam(':usecha', $usechavi,PDO::PARAM_INT);
            // $insert_post_toDBi->execute();
            }else{
                $data = array(
                    'bank_per'   => $bank_per,
                    'usecar'   => $usecar,
                    'usecha'   => $usechavi                    
                );
                $session_un = $_SESSION['Username'];
                $where=array('boss_id' => $boss_id, 'bank_nam' => $bank_nam);
                $update_info=$this->Comman_model->update_entry("bank",$data,$where);

            // $update_info_sql = "UPDATE bank SET bank_per= :bank_per, usecar=:usecar, usecha=:usecha WHERE boss_id= $session_id AND bank_nam=:bank_nam";
            // $update_info = $conn->prepare($update_info_sql);
            // $update_info->bindParam(':bank_nam',$bank_nam,PDO::PARAM_STR);
            // $update_info->bindParam(':bank_per',$bank_per,PDO::PARAM_STR);
            // $update_info->bindParam('$session_id',$boss_id,PDO::PARAM_STR);
            // $update_info->bindParam(':usecar', $usecar,PDO::PARAM_INT);
            // $update_info->bindParam(':usecha', $usechavi,PDO::PARAM_INT);
            // $update_info->execute();
            }
            if (isset($update_info) || isset($insert_post_toDBi)) {
                $EditProfile_save_resultfdgd = "<p class='success_msg'>".lang('changes_saved_seccessfully')."</p>";
            } else {
                $EditProfile_save_resultfdgd = "<p id='error_msg'>".lang('errorSomthingWrong')."</p>";
            }
        
        }
        $message=$EditProfile_save_resultfdgd;
        return $message;
   }

    public function BindData($data)
    {
        $boss_id =  $_SESSION['boss_id'];
        $vpsql = "SELECT * FROM bank WHERE boss_id=$boss_id";
        $FetchedData=$this->Comman_model->get_all_data_by_query($vpsql);
        $data["banks"]=$FetchedData;


        
        return $data;
    }


    public function mode()
    {
          ///check login
          Checklogin(base_url());
          CheckMailVerification();
          Pay_must();
          $mode=LoadMode();
          $data["dircheckPath"]= base_url()."Asset/";
          $data["layoutmode"]  =   $mode;
          $data["currencies_b"]=Currencies_b();
          $data["currencies_a"]=Currencies_a();
          ////////////////////////////////////////////////////
          $data["tc"] = 'mode';
  
          if (isset($_POST['mode_save_changes'])) 
          {              
            $session_un = $_SESSION['Username'];
            $mode = filter_var(htmlentities($_POST['mode']),FILTER_SANITIZE_STRING);     
            $mode_save_changes = filter_var(htmlentities($_POST['mode_save_changes']),FILTER_SANITIZE_STRING);
      
             $update_info_sql = "UPDATE signup SET mode= '$mode' WHERE username= '$session_un'";
             $update_info=$this->Comman_model->run_query($update_info_sql);
             if ($update_info) 
             {
                 $_SESSION['mode'] = $mode;
                 
                 if($mode=="night"){
                    
                    $data["layoutmode"]="dark-skin";
                 }else{
                    $data["layoutmode"]="light-skin";
                 }
                 $lang_save_resultsfs = "<p class='success_msg'>".lang('changes_saved_seccessfully')."</p>";
             } else {
                 $lang_save_resultsfs = "<p id='error_msg'>".lang('errorSomthingWrong')."</p>";
             } 

                 $data["lang_save_resultsfs"]=$lang_save_resultsfs;
                 //$data["layoutmode"]=$_SESSION['mode'];
          }
          //echo $data["layoutmode"];
          //$data=$this->BindData($data);
          $this->load->view('setting/mode',$data);
          
     }


     public function delete_cos_bank(){
        session_start();
        //==================delete the costumers bank========================
        $c_id = htmlentities($_POST['cid'], ENT_QUOTES);
        $sid =  $_SESSION['boss_id'];

        $gfid = $rows['id'];
            $delete_comm_sql = "DELETE FROM bank WHERE bank_nam = '$c_id' AND boss_id = $sid";
            $update_info=$this->Comman_model->run_query($delete_comm_sql);
        //     $delete_comm = $conn->prepare($delete_comm_sql);
        // $delete_comm->bindParam(':sid',$sid,PDO::PARAM_INT);
        //     $delete_comm->bindParam(':c_id',$c_id,PDO::PARAM_INT);
        //     $delete_comm->execute();
            echo "yes";

     }
}
