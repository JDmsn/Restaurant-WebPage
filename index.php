<?php
include_once 'web/libs/View.php';
session_start();
if(!isset($_SESSION['userId'])){
    $_SESSION['userId']="null";
}
if(!isset($_SESSION['username'])){
    $_SESSION['username']="null";
}
if(!isset($_SESSION['loggedin'])){
    $_SESSION['loggedin'] = false;
}
if(!isset($_SESSION['userType'])){
    $_SESSION['userType'] = -1;
}
View::header();
View::menuFor($_SESSION['userType'],"index.php","web/clases/tabla.php","web/clases/contact.php",
    "web/clases/login.php","web/clases/logout.php","web/clases/comand.php","web/clases/kitchen.php","web/clases/search.php");

//echo 'userid='.$_SESSION['userId'].'username='.$_SESSION['username'].'loggedin='.$_SESSION['loggedin'];
View::index("","","","","");
View::end("web/clases/contact.php","index.php");
