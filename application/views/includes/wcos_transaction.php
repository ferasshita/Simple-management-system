<?php
session_start();
include "../config/connect.php";
$post_id = rand(0,9999999)+time();
$post_ido = rand(0,9999999)+time();
$p_user_id = $_SESSION['id'];
$p_author = $_SESSION['Fullname'];
$p_author_photo = $_SESSION['Userphoto'];
$boss_id =  $_SESSION['boss_id'];
$shop_id =  $_SESSION['shop_id'];
$timec = time();
//==================from the form==============================
$un = filter_var(htmlspecialchars($_POST['amou']),FILTER_SANITIZE_STRING);
$p_time = filter_var(htmlspecialchars($_POST['time']),FILTER_SANITIZE_STRING);
$pd = filter_var(htmlspecialchars($_POST['lyamou']),FILTER_SANITIZE_STRING);
$usd = filter_var(htmlspecialchars($_POST['price']),FILTER_SANITIZE_STRING);
$date = filter_var(htmlspecialchars($_POST['date']),FILTER_SANITIZE_STRING);
$received_name = filter_var(htmlspecialchars($_POST['received_name']),FILTER_SANITIZE_STRING);
$given_name = filter_var(htmlspecialchars($_POST['given_name']),FILTER_SANITIZE_STRING);
$cosnam = filter_var(htmlspecialchars($_POST['cosnam']),FILTER_SANITIZE_STRING);
$bankacco = filter_var(htmlspecialchars($_POST['bankacco']),FILTER_SANITIZE_STRING);
$banknam = filter_var(htmlspecialchars($_POST['banknam']),FILTER_SANITIZE_STRING);
$address = filter_var(htmlspecialchars($_POST['address']),FILTER_SANITIZE_STRING);
$phone = filter_var(htmlspecialchars($_POST['phno']),FILTER_SANITIZE_STRING);
$email = filter_var(htmlspecialchars($_POST['email']),FILTER_SANITIZE_STRING);
$uprce = filter_var(htmlspecialchars($_POST['uprce']),FILTER_SANITIZE_STRING);
$kighy = filter_var(htmlspecialchars($_POST['kighy']),FILTER_SANITIZE_STRING);
$bprce = filter_var(htmlspecialchars($_POST['bprce']),FILTER_SANITIZE_STRING);
$resnam = filter_var(htmlspecialchars($_POST['resnam']),FILTER_SANITIZE_STRING);
$resbankacco = filter_var(htmlspecialchars($_POST['resbankacco']),FILTER_SANITIZE_STRING);
$resbanknam = filter_var(htmlspecialchars($_POST['resbanknam']),FILTER_SANITIZE_STRING);
$resaddress = filter_var(htmlspecialchars($_POST['resaddress']),FILTER_SANITIZE_STRING);
$resphone = filter_var(htmlspecialchars($_POST['resphno']),FILTER_SANITIZE_STRING);
$resemail = filter_var(htmlspecialchars($_POST['resemail']),FILTER_SANITIZE_STRING);
$idjd = filter_var(htmlspecialchars($_POST['idjd']),FILTER_SANITIZE_STRING);
$idvb = filter_var(htmlspecialchars($_POST['idvb']),FILTER_SANITIZE_STRING);
$rescountry = filter_var(htmlspecialchars($_POST['rescountry']),FILTER_SANITIZE_STRING);
$bank = "bank";
$cash = "cash";
if($cosnam == ""){
$name = "casher";
}else{
$name = $cosnam;
}
$fas = "0";
if($given_name == "LYD"){
$kin = lang('sell');
}else{
  $kin = lang('buy');
}
if($kighy == "chak"){
$vpsql = "SELECT * FROM capital WHERE shop_id=:sid AND kind=:received_name AND tyi=:cash";
$view_postsi = $conn->prepare($vpsql);
$view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
$view_postsi->bindParam(':received_name', $received_name, PDO::PARAM_INT);
$view_postsi->bindParam(':cash', $cash, PDO::PARAM_INT);
$view_postsi->execute();
$num = $view_postsi->rowCount();
while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
  $numberhea = $postsfetch['number'];
  $exchangehea = $postsfetch['exchange'];
  $tyhea = $postsfetch['kind'];
  $ty_gt = $postsfetch['type'];
}
$vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE shop_id=:sid AND kind=:received_name AND tyi=:cash";
$view_postsi = $conn->prepare($vpsql);
$view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
$view_postsi->bindParam(':received_name', $given_name, PDO::PARAM_INT);
$view_postsi->bindParam(':cash', $cash, PDO::PARAM_INT);
$view_postsi->execute();
while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
  $ty_uy = $postsfetch['ty_uy'];
}
$vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE shop_id=:sid AND kind=:received_name AND tyi=:cash";
$view_postsi = $conn->prepare($vpsql);
$view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
$view_postsi->bindParam(':received_name', $given_name, PDO::PARAM_INT);
$view_postsi->bindParam(':cash', $cash, PDO::PARAM_INT);
$view_postsi->execute();
while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
  $ty_ji = $postsfetch['ty_ji'];
}
$medid= $ty_uy/$ty_ji;
$media = number_format("$medid",2, ".", "");
}else{
  $vpsql = "SELECT * FROM capital WHERE shop_id=:sid AND kind=:received_name AND tyi=:cash AND wh=:id";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
  $view_postsi->bindParam(':received_name', $received_name, PDO::PARAM_INT);
  $view_postsi->bindParam(':cash', $bank, PDO::PARAM_INT);
  $view_postsi->bindParam(':id', $idjd, PDO::PARAM_INT);
  $view_postsi->execute();
  $num = $view_postsi->rowCount();
  while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
    $numberhea = $postsfetch['number'];
    $exchangehea = $postsfetch['exchange'];
    $tyhea = $postsfetch['kind'];
    $ty_gt = $postsfetch['type'];
  }
  $vpsql = "SELECT SUM(calc) AS ty_uy FROM capital WHERE shop_id=:sid AND kind=:received_name AND tyi=:cash AND wh=:id";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
  $view_postsi->bindParam(':received_name', $given_name, PDO::PARAM_INT);
  $view_postsi->bindParam(':cash', $bank, PDO::PARAM_INT);
  $view_postsi->bindParam(':id', $idjd, PDO::PARAM_INT);
  $view_postsi->execute();
  while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
    $ty_uy = $postsfetch['ty_uy'];
  }
  $vpsql = "SELECT SUM(number) AS ty_ji FROM capital WHERE shop_id=:sid AND kind=:received_name AND tyi=:cash AND wh=:id";
  $view_postsi = $conn->prepare($vpsql);
  $view_postsi->bindParam(':sid', $shop_id, PDO::PARAM_INT);
  $view_postsi->bindParam(':received_name', $given_name, PDO::PARAM_INT);
  $view_postsi->bindParam(':cash', $bank, PDO::PARAM_INT);
  $view_postsi->bindParam(':id', $idjd, PDO::PARAM_INT);
  $view_postsi->execute();
  while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
    $ty_ji = $postsfetch['ty_ji'];
  }
$medid= $ty_uy/$ty_ji;
$media = number_format("$medid",2, ".", "");
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
  ( :main_id, :boss_id, :shop_id, :user_id, :name, :email, :phno, :address)
  ";
  $insert_post_toDBi = $conn->prepare($iptdbsqli);
  $insert_post_toDBi->bindParam(':main_id', $main_id,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':user_id', $p_user_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':name', $name,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':address', $address,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':phno', $phno,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':email', $email,PDO::PARAM_STR);
  $insert_post_toDBi->execute();
}else{
if($address != ""){
  $iptdbsql = "UPDATE costumers SET address=:address WHERE shop_id=:sid AND name=:name";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':address', $address,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':name', $name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':sid', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->execute();
}if($phno != ""){
  $iptdbsql = "UPDATE costumers SET phone=:phno WHERE shop_id=:sid AND name=:name";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':phno', $phno,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':name', $name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':sid', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->execute();
}if($email != ""){
  $iptdbsql = "UPDATE costumers SET email=:email WHERE shop_id=:sid AND name=:name";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':email', $email,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':name', $name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':sid', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->execute();
}
}
if($kighy == "chak"){
  if($kin == lang('sell')){
    //======================check the cad====================
//======================taken cash usd===========
    $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:received_name AND tyi=:cash";
    $view_posts = $conn->prepare($vpsql);
    $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
    $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
    $view_posts->bindParam(':cash', $cash, PDO::PARAM_INT);
    $view_posts->execute();
    $numvf = $view_posts->rowCount();
    while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
    $numberya = $postsfetch['number'];
    }
//==================given lyd bank==================
    $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:given_name AND wh=:idjd AND tyi=:bank";
    $view_posts = $conn->prepare($vpsql);
    $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
    $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
    $view_posts->bindParam(':bank', $bank, PDO::PARAM_INT);
    $view_posts->bindParam(':idjd', $idjd, PDO::PARAM_INT);
    $view_posts->execute();
    $numvh = $view_posts->rowCount();
    while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
    $numberyb = $postsfetch['number'];
    }
    //a taken
      $numbero = $numberya+$un;
    //b given
      $numberb = $numberyb-$pd;
      if($numberyb >= $pd){
        unset($_SESSION['myerrorch']);
      }else{
        $_SESSION['myerrorch'] = number_format("$numberyb",2, ".", "")." $given_name :".lang('youhave');
        return false;
      }
    //a
        if($numvf < 1){
          $iptdbsql = "INSERT INTO treasury
    (user_id,shop_id,boss_id,kind,number,wh,tyi)
    VALUES
    ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
    ";
    $insert_post_toDB = $conn->prepare($iptdbsql);
    $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':un', $numbero,PDO::PARAM_STR);
    $insert_post_toDB->bindParam(':id', $fas, PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
    $insert_post_toDB->bindParam(':bgh', $cash, PDO::PARAM_STR);
    $insert_post_toDB->execute();
        }else{
          $iptdbsql = "UPDATE treasury SET number=:numbero WHERE shop_id = :p_user_id AND kind=:received_name AND tyi=:cash";
      $insert_post_toDB = $conn->prepare($iptdbsql);
      $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':cash', $cash, PDO::PARAM_INT);
      $insert_post_toDB->execute();
}
    // b
    if($numvh < 1){
      $iptdbsql = "INSERT INTO treasury
(user_id,shop_id,boss_id,kind,number,wh,tyi)
VALUES
( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':un', $numberb,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':id', $idjd, PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $given_name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':bgh', $bank, PDO::PARAM_STR);
$insert_post_toDB->execute();
    }else{
          $iptdbsql = "UPDATE treasury SET number=:numberb WHERE shop_id = :p_user_id AND kind=:given_name AND wh=:idjd AND tyi=:bank";
      $insert_post_toDB = $conn->prepare($iptdbsql);
      $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':numberb', $numberb,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':bank', $bank, PDO::PARAM_INT);
      $insert_post_toDB->execute();
}
  }elseif($kin == lang('buy')){
    //======================check the cad====================
    //======================taken bank===========
        $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:received_name AND wh=:idjd AND tyi=:bank";
        $view_posts = $conn->prepare($vpsql);
        $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
        $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
        $view_posts->bindParam(':bank', $bank, PDO::PARAM_INT);
        $view_posts->bindParam(':idjd', $idjd, PDO::PARAM_INT);
        $view_posts->execute();
        $numvf = $view_posts->rowCount();
        while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
        $numberya = $postsfetch['number'];
        }
    //==================given cash==================
        $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:given_name AND tyi=:cash";
        $view_posts = $conn->prepare($vpsql);
        $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
        $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
        $view_posts->bindParam(':cash', $cash, PDO::PARAM_INT);
        $view_posts->execute();
        $numvh = $view_posts->rowCount();
        while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
        $numberyb = $postsfetch['number'];
        }
        //a taken
          $numbero = $numberya+$un;
        //b given
          $numberb = $numberyb-$pd;
          if($numberyb >= $pd){
            unset($_SESSION['myerrorch']);
          }else{
            $_SESSION['myerrorch'] = number_format("$numberyb",2, ".", "")."  $given_name :".lang('youhave');
            return false;
          }
        //a
        if($numvf < 1){
          $iptdbsql = "INSERT INTO treasury
    (user_id,shop_id,boss_id,kind,number,wh,tyi)
    VALUES
    ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
    ";
    $insert_post_toDB = $conn->prepare($iptdbsql);
    $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':un', $numbero,PDO::PARAM_STR);
    $insert_post_toDB->bindParam(':id', $idjd, PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
    $insert_post_toDB->bindParam(':bgh', $bank, PDO::PARAM_STR);
    $insert_post_toDB->execute();
        }else{
              $iptdbsql = "UPDATE treasury SET number=:numbero WHERE shop_id = :p_user_id AND kind=:received_name AND wh=:idjd AND tyi=:bank";
          $insert_post_toDB = $conn->prepare($iptdbsql);
          $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':bank', $bank, PDO::PARAM_INT);
          $insert_post_toDB->execute();
}
        // b
        if($numvh < 1){
          $iptdbsql = "INSERT INTO treasury
    (user_id,shop_id,boss_id,kind,number,wh,tyi)
    VALUES
    ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
    ";
    $insert_post_toDB = $conn->prepare($iptdbsql);
    $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':un', $numberb,PDO::PARAM_STR);
    $insert_post_toDB->bindParam(':id', $fas, PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':received_name', $given_name,PDO::PARAM_STR);
    $insert_post_toDB->bindParam(':bgh', $cash, PDO::PARAM_STR);
    $insert_post_toDB->execute();
        }else{
              $iptdbsql = "UPDATE treasury SET number=:numberb WHERE shop_id = :p_user_id AND kind=:given_name AND tyi=:cash";
          $insert_post_toDB = $conn->prepare($iptdbsql);
          $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':numberb', $numberb,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':cash', $cash, PDO::PARAM_INT);
          $insert_post_toDB->execute();
}
  }

  $ty_kin = "chak";
//=====================insert into cash=====================
$iptdbsqli = "INSERT INTO cos_transactions
(post_id,user_id,cos_id,bankacc,bankname,uprcen,type,idjd)
VALUES
( :post_ido, :p_user_id, :cosnam, :bankacco, :banknam, :uprce, :type, :idjd)
";
$insert_post_toDBi = $conn->prepare($iptdbsqli);
$insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':cosnam', $main_id,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':idjd', $idjd,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':bankacco', $bankacco,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':banknam', $banknam,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':uprce', $uprce,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':type', $ty_kin,PDO::PARAM_STR);
$insert_post_toDBi->execute();
}elseif($kighy == "cards"){
  if($kin == lang('sell')){
    //======================check the cad====================
//======================taken bank===========
    $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:received_name AND wh=:idjd AND tyi=:bank";
    $view_posts = $conn->prepare($vpsql);
    $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
    $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_STR);
    $view_posts->bindParam(':idjd', $idjd, PDO::PARAM_INT);
    $view_posts->bindParam(':bank', $bank, PDO::PARAM_STR);
    $view_posts->execute();
    $numvf = $view_posts->rowCount();
    while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
    $numberya = $postsfetch['number'];
    }
//==================given cash==================
    $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:given_name AND tyi=:cash";
    $view_posts = $conn->prepare($vpsql);
    $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
    $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_STR);
    $view_posts->bindParam(':cash', $cash, PDO::PARAM_STR);
    $view_posts->execute();
    $numvh = $view_posts->rowCount();
    while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
    $numberyb = $postsfetch['number'];
    }
    //a taken
      $numbero = $numberya+$un;
    //b given
      $numberb = $numberyb-$pd;
      if($numberyb >= $pd){
                 unset($_SESSION['myerrorca']);
               }else{
                 $_SESSION['myerrorca'] = number_format("$numberyb",2, ".", "")." $given_name :".lang('youhave');
                 return false;
               }
    //a
    if($numvf < 1){
      $iptdbsql = "INSERT INTO treasury
(user_id,shop_id,boss_id,kind,number,wh,tyi)
VALUES
( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':un', $numbero,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':id', $idjd, PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':bgh', $bank, PDO::PARAM_STR);
$insert_post_toDB->execute();
    }else{
          $iptdbsql = "UPDATE treasury SET number=:numbero WHERE shop_id = :p_user_id AND kind=:received_name AND wh=:idjd AND tyi=:bank";
      $insert_post_toDB = $conn->prepare($iptdbsql);
      $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':bank', $bank, PDO::PARAM_INT);
      $insert_post_toDB->execute();
}
if($numvh < 1){
  $iptdbsql = "INSERT INTO treasury
(user_id,shop_id,boss_id,kind,number,wh,tyi)
VALUES
( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':un', $numberb,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':id', $fas, PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $given_name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':bgh', $cash, PDO::PARAM_STR);
$insert_post_toDB->execute();
}else{
    // b
          $iptdbsql = "UPDATE treasury SET number=:numberb WHERE shop_id = :p_user_id AND kind=:given_name AND tyi=:cash";
      $insert_post_toDB = $conn->prepare($iptdbsql);
      $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':numberb', $numberb,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':cash', $cash, PDO::PARAM_INT);
      $insert_post_toDB->execute();
}
  }elseif($kin == lang('buy')){
    //======================check the cad====================
    //======================a taken bank===========
        $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:received_name AND wh=:idjd AND tyi=:bank";
        $view_posts = $conn->prepare($vpsql);
        $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
        $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
        $view_posts->bindParam(':idjd', $idjd, PDO::PARAM_INT);
        $view_posts->bindParam(':bank', $bank, PDO::PARAM_INT);
        $view_posts->execute();
        $numvf = $view_posts->rowCount();
        while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
        $numberya = $postsfetch['number'];
        }
    //==================b given cash==================
        $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:given_name AND tyi=:cash";
        $view_posts = $conn->prepare($vpsql);
        $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
        $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
        $view_posts->bindParam(':cash', $cash, PDO::PARAM_INT);
        $view_posts->execute();
        $numvh = $view_posts->rowCount();
        while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
        $numberyb = $postsfetch['number'];
        }
        //a taken
          $numbero = $numberya+$un;
        //b given
          $numberb = $numberyb-$pd;
          if($numberyb >= $pd){
                     unset($_SESSION['myerrorca']);
                   }else{
                     $_SESSION['myerrorca'] = number_format("$numberyb",2, ".", "")." $given_name :".lang('youhave');
                     return false;
                   }
        //a
        if($numvf < 1){
          $iptdbsql = "INSERT INTO treasury
    (user_id,shop_id,boss_id,kind,number,wh,tyi)
    VALUES
    ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
    ";
    $insert_post_toDB = $conn->prepare($iptdbsql);
    $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':un', $numbero,PDO::PARAM_STR);
    $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
    $insert_post_toDB->bindParam(':id', $idjd, PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':bgh', $bank, PDO::PARAM_STR);
    $insert_post_toDB->execute();
        }else{
              $iptdbsql = "UPDATE treasury SET number=:numbero WHERE user_id = :p_user_id AND kind=:received_name AND wh=:idjd AND tyi=:bank";
          $insert_post_toDB = $conn->prepare($iptdbsql);
          $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':bank', $bank, PDO::PARAM_INT);
          $insert_post_toDB->execute();
}
if($numvh < 1){
  $iptdbsql = "INSERT INTO treasury
(user_id,shop_id,boss_id,kind,number,wh,tyi)
VALUES
( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':un', $numberb,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':id', $fas, PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $given_name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':bgh', $cash, PDO::PARAM_STR);
$insert_post_toDB->execute();
}else{
        // b
              $iptdbsql = "UPDATE treasury SET number=:numberb WHERE shop_id = :p_user_id AND kind=:given_name AND tyi=:cash";
          $insert_post_toDB = $conn->prepare($iptdbsql);
          $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':numberb', $numberb,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':cash', $cash, PDO::PARAM_INT);
          $insert_post_toDB->execute();
}
  }

  //===============end of the checking=================================
  $ty_kin = "cards";
  $iptdbsqli = "INSERT INTO cos_transactions
  (post_id,user_id,cos_id,bankname,uprcen,bankprcen,type,idjd)
  VALUES
  ( :post_ido, :p_user_id, :cosnam, :banknam, :uprce, :bprce, :type, :idjd)
  ";
  $insert_post_toDBi = $conn->prepare($iptdbsqli);
  $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':cosnam', $main_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':idjd', $idjd,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':banknam', $banknam,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':uprce', $uprce,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':bprce', $bprce,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':type', $ty_kin,PDO::PARAM_STR);
  $insert_post_toDBi->execute();
}elseif($kighy == "transfar"){
  if($kin == lang('sell')){
    //======================check the cad====================
//======================taken bank===========
    $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:received_name AND wh=:idjd AND tyi=:bank";
    $view_posts = $conn->prepare($vpsql);
    $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
    $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
    $view_posts->bindParam(':idjd', $idjd, PDO::PARAM_INT);
    $view_posts->bindParam(':bank', $bank, PDO::PARAM_INT);
    $view_posts->execute();
    $numvf = $view_posts->rowCount();
    while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
    $numberya = $postsfetch['number'];
    }
if($idvb == ""){
//==================given cash==================
    $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:given_name AND tyi=:cash";
    $view_posts = $conn->prepare($vpsql);
    $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
    $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
    $view_posts->bindParam(':cash', $cash, PDO::PARAM_INT);
    $view_posts->execute();
    $numvh = $view_posts->rowCount();
    while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
    $numberyb = $postsfetch['number'];
    }
}else{
  $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:given_name AND wh=:idjd AND tyi=:bank";
  $view_posts = $conn->prepare($vpsql);
  $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
  $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
  $view_posts->bindParam(':bank', $bank, PDO::PARAM_INT);
  $view_posts->bindParam(':idjd', $idjd, PDO::PARAM_INT);
  $view_posts->execute();
  $numvf = $view_posts->rowCount();
  while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
  $numberya = $postsfetch['number'];
  }
}
    //a taken
      $numbero = $numberya+$un;
    //b given
      $numberb = $numberyb-$pd;
      if($numberyb >= $pd){
                 unset($_SESSION['myerrortr']);
               }else{
                 $_SESSION['myerrortr'] = number_format("$numberyb",2, ".", "")." $given_name :".lang('youhave');
                 return false;
               }
    //a
    if($numvf < 1){
      $iptdbsql = "INSERT INTO treasury
(user_id,shop_id,boss_id,kind,number,wh,tyi)
VALUES
( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':un', $numbero,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':id', $idjd, PDO::PARAM_INT);
$insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':bgh', $bank, PDO::PARAM_STR);
$insert_post_toDB->execute();
    }else{
          $iptdbsql = "UPDATE treasury SET number=:numbero WHERE shop_id = :p_user_id AND kind=:received_name AND wh=:idjd AND tyi=:bank";
      $insert_post_toDB = $conn->prepare($iptdbsql);
      $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':bank', $bank, PDO::PARAM_INT);
      $insert_post_toDB->execute();
}
if($idvb == ""){
if($numvf < 1){
  $iptdbsql = "INSERT INTO treasury
(user_id,shop_id,boss_id,kind,number,wh,tyi)
VALUES
( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':un', $numberb,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':id', $fas, PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $given_name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':bgh', $cash, PDO::PARAM_STR);
$insert_post_toDB->execute();
}else{
    // b
          $iptdbsql = "UPDATE treasury SET number=:numberb WHERE shop_id = :p_user_id AND kind=:given_name AND tyi=:cash";
      $insert_post_toDB = $conn->prepare($iptdbsql);
      $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':numberb', $numberb,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
      $insert_post_toDB->bindParam(':cash', $cash, PDO::PARAM_INT);
      $insert_post_toDB->execute();
}
}else{
  if($numvf < 1){
    $iptdbsql = "INSERT INTO treasury
(user_id,shop_id,boss_id,kind,number,wh,tyi)
VALUES
( :p_user_id, :shop_id, :boss_id, :given_name, :un , :id, :bgh)
";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':un', $numbero,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':id', $idjd, PDO::PARAM_INT);
$insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':bgh', $bank, PDO::PARAM_STR);
$insert_post_toDB->execute();
  }else{
        $iptdbsql = "UPDATE treasury SET number=:numbero WHERE shop_id = :p_user_id AND kind=:given_name AND wh=:idjd AND tyi=:bank";
    $insert_post_toDB = $conn->prepare($iptdbsql);
    $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':bank', $bank, PDO::PARAM_INT);
    $insert_post_toDB->execute();
}
}
  }elseif($kin == lang('buy')){
    //======================check the cad====================
    //======================taken cash===========
    if($idvb == ""){
        $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:received_name AND tyi=:cash";
        $view_posts = $conn->prepare($vpsql);
        $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
        $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
        $view_posts->bindParam(':cash', $cash, PDO::PARAM_INT);
        $view_posts->execute();
        $numvf = $view_posts->rowCount();
        while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
        $numberya = $postsfetch['number'];
        }
}else{
  $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:received_name AND tyi=:bank AND wh=:idjd";
  $view_posts = $conn->prepare($vpsql);
  $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
  $view_posts->bindParam(':received_name', $received_name, PDO::PARAM_INT);
  $view_posts->bindParam(':bank', $bank, PDO::PARAM_INT);
  $view_posts->bindParam(':idjd', $idjd, PDO::PARAM_INT);
  $view_posts->execute();
  $numvh = $view_posts->rowCount();
  while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
  $numberyb = $postsfetch['number'];
  }
}
    //==================given bank==================
        $vpsql = "SELECT * FROM treasury WHERE shop_id=:p_user_id AND kind=:given_name AND tyi=:bank AND wh=:idjd";
        $view_posts = $conn->prepare($vpsql);
        $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
        $view_posts->bindParam(':given_name', $given_name, PDO::PARAM_INT);
        $view_posts->bindParam(':bank', $bank, PDO::PARAM_INT);
        $view_posts->bindParam(':idjd', $idjd, PDO::PARAM_INT);
        $view_posts->execute();
        $numvh = $view_posts->rowCount();
        while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
        $numberyb = $postsfetch['number'];
        }
        //a taken
          $numbero = $numberya+$un;
        //b given
          $numberb = $numberyb-$pd;
          if($numberyb >= $pd){
                     unset($_SESSION['myerrortr']);
                   }else{
                     $_SESSION['myerrortr'] = number_format("$numberyb",2, ".", "")." $given_name :".lang('youhave');
                     return false;
                   }
        //a
        if($idvb == ""){
        if($numvf < 1){
          $iptdbsql = "INSERT INTO treasury
    (user_id,shop_id,boss_id,kind,number,wh,tyi)
    VALUES
    ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
    ";
    $insert_post_toDB = $conn->prepare($iptdbsql);
    $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':un', $numbero,PDO::PARAM_STR);
    $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':id', $fas, PDO::PARAM_INT);
    $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
    $insert_post_toDB->bindParam(':bgh', $cash, PDO::PARAM_STR);
    $insert_post_toDB->execute();
        }else{
              $iptdbsql = "UPDATE treasury SET number=:numbero WHERE shop_id = :p_user_id AND kind=:received_name AND tyi=:cash";
          $insert_post_toDB = $conn->prepare($iptdbsql);
          $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':numbero', $numbero,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':cash', $cash, PDO::PARAM_INT);
          $insert_post_toDB->execute();
}
}else{
  if($numvf < 1){
    $iptdbsql = "INSERT INTO treasury
  (user_id,shop_id,boss_id,kind,number,wh,tyi)
  VALUES
  ( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
  ";
  $insert_post_toDB = $conn->prepare($iptdbsql);
  $insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':un', $numbero,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':id', $idjd, PDO::PARAM_INT);
  $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
  $insert_post_toDB->bindParam(':bgh', $bank, PDO::PARAM_STR);
  $insert_post_toDB->execute();
          }else{
          // b
                $iptdbsql = "UPDATE treasury SET number=:numberb WHERE shop_id = :p_user_id AND kind=:received_name AND wh=:idjd AND tyi=:bank";
            $insert_post_toDB = $conn->prepare($iptdbsql);
            $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
            $insert_post_toDB->bindParam(':numberb', $numbero,PDO::PARAM_INT);
            $insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
            $insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
            $insert_post_toDB->bindParam(':bank', $bank, PDO::PARAM_INT);
            $insert_post_toDB->execute();
  }
}
//================================== X ============================
if($numvh < 1){
  $iptdbsql = "INSERT INTO treasury
(user_id,shop_id,boss_id,kind,number,wh,tyi)
VALUES
( :p_user_id, :shop_id, :boss_id, :received_name, :un , :id, :bgh)
";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':un', $numberb,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':id', $idjd, PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $given_name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':bgh', $bank, PDO::PARAM_STR);
$insert_post_toDB->execute();
        }else{
        // b
              $iptdbsql = "UPDATE treasury SET number=:numberb WHERE shop_id = :p_user_id AND kind=:given_name AND wh=:idjd AND tyi=:bank";
          $insert_post_toDB = $conn->prepare($iptdbsql);
          $insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':numberb', $numberb,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':given_name', $given_name,PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':idjd', $idjd, PDO::PARAM_INT);
          $insert_post_toDB->bindParam(':bank', $bank, PDO::PARAM_INT);
          $insert_post_toDB->execute();
}
  }
  $ty_kin = "transfar";
  $iptdbsqli = "INSERT INTO cos_transactions
  (post_id,user_id,cos_id,bankacc,bankname,uprcen,bankprcen,type,resnam,resbankacco,resbanknam,resaddress,resphone,resemail,rescountry,idjd)
  VALUES
  ( :post_ido, :p_user_id, :cosnam, :bankacco, :banknam, :uprce, :bprce, :type, :resnam, :resbankacco, :resbanknam, :resaddress, :resphone, :resemail, :rescountry,:idjd)
  ";
  $insert_post_toDBi = $conn->prepare($iptdbsqli);
  $insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':cosnam', $main_id,PDO::PARAM_INT);
  $insert_post_toDBi->bindParam(':bankacco', $bankacco,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':banknam', $banknam,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':uprce', $uprce,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':idjd', $idjd,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':bprce', $bprce,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':type', $ty_kin,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':resnam', $resnam,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':resbankacco', $resbankacco,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':resbanknam', $resbanknam,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':resaddress', $resaddress,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':resphone', $resphone,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':resemail', $resemail,PDO::PARAM_STR);
  $insert_post_toDBi->bindParam(':rescountry', $rescountry,PDO::PARAM_STR);
  $insert_post_toDBi->execute();
}
//========================================================================
    $iptdbsqli = "INSERT INTO transactions
(post_id,user_id,chak_id,exchange,received,given,received_name,given_name,kin,type,media,time,date,timex)
VALUES
( :post_id, :p_user_id,:post_ido, :usd, :un, :pd, :received_name, :given_name, :kin, :ty_kin, :media, :p_time, :date,:timec)
";
$insert_post_toDBi = $conn->prepare($iptdbsqli);
$insert_post_toDBi->bindParam(':post_id', $post_id,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':p_user_id', $p_user_id,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':post_ido', $post_ido,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':p_time', $p_time,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':media', $media,PDO::PARAM_INT);
$insert_post_toDBi->bindParam(':un', $un,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':timec', $timec,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':date', $date,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':received_name', $received_name,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':kin', $kin,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':pd', $pd,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':ty_kin', $ty_kin,PDO::PARAM_STR);
$insert_post_toDBi->bindParam(':usd', $usd,PDO::PARAM_STR);
$insert_post_toDBi->execute();
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
