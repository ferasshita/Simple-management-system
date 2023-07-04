<?php
include("../config/connect.php");
session_start();
//==================personal information========================
$c_id = htmlentities($_POST['cid'], ENT_QUOTES);
$sid =  $_SESSION['boss_id'];
//================delete the company's bank=============================
	$delete_comm_sql = "UPDATE signup SET package_chose=:c_id WHERE boss_id = :sid AND id = :sid";
	$delete_comm = $conn->prepare($delete_comm_sql);
  $delete_comm->bindParam(':c_id',$c_id,PDO::PARAM_INT);
  $delete_comm->bindParam(':sid',$sid,PDO::PARAM_INT);
	$delete_comm->execute();
//=============================================================
	echo "yes";

$_SESSION['package_chose'] = $c_id;
?>
