<?php
function Pay_must(){
     // Get a reference to the controller object
     $CI = get_instance();

     // You may need to load the model if it hasn't been pre-loaded
     $CI->load->model('Comman_model');


     if ($_SESSION['admin'] != '1') {
        if ($_SESSION['admin'] != '2') {
                //include "config/connect.php";
                $sid =  $_SESSION['id'];


                $boss_id =  $_SESSION['boss_id'];

                $fetchUsers_sql = "SELECT boss_id FROM signup WHERE id='$boss_id'";
                $result=$CI->Comman_model->get_all_data_by_query($fetchUsers_sql);

                //   $fetchUsers = $conn->prepare($fetchUsers_sql);
                //   $fetchUsers->execute();
                //while ($rows = $fetchUsers->fetch(PDO::FETCH_ASSOC)) {
                foreach ($result as $rows) {
                    $gfid = $rows['boss_id'];
                }
                $yy = date('Y');
                $mm = date('m');
                $dd = date('d');
                $ghys = "$dd-$mm-$yy";
                //$uInfo = $conn->prepare("SELECT sus,expir,nex_pay FROM signup WHERE id = :ed");
                $uInfo = "SELECT sus,expir,nex_pay FROM signup WHERE id = $gfid";
                $resultCount=$CI->Comman_model->get_all_dataCounts_by_query($uInfo);
                $result=$CI->Comman_model->get_all_data_by_query($uInfo);
                //   $uInfo->bindParam(':ed',$gfid,PDO::PARAM_STR);
                //   $uInfo->execute();
                $uInfo_count = $resultCount;//$uInfo->rowCount();
                foreach($result as $uInfoRow ) {
                    $nex_pay = $uInfoRow['nex_pay'];
                    $expir = $uInfoRow['expir'];
                    $status = $uInfoRow['sus'];
                }
                $date_now = new DateTime();
                $date2 = new DateTime($nex_pay);

                if($status == "0"){
                    if($date2 <= $date_now){
                      loginRedirect(base_url()."Home/pay");
                        exit;
                    }
                }else{

                  loginRedirect(base_url()."Home/pay");
                    exit;
                }
         }
    }

}
?>
