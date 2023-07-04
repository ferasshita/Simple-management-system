<?php
function GetIdofUser(){
     // Get a reference to the controller object
     session_start();
     $CI = get_instance();
    
     // You may need to load the model if it hasn't been pre-loaded
     $CI->load->model('Comman_model');    
      
     $sid =  $_SESSION['id'];
     $shopo =  $_SESSION['shop_id'];
     $typo =  $_SESSION['type'];
     $query="";
     if($typo == "admin"){
     $query = "SELECT id FROM signup WHERE shop_id='$shopo'";
    //  $fetchUsers = $conn->prepare($fetchUsers_sql);
    //  $fetchUsers->execute();
     }else{
     $query = "SELECT id FROM signup WHERE boss_id='$sid' OR id='$sid'";
    //  $fetchUsers = $conn->prepare($fetchUsers_sql);
    //  $fetchUsers->execute();
     }



    

     
     $result=$CI->Comman_model->get_all_data_by_query($query);
     return $result;
}






?>