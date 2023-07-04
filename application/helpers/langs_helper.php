<?php
function LoadLang(){
    switch ($_SESSION['language']) {
    case 'English':
        include_once "english.php";
        break;
    case 'العربية':
        include_once "arabic.php";
        break;
    default:
        $_SESSION['language'] = "English";
        include_once "english.php";
        break;
    }
}
?>