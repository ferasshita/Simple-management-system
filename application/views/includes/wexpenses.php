<?php
session_start();
include "../config/connect.php";
$post_id = rand(0,9999999)+time();
$p_user_id = $_SESSION['id'];
$p_author = $_SESSION['Fullname'];
$boss_id = $_SESSION['boss_id'];
$shop_id = $_SESSION['shop_id'];
$p_author_photo = $_SESSION['Userphoto'];
$timec = time();
//===================which boss====================
$sid =  $_SESSION['id'];
$shopo =  $_SESSION['shop_id'];
$typo =  $_SESSION['type'];
$fetchUsers_sql = "SELECT id FROM signup WHERE boss_id='$sid' OR shop_id='$shopo' AND type='admin' OR id='$sid'";
$fetchUsers = $conn->prepare($fetchUsers_sql);
$fetchUsers->execute();
while ($rows = $fetchUsers->fetch(PDO::FETCH_ASSOC)) {
$gfid = $rows['id'];
}
$yy = date('Y');
$mm = date('m');
$dd = date('d');
$jgj = "$yy-$mm-$dd";
//========================== input and form ===============================
$un = filter_var(htmlspecialchars($_POST['amou']),FILTER_SANITIZE_STRING);
$received_name = filter_var(htmlspecialchars($_POST['received_name']),FILTER_SANITIZE_STRING);
$ex = filter_var(htmlspecialchars($_POST['ex']),FILTER_SANITIZE_STRING);
$note = filter_var(htmlspecialchars($_POST['note']),FILTER_SANITIZE_STRING);
$notey = filter_var(htmlspecialchars($_POST['notey']),FILTER_SANITIZE_STRING);
$ty = filter_var(htmlspecialchars($_POST['ty']),FILTER_SANITIZE_STRING);
$idjd = filter_var(htmlspecialchars($_POST['idjd']),FILTER_SANITIZE_STRING);
$time = filter_var(htmlspecialchars($_POST['time']),FILTER_SANITIZE_STRING);
$yer = filter_var(htmlspecialchars($_POST['yer']),FILTER_SANITIZE_STRING);
if($received_name == "LYD"){
  $calc = "";
}else{
      if($ex > 1){
          $calc= $un*$ex;
      }else{
          $calc= $un/$ex;
      }
}
//===================check if there is money=====================================
$vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:ty AND wh=:idjd";
$view_postsi = $conn->prepare($vpsql);
$view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
$view_postsi->bindParam(':ty', $received_name, PDO::PARAM_INT);
$view_postsi->bindParam(':idjd', $idjd, PDO::PARAM_INT);
$view_postsi->execute();
while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
$numberaq = $postsfetch['number'];
}
if($numberaq >= $un){
  unset($_SESSION['myerrorb']);
}else{
if(!$numberaq){
  $_SESSION['myerrorb'] = "0 $received_name :".lang('youhave');
  return false;
}else{
  $_SESSION['myerrorb'] = number_format("$numberaq",2, ".", "")." $received_name :".lang('youhave');
  return false;
}
}
//=======================start insert or update profit=========================
	$iptdbsql = "INSERT INTO expenses
(user_id,boss_id,shop_id,number,ex,calc,type,note,notey,yt,time,datepost,yer,toyer,fgyer,timex)
VALUES
( :p_user_id, :boss_id, :shop_id, :un, :ex, :calc, :received_name, :note, :notey, :idjd, :time, :jgj, :yer, :yy, :fgyer, :timec)
";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':timec', $timec,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':fgyer', $yy,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':ex', $ex,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':yer', $yer,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':yy', $jgj,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':notey', $notey,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':calc', $calc,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':note', $note,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':idjd', $idjd,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':time', $time,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':jgj', $jgj,PDO::PARAM_STR);
$insert_post_toDB->execute();
//=======================end of insert or update profit=========================
//========================check if there is money==============================
$vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:received_name AND wh=:idjd";
$view_postsi = $conn->prepare($vpsql);
$view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
$view_postsi->bindParam(':received_name', $received_name, PDO::PARAM_INT);
$view_postsi->bindParam(':idjd', $idjd, PDO::PARAM_INT);
$view_postsi->execute();
$numsh = $view_postsi->rowCount();
while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
$number = $postsfetch['number'];
}
$numbercalv = $number-$un;
//==============================end of the checking================================
//==============================insert money========================================

	$iptdbsql = "UPDATE treasury SET number=:numbercalv WHERE shop_id = :p_user_id AND kind=:received_name AND wh=:idjd";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':numbercalv', $numbercalv,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
$insert_post_toDB->execute();

//================================end of insertation===================================
?>
