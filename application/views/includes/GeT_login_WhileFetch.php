<?php
while($row_fetch = $query->fetch(PDO::FETCH_ASSOC)){
  //print_r($row_fetch);exit;
$row_id = $row_fetch['id'];
$row_fullname = $row_fetch['phone'];
$row_username = $row_fetch['Username'];
$row_email = $row_fetch['Email'];
$row_password = $row_fetch['Password'];
$row_user_photo = $row_fetch['Userphoto'];
$row_user_cover_photo = $row_fetch['user_cover_photo'];
$row_school = $row_fetch['school'];
$row_work = $row_fetch['work'];
$row_work0 = $row_fetch['work0'];
$row_country = $row_fetch['country'];
$row_birthday = $row_fetch['birthday'];
$row_verify = $row_fetch['verify'];
$hide_trsh = $row_fetch['hide_trsh'];
$row_website = $row_fetch['website'];
$row_bio = $row_fetch['bio'];
$row_admin = $row_fetch['admin'];
$row_gender = $row_fetch['gender'];
$row_profile_pic_border = $row_fetch['profile_pic_border'];
$row_language = $row_fetch['language'];
$row_online = $row_fetch['online'];
$name_var = $row_fetch['name'];
$phone_no = $row_fetch['phone_no'];
$email_ad = $row_fetch['email_ad'];
$website = $row_fetch['website'];
$package_chose = $row_fetch['package_chose'];
$type = $row_fetch['type'];
$boss_id = $row_fetch['boss_id'];
$shop_id = $row_fetch['shop_id'];
$address_var = $row_fetch['address'];
$nex_pay = $row_fetch['nex_pay'];
$accou_stu = $row_fetch['accou_stu'];
$cash = $row_fetch['cash'];
$chak = $row_fetch['chak'];
$cards = $row_fetch['cards'];
$transfar = $row_fetch['transfar'];
$invest = $row_fetch['invest'];
$trash = $row_fetch['trash'];
$Treasury = $row_fetch['Treasury'];
$buythings = $row_fetch['buythings'];
$Profit = $row_fetch['Profit'];
$bank = $row_fetch['bank'];
$trsh = $row_fetch['trsh'];
$mode = $row_fetch['mode'];
$title = $row_fetch['title'];
$steps = $row_fetch['steps'];
$user_email_status = $row_fetch['user_email_status'];
}
$vpsql = "SELECT package,Username FROM signup WHERE id=:sid";
$view_postsi = $conn->prepare($vpsql);
$view_postsi->bindParam(':sid', $boss_id, PDO::PARAM_INT);
$view_postsi->execute();
while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
  $package = $postsfetch['package'];
}
$typef = "in";
if($req == "login_code"){
      $iptdbsqli = "INSERT INTO inandout
      (user_id,type)
      VALUES
      ( :p_user_id, :type)
      ";
      $insert_post_toDBi = $conn->prepare($iptdbsqli);
      $insert_post_toDBi->bindParam(':type', $typef,PDO::PARAM_STR);
      $insert_post_toDBi->bindParam(':p_user_id', $row_id,PDO::PARAM_INT);
      $insert_post_toDBi->execute();
}
$_SESSION['id'] = $row_id;
$_SESSION['phone'] = $row_fullname;
$_SESSION['Username'] = $row_username;
$_SESSION['Email'] = $row_email;
$_SESSION['Password'] = $row_password;
$_SESSION['shop_id'] = $shop_id;
$_SESSION['type'] = $type;
$_SESSION['boss_id'] = $boss_id;
$_SESSION['Userphoto'] = $row_user_photo;
$_SESSION['uCoverPhoto'] = $row_user_cover_photo;
$_SESSION['school'] = $row_school;
$_SESSION['nex_pay'] = $nex_pay;
$_SESSION['accou_stu'] = $accou_stu;
$_SESSION['package_chose'] = $package_chose;
$_SESSION['work'] = $row_work;
$_SESSION['work0'] = $row_work0;
$_SESSION['steps'] = $steps;
$_SESSION['country'] = $row_country;
$_SESSION['birthday'] = $row_birthday;
$_SESSION['verify'] = $row_verify;
$_SESSION['website'] = $row_website;
$_SESSION['bio'] = $row_bio;
$_SESSION['hide_trsh'] = $hide_trsh;
$_SESSION['name'] = $name_var;
$_SESSION['phone_no'] = $phone_no;
$_SESSION['email_ad'] = $email_ad;
$_SESSION['website'] = $website;
$_SESSION['address'] = $address_var;
$_SESSION['admin'] = $row_admin;
$_SESSION['cash'] = $cash;
$_SESSION['chak'] = $chak;
$_SESSION['cards'] = $cards;
$_SESSION['transfar'] = $transfar;
$_SESSION['invest'] = $invest;
$_SESSION['trash'] = $trash;
$_SESSION['Treasury'] = $Treasury;
$_SESSION['buythings'] = $buythings;
$_SESSION['Profit'] = $Profit;
$_SESSION['bank'] = $bank;
$_SESSION['trsh'] = $trsh;
$_SESSION['mode'] = $mode;
$_SESSION['package'] = $package;
$_SESSION['title_h'] = $title;
$_SESSION['user_email_status'] = $user_email_status;
if ($row_gender == "0" or $row_gender == "Male") {
$_SESSION['gender'] = "Male";
}elseif ($row_gender == "1" or $row_gender == "Female") {
$_SESSION['gender'] = "Female";
}
$_SESSION['profile_pic_border'] = $row_profile_pic_border;
$_SESSION['language'] = $row_language;
$_SESSION['online'] = $row_online;
?>
