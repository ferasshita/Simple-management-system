<?php
// error_reporting(E_ALL ^ E_NOTICE);
// session_start();
// include("../config/connect.php");
// include ("../includes/num_k_m_count.php");
// include ("../includes/time_function.php");
$t=time();
time_ago($t);
// // if user not logged in go index page or login
// if(!isset($_SESSION['Username'])){
//     header("location: ../index");
// }
// if($_SESSION['user_email_status'] == "not verified"){
// header("location:../email_verification");
// }
// if($_SESSION['package'] == "2" || $_SESSION['package'] == "3" || $_SESSION['package'] == "4" || $_SESSION['admin'] == "1"){
// }else{
// header("location: ../home");
// }
// // check if user is an admin or not to access dashboard
// if ($_SESSION['type'] == 'boss' || $_SESSION['type'] == 'admin') {
// }else{
// header("location: ../index");
// }
// set check path var
// if (is_dir("imgs/")) {
//     $dircheckPath = "";
// }elseif (is_dir("../imgs/")) {
//     $dircheckPath = "../";
// }elseif (is_dir("../../imgs/")) {
//     $dircheckPath = "../../";
// }
//$dir="../";
// $ido = $_SESSION['id'];
// $shop_id = $_SESSION['shop_id'];
// //get request 'urlP'
// $urlP = filter_var(htmlspecialchars($_GET['adb']),FILTER_SANITIZE_STRING);
// // ============= [ General Data ] ==============
// if($_SESSION['type'] == "admin"){
//     $cusers_q_sql = "SELECT id FROM signup WHERE shop_id='$shop_id'";
//     $cusers_q = $conn->prepare($cusers_q_sql);
//     $cusers_q->execute();
//     $cusers_q_num_rows = $cusers_q->rowCount();
// }else{
//     $cusers_q_sql = "SELECT id FROM signup WHERE boss_id='$ido'";
//     $cusers_q = $conn->prepare($cusers_q_sql);
//     $cusers_q->execute();
//     $cusers_q_num_rows = $cusers_q->rowCount();
// }
// if($_SESSION['type'] == "admin"){
//     $admins_q_sql = "SELECT admin FROM signup WHERE shop_id='$shop_id' AND type='admin'";
//     $admins_q = $conn->prepare($admins_q_sql);
//     $admins_q->execute();
//     $admins_q_num_rows = $admins_q->rowCount();
// }else{
//     $admins_q_sql = "SELECT admin FROM signup WHERE boss_id='$ido' AND type='admin'";
//     $admins_q = $conn->prepare($admins_q_sql);
//     $admins_q->execute();
//     $admins_q_num_rows = $admins_q->rowCount();
// }
// if($_SESSION['type'] == "admin"){
//     $admins_q_sql = "SELECT language FROM signup WHERE shop_id='$shop_id' AND  language='english'";
//     $language = $conn->prepare($admins_q_sql);
//     $language->execute();
//     $language_q_num_rows = $language->rowCount();
// }else{
//     $admins_q_sql = "SELECT language FROM signup WHERE boss_id='$ido' AND  language='english'";
//     $language = $conn->prepare($admins_q_sql);
//     $language->execute();
//     $language_q_num_rows = $language->rowCount();
// }
// if($_SESSION['type'] == "admin"){
//     $admins_q_sql = "SELECT language FROM signup WHERE shop_id='$shop_id' AND  language='العربية'";
//     $languagear = $conn->prepare($admins_q_sql);
//     $languagear->execute();
//     $languagear_q_num_rows = $languagear->rowCount();
// }else{
//     $admins_q_sql = "SELECT language FROM signup WHERE boss_id='$ido' AND  language='العربية'";
//     $languagear = $conn->prepare($admins_q_sql);
//     $languagear->execute();
//     $languagear_q_num_rows = $languagear->rowCount();
// }if($_SESSION['type'] == "admin"){
//     $admins_q_sql = "SELECT sus FROM signup WHERE shop_id='$shop_id' AND sus='1'";
//     $sus_q = $conn->prepare($admins_q_sql);
//     $sus_q->execute();
//     $sus_q_num_rows = $sus_q->rowCount();
// }else{
//     $admins_q_sql = "SELECT sus FROM signup WHERE boss_id='$ido' AND sus='1'";
//     $sus_q = $conn->prepare($admins_q_sql);
//     $sus_q->execute();
//     $sus_q_num_rows = $sus_q->rowCount();
// }
$users = thousandsCurrencyFormat($cusers_q_num_rows);
$posts = thousandsCurrencyFormat($cposts_q_num_rows);
$admins = thousandsCurrencyFormat($admins_q_num_rows);

$eng = thousandsCurrencyFormat($language_q_num_rows);
$arb = thousandsCurrencyFormat($languagear_q_num_rows);
$susb = thousandsCurrencyFormat($sus_q_num_rows);
// ============= [ Verify badge ] ==============
$dir = "../";

?>
<!DOCTYPE html>
<html translate="no" lang="<?php echo lang('html_lang'); ?>" dir="">
<head>
<title>Account settings | almaqar</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<?php
$this->load->view('includes/head_imports_main');
//include "../includes/head_imports_main.php";?>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="<?php echo base_url();?>Asset/css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="<?php echo base_url();?>Asset/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url();?>Asset/css/skin_color.css">

    <style>
        .exchange-calculator  .select2-container {
            margin-top: 0px;
        }
        .input-group {

            width: 50%;
        }
        @media (min-width: 768px){
            .fixed-flex-report {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }@media (min-width: 992px){
            .fixed-width-form {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
        .strength{
          height:0px;
          width:100%;
          background:#ccc;
          margin-top: -7px;
          border-bottom-left-radius: 4px;
          border-bottom-right-radius: 4px;
          overflow: hidden;
          transition: height 0.3s;
        }
          .pst{
            width:0px;
            height: 7px;
            display: block;
            transition: width 0.3s;
            }

    </style>

</head>
<body class="hold-transition <?php echo lang('html_dir'); ?> <?php echo $layoutmode; ?> sidebar-mini theme-primary">
<!-- Site wrapper -->
<div class="wrapper">

    <!-- Left side column. contains the logo and sidebar -->
  <?php
  $this->load->view('includes/navbar_main');

  //include "../includes/navbar_main.php"; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="mr-auto">
                        <h3 class="page-title"><strong><?php echo lang('control_panel'); ?></strong></h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard"><i class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo lang('control_panel'); ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
<?php
$this->load->view('includes/treasury_header');
  //include "../includes/treasury_header.php"; ?>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">


                <!-- button of reports -->
                <div class="row">
                  <div class="col-xl-3 col-md-6 col-12 ">
                      <div class="box box-inverse box-success">
                        <div class="box-body">
                          <div class="flexbox">
                            <h5><?php echo  lang('users'); ?></h5>
                            <div class="dropdown">
                              <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                              </div>
                            </div>
                          </div>

                          <div class="text-center my-2">
                            <div class="font-size-60"><?php if($users == "" ){echo "0";}else{echo "$users";} ?></div>
                            <span><?php echo  lang('users'); ?></span>
                          </div>
                        </div>
                      </div>
                  </div>
                  <!-- /.col -->
                  <div class="col-xl-3 col-md-6 col-12 ">
                      <div class="box box-inverse box-primary">
                        <div class="box-body">
                          <div class="flexbox">
                            <h5><?php echo  lang('admins'); ?></h5>
                            <div class="dropdown">
                              <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                              </div>
                            </div>
                          </div>

                          <div class="text-center my-2">
                            <div class="font-size-60"><?php if($admins == "" ){echo "0";}else{echo "$admins";} ?></div>
                            <span><?php echo  lang('admins'); ?> </span>
                          </div>
                        </div>
                      </div>
                  </div>
                  <!-- /.col -->

                  <div class="col-xl-3 col-md-6 col-12">
                      <div class="box box-inverse box-danger">
                        <div class="box-body">
                          <div class="flexbox">
                            <h5>English</h5>
                            <div class="dropdown">
                              <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                              </div>
                            </div>
                          </div>

                          <div class="text-center my-2">
                            <div class="font-size-60"><span><?php if($eng == "" ){echo "0";}else{echo "$eng";} ?></span></div>
                            <span>English</span>
                          </div>
                        </div>

                      </div>
                  </div>
                  <!-- /.col -->
                  <div class="col-xl-3 col-md-6 col-12">
                      <div class="box box-inverse box-warning">
                        <div class="box-body">
                          <div class="flexbox">
                            <h5>العربية </h5>
                            <div class="dropdown">
                              <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                              </div>
                            </div>
                          </div>

                          <div class="text-center my-2">
                            <div class="font-size-60"><?php if($arb == "" ){echo "0";}else{echo "$arb";} ?></div>
                            <span>العربية</span>
                          </div>
                        </div>
                      </div>
                  </div>
                  <!-- /.col -->
              </div>
                <!--./ button of reports -->

                <!--Tabel deital-->
                <div class="row">

                    <div class="col-md-6 col-12 fixed-flex-report">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title"><strong><?php echo lang('users') ?></strong></h4>
                                <ul class="box-controls pull-right">
                                    <li><a class="box-btn-close" href="#"></a></li>
                                    <li><a class="box-btn-slide" href="#"></a></li>
                                    <li><a class="box-btn-fullscreen" href="#"></a></li>
                                </ul>
                            </div>

                            <div class="col-12">
                                <div class="box">
                                    <div class="box-header with-border">
                                        <h4 class="box-title"><?php echo lang('users') ?></h4>
                                    </div>
                                    <div class="box-body">
                                        <div class="table-responsive">

                                            <table id="reports_1" class="table table-lg invoice-archive">
                                                <thead>
                                                <tr>
                                                    <th><?php echo lang('name'); ?></th>
                                                    <th><?php echo lang('phone'); ?></th>
                                                    <th><?php echo lang('email'); ?></th>
                                                    <th><?php echo lang('shop'); ?></th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                  <?php
                                                  error_reporting(0);
                                                  $yy = date('Y');
                                                  $mm = date('m');
                                                  $dd = date('d');
                                                  $nowdate = "$dd-$mm-$yy";
                                                //   if($_SESSION['type'] == "admin"){
                                                //       $fetchUsers_sql = "SELECT Username,phone,name,email,nex_pay,admin,sus,shop_id,type FROM signup WHERE shop_id='$shop_id'";
                                                //   }else{
                                                //       $fetchUsers_sql = "SELECT Username,phone,name,email,nex_pay,admin,sus,shop_id,type FROM signup WHERE boss_id='$ido'";
                                                //   }
                                                //   $fetchUsers = $conn->prepare($fetchUsers_sql);
                                                //   $fetchUsers->execute();
                                                  foreach ($signups["info"] as $rows) {
                                                      $shopid = $rows['shop_id'];
                                                      ?>
                                                      <tr>
                                                          <td>

                                                              <a><p><?php echo $rows['Username']; ?><?php if( $rows['type'] == "admin" or  $rows['admin'] =="2"){echo" <span style='color:blue' class='fa fa-check-circle verifyUser'></span>";}echo"</p></a>";?>
                                                          </td>
                                                          <td>
                                                              <a href="tel:<?php echo $rows['phone']; ?>"><p><?php echo $rows['phone']; ?></p></a>
                                                          </td>
                                                          <td >
                                                              <a href="mailto:<?php echo $rows['email']; ?>"><p><?php echo $rows['email']; ?></p></a>
                                                          </td>
                                                          <td >
                                                              <?php
                                                            //   $fetchUsers_sqli = "SELECT Username FROM signup WHERE id='$shopid'";
                                                            //   $fetchUsersi = $conn->prepare($fetchUsers_sqli);
                                                            //   $fetchUsersi->execute();
                                                              foreach ($signups["usernames"][$shopid] as $rowis)
                                                              {
                                                                  echo"<p>".$rowis['Username']."</p>";
                                                              }
                                                              ?>
                                                          <td><a href="<?php echo base_url();?>Dashboard/user?ed=<?php echo $rows['Username']; ?>" class="btn"><?php echo  lang('edit_delete_dashboard'); ?></a></td>
                                                      </tr>
                                                  <?php } ?>

                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.Tabel deital-->

                <!--Add User-->
                <div class="row">

                    <div class="col-md-6 col-12 fixed-flex-report">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title"> <strong><?php echo  lang('create_account'); ?></strong></h4>
                                <ul class="box-controls pull-right">
                                    <li><a class="box-btn-close" href="#"></a></li>
                                    <li><a class="box-btn-slide" href="#"></a></li>
                                    <li><a class="box-btn-fullscreen" href="#"></a></li>
                                </ul>
                            </div>

                            <div class="col-lg-6 col-12 fixed-width-form">
                                <div class="box">
                                    <!-- /.box-header -->
                                        <div class="box-body">
                                            <h4 class="box-title text-info"><i class="ti-user mr-15"></i> <?php echo lang('create_account') ?></h4>
                                            <hr class="my-15">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo  lang('username'); ?></label>
                                                        <input type="text" class="form-control login_signup_textfield" id="un" name="signup_username"  placeholder="<?php echo  lang('username'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6" id="ph_dev">
                                                    <div class="form-group">
                                                        <label><?php echo  lang('phone'); ?></label>
                                                        <input type="number" class="form-control login_signup_textfield" id="fn" name="signup_fullname"  placeholder="<?php echo  lang('phone'); ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-6" id="em_dev">
                                                    <div class="form-group">
                                                        <label ><?php echo  lang('email'); ?></label>
                                                        <input type="email" class="form-control login_signup_textfield" id="em" name="signup_email"  placeholder="<?php echo  lang('email'); ?>">
                                                    </div>
                                                </div>
                                                  <div class="col-md-6" id="sh_dev">
                                                    <div class="form-group">
                                                        <label ><?php echo  lang('shop'); ?></label>
                                              <select name="shop" id="shopt" class="form-control" style="width: 158px;">
                                                      <?php
                                                    //   $sid = $_SESSION['id'];
                                                    //   $fetchUsers_sql = "SELECT Username,id FROM signup WHERE type='shop' AND boss_id='$sid'";
                                                    //   $fetchUsers = $conn->prepare($fetchUsers_sql);
                                                    //   $fetchUsers->execute();
                                                      foreach ($UData as $rows)
                                                      {
                                                          $id = $rows['id'];
                                                          $username = $rows['Username'];
                                                          echo"<option value='$id'>$username</option>";
                                                      }
                                                      ?>
                                                  </select>
                                                </div>
                                                </div>
                                                <?php if($_SESSION['type'] == "admin"){ ?>
                                                    <input type="hidden" name="typt" value="">
                                                <?php }else{ ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label ><?php echo  lang('account_type'); ?></label>
                                                        <select name="typt" onchange="countryChange(this);" class="form-control" id="typt">
                                                            <option value="user"><?php echo lang('user'); ?></option>
                                                            <option value="admin"><?php echo lang('admin'); ?></option>
                                                            <?php if($_SESSION['type'] == "admin"){ ?>
                                                            <?php }else{ ?>
                                                                <option value="shop"><?php echo lang('shop'); ?></option>
                                                            <?php } ?>
                                                        </select>                                                    </div>
                                                </div>
                                                <?php } ?>
                                                <div class="col-md-6"  id="pw_dev">
                                                    <div class="form-group">
                                                        <label ><?php echo  lang('password'); ?></label>
                                                        <input type="password" data-strength class="form-control login_signup_textfield" name="signup_cpassword" id="pd"  placeholder="<?php echo  lang('password'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6"  id="cpw_dev">
                                                    <div class="form-group">
                                                        <label ><?php echo  lang('confirm_password'); ?></label>
                                                        <input type="password" class="form-control login_signup_textfield"name="signup_username"  id="cpd"  placeholder="<?php echo  lang('confirm_password'); ?>">
                                                    </div>
                                                </div>
                                            </div>
<input type="hidden" name="gender" value="user" id="gr">
                                            <!-- /.box-body -->
                                            <div class="box-footer">
                                                <button id="signupFunCode" type="submit" class="login_signup_btn2 btn btn-rounded btn-primary btn-outline" >
                                                    <i class="ti-save-alt"></i> Save
                                                </button>
                                            </div>
                                        <p id="login_wait" style="margin: 0px;"></p>
                                        </div>
                                    <script type="text/javascript">
                                        function signupUser(){
                                            var fullname = document.getElementById("fn").value;
                                            var username = document.getElementById("un").value;
                                            var emailAdd = document.getElementById("em").value;
                                            var password = document.getElementById("pd").value;
                                            var cpassword = document.getElementById("cpd").value;
                                            var gender = document.getElementById("gr").value;
                                            var shopt = document.getElementById("shopt").value;
                                            var typt = document.getElementById("typt").value;
                                            $.ajax({
                                                type:'POST',
                                                url:'<?php echo base_url()."account/doregister";?>',
                                                data:{'req':'signup_code','fn':fullname,'un':username,'em':emailAdd,'pd':password,'cpd':cpassword,'gr':gender,'shop':shopt,'typ':typt},
                                                beforeSend:function(){
                                                    $('.login_signup_btn2').hide();
                                                    $('#login_wait').html("<b><?php echo  lang('creating_your_account'); ?></b>");
                                                },
                                                success:function(data){
                                                    $('#login_wait').html(data);
                                                    if (data == "Done..") {
                                                        $('#login_wait').html("<p class='alertGreen'><?php echo  lang('done'); ?>..</p>");
                                                    }else{
                                                        $('.login_signup_btn2').show();
                                                    }
                                                },
                                                error:function(err){
                                                    alert(err);
                                                }
                                            });
                                        }
                                        $('#signupFunCode').click(function(){
                                            signupUser();
                                        });

                                        $(".login_signup_textfield").keypress( function (e) {
                                            if (e.keyCode == 13) {
                                                signupUser();
                                            }
                                        });
                                    </script>
                                </div>
                                <!-- /.box -->
                            </div>

                        </div>
                    </div>

                </div>
                <!-- /.Add User-->

            </section>
            <!-- /.content -->

        </div>
    </div>
    <!-- /.content-wrapper -->

    <?php //include "../includes/footer.php";
    $this->load->view('includes/footer');
    ?>
    <!-- Control Sidebar -->

</div>
<!-- ./wrapper -->


<!-- Vendor JS -->
<script src="<?php echo base_url();?>Asset/js/vendors.min.js"></script>

<!-- Vendor Plugnin -->
<script src="<?php echo base_url();?>Asset/assets/vendor_components/chart.js-master/Chart.min.js"></script>
<script src="<?php echo base_url();?>Asset/js/pages/chartjs-int.js"></script>
<script src="<?php echo base_url();?>Asset/assets/vendor_components/select2/dist/js/select2.full.js"></script>
<script src="<?php echo base_url();?>Asset/assets/vendor_components/datatable/datatables.min.js"></script>
<script src="<?php echo base_url();?>Asset/assets/vendor_plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?php echo base_url();?>Asset/assets/vendor_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url();?>Asset/assets/vendor_components/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- Crypto Tokenizer Admin App -->
<script src="<?php echo base_url();?>Asset/js/template.js"></script>
<script src="<?php echo base_url();?>Asset/js/demo.js"></script>
<script src="<?php echo base_url();?>Asset/js/pages/data-table.js"></script>
<script src="<?php echo base_url();?>Asset/js/pages/advanced-form-element.js"></script>

<script src="<?php echo base_url(); ?>Asset/js/jquery.form.js"></script>

<script type="text/javascript">
    function countryChange(selectObj) {
        // get the index of the selected option
        var idx = selectObj.selectedIndex;
        // get the value of the selected option
        var which = selectObj.options[idx].value;
        // use the selected option value to retrieve the list of items from the countryLists array

if(which == "shop"){
  document.getElementById('ph_dev').style.display = "none";
  document.getElementById('em_dev').style.display = "none";
  document.getElementById('sh_dev').style.display = "none";
  document.getElementById('pw_dev').style.display = "none";
  document.getElementById('cpw_dev').style.display = "none";
  }else{
    document.getElementById('ph_dev').style.display = "block";
    document.getElementById('em_dev').style.display = "block";
    document.getElementById('sh_dev').style.display = "block";
    document.getElementById('pw_dev').style.display = "block";
    document.getElementById('cpw_dev').style.display = "block";  }
        // get the country select element via its known id
    }
    //]]>
</script>
<script>
$(function() {

  function passwordCheck(password) {
    if (password.length >= 8)
      strength += 1;

    if (password.match(/(?=.*[0-9])/))
      strength += 1;

    if (password.match(/(?=.*[!,%,&,@,#,$,^,*,?,_,~,<,>,])/))
      strength += 1;

    if (password.match(/(?=.*[a-z])/))
      strength += 1;

    if (password.match(/(?=.*[A-Z])/))
      strength += 1;

    displayBar(strength);
  }

  function displayBar(strength) {
    switch (strength) {
      case 1:
        $("#password-strength span").css({
          "width": "20%",
          "background": "#de1616"
        });
        break;

      case 2:
        $("#password-strength span").css({
          "width": "40%",
          "background": "#de1616"
        });
        break;

      case 3:
        $("#password-strength span").css({
          "width": "60%",
          "background": "#de1616"
        });
        break;

      case 4:
        $("#password-strength span").css({
          "width": "80%",
          "background": "#FFA200"
        });
        break;

      case 5:
        $("#password-strength span").css({
          "width": "100%",
          "background": "#06bf06"
        });
        break;

      default:
        $("#password-strength span").css({
          "width": "0",
          "background": "#de1616"
        });
    }
  }

  $("[data-strength]").after("<div id=\"password-strength\" class=\"strength\"><span class=\"pst\"></span></div>")

  $("[data-strength]").focus(function() {
    $("#password-strength").css({
      "height": "7px"
    });
  }).blur(function() {
    $("#password-strength").css({
      "height": "0px"
    });
  });

  $("[data-strength]").keyup(function() {
    strength = 0;
    var password = $(this).val();
    passwordCheck(password);
  });

});
</script>
</body>
</html>
