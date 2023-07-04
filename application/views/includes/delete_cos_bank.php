<?php
include("../config/connect.php");
session_start();
//==================delete the costumers bank========================
$c_id = htmlentities($_POST['cid'], ENT_QUOTES);
$sid =  $_SESSION['boss_id'];

$gfid = $rows['id'];
	$delete_comm_sql = "DELETE FROM bank WHERE bank_nam = :c_id AND boss_id = :sid";
	$delete_comm = $conn->prepare($delete_comm_sql);
  $delete_comm->bindParam(':sid',$sid,PDO::PARAM_INT);
	$delete_comm->bindParam(':c_id',$c_id,PDO::PARAM_INT);
	$delete_comm->execute();
	echo "yes";

?>
