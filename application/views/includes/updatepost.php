<?php
include("../config/connect.php");
// ================= recive data from ajax data ======================================
$exchange = trim(htmlspecialchars($_POST['exchange']));
$amousel = trim(htmlspecialchars($_POST['amousel']));
$received = trim(htmlspecialchars($_POST['received']));
$amouse = trim(htmlspecialchars($_POST['amouse']));
$amountsd = trim(htmlspecialchars($_POST['amountsd']));
$selbu = trim(htmlspecialchars($_POST['selbu']));
$pid = htmlspecialchars($_POST['pid']);
// ================= PDO sql query ===================================================
$edit_post_sql = "UPDATE transactions SET exchange= :exchange,received = :received,received_name = :amousel,amountsd=:amountsd,given_name =:amouse,kin=:selbu WHERE post_id= :pid";
$edit_post = $conn->prepare($edit_post_sql);
$edit_post->bindParam(':exchange',$exchange,PDO::PARAM_STR);
$edit_post->bindParam(':amousel',$amousel,PDO::PARAM_STR);
$edit_post->bindParam(':received',$received,PDO::PARAM_INT);
$edit_post->bindParam(':amouse',$amouse,PDO::PARAM_INT);
$edit_post->bindParam(':amountsd',$amountsd,PDO::PARAM_INT);
$edit_post->bindParam(':selbu',$selbu,PDO::PARAM_INT);
$edit_post->bindParam(':pid',$pid,PDO::PARAM_INT);
$edit_post->execute();
exit;
?>
