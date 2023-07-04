<?php
session_start();
include("../config/connect.php");
include("time_function.php");
$comment_id = htmlentities($_POST['cid'], ENT_QUOTES);
$edit_commant_var = htmlentities($_POST['cContent'], ENT_QUOTES);

$yy = date('Y');
$mm = date('m');
$dd = date('d');

$timeEdited = "$dd/$mm/$yy";

$commentEdit_sql = "UPDATE transactions SET setov= :edit_commant_var ,setova= :timeEdited WHERE post_id= :comment_id";
$commentEdit = $conn->prepare($commentEdit_sql);
$commentEdit->bindParam(':edit_commant_var',$edit_commant_var,PDO::PARAM_STR);
$commentEdit->bindParam(':timeEdited',$timeEdited,PDO::PARAM_STR);
$commentEdit->bindParam(':comment_id',$comment_id,PDO::PARAM_INT);
$commentEdit->execute();


exit();
?>
