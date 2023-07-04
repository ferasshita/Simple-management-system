<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends CI_Controller {

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
				array('langs', 'IsLogedin', 'Paymust','timefunction','Mode','User','Currencynodes','timefunction','numkmcount')
		);
			$this->load->model('comman_model');
			$this->load->model('Home_model');
			LoadLang();
			//LoadLang();
			// Your own constructor code
            
			Checklogin(base_url());
			Pay_must();
            error_reporting(0);
			
	}
    public function index()
    {
        if(!isset($_SESSION['Username'])){
            header("location: index");
        }
        if($_SESSION['user_email_status'] == "not verified"){
        header("location:email_verification");
        }
        if ($_SESSION['bank'] == '1') {
        header("location: home");
        }
        if($_SESSION['steps'] != "1"){
        header('location:steps?tc=shop');
        }
      
		////////////////////////////////////////////////////
        $data["currencies_b"]=Currencies_b();
		$data["currencies_a"]=Currencies_a();
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
        /////////////////////////////////////////////////////
        //==========================check if i own this bank======================
        $story_id = $_GET['pid'];
        $sid = $_SESSION['id'];
        $typem =  $_SESSION['type'];
        $sid =  $_SESSION['id'];
        $shop_id = $_SESSION['shop_id'];
        $boss_id = $_SESSION['boss_id'];
        $gfid = $boss_id;
        if($story_id==null)
        {
            $story_id = 0;
        }
        $fPosts_sql_sql = "SELECT * FROM my_bank WHERE id =$story_id";
        //echo $fPosts_sql_sql;exit;
        $udata=$this->Home_model->get_all_data_by_query($fPosts_sql_sql);
        foreach($udata as $mybanks) 
        {
            $array['mybanks'][$story_id][]=$mybanks;
            $bank_namv = $mybanks['bank_name'];
            $namev = $mybanks['name'];
            $banaccv = $mybanks['bank_acc'];
            $cityv = $mybanks['city'];
            $streetv = $mybanks['street'];
            $phonev = $mybanks['phone'];
            $emailv = $mybanks['email'];
            $usecarv = $mybanks['usecarv'];
            $usechav = $mybanks['usechav'];
            $usetrav = $mybanks['usetrav'];
            $countryv = $mybanks['country'];
            $note = $mybanks['note'];
      }
        //echo count($udata);exit;
        $data["mybankks"]=$array;
        //print_r($data["mybanks"]);exit;
        $data["mybank"]=count($udata);
        /////////////////////////////////////////////////

        // // =============================[ Save Edit bank settings ]==============================
        $usecar = $_POST['usecar'];
        $usecha = $_POST['usecha'];
        $usetra = $_POST['usetra'];
        if (isset($_POST['bank_save_changesv'])) {
        $boss_id =  $_SESSION['boss_id'];
        $vpsql = "SELECT id FROM my_bank WHERE boss_id=$boss_id";
        $udata=$this->Home_model->get_all_data_by_query($vpsql);
        $data["savebank"]=count($udata); 
        $numcosxz = count($udata); 
        if($_SESSION['package'] == "1" && $numcosxz <= "2" || $_SESSION['package'] == "2" && $numcosxz <= "6" || $_SESSION['package'] == "3" && $numcosxz <= "10" || $_SESSION['package'] == "4" || $_SESSION['admin'] == "1"){

            //$iptdbsqli = "UPDATE my_bank SET usecarv=$usecar,usechav = $usecha,usetrav= $usetra WHERE boss_id=$boss_id";
            $updatedata = array(
                'usecarv'      => $usecar,
                'usechav'      => $usecha,
                'usetrav'      => $usetra,              
            );
            $update_info = $this->comman_model->update_entry("my_bank",$updatedata,$boss_id);	
        }
        }


        /////////////////////////////////////////////////


        $sid =  $_SESSION['id'];
        $branch = "LYD";
        $vpsql = "SELECT DISTINCT kind FROM capital WHERE kind!='$branch' AND wh=$story_id AND boss_id=$gfid";
        //echo $vpsql;exit;
        $udata1=$this->Home_model->get_all_data_by_query($vpsql);
        $numcoss = count($udata1);
        foreach ($udata1 as $postsfetch) {
        $array3['datafromCapital'][$gfid][] = $postsfetch;
        $ghj = $postsfetch['kind'];
        $vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE kind='$ghj' AND wh=$story_id AND  boss_id=$gfid";
        $udata2=$this->Home_model->get_all_data_by_query($vpsql);
        $num = count($udata2);
        foreach ($udata2 as $postsfetch) {
         $ty_uy = $postsfetch['ty_uy'];
        }
        $vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE kind='$ghj' AND wh=$story_id AND  boss_id=$gfid";
        $udata3=$this->Home_model->get_all_data_by_query($vpsql);
        $num = count($udata3);
        foreach ($udata3 as $postsfetch) {
         $ty_ji = $postsfetch['ty_ji'];
        }
        $media = $ty_uy/$ty_ji;
        $data['media'] = $media;
        $data['numcoss'] = $numcoss;
    }
    $data['datafromCapital'] = $array3;
        ////////////////////////////////////////////////////////////////////////




        $sid =  $_SESSION['id'];
        $typey = "head";
        $ghy = "LYD";
        $vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE type='$typey' AND wh=$story_id AND  boss_id=$gfid";
        $udata4=$this->Home_model->get_all_data_by_query($vpsql);
        foreach ($udata4 as $postsfetch) {
          $ty_uy = $postsfetch['ty_uy'];
        }
        $vpsql = "SELECT SUM(number) AS ty_uy FROM capital WHERE kind='$ghy' AND type='$typey' AND wh=$story_id AND  boss_id=$gfid";
        $udata5 = $this->Home_model->get_all_data_by_query($vpsql);
        foreach ($udata5 as $postsfetch) {
          $ty_ub = $postsfetch['ty_uy'];
        }
        $tyji = $ty_ub+$ty_uy;
        $vpsql = "SELECT * FROM capital WHERE type='$typey' AND wh=$story_id AND  boss_id=$gfid ORDER BY time DESC";
        $udata6 = $this->Home_model->get_all_data_by_query($vpsql);
        //echo count($udata6);exit;
        foreach ($udata6 as $postsfetch) {
            $array2['dataCapital'][$gfid][] = $postsfetch;
            $id = $postsfetch['id'];
            $number = $postsfetch['number'];
            $kind = $postsfetch['kind'];
            $ex = $postsfetch['exchange'];
            $calc = $postsfetch['calc'];
            $note = $postsfetch['note'];
            $time = $postsfetch['time'];
            $wh = $postsfetch['wh'];
            $whb = $postsfetch['whb'];
            $tyi = $postsfetch['tyi'];
            $headed = $postsfetch['headed'];
            
            


        ////////////////////////////////////////////////////////////////////////



            $vpsqlu = "SELECT * FROM my_bank WHERE id=$wh";
            $udata7 = $this->Home_model->get_all_data_by_query($vpsqlu);
            foreach ($udata7 as $postsfetchu) {
            $array4['datafrommybank'][$wh][] = $postsfetchu;   
            $bank_name = $postsfetchu['bank_name'];
            $note = $postsfetchu['note'];
            $cont = $postsfetchu['country'];
            }
            $data['datafrommybank'] = $array4;

            //----------------------------------------------------------------------
            $vpsqlu = "SELECT * FROM my_bank WHERE id=$whb";
            $udata8 = $this->Home_model->get_all_data_by_query($vpsqlu);
            foreach ($udata8 as $postsfetchu) {
                $array5['datafrommybank2'][$whb][] = $postsfetchu;
                $bank_name = $postsfetchu['bank_name'];
                $note = $postsfetchu['note'];
                $cont = $postsfetchu['country'];
            }
            $data['datafrommybank2'] = $array4;
        }
        $data['dataCapital'] = $array2;


        /////////////////////////////////////////////////////////////////////////


        $vpsql = "SELECT * FROM treasury WHERE wh=$story_id AND  boss_id=$gfid";
        $udata9 = $this->Home_model->get_all_data_by_query($vpsql);
        foreach ($udata9 as $postsfetch) {
        $array5['datafromtreasury'][$gfid][] = $postsfetch;  
        $kind = $postsfetch['kind'];
        $number = $postsfetch['number'];

        }
        $data['datafromtreasury'] = $array5;
        /////////////////////////////////////////////////////////////////////////
        $this->load->view('bank/index',$data);
        
    
    }
}