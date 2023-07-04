<?php
include("../config/connect.php");
session_start();
$sid = $_SESSION['id'];
//==================from the function========================
$c_id = htmlentities($_POST['cid'], ENT_QUOTES);
$p_id = htmlentities($_POST['pid'], ENT_QUOTES);
$delo = "1";
$deloz = "0";
$cash = "cash";
$bank = "bank";
$inves = "أستثمار";
//===================check the user========================
$check = $conn->prepare("SELECT * FROM transactions WHERE post_id =:c_id");
$check->bindParam(':c_id',$c_id,PDO::PARAM_INT);
$check->execute();
while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
	$chR_aid = $chR['user_id'];
	$received = $chR['received'];
	$given = $chR['given'];
	$received_name = $chR['received_name'];
	$given_name = $chR['given_name'];
	$type = $chR['type'];
	$kin = $chR['kin'];
	$chak_id = $chR['chak_id'];
	$giv = $chR['giv'];
	$giva = $chR['giva'];
}

$fetchUsers_sql = "SELECT id,boss_id,shop_id FROM signup WHERE id='$chR_aid'";
$fetchUsers = $conn->prepare($fetchUsers_sql);
$fetchUsers->execute();
while ($rows = $fetchUsers->fetch(PDO::FETCH_ASSOC)) {
	$gfid = $rows['id'];
	$gfshop = $rows['shop_id'];
$gfboss = $rows['boss_id'];
 }
$check = $conn->prepare("SELECT * FROM cos_transactions WHERE post_id =:chR_aid");
$check->bindParam(':chR_aid',$chak_id,PDO::PARAM_INT);
$check->execute();
while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
	$idji = $chR['idjd'];
}
if($type == $inves){
	$check = $conn->prepare("SELECT name FROM cos_transactions WHERE post_id =:chR_aid");
	$check->bindParam(':chR_aid',$chak_id,PDO::PARAM_INT);
	$check->execute();
	while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
		$name = $chR['name'];
	}
	//===================first select from treasury==========================
	$check = $conn->prepare("SELECT * FROM invest_treasury WHERE kind=:received_name AND name=:name");
	$check->bindParam(':received_name',$received_name,PDO::PARAM_INT);
	$check->bindParam(':name',$name,PDO::PARAM_INT);
	$check->execute();
	while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
		$numbero = $chR['number'];
	}
	//===================second select from treasury==========================
	$check = $conn->prepare("SELECT * FROM invest_treasury WHERE kind=:given_name AND name=:name");
	$check->bindParam(':given_name',$given_name,PDO::PARAM_INT);
	$check->bindParam(':name',$name,PDO::PARAM_INT);
	$check->execute();
	while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
		$numberi = $chR['number'];
	}
}else{
	if($type == "transfar"){
	//===================first select from treasury==========================
	$check = $conn->prepare("SELECT * FROM treasury WHERE boss_id =:chR_aid AND kind=:received_name AND tyi=:cash ");
	$check->bindParam(':chR_aid',$gfboss,PDO::PARAM_INT);
	$check->bindParam(':received_name',$received_name,PDO::PARAM_INT);
	$check->bindParam(':cash',$cash,PDO::PARAM_INT);
	$check->execute();
	while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
		$numbero = $chR['number'];
	}
	//===================second select from treasury==========================
	$check = $conn->prepare("SELECT * FROM treasury WHERE shop_id =:chR_aid AND kind=:given_name AND wh=:idji AND tyi=:bank");
	$check->bindParam(':chR_aid',$gfshop,PDO::PARAM_INT);
	$check->bindParam(':given_name',$given_name,PDO::PARAM_INT);
	$check->bindParam(':idji',$idji,PDO::PARAM_INT);
	$check->bindParam(':bank',$bank,PDO::PARAM_INT);
	$check->execute();
	while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
		$numberi = $chR['number'];
	}

}elseif($type == "cards"){
	//=================== select from treasury==========================
	$check = $conn->prepare("SELECT * FROM treasury WHERE boss_id =:chR_aid AND kind=:received_name AND wh=:idji AND tyi=:bank");
	$check->bindParam(':chR_aid',$gfboss,PDO::PARAM_INT);
	$check->bindParam(':received_name',$received_name,PDO::PARAM_INT);
	$check->bindParam(':idji',$idji,PDO::PARAM_INT);
	$check->bindParam(':bank',$bank,PDO::PARAM_INT);
	$check->execute();
	while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
		$numbero = $chR['number'];
	}
	//=================== select from treasury==========================
	$check = $conn->prepare("SELECT * FROM treasury WHERE shop_id =:chR_aid AND kind=:given_name AND tyi=:cash");
	$check->bindParam(':chR_aid',$gfshop,PDO::PARAM_INT);
	$check->bindParam(':given_name',$given_name,PDO::PARAM_INT);
	$check->bindParam(':cash',$cash,PDO::PARAM_INT);
	$check->execute();
	while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
		$numberi = $chR['number'];
	}

}elseif($type == "chak"){
	//=================== select from treasury==========================
	$check = $conn->prepare("SELECT * FROM treasury WHERE shop_id =:chR_aid AND kind=:received_name AND wh=:idji AND tyi=:bank");
	$check->bindParam(':chR_aid',$gfshop,PDO::PARAM_INT);
	$check->bindParam(':received_name',$received_name,PDO::PARAM_INT);
	$check->bindParam(':idji',$idji,PDO::PARAM_INT);
	$check->bindParam(':bank',$bank,PDO::PARAM_INT);
	$check->execute();
	while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
		$numbero = $chR['number'];
	}
	//=================== select from treasury==========================
	$check = $conn->prepare("SELECT * FROM treasury WHERE boss_id =:chR_aid AND kind=:given_name AND tyi=:cash");
	$check->bindParam(':chR_aid',$gfboss,PDO::PARAM_INT);
	$check->bindParam(':given_name',$given_name,PDO::PARAM_INT);
	$check->bindParam(':cash',$cash,PDO::PARAM_INT);
	$check->execute();
	while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
		$numberi = $chR['number'];
	}

}elseif($type == "cash"){
	//=================== select from treasury==========================
	$check = $conn->prepare("SELECT * FROM treasury WHERE shop_id =:chR_aid AND kind=:received_name AND tyi=:cash");
	$check->bindParam(':chR_aid',$gfshop,PDO::PARAM_INT);
	$check->bindParam(':received_name',$received_name,PDO::PARAM_INT);
	$check->bindParam(':cash',$cash,PDO::PARAM_INT);
	$check->execute();
	while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
		$numbero = $chR['number'];
	}
	//=================== select from treasury==========================
	$check = $conn->prepare("SELECT * FROM treasury WHERE shop_id =:chR_aid AND kind=:given_name AND tyi=:cash");
	$check->bindParam(':chR_aid',$gfshop,PDO::PARAM_INT);
	$check->bindParam(':given_name',$given_name,PDO::PARAM_INT);
	$check->bindParam(':cash',$cash,PDO::PARAM_INT);
	$check->execute();
	while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
		$numberi = $chR['number'];
	}
}
}
	$numbery = $numbero-$received;
	$numberb = $numberi+$given;

//==================delete the transaction =================================
	$delete_comm_sql = "UPDATE transactions SET hide=:numbery WHERE post_id = :c_id";
	$delete_comm = $conn->prepare($delete_comm_sql);
	$delete_comm->bindParam(':numbery',$delo,PDO::PARAM_INT);
	$delete_comm->bindParam(':c_id',$c_id,PDO::PARAM_INT);
	$delete_comm->execute();
		$delete_comm_sqli = "UPDATE cos_transactions SET hide=:numbery WHERE post_id = :c_id";
		$delete_commi = $conn->prepare($delete_comm_sqli);
		$delete_commi->bindParam(':numbery',$delo,PDO::PARAM_INT);
		$delete_commi->bindParam(':c_id',$chak_id,PDO::PARAM_INT);
		$delete_commi->execute();
		if($type == $inves){
			//===================update your treasury=========================
				$iptdbsql = "UPDATE invest_treasury SET number=:numbery WHERE kind=:received_name AND name=:name";
			$insert_post_toDB = $conn->prepare($iptdbsql);
			$insert_post_toDB->bindParam(':numbery', $numbery,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':name',$name,PDO::PARAM_INT);
			$insert_post_toDB->execute();
			//==================================invest treasury========================
			$iptdbsqli = "UPDATE invest_treasury SET number=:numberb WHERE kind=:given_name AND name=:name";
			$insert_post_toDBi = $conn->prepare($iptdbsqli);
			$insert_post_toDBi->bindParam(':numberb', $numberb,PDO::PARAM_INT);
			$insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_INT);
			$insert_post_toDBi->bindParam(':name',$name,PDO::PARAM_INT);
			$insert_post_toDBi->execute();
			if($giv != ""){
				$check = $conn->prepare("SELECT * FROM treasury WHERE shop_id =:chR_aid AND kind=:received_name");
				$check->bindParam(':chR_aid',$gfshop,PDO::PARAM_INT);
				$check->bindParam(':received_name',$giva,PDO::PARAM_INT);
				$check->execute();
				while ($chR = $check->fetch(PDO::FETCH_ASSOC)) {
					$numgiv = $chR['number'];
				}
				$numgivcalc = $numgiv+$giv;
				$iptdbsql = "UPDATE treasury SET number=:numbery WHERE shop_id = :chR_aid AND kind=:received_name";
			$insert_post_toDB = $conn->prepare($iptdbsql);
			$insert_post_toDB->bindParam(':chR_aid', $gfshop,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':numbery', $numgivcalc,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':received_name', $giva,PDO::PARAM_INT);
			$insert_post_toDB->execute();
			}
		}else{
			if($type == "transfar"){
			//===================update your treasury=========================
				$iptdbsql = "UPDATE treasury SET number=:numbery WHERE boss_id = :chR_aid AND kind=:received_name AND tyi=:cash ";
			$insert_post_toDB = $conn->prepare($iptdbsql);
			$insert_post_toDB->bindParam(':chR_aid', $gfboss,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':numbery', $numbery,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':cash', $cash,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
			$insert_post_toDB->execute();
			//==================================LYD X========================
			$iptdbsqli = "UPDATE treasury SET number=:numberb WHERE shop_id = :chR_aid AND kind=:given_name AND wh=:idji AND tyi=:bank";
			$insert_post_toDBi = $conn->prepare($iptdbsqli);
			$insert_post_toDBi->bindParam(':chR_aid', $gfshop,PDO::PARAM_INT);
			$insert_post_toDBi->bindParam(':numberb', $numberb,PDO::PARAM_INT);
			$insert_post_toDBi->bindParam(':idji',$idji,PDO::PARAM_INT);
			$insert_post_toDBi->bindParam(':bank',$bank,PDO::PARAM_INT);
			$insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_INT);
			$insert_post_toDBi->execute();
		}elseif($type == "cards"){
			//===================update your treasury=========================
				$iptdbsql = "UPDATE treasury SET number=:numbery WHERE boss_id = :chR_aid AND kind=:received_name AND wh=:idji AND tyi=:bank";
			$insert_post_toDB = $conn->prepare($iptdbsql);
			$insert_post_toDB->bindParam(':chR_aid', $gfboss,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':numbery', $numbery,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':idji',$idji,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':bank',$bank,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
			$insert_post_toDB->execute();
			$iptdbsqli = "UPDATE treasury SET number=:numberb WHERE shop_id = :chR_aid AND kind=:given_name AND tyi=:cash";
			$insert_post_toDBi = $conn->prepare($iptdbsqli);
			$insert_post_toDBi->bindParam(':chR_aid', $gfshop,PDO::PARAM_INT);
			$insert_post_toDBi->bindParam(':cash', $cash,PDO::PARAM_INT);
			$insert_post_toDBi->bindParam(':numberb', $numberb,PDO::PARAM_INT);
			$insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_INT);
			$insert_post_toDBi->execute();
}elseif($type == "chak"){
			 //===================update your treasury=========================
				$iptdbsql = "UPDATE treasury SET number=:numbery WHERE shop_id = :chR_aid AND kind=:received_name AND wh=:idji AND tyi=:bank";
			$insert_post_toDB = $conn->prepare($iptdbsql);
			$insert_post_toDB->bindParam(':chR_aid', $gfshop,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':numbery', $numbery,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':idji', $idji,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':bank', $bank,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
			$insert_post_toDB->execute();
			$iptdbsqli = "UPDATE treasury SET number=:numberb WHERE boss_id = :chR_aid AND kind=:given_name AND tyi=:cash";
			$insert_post_toDBi = $conn->prepare($iptdbsqli);
			$insert_post_toDBi->bindParam(':chR_aid', $gfboss,PDO::PARAM_INT);
			$insert_post_toDBi->bindParam(':numberb', $numberb,PDO::PARAM_INT);
			$insert_post_toDBi->bindParam(':cash',$cash,PDO::PARAM_INT);
			$insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_INT);
			$insert_post_toDBi->execute();
		}elseif($type == "cash"){
			//===================update your treasury=========================
				$iptdbsql = "UPDATE treasury SET number=:numbery WHERE shop_id = :chR_aid AND kind=:received_name AND tyi=:cash";
			$insert_post_toDB = $conn->prepare($iptdbsql);
			$insert_post_toDB->bindParam(':chR_aid', $gfshop,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':numbery', $numbery,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':received_name', $received_name,PDO::PARAM_INT);
			$insert_post_toDB->bindParam(':cash',$cash,PDO::PARAM_INT);
			$insert_post_toDB->execute();
			$iptdbsqli = "UPDATE treasury SET number=:numberb WHERE shop_id = :chR_aid AND kind=:given_name AND tyi=:cash";
			$insert_post_toDBi = $conn->prepare($iptdbsqli);
			$insert_post_toDBi->bindParam(':chR_aid', $gfshop,PDO::PARAM_INT);
			$insert_post_toDBi->bindParam(':numberb', $numberb,PDO::PARAM_INT);
			$insert_post_toDBi->bindParam(':given_name', $given_name,PDO::PARAM_INT);
			$insert_post_toDBi->bindParam(':cash',$cash,PDO::PARAM_INT);
			$insert_post_toDBi->execute();
		}
		}
	echo"yes";

?>
