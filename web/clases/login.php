<?php
include_once '../libs/View.php';
include_once '../DB/Connection.php';
session_start();
View::header('Restaurant',["../../assets/css/estilo.css"]);
View::menuFor($_SESSION['userType'],"../../index.php","tabla.php","contact.php","login.php","logout.php","comand.php","kitchen.php","search.php");

View::login("login.php");
View::end("contact.php");

if(isset($_POST['login'])){
    $dbb = new Connection();
    $dbb->enableForeignKeys();
    $db = $dbb->getPDO();
    $query = $db->prepare("SELECT id, tipo, nombre FROM usuarios WHERE usuario = :usuario AND clave = :clave");
    if($query == false){
        print_r(array_values($db->errorInfo()));
    }
    $query->bindParam(":usuario",$_POST['username'],PDO::PARAM_STR);
    $md5Pass = md5($_POST['password']);
    $query->bindParam(":clave",$md5Pass);

    $query->execute();
    $db=null;

    $result = $query->fetch(PDO::FETCH_ASSOC);

    if($result['id']!= null){
        $_SESSION['userId'] = $result['id'];
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['userRealName'] = $result['nombre'];
        $_SESSION['userType'] = $result['tipo'];
        $_SESSION['loggedin'] = true;

        header("Location: ../../index.php");
        die();

    }
}

