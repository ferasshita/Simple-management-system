<?php
include("../config/connect.php");
session_start();
$shopo =  $_SESSION['shop_id'];
$boss_id =  $_SESSION['boss_id'];
//==================from the function========================
$c_id = htmlentities($_POST['cid'], ENT_QUOTES);
$p_id = htmlentities($_POST['pid'], ENT_QUOTES);
$delo = "0";
//===================check the user========================
$check = $conn->prepare("SELECT * FROM capital WHERE id =:c_id");
$check->bindParam(':c_id',$c_id,PDO::PARAM_INT);
$check->execute();
while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
	$chR_aid = $chR['user_id'];
	$shop_id = $chR['shop_id'];
	$received = $chR['number'];
	$type = $chR['type'];
	$tyi = $chR['tyi'];
	$received_name = $chR['kind'];
	$wh = $chR['wh'];
	$whb = $chR['whb'];
}
if($tyi == "transfar"){
	$bgha = "cash";
  $bghb = "bank";
  $bgh = "transfar";
if($wh == "0"){
  $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:ty AND wh=:id AND tyi=:bgh";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
  $view_postsi->bindParam(':ty', $received_name, PDO::PARAM_INT);
  $view_postsi->bindParam(':id', $wh, PDO::PARAM_INT);
  $view_postsi->bindParam(':bgh', $bgha, PDO::PARAM_STR);
  $view_postsi->execute();
  $numva = $view_postsi->rowCount();
  while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
  $numbera = $postsfetch['number'];
  }
}else{
  $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:ty AND wh=:id AND tyi=:bgh";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
  $view_postsi->bindParam(':ty', $received_name, PDO::PARAM_INT);
  $view_postsi->bindParam(':id', $wh, PDO::PARAM_INT);
  $view_postsi->bindParam(':bgh', $bghb, PDO::PARAM_STR);
  $view_postsi->execute();
  $numva = $view_postsi->rowCount();
  while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
  $numbera = $postsfetch['number'];
  }
}

if($whb == "0"){
  $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:ty AND wh=:id AND tyi=:bgh";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
  $view_postsi->bindParam(':ty', $received_name, PDO::PARAM_INT);
  $view_postsi->bindParam(':id', $whb, PDO::PARAM_INT);
  $view_postsi->bindParam(':bgh', $bgha, PDO::PARAM_STR);
  $view_postsi->execute();
  $numvb = $view_postsi->rowCount();
  while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
  $numberb = $postsfetch['number'];
  }
}else{
  $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:ty AND wh=:id AND tyi=:bgh";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
  $view_postsi->bindParam(':ty', $received_name, PDO::PARAM_INT);
  $view_postsi->bindParam(':id', $whb, PDO::PARAM_INT);
  $view_postsi->bindParam(':bgh', $bghb, PDO::PARAM_STR);
  $view_postsi->execute();
  $numvb = $view_postsi->rowCount();
  while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
  $numberb = $postsfetch['number'];
  }
}
$numberca = $numbera-$received;
$numbercb = $numberb+$received;
}else{
//===================select from treasury==========================
$check = $conn->prepare("SELECT * FROM treasury WHERE shop_id =:chR_aid AND kind=:received_name AND wh=:wh");
$check->bindParam(':chR_aid',$shop_id,PDO::PARAM_INT);
$check->bindParam(':received_name',$received_name,PDO::PARAM_INT);
$check->bindParam(':wh',$wh,PDO::PARAM_INT);
$check->execute();
while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
	$numbero = $chR['number'];
}

//===================calculate the treasury==========================
$numbery = $numbero-$received;
}
//==================delete the wallet =================================
	$delete_comm_sql = "DELETE FROM capital WHERE id = :c_id";
	$delete_comm = $conn->prepare($delete_comm_sql);
	$delete_comm->bindParam(':c_id',$c_id,PDO::PARAM_INT);
	$delete_comm->execute();
if($tyi == "transfar"){
	$iptdbsql = "UPDATE treasury SET number=:numbercalv WHERE shop_id = :p_user_id AND kind=:received_name AND wh=:id";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':numbercalv', $numberca,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':id', $wh, PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
$insert_post_toDB->execute();

	$iptdbsql = "UPDATE treasury SET number=:numbercalv WHERE shop_id = :p_user_id AND kind=:received_name AND wh=:id";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':numbercalv', $numbercb,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':id', $whb, PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
$insert_post_toDB->execute();

}else{
//===================update your treasury=========================
	$iptdbsql = "UPDATE treasury SET number=:numbery WHERE shop_id = :shop_id AND kind=:received_name AND wh=:wh";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':numbery', $numbery,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':wh', $wh,PDO::PARAM_INT);
$insert_post_toDB->execute();
}
	echo "yes";


?>
