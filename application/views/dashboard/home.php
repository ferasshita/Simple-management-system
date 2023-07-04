<?php

// ini_set('display_errors', 1);
// ini_set('log_errors', 1);

// error_reporting(E_ALL ^ E_NOTICE);
ini_set('error_log', dirname(__file__) . '/error_log.txt');
//session_start();
// if(!isset($_SESSION['Username'])){
//     header("location: index");
// }
// if($_SESSION['user_email_status'] == "not verified"){
// header("location:email_verification");}
// if($_SESSION['steps'] != "1"){
// header('location:steps?tc=shop');
// }
$getLang = trim(filter_var(htmlentities($_GET['lang']),FILTER_SANITIZE_STRING));
if (!empty($getLang)) {
    $_SESSION['language'] = $getLang;
}
//include "includes/pay_must.php";
//$this->load->view('includes/pay_must.php');
if ($_SESSION['chak'] == '1') {
    $url=base_url()."Dashboard";
    header("location: $url");
}
// ========================= config the languages ================================
// error_reporting(E_NOTICE ^ E_ALL);
// if (is_file('home.php')){
//     $path = "";
// }elseif (is_file('../home.php')){
//     $path =  "../";
// }elseif (is_file('../../home.php')){
//     $path =  "../../";
// }
//include ("includes/currency_codes.php");
//$this->load->view('includes/currency_codes.php');

$active = "home";

//include_once $path."langs/set_lang.php";
LoadLang();
?>
<!DOCTYPE html>
<html translate="no" lang="en">
<head>
    <title>Almaqar | Home</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php //include "includes/head_imports_main.php"
    $this->load->view('includes/head_imports_main.php');
    ?>
    <!-- Vendors Style-->
    <link rel="stylesheet" href="<?php echo base_url();?>Asset/css/vendors_css.css">
    <link rel="stylesheet" href="/path/to/cdn/bootstrap.min.css" />
    <link  href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet"><!-- Bootstrap is required -->
    <link rel="stylesheet" href="node_modules/bootstrap-steps/dist/bootstrap-steps.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
    <!-- Style-->
    <link rel="stylesheet" href="<?php echo base_url();?>Asset/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url();?>Asset/css/skin_color.css">
    <style>
      .flex-viewport {
          max-height: 90px;
      }.flexslider .slides img {
           height: 80px;
       }.theme-primary .btn-info:hover, .theme-primary .btn-info:active, .theme-primary .btn-info:focus, .theme-primary .btn-info.active {
            background-color: #d3e4e6 !important;
            border-color: #008ca1 !important;
            color: #ffffff;
        }
        .fix-ped{

        }
      @media (min-width: 992px) {
          .resize-width {
              flex: 0 0 100%;
              max-width: 100%;
          }
      }
      .aSetup{
          display: flex;
      }
      .aSetup_item{
          text-align: center;
          margin: auto;
      }
      .aSetup_item_empty_wallet{
          width: 40px;
          height: 40px;
          filter: grayscale(100%);
          background: url('asset/imgs/main_icons/042-wallet.png') no-repeat center center;
          background-size: 40px;
          margin: auto;
      }
      .aSetup_item_empty_bank{
          width: 40px;
      height: 40px;
      filter: grayscale(100%);
      background: url('asset/imgs/main_icons/040-bank.png') no-repeat center center;
      background-size: 40px;
      margin: auto;
      }
      .aSetup_item_empty_buy{
          width: 40px;
          height: 40px;
          filter: grayscale(100%);
          background: url('asset/imgs/main_icons/012-money bag.png') no-repeat center center;
          background-size: 40px;
          margin: auto;
      }
      .aSetup_item_empty_money{
          width: 40px;
          height: 40px;
          filter: grayscale(100%);
          background: url('asset/imgs/main_icons/046-money.png') no-repeat center center;
          background-size: 40px;
          margin: auto;
      }
      .aSetup_item_done1{
          width: 40px;
          height: 40px;
          background: url('asset/imgs/main_icons/042-wallet.png') no-repeat center center;
          background-size: 40px;
          margin: auto;
      }.aSetup_item_done2{
           width: 40px;
           height: 40px;
           background: url('asset/imgs/main_icons/040-bank.png') no-repeat center center;
           background-size: 40px;
           margin: auto;
       }.aSetup_item_done3{
            width: 40px;
            height: 40px;
            background: url('asset/imgs/main_icons/012-money bag.png') no-repeat center center;
            background-size: 40px;
            margin: auto;
        }.aSetup_item_done4{
             width: 40px;
             height: 40px;
             background: url('asset/imgs/main_icons/046-money.png') no-repeat center center;
             background-size: 40px;
             margin: auto;
         }
      .aSetup_progrDiv{
          margin: 10px 15px;
          background: #e9ebee;
          border-radius: 50px;
      }
      .aSetup_progrDiv p{
          width: 10%;
          background: #4bd37b;
          margin: 0;
          font-size: 13px;
          text-align: center;
          color: #fff;
          border-radius: 50px;
          height: 14px;
      }
      .loader {
          position: fixed;
          z-index: 99;
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



      #calc {
          padding: 0px 9px;
          background: #224662;
      }

      #display {
          background: #bcbcbc;
          padding: 8px;
          margin:16px 12px 10px 16px;
          text-align: center;
          font-family: 'Share Tech Mono', monospace;
          border-radius:8px;
          width: 95%;

      }

      #result p{
          font-size:1.8em;
      }

      #result,
      #previous {
          text-align: right;

      }

      #keyboard {
          display: inline-block;
          text-align: center;
          margin-bottom:8px;
      }

      .row {
          margin-top: 4px;
      }

      .last-row {
          float:left;
          margin-top: -11.5%;
      }

      button {
          width: 62px;
          margin: 2px;
      }

      .invisible {
          width:0;
      }

      .btn-zero {
          width: 134px;
      }

      .btn-result {
          float:right;
          margin-left:4px;
          height: 74px;
      }
      .theme-primary .btn-warning {
          width: 106%;
      }

     .fix-bold {font-weight:bold;}


    </style>
</head>

<body class="hold-transition <?php echo lang('html_dir'); ?> <?php $this->load->view('includes/mode.php'); ?> sidebar-mini theme-primary">
<div class="wrapper animate-bottom" id="wrapper_id" >
    <div class="loader">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>

    <!-- Left side column. contains the logo and sidebar -->
    <?php //include "includes/navbar_main.php";
    $this->load->view('includes/navbar_main.php');
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Main content -->
            <section id="up_section" class="content">

                <!-- API-->
                <?php //include"includes/tracker.php";
                $this->load->view('includes/tracker.php');
                ?>
                <!-- /.API -->
                <!-- Slider Show-->
                <div class="row">
                    <div class="col-lg-6 col-12 resize-width">
                        <div class="box">
                            <!-- /.box-header -->
                            <div class="box-body fix-ped">
                                <!-- Place somewhere in the <body> of your page -->
                                <div class="flexslider">
                                    <ul class="slides">
                                        <li>
                                            <img src="<?php echo base_url();?>Asset/imgs/main_icons/1.jpg" alt="slide" style="object-fit:cover;object-position:50% 50%;"/>
                                        </li>
                                        <li>
                                            <img src="<?php echo base_url();?>Asset/imgs/main_icons/cover.jpg" alt="slide" style="object-fit:cover;object-position:50% 50%;" />
                                        </li>
                                        <li>
                                            <img src="<?php echo base_url();?>Asset/imgs/main_icons/3.jpg" alt="slide" style="object-fit:cover;object-position:50% 50%;" />
                                        </li>
                                        <li>
                                            <img src="<?php echo base_url();?>Asset/imgs/main_icons/4.jpg" alt="slide" style="object-fit:cover;object-position:50% 50%;" />
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>
                <!-- /.Slider Show  -->

                <?php
                // include "config/connect.php";
                // $this->load->view('includes/tracker.php');
                // $sid = $_SESSION['id'];
                // $boss_id = $_SESSION['boss_id'];
                // $shop_id = $_SESSION['shop_id'];
                // $uid = $_SESSION['id'];
                // $sqlQ = "SELECT aSetup FROM signup WHERE id=:uid";
                // $sqlQ_check = $conn->prepare($sqlQ);
                // $sqlQ_check->bindParam(':uid',$uid,PDO::PARAM_INT);
                // $sqlQ_check->execute();
                // while ($aSetupDB = $sqlQ_check->fetch(PDO::FETCH_ASSOC)) {
                //     $aSetupFromDb = $aSetupDB['aSetup'];
                // }
                if ($aSetupFromDb != 100) {
                    ?>
                    <div id="steps" class="row">

                        <!--Box controls Buy and Sell Currency--->
                        <div class="col-md-12 col-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h4 class="box-title"><strong><?php echo lang('accountSetup') ?></strong></h4>
                                    <ul class="box-controls pull-right">
                                        <li><a class="box-btn-close" href="#"></a></li>
                                        <li><a class="box-btn-slide"  href="#"></a></li>
                                        <li><a class="box-btn-fullscreen" href="#"></a></li>
                                    </ul>
                                </div>

                                <!-- Buy and Sell Currency-->
                                <div class="box">
                                      <div class="box-body">
                                          <!--==========[ Account Setup ]=========-->
                                          <div class="aSetup"  align="center">
                                              <div class="aSetup_item">
                                                  <?php
                                                  $aSetupVal = array();?>
                                                  <?php
                                                //   $uid = $_SESSION['id'];
                                                //   $sqlQ = "SELECT * FROM capital WHERE boss_id=:boss_id";
                                                //   $sqlQ_check = $conn->prepare($sqlQ);
                                                //   $sqlQ_check->bindParam(':boss_id',$boss_id,PDO::PARAM_INT);
                                                //   $sqlQ_check->execute();

                                                  //$sqlQ_checkCount = $sqlQ_check->rowCount();
                                                  $sqlQ_checkCount = $capitalCount;

                                                  if ($sqlQ_checkCount > 0) {
                                                      $cFollowClass = "aSetup_item_done1";
                                                      $cFollowColor = "color: #4bd37b;";
  //                                                            $cFollowBackGround = "background: url('asset/imgs/main_icons/040-bank.png') no-repeat center center;";
                                                      if (!in_array('followPeople', $aSetupVal)) {
                                                          array_push($aSetupVal,'followPeople');
                                                      }
                                                  }else{
                                                      $cFollowClass = "aSetup_item_empty_wallet";
                                                      $cFollowColor = "color: #c3c3c3;";
                                                  }
                                                  ?>
                                                  <div class="<?php echo $cFollowClass; ?>"></div>
                                                  <p style="<?php echo $cFollowColor; ?>"><?php echo lang('Treasury') ?></p>
                                                  <?php if(!in_array('followPeople', $aSetupVal)){ ?><a href="<?php echo base_url(); ?>wallet/index"><?php echo lang('complete_cauptal') ?></a><?php } ?>
                                              </div>
                                              <div class="aSetup_item">
                                                  <?php

                                                //   $uid = $_SESSION['id'];
                                                //   $sqlQ = "SELECT * FROM bank WHERE boss_id=:boss_id";
                                                //   $sqlQ_checko = $conn->prepare($sqlQ);
                                                //   $sqlQ_checko->bindParam(':boss_id',$boss_id,PDO::PARAM_INT);
                                                //   $sqlQ_checko->execute();

                                                  //$sqlQ_checkCounto = $sqlQ_checko->rowCount();
                                                  $sqlQ_checkCounto = $bankCount;
                                                  if ($sqlQ_checkCounto > 0) {
                                                      $cCphotoClass = "aSetup_item_done2";
                                                      $cCphotoColor = "color: #4bd37b;";
                                                      if (!in_array('uCoverPhoto', $aSetupVal)) {
                                                          array_push($aSetupVal,'uCoverPhoto');
                                                      }
                                                  }else{
                                                      $cCphotoClass = "aSetup_item_empty_bank";
                                                      $cCphotoColor = "color: #c3c3c3;";
                                                  }
                                                  ?>
                                                  <div class="<?php echo $cCphotoClass; ?>"></div>
                                                  <p style="<?php echo $cCphotoColor; ?>"><?php echo lang('bank') ?></p>
                                                  <?php if(!in_array('uCoverPhoto', $aSetupVal)){ ?><a href="<?php echo base_url(); ?>Setting/mybank"><?php echo lang('complete_bank') ?></a><?php } ?>
                                              </div>
                                              <div class="aSetup_item">

                                                  <?php

                                                //   $sqlQ = "SELECT * FROM transactions WHERE user_id=:boss_id";
                                                //   $sqlQ_checkg = $conn->prepare($sqlQ);
                                                //   $sqlQ_checkg->bindParam(':boss_id',$sid,PDO::PARAM_INT);
                                                //   $sqlQ_checkg->execute();

                                                  $sqlQ_checkCountg = $TransCount;//$sqlQ_checkg->rowCount();
                                                  if ($sqlQ_checkCountg > 0) {
                                                      $cUphotoClass = "aSetup_item_done3";
                                                      $cUphotoColor = "color: #4bd37b;";
                                                      if (!in_array('Userphoto', $aSetupVal)) {
                                                          array_push($aSetupVal,'Userphoto');
                                                      }
                                                  }else{
                                                      $cUphotoClass = "aSetup_item_empty_buy";
                                                      $cUphotoColor = "color: #c3c3c3;";
                                                  }
                                                  ?>
                                                  <div class="<?php echo $cUphotoClass; ?>"></div>
                                                  <p style="<?php echo $cUphotoColor; ?>"><?php echo lang('buy') ?></p>
                                                  <?php if(!in_array('Userphoto', $aSetupVal)){ if($sqlQ_checkCounto > 0 && $sqlQ_checkCount > 0){?><a href="<?php echo base_url(); ?>Transaction/Cash"><?php echo lang('complete_sell') ?></a><?php } } ?>
                                              </div>
                                              <div class="aSetup_item">
                                                  <?php
                                                //   $uid = $_SESSION['id'];
                                                //   $sqlQ = "SELECT * FROM expenses WHERE boss_id=:boss_id";
                                                //   $sqlQ_checki = $conn->prepare($sqlQ);
                                                //   $sqlQ_checki->bindParam(':boss_id',$boss_id,PDO::PARAM_INT);
                                                //   $sqlQ_checki->execute();
                                                  $sqlQ_checkCounti = $ExpensesCount;//$sqlQ_checki->rowCount();
                                                  if ($sqlQ_checkCounti > 0) {
                                                      $cInfoClass = "aSetup_item_done4";
                                                      $cInfoColor = "color: #4bd37b;";
                                                      if (!in_array('CompleteInfo', $aSetupVal)) {
                                                          array_push($aSetupVal,'CompleteInfo');
                                                      }
                                                  }else{
                                                      $cInfoClass = "aSetup_item_empty_money";
                                                      $cInfoColor = "color: #c3c3c3;";
                                                  } ?>
                                                  <div class="<?php echo $cInfoClass; ?>"></div>
                                                  <p style="<?php echo $cInfoColor; ?>"><?php echo lang('buythings') ?></p>
                                                  <?php if(!in_array('CompleteInfo', $aSetupVal)){ if($sqlQ_checkCounto > 0 && $sqlQ_checkCount > 0){ ?><a href="<?php echo base_url(); ?>Expenses/index"><?php echo lang('complete_exp') ?></a><?php } } ?>
                                              </div>

                                          </div>
                                          <div class="aSetup_progrDiv" style="text-align: <?php echo lang('textAlign'); ?>">
                                              <?php
                                              $aSetupVal = count($aSetupVal);
                                              switch ($aSetupVal) {
                                                  case '1':
                                                      $aSetupProg = "25";
                                                      break;
                                                  case '2':
                                                      $aSetupProg = "50";
                                                      break;
                                                  case '3':
                                                      $aSetupProg = "75";
                                                      break;
                                                  case '4':
                                                      $aSetupProg = "100";?>
                                                      <style>#steps{display:none}</style>
                                                      <?php
                                                      break;
                                                  default:
                                                      $aSetupProg = "0";
                                                      break;
                                              }
                                              ?>
                                              <p style="width: <?php echo $aSetupProg; ?>%;"><?php if($aSetupProg > 0){echo $aSetupProg.'%';} ?></p>
                                          </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    // if ($aSetupProg == 100 ) {
                    //     $uid = $_SESSION['id'];
                    //     $sqlQ = "UPDATE signup SET aSetup = :aSetupProg WHERE id = :uid";
                    //     $sqlQ_check = $conn->prepare($sqlQ);
                    //     $sqlQ_check->bindParam(':aSetupProg',$aSetupProg,PDO::PARAM_INT);
                    //     $sqlQ_check->bindParam(':uid',$uid,PDO::PARAM_INT);
                    //     $sqlQ_check->execute();
                    //     echo "<script>$('#AccountSetup').html('');</script>";
                    // }
                }
                ?>
                <!--====================================[ shortcut buttons ]====================================-->




                <!-- Chart and  Buy & Sell Currency-->
                <div class="row">

                    <!--Box controls Buy and Sell Currency--->
                    <div class="col-md-6 col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title"><strong><?php echo lang('quick_transaction'); ?></strong></h4>
                                <ul class="box-controls pull-right">
                                    <li><a class="box-btn-close" href="#"></a></li>
                                    <li><a class="box-btn-slide"  href="#"></a></li>
                                    <li><a class="box-btn-fullscreen" href="#"></a></li>
                                    <li><a class="box-btn" id="swap" href="#"><span class="fa fa-refresh"></span></a></li>
                                </ul>
                            </div>

                            <!-- Buy and Sell Currency-->
                            <div class="box">

                                <form class="form" id="postingToDB" action="Transaction/wtransaction" method="post" enctype="multipart/form-data">
                                    <div class="box-body">
                                        <div class="tab-content">
                                            <div id="navpills2-1" class="tab-pane active">

                                                <div class="form-group">
                                                    <input type="text" name="name" list="browsers" autocomplete="off" class="form-control" <?php if($_SESSION['title_h'] == "1"){echo "title='".lang('cosname_h')."'";} ?> placeholder="<?php echo lang('name'); ?>">
                                                </div>
                                                <datalist id="browsers">
                                                <?php
                                                $sid =  $_SESSION['id'];
                                                $shopo =  $_SESSION['shop_id'];
                                                $typo =  $_SESSION['type'];
                                                // if($typo == "admin"){
                                                // $fetchUsers_sql = "SELECT id FROM signup WHERE shop_id='$shopo'";
                                                // $fetchUsers = $conn->prepare($fetchUsers_sql);
                                                // $fetchUsers->execute();
                                                // }else{
                                                // $fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$sid' OR id='$sid'";
                                                // $fetchUsers = $conn->prepare($fetchUsers_sql);
                                                // $fetchUsers->execute();
                                                // }
                                                // while ($rows = $fetchUsers->fetch(PDO::FETCH_ASSOC))
                                                // {
                                                $gfid = $FetchUserID;//$rows['id'];
                                                // $vpsql = "SELECT DISTINCT cos_id FROM cos_transactions WHERE user_id=:sid";
                                                // $view_posts = $conn->prepare($vpsql);
                                                // $view_posts->bindParam(':sid', $gfid, PDO::PARAM_INT);
                                                // $view_posts->execute();
                                                //while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                                                $cos_id = $cosid;//$postsfetch['cos_id'];

                                                // $vpsql = "SELECT name FROM costumers WHERE user_id=:sid AND main_id=:tyo AND name !='casher'";
                                                // $view_postsi = $conn->prepare($vpsql);
                                                // $view_postsi->bindParam(':sid', $gfid, PDO::PARAM_INT);
                                                // $view_postsi->bindParam(':tyo', $cos_id, PDO::PARAM_INT);
                                                // $view_postsi->execute();

                                                //while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
                                                $namec = $name;//$postsfetch['name'];
                                                echo"<option value='$namec'>";
                                                //}
                                            //}
                                           // }
                                                ?>
                                                </datalist>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="prus" <?php if($_SESSION['title_h'] == "1"){echo "title='".lang('prus_h')."'";} ?> autocomplete="off" oninput="validateNumbera(this);" value="<?php echo $_SESSION['exchange_rate']; ?>" inputmode="decimal" onkeypress="div_mul()" placeholder="<?php echo lang('price'); ?>" onkeyup="conve()" name="price" >
                                                </div>


                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <select class="form-control" name="received_name" id="ama" onchange="countryChange(this);" data-toggle="dropdown">
                                                              <?php foreach($currencies_b as $key => $value) { ?>
                                                              <option value="<?= htmlspecialchars($key) ?>" title="<?= $value ?>"><?= htmlspecialchars($key) ?></option>
                                                              <?php } ?>
                                                            </select>
                                                        </div>
                                                        <input type="text" class="form-control" <?php if($_SESSION['title_h'] == "1"){echo "title='".lang('taken_h')."'";} ?> inputmode="decimal" onkeypress="div_mul()" placeholder="<?php echo lang('taken'); ?>" name="amou" onkeyup="conve()" oninput="validateNumberb(this);" autocomplete="off" id="un">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <select class="form-control" onchange="countryChangeb(this);" name="given_name" id="amb">
                                                              <?php foreach($currencies_a as $key => $value) { ?>
                                                              <option value="<?= htmlspecialchars($key) ?>" title="<?= $value ?>"><?= htmlspecialchars($key) ?></option>
                                                              <?php } ?>
                                                            </select>
                                                        </div>
                                                        <input type="text" class="form-control" <?php if($_SESSION['title_h'] == "1"){echo "title='".lang('given_h')."'";} ?> inputmode="decimal" placeholder="<?php echo lang('given'); ?>" onkeypress="div_mul()" id="pd" name="lyamou" oninput="validateNumberc(this);" onkeyup="convel()" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div style="display:none">

                                                    <input class="mdc-radio__native-control" type="radio" name="multd" id="mult" value="muly" checked>

                                                    <input class="mdc-radio__native-control" type="radio" name="multd" id="divi" value="diviy">
                                                </div>
                                                <input type="hidden" name="kin" id="sellbuy" value="">
                                                <div id="mcod">
                                                    <?php
                                                    if($_SESSION['myerror'] != ""){
                                                        echo"<a href='wallet'><div id='error_msg'>";
                                                        echo $_SESSION['myerror'];
                                                        echo"</div></a>";
                                                    }
                                                    ?>
                                                </div>
                                                <p class="success_msg" style="text-align:<?php echo lang('sponsored_align'); ?>;display:none">
                                                    <?php echo lang('success_msg'); ?>
                                                </p>
                                                <?php
                                                $ti = time();
                                                $yy = date('Y');
                                                $mm = date('m');
                                                $dd = date('d');
                                                ?>
                                                <?php
                                                // $sid= $_SESSION['id'];
                                                // $check = $conn->prepare("SELECT * FROM treasury WHERE user_id =:sid");
                                                // $check->bindParam(':sid',$sid,PDO::PARAM_INT);
                                                // $check->execute();
                                                //while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
                                                    $chR=$treasuryData;
                                                    $kind = $chR['kind'];
                                                    $number = $chR['number'];
                                                    echo"<input type='hidden' id='$kind' value='$number'>";
                                                //}
                                                ?>
                                                <input type="hidden" id="datetimef" name="time">
                                                <input type="hidden" value="<?php echo $mm."/".$dd."/".$yy; ?>" name="date">
                                                <script>
                                                    setInterval(function(){
                                                        var dt = new Date();
                                                        document.getElementById("datetimef").value = dt.toLocaleString("en-LY");
                                                    }, 1000);
                                                </script>
                                                <button type="submit" class="waves-effect waves-light btn btn-warning mt-10 d-block w-p100" id="su_buy" name="post_now"><span id="moves_enter"><?php echo lang('sell_tr'); ?></span></button>
                                            </div>

                                        </div>
                                    </div>
                            </div>
                            </form>
                            <!-- /. Buy & Sell Currency -->
                        </div>
                    </div>

                    <!--Box controls Buy and Sell Currency--->
                    <div class="col-md-6 col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title"><strong><?php echo lang('chart_of_profit'); ?></strong></h4>
                                <ul class="box-controls pull-right">
                                    <li><a class="box-btn-close" href="#"></a></li>
                                    <li><a class="box-btn-slide"  href="#"></a></li>
                                    <li><a class="box-btn-fullscreen" href="#"></a></li>
                                </ul>
                            </div>

                            <!-- Chart of Buy & Sell-->
                            <div class="box">
                                <div class="box-body">
                                    <div id="line-chart"></div>
                                </div>
                            </div>
                            <!-- /. Chart of Buy & Sell -->
                        </div>
                    </div>

                </div>
                <!-- /. Chart and Buy & Sell Currency -->

                <!--Pie Chart of  Sell-->
                <div class="row">
                    <!--Box controls Chart of  Sell-->
                    <div class="col-md-6 col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title"><strong><?php echo lang('Calc'); ?></strong></h4>
                                <ul class="box-controls pull-right">
                                    <li><a class="box-btn-close" href="#"></a></li>
                                    <li><a class="box-btn-slide"  href="#"></a></li>
                                    <li><a class="box-btn-fullscreen" href="#"></a></li>
                                </ul>
                            </div>
                            <?php
                            // $yy = date('Y');
                            // $ryt = "$yy-01-01";
                            // $ryd = "$yy-12-31";
                            //include "config/connect.php";
                            // include ("includes/time_function.php");
                            // include ("includes/num_k_m_count.php");

                            //$this->load->view('includes/time_function.php');
                            $t=time();
                            time_ago($t);
                           // $this->load->view('includes/num_k_m_count.php');

                            // $sid =  $_SESSION['id'];
                            // $shopo =  $_SESSION['shop_id'];
                            // $typo =  $_SESSION['type'];
                            $delo = "0";
                            $invest = "invest";
                            //=================start of the archive=======================
                            // if($typo == "admin"){
                            //     $fetchUsers_sql = "SELECT id FROM signup WHERE shop_id='$shopo'";
                            //     $fetchUsers = $conn->prepare($fetchUsers_sql);
                            //     $fetchUsers->execute();
                            // }else{
                            //     $fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$sid' OR id='$sid'";
                            //     $fetchUsers = $conn->prepare($fetchUsers_sql);
                            //     $fetchUsers->execute();
                            // }
                           // while ($rows = $fetchUsers->fetch(PDO::FETCH_ASSOC)) {
                                $gfid = $Archive_id;///$rows['id'];
                                // $vpsql = "SELECT * FROM transactions WHERE user_id=:sid AND hide=:delo AND type!=:invest AND (datepost BETWEEN '".$ryt."' and '".$ryd."') ORDER BY datepost DESC";
                                // $view_posts = $conn->prepare($vpsql);
                                // $view_posts->bindParam(':sid', $gfid, PDO::PARAM_INT);
                                // $view_posts->bindParam(':invest', $invest, PDO::PARAM_INT);
                                // $view_posts->bindParam(':delo', $delo, PDO::PARAM_INT);
                                // $view_posts->execute();
                                //$numcos = $view_posts->rowCount();
                                foreach ($info as $postsfetch ) {
                                    /*
                                  &  exchange = exchange rate
                                  * received_name = taken type
                                  * received = taken number
                                  ^ given_name = given type
                                  ^ amountsd = given number
                                     */
                                    $addonc += 1;
                                    $id = $postsfetch['id'];
                                    $post_id = $postsfetch['post_id'];
                                    $user_id = $postsfetch['user_id'];
                                    $exchange = $postsfetch['exchange'];
                                    $received_name = $postsfetch['received_name'];
                                    $received = $postsfetch['received'];
                                    $given_name = $postsfetch['given_name'];
                                    $kind = $postsfetch['kin'];
                                    $amountsd = $postsfetch['given'];
                                    $time = $postsfetch['time'];
                                    $type = $postsfetch['type'];
                                    if($kind == "buy" || $kind == "بيع"){
                                        $buyadd += 1;
                                    }else{
                                        $selladd += 1;
                                    }
                                    //=================profit started===========================================
                                    //=============profit calc and select=====================
                                    if($kind == "buy" || $kind == "بيع"){
                                        $media = $postsfetch['media'];
                                        if($received_name == $given_name){
                                            $amstv = $received*$exchange;
                                            $ambtv = $amountsd*$exchange;
                                            $cdaexf = $amstv-$ambtv;  }else{
                                            $cdaex = $amountsd*$media;
                                            $cdaegx = number_format("$cdaex",1, ".", "");
                                            $hytu = $received-$cdaegx+0;
                                            $cdaexf = number_format("$hytu",2, ".", "");
                                        }
                                    }
                                    $hhfdj += $cdaexf;

                                    if($type == "cash"){
                                        $kindd = lang('Cash');
                                    }elseif($type == "chak"){
                                        $kindd = lang('chak');
                                    }elseif($type == "cards"){
                                        $kindd = lang('cards');
                                    }elseif($type == "transfar"){
                                        $kindd = lang('transfar');
                                    }}
                                //} ?>
                            <!-- Chart of Buy & Sell-->
                            <div class="box">
                                <main role="main" style="display: flex; justify-content: center; align-items: center; ">
                                    <!-- This will be the fixed size of the calculator -->
                                    <div style="width: 400px;">
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <!-- You already have container-fluid up there, so this is not doing much... -->
                                                <!-- The documentation recommends using only one container when possible -->
                                                <!-- Also, fluid will make the calculator grow, and you want a fixed size -->
                                                <div class="card" id="calc">

                                                    <!-- Added p-2 to match buttons bellow -->
                                                    <div class="row p-2 mt-3 mb-2">
                                                        <!-- col-10 and offset-1 here makes the input be "smalled" than the buttons -->
<!--                                                        <div class="col-12"><input type="text" class="form-control text-right" value="0"></div>-->
                                                        <div id="display">
                                                            <div id="result"><p>0</p></div>
                                                            <div id="previous"><p>0</p></div>
                                                        </div>
                                                    </div>

                                                    <!-- mr-3 here is adding a margin and making things not aligned -->
                                                    <div class="p-2">

                                                        <!-- Replacing "my-1 mx-1" with "mb-2", since the columns in bootstrap already have a padding -->
                                                        <div class="row text-center mb-2">
                                                            <!-- w-100 will make the button take all the available space -->
                                                            <div class="col-3"><button type="button" class="btn-danger " value="ac">AC</button></div>
                                                            <div class="col-3"><button type="button" class="btn-danger "value="ce">CE</button></div>
                                                            <div class="col-3"><button type="button" class="btn-warning "value="*"><i class="fa fa-times"></i></button></div>
                                                            <div class="col-3"><button type="button" class="btn-warning"value="/">/</button></div>
                                                        </div>

                                                        <div class="row text-center mb-2">
                                                            <div class="col-3"><button type="button" class="btn btn-info "value="7">7</button></div>
                                                            <div class="col-3"><button type="button" class="btn btn-info "value="8">8</button></div>
                                                            <div class="col-3"><button type="button" class="btn btn-info "value="9">9</button></div>
                                                            <div class="col-3"><button type="button" class="btn-warning "value="+">+</button></div>
                                                        </div>

                                                        <div class="row text-center mb-2">
                                                            <div class="col-3"><button type="button" class="btn btn-info "value="4">4</button></div>
                                                            <div class="col-3"><button type="button" class="btn btn-info "value="5">5</button></div>
                                                            <div class="col-3"><button type="button" class="btn btn-info "value="6">6</button></div>
                                                            <div class="col-3"><button type="button" class="btn-warning "value="-">-</button></div>

                                                        </div>

                                                        <div class="row text-center mb-2">
                                                            <div class="col-3"><button type="button" class="btn btn-info "value="3">3</button></div>
                                                            <div class="col-3"><button type="button" class="btn btn-info "value="2">2</button></div>
                                                            <div class="col-3"><button type="button" class="btn btn-info " value="1">1</button></div>
                                                            <div class="col-3"><button type="button" class="btn-warning " value=".">.</button></div>
                                                        </div>

                                                        <div class="row text-center">
                                                            <div class="col-3"><button type="button" class="btn btn-info "value="0">0</button></div>
                                                            <div class="col-3"><button type="button" class="btn btn-success" style="width: 372%;" value="=">=</button></div>
                                                        </div>
                                                        <!-- You don't need a <br> here, it is better to use margin or padding in this case -->
                                                    </div>

                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </main>
                            </div>
                            <!-- /. Chart of Buy & Sell -->
                        </div>
                    </div>

                    <!--Box controls Total-->
                    <div class="col-md-6 col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title"><strong><?php echo lang('summery'); ?></strong></h4>
                                <ul class="box-controls pull-right">
                                    <li><a class="box-btn-close" href="#"></a></li>
                                    <li><a class="box-btn-slide"  href="#"></a></li>
                                    <li><a class="box-btn-fullscreen" href="#"></a></li>
                                </ul>
                            </div>
                            <div class="row">
                                <!-- Total-->
                                <div class="col-md-6 col-12">
                                    <a class="box box-link-shadow text-center" href="javascript:void(0)">
                                        <div class="box-body">
                                            <div class="font-size-24"><?php if($buyadd == ""){echo "0";}else{echo "$buyadd";} ?>
                                            </div>
                                            <span><?php echo lang('buy_tr'); ?></span>
                                        </div>
                                        <div class="box-body bg-info">
                                            <p>
                                                <span class="mdi mdi-ticket-confirmation font-size-30"></span>
                                            </p>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-6 col-12">
                                    <a class="box box-link-shadow text-center" href="javascript:void(0)">
                                        <div class="box-body">
                                            <div class="font-size-24"><?php if($selladd == ""){echo "0";}else{echo "$selladd";} ?></div>
                                            <span><?php echo lang('sell_tr'); ?></span>
                                        </div>
                                        <div class="box-body bg-warning">
                                            <p>
                                                <span class="mdi mdi-telegram font-size-30"></span>
                                            </p>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-6 col-12">
                                    <a class="box box-link-shadow text-center" href="javascript:void(0)">
                                        <div class="box-body">
                                            <div class="font-size-24"><?php if($addonc == ""){echo "0";}else{echo "$addonc";} ?></div>
                                            <span><?php echo lang('transactions'); ?></span>
                                        </div>
                                        <div class="box-body bg-success">
                                            <p>
                                                <span class="mdi fa-money font-size-30"></span>
                                            </p>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-6 col-12">
                                    <a class="box box-link-shadow text-center" href="javascript:void(0)">
                                        <div class="box-body">
                                            <div class="font-size-24"><?php if($hhfdj == ""){echo "0";}else{echo "$hhfdj";} ?></div>
                                            <span><?php echo lang('Profit'); ?></span>
                                        </div>
                                        <div class="box-body bg-danger">
                                            <p>
                                                <span class="mdi mdi-coin font-size-30"></span>
                                            </p>
                                        </div>
                                    </a>
                                </div>
                                <!-- /. Total -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.Pie Chart of  Sell-->

            </section>
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->
    <?php
    //include "includes/footer.php";
    $this->load->view('includes/footer');
    ?>

    <!-- Control Sidebar -->
    <!-- /.control-sidebar -->


</div>

<!-- ./wrapper -->
<?php //include "includes/graph.php";
$this->load->view('includes/graph');
?>

<script>
    window.addEventListener("load", function () {
        const loader = document.querySelector(".loader");
        loader.className += " hidden"; // class "loader hidden"
    });
</script>
<!-- Vendor JS -->
<script src="<?php echo base_url();?>Asset/js/vendors.min.js"></script>

<script src="<?php echo base_url();?>Asset/assets/vendor_components/apexcharts-bundle/irregular-data-series.js"></script>
<script src="<?php echo base_url();?>Asset/assets/vendor_components/apexcharts-bundle/dist/apexcharts.js"></script>
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<script src="<?php echo base_url();?>Asset/assets/vendor_components/Web-Ticker-master/jquery.webticker.min.js"></script>

<script src="<?php echo base_url();?>Asset/assets/vendor_plugins/bootstrap-slider/bootstrap-slider.js"></script>
<script src="<?php echo base_url();?>Asset/assets/vendor_components/OwlCarousel2/dist/owl.carousel.js"></script>
<script src="<?php echo base_url();?>Asset/assets/vendor_components/flexslider/jquery.flexslider.js"></script>
<script src="<?php echo base_url();?>Asset/js/pages/web-ticker.js"></script>
<script src="<?php echo base_url();?>Asset/assets/vendor_components/echarts/dist/echarts-en.min.js"></script>

<script src="<?php echo base_url();?>Asset/assets/vendor_components/c3/d3.min.js"></script>
<script src="<?php echo base_url();?>Asset/assets/vendor_components/c3/c3.min.js"></script>

<!-- Crypto Tokenizer Admin App -->
<script src="<?php echo base_url();?>Asset/js/template.js"></script>
<script src="<?php echo base_url();?>Asset/js/pages/dashboard.js"></script>
<script src="<?php echo base_url();?>Asset/js/pages/slider.js"></script>
<script src="<?php echo base_url();?>Asset/js/demo.js"></script>
<!--<script src="<?php echo base_url();?>Asset/js/pages/echart-pie-doghnut.js"></script>-->
<!--<script src="<?php echo base_url();?>Asset/js/pages/c3-line.js"></script>-->
<script src="<?php echo base_url();?>Asset/js/pages/c3-stap-line.js"></script>
<script src="<?php echo base_url();?>Asset/js/pages/c3-multi-xy-line.js"></script>

<script>
    function sub_bills(){
        $('#sub_bills').click();
    }
</script>
<script type="text/javascript">
    function conve(){
        var input1 = document.getElementById('un');
        var input2 = document.getElementById('pd');
        var prus = document.getElementById('prus');
        var ama = document.getElementById('ama');
        var amb = document.getElementById('amb');
        var mult = document.getElementById('mult');
        var divi = document.getElementById('divi');
        var fye = input2.value;
        var ytu = input1.value;
        var prusd = prus.value;
        var amai = ama.value;
        var ambi = amb.value;
        if(prusd == ""){
            prus.style.borderColor = "red";
        }else if(prusd == 0){
            prus.style.borderColor = "red";
        }else{
            prus.style.borderColor = "#688cb4";
            if(mult.checked){
//dollar
                var x = prusd*ytu;
            }else{
                var x = ytu/prusd;
            }
            input2.value = x.toFixed(2);
        }
    }
</script>
<script>
    function convel(){
        var input1 = document.getElementById('un');
        var input2 = document.getElementById('pd');
        var prus = document.getElementById('prus');
        var ama = document.getElementById('ama');
        var amb = document.getElementById('amb');
        var mult = document.getElementById('mult');
        var divi = document.getElementById('divi');
        var fye = input2.value;
        var ytu = input2.value;
        var prusd = prus.value;
        var amai = ama.value;
        var ambi = amb.value;
        if(prusd == ""){
            alert("<?php echo lang('excenter'); ?> ");
            prus.style.borderColor = "red";
        }else if(prusd == 0){
            prus.style.borderColor = "red";
        }else{
            prus.style.borderColor = "#688cb4";
            if(mult.checked){
//dollar
                var x = ytu/prusd;
            }else{
                var x = ytu*prusd;
            }
            input1.value = x.toFixed(2);
        }
    }
</script>
<script type="text/javascript">
    function flipr(){
        $('#flrigh').attr('onclick','flipl()');
        $('#flrigh').addClass('fa-arrow-right');
        $('#flrigh').removeClass('fa-arrow-left');
        $('#pd').attr('onkeyup','convel()');
        $('#un').attr('onkeyup','');
        $('#prus').attr('onkeyup','convel()');
        var mult = document.getElementById('mult');
        var divi = document.getElementById('divi');
        if(mult.checked){
            //dollar
            divi.checked = true;
        }else{
            mult.checked = true;
        }
    }
    function flipl(){
        $('#flrigh').attr('onclick','flipr()');
        $('#flrigh').removeClass('fa-arrow-right');
        $('#flrigh').addClass('fa-arrow-left');
        $('#pd').attr('onkeyup','');
        $('#un').attr('onkeyup','conve()');
        $('#prus').attr('onkeyup','conve()');
        var mult = document.getElementById('mult');
        var divi = document.getElementById('divi');
        if(mult.checked){
            //dollar
            divi.checked = true;
        }else{
            mult.checked = true;
        }
    }
</script>
<script>
    function div_mul() {
        var ambb = document.getElementById('amb');
        var ambx = ambb.selectedIndex;
        var ambch = ambb.options[ambx].value;
        var amaa = document.getElementById('ama');
        var amax = amaa.selectedIndex;
        var amach = amaa.options[amax].value;
        var divit = document.getElementById('divi');
        var multor = document.getElementById('mult');
        var prus = document.getElementById('prus');
        var prusd = prus.value;
        if(amach == "LYD" || ambch != "LYD"){
            if(prus > 1){
                multor.checked = true;
            }else{
                divit.checked = true;
            }
        }else if(amach != "LYD" || ambch == "LYD"){
            if(prus > 1){
                divit.checked = true;
            }else{
                multor.checked = true;
            }

        }
    }
</script>
<script type="text/javascript">
    //<![CDATA[
    // array of possible countries in the same order as they appear in the country selection list
    var countryLists = new Array(4)
    countryLists["USD"] = ["LYD", "USD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    countryLists["EU"] = ["LYD", "USD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    countryLists["DT"] = ["LYD", "USD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    countryLists["TRY"] = ["LYD", "USD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    countryLists["EGP"] = ["LYD", "USD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    countryLists["GBP"] = ["LYD", "USD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    countryLists["SAR"] = ["LYD", "USD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    countryLists["RM"] = ["LYD", "USD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    countryLists["LYD"] = ["USD", "LYD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    /* CountryChange() is called from the onchange event of a select element.
    * param selectObj - the select object which fired the on change event.
    */
    function countryChangeb(selectObj) {
        // get the index of the selected option
        var idx = selectObj.selectedIndex;
        // get the value of the selected option
        var which = selectObj.options[idx].value;
        var ambb = document.getElementById('ama');
        var ambx = ambb.selectedIndex;
        var ambch = ambb.options[ambx].value;
        // use the selected option value to retrieve the list of items from the countryLists array
        cList = countryLists[which];

        var divit = document.getElementById('divi');
        var multor = document.getElementById('mult');
        var prus = document.getElementById('prus');
        var prusd = prus.value;
        if(which == "LYD" || ambch != "LYD"){
            if(prus > 1){
                multor.checked = true;
            }else{
                divit.checked = true;
            }
        }else if(which != "LYD" || ambch == "LYD"){
            if(prus > 1){
                divit.checked = true;
            }else{
                multor.checked = true;
            }

        }
        if(which == "LYD"){
            document.getElementById('moves_enter').innerHTML = "<?php echo lang('buy_tr'); ?>";
            document.getElementById('su_buy').style.background = "#18d26b";
            document.getElementById('su_buy').style.border = "#18d26b";
        }else{
            document.getElementById('moves_enter').innerHTML = "<?php echo lang('sell_tr'); ?>";
            document.getElementById('su_buy').style.background = "#ffa800";
            document.getElementById('su_buy').style.border = "#ffa800";
        }

        // get the country select element via its known id
        var cSelect = document.getElementById("ama");
        // remove the current options from the country select
        var len=cSelect.options.length;
        while (cSelect.options.length > 0) {
            cSelect.remove(0);
        }
        var newOption;
        // create new options
        for (var i=0; i<cList.length; i++) {
            newOption = document.createElement("option");
            newOption.value = cList[i];  // assumes option string and value are the same
            newOption.text=cList[i];
            // add the new option
            try {
                cSelect.add(newOption);  // this will fail in DOM browsers but is needed for IE
            }
            catch (e) {
                cSelect.appendChild(newOption);
            }
        }
        div_mul();
        conve();
    }
    //]]>
</script>
<script type="text/javascript">
    //<![CDATA[
    // array of possible countries in the same order as they appear in the country selection list
    var countryLists = new Array(4)
    countryLists["USD"] = ["LYD", "USD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    countryLists["EU"] = ["LYD", "USD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    countryLists["DT"] = ["LYD", "USD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    countryLists["TRY"] = ["LYD", "USD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    countryLists["EGP"] = ["LYD", "USD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    countryLists["GBP"] = ["LYD", "USD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    countryLists["SAR"] = ["LYD", "USD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    countryLists["RM"] = ["LYD", "USD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    countryLists["LYD"] = ["USD", "LYD", "EU", "DT", "TRY","EGP","GBP","SAR","RM"];
    /* CountryChange() is called from the onchange event of a select element.
    * param selectObj - the select object which fired the on change event.
    */
    function countryChange(selectObj) {
        // get the index of the selected option
        var idx = selectObj.selectedIndex;
        // get the value of the selected option
        var which = selectObj.options[idx].value;
        var ambb = document.getElementById('amb');
        var ambx = ambb.selectedIndex;
        var ambch = ambb.options[ambx].value;
        // use the selected option value to retrieve the list of items from the countryLists array
        cList = countryLists[which];

        var divit = document.getElementById('divi');
        var multor = document.getElementById('mult');
        var prus = document.getElementById('prus');
        var prusd = prus.value;
        if(which == "LYD" || ambch != "LYD"){
            if(prus > 1){
                multor.checked = true;
            }else{
                divit.checked = true;
            }
        }else if(which != "LYD" || ambch == "LYD"){
            if(prus > 1){
                divit.checked = true;
            }else{
                multor.checked = true;
            }

        }
        if(which == "LYD"){
            document.getElementById('moves_enter').innerHTML = "<?php echo lang('buy_tr'); ?>";
            document.getElementById('su_buy').style.background = "#18d26b";
            document.getElementById('su_buy').style.border = "#18d26b";
        }else{
            document.getElementById('moves_enter').innerHTML = "<?php echo lang('sell_tr'); ?>";
            document.getElementById('su_buy').style.background = "#ffa800";
            document.getElementById('su_buy').style.border = "#ffa800";
        }
        // get the country select element via its known id
        var cSelect = document.getElementById("amb");
        // remove the current options from the country select
        var len=cSelect.options.length;
        while (cSelect.options.length > 0) {
            cSelect.remove(0);
        }
        var newOption;
        // create new options
        for (var i=0; i<cList.length; i++) {
            newOption = document.createElement("option");
            newOption.value = cList[i];  // assumes option string and value are the same
            newOption.text=cList[i];
            // add the new option
            try {
                cSelect.add(newOption);  // this will fail in DOM browsers but is needed for IE
            }
            catch (e) {
                cSelect.appendChild(newOption);
            }
        }
        div_mul();
        conve();
    }
    //]]>
</script>
<script>
    function buyyi(){
        $("#sellbuy").val("<?php echo lang('buy_tr'); ?>");
        $("#postingToDB").submit();
    }
</script>
<script>
    $(document).ready(function(){
        $('.loadingPosting').hide();
        var i = 1;
        $("#postingToDB").on('submit',function(e){
            if ($.trim($('#un').val()) == "") {
                $('#un').css("border-color", "red");
                alert("<?php echo lang('please_fill_required_fields'); ?>");
                return false;
            }else{
                if($.trim($('#pd').val()) == ""){
                    $('#pd').css("border-color", "red");
                    alert("<?php echo lang('please_fill_required_fields'); ?>");
                    return false;
                }else{
                    if($.trim($('#prus').val()) == ""){
                        $('#prus').css("border-color", "red");
                        alert("<?php echo lang('please_fill_required_fields'); ?>");
                        return false;
                    }else{
                        if($.trim($('#ama').val()) == $.trim($('#amb').val())){
                            $('#ama').css("border-color", "red");
                            $('#amb').css("border-color", "red");
                            return false;
                        }else{
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
                                    $('#un').val('');
                                    $('#pd').val('');
                                    $('#w_title').hide();
                                    $('#p_privacy').val("<?php echo lang('wpr_public'); ?>");
                                    $('#photo_preview').hide();
                                    $('#cancel_photo_preview').hide();
                                    $('#photo_preview_box').show();
                                    $("#refldiv").load(location.href + " #refldiv");
                                    $("#mod").load(location.href + " #mod");
                                    $("#mcod").load(location.href + " #mcod");
                                    $("#print").load(location.href + " #print");
                                    $(".success_msg").fadeOut(800);
                                }
                            });
                        }}}}
        });
    });
</script>
<script>
    var validNumber = new RegExp(/^\d*\.?\d*$/);
    var lastValid = document.getElementById("prus").value;
    function validateNumbera(elem) {
        if (validNumber.test(elem.value)) {
            lastValid = elem.value;
        } else {
            elem.value = lastValid;
        }
    }
</script>
<script>
    var validNumber = new RegExp(/^\d*\.?\d*$/);
    var lastValid = document.getElementById("un").value;
    function validateNumberb(elem) {
        if (validNumber.test(elem.value)) {
            lastValid = elem.value;
        } else {
            elem.value = lastValid;
        }
    }
</script>
<script>
    var validNumber = new RegExp(/^\d*\.?\d*$/);
    var lastValid = document.getElementById("pd").value;
    function validateNumberc(elem) {
        if (validNumber.test(elem.value)) {
            lastValid = elem.value;
        } else {
            elem.value = lastValid;
        }
    }
</script>
<script>
    $('#swap').click(function () {
        var v1 = $('#ama').val(),
            v2 = $('#amb').val();
        $('#ama').val(v2);
        $('#amb').val(v1);
        var v3 = $('#un').val(),
            v4 = $('#pd').val();
        $('#ama').val(v2);
        $('#amb').val(v1);
        $('#un').val(v4);
        $('#pd').val(v3);
        if($('#ama').val() == "LYD"){
            document.getElementById('moves_enter').innerHTML = "<?php echo lang('buy'); ?>";
            document.getElementById('su_buy').style.background = "#18d26b";
            document.getElementById('su_buy').style.border = "#18d26b";
        }else{
            document.getElementById('moves_enter').innerHTML = "<?php echo lang('sell_tr'); ?>";
            document.getElementById('su_buy').style.background = "#ffa800";
            document.getElementById('su_buy').style.border = "#ffa800";
        }
        div_mul();
        conve();
    });
</script>
<script>
    $(document).ready(function() {
        var eq = "";
        var curNumber="";
        var result = "";
        var entry = "";
        var reset = false;

        $("button").click(function() {
            entry = $(this).attr("value");

            if (entry === "ac") {
                entry=0;
                eq=0;
                result=0;
                curNumber=0;
                $('#result p').html(entry);
                $('#previous p').html(eq);
            }

            else if (entry === "ce") {
                if (eq.length > 1) {
                    eq = eq.slice(0, -1);
                    $('#previous p').html(eq);
                }
                else {
                    eq = 0;
                    $('#result p').html(0);
                }

                $('#previous p').html(eq);

                if (curNumber.length > 1) {
                    curNumber = curNumber.slice(0, -1);
                    $('#result p').html(curNumber);
                }
                else {
                    curNumber = 0;
                    $('#result p').html(0);
                }

            }

            else if (entry === "=") {
                result = eval(eq);
                $('#result p').html(result);
                eq += "="+result;
                $('#previous p').html(eq);
                eq = result;
                entry = result;
                curNumber = result;
                reset = true;
            }

            else if (isNaN(entry)) {   //check if is not a number, and after that, prevents for multiple "." to enter the same number
                if (entry !== ".") {
                    reset = false;
                    if (curNumber === 0 || eq === 0) {
                        curNumber = 0;
                        eq = entry;
                    }
                    else {
                        curNumber = "";
                        eq += entry;
                    }
                    $('#previous p').html(eq);
                }
                else if (curNumber.indexOf(".") === -1) {
                    reset = false;
                    if (curNumber === 0 || eq === 0) {
                        curNumber = 0.;
                        eq = 0.;
                    }
                    else {
                        curNumber += entry;
                        eq += entry;
                    }
                    $('#result p').html(curNumber);
                    $('#previous p').html(eq);
                }
            }

            else {
                if (reset) {
                    eq = entry;
                    curNumber = entry;
                    reset = false;
                }
                else {
                    eq += entry;
                    curNumber += entry;
                }
                $('#previous p').html(eq);
                $('#result p').html(curNumber);
            }


            if (curNumber.length > 10 || eq.length > 26) {
                $("#result p").html("0");
                $("#previous p").html("Too many digits");
                curNumber ="";
                eq="";
                result ="";
                reset=true;
            }

            if (result.indexOf(".") !== -1) {
                result = result.truncate()
            }

        });


    });
</script>
<?php
///include("includes/endJScodes.php");
$this->load->view('includes/endJScodes');
?>
<script src="<?php echo base_url(); ?>Asset/js/jquery.form.js"></script>

</body>
</html>
