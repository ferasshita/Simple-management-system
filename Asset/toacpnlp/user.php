<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
include("../config/connect.php");
include ("../includes/num_k_m_count.php");
include ("../includes/time_function.php");

// if user not logged in go index page or login
if(!isset($_SESSION['Username'])){
    header("location: ../index");
}
if($_SESSION['user_email_status'] == "not verified"){
header("location:../email_verification");}
// check if user is an admin or naot to access dashboard
if ($_SESSION['admin'] != '1') {
    if ($_SESSION['admin'] != '2') {
        header("location: ../index");
    }
}

// this to make dashboard 'Users' link active
$urlP='Users';

// set check path var
if (is_dir("imgs/")) {
    $dircheckPath = "";
}elseif (is_dir("../imgs/")) {
    $dircheckPath = "../";
}elseif (is_dir("../../imgs/")) {
    $dircheckPath = "../../";
}
$dir="../";
// get var's
$nex_payi = trim(filter_var(htmlspecialchars($_POST['nex_pay']),FILTER_SANITIZE_STRING));
$package = trim(filter_var(htmlspecialchars($_POST['package']),FILTER_SANITIZE_STRING));
$ed = trim(filter_var(htmlspecialchars($_GET['ed']),FILTER_SANITIZE_STRING));
$db_username = trim(filter_var(htmlentities($_POST['username']),FILTER_SANITIZE_STRING));
$db_email = trim(filter_var(htmlentities($_POST['email']),FILTER_SANITIZE_STRING));
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
// get information of username and put it into fields as default
$uInfo = $conn->prepare("SELECT id,Username,Email,phone,Password,admin,nex_pay,sus,accou_stu,package,package_chose FROM signup WHERE Username = :ed");
$uInfo->bindParam(':ed',$ed,PDO::PARAM_STR);
$uInfo->execute();
$uInfo_count = $uInfo->rowCount();
if ($uInfo_count > 0) {
while ($uInfoRow = $uInfo->fetch(PDO::FETCH_ASSOC)) {
    $uInfo_id = $uInfoRow['id'];
    $uInfo_un = $uInfoRow['Username'];
    $pack_ad = $uInfoRow['package'];
    $uInfo_em = $uInfoRow['Email'];
    $uInfo_ph = $uInfoRow['phone'];
    $uInfo_pd = $uInfoRow['Password'];
    $uInfo_ad = $uInfoRow['admin'];
    $nex_payf = $uInfoRow['nex_pay'];
    $package_chose = $uInfoRow['package_chose'];
    $status = $uInfoRow['sus'];
    $accou_stu = $uInfoRow['accou_stu'];
}
}else{
    $un_not_found = "user not found";
}
// update user info
if (isset($_POST['submit_uInfo'])) {
    if (empty($db_username) or empty($db_email)) {
        $update_result = "<p class='alertRed'>".lang('please_fill_required_fields')."</p>";
        $stop = "1";
    }
    if(strpos($db_username, ' ') !== false || preg_match('/[\'^£$%&*()}{@#~?><>,.|=+¬-]/', $db_username) || !preg_match('/[A-Za-z0-9]+/', $db_username)) {
        $update_result = "
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
    // check if username exist
    $unExist = $conn->prepare("SELECT Username FROM signup WHERE Username =:db_username");
    $unExist->bindParam(':db_username',$db_username,PDO::PARAM_STR);
    $unExist->execute();
    $unExistCount = $unExist->rowCount();
    if($unExistCount > 0){
        if ($ed != $db_username) {
        $update_result = "<p class='alertRed'>".lang('user_already_exist')."</p>";
        $stop = "1";
        }
    }
    // check if email exist
    $emExist = $conn->prepare("SELECT Email FROM signup WHERE Email =:db_email");
    $emExist->bindParam(':db_email',$db_email,PDO::PARAM_STR);
    $emExist->execute();
    $emExistCount = $emExist->rowCount();
    if($emExistCount > 0){
        if ($uInfo_em != $db_email) {
        $update_result = "<p class='alertRed'>".lang('email_already_exist')."</p>";
        $stop = "1";
        }
    }
    if (!filter_var($db_email, FILTER_VALIDATE_EMAIL)) {
        $update_result = "<p class='alertRed'>".lang('invalid_email_address')."</p>";
        $stop = "1";
    }
    if ($stop != "1") {
        if (empty($db_password_var)) {
        $update = $conn->prepare("UPDATE signup SET Username = :db_username,Email = :db_email,package = :package,admin = :db_admin WHERE Username = :ed");
        }else{
        $update = $conn->prepare("UPDATE signup SET Username = :db_username,Email = :db_email,package = :package,Password = :db_password,admin = :db_admin WHERE Username = :ed");
        }
        $update->bindParam(':db_username',$db_username,PDO::PARAM_STR);
        $update->bindParam(':db_email',$db_email,PDO::PARAM_STR);
        $update->bindParam(':package',$package,PDO::PARAM_INT);
        if (!empty($db_password_var)) {
        $update->bindParam(':db_password',$db_password,PDO::PARAM_STR);
        }
        $update->bindParam(':db_admin',$db_admin,PDO::PARAM_INT);
        $update->bindParam(':ed',$ed,PDO::PARAM_STR);
        $update->execute();
        if ($update) {
            $update_result = "<p class='alertGreen'>".lang('changes_saved_seccessfully')."</p>";
        }else{
            $update_result = "<p class='alertRed'>".lang('errorSomthingWrong')."</p>";
        }
    }
}


// remove a user from all tables (forever from database)
if (isset($_POST['rAccBtn'])) {
        $remeveAccount_sql = "DELETE FROM signup WHERE id= :uInfo_id";
        $remeveAccount = $conn->prepare($remeveAccount_sql);
        $remeveAccount->bindParam(':uInfo_id',$uInfo_id,PDO::PARAM_STR);
        $remeveAccount->execute();
        if ($remeveAccount) {
            header("location: index?adb=Users");
        }else{
            $update_result = "<p class='alertRed'>".lang('errorSomthingWrong')."</p>";
        }
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
      $update = $conn->prepare("UPDATE signup SET nex_pay = :nex_pay, accou_stu = :ghys,expir = :fbm  WHERE Username = :ed");
      $update->bindParam(':fbm',$fbm,PDO::PARAM_STR);
      $update->bindParam(':nex_pay',$ergh,PDO::PARAM_STR);
      $update->bindParam(':ghys',$ghys,PDO::PARAM_STR);
      $update->bindParam(':ed',$ed,PDO::PARAM_STR);
      $update->execute();
            if ($update) {
            }else{
                $update_result = "<p class='alertRed'>".lang('errorSomthingWrong')."</p>";
            }
        }
// remove a user from all tables (forever from database)
if (isset($_POST['zAccBtn'])) {
  $remeveAccount_sql = "DELETE FROM transactions WHERE author_id= :uInfo_id";
  $remeveAccount = $conn->prepare($remeveAccount_sql);
  $remeveAccount->bindParam(':uInfo_id',$uInfo_id,PDO::PARAM_STR);
  $remeveAccount->execute();
  $remeveAccount_sql = "DELETE FROM shop WHERE author_id= :uInfo_id";
  $remeveAccount = $conn->prepare($remeveAccount_sql);
  $remeveAccount->bindParam(':uInfo_id',$uInfo_id,PDO::PARAM_STR);
  $remeveAccount->execute();
  $remeveAccount_sql = "DELETE FROM capital WHERE author_id= :uInfo_id";
  $remeveAccount = $conn->prepare($remeveAccount_sql);
  $remeveAccount->bindParam(':uInfo_id',$uInfo_id,PDO::PARAM_STR);
  $remeveAccount->execute();
  $remeveAccount_sql = "DELETE FROM cos_transactions WHERE author_id= :uInfo_id";
  $remeveAccount = $conn->prepare($remeveAccount_sql);
  $remeveAccount->bindParam(':uInfo_id',$uInfo_id,PDO::PARAM_STR);
  $remeveAccount->execute();
  $remeveAccount_sql = "DELETE FROM bank WHERE author_id= :uInfo_id";
  $remeveAccount = $conn->prepare($remeveAccount_sql);
  $remeveAccount->bindParam(':uInfo_id',$uInfo_id,PDO::PARAM_STR);
  $remeveAccount->execute();
      if ($remeveAccount) {
          header("location: index?adb=Users");
      }else{
          $update_result = "<p class='alertRed'>".lang('errorSomthingWrong')."</p>";
      }
  }
  if (isset($_POST['susbend'])) {
    $uInfo = $conn->prepare("SELECT sus FROM signup WHERE Username = :ed");
    $uInfo->bindParam(':ed',$ed,PDO::PARAM_STR);
    $uInfo->execute();
    $uInfo_count = $uInfo->rowCount();
    if ($uInfo_count > 0) {
    while ($uInfoRow = $uInfo->fetch(PDO::FETCH_ASSOC)) {
        $uInfo_sus= $uInfoRow['sus'];
    }
    if($uInfo_sus == "1"){
      $sus = "0";
    }else{
      $sus = "1";
    }
    $update = $conn->prepare("UPDATE signup SET sus = :db_username WHERE Username = :ed");
    $update->bindParam(':db_username',$sus,PDO::PARAM_INT);
    $update->bindParam(':ed',$ed,PDO::PARAM_STR);
    $update->execute();
        if ($update) {
        }else{
            $update_result = "<p class='alertRed'>".lang('errorSomthingWrong')."</p>";
        }
    }}
?>
<!DOCTYPE html>
<html translate="no" lang="<?php echo lang('html_lang'); ?>">
<head>
<title>Account settings | almaqar</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<?php include "../includes/head_imports_main.php";?>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="../css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/skin_color.css">

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
<body class="hold-transition <?php echo lang('html_dir'); ?> <?php include "../includes/mode.php"; ?> sidebar-mini theme-primary">
<!-- Site wrapper -->
<div class="wrapper">

    <!-- Left side column. contains the logo and sidebar -->
  <?php include "../includes/navbar_main.php"; ?>

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
                                    <li class="breadcrumb-item"><a href="../home"><i class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo lang('control_panel'); ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
<?php include "../includes/treasury_header.php"; ?>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">

                <!--Add User-->
                <div class="row">

                    <div class="col-md-6 col-12 fixed-flex-report">
                      <? if ($un_not_found != "user not found") {
                      ?>
                      <?php
                      $admin_int = "1";
                      $chAdmin = $conn->prepare("SELECT Username FROM signup WHERE Username =:ed AND admin =:admin_int");
                      $chAdmin->bindParam(':ed',$ed,PDO::PARAM_STR);
                      $chAdmin->bindParam(':admin_int',$admin_int,PDO::PARAM_INT);
                      $chAdmin->execute();
                      $chAdminCount = $chAdmin->rowCount();
                      if ($chAdminCount < 1) {
                          ?>
                          <? echo $update_result; ?>
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title"> <strong><? echo $uInfo_un ?></strong></h4>
                                <ul class="box-controls pull-right">
                                    <li><a class="box-btn-close" href="#"></a></li>
                                    <li><a class="box-btn-slide" href="#"></a></li>
                                    <li><a class="box-btn-fullscreen" href="#"></a></li>
                                </ul>
                            </div>

                            <div class="col-lg-6 col-12 fixed-width-form">
                                <div class="box">
                              <? echo $update_result; ?>
                                    <!-- /.box-header -->
                                    <form class="form" action="" method="post">
                                        <div class="box-body">
                                            <h4 class="box-title text-info"><i class="ti-user mr-15"></i> User Personal Info</h4>
                                            <hr class="my-15">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><? echo lang('username'); ?></label>
                                                        <input type="text" class="form-control" dir="auto" name="username" value="<? echo $uInfo_un ?>"   placeholder="<?php echo lang('username'); ?>" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><? echo lang('phone'); ?></label>
                                                        <input type="text" dir="auto" class="form-control" name="email" value="<? echo $uInfo_em ?>"   placeholder="<?php echo lang('email'); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label ><? echo lang('email'); ?></label>
                                                        <input type="text" dir="auto" class="form-control" name="phone" value="<? echo $uInfo_ph ?>"   placeholder="<?php echo lang('phone'); ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label ><? echo lang('password'); ?></label>
                                                        <input type="password" dir="auto" class="form-control" data-strength name="password" placeholder="<?php echo lang('password'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                  <div class="form-group">
                                                      <label ><? echo lang('upgradeToAdmin'); ?></label>
                                                <select name="admin" class="form-control" >
                                                  <option <? if($uInfo_ad == 1){echo "selected";} ?>><? echo lang('yes'); ?></option>
                                                  <option <? if($uInfo_ad == 0){echo "selected";} ?>><? echo lang('no'); ?></option>
                                                </select>
                                                </div>
                                                </div>
                                                <div class="col-md-6">
                                                  <div class="form-group">
                                                      <label ><? echo lang('upgradeToAdmin'); ?></label>
                                                <select name="package" class="form-control" >
                                                  <option value="0" <? if($pack_ad == 0){echo "selected";} ?>>trail</option>
                                                    <option value="1" <? if($pack_ad == 1){echo "selected";} ?>>Basic</option>
                                                    <option value="2" <? if($pack_ad == 2){echo "selected";} ?>>Medium</option>
                                                    <option value="3" <? if($pack_ad == 3){echo "selected";} ?>>Pro</option>
                                                </select>
                                                </div>
                                                </div>
                                            </div>
                                            <!-- /.box-body -->
                                            <div class="box-footer">
                                                <button type="submit" value="<? echo lang('save_changes'); ?>" name="submit_uInfo" class="btn btn-rounded btn-primary btn-outline" >
                                                    <i class="ti-save-alt"></i> Save
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.box -->
                            </div>

                            <div class="col-lg-6 col-12 fixed-width-form">
                                <div class="box">
<div class="box-body">
                                    <!-- /.box-header -->
                                    <form class="form" action="" method="post" id="postingToDBpayne">
                                            <h4 style="color: #ee4f51;">Pay for the user</h4>
                                              <input type='text' id="datepicker" class='dateFilter form-control' autocomplete="off"  placeholder="<?php echo lang('date_to'); ?>" name='nex_pay' value='<?php echo"$nex_payf"; ?>'>

                                            <p style="margin: 8px 0px;"><button class="btn btn-rounded btn-primary btn-outline" name="pay" type="submit">Pay</button></p>
                                        </form>
                                        <div style="font-size:25px">
                                          <p style="color:green">Status: <?php if($status == "0"){echo"active";}else{echo"not active";} ?></p><br>
                                        <p>From: <span style="color:green"><?php echo"$accou_stu"; ?></span> to <span style="color:red"><?php echo"$nex_payf"; ?></span></p>
                                      </div>
                                      <div style="font-size:25px">
                                      <p><?php if($package_chose == "1"){echo "basic";}elseif($package_chose == "2"){echo "medium";}elseif($package_chose == "3"){echo "pro";}else{echo "No package had been selected";} ?></p>
                                      </div>
                                    <form class="form" action="" method="post" id="postingToDBsusbj">
                                      <p style="margin: 8px 0px;"><button class="btn btn-rounded btn-primary btn-outline" name="susbend" type="submit"><?php if($status == "0"){echo"stop";}else{echo"start";} ?></button></p>
                                    </form>


                                  </div>
                                </div>
                                <!-- /.box -->
</div>

                            <div class="col-lg-6 col-12 fixed-width-form">
                                <div class="box">
                                    <!-- /.box-header -->
                                    <form class="form" action="" onsubmit="return senft('delacc')" method="post" id="postingToDBdelacc">
                                      <div class="box-body">
                                          <h4 class="box-title text-info"><i class="fa fa-times mr-15"></i> <?php echo lang('remove_account'); ?></h4>
                                          <hr class="my-15">
                                          <p style="background: rgba(247, 81, 81, 0.14); color: #f75151; padding: 15px; border: 1px solid #f75151; border-radius: 3px;"><?php echo lang('remove_account_note'); ?></p>
                                          <p style="margin: 8px 0px;"><input class="btn btn-rounded btn-primary btn-outline" name="rAccBtn" type="submit" value="<?php echo lang('remove_account'); ?>" /></p>

                                      </div>
                                    </form>
                                </div>
                                <!-- /.box -->
                            </div>
                            <div class="col-lg-6 col-12 fixed-width-form">
                                <div class="box">
                                    <!-- /.box-header -->
                                    <form class="form"  action="" method="post" onsubmit="return senft('delac')" id="postingToDBdelac">
                                      <div class="box-body">
                                        <h4 class="box-title text-info"><i class="fa fa-trash mr-15"></i><?php echo lang('delete_database'); ?></h4>
                                        <hr class="my-15">
                                        <p style="margin: 8px 0px;"><input class="btn btn-rounded btn-primary btn-outline" name="zAccBtn" type="submit" value="<?php echo lang('delete_database'); ?>" /></p>


                                      </div>
                                    </form>
                                </div>
                                <!-- /.box -->
                            </div>

                        </div>
<?php }else{
    if ($ed == $_SESSION['Username']) {
        echo "<p class='alertYellow'>".lang('uCan_access_your_data_from_settings')."</p>";
    }else{
        echo "<p class='alertRed'>".lang('uCannot_access_admin_data')."</p>";
    }
}

}else{
    echo "<p class='alertRed'>".lang('username_not_exists')."</p>";
} ?>
                    </div>

                </div>
                <!-- /.Add User-->

            </section>
            <!-- /.content -->

        </div>
    </div>
    <!-- /.content-wrapper -->

    <?php include "../includes/footer.php"; ?>
    <!-- Control Sidebar -->

</div>
<!-- ./wrapper -->


<!-- Vendor JS -->
<script src="../js/vendors.min.js"></script>

<!-- Vendor Plugnin -->
<script src="../assets/vendor_components/chart.js-master/Chart.min.js"></script>
<script src="../js/pages/chartjs-int.js"></script>
<script src="../assets/vendor_components/select2/dist/js/select2.full.js"></script>
<script src="../assets/vendor_components/datatable/datatables.min.js"></script>
<script>
    if ($('.coins-exchange').length) {
        $('.coins-exchange').select2();
    }
    if ($('.money-exchange').length) {
        $('.money-exchange').select2();
    }
</script>
<script src="../assets/vendor_plugins/input-mask/jquery.inputmask.js"></script>
<script src="../assets/vendor_components/moment/min/moment.min.js"></script>
<script src="../assets/vendor_components/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- Crypto Tokenizer Admin App -->
<script src="../js/template.js"></script>
<script src="../js/demo.js"></script>
<script src="../js/pages/data-table.js"></script>
<script src="../js/pages/advanced-form-element.js"></script>

<script src="http://malsup.github.com/jquery.form.js"></script>

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
