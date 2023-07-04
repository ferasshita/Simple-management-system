<?php
function SendEmail($subject,$to,$body){
     // Get a reference to the controller object

     $CI = get_instance();

     // You may need to load the model if it hasn't been pre-loaded
     $CI->load->model('Comman_model');
     $CI->load->library("phpmailer_library");
     $objMail = $CI->phpmailer_library->load();
     $mail = new PHPMailer();

     $mail->IsSMTP();
     $mail->SMTPAuth = true;

     $mail->Host = "mail.bumedianbm.com";
     $mail->Username = "activate@bumedianbm.com";
     $mail->Password = 'AlmaqarPos112233!!';
     $mail->From = "activate@bumedianbm.com";
     $mail->FromName = "Support team";

     $mail->AddAddress($to,"test");
     $mail->Subject = $subject;
     $mail->Body = $body;
     $mail->WordWrap = 50;
     $mail->IsHTML(true);
     $str1= "gmail.com";
     $str2=strtolower("activate@bumedianbm.com");
     If(strstr($str2,$str1))
     {
     $mail->SMTPSecure = 'tls';
     $mail->Port = 587;
     if(!$mail->Send()) {
    //  echo "Mailer Error: " . $mail->ErrorInfo;
    //  echo "<br><br> * Please double check the user name and password to confirm that both of them are correct. <br><br>";
    //  echo "* If you are the first time to use gmail smtp to send email, please refer to this link :http://www.smarterasp.net/support/kb/a1546/send-email-from-gmail-with-smtp-authentication-but-got-5_5_1-authentication-required-error.aspx?KBSearchID=137388";
        return false;
     }
     else {
        return true;
     }
     }
     else{
         $mail->Port = 25;
         if(!$mail->Send()) {
    //  echo "Mailer Error: " . $mail->ErrorInfo;
    //  echo "<br><BR>* Please double check the user name and password to confirm that both of them are correct. <br>";
        return false;
     }
     else {
        return true;
     }
     }

}
?>
