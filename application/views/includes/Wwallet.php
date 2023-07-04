<?php
session_start();
include "../config/connect.php";
$post_id = rand(0,9999999)+time();
$p_user_id = $_SESSION['id'];
$p_author = $_SESSION['Fullname'];
$p_author_photo = $_SESSION['Userphoto'];
$shop_id = $_SESSION['shop_id'];
$boss_id = $_SESSION['boss_id'];
$timec = time();
$un = filter_var(htmlspecialchars($_POST['amou']),FILTER_SANITIZE_STRING);
$received_name = filter_var(htmlspecialchars($_POST['received_name']),FILTER_SANITIZE_STRING);
$ex = filter_var(htmlspecialchars($_POST['ex']),FILTER_SANITIZE_STRING);
$time = filter_var(htmlspecialchars($_POST['time']),FILTER_SANITIZE_STRING);
$note = filter_var(htmlspecialchars($_POST['note']),FILTER_SANITIZE_STRING);
$ty = filter_var(htmlspecialchars($_POST['ty']),FILTER_SANITIZE_STRING);
$cda = filter_var(htmlspecialchars($_POST['cda']),FILTER_SANITIZE_STRING);
$headed = filter_var(htmlspecialchars($_POST['headed']),FILTER_SANITIZE_STRING);
$id = filter_var(htmlspecialchars($_POST['id']),FILTER_SANITIZE_STRING);

$submit_name = filter_var(htmlspecialchars($_POST['submit_name']),FILTER_SANITIZE_STRING);
$amoun_submit = filter_var(htmlspecialchars($_POST['amoun_submit']),FILTER_SANITIZE_STRING);
$from_name = filter_var(htmlspecialchars($_POST['from_name']),FILTER_SANITIZE_STRING);
$to_name = filter_var(htmlspecialchars($_POST['to_name']),FILTER_SANITIZE_STRING);

if($received_name == "LYD"){
  $calc = "";
}else{
	$calc= $un*$ex;
}
if($cda == "bank"){
  $bgh = "bank";
  //===================check if there is money in wallet=====================================
  $vpsql = "SELECT * FROM capital WHERE shop_id=:p_user_id AND kind=:received_name AND type=:ty AND wh=:id AND tyi=:bgh";
  $view_posts = $conn->prepare($vpsql);
  $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
  $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
  $view_posts->bindParam(':id', $id, PDO::PARAM_INT);
  $view_posts->bindParam(':ty', $ty, PDO::PARAM_INT);
  $view_posts->bindParam(':bgh', $bgh, PDO::PARAM_STR);
  $view_posts->execute();
  $numvg = $view_posts->rowCount();
  while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
  $numberg = $postsfetch['number'];
  }
  $type = "head";
  $vpsql = "SELECT * FROM capital WHERE shop_id=:p_user_id AND type=:ty AND wh=:id AND tyi=:bgh";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
  $view_postsi->bindParam(':ty', $gth, PDO::PARAM_INT);
  $view_postsi->bindParam(':id', $id, PDO::PARAM_INT);
  $view_postsi->bindParam(':bgh', $bgh, PDO::PARAM_STR);
  $view_postsi->execute();
  $numfdy = $view_postsi->rowCount();
  while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
  $kindaq = $postsfetch['kind'];
  }

  $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:ty AND wh=:id AND tyi=:bgh";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
  $view_postsi->bindParam(':ty', $kindaq, PDO::PARAM_INT);
  $view_postsi->bindParam(':id', $id, PDO::PARAM_INT);
  $view_postsi->bindParam(':bgh', $bgh, PDO::PARAM_STR);
  $view_postsi->execute();
  while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
  $numberaq = $postsfetch['number'];
  }
  //=======================end of the checking==============================
  //=======================start insert or update wallet=========================
  	$iptdbsql = "INSERT INTO capital
  (user_id,shop_id,boss_id,number,exchange,kind,calc,type,note,wh,tyi,headed,time,timex)
  VALUES
  ( :p_user_id, :shop_id, :boss_id, :un, :ex, :received_name, :calc, :type, :note, :id, :bgh, :headed, :time, :timec)
  ";
  $insert_post_toDB = $conn->prepare($iptdbsql);
  $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':timec', $timec,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':ex', $ex,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':headed', $headed,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':calc', $calc,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':note', $note,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':id', $id, PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':type', $type,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':bgh', $bgh, PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':time', $time, PDO::PARAM_STR);
  $insert_post_toDB->execute();
  $gskl = $numberaq-$calc;
  $iptdbsql = "UPDATE treasury SET number=:numbero WHERE shop_id = :p_user_id AND kind=:gth AND wh=:id AND tyi=:bgh";
  $insert_post_toDB = $conn->prepare($iptdbsql);
  $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':numbero', $gskl,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':gth', $kindaq,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':id', $id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':bgh', $bgh, PDO::PARAM_STR);
  $insert_post_toDB->execute();
  //=======================end of insert or update profit=========================
  //========================check if there is money==============================
  $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:received_name AND wh=:id AND tyi=:bgh";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
  $view_postsi->bindParam(':received_name', $received_name, PDO::PARAM_INT);
  $view_postsi->bindParam(':id', $id, PDO::PARAM_INT);
  $view_postsi->bindParam(':bgh', $bgh, PDO::PARAM_STR);
  $view_postsi->execute();
  $numsh = $view_postsi->rowCount();
  while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
  $number = $postsfetch['number'];
  }
  $numbercalv = $number+$un;
  //==============================end of the checking================================
  //==============================insert money========================================
  if(1 > $numsh){
  	    $iptdbsql = "INSERT INTO treasury
  (user_id,shop_id,boss_id,kind,number,wh,tyi)
  VALUES
  ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
  ";
  $insert_post_toDB = $conn->prepare($iptdbsql);
  $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':id', $id, PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':bgh', $bgh, PDO::PARAM_STR);
  $insert_post_toDB->execute();
  }else{
  	$iptdbsql = "UPDATE treasury SET number=:numbercalv WHERE shop_id = :p_user_id AND kind=:received_name AND wh=:id AND tyi=:bgh";
  $insert_post_toDB = $conn->prepare($iptdbsql);
  $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':numbercalv', $numbercalv,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':id', $id, PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':bgh', $bgh, PDO::PARAM_STR);
  $insert_post_toDB->execute();
  }
}elseif($cda == "transfar"){
  $bgha = "cash";
  $bghb = "bank";
  $bgh = "transfar";
if($from_name == "0"){
$ty_kin = "cash";
}else{
$ty_kin = "bank";
}
$type = "head";
  $vpsql = "SELECT * FROM capital WHERE shop_id=:sid AND kind=:received_name AND tyi=:tyi";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
  $view_postsi->bindParam(':received_name', $from_name, PDO::PARAM_INT);
  $view_postsi->bindParam(':tyi', $ty_kin, PDO::PARAM_INT);
  $view_postsi->execute();
  $num = $view_postsi->rowCount();
  while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
    $numberhea = $postsfetch['number'];
    $exchangehea = $postsfetch['exchange'];
    $tyhea = $postsfetch['kind'];
    $ty_gt = $postsfetch['type'];
  }
  //=============calculate the average of the exchange rate=======================
  $vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE shop_id=:sid AND kind=:from_name AND tyi=:tyi";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
  $view_postsi->bindParam(':from_name', $given_name, PDO::PARAM_INT);
  $view_postsi->bindParam(':tyi', $ty_kin, PDO::PARAM_INT);
  $view_postsi->execute();
  while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
    $ty_uy = $postsfetch['ty_uy'];
  }
  $vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE shop_id=:sid AND kind=:from_name AND tyi=:tyi";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
  $view_postsi->bindParam(':from_name', $given_name, PDO::PARAM_INT);
  $view_postsi->bindParam(':tyi', $ty_kin, PDO::PARAM_INT);
  $view_postsi->execute();
  while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
    $ty_ji = $postsfetch['ty_ji'];
  }
  $medid= $ty_uy/$ty_ji;
  $media = number_format("$medid",2, ".", "");

  if($submit_name == "LYD"){
    $calcx = "";
  }else{
  	$calcx= $amoun_submit*$media;
  }
if($from_name == "0"){
  $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:ty AND wh=:id AND tyi=:bgh";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
  $view_postsi->bindParam(':ty', $submit_name, PDO::PARAM_INT);
  $view_postsi->bindParam(':id', $from_name, PDO::PARAM_INT);
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
  $view_postsi->bindParam(':ty', $submit_name, PDO::PARAM_INT);
  $view_postsi->bindParam(':id', $from_name, PDO::PARAM_INT);
  $view_postsi->bindParam(':bgh', $bghb, PDO::PARAM_STR);
  $view_postsi->execute();
  $numva = $view_postsi->rowCount();
  while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
  $numbera = $postsfetch['number'];
  }
}
if($numbera >= $amoun_submit){
  unset($_SESSION['myerrortrban']);

if($to_name == "0"){
  $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:ty AND wh=:id AND tyi=:bgh";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
  $view_postsi->bindParam(':ty', $submit_name, PDO::PARAM_INT);
  $view_postsi->bindParam(':id', $to_name, PDO::PARAM_INT);
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
  $view_postsi->bindParam(':ty', $submit_name, PDO::PARAM_INT);
  $view_postsi->bindParam(':id', $to_name, PDO::PARAM_INT);
  $view_postsi->bindParam(':bgh', $bghb, PDO::PARAM_STR);
  $view_postsi->execute();
  $numvb = $view_postsi->rowCount();
  while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
  $numberb = $postsfetch['number'];
  }
}
$numberca = $numbera-$amoun_submit;
$numbercb = $numberb+$amoun_submit;
  //=======================end of the checking==============================
  //=======================start insert or update wallet=========================
  	$iptdbsql = "INSERT INTO capital
  (user_id,shop_id,boss_id,number,exchange,kind,calc,type,note,wh,whb,tyi,headed,time,timex)
  VALUES
  ( :p_user_id, :shop_id, :boss_id, :un, :ex, :from_name, :calc, :type, :note, :id, :whb, :bgh, :headed, :time, :timec)
  ";
  $insert_post_toDB = $conn->prepare($iptdbsql);
  $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':timec', $timec,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':un', $amoun_submit,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':ex', $media,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':headed', $headed,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':from_name', $submit_name,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':calc', $calcx,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':note', $note,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':id', $to_name, PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':whb', $from_name, PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':type', $type,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':bgh', $bgh, PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':time', $time, PDO::PARAM_STR);
  $insert_post_toDB->execute();

  //==============================end of the checking================================
  //==============================insert money========================================
  	$iptdbsql = "UPDATE treasury SET number=:numbercalv WHERE shop_id = :p_user_id AND kind=:received_name AND wh=:id";
  $insert_post_toDB = $conn->prepare($iptdbsql);
  $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':numbercalv', $numberca,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':id', $from_name, PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':received_name', $submit_name,PDO::PARAM_INT);
  $insert_post_toDB->execute();

  if(1 > $numvb){
  	    $iptdbsql = "INSERT INTO treasury
  (user_id,shop_id,boss_id,kind,number,wh,tyi)
  VALUES
  ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
  ";
  $insert_post_toDB = $conn->prepare($iptdbsql);
  $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':un', $amoun_submit,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':id', $to_name, PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':received_name', $submit_name,PDO::PARAM_STR);
if($to_name == "0"){
  $insert_post_toDB->bindParam(':bgh', $bgha, PDO::PARAM_STR);
}else{
  $insert_post_toDB->bindParam(':bgh', $bghb, PDO::PARAM_STR);
}
  $insert_post_toDB->execute();
  }else{
  	$iptdbsql = "UPDATE treasury SET number=:numbercalv WHERE shop_id = :p_user_id AND kind=:received_name AND wh=:id";
  $insert_post_toDB = $conn->prepare($iptdbsql);
  $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':numbercalv', $numbercb,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':id', $to_name, PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':received_name', $submit_name,PDO::PARAM_INT);
  $insert_post_toDB->execute();
  }
  }else{
  $_SESSION['myerrortrban'] = number_format("$numbera",2, ".", "")." $submit_name :".lang('youhave');
  }
}else{
  $fgdx = "cash";
//===================check if there is wallet=====================================
$vpsql = "SELECT * FROM capital WHERE shop_id=:p_user_id AND kind=:received_name AND type=:ty AND tyi=:fgdx";
$view_posts = $conn->prepare($vpsql);
$view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
$view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
$view_posts->bindParam(':ty', $ty, PDO::PARAM_INT);
$view_posts->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
$view_posts->execute();
$numvg = $view_posts->rowCount();
while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
$numberg = $postsfetch['number'];
}
$type = "head";
$vpsql = "SELECT * FROM capital WHERE shop_id=:p_user_id AND type=:ty AND tyi=:fgdx";
$view_postsi = $conn->prepare($vpsql);
$view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
$view_postsi->bindParam(':ty', $gth, PDO::PARAM_INT);
$view_postsi->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
$view_postsi->execute();
$numfdy = $view_postsi->rowCount();
while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
$kindaq = $postsfetch['kind'];
}

$vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:ty AND tyi=:fgdx";
$view_postsi = $conn->prepare($vpsql);
$view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
$view_postsi->bindParam(':ty', $kindaq, PDO::PARAM_INT);
$view_postsi->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
$view_postsi->execute();
while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
$numberaq = $postsfetch['number'];
}
//=======================end of the checking==============================
//=======================start insert or update profit=========================
	$iptdbsql = "INSERT INTO capital
(user_id,shop_id,boss_id,number,exchange,kind,calc,type,note,tyi,headed,time,timex)
VALUES
( :p_user_id, :shop_id, :boss_id, :un, :ex, :received_name, :calc, :type, :note, :fgdx, :headed, :time,:timec)
";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':timec', $timec,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':headed', $headed,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':ex', $ex,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':calc', $calc,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':note', $note,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':type', $type,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
$insert_post_toDB->bindParam(':time', $time, PDO::PARAM_STR);
$insert_post_toDB->execute();
$gskl = $numberaq-$calc;
$iptdbsql = "UPDATE treasury SET number=:numbero WHERE shop_id = :p_user_id AND kind=:gth AND tyi=:fgdx";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':numbero', $gskl,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':gth', $kindaq,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
$insert_post_toDB->execute();
//=======================end of insert or update profit=========================
//========================check if there is money==============================
$vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:received_name AND tyi=:fgdx";
$view_postsi = $conn->prepare($vpsql);
$view_postsi->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
$view_postsi->bindParam(':received_name', $received_name, PDO::PARAM_INT);
$view_postsi->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
$view_postsi->execute();
$numsh = $view_postsi->rowCount();
while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
$number = $postsfetch['number'];
}
$numbercalv = $number+$un;
//==============================end of the checking================================
//==============================insert money========================================
if(1 > $numsh){
	    $iptdbsql = "INSERT INTO treasury
(user_id,shop_id,boss_id,kind,number,tyi)
VALUES
( :p_user_id, :shop_id, :boss_id, :received_name, :un, :fgdx)
";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
$insert_post_toDB->execute();
}else{
	$iptdbsql = "UPDATE treasury SET number=:numbercalv WHERE shop_id = :p_user_id AND kind=:received_name AND tyi=:fgdx";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':numbercalv', $numbercalv,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':fgdx', $fgdx, PDO::PARAM_STR);
$insert_post_toDB->execute();
}
}
//================================end of insertation===================================
?>
