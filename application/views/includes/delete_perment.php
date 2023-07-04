<?php
include("../config/connect.php");
session_start();
$sid =  $_SESSION['id'];
//==================from the function========================
$c_id = htmlentities($_POST['cid'], ENT_QUOTES);
$p_id = htmlentities($_POST['pid'], ENT_QUOTES);
$delo = "1";
//===================check the user========================
$check = $conn->prepare("SELECT * FROM transactions WHERE post_id =:c_id");
$check->bindParam(':c_id',$c_id,PDO::PARAM_INT);
$check->execute();
while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
	$chR_aid = $chR['user_id'];
	$received = $chR['received'];
	$given = $chR['given'];
	$received_name = $chR['received_name'];
	$given_name = $chR['given_name'];
}
//==================delete the transaction =================================
	$delete_comm_sql = "DELETE FROM transactions WHERE hide=:numbery AND post_id = :c_id";
	$delete_comm = $conn->prepare($delete_comm_sql);
	$delete_comm->bindParam(':numbery',$delo,PDO::PARAM_INT);
	$delete_comm->bindParam(':c_id',$c_id,PDO::PARAM_INT);
	$delete_comm->execute();
//=============================================================
	echo "yes";

?>
