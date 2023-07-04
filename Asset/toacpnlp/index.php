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
header("location:../email_verification");
}
// check if user is an admin or naot to access dashboard
if ($_SESSION['admin'] != '1') {
    if ($_SESSION['admin'] != '2') {
        header("location: ../index");
    }
}
// set check path var
if (is_dir("imgs/")) {
    $dircheckPath = "";
}elseif (is_dir("../imgs/")) {
    $dircheckPath = "../";
}elseif (is_dir("../../imgs/")) {
    $dircheckPath = "../../";
}
$dir="../";
//get request 'urlP'
$urlP = filter_var(htmlspecialchars($_GET['adb']),FILTER_SANITIZE_STRING);
// ============= [ General Data ] ==============
$cusers_q_sql = "SELECT id FROM signup";
$cusers_q = $conn->prepare($cusers_q_sql);
$cusers_q->execute();
$cusers_q_num_rows = $cusers_q->rowCount();

$cposts_q_sql = "SELECT post_id FROM transactions";
$cposts_q = $conn->prepare($cposts_q_sql);
$cposts_q->execute();
$cposts_q_num_rows = $cposts_q->rowCount();

$admins_q_sql = "SELECT admin FROM signup WHERE admin='1' OR admin='2'";
$admins_q = $conn->prepare($admins_q_sql);
$admins_q->execute();
$admins_q_num_rows = $admins_q->rowCount();

$admins_q_sql = "SELECT language FROM signup WHERE language='english'";
$language = $conn->prepare($admins_q_sql);
$language->execute();
$language_q_num_rows = $language->rowCount();

$admins_q_sql = "SELECT language FROM signup WHERE language='العربية'";
$languagear = $conn->prepare($admins_q_sql);
$languagear->execute();
$languagear_q_num_rows = $languagear->rowCount();

$admins_q_sql = "SELECT language FROM signup WHERE package='0'";
$languagear = $conn->prepare($admins_q_sql);
$languagear->execute();
$package_free = $languagear->rowCount();

$admins_q_sql = "SELECT language FROM signup WHERE package='1'";
$languagear = $conn->prepare($admins_q_sql);
$languagear->execute();
$package_basic = $languagear->rowCount();

$admins_q_sql = "SELECT language FROM signup WHERE package='2'";
$languagear = $conn->prepare($admins_q_sql);
$languagear->execute();
$package_medium = $languagear->rowCount();

$admins_q_sql = "SELECT language FROM signup WHERE package='3'";
$languagear = $conn->prepare($admins_q_sql);
$languagear->execute();
$package_pro = $languagear->rowCount();

$admins_q_sql = "SELECT sus FROM signup WHERE sus='1'";
$sus_q = $conn->prepare($admins_q_sql);
$sus_q->execute();
$sus_q_num_rows = $sus_q->rowCount();

$admins_q_sql = "SELECT type FROM signup WHERE type='boss'";
$sus_q = $conn->prepare($admins_q_sql);
$sus_q->execute();
$boss = $sus_q->rowCount();

$admins_q_sql = "SELECT type FROM signup WHERE type='admin' OR type='user'";
$sus_q = $conn->prepare($admins_q_sql);
$sus_q->execute();
$sub_user = $sus_q->rowCount();

$admins_q_sql = "SELECT type FROM signup WHERE type='shop'";
$sus_q = $conn->prepare($admins_q_sql);
$sus_q->execute();
$shop = $sus_q->rowCount();

$users = thousandsCurrencyFormat($cusers_q_num_rows);
$posts = thousandsCurrencyFormat($cposts_q_num_rows);
$admins = thousandsCurrencyFormat($admins_q_num_rows);

$eng = thousandsCurrencyFormat($language_q_num_rows);
$arb = thousandsCurrencyFormat($languagear_q_num_rows);
$susb = thousandsCurrencyFormat($sus_q_num_rows);

$free = thousandsCurrencyFormat($package_free);
$medium = thousandsCurrencyFormat($package_medium);
$basic = thousandsCurrencyFormat($package_basic);
$pro = thousandsCurrencyFormat($package_pro);
$boss = thousandsCurrencyFormat($boss);
$sub_user = thousandsCurrencyFormat($sub_user);
$shop = thousandsCurrencyFormat($shop);
// ============= [ Verify badge ] ==============
if (isset($_POST['verifyBadgeBtn'])) {
    $userVerifyBadge = htmlspecialchars(htmlentities($_POST['verifyBadge']));
    $verifyUserE_sql = "SELECT Username FROM signup WHERE Username=:userVerifyBadge";
    $verifyUserE = $conn->prepare($verifyUserE_sql);
    $verifyUserE->bindParam(':userVerifyBadge',$userVerifyBadge,PDO::PARAM_STR);
    $verifyUserE->execute();
    $verifyUserE_count = $verifyUserE->rowCount();
    if ($verifyUserE_count > 0) {
    switch (filter_var(htmlentities($_POST['verifyOptions']),FILTER_SANITIZE_STRING)) {
        case lang('verify_user'):
        $verifyValue = "1";
        $insertVerify_sql = "UPDATE signup SET admin=:verifyValue WHERE Username =:userVerifyBadge";
        $insertVerify = $conn->prepare($insertVerify_sql);
        $insertVerify->bindParam(':verifyValue',$verifyValue,PDO::PARAM_INT);
        $insertVerify->bindParam(':userVerifyBadge',$userVerifyBadge,PDO::PARAM_STR);
        $insertVerify->execute();
        if ($insertVerify) {
            $verifyBadgeResult = "<p style='color:#4CAF50;'><a href='".$dircheckPath."u/".$userVerifyBadge."'> $userVerifyBadge</a> ".lang('verified_successfully')."</p>";
        }else{
            $verifyBadgeResult = "<p style='color:#F44336;'> ".lang('errorSomthingWrong')."</p>";
        }
        break;
        case lang('remove_verifyBadge'):
        $verifyValue = "0";
        $insertVerify_sql = "UPDATE signup SET admin=:verifyValue WHERE Username =:userVerifyBadge";
        $insertVerify = $conn->prepare($insertVerify_sql);
        $insertVerify->bindParam(':verifyValue',$verifyValue,PDO::PARAM_INT);
        $insertVerify->bindParam(':userVerifyBadge',$userVerifyBadge,PDO::PARAM_STR);
        $insertVerify->execute();
        if ($insertVerify) {
            $verifyBadgeResult = "<p style='color:#4CAF50;'>".lang('verify_badge_removed_succ_from')." <a href='".$dircheckPath."u/".$userVerifyBadge."'> $userVerifyBadge</a></p>";
        }else{
            $verifyBadgeResult = "<p style='color:#F44336;'> ".lang('errorSomthingWrong')."</p>";
        }
        break;
    }
    }else{
        $verifyBadgeResult = "<p style='color:#F44336;'>".lang('user_doesnt_exist')."</p>";
    }
}
?>
<!DOCTYPE html>
<html translate="no" lang="<?php echo lang('html_lang'); ?>" dir="<?php echo lang('html_dir'); ?>">
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


                <!-- button of reports -->
                <div class="row">
                  <div class="col-xl-3 col-md-6 col-12 ">
                      <div class="box box-inverse box-success">
                        <div class="box-body">
                          <div class="flexbox">
                            <h5><? echo lang('users'); ?></h5>
                            <div class="dropdown">
                              <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                              </div>
                            </div>
                          </div>

                          <div class="text-center my-2">
                            <div class="font-size-60"><?php if($users == "" ){echo "0";}else{echo "$users";} ?></div>
                            <span><? echo lang('users'); ?></span>
                          </div>
                        </div>
                      </div>
                  </div>
                  <!-- /.col -->
                  <div class="col-xl-3 col-md-6 col-12 ">
                      <div class="box box-inverse box-primary">
                        <div class="box-body">
                          <div class="flexbox">
                            <h5><? echo lang('admins'); ?></h5>
                            <div class="dropdown">
                              <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                              </div>
                            </div>
                          </div>

                          <div class="text-center my-2">
                            <div class="font-size-60"><?php if($admins == "" ){echo "0";}else{echo "$admins";} ?></div>
                            <span><? echo lang('admins'); ?> </span>
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
                  <div class="col-xl-3 col-md-6 col-12">
                      <div class="box box-inverse box-primary">
                        <div class="box-body">
                          <div class="flexbox">
                            <h5>not active </h5>
                            <div class="dropdown">
                              <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                              </div>
                            </div>
                          </div>

                          <div class="text-center my-2">
                            <div class="font-size-60"><?php if($susb == "" ){echo "0";}else{echo "$susb";} ?></div>
                            <span>not active</span>
                          </div>
                        </div>
                      </div>
                  </div>

                  <div class="col-xl-3 col-md-6 col-12">
                      <div class="box box-inverse box-danger">
                        <div class="box-body">
                          <div class="flexbox">
                            <h5>Trail </h5>
                            <div class="dropdown">
                              <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                              </div>
                            </div>
                          </div>

                          <div class="text-center my-2">
                            <div class="font-size-60"><?php if($free == "" ){echo "0";}else{echo "$free";} ?></div>
                            <span>Trail</span>
                          </div>
                        </div>
                      </div>
                  </div>

                  <div class="col-xl-3 col-md-6 col-12">
                      <div class="box box-inverse box-warning">
                        <div class="box-body">
                          <div class="flexbox">
                            <h5>basic </h5>
                            <div class="dropdown">
                              <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                              </div>
                            </div>
                          </div>

                          <div class="text-center my-2">
                            <div class="font-size-60"><?php if($basic == "" ){echo "0";}else{echo "$basic";} ?></div>
                            <span>basic</span>
                          </div>
                        </div>
                      </div>
                  </div>

                  <div class="col-xl-3 col-md-6 col-12">
                      <div class="box box-inverse box-success">
                        <div class="box-body">
                          <div class="flexbox">
                            <h5>medium </h5>
                            <div class="dropdown">
                              <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                              </div>
                            </div>
                          </div>

                          <div class="text-center my-2">
                            <div class="font-size-60"><?php if($medium == "" ){echo "0";}else{echo "$medium";} ?></div>
                            <span>medium</span>
                          </div>
                        </div>
                      </div>
                  </div>

                  <div class="col-xl-3 col-md-6 col-12">
                      <div class="box box-inverse box-warning">
                        <div class="box-body">
                          <div class="flexbox">
                            <h5>pro </h5>
                            <div class="dropdown">
                              <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                              </div>
                            </div>
                          </div>

                          <div class="text-center my-2">
                            <div class="font-size-60"><?php if($pro == "" ){echo "0";}else{echo "$pro";} ?></div>
                            <span>pro</span>
                          </div>
                        </div>
                      </div>
                  </div>

                  <div class="col-xl-3 col-md-6 col-12">
                      <div class="box box-inverse box-success">
                        <div class="box-body">
                          <div class="flexbox">
                            <h5>Boss </h5>
                            <div class="dropdown">
                              <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                              </div>
                            </div>
                          </div>

                          <div class="text-center my-2">
                            <div class="font-size-60"><?php if($boss == "" ){echo "0";}else{echo "$boss";} ?></div>
                            <span>Boss</span>
                          </div>
                        </div>
                      </div>
                  </div>

                  <div class="col-xl-3 col-md-6 col-12">
                      <div class="box box-inverse box-danger">
                        <div class="box-body">
                          <div class="flexbox">
                            <h5>sub user </h5>
                            <div class="dropdown">
                              <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                              </div>
                            </div>
                          </div>

                          <div class="text-center my-2">
                            <div class="font-size-60"><?php if($sub_user == "" ){echo "0";}else{echo "$sub_user";} ?></div>
                            <span>sub user</span>
                          </div>
                        </div>
                      </div>
                  </div>

                  <div class="col-xl-3 col-md-6 col-12">
                      <div class="box box-inverse box-primary">
                        <div class="box-body">
                          <div class="flexbox">
                            <h5>shop </h5>
                            <div class="dropdown">
                              <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                              </div>
                            </div>
                          </div>

                          <div class="text-center my-2">
                            <div class="font-size-60"><?php if($shop == "" ){echo "0";}else{echo "$shop";} ?></div>
                            <span>shop</span>
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
                                <h4 class="box-title"><strong><?php echo lang('users'); ?></strong></h4>
                                <ul class="box-controls pull-right">
                                    <li><a class="box-btn-close" href="#"></a></li>
                                    <li><a class="box-btn-slide" href="#"></a></li>
                                    <li><a class="box-btn-fullscreen" href="#"></a></li>
                                </ul>
                            </div>

                            <div class="col-12">
                                <div class="box">
                                    <div class="box-header with-border">
                                        <h4 class="box-title"><?php echo lang('users'); ?></h4>
                                    </div>
                                    <div class="box-body">
                                        <div class="table-responsive">

                                            <table id="reports_1" class="table table-lg invoice-archive">
                                                <thead>
                                                <tr>
                                                  <th>#</th>
                                                    <th><?php echo lang('name'); ?></th>
                                                    <th><?php echo lang('phone'); ?></th>
                                                    <th><?php echo lang('email'); ?></th>
                                                    <th><?php echo lang('expire'); ?></th>
                                                    <th class="text-center"><span class="fa fa-cog"></span></th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                  <?php
                                                  $yy = date('Y');
                                                  $mm = date('m');
                                                  $dd = date('d');
                                                  $nowdate = "$dd-$mm-$yy";
                                                  $fetchUsers_sql = "SELECT Username,phone,name,email,nex_pay,admin,sus FROM signup";
                                                  $fetchUsers = $conn->prepare($fetchUsers_sql);
                                                  $fetchUsers->execute();
                                                  while ($rows = $fetchUsers->fetch(PDO::FETCH_ASSOC)) {
                                                  $serial += 1;
                                                  ?>
                                                      <tr>
                                                        <td>
                                                            <a><p><?php echo $serial; ?>
                                                        </td>
                                                          <td>
                                                              <a><p><?php echo $rows['Username']; ?><?php if( $rows['admin'] == "1" or  $rows['admin'] =="2"){echo" <span style='color:blue' class='fa fa-check-circle verifyUser'></span>";}echo"</p></a>";?>
                                                          </td>
                                                          <td>
                                                              <a href="tel:<?php echo $rows['phone']; ?>"><p><?php echo $rows['phone']; ?></p></a>
                                                          </td>
                                                          <td >
                                                              <a href="mailto:<?php echo $rows['email']; ?>"><p><?php echo $rows['email']; ?></p></a>
                                                          </td>
                                                          <td >
                                                              <a style="color:<?php $date_now = new DateTime();$date2 = new DateTime($rows['nex_pay']);if($date2 <= $date_now){echo red;}else{echo green;}?>"><p><?php echo $rows['nex_pay']; if($rows['sus'] == "1"){echo" <span style='color:red' class='fa fa-warning'></span>";} ?></p></a>
                                                          </td>
                                                          <td><a href="user?ed=<?php echo $rows['Username']; ?>" class="btn"><? echo lang('edit_delete_dashboard'); ?></a></td>
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
                                <h4 class="box-title"> <strong>Error log</strong></h4>
                                <ul class="box-controls pull-right">
                                    <li><a class="box-btn-close" href="#"></a></li>
                                    <li><a class="box-btn-slide" href="#"></a></li>
                                    <li><a class="box-btn-fullscreen" href="#"></a></li>
                                </ul>
                            </div>

                            <div class="col-lg-6 col-12 fixed-width-form">
                                <div class="box">
                                    <!-- /.box-header -->
                                    <div style="min-height:500px" dir="ltr" disabled class="dashboard_sbFetch">
                                      <p style="margin:5px;font-size:20px"><?php
                                      echo file_get_contents( "../error_log.txt" ); // get the contents, and echo it out.
                                      ?></p>
                                    </div>

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


</body>
</html>
