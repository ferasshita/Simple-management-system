<?php
session_start();
include "../config/connect.php";
$post_id = rand(0,9999999)+time();
$post_ido = rand(0,9999999)+time();
$timec = time();
$p_user_id = $_SESSION['id'];
$shop_id = $_SESSION['shop_id'];
$boss_id = $_SESSION['boss_id'];
$p_author = $_SESSION['Fullname'];
$p_author_photo = $_SESSION['Userphoto'];
//==================from the form of transaction==============================
$un = filter_var(htmlspecialchars($_POST['amou']),FILTER_SANITIZE_STRING);
$p_time = filter_var(htmlspecialchars($_POST['time']),FILTER_SANITIZE_STRING);
$pd = filter_var(htmlspecialchars($_POST['lyamou']),FILTER_SANITIZE_STRING);
$usd = filter_var(htmlspecialchars($_POST['price']),FILTER_SANITIZE_STRING);
$date = filter_var(htmlspecialchars($_POST['date']),FILTER_SANITIZE_STRING);
$received_name = filter_var(htmlspecialchars($_POST['received_name']),FILTER_SANITIZE_STRING);
$given_name = filter_var(htmlspecialchars($_POST['given_name']),FILTER_SANITIZE_STRING);
$name = filter_var(htmlspecialchars($_POST['name']),FILTER_SANITIZE_STRING);
if($name == NULL){
$name = "casher";
}
if($given_name == "LYD"){
$kin = lang('sell');
}else{
  $kin = lang('buy');
}
$ty_kin = "cash";
//=================select the capital =========================
$vpsql = "SELECT * FROM capital WHERE shop_id=:sid AND kind=:received_name AND tyi=:tyi";
$view_postsi = $conn->prepare($vpsql);
$view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
$view_postsi->bindParam(':received_name', $received_name, PDO::PARAM_INT);
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
$vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE shop_id=:sid AND kind=:received_name AND tyi=:tyi";
$view_postsi = $conn->prepare($vpsql);
$view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
$view_postsi->bindParam(':received_name', $given_name, PDO::PARAM_INT);
$view_postsi->bindParam(':tyi', $ty_kin, PDO::PARAM_INT);
$view_postsi->execute();
while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
  $ty_uy = $postsfetch['ty_uy'];
}
$vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE shop_id=:sid AND kind=:received_name AND tyi=:tyi";
$view_postsi = $conn->prepare($vpsql);
$view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
$view_postsi->bindParam(':received_name', $given_name, PDO::PARAM_INT);
$view_postsi->bindParam(':tyi', $ty_kin, PDO::PARAM_INT);
$view_postsi->execute();
while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
  $ty_ji = $postsfetch['ty_ji'];
}
$medid= $ty_uy/$ty_ji;
$media = number_format("$medid",2, ".", "");
//======================check the treasury====================
$vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:received_name AND tyi='cash'";
$view_posts = $conn->prepare($vpsql);
$view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
$view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
$view_posts->execute();
$numvf = $view_posts->rowCount();
while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
$numberya = $postsfetch['number'];
}
$vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:given_name AND tyi='cash'";
$view_posts = $conn->prepare($vpsql);
$view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
$view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
$view_posts->execute();
$numvh = $view_posts->rowCount();
while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
$numberyb = $postsfetch['number'];
}

if($numberyb >= $pd){
  unset($_SESSION['myerror']);
if($received_name == "LYD"){
  $calc = "";
}else{
      if($usd > 1){
          $calc= $un*$usd;
      }else{
          $calc= $un/$usd;
      }
}
//===============end of the checking=================================
//================calculate the values (buy & sell)========================
if($kin == lang('sell')){
  //taken treasury value
  $numbero = $numberya+$un;
  //given treasury value
  $numberb = $numberyb-$pd;
}elseif($kin == lang('buy')){
  //taken treasury value
  $numbero = $numberya+$un;
  //given treasury value
  $numberb = $numberyb-$pd;
}
//=====================insert the value into tabel transaction=====================
    $iptdbsqli = "INSERT INTO transactions
(post_id,user_id,chak_id,exchange,received,given,received_name,given_name,kin,type,media,time,date,timex)
VALUES
( :post_id, :p_user_id, :post_ido, :usd, :un, :pd, :received_name, :given_name, :kin, :ty_kin, :media, :p_time, :date, :timec)
";
$insert_post_toDBi = $conn->prepare($iptdbsqli);
$insert_post_toDBi->bindParam(':post_id', $post_id,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':p_time', $p_time,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':media', $media,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':un', $un,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':timec', $timec,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':date', $date,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':received_name', $received_name,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':kin', $kin,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':pd', $pd,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':ty_kin', $ty_kin,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':usd', $usd,PDO::PARAM_STR);
$insert_post_toDBi->execute();

$vpsql = "SELECT * FROM costumers WHERE shop_id=:sid AND name=:name";
$view_postsi = $conn->prepare($vpsql);
$view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
$view_postsi->bindParam(':name', $name, PDO::PARAM_INT);
$view_postsi->execute();
$num_name = $view_postsi->rowCount();
while ($post_viewi = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
  $main_id = $post_viewi['main_id'];
}
if($num_name < 1){
$main_id = rand(0,9999999)+time();
  $iptdbsqli = "INSERT INTO costumers
  (main_id,boss_id,shop_id,user_id,name)
  VALUES
  ( :main_id, :boss_id, :shop_id, :user_id, :name)
  ";
  $insert_post_toDBi = $conn->prepare($iptdbsqli);
  $insert_post_toDBi->bindParam(':main_id', $main_id,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':user_id', $p_user_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':name', $name,PDO::PARAM_STR);
  $insert_post_toDBi->execute();
}
$iptdbsqli = "INSERT INTO cos_transactions
(post_id,user_id,cos_id)
VALUES
( :post_ido, :p_user_id, :cosnam)
";
$insert_post_toDBi = $conn->prepare($iptdbsqli);
$insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':cosnam', $main_id,PDO::PARAM_INT);
$insert_post_toDBi->execute();
//===================insert the value into table treasury================================
$cash = "cash";

if(1 > $numvf){
  $iptdbsql = "INSERT INTO treasury
(user_id, shop_id, boss_id,kind,number,tyi)
VALUES
( :p_user_id, :shop_id, :boss_id, :received_name, :un, :cash)
";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':cash', $cash,PDO::PARAM_STR);
$insert_post_toDB->execute();
}else{
    $iptdbsql = "UPDATE treasury SET number=:numbero WHERE shop_id = :p_user_id AND kind=:received_name AND tyi='cash'";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
$insert_post_toDB->execute();
}
if(1 > $numvh){
  $iptdbsql = "INSERT INTO treasury
(user_id, shop_id, boss_id,kind,number,tyi)
VALUES
( :p_user_id, :shop_id, :boss_id, :given_name, :un, :cash)
";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':cash', $cash,PDO::PARAM_STR);
$insert_post_toDB->execute();
}else{
    $iptdbsql = "UPDATE treasury SET number=:numberb WHERE shop_id = :shop_id AND kind=:given_name AND tyi='cash'";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':numberb', $numberb,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
$insert_post_toDB->execute();
}
//============================insert into tabel capital==========================================
if($kin == lang('sell')){
  $iptdbsql = "INSERT INTO capital
(user_id, shop_id, boss_id,number,exchange,kind,calc,tyi,timex)
VALUES
( :p_user_id, :shop_id, :boss_id, :un, :usd, :received_name, :calc, :cash,:timec)
";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':un', $un,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':timec', $timec,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':usd', $usd,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':calc', $calc,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':cash', $cash,PDO::PARAM_STR);
$insert_post_toDB->execute();
}
//=============================end insert into table capital ===================================
//=============================error: there is no money in treasury ===================================
}else{
$_SESSION['myerror'] = number_format("$numberyb",2, ".", "")." $given_name :".lang('youhave');
}

//=======================fetching the information in the table============================
include("fetch_users_info.php");
include ("time_function.php");
include ("num_k_m_count.php");
$vpsql = "SELECT * FROM transactions WHERE post_id = :post_id";
$view_posts = $conn->prepare($vpsql);
$view_posts->bindParam(':post_id', $post_id, PDO::PARAM_INT);
$view_posts->execute();
include "fetch_posts.php";
?>
