<?php
session_start();
$comment_content = filter_var(htmlentities($_POST['cContent']),FILTER_SANITIZE_STRING);



$_SESSION['exchange_rate'] = "$comment_content";


exit();
?>
