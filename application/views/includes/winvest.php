<?php
session_start();
include "../config/connect.php";
$post_id = rand(0,9999999)+time();
$post_ido = rand(0,9999999)+time();
$post_idv = rand(0,9999999)+time();
$p_user_id = $_SESSION['id'];
$boss_id = $_SESSION['boss_id'];
$shop_id = $_SESSION['shop_id'];
$p_author = $_SESSION['Fullname'];
$timec = time();
$p_author_photo = $_SESSION['Userphoto'];
//==================from the form==============================
$un = filter_var(htmlspecialchars($_POST['amou']),FILTER_SANITIZE_STRING);
$p_time = filter_var(htmlspecialchars($_POST['time']),FILTER_SANITIZE_STRING);
$pd = filter_var(htmlspecialchars($_POST['lyamou']),FILTER_SANITIZE_STRING);
$usdy = filter_var(htmlspecialchars($_POST['price']),FILTER_SANITIZE_STRING);
$date = filter_var(htmlspecialchars($_POST['date']),FILTER_SANITIZE_STRING);
$received_name = filter_var(htmlspecialchars($_POST['received_name']),FILTER_SANITIZE_STRING);
$precy = filter_var(htmlspecialchars($_POST['prec']),FILTER_SANITIZE_STRING);
$given_name = filter_var(htmlspecialchars($_POST['given_name']),FILTER_SANITIZE_STRING);
//investor
$infh_st = filter_var(htmlspecialchars($_POST['infh_st']),FILTER_SANITIZE_STRING);
$prec_st = filter_var(htmlspecialchars($_POST['prec_st']),FILTER_SANITIZE_STRING);
$price_st = filter_var(htmlspecialchars($_POST['price_st']),FILTER_SANITIZE_STRING);
$received_name_st = filter_var(htmlspecialchars($_POST['received_name_st']),FILTER_SANITIZE_STRING);
$amou_st = filter_var(htmlspecialchars($_POST['amou_st']),FILTER_SANITIZE_STRING);
$given_name_st = filter_var(htmlspecialchars($_POST['given_name_st']),FILTER_SANITIZE_STRING);
$lyamou_st = filter_var(htmlspecialchars($_POST['lyamou_st']),FILTER_SANITIZE_STRING);
//costom
$price_sd = filter_var(htmlspecialchars($_POST['price_sd']),FILTER_SANITIZE_STRING);
$received_name_sd = filter_var(htmlspecialchars($_POST['received_name_sd']),FILTER_SANITIZE_STRING);
$amou_sd = filter_var(htmlspecialchars($_POST['amou_sd']),FILTER_SANITIZE_STRING);
$given_name_sd = filter_var(htmlspecialchars($_POST['given_name_sd']),FILTER_SANITIZE_STRING);
$lyamou_sd = filter_var(htmlspecialchars($_POST['lyamou_sd']),FILTER_SANITIZE_STRING);

$howgia = filter_var(htmlspecialchars($_POST['howgia']),FILTER_SANITIZE_STRING);
$howgi = filter_var(htmlspecialchars($_POST['howgi']),FILTER_SANITIZE_STRING);

$giv = filter_var(htmlspecialchars($_POST['giv']),FILTER_SANITIZE_STRING);
$giva = filter_var(htmlspecialchars($_POST['giva']),FILTER_SANITIZE_STRING);
$pero = filter_var(htmlspecialchars($_POST['pero']),FILTER_SANITIZE_STRING);
$exc = filter_var(htmlspecialchars($_POST['exc']),FILTER_SANITIZE_STRING);

$postsea_now = filter_var(htmlspecialchars($_POST['infh']),FILTER_SANITIZE_STRING);
$cosnamf = filter_var(htmlspecialchars($_POST['name']),FILTER_SANITIZE_STRING);
$addressf = filter_var(htmlspecialchars($_POST['address']),FILTER_SANITIZE_STRING);
$phonef = filter_var(htmlspecialchars($_POST['phone']),FILTER_SANITIZE_STRING);
$emailf = filter_var(htmlspecialchars($_POST['email']),FILTER_SANITIZE_STRING);
$whhead = filter_var(htmlspecialchars($_POST['whichead']),FILTER_SANITIZE_STRING);

if($cosnamf != ""){
$name = $cosnamf;
}elseif($postsea_now != ""){
  $name = $postsea_now;
}elseif($cosnamf == ""){
$name = "casher";
}elseif($postsea_now == ""){
  $name = "casher";
}
//============================if condition buy or sell===============================
if($howgi){
  $kin = lang('given');
}elseif($giv){
  $kin = lang('given');
}else{
if($given_name == "LYD"){
$kin = lang('sell');
}else{
  $kin = lang('buy');
}
}
$buy = lang('buy');
$sell = lang('sell');
if($kin == "buy" || $kin == "بيع"){
$whheadt = $whhead;
}else {
  $whheadt = "";
}
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
(main_id,boss_id,shop_id,user_id,name,email,phone,address)
VALUES
( :main_id, :boss_id, :shop_id, :user_id, :name, :email, :phone, :address)
";
$insert_post_toDBi = $conn->prepare($iptdbsqli);
$insert_post_toDBi->bindParam(':main_id', $main_id,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':name', $name,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':address', $addressf,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':phone', $phonef,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':email', $emailf,PDO::PARAM_STR);
$insert_post_toDBi->execute();
}else{
if($address != ""){
$iptdbsql = "UPDATE costumers SET address=:address WHERE shop_id=:sid AND name=:name";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':address', $addressf,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':name', $name,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':sid', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->execute();
}if($phno != ""){
$iptdbsql = "UPDATE costumers SET phone=:phno WHERE shop_id=:sid AND name=:name";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':phno', $phonef,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':name', $name,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':sid', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->execute();
}if($email != ""){
$iptdbsql = "UPDATE costumers SET email=:email WHERE shop_id=:sid AND name=:name";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':email', $emailf,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':name', $name,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':sid', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->execute();
}
}
if($whheadt != ""){
$vpsql = "SELECT * FROM transactions WHERE post_id=:whheadt";
$view_postsi = $conn->prepare($vpsql);
$view_postsi->bindParam(':whheadt', $whheadt, PDO::PARAM_INT);
$view_postsi->execute();
$num = $view_postsi->rowCount();
while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
  $chak_idgj = $postsfetch['chak_id'];
  $exchangegj = $postsfetch['exchange'];

  $vpsql = "SELECT * FROM cos_transactions WHERE post_id=:chak_idgj";
  $view_posts = $conn->prepare($vpsql);
  $view_posts->bindParam(':chak_idgj', $chak_idgj, PDO::PARAM_INT);
  $view_posts->execute();
  $num = $view_posts->rowCount();
  while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
    $pergj = $postsfetch['invest_per'];

  }
}
}
if($whheadt != ""){
  $prec = $pergj;
}else{
$prec = $precy;
}
$usd = $usdy;
if($postsea_now != ""){
  $vpsql = "SELECT * FROM costumers WHERE boss_id=:sid AND name=:postsea_now";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':sid', $boss_id, PDO::PARAM_INT);
  $view_postsi->bindParam(':postsea_now', $postsea_now, PDO::PARAM_INT);
  $view_postsi->execute();
  while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
    $address = $postsfetch['address'];
    $phone = $postsfetch['phone'];
    $email = $postsfetch['email'];
    $cosnam = $postsfetch['name'];
  }
}else{
  $cosnam = "$cosnamf";
  $address = "$addressf";
  $phone = "$phonef";
  $email = "$emailf";
}

$calcr = $usd*$un;
$sid =  $_SESSION['id'];
$branch = "LYD";
$vpsql = "SELECT * FROM transactions WHERE user_id=:sid";
$view_posts = $conn->prepare($vpsql);
$view_posts->bindParam(':sid', $sid, PDO::PARAM_INT);
$view_posts->execute();
while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
  $ghj = $postsfetch['received'];
  $kind = $postsfetch['type'];
  $received_nameu = $postsfetch['received_name'];


$media = $exchangegj;
}
$vpsqlo = "SELECT * FROM transactions WHERE post_id=:whhead";
$view_postso = $conn->prepare($vpsqlo);
$view_postso->bindParam(':whhead', $whhead, PDO::PARAM_INT);
$view_postso->execute();
while ($postsfetcho = $view_postso->fetch(PDO::FETCH_ASSOC)) {
$chak_idfsf = $postsfetcho['chak_id'];

$vpsqli = "SELECT * FROM cos_transactions WHERE post_id=:chak_id";
$view_postsi = $conn->prepare($vpsqli);
$view_postsi->bindParam(':chak_id', $chak_idfsf, PDO::PARAM_INT);
$view_postsi->execute();
while ($postsfetchi = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
$cutro = $postsfetchi['cutr'];
}
}
if($howgi){
$vpsql = "SELECT * FROM invest_treasury WHERE user_id=:p_user_id AND kind=:howgia AND name=:name";
$view_posts = $conn->prepare($vpsql);
$view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
$view_posts->bindParam(':howgia', $howgia, PDO::PARAM_INT);
$view_posts->bindParam(':name', $cosnam, PDO::PARAM_INT);
$view_posts->execute();
$numvf = $view_posts->rowCount();
while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
$bghh = $postsfetch['number'];
}
$ddf = $bghh-$howgi;
}else{
//======================check the cad====================
$vpsql = "SELECT * FROM invest_treasury WHERE user_id=:p_user_id AND kind=:received_name AND name=:name";
$view_posts = $conn->prepare($vpsql);
$view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
$view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
$view_posts->bindParam(':name', $cosnam, PDO::PARAM_INT);
$view_posts->execute();
$numvf = $view_posts->rowCount();
while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
$numberya = $postsfetch['number'];
}
$vpsql = "SELECT * FROM invest_treasury WHERE user_id=:p_user_id AND kind=:given_name AND name=:name";
$view_posts = $conn->prepare($vpsql);
$view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
$view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
$view_posts->bindParam(':name', $cosnam, PDO::PARAM_INT);
$view_posts->execute();
$numvh = $view_posts->rowCount();
while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
$numberyb = $postsfetch['number'];
}
}
//===============end of the checking=================================
//================calculate the values========================
if($kin == lang('sell')){
//taken invest_treasury value
$numbero = $numberya+$un;
//given invest_treasury value
$numberb = $numberya-$pd;
}elseif($kin == lang('buy')){
  //taken invest_treasury value
  $numbero = $numberya+$un;
  //given invest_treasury value
  $numberb = $numberyb-$pd;
}
  $ty_kin = lang('invest');
//=====================insert into cash=====================
if($prec_st != ""){
  $dfs = "0";
  $iptdbsqli = "INSERT INTO cos_transactions
  (post_id,user_id,cos_id,type,invest_per,calcr,head,cutr)
  VALUES
  ( :post_ido, :p_user_id, :cosnam, :type, :prec, :calcr, :whhead, :cutr)
  ";
  $insert_post_toDBi = $conn->prepare($iptdbsqli);
  $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':cosnam', $main_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':whhead', $whhead,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':cutr', $un,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':prec', $prec_st,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':type', $ty_kin,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':calcr', $dfs,PDO::PARAM_STR);
  $insert_post_toDBi->execute();
  $post_idt = rand(0,9999999)+time();

  $iptdbsqli = "INSERT INTO cos_transactions
  (post_id,user_id,cos_id,type,invest_per,calcr,head)
  VALUES
  ( :post_ido, :p_user_id, :cosnam, :type, :prec, :calcr, :whhead)
  ";
  $insert_post_toDBi = $conn->prepare($iptdbsqli);
  $insert_post_toDBi->bindParam(':post_ido', $post_idt,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':cosnam', $main_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':whhead', $post_idv,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':prec', $prec_st,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':type', $ty_kin,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':calcr', $dfs,PDO::PARAM_STR);
  $insert_post_toDBi->execute();
}elseif($kin == "buy" || $kin == "بيع"){
$iptdbsqli = "INSERT INTO cos_transactions
(post_id,user_id,cos_id,type,invest_per,calcr,head)
VALUES
( :post_ido, :p_user_id, :cosnam, :type, :prec, :calcr, :whhead)
";
$insert_post_toDBi = $conn->prepare($iptdbsqli);
$insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':cosnam', $main_id,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':whhead', $whhead,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':prec', $prec,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':type', $ty_kin,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':calcr', $calcr,PDO::PARAM_STR);
$insert_post_toDBi->execute();
$cutr = $cutro-$pd;
$iptdbsql = "UPDATE cos_transactions SET cutr=:cutr WHERE post_id=:whhead";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':cutr', $cutr,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':whhead', $chak_idfsf,PDO::PARAM_STR);
$insert_post_toDB->execute();
}else{
  $iptdbsqli = "INSERT INTO cos_transactions
  (post_id,user_id,cos_id,type,invest_per,calcr,head,cutr)
  VALUES
  ( :post_ido, :p_user_id, :cosnam, :type, :prec, :calcr, :whhead, :cutr)
  ";
  $insert_post_toDBi = $conn->prepare($iptdbsqli);
  $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':cosnam', $main_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':whhead', $whhead,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':cutr', $un,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':prec', $prec,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':type', $ty_kin,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':calcr', $calcr,PDO::PARAM_STR);
  $insert_post_toDBi->execute();
}
//========================================================================
if($giv != ""){
  $iptdbsqli = "INSERT INTO transactions
(post_id,user_id,chak_id,exchange,received,given,received_name,given_name,kin,type,media,time,date,giv,giva,pero,exc,timex)
VALUES
( :post_id, :p_user_id,:post_ido, :usd, :un, :pd, :received_name, :given_name, :kin, :ty_kin, :media, :p_time, :date, :giv, :giva, :pero, :exc,:timec)
";
$insert_post_toDBi = $conn->prepare($iptdbsqli);
$insert_post_toDBi->bindParam(':post_id', $post_id,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':p_time', $p_time,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':giv', $giv,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':giva', $giva,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':timec', $timec,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':exc', $exc,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':pero', $pero,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':media', $media,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':un', $un,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':date', $date,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':received_name', $received_name,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':kin', $kin,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':pd', $pd,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':ty_kin', $ty_kin,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':usd', $usd,PDO::PARAM_STR);
$insert_post_toDBi->execute();
}elseif($howgi != ""){
  $iptdbsqli = "INSERT INTO transactions
(post_id,user_id,chak_id,exchange,received,given,received_name,given_name,kin,type,media,time,date,howgi,howgia,timex)
VALUES
( :post_id, :p_user_id,:post_ido, :usd, :un, :pd, :received_name, :given_name, :kin, :ty_kin, :media, :p_time, :date, :howgi, :howgia, :timec)
";
$insert_post_toDBi = $conn->prepare($iptdbsqli);
$insert_post_toDBi->bindParam(':post_id', $post_id,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':p_time', $p_time,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':howgi', $howgi,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':timec', $timec,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':howgia', $howgia,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':media', $media,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':un', $un,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':date', $date,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':received_name', $received_name,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':kin', $kin,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':pd', $pd,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':ty_kin', $ty_kin,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':usd', $usd,PDO::PARAM_STR);
$insert_post_toDBi->execute();
}elseif($prec_st != ""){
  $kino = lang('sell');
  $kinc = lang('buy');
  $iptdbsqli = "INSERT INTO transactions
  (post_id,user_id,chak_id,exchange,received,given,received_name,given_name,kin,type,media,time,date,timex)
  VALUES
  ( :post_id, :p_user_id,:post_ido, :usd, :un, :pd, :received_name, :given_name, :kin, :ty_kin, :media, :p_time, :date, :timec)
  ";
  $insert_post_toDBi = $conn->prepare($iptdbsqli);
  $insert_post_toDBi->bindParam(':post_id', $post_idv,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':p_time', $p_time,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':timec', $timec,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':media', $media,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':un', $amou_st,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':date', $date,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':received_name', $received_name_st,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':kin', $kino,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':given_name', $given_name_st,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':pd', $lyamou_st,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':ty_kin', $ty_kin,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':usd', $price_st,PDO::PARAM_STR);
  $insert_post_toDBi->execute();
  $iptdbsqli = "INSERT INTO transactions
  (post_id,user_id,chak_id,exchange,received,given,received_name,given_name,kin,type,media,time,date,timex)
  VALUES
  ( :post_id, :p_user_id,:post_ido, :usd, :un, :pd, :received_name, :given_name, :kin, :ty_kin, :media, :p_time, :date, :timec)
  ";
  $insert_post_toDBi = $conn->prepare($iptdbsqli);
  $insert_post_toDBi->bindParam(':post_id', $post_id,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':post_ido', $post_idt,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':p_time', $p_time,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':timec', $timec,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':media', $price_st,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':un', $amou_sd,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':date', $date,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':received_name', $received_name_sd,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':kin', $kinc,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':given_name', $given_name_sd,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':pd', $lyamou_sd,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':ty_kin', $ty_kin,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':usd', $price_sd,PDO::PARAM_STR);
  $insert_post_toDBi->execute();
}else{
    $iptdbsqli = "INSERT INTO transactions
(post_id,user_id,chak_id,exchange,received,given,received_name,given_name,kin,type,media,time,date, timex)
VALUES
( :post_id, :p_user_id,:post_ido, :usd, :un, :pd, :received_name, :given_name, :kin, :ty_kin, :media, :p_time, :date, :timec)
";
$insert_post_toDBi = $conn->prepare($iptdbsqli);
$insert_post_toDBi->bindParam(':post_id', $post_id,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':timec', $timec,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':p_time', $p_time,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':media', $media,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':un', $un,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':date', $date,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':received_name', $received_name,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':kin', $kin,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':pd', $pd,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':ty_kin', $ty_kin,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':usd', $usd,PDO::PARAM_STR);
$insert_post_toDBi->execute();
}
//===================insert the money of taken X================================
if($howgi){
$iptdbsql = "UPDATE invest_treasury SET number=:numbero WHERE user_id = :p_user_id AND kind=:received_name AND name=:name";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':numbero', $ddf,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $howgia,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':name', $cosnam, PDO::PARAM_INT);
$insert_post_toDB->execute();
}elseif($giv){
  $vpsql = "SELECT * FROM treasury WHERE user_id=:p_user_id AND kind=:given_name";
  $view_posts = $conn->prepare($vpsql);
  $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
  $view_posts->bindParam(':given_name', $giva, PDO::PARAM_INT);
  $view_posts->execute();
  while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
  $gjjbv = $postsfetch['number'];
  }
  $ghh = $gjjbv-$giv;
  $delete_comm_sqli = "UPDATE treasury SET number=:numbery WHERE user_id = :p_user_id AND kind=:received_name";
  $delete_commi = $conn->prepare($delete_comm_sqli);
  $delete_commi->bindParam(':numbery',$ghh,PDO::PARAM_INT);
  $delete_commi->bindParam(':received_name', $giva,PDO::PARAM_INT);
  $delete_commi->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
  $delete_commi->execute();
}elseif($prec_st != ""){
  $vpsql = "SELECT * FROM invest_treasury WHERE user_id=:p_user_id AND kind=:given_name AND name=:name";
  $view_posts = $conn->prepare($vpsql);
  $view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
  $view_posts->bindParam(':given_name', $received_name_st, PDO::PARAM_INT);
  $view_posts->bindParam(':name', $cosnam, PDO::PARAM_INT);
  $view_posts->execute();
  $numvz = $view_posts->rowCount();
  while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
  $gjjbvcv = $postsfetch['number'];
  }
  $ghcv = $gjjbvcv+$amou_st;
  $vpsql = "SELECT * FROM invest_treasury WHERE user_id=:p_user_id AND kind=:given_name AND name=:name";
  $view_posts = $conn->prepare($vpsql);
  $view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
  $view_posts->bindParam(':given_name', $given_name_st, PDO::PARAM_INT);
  $view_posts->bindParam(':name', $cosnam, PDO::PARAM_INT);
  $view_posts->execute();
  $numvw = $view_posts->rowCount();
  while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
  $gjjbvxv = $postsfetch['number'];
  }
  $ghxv = $gjjbvxv-$lyamou_st;

  /*    $iptdbsql = "UPDATE invest_treasury SET number=:numbero WHERE user_id = :p_user_id AND kind=:received_name AND name=:name";
  $insert_post_toDB = $conn->prepare($iptdbsql);
  $insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':numbero', $ghcv,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':received_name', $received_name_st,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':name', $cosnam, PDO::PARAM_INT);
  $insert_post_toDB->execute();

  //=====================insert the money of given==================================

      $iptdbsql = "UPDATE invest_treasury SET number=:numberb WHERE user_id = :p_user_id AND kind=:given_name AND name=:name";
  $insert_post_toDB = $conn->prepare($iptdbsql);
  $insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':numberb', $ghxv,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':given_name', $given_name_st,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':name', $cosnam, PDO::PARAM_INT);
  $insert_post_toDB->execute(); */

//==============================================================================================================================================
  $vpsql = "SELECT * FROM invest_treasury WHERE user_id=:p_user_id AND kind=:given_name AND name=:name";
  $view_posts = $conn->prepare($vpsql);
  $view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
  $view_posts->bindParam(':given_name', $received_name_sd, PDO::PARAM_INT);
  $view_posts->bindParam(':name', $cosnam, PDO::PARAM_INT);
  $view_posts->execute();
  $numvzt = $view_posts->rowCount();
  while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
  $gjjbvc = $postsfetch['number'];
  }
  $ghc = $gjjbvc+$amou_sd;
  $vpsql = "SELECT * FROM invest_treasury WHERE user_id=:p_user_id AND kind=:given_name AND name=:name";
  $view_posts = $conn->prepare($vpsql);
  $view_posts->bindParam(':p_user_id', $boss_id, PDO::PARAM_INT);
  $view_posts->bindParam(':given_name', $given_name_sd, PDO::PARAM_INT);
  $view_posts->bindParam(':name', $cosnam, PDO::PARAM_INT);
  $view_posts->execute();
  $numvwt = $view_posts->rowCount();
  while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
  $gjjbvx = $postsfetch['number'];
  }
  $ghx = $gjjbvx-$lyamou_sd;
  if(1 > $numvzt){
    $iptdbsqli = "INSERT INTO invest_treasury
  (user_id,number,kind,name)
  VALUES
  ( :p_user_id, :numbero,:received_name, :name)
  ";
  $insert_post_toDB = $conn->prepare($iptdbsqli);
  $insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':numbero', $amou_sd,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':received_name', $received_name_sd,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':name', $cosnam,PDO::PARAM_INT);
  $insert_post_toDB->execute();
  }else{
      $iptdbsql = "UPDATE invest_treasury SET number=:numbero WHERE user_id = :p_user_id AND kind=:received_name AND name=:name";
  $insert_post_toDB = $conn->prepare($iptdbsql);
  $insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':numbero', $ghc,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':received_name', $received_name_sd,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':name', $cosnam, PDO::PARAM_INT);
  $insert_post_toDB->execute();
  }
  if($kin == "buy" || $kin == "بيع"){
  //=====================insert the money of given==================================
  if(1 > $numvwt){
    $iptdbsqli = "INSERT INTO invest_treasury
  (user_id,number,kind,name)
  VALUES
  ( :p_user_id, :numbero,:received_name, :name)
  ";
  $insert_post_toDB = $conn->prepare($iptdbsqli);
  $insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':numbero', $lyamou_sd,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':received_name', $given_name_sd,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':name', $cosnam,PDO::PARAM_INT);
  $insert_post_toDB->execute();
  }else{
      $iptdbsql = "UPDATE invest_treasury SET number=:numberb WHERE user_id = :p_user_id AND kind=:given_name AND name=:name";
  $insert_post_toDB = $conn->prepare($iptdbsql);
  $insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':numberb', $ghx,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':given_name', $given_name_sd,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':name', $cosnam, PDO::PARAM_INT);
  $insert_post_toDB->execute();
  }
  }
}else{
if(1 > $numvf){
  $iptdbsqli = "INSERT INTO invest_treasury
(user_id,number,kind,name)
VALUES
( :p_user_id, :numbero,:received_name, :name)
";
$insert_post_toDB = $conn->prepare($iptdbsqli);
$insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':name', $cosnam,PDO::PARAM_INT);
$insert_post_toDB->execute();
}else{
    $iptdbsql = "UPDATE invest_treasury SET number=:numbero WHERE user_id = :p_user_id AND kind=:received_name AND name=:name";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':name', $cosnam, PDO::PARAM_INT);
$insert_post_toDB->execute();
}
if($kin == "buy" || $kin == "بيع"){
//=====================insert the money of given==================================
if(1 > $numvh){
  $iptdbsqli = "INSERT INTO invest_treasury
(user_id,number,kind,name)
VALUES
( :p_user_id, :numbero,:received_name, :name)
";
$insert_post_toDB = $conn->prepare($iptdbsqli);
$insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':numbero', $numberb,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $given_name,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':name', $cosnam,PDO::PARAM_INT);
$insert_post_toDB->execute();
}else{
    $iptdbsql = "UPDATE invest_treasury SET number=:numberb WHERE user_id = :p_user_id AND kind=:given_name AND name=:name";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':numberb', $numberb,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':name', $cosnam, PDO::PARAM_INT);
$insert_post_toDB->execute();
}
}
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
