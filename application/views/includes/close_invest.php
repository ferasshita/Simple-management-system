<?php
include("../config/connect.php");
session_start();
$sid = $_SESSION['id'];
//==================function (close invest)========================
$c_id = htmlentities($_POST['cid'], ENT_QUOTES);
$p_id = htmlentities($_POST['pid'], ENT_QUOTES);
$delo = "1";
$inves = "أستثمار";
//===================select information from transactions========================
$checki = $conn->prepare("SELECT * FROM transactions WHERE post_id =:c_id");
$checki->bindParam(':c_id',$c_id,PDO::PARAM_INT);
$checki->execute();
while ($chR = $checki->fetch(PDO::FETCH_ASSOC)) {
	$chR_aid = $chR['user_id'];
	$received = $chR['received'];
	$exchange = $chR['exchange'];
	$given = $chR['given'];
	$received_name = $chR['received_name'];
	$given_name = $chR['given_name'];
	$type = $chR['type'];
	$kin = $chR['kin'];
	$chak_id = $chR['chak_id'];
	$media = $chR['media'];
}
$fetchUsers_sql = "SELECT boss_id,shop_id FROM signup WHERE id='$chR_aid'";
$fetchUsers = $conn->prepare($fetchUsers_sql);
$fetchUsers->execute();
while ($rows = $fetchUsers->fetch(PDO::FETCH_ASSOC)) {
$shop_id = $rows['shop_id'];
$boss_id = $rows['boss_id'];
}
$checki = $conn->prepare("SELECT * FROM cos_transactions WHERE post_id =:c_id");
$checki->bindParam(':c_id',$chak_id,PDO::PARAM_INT);
$checki->execute();
while ($chR = $checki->fetch(PDO::FETCH_ASSOC)) {
	$invest_per = $chR['invest_per'];
	$cos_id = $chR['cos_id'];
}
$vpsql = "SELECT name FROM costumers WHERE main_id=:sid";
$view_postso = $conn->prepare($vpsql);
$view_postso->bindParam(':sid', $cos_id, PDO::PARAM_INT);
$view_postso->execute();
while ($postsfetch = $view_postso->fetch(PDO::FETCH_ASSOC)) {
	$name = $postsfetch['name'];
}
//============calculate average of the exchange rate===================
$total_lyg = $received*$exchange;
$itake = $invest_per/100;
$haj = $given*$media;
$yui = $received-$haj;
$yux = $yui*$itake;
$hetake = $received-$yux;

	//===================select value from treasury ==========================
	$check = $conn->prepare("SELECT * FROM treasury WHERE shop_id =:chR_aid AND kind=:given_name");
	$check->bindParam(':chR_aid',$shop_id,PDO::PARAM_INT);
	$check->bindParam(':given_name',$received_name,PDO::PARAM_INT);
	$check->execute();
	$num = $check->rowCount();
	while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
		$numberi = $chR['number'];
	}
	$check = $conn->prepare("SELECT * FROM invest_treasury WHERE user_id =:chR_aid AND kind=:given_name AND name=:name");
	$check->bindParam(':chR_aid',$boss_id,PDO::PARAM_INT);
	$check->bindParam(':given_name',$received_name,PDO::PARAM_INT);
	$check->bindParam(':name', $name, PDO::PARAM_INT);
	$check->execute();
	$numvf = $check->rowCount();
	while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
		$numberx = $chR['number'];
	}
	$ghh = $numberi+$yux;
	$tyh = $numberx-$ghh;
	$hvnk = $numberx-$yux;

//==============functin of  select(buy or sell)===============
if($kin == "buy" || $kin == "بيع"){
$numbery = $numbero-$received;
$numberb = $given+$numberi;
}elseif($kin == "sell" || $kin == "شراء"){
	$numbery = $numbero-$received;
	$numberb = $numberi+$given;
}
$cash = "cash";
//==================update user information on the table =================================
	$delete_comm_sqli = "UPDATE cos_transactions SET og=:numbery WHERE post_id = :c_id";
		$delete_commi = $conn->prepare($delete_comm_sqli);
		$delete_commi->bindParam(':numbery',$delo,PDO::PARAM_INT);
		$delete_commi->bindParam(':c_id',$chak_id,PDO::PARAM_INT);
		$delete_commi->execute();
		if($num < 1){
			$iptdbsql = "INSERT INTO treasury
(user_id,shop_id,boss_id,kind,number,tyi)
VALUES
( :p_user_id, :boss_id, :shop_id, :received_name, :un, :cash)
";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':p_user_id', $sid,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':boss_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':shop_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':un', $yux,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_STR);
$insert_post_toDB->bindParam(':cash', $cash,PDO::PARAM_STR);
$insert_post_toDB->execute();
}else{
	$delete_comm_sqli = "UPDATE treasury SET number=:numbery WHERE shop_id = :p_user_id AND kind=:received_name AND tyi=:cash";
	$delete_commi = $conn->prepare($delete_comm_sqli);
	$delete_commi->bindParam(':numbery',$ghh,PDO::PARAM_INT);
	$delete_commi->bindParam(':received_name', $received_name,PDO::PARAM_INT);
	$delete_commi->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
	$delete_commi->bindParam(':cash', $cash,PDO::PARAM_STR);
	$delete_commi->execute();
}
if($numvf < 1){
  $iptdbsqli = "INSERT INTO invest_treasury
(user_id,number,kind,name)
VALUES
( :p_user_id, :numbero,:received_name, :name)
";
$insert_post_toDB = $conn->prepare($iptdbsqli);
$insert_post_toDB->bindParam(':p_user_id', $shop_id,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':numbero', $hetake,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':name', $name,PDO::PARAM_INT);
$insert_post_toDB->execute();
}else{
    $iptdbsql = "UPDATE invest_treasury SET number=:numbero WHERE kind=:received_name AND name=:name AND user_id=:p_user_id AND name=:name";
$insert_post_toDB = $conn->prepare($iptdbsql);
$insert_post_toDB->bindParam(':numbero', $hvnk,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
$insert_post_toDB->bindParam(':name', $name, PDO::PARAM_INT);
$insert_post_toDB->bindParam(':p_user_id', $boss_id,PDO::PARAM_INT);
$insert_post_toDB->execute();
}
//=============================================================
	echo "yes";

?>
