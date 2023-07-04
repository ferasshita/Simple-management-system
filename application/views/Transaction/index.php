<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__file__) . 'error_log.txt');
error_reporting(E_ALL ^ E_NOTICE);
error_reporting(0);
// session_start();
// if(!isset($_SESSION['Username'])){
//     header("location: index");
// }
// if($_SESSION['user_email_status'] == "not verified"){
// header("location:email_verification");
// }
// if($_SESSION['steps'] != "1"){
// header('location:steps?tc=shop');
// }
$getLang = trim(filter_var(htmlentities($_GET['lang']),FILTER_SANITIZE_STRING));
if (!empty($getLang)) {
    $_SESSION['language'] = $getLang;
}
//include "includes/pay_must.php";
// if ($_SESSION['transfar'] == '1') {
//     header("location: home");
// }
// if($_SESSION['package'] == "2" || $_SESSION['package'] == "3" || $_SESSION['package'] == "4" || $_SESSION['admin'] == "1"){
// }else{
//     header("location: home");
// }
//$active = "transfar";
//include ("includes/currency_codes.php");
// ========================= config the languages ================================
// error_reporting(E_NOTICE ^ E_ALL);
// if (is_file('home.php')){
//     $path = "";
// }elseif (is_file('../home.php')){
//     $path =  "../";
// }elseif (is_file('../../home.php')){
//     $path =  "../../";
// }
// include_once $path."langs/set_lang.php";
//include("includes/country_name_function.php");
// if (isset($_POST['submi_up'])) {
//     include "config/connect.php";
//     $billclo = filter_var(htmlspecialchars($_POST['billclo']),FILTER_SANITIZE_STRING);
//     $sid = $_SESSION['id'];
//     $type= "transfar";
//     $update_info_sql = "UPDATE transactions SET bill= :billclo WHERE user_id= :sid AND type=:type";
//     $update_info = $conn->prepare($update_info_sql);
//     $update_info->bindParam(':billclo',$billclo,PDO::PARAM_STR);
//     $update_info->bindParam(':type',$type,PDO::PARAM_STR);
//     $update_info->bindParam(':sid',$sid,PDO::PARAM_STR);
//     $update_info->execute();
// }
//include "config/connect.php";
// $sid = $_SESSION['id'];
// $shop_id = $_SESSION['shop_id'];
// $boss_id = $_SESSION['boss_id'];
// if (isset($_POST['for'])) {
//     $exchange = filter_var(htmlentities($_POST['exchange']),FILTER_SANITIZE_STRING);
//     $received_name = filter_var(htmlentities($_POST['amousel']),FILTER_SANITIZE_STRING);
//     $received = filter_var(htmlentities($_POST['received']),FILTER_SANITIZE_STRING);
//     $given_name = filter_var(htmlentities($_POST['amouse']),FILTER_SANITIZE_STRING);
//     $amountsd = filter_var(htmlentities($_POST['amountsd']),FILTER_SANITIZE_STRING);
//     $selbu = filter_var(htmlentities($_POST['selbu']),FILTER_SANITIZE_STRING);
//     $pid = filter_var(htmlentities($_POST['pid']),FILTER_SANITIZE_STRING);

//     $vpsql = "SELECT * FROM transactions WHERE post_id=:pid";
//     $view_posts = $conn->prepare($vpsql);
//     $view_posts->bindParam(':pid', $pid, PDO::PARAM_INT);
//     $view_posts->execute();
//     $numvf = $view_posts->rowCount();
//     while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
//         $receivedy = $postsfetch['received'];
//         $giveny = $postsfetch['given'];
//     }
//     $numbj = $received-$receivedy;
//     $numbg = $amountsd-$giveny;
//     $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:received_name";
//     $view_posts = $conn->prepare($vpsql);
//     $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
//     $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
//     $view_posts->execute();
//     $numvf = $view_posts->rowCount();
//     while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
//         $numberya = $postsfetch['number'];
//     }
//     $numberybt = $numberya+$numbj;
//     $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:given_name";
//     $view_posts = $conn->prepare($vpsql);
//     $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
//     $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
//     $view_posts->execute();
//     $numvh = $view_posts->rowCount();
//     while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
//         $numberyb = $postsfetch['number'];
//     }
//     $numberd = $numberyb+$numbg;

//     $edit_post_sql = "UPDATE cash SET exchange= :exchange,received = :received,given=:amountsd,received_name = :amousel,given_name =:amouse,kin=:selbu WHERE post_id= :pid";
//     $edit_post = $conn->prepare($edit_post_sql);
//     $edit_post->bindParam(':exchange',$exchange,PDO::PARAM_STR);
//     $edit_post->bindParam(':amousel',$received_name,PDO::PARAM_STR);
//     $edit_post->bindParam(':received',$received,PDO::PARAM_INT);
//     $edit_post->bindParam(':amouse',$given_name,PDO::PARAM_INT);
//     $edit_post->bindParam(':amountsd',$amountsd,PDO::PARAM_INT);
//     $edit_post->bindParam(':selbu',$selbu,PDO::PARAM_INT);
//     $edit_post->bindParam(':pid',$pid,PDO::PARAM_INT);
//     $edit_post->execute();

//     $iptdbsql = "UPDATE treasury SET number=:numbero WHERE shop_id = :p_user_id AND kind=:received_name";
//     $insert_post_toDB = $conn->prepare($iptdbsql);
//     $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
//     $insert_post_toDB->bindParam(':numbero', $numberybt,PDO::PARAM_INT);
//     $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
//     $insert_post_toDB->execute();

//     //=====================insert the money of given==================================

//     $iptdbsql = "UPDATE treasury SET number=:numberb WHERE shop_id = :p_user_id AND kind=:given_name";
//     $insert_post_toDB = $conn->prepare($iptdbsql);
//     $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
//     $insert_post_toDB->bindParam(':numberb', $numberd,PDO::PARAM_INT);
//     $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
//     $insert_post_toDB->execute();

// }
?>
<!DOCTYPE html>
<html lang="<?php echo lang('html_lang'); ?>" dir="">
<head>
    <title>تقرير العامل</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php //include "includes/head_imports_main.php"
    $this->load->view('includes/head_imports_main');
    ?>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="<?php echo base_url();?>Asset/css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="<?php echo base_url();?>Asset/css/style.css">

    <style>
        .exchange-calculator  .select2-container {
            margin-top: 0px;
        }@media only screen and (min-width: 1000px) {
            .input-group {
                position: relative;
                display: inline-flex;
                flex-wrap: wrap;
                align-items: stretch;
                width: 45%;
            }
        }
        @media (max-width: 767px) {
            .fixed-width {
                flex: 0 0 49%;
                max-width: 49%;
                width: 50%;
            }
        }
        .loader {
            position: fixed;
            /*z-index: 99;*/
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #f2f5fa;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .loader > img {
            width: 100px;
        }

        .loader.hidden {
            animation: fadeOut 1s;
            animation-fill-mode: forwards;
        }

        @keyframes fadeOut {
            100% {
                opacity: 0;
                visibility: hidden;
            }
        }

        .thumb {
            height: 100px;
            border: 1px solid black;
            margin: 10px;
        }

        .loader {
            display: flex;
        }
        .loader .dot {
            position: relative;
            width: 2em;
            height: 2em;
            margin: 0.8em;
            border-radius: 50%;
        }
        .loader .dot::before {
            position: absolute;
            content: "";
            width: 100%;
            height: 100%;
            background: inherit;
            border-radius: inherit;
            animation: wave 2s ease-out infinite;
        }
        .loader .dot:nth-child(1) {
            background: #7ef9ff;
        }
        .loader .dot:nth-child(1)::before {
            animation-delay: 0.2s;
        }
        .loader .dot:nth-child(2) {
            background: #89cff0;
        }
        .loader .dot:nth-child(2)::before {
            animation-delay: 0.4s;
        }
        .loader .dot:nth-child(3) {
            background: #4682b4;
        }
        .loader .dot:nth-child(3)::before {
            animation-delay: 0.6s;
        }
        .loader .dot:nth-child(4) {
            background: #0f52ba;
        }
        .loader .dot:nth-child(4)::before {
            animation-delay: 0.8s;
        }
        .loader .dot:nth-child(5) {
            background: #000080;
        }
        .loader .dot:nth-child(5)::before {
            animation-delay: 1s;
        }

        @keyframes wave {
            50%, 75% {
                transform: scale(2.5);
            }
            80%, 100% {
                opacity: 0;
            }
        }

        /*@-webkit-keyframes spin {*/
        /*    0% { -webkit-transform: rotate(0deg); }*/
        /*    100% { -webkit-transform: rotate(360deg); }*/
        /*}*/

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Add animation to "page content" */
        .animate-bottom {
            position: relative;
            -webkit-animation-name: animatebottom;
            -webkit-animation-duration: 1s;
            animation-name: animatebottom;
            animation-duration: 1s
        }

        @-webkit-keyframes animatebottom {
            from { bottom:-100px; opacity:0 }
            to { bottom:0px; opacity:1 }
        }

        @keyframes animatebottom {
            from{ bottom:-100px; opacity:0 }
            to{ bottom:0; opacity:1 }
        }

    </style>

</head>
<body class="hold-transition rtl light-skin sidebar-mini theme-primary sidebar-collapse">
<!-- Site wrapper -->
<div  class="wrapper animate-bottom" id="wrapper_id">
    <div class="loader">
<!--        <div class="dot"></div>-->
<!--        <div class="dot"></div>-->
<!--        <div class="dot"></div>-->
<!--        <div class="dot"></div>-->
<!--        <div class="dot"></div>-->
    </div>
    <!-- Left side column. contains the logo and sidebar -->


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="mr-auto">
                        <h3 class="page-title"><strong>منضومه العمال</strong></h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Transaction/index"><i class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item active" aria-current="page">منضومه العمال</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <form class="form" id="postingToDB" action="<?php echo base_url();?>Transaction/wtransaction" method="post" enctype="multipart/form-data">
                    <!-- Informations Of Receiver -->

                    <!-- /.Informations Of Receiver  -->

                    <!-- Informations Of Sender -->

                    <!-- /.Informations Of Sender  -->

                    <!-- Informations Buy and Sell -->

                    <!-- /.Informations Buy and Sell -->

                    <!-- Exchange -->
                    <div style="<?php if(isset($_POST['search_em'])){ ?>display:none<?php } ?>" id="info" class="row">
                        <div class="col-12">
                            <div class="box">
                                <div class="box-header">
                                    <h4 class="box-title">
                                        البيانات <button type="button" onclick="updatex()" class="btn btn-primary">تحديث</button>
                                    </h4>
                                </div> <div class="box-body">
                                <div class="exchange-calculator">
                                <input type="text" list="browsersco" class="form-control" id="company" autocomplete="off" name="company" placeholder="اسم الشركه">
                                <input type="text" list="browserscoadd" class="form-control" id="company_address" autocomplete="off" name="company_addresss" placeholder="موقع الشركه">
                                <input type="text" list="browserscodo" class="form-control" id="company_do" autocomplete="off" name="company_do" placeholder="تخصص الشركه">
                                <input type="text" list="browserscoac" class="form-control" id="company_action" autocomplete="off" name="company_action" placeholder="نشاط الشركه">
                                </div></div><div class="box-body">
                                    <div class="exchange-calculator">
<div class="input-group">
                                      <input type="text" class="form-control" id="name" autocomplete="off" name="name" placeholder="الاسم">
                                      <div class="input-group-prepend phone-w">
                                      <select class="form-control fix-padding" id="gender" autocomplete="off" name="gender" placeholder="<?php echo lang('Bank_account'); ?>" ><option>دكر</option><option>انثى</option></select>
                                    </div>
</div>
<input type="text" list="browsersna" style="width: 554px;" class="form-control" id="nationality" autocomplete="off" name="nationality" placeholder="الجنسيه">

<input type="text" onblur="(this.type='text')"  onfocus="(this.type='date')"  data-date="" data-date-format="YYYY-mm-DD" class="form-control" id="birthdate" autocomplete="off" name="birthdate" placeholder="تايخ الميلاد" >
                                      <input type="text" class="form-control" id="passport" autocomplete="off" name="passport" placeholder="رقم الجواز" >
                                      <input type="text" list="browsersed" class="form-control" id="education" autocomplete="off" name="education" placeholder="الؤهل العلمي" >
                                      <input type="text" list="browsersse" class="form-control" id="section" autocomplete="off" name="section" placeholder="التخصص" >
                                      <input type="number" class="form-control" id="qualification_years" autocomplete="off" name="qualification_years" placeholder="سنوات الخبره" >
                                      <input type="text" list="browsersjo" class="form-control" id="job" autocomplete="off" name="job" placeholder="المهنه\الوظيفه" >
                                      <input type="text" class="form-control" id="paid" autocomplete="off" name="paid" placeholder="المرتب" >
                                      <input type="text" onblur="(this.type='text')" onfocus="(this.type='date')"  data-date="" data-date-format="YYYY-mm-DD" class="form-control" id="starting" autocomplete="off" name="starting" placeholder="بدايه العقد" >
                                      <input type="text" onblur="(this.type='text')" onfocus="(this.type='date')"  data-date="" data-date-format="YYYY-mm-DD" class="form-control" id="expiring" autocomplete="off" name="expiring" placeholder="نهايه العقد">
                                      <input type="number" class="form-control" id="visa" autocomplete="off" name="visa" placeholder="رقم التأشيره">
                                      <input type="text" onblur="(this.type='text')" onfocus="(this.type='date')"  data-date="" data-date-format="YYYY-mm-DD" class="form-control" id="enter_date" autocomplete="off" name="enter_date" placeholder="تاريخ الدخول">
                                      <input type="text" list="browsersi" class="form-control" id="group" autocomplete="off" name="group" placeholder="المجموعه">
                                      <datalist id="browsersi">
                                      <?php

                                      foreach ($groupsc as $rows) {
                                        $group = $rows['groupco'];
                                        echo"<option value='$group'>";
                                      }
                                      ?>
                                      </datalist>

                                      <datalist id="browsersna">
                                      <?php

                                      foreach ($groupsn as $rows) {
                                        $nationality = $rows['nationality'];
                                        echo"<option value='$nationality'>";
                                      }
                                      ?>
                                      </datalist>

                                      <datalist id="browsersco">
                                      <?php

                                      foreach ($groupsco as $rows) {
                                        $company = $rows['company'];
                                        echo"<option value='$company'>";
                                      }
                                      ?>
                                      </datalist>
                                      <datalist id="browserscoadd">
                                      <?php

                                      foreach ($groupscoadd as $rows) {
                                        $company_address = $rows['company_address'];
                                        echo"<option value='$company_address'>";
                                      }
                                      ?>
                                      </datalist>
                                      <datalist id="browserscodo">
                                      <?php

                                      foreach ($groupscodo as $rows) {
                                        $company_do = $rows['company_do'];
                                        echo"<option value='$company_do'>";
                                      }
                                      ?>
                                      </datalist>
                                      <datalist id="browserscoac">
                                      <?php

                                      foreach ($groupscoac as $rows) {
                                        $company_action = $rows['company_action'];
                                        echo"<option value='$company_action'>";
                                      }
                                      ?>
                                      </datalist>

                                      <datalist id="browsersjo">
                                      <?php

                                      foreach ($groupsj as $rows) {
                                        $job = $rows['job'];
                                        echo"<option value='$job'>";
                                      }
                                      ?>
                                      </datalist>

                                      <datalist id="browsersse">
                                      <?php

                                      foreach ($groupss as $rows) {
                                        $section = $rows['section'];
                                        echo"<option value='$section'>";
                                      }
                                      ?>
                                      </datalist>
                                      <datalist id="browsersname">
                                      <?php

                                      foreach ($groupsname as $rows) {
                                        $name = $rows['name'];
                                        echo"<option value='$name'>";
                                      }
                                      ?>
                                      </datalist>
                                      <datalist id="browsersed">
                                      <?php

                                      foreach ($groupsed as $rows) {
                                        $education = $rows['education'];
                                        echo"<option value='$education'>";
                                      }
                                      ?>
                                      </datalist>
                                    </div><div id="modc"><?php if($_SESSION['error'] == "1"){ ?>
                                    <p id="error_msg" style="text-align:<?php echo lang('sponsored_align'); ?>;">
                                        لا يمكنك تكرار رقم الجواز
                                    </p><?php } ?></div>
                                    <p class="success_msg" style="text-align:<?php echo lang('sponsored_align'); ?>;display:none">
                                        <?php echo lang('success_msg'); ?>
                                    </p>
                                    <div class="text-center mt-15 mb-25">
                                        <button  type="submit" class="btn btn-success mx-auto with-btm" id="su_buy" name="post_now">
                                            <span id="moves_enter">حفط</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
</form>
<?php
$namese = $_POST['namese'];
 if(isset($_POST['search_em'])){
   $vpsql = "SELECT * FROM transaction WHERE name='$namese'";

   $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
 foreach($FetchedData as $postsfetch)
   {
     $names = $postsfetch['name'];
     $gender = $postsfetch['gender'];
     $birthdate = $postsfetch['birthdate'];
     $passport = $postsfetch['passport'];
     $education = $postsfetch['education'];
     $section = $postsfetch['section'];
     $qualification_years = $postsfetch['qualification_years'];
     $job = $postsfetch['job'];
     $paid = $postsfetch['paid'];
     $starting = $postsfetch['starting'];
     $expiring = $postsfetch['expiring'];
     $visa = $postsfetch['visa'];
     $nationality = $postsfetch['nationality'];
     $enter_date = $postsfetch['enter_date'];
     $company = $postsfetch['company'];
     $groups = $postsfetch['groupco'];
     $company_address = $postsfetch['company_address'];
     $company_do = $postsfetch['company_do'];
     $company_action = $postsfetch['company_action'];

}
}
 ?>
<form style="display:none" action="" id="hiform" method="post">
  <input type="hidden" name="namese" id="hjdjs" value="<?php if(isset($_POST['search_name'])) echo $_POST['name']; ?>"/>
<input type="hidden" name="search_em" value="submit">
</form>
<form class="form" id="postingToDBup" action="<?php echo base_url();?>Transaction/utransaction" method="post">
<div id="update" style="<?php if(!isset($_POST['search_em'])){ ?>display:none<?php } ?>" class="row">
    <div class="col-12">
        <div class="box">
            <div class="box-header">
                <h4 class="box-title">
                    تحديث <button type="button" onclick="newz()" class="btn btn-primary">بيانات جديده</button>
                </h4>
            </div>
            <div class="box-body">
                <div class="exchange-calculator">
<div class="input-group">
                  <input type="text" class="form-control" id="search_name" onkeyup="populateSecondTextBox();" value="<?php echo $namese; ?>" autocomplete="off" name="names"  list="browsersname" placeholder="الاسم">
                  <div class="input-group-prepend phone-w">
                  <select class="form-control fix-padding" id="gender" name="genders"><option <?php if($gender == "دكر") echo "selected"; ?>>دكر</option><option <?php if($gender == "انثى") echo "selected"; ?>>انثى</option></select>
                </div><div class="input-group-prepend phone-w">
                <button type="submit" form="hiform" class="form-control fix-padding btn btn-success" name="search_em">بحث</button>
              </div>
</div>
<input type="text" list="browsersna" style="width: 554px;" value="<?php echo $nationality; ?>" class="form-control" id="nationality" autocomplete="off" name="nationalitys" placeholder="الجنسيه">

<input type="text" onblur="(this.type='text')" value="<?php echo $birthdate; ?>" onfocus="(this.type='date')"  data-date="" data-date-format="YYYY-mm-DD" class="form-control" id="birthdate" autocomplete="off" name="birthdates" placeholder="تايخ الميلاد" >
                  <input type="text" class="form-control" value="<?php echo $passport; ?>" id="passport" autocomplete="off" name="passports" placeholder="رقم الجواز" >
                  <input type="text" list="browsersed" value="<?php echo $education; ?>" class="form-control" id="education" autocomplete="off" name="educations" placeholder="الؤهل العلمي" >
                  <input type="text" list="browsersse" value="<?php echo $section; ?>" class="form-control" id="section" autocomplete="off" name="sections" placeholder="التخصص" >
                  <input type="number" class="form-control" value="<?php echo $qualification_years; ?>" id="qualification_years" autocomplete="off" name="qualification_yearss" placeholder="سنوات الخبره" >
                  <input type="text" list="browsersjo" value="<?php echo $job; ?>" class="form-control" id="job" autocomplete="off" name="jobs" placeholder="المهنه\الوظيفه" >
                  <input type="text" class="form-control" value="<?php echo $paid; ?>" id="paid" autocomplete="off" name="paids" placeholder="المرتب" >
                  <input type="text" onblur="(this.type='text')" value="<?php echo $starting; ?>" onfocus="(this.type='date')"  data-date="" data-date-format="YYYY-mm-DD" class="form-control" id="starting" autocomplete="off" name="startings" placeholder="بدايه العقد" >
                  <input type="text" onblur="(this.type='text')" value="<?php echo $expiring; ?>" onfocus="(this.type='date')"  data-date="" data-date-format="YYYY-mm-DD" class="form-control" id="expiring" autocomplete="off" name="expirings" placeholder="نهايه العقد">
                  <input type="text" class="form-control" value="<?php echo $visa; ?>" id="visa" autocomplete="off" name="visas" placeholder="رقم التأشيره">
                  <input type="text" onblur="(this.type='text')" value="<?php echo $enter_date; ?>" onfocus="(this.type='date')"  data-date="" data-date-format="YYYY-mm-DD" class="form-control" id="enter_date" autocomplete="off" name="enter_dates" placeholder="تاريخ الدخول">
                  <input type="text" list="browsersco" value="<?php echo $company; ?>" class="form-control" id="company" autocomplete="off" name="companys" placeholder="اسم الشركه">
                  <input type="text" list="browserscoadd" value="<?php echo $company_address; ?>" class="form-control" id="company_addresss" autocomplete="off" name="company_addresss" placeholder="موقع الشركه">
                  <input type="text" list="browserscodo" value="<?php echo $company_do; ?>" class="form-control" id="company_do" autocomplete="off" name="company_do" placeholder="تخصص الشركه">
                  <input type="text" list="browserscoac" value="<?php echo $company_action; ?>" class="form-control" id="company_action" autocomplete="off" name="company_action" placeholder="نشاط الشركه">
                  <input type="text" list="browsersi" value="<?php echo $groups; ?>" class="form-control" id="group" autocomplete="off" name="groups" placeholder="المجموعه">

                </div>
                <div id="modx"><?php if($_SESSION['error_u'] == "1"){ ?>
                <p id="error_msg" style="text-align:<?php echo lang('sponsored_align'); ?>;">
                    لا يمكنك تكرار رقم الجواز
                </p><?php } ?></div>
                <p class="success_msg" id="sent" style="text-align:<?php echo lang('sponsored_align'); ?>;display:none">
                    <?php echo lang('success_msg'); ?>
                </p>
                <div class="text-center mt-15 mb-25">
                    <button  type="submit" class="btn btn-success mx-auto with-btm" id="su_buy" name="post_nowe">
                        <span id="moves_enter">حفط</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
                    <div class="row">
                        <div class="col-12">
                            <div class="box">
                                <div class="box-header">
                                    <h4 class="box-title">
                                        بحث
                                    </h4>
                                </div>
                                <div class="box-body">
                                  <div class="form-group">
                                  <form method='post' action=''>
                                       <div class="input-group">

                                            <input type="text" class="form-control pull-right"  autocomplete="off"  placeholder="بحث.." name='fromDate' value='<?php if(isset($_POST['fromDate'])) echo $_POST['fromDate']; ?>'>
                                            <input type="text" class="form-control pull-right" list="browsersi" autocomplete="off" placeholder="رقم المجموعه" name='endDate' value='<?php if(isset($_POST['endDate'])) echo $_POST['endDate']; ?>'>
                              <button type='submit' name='but_search' class="waves-effect waves-light btn btn-info">بحث..</button>
                              </div>
                              </form>

                                </div>
                            </div>
                        </div>
                      </div>
                    </div>


                <!-- Tabel -->
                <div id="a" class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">البيانات العمال</h4>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">

                                    <table id="reports_1" class="table table-lg invoice-archive ">
                                        <thead>
                                        <tr><th>ر.م</th>
                                          <th> الاسم</th>
                                          <th> الجنسيه</th>
                                        <th> الجنس</th>
                                        <th> تايخ الميلاد</th>
                                        <th> رقم الجواز</th>
                                        <th> الؤهل العلمي</th>
                                        <th> التخصص</th>
                                        <th> سنوات الخبره</th>
                                        <th> المهنه\الوظيفه</th>
                                        <th> المرتب</th>
                                        <th> بدايه العقد</th>
                                        <th> نهايه العقد</th>
                                        <th> رقم التأشيره</th>
                                        <th> تاريخ الدخول</th>
                                        <th> اسم الشركه</th>
                                        <th> نشاط الشركه</th>
                                        <th> عنوان الشركه</th>
                                        <th>  تخصص الشركه</th>
                                        <th> تاريخ الادخال</th>

                                            <th class='text-center'><i class="fa fa-cog"></i></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        // include "config/connect.php";
                                        // $sid =  $_SESSION['id'];
                                        // $typey = "0";
                                        // $typeu = "transfar";
                                        // $vpsql = "SELECT * FROM transactions WHERE user_id=:sid AND bill=:type AND hide=:type AND type=:typeu ORDER BY datepost DESC";
                                        // $view_posts = $conn->prepare($vpsql);
                                        // $view_posts->bindParam(':typeu', $typeu, PDO::PARAM_INT);
                                        // $view_posts->bindParam(':type', $typey, PDO::PARAM_INT);
                                        // $view_posts->bindParam(':sid', $sid, PDO::PARAM_INT);
                                        // $view_posts->execute();
                                        $fromDate=$_POST['fromDate'];
                                    		$endDate=$_POST['endDate'];
                                      if (isset($_POST['but_search'])) {
if(!empty($fromDate)){
$a = "(passport='$fromDate' OR name='$fromDate' OR company='$fromDate' OR nationality='$fromDate')";
}else{
$a = "";
}
if(!empty($fromDate) && !empty($endDate)){
$b= "AND groupco='$endDate'";
}elseif(!empty($fromDate) && empty($endDate)){
  $b= "";
}else{
$b= "groupco='$endDate'";
}
                                    		$vpsql = "SELECT * FROM transaction WHERE $a $b";

                                        $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                                    }else{
                                    	$vpsql = "SELECT * FROM transaction";
                                    	$FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                                    }

                                        $num = count($transactions);//$view_posts->rowCount();
                                        foreach($FetchedData as $postsfetch)
                                        {
                                            $id = $postsfetch['id'];
                                            $name = $postsfetch['name'];
                                            $gender = $postsfetch['gender'];
                                            $birthdate = $postsfetch['birthdate'];
                                            $passport = $postsfetch['passport'];
                                            $education = $postsfetch['education'];
                                            $section = $postsfetch['section'];
                                            $qualification_years = $postsfetch['qualification_years'];
                                            $job = $postsfetch['job'];
                                            $paid = $postsfetch['paid'];
                                            $starting = $postsfetch['starting'];
                                            $expiring = $postsfetch['expiring'];
                                            $visa = $postsfetch['visa'];
                                            $nationality = $postsfetch['nationality'];
                                            $enter_date = $postsfetch['enter_date'];
                                            $company = $postsfetch['company'];
                                            $date = $postsfetch['date'];
                                            $company_do = $postsfetch['company_do'];
                                            $company_address = $postsfetch['company_address'];
                                            $company_action = $postsfetch['company_action'];
                                            //=================profit started===========================================
                                            //=============profit calc and select=====================

                                            $hdjd += 1;
                                            //=================table values start===========================
                                            echo"<tr id='tr_$id'>
                                                      <td> #$hdjd</td>
                                                    <td> $name</td>
                                                    <td> $nationality</td>
                                                    <td> $gender</td>
                                                    <td> $birthdate</td>
                                                    <td> $passport</td>
                                                    <td> $education</td>
                                                    <td> $section</td>
                                                    <td> $qualification_years</td>
                                                    <td> $job</td>
                                                    <td> $paid</td>
                                                    <td> $starting</td>
                                                    <td> $expiring</td>
                                                    <td> $visa</td>
                                                    <td> $enter_date</td>
                                                    <td> $company</td>
                                                    <td> $company_action</td>
                                                    <td> $company_address</td>
                                                    <td> $company_do</td>
                                                    <td> $date</td>

                                              <td class='text-center'>
                                                  <div class='list-icons d-inline-flex'>
                                                      <div class='list-icons-item dropdown'>
                                                          <a href='#' class='list-icons-item dropdown-toggle' data-toggle='dropdown'><i class='fa fa-file-text'></i></a>
                                                          <div class='dropdown-menu dropdown-menu-right'>

                                                              <a href='#' onclick=\"deletear('$id')\" style='color:#d71717' class='dropdown-item'><i class='fa fa-remove'></i> ".lang('delete')."</a>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </td>
                                            </tr>
                                                ";

                                        }
                                        ?></tbody>

                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
            <!-- /.content -->

        </div>
    </div>
    <!-- /.content-wrapper -->

    <?php //include "includes/footer.php";
    $this->load->view('includes/footer');
    ?>

    <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->



<!-- Modal -->
<script>
    window.addEventListener("load", function () {
        const loader = document.querySelector(".loader");
        loader.className += " hidden"; // class "loader hidden"
    });
</script>
<!-- Vendor JS -->
<script src="<?php echo base_url();?>Asset/js/vendors.min.js"></script>

<!-- Vendor Plugnin -->
<script src="<?php echo base_url();?>Asset/assets/vendor_components/chart.js-master/Chart.min.js"></script>
<script src="<?php echo base_url();?>Asset/js/pages/chartjs-int.js"></script>
<script src="<?php echo base_url();?>Asset/assets/vendor_components/select2/dist/js/select2.full.js"></script>
<script src="<?php echo base_url();?>Asset/assets/vendor_components/datatable/datatables.min.js"></script>
<script>
    if ($('.coins-exchange').length) {
        $('.coins-exchange').select2();
    }
    if ($('.money-exchange').length) {
        $('.money-exchange').select2();
    }
</script>
<script src="<?php echo base_url();?>Asset/assets/vendor_components/formatter/formatter.js"></script>
<script src="<?php echo base_url();?>Asset/assets/vendor_components/formatter/jquery.formatter.js"></script>

<!-- Crypto Tokenizer Admin App -->
<script src="<?php echo base_url();?>Asset/js/template.js"></script>
<script src="<?php echo base_url();?>Asset/js/demo.js"></script>
<script src="<?php echo base_url();?>Asset/js/pages/data-table.js"></script>
<script src="<?php echo base_url();?>Asset/js/pages/formatter.js"></script>


<!--====================================[  ]====================================-->

<!--====================================[  ]====================================-->
<script>
    $(document).ready(function(){
        $('.loadingPosting').hide();
        var i = 1;
        $("#postingToDBup").on('submit',function(e){

                            var plus = i++;
                            $("#getingNP").prepend("<div id='FetchingNewPostsDiv"+plus+"' style='display:none;'></div>");
                            e.preventDefault();
                            $(this).ajaxSubmit({
                                beforeSend:function(){
                                    $('.loadingPosting').show();
                                    $(".loadingPostingP").css({'width' : '0%'});
                                    $(".loadingPostingP").html('0');
                                    $(".success_msg").show();
                                },
                                uploadProgress:function(event,position,total,percentCompelete){
                                    $(".loadingPostingP").css({'width' : percentCompelete + '%'});
                                    $(".loadingPostingP").html(percentCompelete);
                                },
                                success:function(data){
                                    $('.post_textbox').css({'height':'95px'});
                                    $("#a").load(location.href + " #a");
                                    $("#list").load(location.href + " #list");
                                      $("#modx").load(location.href + " #modx");
                          $("#sent").fadeOut(800);
                                }
                            });

        });
    });
</script>
<script>
    $(document).ready(function(){
        $('.loadingPosting').hide();
        var i = 1;
        $("#postingToDB").on('submit',function(e){

                            var plus = i++;
                            $("#getingNP").prepend("<div id='FetchingNewPostsDiv"+plus+"' style='display:none;'></div>");
                            e.preventDefault();
                            $(this).ajaxSubmit({
                                beforeSend:function(){
                                    $('.loadingPosting').show();
                                    $(".loadingPostingP").css({'width' : '0%'});
                                    $(".loadingPostingP").html('0');
                                    $(".success_msg").show();
                                },
                                uploadProgress:function(event,position,total,percentCompelete){
                                    $(".loadingPostingP").css({'width' : percentCompelete + '%'});
                                    $(".loadingPostingP").html(percentCompelete);
                                },
                                success:function(data){
                                    $('.post_textbox').css({'height':'95px'});
                                    $('#w_photo').hide();
                                    $('#name').val('');
                                  $('#birthdate').val('');
                                  $('#passport').val('');
                                  $('#education').val('');
                                  $('#section').val('');
                                  $('#qualification_years').val('');
                                  $('#job').val('');
                                  $('#paid').val('');
                                  $('#starting').val('');
                                  $('#expiring').val('');
                                  $('#visa').val('');
                                  $('#enter_date').val('');
                                  $('#company').val('');
                                  $("#a").load(location.href + " #a");
                                    $("#list").load(location.href + " #list");
                                    $("#modc").load(location.href + " #modc");
                                    $(".success_msg").fadeOut(800);
                                }
                            });

        });
    });
</script>
<script>
function newz(){
  $("#info").show(300);
$("#update").hide(300);
}
function updatex(){
  $("#info").hide(300);
$("#update").show(300);
}
</script>

<script type="text/javascript">
function populateSecondTextBox() {
        document.getElementById('hjdjs').value = document.getElementById('search_name').value;
}
</script>
<!-- <script src="<?php echo base_url(); ?>Asset/js/jquery.form.js"></script> -->

<script src="<?php echo base_url(); ?>Asset/js/jquery.form.js"></script>

<?php // include("includes/endJScodes.php");
$this->load->view('includes/endJScodes');
?>
<div id="list" class="print">
  <div class="row">
      <div class="col-12">
          <div class="box">
              <div class="box-header with-border">
                  <h4 class="box-title">البيانات العمال</h4>
              </div>
              <div class="box-body">
                  <div class="table-responsive">
                <table id="reports_1" class="table table-lg invoice-archive ">
                    <thead>
                    <tr><th>ر.م</th>
                      <th> الاسم</th>
                      <th> الجنسيه</th>
                      <th> رقم الجواز</th>
                    <th> رقم التأشيره</th>
                    <th> تاريخ الدخول</th>

                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    // include "config/connect.php";
                    // $sid =  $_SESSION['id'];
                    // $typey = "0";
                    // $typeu = "transfar";
                    // $vpsql = "SELECT * FROM transactions WHERE user_id=:sid AND bill=:type AND hide=:type AND type=:typeu ORDER BY datepost DESC";
                    // $view_posts = $conn->prepare($vpsql);
                    // $view_posts->bindParam(':typeu', $typeu, PDO::PARAM_INT);
                    // $view_posts->bindParam(':type', $typey, PDO::PARAM_INT);
                    // $view_posts->bindParam(':sid', $sid, PDO::PARAM_INT);
                    // $view_posts->execute();
                    $fromDate=$_POST['fromDate'];
                    $endDate=$_POST['endDate'];
                  if (isset($_POST['but_search'])) {
if(!empty($fromDate)){
$a = "(passport='$fromDate' OR name='$fromDate' OR company='$fromDate' OR nationality='$fromDate')";
}else{
$a = "";
}
if(!empty($fromDate) && !empty($endDate)){
$b= "AND groupco='$endDate'";
}elseif(!empty($fromDate) && empty($endDate)){
$b= "";
}else{
$b= "groupco='$endDate'";
}
                    $vpsql = "SELECT * FROM transaction WHERE $a $b";

                    $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                }else{
                  $vpsql = "SELECT * FROM transaction";
                  $FetchedData=$this->comman_model->get_all_data_by_query($vpsql);
                }

                    $num = count($transactions);//$view_posts->rowCount();
                    foreach($FetchedData as $postsfetch)
                    {
                        $id = $postsfetch['id'];
                        $name = $postsfetch['name'];
                        $passport = $postsfetch['passport'];
                        $visa = $postsfetch['visa'];
                        $nationality = $postsfetch['nationality'];
                        $company = $postsfetch['company'];
                        $enter_date = $postsfetch['enter_date'];
                        //=================profit started===========================================
                        //=============profit calc and select=====================

                        $hdjd += 1;
                        //=================table values start===========================
                        echo"<tr id='tr_$id'>
                                  <td> #$hdjd</td>
                                <td> $name</td>
                                <td> $nationality</td>
                                <td> $passport</td>
                                <td> $visa</td>
                                <td> $enter_date</td>
                            </tr>
                            ";

                    }
                    ?></tbody>

                </table>
              </div>
          </div>
      </div>
  </div>
</div>
</div>
</body>
</html>
