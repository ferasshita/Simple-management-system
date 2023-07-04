<?php

include("../config/connect.php");
session_start();
$sid = $_SESSION['id'];
$shop_id = $_SESSION['shop_id'];
//==================function delet expenses========================
$c_id = htmlentities($_POST['cid'], ENT_QUOTES);
$p_id = htmlentities($_POST['pid'], ENT_QUOTES);
//===================check the user========================
$check = $conn->prepare("SELECT * FROM expenses WHERE id =:c_id");
$check->bindParam(':c_id',$c_id,PDO::PARAM_INT);
$check->execute();
while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
	$chR_aid = $chR['user_id'];
	$received = $chR['number'];
	$received_name = $chR['type'];
	$yt = $chR['yt'];
}
//=================== select the treasury==========================
$check = $conn->prepare("SELECT * FROM treasury WHERE kind=:received_name AND wh=:yt");
$check->bindParam(':received_name',$received_name,PDO::PARAM_INT);
$check->bindParam(':yt',$yt,PDO::PARAM_INT);
$check->execute();
while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
	$numbero = $chR['number'];
}
//===================calculate the treasury==========================
$numbery = $numbero+$received;

//==================delete the expense =================================
	$delete_comm_sql = "DELETE FROM expenses WHERE id = :c_id";
	$delete_comm = $conn->prepare($delete_comm_sql);
	$delete_comm->bindParam(':c_id',$c_id,PDO::PARAM_INT);
	$delete_comm->execute();
//===================update the treasury=========================
	$iptdbsql = "UPDATE treasury SET number=:numbery WHERE kind=:received_name AND wh=:yt AND shop_id=:shop_id";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':numbery', $numbery,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':yt',$yt,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':shop_id',$shop_id,PDO::PARAM_INT);
$insert_post_toDB->execute();
//=============================================================
	echo "yes";

?>
