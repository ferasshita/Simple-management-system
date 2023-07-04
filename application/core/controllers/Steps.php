<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Steps extends CI_Controller {

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
				array('langs', 'IsLogedin', 'Paymust','timefunction','Mode','User','Currencynodes','countrynames')
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
            header("location:email_verification");
        }

        $mode=LoadMode();
        $data["dircheckPath"]= base_url()."Asset/";
        
        $data["layoutmode"]  =   $mode;
        $data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
		////////////////////////////////////////////////////
        $data['countries']=Countries();
        
        $data["tc"] = filter_var(htmlentities($_GET['tc']),FILTER_SANITIZE_STRING);
        $data["tcf"] = filter_var(htmlentities($_GET['tcf']),FILTER_SANITIZE_STRING);
        // if (isset($_POST['for'])) {
        //     $data=$this->UpdateData($_POST);            
        // }

        $_SESSION['steps'] = "1";





        $sid =  $_SESSION['id'];
        $delo = "0";
        $type = "transfar";
        $sid =  $_SESSION['id'];
        $shopo =  $_SESSION['shop_id'];
        $shop_id =  $_SESSION['shop_id'];
        $boss_id = $_SESSION['boss_id'];
        $typo =  $_SESSION['type'];

		$s_id = $_SESSION['id'];
        $s_username = $_SESSION['username'];

        $un = filter_var(htmlspecialchars($_GET['u']),FILTER_SANITIZE_STRING);

        $uisql = "SELECT * FROM signup WHERE Username='$un'";
        // $que = $conn->prepare($uisql);
        // $que->bindParam(':un', $un, PDO::PARAM_STR);
        // $que->execute();
        $FetchedData=$this->comman_model->get_all_data_by_query($uisql);
        foreach($FetchedData as $row){

            $data["row_username"] = $row['username'];
            $data["row_email"] = $row['email'];
            $data["row_password"] = $row['password'];
            $data["row_user_cover_photo"] = $row['user_cover_photo'];
            $data["row_school"] = $row['school'];
            $data["row_work"] = $row['work'];
            $data["row_work0"] = $row['work0'];
            $data["row_country"] = $row['country'];
            $data["row_birthday"] = $row['birthday'];
            $data["row_verify"] = $row['verify'];
            $data["row_website"] = $row['website'];
            $data["row_bio"] = $row['bio'];
            $data["row_admin"] = $row['admin'];
            $data["row_gender"] = $row['gender'];
            $data["row_profile_pic_border"] = $row['profile_pic_border'];
            $data["row_language"] = $row['language'];
            $data["row_online"] = $row['online'];
        }


        $steps = "1";
        
        // $update_info_sql = "UPDATE signup SET steps= "$name_var" WHERE username= '$session_un'";
        // $IsUpdate=$this->comman_model->run_query($update_info_sql);
     
        // $_SESSION['steps'] = "1";
    



        $session_un = $_SESSION['Username'];

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
        
        $general_current_pass_var = filter_var(htmlentities($_POST['general_current_pass']),FILTER_SANITIZE_STRING);
        $titl = filter_var(htmlentities($_POST['titl']),FILTER_SANITIZE_STRING);
        $remeveA_current_pass_var = filter_var(htmlentities($_POST['removeA_current_pass']),FILTER_SANITIZE_STRING);
        $remevexj_current_pass_var = filter_var(htmlentities($_POST['removexj_current_pass']),FILTER_SANITIZE_STRING);
        
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
        $usechav = filter_var(htmlentities($_POST['usechav']),FILTER_SANITIZE_STRING);
        $usetrav = filter_var(htmlentities($_POST['usetrav']),FILTER_SANITIZE_STRING);
        $currency = filter_var(htmlentities($_POST['addcur']),FILTER_SANITIZE_STRING);
        $note = filter_var(htmlentities($_POST['note']),FILTER_SANITIZE_STRING);
        $countryv = filter_var(htmlentities($_POST['countryv']),FILTER_SANITIZE_STRING);
        $mode = filter_var(htmlentities($_POST['mode']),FILTER_SANITIZE_STRING);
        $shop_name = filter_var(htmlentities($_POST['shop_name']),FILTER_SANITIZE_STRING);
        $mode_save_changes = filter_var(htmlentities($_POST['mode_save_changes']),FILTER_SANITIZE_STRING);

        











        /////////////////////////////////////////////
        if (isset($_POST['bank_save_changesv'])) {
           

               // $_POST=$array;
                $boss_id =  $_SESSION['boss_id'];
                $vpsql = "SELECT id FROM my_bank WHERE boss_id=$boss_id";

                $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
                
                // $view_postsi = $conn->prepare($vpsql);
                // $view_postsi->bindParam(':sid', $boss_id, PDO::PARAM_INT);
                // $view_postsi->execute();
                $numcosxz = count($FetchedData);// $view_postsi->rowCount();
                
                if($_SESSION['package'] == "1" && $numcosxz <= "2" || $_SESSION['package'] == "2" && $numcosxz <= "6" || $_SESSION['package'] == "3" && $numcosxz <= "10" || $_SESSION['package'] == "4" || $_SESSION['admin'] == "1")
                {
                
                    //$type= "out";
                    $data = array(
                        'boss_id'   => $boss_id,
                        'name'      => $namev,
                        'bank_name'      => $bank_namv,
                        'country'      => $countryv,
                        'city'      => $cityv,
                        'street'      => $streetv,
                        'phone'      => $phonev,
                        'email'      => $emailv,
                        'bank_acc'      => $banaccv,
                        'usecarv'      => $usecarv,
                        'usechav'      => $usechav,
                        'usetrav'   => $usetrav,
                        'note'      => $note
                    );
                   
                    $insert_post_toDBi=$this->comman_model->insert_entry("my_bank",$data);

                    // $iptdbsqli = "INSERT INTO my_bank
                    // (boss_id,name,bank_name,country,city,street,phone,email,bank_acc,usecarv,usechav,usetrav,note)
                    // VALUES
                    // ( :session_id, :namev, :bank_nam, :countryv, :cityv, :streetv, :phonev, :emailv, :banaccv, :usecarv, :usechav, :usetrav, :note)
                    // ";
                    // $insert_post_toDBi = $conn->prepare($iptdbsqli);
                    // $insert_post_toDBi->bindParam(':bank_nam', $bank_namv,PDO::PARAM_STR);
                    // $insert_post_toDBi->bindParam(':note', $note,PDO::PARAM_STR);
                    // $insert_post_toDBi->bindParam(':session_id', $boss_id,PDO::PARAM_INT);
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
                    if (isset($insert_post_toDBi)) {
                        $data["EditProfile_save_resultddd"] = "<p class='alertGreen'>".lang('changes_saved_seccessfully')."</p>";
                    } else {
                        $data["EditProfile_save_resultddd"] = "<p class='alertRed'>".lang('errorSomthingWrong')."</p>";
                    }
                    $boss_id =  $_SESSION['boss_id'];
                    $vpsql = "SELECT * FROM my_bank WHERE boss_id=$boss_id";
                    $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
                    // $view_posts = $conn->prepare($vpsql);
                    // $view_posts->bindParam(':sid', $boss_id, PDO::PARAM_INT);
                    // $view_posts->execute();
                        foreach ($FetchedData as $postsfetch) {
                            $id = $postsfetch['id'];
                            $_SESSION['banked'] = "$id";
                        }
                        $url=base_url()."Steps?tc=banked";
                        header("location: $url");
                    }else{
                       //echo "else";exit;
                       $fsh = "<p id='error_msg'>".lang('allowed_banks');
                        $data["fsh"] = $fsh;
                        if($_SESSION['package'] == "1"){
                            $fsj="2";
                            $data["fsj"] =$fsj;

                        }elseif($_SESSION['package'] == "0"){
                            $fsj    = "2";
                            $data["fsj"] =$fsj;

                        }elseif($_SESSION['package'] == "2"){
                            $fsj="6";
                            $data["fsj"] =$fsj;                            
                        }elseif($_SESSION['package'] == "3"){                            
                            $fsj="10";
                            $data["fsj"] = $fsj;                            
                        }
                        $fbh = lang('banks')."</p>";
                        $data["EditProfile_save_resultddd"] = "$fsh $fsj $fbh";
                    }
        }
        /////////////////////////////////////////


        ////////////////////////////////////////////
        if (isset($_POST['EditProfile_save_changes'])) {

             $update_info_sql = "UPDATE signup SET name= '$name_var',address= '$address_var',phone_no= '$phone_no',email_ad= '$email_ad',bankacc= '$bankacc',banknam= '$banknam',website= '$website',title='$titl' WHERE username= '$session_un'";
             $update_info=$this->comman_model->run_query($update_info_sql);
            //  $update_info = $conn->prepare($update_info_sql);
            //  $update_info->bindParam(':name_var',$name_var,PDO::PARAM_STR);
            //  $update_info->bindParam(':address_var',$address_var,PDO::PARAM_STR);
            //  $update_info->bindParam(':phone_no',$phone_no,PDO::PARAM_STR);
            //  $update_info->bindParam(':email_ad',$email_ad,PDO::PARAM_STR);
            //  $update_info->bindParam(':bankacc',$bankacc,PDO::PARAM_STR);
            //  $update_info->bindParam(':titl',$titl,PDO::PARAM_STR);
            //  $update_info->bindParam(':banknam',$banknam,PDO::PARAM_STR);
            //  $update_info->bindParam(':website',$website,PDO::PARAM_STR);
            //  $update_info->bindParam(':session_un',$session_un,PDO::PARAM_STR);
            //  $update_info->execute();
            if (isset($update_info)) {
                $_SESSION['name'] = $name_var;
                $_SESSION['phone_no'] = $phone_no;
                $_SESSION['email_ad'] = $email_ad;
                $_SESSION['website'] = $website;
                $_SESSION['bankacc'] = $bankacc;
                $_SESSION['bankname'] = $banknam;
                $_SESSION['address'] = $address_var;
                $_SESSION['title_h'] = $titl;
                $_SESSION['logo'] = $p_img;
                $data["EditProfile_save_result"] = "<p class='alertGreen'>".lang('changes_saved_seccessfully')."</p>";
            } else {
                $data["EditProfile_save_result"] = "<p class='alertRed'>".lang('errorSomthingWrong')."</p>";
            }
        
        }

        /////////////////////////////////////////


        ////////////////////////////////////
        if (isset($_POST['shop_creation'])) {
            $session_id = $_SESSION['id'];
            $boss_id = $_SESSION['boss_id'];
            $id_creation = rand(0,9999999)+time();
            $type="shop";
            $delo = "0";
            $data = array(
                'boss_id'   => $boss_id,
                'shop_id'      => $delo,
                'id'      => $id_creation,
                'Username'      => $shop_name,
                'type'      => $type
            );
            $insert_post_toDBi=$this->comman_model->insert_entry("signup",$data);

            //   $iptdbsqli = "INSERT INTO signup
            //   (boss_id,shop_id,id,Username,type)
            //   VALUES
            //   ( :boss_id, :shop_id, :id, :shop_name, :type)
            //   ";
            //  $insert_post_toDBi = $conn->prepare($iptdbsqli);
            //  $insert_post_toDBi->bindParam(':boss_id', $boss_id,PDO::PARAM_STR);
            //  $insert_post_toDBi->bindParam(':shop_id', $delo,PDO::PARAM_INT);
            //  $insert_post_toDBi->bindParam(':id', $id_creation,PDO::PARAM_INT);
            //  $insert_post_toDBi->bindParam(':shop_name', $shop_name,PDO::PARAM_INT);
            //  $insert_post_toDBi->bindParam(':type', $type,PDO::PARAM_INT);
            //  $insert_post_toDBi->execute();
          
             $update_info_sql = "UPDATE signup SET shop_id= $id_creation WHERE id= $session_id";
             $update_info=$this->comman_model->run_query($update_info_sql);
            //  $update_info = $conn->prepare($update_info_sql);
            //  $update_info->bindParam(':sh_id',$id_creation,PDO::PARAM_STR);
            //  $update_info->bindParam(':session_id',$session_id,PDO::PARAM_STR);
            //  $update_info->execute();
            $_SESSION['shop_id'] = $id_creation;
              if (isset($update_info) || isset($insert_post_toDBi)) {
                  $data["EditProfile_save_resultfdgd"] = "<p class='alertGreen'>".lang('changes_saved_seccessfully')."</p>";
              } else {
                  $data["EditProfile_save_resultfdgd"] = "<p class='alertRed'>".lang('errorSomthingWrong')."</p>";
              }
          
        }

        ////////////////////////////////////////



        /////////////////////////////////////////////
        if (isset($_POST['bank_save_changes'])) {
                $boss_id =  $_SESSION['boss_id'];
            
                $session_id = $_SESSION['id'];
                $vpsql = "SELECT * FROM bank WHERE boss_id=$boss_id AND bank_nam='$bank_nam'";
                $FetchedData = $this->comman_model->get_all_data_by_query($vpsql);
                // $view_postsi = $conn->prepare($vpsql);
                // $view_postsi->bindParam(':session_id', $boss_id, PDO::PARAM_INT);
                // $view_postsi->bindParam(':bank_nam', $bank_nam, PDO::PARAM_INT);
                // $view_postsi->execute();
                $num = count($FetchedData);// $view_postsi->rowCount();
                if($num < 1){
                    $data = array(
                        'boss_id'   => $boss_id,
                        'bank_nam'      => $bank_nam,
                        'bank_per'      => $bank_per,
                        'usecar'      => $usecar,
                        'usecha'      => $usecha
                    );
                    $insert_post_toDBi=$this->comman_model->insert_entry("bank",$data);
                // $iptdbsqli = "INSERT INTO bank
                // (boss_id,bank_nam,bank_per,usecar,usecha)
                // VALUES
                // ( :session_id, :bank_nam, :bank_per, :usecar, :usecha)
                // ";
                // $insert_post_toDBi = $conn->prepare($iptdbsqli);
                // $insert_post_toDBi->bindParam(':bank_nam', $bank_nam,PDO::PARAM_STR);
                // $insert_post_toDBi->bindParam(':session_id', $boss_id,PDO::PARAM_INT);
                // $insert_post_toDBi->bindParam(':bank_per', $bank_per,PDO::PARAM_INT);
                // $insert_post_toDBi->bindParam(':usecar', $usecar,PDO::PARAM_INT);
                // $insert_post_toDBi->bindParam(':usecha', $usecha,PDO::PARAM_INT);
                // $insert_post_toDBi->execute();
                }else{
                $update_info_sql = "UPDATE bank SET bank_per= '$bank_per', usecar='$usecar', usecha='$usecha' WHERE boss_id= $boss_id AND bank_nam='$bank_nam'";
                $update_info=$this->comman_model->run_query("bank",$data);
                // $update_info = $conn->prepare($update_info_sql);
                // $update_info->bindParam(':bank_nam',$bank_nam,PDO::PARAM_STR);
                // $update_info->bindParam(':bank_per',$bank_per,PDO::PARAM_STR);
                // $update_info->bindParam(':session_id',$boss_id,PDO::PARAM_STR);
                // $update_info->bindParam(':usecar', $usecar,PDO::PARAM_INT);
                // $update_info->bindParam(':usecha', $usecha,PDO::PARAM_INT);
                // $update_info->execute();
                }
                if (isset($update_info) || isset($insert_post_toDBi)) {
                    $data["EditProfile_save_resultfdgd"] = "<p class='alertGreen'>".lang('changes_saved_seccessfully')."</p>";
                }
                 else 
                {
                    $data["EditProfile_save_resultfdgd"] = "<p class='alertRed'>".lang('errorSomthingWrong')."</p>";
                }            
          }
        //////////////////////////////////////////////

        $data["active"] = "chakarc";
       //$//data[]=$test;
       
       $mode=LoadMode();
       $data["dircheckPath"]= base_url()."Asset/";
       
       $data["layoutmode"]  =   $mode;
       $data["currencies_b"]=Currencies_b();
       $data["currencies_a"]=Currencies_a();
       ////////////////////////////////////////////////////
       $data['countries']=Countries();
       
       $data["tc"] = filter_var(htmlentities($_GET['tc']),FILTER_SANITIZE_STRING);
       $data["tcf"] = filter_var(htmlentities($_GET['tcf']),FILTER_SANITIZE_STRING);
		$this->load->view('Steps/index', $data);
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
