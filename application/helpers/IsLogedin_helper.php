<?php
function Checklogin($baseurl){
    session_start();
    
    if(!isset($_SESSION['Username'])){
        header("location: $baseurl");
        exit;
    }
    
    // if($_SESSION['user_email_status'] == "not verified"){
    // header("location:email_verification");}
    // if($_SESSION['steps'] != "1"){
    // header('location:steps?tc=shop');
    // }
    //return $currencies_a;
}
function loginRedirect($baseurl){
    session_start();
    
    if(isset($_SESSION['Username'])){
        header("location: $baseurl");
        exit;
    }
    
    // if($_SESSION['user_email_status'] == "not verified"){
    // header("location:email_verification");}
    // if($_SESSION['steps'] != "1"){
    // header('location:steps?tc=shop');
    // }
    //return $currencies_a;
}
function CheckMailVerification(){
    session_start();
     if($_SESSION['user_email_status'] == "not verified"){
        header("location:email_verification");
        exit;
     }
    
    if($_SESSION['steps'] != "1"){
        header('location:steps?tc=shop');
        exit;
    }
}
?>
